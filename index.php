<?php
//file_get_contents('php://input')で送られてきたものを取得
$data = json_decode(file_get_contents('php://input'), true);
///var/www/data/sugikawa/debug.log以下にログを表示。
//tail -f /var/www/data/sugikawa/debug.logでリアルタイムに監視できる
error_log(print_r($data,true),3,"/var/www/data/sugikawa/debug.log");

//slackの発言がBotの時以外にAPIを実行
//ここで、Bot以外と指定しないと、Botが自分の発言に反応してしまい、無限ループになってしまう。
if(!isset($data["event"]["bot_id"])){
  $slackApiKey         = "token";
  $slackUrl      = "https://slack.com/api/chat.postMessage?token=" . $slackApiKey;

  // $channel             = "smv_2019"; //送信するチャンネル名
  $channel             = "smv_2019";
  $userName            = "newapp"; //bot名
  $asUser              = false; //これがtrueだと、bot名はこれを作成したユーザー名になる

  //ランダムでBotが発言する
  $array_1 = array("1","2","3","4","5","6","7","8","9","10","11","12");
  $randkey_1 = array_rand( $array_1, 1 );
  $execText = $array_1[$randkey_1];

  // $execText            = "hello world！";

  $slackParams = array(
      'channel' => $channel,
      'text' => $execText,
      'username' => $userName,
      'as_user' => $asUser
  );

  $slackUrl = $slackUrl . "&" . http_build_query($slackParams, "", "&");
  $slackCommentHeaders = array('Content-Type: application/x-www-form-urlencoded','Authorization: xoxb-sample-authorizationkey');
  $slack_context = array('http' => array(
      'method' => 'POST',
      'header' => $slackCommentHeaders,
      'ignore_errors' => true
  ));
  file_get_contents($slackUrl, false, stream_context_create($slack_context));
}
