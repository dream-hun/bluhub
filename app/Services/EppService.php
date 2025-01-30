<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Exception;
use Net_EPP_Client;

class EppService
{
    private array $params;
    private ?Net_EPP_Client $client = null;
    private const MAX_RETRIES = 3;

    public function __construct()
    {
        require_once base_path('app/Epp/Net/EPP/Client.php');
        require_once base_path('app/Epp/Net/EPP/Protocol.php');
        $this->params = [
            'Username' => config('epp.username'),
            'Password' => config('epp.password'),
            'Server' => config('epp.server'),
            'Port' => config('epp.port'),
            'Certificate' => config('epp.certificate_path'),
            'SSL' => config('epp.ssl_enabled', 'on'),
        ];
    }

    /**
     * @throws Exception
     */
    public function checkDomainAvailability(string $domainText, string $extension): array
    {
        $attempt = 0;
        $lastException = null;

        while ($attempt < self::MAX_RETRIES) {
            try {
                $client = $this->getEppConnection();
                $domainString = $this->buildDomainString($domainText, $extension);
                $response = $this->performDomainCheck($client, $domainString);
                return $this->processDomainCheckResponse($response);
            } catch (Exception $e) {
                $lastException = $e;
                $attempt++;
                if ($attempt >= self::MAX_RETRIES) {
                    throw new Exception("Failed after " . self::MAX_RETRIES . " attempts: " . $e->getMessage(), 0, $e);
                }
                sleep(1); // Wait before retry
                
                // Force new connection on retry
                $this->disconnectClient();
            }
        }
        
        throw $lastException ?? new Exception("Unknown error occurred during domain check");
    }

    /**
     * @throws Exception
     */
    protected function getEppConnection(): Net_EPP_Client
    {
        try {
            if ($this->client !== null && $this->client->socket) {
                return $this->client;
            }

            $useSSL = ($this->params['SSL'] === 'on');
            $context = null;

            if ($useSSL && ! empty($this->params['Certificate'])) {
                if (! file_exists($this->params['Certificate'])) {
                    throw new Exception('Certificate file does not exist.');
                }

                $context = stream_context_create([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                    ],
                ]);

                stream_context_set_option($context, 'ssl', 'local_cert', $this->params['Certificate']);
            }

            $client = new Net_EPP_Client;
            $timeout = config('epp.timeout', 60); // Use config value with 60s fallback
            
            $client->connect(
                $this->params['Server'],
                $this->params['Port'],
                $timeout,
                $useSSL,
                $context
            );

            $loginXml = $this->buildLoginXml();
            $response = $client->request($loginXml);

            $this->logEppTransaction('login', $loginXml, $response);
            
            $this->client = $client;
            return $client;
        } catch (Exception $e) {
            $this->logEppTransaction('connection-error', 'Connection attempt', $e->getMessage());
            throw new Exception('Failed to establish EPP connection: ' . $e->getMessage(), 0, $e);
        }
    }

    private function buildDomainString(string $domainText, string $extension): string
    {
        $domainString = "<domain:name>$domainText.$extension</domain:name>";
        $additionalTlds = ['.co.rw', '.org.rw'];

        foreach ($additionalTlds as $tld) {
            if ($tld !== $extension) {
                $domainString .= "<domain:name>$domainText$tld</domain:name>";
            }
        }

        return $domainString;
    }

    /**
     * @throws Exception
     */
    private function performDomainCheck(Net_EPP_Client $client, string $domainString): string
    {
        try {
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
                <command>
                    <check>
                        <domain:check xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
                            '.$domainString.'
                        </domain:check>
                    </check>
                    <clTRID>'.mt_rand().mt_rand().'</clTRID>
                </command>
            </epp>';

            $response = $client->request($xml);
            $this->logEppTransaction('domain-check', $xml, $response);

            return $response;
        } catch (Exception $e) {
            $this->logEppTransaction('domain-check-error', $xml ?? 'XML preparation failed', $e->getMessage());
            throw new Exception('Failed to process domain check: ' . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @throws Exception
     */
    private function processDomainCheckResponse(string $response): array
    {
        $xmlResponse = new DOMDocument('1.0');
        $xmlResponse->loadXML($response);

        $responseCode = $xmlResponse->getElementsByTagName('result')->item(0)->getAttribute('code');
        $message = $xmlResponse->getElementsByTagName('msg')->item(0)->nodeValue;

        if ($responseCode !== '1000') {
            throw new Exception("EPP Error: $message");
        }

        $domainStatuses = [];
        $domXPath = new DOMXPath($xmlResponse);
        $domXPath->registerNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $domainElements = $domXPath->query('//domain:chkData/domain:cd/domain:name');

        foreach ($domainElements as $index => $element) {
            $domainStatuses[] = [
                'domain_name' => $element->textContent,
                'is_available' => $element->getAttribute('avail') === '1',
                'is_primary' => $index === 0,
                'selling_cost' => 'Not yet implemented',
            ];
        }

        return $domainStatuses;
    }

    private function buildLoginXml(): string
    {
        return '
        <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
            <command>
                <login>
                    <clID>'.$this->params['Username'].'</clID>
                    <pw>'.$this->params['Password'].'</pw>
                    <options>
                        <version>1.0</version>
                        <lang>en</lang>
                    </options>
                    <svcs>
                        <objURI>urn:ietf:params:xml:ns:domain-1.0</objURI>
                        <objURI>urn:ietf:params:xml:ns:contact-1.0</objURI>
                    </svcs>
                </login>
            </command>
        </epp>';
    }

    protected function logEppTransaction($type, $request, $response): void
    {
        try {
            $logPath = storage_path('logs/epp');
            if (! file_exists($logPath)) {
                mkdir($logPath, 0755, true);
            }

            $timestamp = now()->format('Y-m-d_H-i-s');
            file_put_contents("$logPath/$type-request-$timestamp.xml", $request);
            file_put_contents("$logPath/$type-response-$timestamp.xml", $response);
        } catch (Exception $e) {
            // Silently fail logging - we don't want logging issues to affect the main functionality
            report($e);
        }
    }

    /**
     * Safely disconnect the EPP client
     */
    private function disconnectClient(): void
    {
        try {
            if ($this->client !== null && $this->client->socket) {
                $this->client->disconnect();
            }
        } catch (Exception $e) {
            // Log but don't throw - this is cleanup code
            $this->logEppTransaction('disconnect-error', 'Disconnect attempt', $e->getMessage());
        } finally {
            $this->client = null;
        }
    }

    public function __destruct()
    {
        $this->disconnectClient();
    }
}
