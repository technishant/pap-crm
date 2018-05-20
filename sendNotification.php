<?php
#API access key from Google API's Console
define( 'API_ACCESS_KEY', 'PA9...6Dt7b6MN8mMfZlJJM81kNfw06...KIcT5XhoPA' );
$registrationIds = $_GET['id'];
#prep the bundle
$msg = array
(
    'body' 	=> 'Body  Of Notification',
    'title'	=> 'Title Of Notification',
    'icon'	=> 'myicon',/*Default Icon*/
    'sound' => 'mySound'/*Default sound*/
);
$fields = array
(
    'to'		=> '289a11020151ec5',
    'notification'	=> $msg
);


$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);
#Send Reponse To FireBase Server
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
$result = curl_exec($ch );
curl_close( $ch );
#Echo Result Of FireBase Server
echo $result;