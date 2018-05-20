<?php

namespace app\components;
use Yii;
use yii\base\ErrorException;

include 'Curl.php';

class CrmManager
{
	public $data;

	public function __construct($data)
	{
			$this->data = $data;
	}

	public function addContact(){
		$contact = curl_wrap("contacts", $this->data, "POST", "application/json");
		if($contact == false){
			throw new \ErrorException("Seems some problem adding to contact to CRM");
		}
		return json_decode($contact);
	}




}