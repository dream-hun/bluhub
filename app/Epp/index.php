<?php

/*

  This Php snippet implements the opensource PHP EPP framework Available on https://github.com/centralnic/php-epp

  It is to be copied to your web server's root

  To test the various commands, replace the $xml with your EPP command


 */

// enable to display errors - Debugging
// echo ini_get('display_errors'); if (!ini_get('display_errors')) { ini_set('display_errors', '1'); } echo ini_get('display_errors');
// Login Parameters

function epp_Client($params)
{
    // Setup include dir
    // Include EPP stuff we need
    require_once 'Net/EPP/Client.php';
    require_once 'Net/EPP/Protocol.php';

    // Grab module parameters
    // Are we using ssl?
    $use_ssl = false;
    if (isset($params['SSL']) && $params['SSL'] == 'on') {
        $use_ssl = true;
    }

    // Set certificate if we have one
    if ($use_ssl && ! empty($params['Certificate'])) {
        if (! file_exists($params['Certificate'])) {
            throw new Exception('Certificate file does not exist.');
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
        echo 'Caught exception: ', $e->getMessage(), "\n";
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
    file_put_contents(dirname(__FILE__).'/debug/response.xml', $doc->saveXML(), FILE_APPEND);

    return $client;
}

function get_epp_connection()
{
    $certpath = 'test.pem';
    $params = ['Username' => 'bluhub_rwf', 'Password' => 'CVQTsh8R64CCdt6G', 'Server' => 'registry.ricta.org.rw', 'Port' => '700', 'Certificate' => $certpath, 'SSL' => 'on'];
    try {
        return epp_Client($params);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

function search($domain_text, $extension)
{
    // Get other Extension

    $domain_string = '<domain:name>'.$domain_text.'.'.$extension.'</domain:name>';
    $additinal_tlds = ['.co.rw', '.org.rw'];

    if (count($additinal_tlds) > 0) {
        foreach ($additinal_tlds as $tld) {
            if ($tld != $extension) {
                $domain_string .= '<domain:name>'.$domain_text.$tld.'</domain:name>';
            }
        }
    }
    $client = get_epp_connection();
    // Domain Check
    $request = $client->request($xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
          <epp xmlns="urn:ietf:params:xml:ns:epp-1.0">
          <command>
          <check>
          <domain:check
          xmlns:domain="urn:ietf:params:xml:ns:domain-1.0">'
            .$domain_string.
            '</domain:check>
          </check>
          <clTRID>'.mt_rand().mt_rand().'</clTRID>
          </command>
          </epp>
          ');

    // We define our Test DOMDocument
    $xml_response = new DOMDocument('1.0');
    $xml_response->loadXML($request);

    // Process the response
    // Check the response status
    $response_code = $xml_response->getElementsByTagName('result')->item(0)->getAttribute('code');
    $msg = $xml_response->getElementsByTagName('msg')->item(0)->nodeValue;

    $domain_statuses = [];
    if ($response_code == '1000') {

        // We use xpath to search ChildElement:
        $domXPath = new DOMXPath($xml_response);
        $domXPath->registerNamespace('domain', 'urn:ietf:params:xml:ns:domain-1.0');
        $DOMNodeList_ChildElement = $domXPath->query('//domain:chkData/domain:cd/domain:name');

        // get the number of return domain checks
        $number_of_domains = $DOMNodeList_ChildElement->length;

        for ($i = 0; $i < $number_of_domains; $i++) {
            $domain = $DOMNodeList_ChildElement->item($i)->textContent;
            $is_available = $DOMNodeList_ChildElement->item($i)->getAttribute('avail') == '1' ? true : false;
            if ($i == 0) {
                $is_the_needed = true;
            } else {
                $is_the_needed = false;
            }
            array_push($domain_statuses, ['domain_name' => $domain, 'is_available' => $is_available, 'is_the_needed' => $is_the_needed, 'selling_cost' => 'Not yet implemented']);
        }
    } else {
        // Process the error response here
    }
    $response_xml = $xml_response->saveXML();
    // Save the logs
    file_put_contents('debug/response.xml', $response_xml, FILE_APPEND);
    file_put_contents('debug/domain-check-in-aroc.xml', $xml, FILE_APPEND);

    echo 'Request completed successfully';
    echo $response_xml;
    exit();
}

search($_GET['domain_text'], $_GET['extension']);

var_dump(get_epp_connection());
