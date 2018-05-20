<?php

namespace app\components;
use Yii;
use yii\base\ErrorException;

include 'PapApi.class.php';

class PAPManager
{
	private $username = "nishant94goel@gmail.com";
	private $password = "yABuh31a";
	private $api_url = "http://mds.postaffiliatepro.com/scripts/server.php";
	public $session;
	public $limit;

	public function __construct($limit = 30)
	{
		$this->limit = $limit;
		$this->session = new \Pap_Api_Session($this->api_url);
		if(!$this->session->login($this->username, $this->password, \Pap_Api_Session::MERCHANT)) {
			throw new ErrorException($this->session->getMessage());
		}
	}

	public function getAffiliateList(){
		$request = new \Pap_Api_AffiliatesGrid($this->session);
		$request->setLimit(0, $this->limit);
		try {
			$request->sendNow();
		} catch (ErrorException $e) {
			throw new ErrorException("API Error : ". $e->getMessage());
		}
		$grid = $request->getGrid()->getRecordset();
		return $grid;
	}



}