<?php

/*
SCREEN module
powered by s-shot.ru

Written by AlexB 31.08.2018

feel free to modify
*/

try {

	if(preg_match("/^\/screen/", $message)){
		$text=explode(" ",$message);
$d=false;
if(isset($update['message']['reply_to_msg_id'])){
			if($update["_"]=="updateNewChannelMessage"){
$get=$this->channels->getMessages(['channel' => $update["message"]["to_id"],'id' => [$update['message']['reply_to_msg_id']], ]);
		}else{
$get=$this->messages->getMessages(['id' => [$update['message']['reply_to_msg_id']], ]);}
		$domain=$get['messages'][0]['message'];
		$i=$get['messages'][0];
}elseif(isset($text[1])){
	$domain=$text[1];
	$i=$update['message'];
	$d=true;
}


		if (!isset($domain)) {
			$this->messages->sendMessage(['peer' => $update, 'message' => "Укажите норм домен", 'parse_mode' => 'html']);
		}else{
			if(isset($i['entities']) AND !isset($text[1])){
				if($i['entities'][0]["_"]=="messageEntityUrl"){
					$domain=substr($domain, $i['entities'][0]["offset"],$i['entities'][0]["length"]);
					$d=true;
				}elseif($i['entities'][1]["_"]=="messageEntityUrl"){
					$domain=substr($domain, $i['entities'][1]["offset"],$i['entities'][1]["length"]);
					$d=true;
				}else{
$d=false;
				}
			}
			if ($d) {
				$main=file_get_contents("http://mini.s-shot.ru/1024x768/700/jpeg/?".urlencode($domain));
				$s="s".rand(1,200).".jpg";
				file_put_contents($s, $main);

$this->messages->sendMedia([
    'peer' => $update,
    'media' => [
        '_' => 'inputMediaUploadedPhoto',
        'file' => $s
    ],
    'message' => '_Скриншот_',
    'parse_mode' => 'Markdown'
]);

				unlink($s);
 }else{
 	$this->messages->sendMessage(['peer' => $update, 'message' => "Сайт не найден", 'parse_mode' => 'html']);
 }

			}
		}

} catch (Exception $e) {
	$this->messages->sendMessage(['peer' => $update, 'message' => "__Возможно такого сайта нет__", 'parse_mode' => 'markdown']);
}
?>