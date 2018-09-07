<?php
/*
WEATHER module
powered by https://developer.yahoo.com/weather

Written by AlexB 31.08.2018

feel free to modify
*/
try {

	if(preg_match("/^\/w/", $message)){
		$text=explode(" ",$message);

		if (!isset($text[1])) { //if not set city
			$this->messages->sendMessage(['peer' => $update, 'message' => "Укажите норм город", 'parse_mode' => 'html']);
		}else{
			$BASE_URL = "http://query.yahooapis.com/v1/public/yql";
			$yql_query = 'select * from weather.forecast where woeid in (select woeid from geo.places(1) where text="'.$text[1].'")';
			$yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json&language=RU-ru";
    // Make call with cURL
			$session = curl_init($yql_query_url);
			curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
			$json = curl_exec($session);
    // Convert JSON to PHP object
			$phpObj =  json_decode($json,true);

	//if we get results
			if(isset($phpObj['query']['results']['channel']['item'])){

				$fo=$phpObj['query']['results']['channel']['item']['forecast'];


$text="<i>".$phpObj['query']['results']['channel']['location']['city'].", ".$phpObj['query']['results']['channel']['location']['country']."</i><br>"; //location
$text=$text."<b>".$fo[0]['date']."</b><br>"."От ".$fo[0]['low']."° до ".$fo[0]['low']."°<br>".$fo[0]['text']."<br>";//today
$text=$text."<b>".$fo[1]['date']."</b><br>"."От ".$fo[1]['low']."° до ".$fo[1]['low']."°<br>".$fo[1]['text']."<br>";//next day
$text=$text."<b>".$fo[2]['date']."</b><br>"."От ".$fo[2]['low']."° до ".$fo[2]['low']."°<br>".$fo[2]['text']."<br>";//next next day

			$this->messages->sendMessage(['peer' => $update, 'message' => $text, 'parse_mode' => 'html']); //Sending weather

		}else{
		//if we dont get results
			$this->messages->sendMessage(['peer' => $update, 'message' => "Не удалось найти такой город", 'parse_mode' => 'html']);

		}}
	}

} catch (Exception $e) {
	$this->messages->sendMessage(['peer' => $logpeer, 'message' => $e->getMessage(), 'parse_mode' => 'markdown']);
}
?>