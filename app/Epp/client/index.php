<?php

/*

This Php snippet implements the opensource PHP EPP framework Available on https://github.com/centralnic/php-epp

It is to be copied to your web server's root

To test the various commands, replace the $xml with your EPP command


*/

// enable to display errors - Debugging
// echo ini_get('display_errors'); if (!ini_get('display_errors')) { ini_set('display_errors', '1'); } echo ini_get('display_errors');

// Login Parameters
$certpath = dirname(__FILE__).'/test.pem';
$params = ['Username' => 'iroc_registrar', 'Password' => 'Acc123456', 'Server' => 'ote.coccaregistry.org', 'Port' => '700', 'Certificate' => $certpath, 'SSL' => 'on'];
try {
    $client = epp_Client($params);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

// Domain Check
$request = $client->request($xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
   <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
     <command>
       <check>
         <domain:check
          xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">
           <domain:name>musya.iroc</domain:name>
           <domain:name>musya.ote</domain:name>
           <domain:name>cools.ote</domain:name>
         </domain:check>
       </check>
       <clTRID>ABC-12345</clTRID>
     </command>
   </epp>
');

// Parse XML result
$doc = new DOMDocument;
$doc->loadXML($request);

$coderes = $doc->getElementsByTagName('result')->item(0)->getAttribute('code');
$msg = $doc->getElementsByTagName('msg')->item(0)->nodeValue;

// Check results
if ($coderes != '1000') {

    echo 'Code ('.$coderes.') '.$msg;
}
print_r($doc->saveXML());
// logs
file_put_contents('../debug/domain-check-aroc-out.xml', $doc->saveXML(), FILE_APPEND);
file_put_contents('../debug/domain-check-in-aroc.xml', $xml, FILE_APPEND);

function epp_Client($params)
{
    // Setup include dir

    // Include EPP stuff we need
    require_once '../Net/EPP/Client.php';
    require_once '../Net/EPP/Protocol.php';

    // Grab module parameters

    // Are we using ssl?
    $use_ssl = false;
    if (isset($params['SSL']) && $params['SSL'] == 'on') {
        $use_ssl = true;
    }

    // Set certificate if we have one
    if ($use_ssl && ! empty($params['Certificate'])) {
        if (! file_exists($params['Certificate'])) {
            return PEAR_Error('Certificate file does not exist');
        }

        // Create SSL context
        // $context = stream_context_create();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]
        );

        stream_context_set_option($context, 'ssl', 'local_cert', $params['Certificate']);
    }
    try {
        // Create EPP client
        $client = new Net_EPP_Client;
        // Connect
        $res = $client->connect($params['Server'], $params['Port'], 60, $use_ssl, $context);

    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

    // Perform login
    $request = $client->request($xml = '
<epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
	<command>
		<login>
			<clID>'.$params['Username'].'</clID>
			<pw>'.$params['Password'].'</pw>
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
</epp>
');

    $doc = new DOMDocument;
    $doc->preserveWhiteSpace = false;
    $doc->loadXML($request);
    file_put_contents(dirname(__FILE__).'/debug/login-request.xml', $xml, FILE_APPEND);
    file_put_contents(dirname(__FILE__).'/debug/login-response.xml', $doc->saveXML(), FILE_APPEND);

    return $client;
}
