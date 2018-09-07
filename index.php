<?php
/*
Alex B's Bot
https://github.com/AlexBSoft/aleha

Writen in Russia. With bugs and vodka.

Note IT IS TEST VERSION.

*/
//Madeline check
if (!file_exists('madeline.php')) {
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
include 'madeline.php';


// it is better than webhook
class EventHandler extends \danog\MadelineProto\EventHandler
{

    public function onUpdateNewChannelMessage($update)
    {
        $this->onUpdateNewMessage($update);
    }
    public function onUpdateNewMessage($update)
    {
        if (isset($update['message']['out']) && $update['message']['out']) {
            return;
        }
        $res = json_encode($update, JSON_PRETTY_PRINT);
        if ($res == '') {
            $res = var_export($update, true);
        }

if(isset($update['message']['message'])){
        $message=json_decode($res, TRUE)['message']['message'];

        include ('config.php'); //CONFIG GOES HERE

        if($full_log){$this->messages->sendMessage(['peer' => $logpeer, 'message' => $res, 'reply_to_msg_id' => $update['message']['id'], 'entities' => [['_' => 'messageEntityPre', 'offset' => 0, 'length' => strlen($res), 'language' => 'json']]]);}
try {



  if ($handle = opendir('commands')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            include ("commands/$file");
        }
    }
    closedir($handle);
}
        
           
        } catch (\danog\MadelineProto\RPCErrorException $e) {
            if($e->getMessage()=="USER_IS_BLOCKED"){

            }else{
            $this->messages->sendMessage(['peer' => $logpeer, 'message' => $e->getCode().': '.$e->getMessage().PHP_EOL.$e->getTraceAsString()]);
            }
        }

        
    }
    }
    

}
$MadelineProto = new \danog\MadelineProto\API('bot.madeline');
$MadelineProto->start();
$MadelineProto->setEventHandler('\EventHandler');
$MadelineProto->loop(-1); //-1 for async

?>