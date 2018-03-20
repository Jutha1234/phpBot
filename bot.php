<?php
$access_token = 'xCdh5IflTKFJ9UZCisDTRg0itxVVQhObx8jub3RGM9gItBPdOVil+DYJznFtX3cJzOw8WvhP3rkRHrJuJmOU19dVIDhzzg35f9Q0abblFhDpDR405+7M2rFKAj+/1PW1irIeUkiCUIPkRndfcrqqywdB04t89/1O/w1cDnyilFU=';

// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);

$userid = getUserId();
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

			echo "$user_id";

			// Get replyToken
			$replyToken = $event['replyToken'];

			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => $text
			];


			// Build message to reply back
			if (strcmp($text,'get userid') == 0) {
				$messages = [
					'type' => 'text',
					'text' => $user_id
				];
			}

			if (strcmp($text,'get content') == 0) {
				$messages = [
					'type' => 'text',
					'text' => $content
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
echo "OK" . $userid;
