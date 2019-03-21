<?php 
use SignalWire\Rest\Client;
require('./vendor/autoload.php');
/*Space URL*/
$_ENV['SIGNALWIRE_API_HOSTNAME'] = "example.signalwire.com";

/*Space URL*/
putenv("SIGNALWIRE_API_HOSTNAME=example.signalwire.com");

/*Application Sid*/
define("APPLICATION_SID","xxxxxxxx-xxxxx-xxxx-xxxx-xxxxxxxxx");

/*Project Key*/
$project = 'xxxxxxxxx-xxxxxxx-xxxxxx-xxxxx-xxxxx';

/*Project Token*/
$token = 'PTxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

/*Numbers File Name With Path*/
$fileName="numbers.txt";

$client = new Client($project, $token);


function updateNumber($client,$appSid,$numberSid){
	try{
		$incoming_phone_number = $client->incomingPhoneNumbers($numberSid)->update(
			array(
				"SmsApplicationSid" => $appSid
			)
		);
		if($incoming_phone_number->smsApplicationSid==$appSid)
			return true;
		return false;
	}catch(Exception $ex){
		return false;
	}
}

function getNumberSid($client,$number){
	$incomingPhoneNumbers = $client->incomingPhoneNumbers->read(
		array(
			"PhoneNumber"=>$number
		)
	);
	return $incomingPhoneNumbers[0]->sid;
}

$file = fopen($fileName,"r");
$data=fread($file,filesize($fileName));
fclose($file);
$arrNumber=explode("\n",$data);
$sno=1;
foreach($arrNumber as $strNumber){
	$numberSid=getNumberSid($client,"+".$strNumber);
	if($numberSid)
		$boolNumberUpdate=updateNumber($client,APPLICATION_SID,$numberSid);
	echo "line =".$sno." Number = +".$strNumber." Number Sid =".$numberSid." Is Updated ? ".$boolNumberUpdate."\n";
	$sno++;
}

