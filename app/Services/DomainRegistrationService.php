<?php

namespace App\Services;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
use Net_EPP_Client;
use Random\RandomException;

class DomainRegistrationService extends EppService
{
    const MAX_RETRIES = 3;

    /**
     * Register a new domain
     *
     * @throws Exception
     */
    public function registerDomain(
        string $domainName,
        string $extension,
        array $nameservers,
        array $registrantInfo,
        int $period = 1
    ): array {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $client = $this->getEppConnection();

                // Create contact first
                $contactId = $this->createContact($client, $registrantInfo);

                // Then register domain
                $response = $this->performDomainRegistration(
                    $client,
                    $domainName,
                    $extension,
                    $contactId,
                    $nameservers,
                    $period
                );

                $this->safeDisconnect($client);

                return $this->processDomainRegistrationResponse($response);

            } catch (Exception $e) {
                $lastException = $e;
                $attempt++;

                Log::warning("EPP Domain Registration Failed - Attempt $attempt of ".self::MAX_RETRIES, [
                    'domain' => "$domainName.$extension",
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                if (isset($client)) {
                    $this->safeDisconnect($client);
                }

                if ($attempt < self::MAX_RETRIES) {
                    sleep(pow(2, $attempt));

                    continue;
                }

                throw new Exception(
                    'Failed to register domain after '.self::MAX_RETRIES.' attempts: '.$e->getMessage(),
                    0,
                    $e
                );
            }
        }

        throw $lastException;
    }

    /**
     * Create contact for domain registration
     *
     * @throws Exception
     */
    private function createContact(Net_EPP_Client $client, array $registrantInfo): string
    {
        $contactId = 'CONT'.time().rand(1000, 9999);

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <create>
                    <contact:create xmlns:contact="urn:ietf:params:xml:ns:contact-1.0">
                        <contact:id>'.$contactId.'</contact:id>
                        <contact:postalInfo type="int">
                            <contact:name>'.htmlspecialchars($registrantInfo['name']).'</contact:name>
                            <contact:org>'.htmlspecialchars($registrantInfo['organization']).'</contact:org>
                            <contact:addr>
                                <contact:street>'.htmlspecialchars($registrantInfo['address']).'</contact:street>
                                <contact:city>'.htmlspecialchars($registrantInfo['city']).'</contact:city>
                                <contact:sp>'.htmlspecialchars($registrantInfo['state']).'</contact:sp>
                                <contact:pc>'.htmlspecialchars($registrantInfo['postal_code']).'</contact:pc>
                                <contact:cc>'.htmlspecialchars($registrantInfo['country_code']).'</contact:cc>
                            </contact:addr>
                        </contact:postalInfo>
                        <contact:voice>'.htmlspecialchars($registrantInfo['phone']).'</contact:voice>
                        <contact:email>'.htmlspecialchars($registrantInfo['email']).'</contact:email>
                    </contact:create>
                </create>
                <clTRID>'.mt_rand().mt_rand().'</clTRID>
            </command>
        </epp>';

        $response = $client->request($xml);
        $this->logEppTransaction('contact-create', $xml, $response);

        // Process response to ensure contact was created
        $this->processContactResponse($response);

        return $contactId;
    }

    /**
     * Perform domain registration
     *
     * @throws Exception
     */
    private function performDomainRegistration(
        Net_EPP_Client $client,
        string $domainName,
        string $extension,
        string $contactId,
        array $nameservers,
        int $period
    ): string {
        // Build nameserver XML
        $nsXml = '';
        foreach ($nameservers as $ns) {
            $nsXml .= "<domain:ns>{$ns}</domain:ns>";
        }

        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <create>
                    <domain:create xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                        <domain:name>'.$domainName.'.'.$extension.'</domain:name>
                        <domain:period unit="y">'.$period.'</domain:period>
                        <domain:ns>
                            '.$nsXml.'
                        </domain:ns>
                        <domain:registrant>'.$contactId.'</domain:registrant>
                        <domain:contact type="admin">'.$contactId.'</domain:contact>
                        <domain:contact type="tech">'.$contactId.'</domain:contact>
                        <domain:contact type="billing">'.$contactId.'</domain:contact>
                        <domain:authInfo>
                            <domain:pw>'.$this->generateAuthCode().'</domain:pw>
                        </domain:authInfo>
                    </domain:create>
                </create>
                <clTRID>'.mt_rand().mt_rand().'</clTRID>
            </command>
        </epp>';

        $response = $client->request($xml);
        $this->logEppTransaction('domain-create', $xml, $response);

        return $response;
    }

    /**
     * Process the domain registration response
     *
     * @throws Exception
     */
    private function processDomainRegistrationResponse(string $response): array
    {
        $xmlResponse = new DOMDocument('1.0');
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000') {
            throw new Exception("EPP Error: $message");
        }

        $domainName = $xmlResponse->getElementsByTagName('name')->item(0)->nodeValue;
        $creationDate = $xmlResponse->getElementsByTagName('crDate')->item(0)->nodeValue;
        $expiryDate = $xmlResponse->getElementsByTagName('exDate')->item(0)->nodeValue;

        return [
            'domain_name' => $domainName,
            'status' => 'registered',
            'creation_date' => $creationDate,
            'expiry_date' => $expiryDate,
            'message' => $message,
        ];
    }

    /**
     * Process contact creation response
     *
     * @throws Exception
     */
    private function processContactResponse(string $response): void
    {
        $xmlResponse = new DOMDocument('1.0');
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000') {
            throw new Exception("EPP Contact Creation Error: $message");
        }
    }

    /**
     * Generate a random authorization code for the domain
     *
     * @throws RandomException
     */
    private function generateAuthCode(): string
    {
        return 'EPP'.bin2hex(random_bytes(8));
    }
}
