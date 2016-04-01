<?php
	// SET YOUR SLACK TOKEN AND CHANNEL HERE
	$slacktoken = XX;
	$slackchannel = XX;
	
	// No timeout
	set_time_limit(0);
	
	// Begins loop to read slack channel messages
	while(1) {
		// Resets all variables at the beginning of the loop
		$message = "";
		$cmd = "";
		$output = "";
		$username = "";
		$sendtosign = "";
		$output2 = "";
		$obj = "";
		$hash = "";
		$hashmatch = "0";
		
		// Curl command that grabs the most recent message in the Slack channel
		$cmd = 'curl -s \'https://slack.com/api/channels.history?token='.$slacktoken.'&channel='.$slackchannel.'&count=1&pretty=0\' -H "Cache-Control: no-cache"';
		
		// Executes the curl command
		exec($cmd,$output);
		
		// Parses the JSON response from curl
		$obj = json_decode($output[0], true);
		
		// If a username exists, add it to the sign message.  Otherwise, just send the message.
		if(isset($obj['messages'][0]['username'])){
			$username = $obj['messages'][0]['username'].": ";
		} else {
			$username = "";
		}

		$message = $obj['messages'][0]['text'];
		
		// If this is the first time the script is run (not in a loop) then set $current variable to blank
		if(!isset($current)){
			$current = "";
		}

		// Logic to check if the last message is new and if so, display it.  If the message contains a hashtag, send it to the Twitter script otherwise send the message directly to the sign.
		if($current == $message){
			////echo "Message hasn't changed since last time\n";
			$signmessage = $username.$message;
			$signmessage = htmlspecialchars_decode($signmessage);
			$signmessage = str_replace("\"", "'", $signmessage);
			$signmessage = str_replace("â��", "'", $signmessage);
			preg_match("/#(\\w+)/", $signmessage, $hashmatch);
			
			if(isset($hashmatch[1])){
				$hash = $hashmatch[1];
				$sendtosign = 'php twitter.php '.$hash;
				exec($sendtosign,$output2);
			}

		} else {
			$signmessage = $username.$message;
			$signmessage = htmlspecialchars_decode($signmessage);
			$signmessage = str_replace("\"", "'", $signmessage);
			$signmessage = str_replace("â��", "'", $signmessage);
			preg_match("/#(\\w+)/", $signmessage, $hashmatch);
			
			if(isset($hashmatch[1])){
				$hash = $hashmatch[1];
				$sendtosign = 'php twitter.php '.$hash;
				exec($sendtosign,$output2);
			} else {
				$sendtosign = './led.pl "'.$signmessage.'"';
				exec($sendtosign,$output2);
			}

			$current = $message;
		}

		// Send message to STDOUT
		if(isset($output2[0])){
			echo $output2[0]."\n";
		}

		// Length of time to wait before new messages are checked and sent.
		sleep(90);
	}

?>
