<?php
$access_token = 'xCdh5IflTKFJ9UZCisDTRg0itxVVQhObx8jub3RGM9gItBPdOVil+DYJznFtX3cJzOw8WvhP3rkRHrJuJmOU19dVIDhzzg35f9Q0abblFhDpDR405+7M2rFKAj+/1PW1irIeUkiCUIPkRndfcrqqywdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
$string = file_get_contents("/cus.json");
$json_a = json_decode($string, true);

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);


//$data = '' ;
foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        echo "$key:\n";
    } else {
		//$data = $key  ;
        echo "$key => $val\n";
    }
}



// Validate parsed JSON data
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {

		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {

			
			// Get text sent
			$text = $event['message']['text'];

			// Get user id
			$user_id = $event['source']['userId'];




			
			#message to send 
			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			if (strcmp($text,'test') == 0){
				
				$messages = [
				'type' => 'text',
				'text' => 'Do you want to test!!!'
				];


			}
			// Build message to reply back
			else if(strcmp($text,'get userid') == 0) {
				$messages = [
					'type' => 'text',
					'text' => $user_id
				];
			}else if(strcmp($text,'get content') == 0) {
				$messages = [
					'type' => 'text',
					'text' => $content
				];
			}else if(strcmp($text,'zzz') == 0 || strcmp($text,'...') == 0){
				$messages = [
					'type' => 'sticker',
					'text' => $text,
					'packageId' => '1',
					'stickerId' => '1'
				];
				
			}else{
				$messages = [
					'type' => 'text',
					'text' => $text
				];
			}
			
			




			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			echo $result . "\r\n";
		}
	}
}
echo "OK test" . $events ;
