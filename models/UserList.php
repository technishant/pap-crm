<?php

namespace app\models;

use Yii;
use yii\base\ErrorException;
use yii\base\Model;
use app\components\PAPManager;
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class UserList extends Model
{
	public function getUserList()
	{
		try {
			$papManager = new PAPManager(1000);
			$list = $papManager->getAffiliateList();
		} catch (ErrorException $ex) {
			echo $ex->getMessage(); die;
		}
		$data = [];
		for ($i = 0; $i <= ($list->getSize() - 1); $i++) {
			$data[] = $list->get($i)->getAttributes();
		}
		$dataProvider = [];
		$i = 0;
		foreach ($data as $key => $val) {
			$dataProvider[$i]['u_id'] = $key;
			$dataProvider[$i]['id'] = $val['id'];
			$dataProvider[$i]['unique_identifier'] = $val['refid'];
			$dataProvider[$i]['name'] = $val['firstname'] . ' ' . $val['lastname'];
			$dataProvider[$i]['email'] = $val['username'];
			$dataProvider[$i]['city'] = $val['data4'];
			$dataProvider[$i]['state'] = $val['data5'];
			$dataProvider[$i]['country'] = $val['data6'];
			$dataProvider[$i]['pincode'] = $val['data7'];
			$dataProvider[$i]['phone'] = $val['data8'];
			$i++;
		}
		return $dataProvider;
	}


	public function getUserDetail($id)
	{

		$session = new \Pap_Api_Session("https://thewellnessenterprise.com/pap/scripts/server.php");

		if(!@$session->login("thewellnessenterprise@gmail.com", "water2018", \Pap_Api_Session::MERCHANT)) {
			die("Cannot login. Message: " . $session->getMessage());
		}

		//----------------------------------------------

		$request = new \Pap_Api_AffiliatesGrid($session);

		$request->setLimit(0, 30);
//----------------------------------------------

		try {
			$request->sendNow();
		} catch (Exception $e) {
			die("API call error: " . $e->getMessage());
		}

//----------------------------------------------

		$grid = $request->getGrid();
//----------------------------------------------

		$recordset = $grid->getRecordset();

		$data[] = $recordset->get($id)->getAttributes();


		$dataProvider = [];
		echo "<pre>";
		print_r($data);
		die;
		$i = 0;
		foreach ($data as $val) {
			$dataProvider['id'] = $val['id'];
			$dataProvider['unique_identifier'] = $val['refid'];
			$dataProvider['name'] = $val['firstname'] . ' ' . $val['lastname'];
			$dataProvider['email'] = $val['username'];
			$dataProvider['city'] = $val['data4'];
			$dataProvider['state'] = $val['data5'];
			$dataProvider['country'] = $val['data6'];
			$dataProvider['pincode'] = $val['data7'];
			$dataProvider['phone'] = $val['data8'];
			$i++;
		}
		return $dataProvider;
	}

	public function getTransaction()
	{
		$session = new \Pap_Api_Session("https://thewellnessenterprise.com/pap/scripts/server.php");
		if(!@$session->login("thewellnessenterprise@gmail.com", "water2018", \Pap_Api_Session::MERCHANT)) {
			die("Cannot login. Message: " . $session->getMessage());
		}

		//----------------------------------------------

		//----------------------------------------------
// get recordset of list of transactions
		$request = new \Pap_Api_TransactionsGrid($session);

// set filter
		//$request->addFilter('dateinserted', \Gpf_Data_Filter::DATERANGE_IS, \Gpf_Data_Filter::RANGE_THIS_YEAR);
		//if you have refid

		$request->addFilter('userid', \Gpf_Data_Filter::EQUALS, '971b1ec3');

// list here all columns which you want to read from grid
		$request->addParam('columns', new \Gpf_Rpc_Array(array(array('id'), array('transid'), array('campaignid'), array('orderid'), array('commission'), array('userid'))));

		//$request->addFilter('orderid', \Gpf_Data_Filter::EQUALS, 'ORD_123');

		$request->setLimit(0, 100);

		$request->setSorting('orderid', false);

		$request->sendNow();

		$grid = $request->getGrid();

		$recordset = $grid->getRecordset();


		echo "Total number of records: " . $grid->getTotalCount() . "<br>";

		echo "Number of returned records: " . $recordset->getSize() . "<br>";

// iterate through the records
		foreach ($recordset as $rec) {
			echo 'Transaction OrderID: ' . $rec->get('orderid') . ', Commission: ' . $rec->get('commission') . '<br>';
		}
//----------------------------------------------
// in case there are more than 100 records total
// we should load and display the rest of the records
// in the cycle
		$totalRecords = $grid->getTotalCount();

		$maxRecords = $recordset->getSize();

		if($maxRecords > 0) {
			$cycles = ceil($totalRecords / $maxRecords);
			for ($i = 1; $i < $cycles; $i++) {
				// now get next 100 records
				$request->setLimit($i * $maxRecords, $maxRecords);
				$request->sendNow();
				$recordset = $request->getGrid()->getRecordset();
				// iterate through the records
				foreach ($recordset as $rec) {
					echo 'Transaction OrderID: ' . $rec->get('orderid') . ', Commission: ' . $rec->get('commission') . '<br>';
				}
			}
		}

		die();

		//return $dataProvider;


	}
}
