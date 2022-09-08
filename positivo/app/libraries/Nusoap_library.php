<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/nusoap/lib/nusoap.php";

class Nusoap_library
{
//$api_url,$service,$proxyhost,$proxyport,$proxyusername,$proxypassword,$params

   function soaprequest($method,$api,$params)
	{

	    if ($api->api_url != '' && $api->service != '' && count($params) > 0)
	    {

	       	$client = new nusoap_client(
	       		$api->api_url,
	       		$api->service,
	       	    $api->proxyhost,
	       		$api->proxyport,
	       		$api->username,
	       		$api->password);

			//Capture result soap with parameters and error result
			$result = $client->call($method, $params, '', '', false, true);
			$err = $client->getError();
			//Array, result and states
			$soapmsg = array(
				'Request'	=> $client->request,
				'Response'	=> $client->response,
				'Debug'		=> $client->debug_str,
				'Result'	=> $result,
				'Error'		=> $err);

			//return array, result and states
				return $soapmsg;



	    }
	}


}