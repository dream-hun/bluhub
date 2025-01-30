<?php

namespace App\Services;

use DOMDocument;
use Exception;
use Illuminate\Support\Facades\Log;
use Net_EPP_Client;

class DomainTransferService extends EppService
{
    const MAX_RETRIES = 3;

    /**
     * Initiate a domain transfer
     *
     * @throws Exception
     */
    public function initiateDomainTransfer(
        string $domainName,
        string $authCode,
        array $registrantInfo,
        int $period = 1
    ): array {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $client = $this->getEppConnection();

                // First create contact for the new registrant
                $contactId = $this->createContact($client, $registrantInfo);

                // Then request the transfer
                $response = $this->performTransferRequest(
                    $client,
                    $domainName,
                    $authCode,
                    $contactId,
                    $period
                );

                $this->safeDisconnect($client);

                return $this->processTransferResponse($response);

            } catch (Exception $e) {
                $lastException = $e;
                $attempt++;

                Log::warning("EPP Domain Transfer Failed - Attempt {$attempt} of ".self::MAX_RETRIES, [
                    'domain' => $domainName,
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
                    'Failed to initiate domain transfer after '.self::MAX_RETRIES.' attempts: '.$e->getMessage(),
                    0,
                    $e
                );
            }
        }

        throw $lastException;
    }

    /**
     * Check transfer status
     */
    public function checkTransferStatus(string $domainName): array
    {
        try {
            $client = $this->getEppConnection();
            $response = $this->performTransferQuery($client, $domainName);
            $this->safeDisconnect($client);

            return $this->processTransferQueryResponse($response);
        } catch (Exception $e) {
            Log::error('Transfer status check failed', [
                'domain' => $domainName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Create contact for domain transfer
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

        return $contactId;
    }

    /**
     * Perform domain transfer request
     *
     * @throws Exception
     */
    private function performTransferRequest(
        Net_EPP_Client $client,
        string $domainName,
        string $authCode,
        string $contactId,
        int $period
    ): string {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <transfer op="request">
                    <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                        <domain:name>'.$domainName.'</domain:name>
                        <domain:period unit="y">'.$period.'</domain:period>
                        <domain:authInfo>
                            <domain:pw>'.htmlspecialchars($authCode).'</domain:pw>
                        </domain:authInfo>
                    </domain:transfer>
                </transfer>
                <extension>
                    <domain-ext:transfer xmlns:domain-ext="urn:ietf:params:xml:ns:domain-ext-1.0">
                        <domain-ext:registrant>'.$contactId.'</domain-ext:registrant>
                    </domain-ext:transfer>
                </extension>
                <clTRID>'.mt_rand().mt_rand().'</clTRID>
            </command>
        </epp>';

        $response = $client->request($xml);
        $this->logEppTransaction('domain-transfer-request', $xml, $response);

        return $response;
    }

    /**
     * Query transfer status
     *
     * @throws Exception
     */
    private function performTransferQuery(Net_EPP_Client $client, string $domainName): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <transfer op="query">
                    <domain:transfer xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                        <domain:name>'.$domainName.'</domain:name>
                    </domain:transfer>
                </transfer>
                <clTRID>'.mt_rand().mt_rand().'</clTRID>
            </command>
        </epp>';

        $response = $client->request($xml);
        $this->logEppTransaction('domain-transfer-query', $xml, $response);

        return $response;
    }

    /**
     * Process transfer request response
     *
     * @throws Exception
     */
    private function processTransferResponse(string $response): array
    {
        $xmlResponse = new DOMDocument('1.0');
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000' && $responseCode !== '1001') {
            throw new Exception("EPP Error: $message");
        }

        $trStatus = $xmlResponse->getElementsByTagName('trStatus')->item(0)->nodeValue;
        $reID = $xmlResponse->getElementsByTagName('reID')->item(0)->nodeValue;
        $reDate = $xmlResponse->getElementsByTagName('reDate')->item(0)->nodeValue;
        $acID = $xmlResponse->getElementsByTagName('acID')->item(0)->nodeValue;
        $acDate = $xmlResponse->getElementsByTagName('acDate')->item(0)->nodeValue;
        $exDate = $xmlResponse->getElementsByTagName('exDate')->item(0)->nodeValue;

        return [
            'status' => $trStatus,
            'requesting_registrar' => $reID,
            'request_date' => $reDate,
            'action_registrar' => $acID,
            'action_date' => $acDate,
            'expiry_date' => $exDate,
            'message' => $message,
        ];
    }

    /**
     * Process transfer query response
     *
     * @throws Exception
     */
    private function processTransferQueryResponse(string $response): array
    {
        $xmlResponse = new DOMDocument('1.0');
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000') {
            throw new Exception("EPP Error: $message");
        }

        $trStatus = $xmlResponse->getElementsByTagName('trStatus')->item(0)->nodeValue;

        return [
            'status' => $trStatus,
            'message' => $message,
        ];
    }

    private function safeDisconnect(Net_EPP_Client $client) {}
}
