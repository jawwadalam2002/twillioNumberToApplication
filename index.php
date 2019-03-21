<?php 
use SignalWire\Rest\Client;
require('./vendor/autoload.php');
/*Space URL*/
$_ENV['SIGNALWIRE_API_HOSTNAME'] = "extreme.signalwire.com";

/*Space URL*/
putenv("SIGNALWIRE_API_HOSTNAME=extreme.signalwire.com");

/*Application Sid*/
define("APPLICATION_SID","1beff68a-ce9d-4e4b-adcd-026e77e56522d");

/*Project Key*/
$project = '41da2150-44e2-4bb7-a15a-d350a5ee34584';

/*Project Token*/
$token = 'PT3adf9434545622cf2707f51c15d0ca421286c3e3c531897884';

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

