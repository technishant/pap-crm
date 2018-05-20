<?php
ini_set("display_errors",1);
/**
 * Created by PhpStorm.
 * User: aniroodh
 * Date: 02/05/18
 * Time: 4:18 PM
 */
include 'PapApi.class.php';

$session = new Pap_Api_Session("https://thewellnessenterprise.com/pap/scripts/server.php");

if(!@$session->login("thewellnessenterprise@gmail.com", "water2018", Pap_Api_Session::MERCHANT)) {
    die("Cannot login. Message: ".$session->getMessage());
}
/*$session = new Pap_Api_Session("http://demo.postaffiliatepro.com/scripts/server.php"
);
if(!$session->login("merchant@example.com","demo")) {
    die("Cannot login. Message: ".$session->getMessage());
}*/

//----------------------------------------------
// get recordset with list of affiliates
$request = new Pap_Api_AffiliatesGrid($session);
// sets limit to 30 rows, offset to 0 (first row will start)
$request->setLimit(0, 30);
//----------------------------------------------
// send request
try {
    $request->sendNow();
} catch(Exception $e) {
    die("API call error: ".$e->getMessage());
}

//----------------------------------------------
// request was successful, get the grid result
$grid = $request->getGrid();
//----------------------------------------------
// get recordset from the grid
$recordset = $grid->getRecordset();
echo 'Total number of affiliates: '.$grid->getTotalCount().'<br>';
echo 'Number of affiliates returned in result: '.$recordset->getSize();

echo "<pre>";print_r($recordset);die;
// iterate through the records
foreach($recordset as $rec) {
    echo 'Affiliate name: '.$rec->get('firstname').' '.$rec->get('lastname').'<br>';
}
//echo "<pre>";print_r($session);die;