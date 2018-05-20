<?php

/**
 * Agile CRM \ Curl Wrap
 *
 * The Curl Wrap is the entry point to all services and actions.
 *
 * @author    Agile CRM developers <Ghanshyam>
 */


# Enter your domain name , agile email and agile api key
define("AGILE_DOMAIN", "nishant");  # Example : define("domain","jim");
define("AGILE_USER_EMAIL", "nishant@metadesignsolutions.com");
define("AGILE_REST_API_KEY", "v89hoqkd70l0f7c7svu4hpmncu");

function curl_wrap($entity, $data, $method, $content_type)
{
	if($content_type == NULL) {
		$content_type = "application/json";
	}

	$agile_url = "https://" . AGILE_DOMAIN . ".agilecrm.com/dev/api/" . $entity;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_UNRESTRICTED_AUTH, true);
	switch ($method) {
		case "POST":
			$url = $agile_url;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			break;
		case "GET":
			$url = $agile_url;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			break;
		case "PUT":
			$url = $agile_url;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			break;
		case "DELETE":
			$url = $agile_url;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			break;
		default:
			break;
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		"Content-type : $content_type;", 'Accept : application/json'
	));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, AGILE_USER_EMAIL . ':' . AGILE_REST_API_KEY);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	$output = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	if($httpcode == 200){
		return $output;
	}
	return false;
}