<?php
	require "twitteroauth-0.6.2/autoload.php";
	use AbrahamTwitterOAuthTwitterOAuth;
	
	// Listens for a hashtag as an argument from the Slack script
	$hashtag = $argv[1];
	
	// ENTER TWITTER API INFO HERE
	define('CONSUMER_KEY', 'ENTER YOUR CONSUMER KEY HERE!');
	define('CONSUMER_SECRET', 'ENTER YOUR CONSUMER SECRET HERE!');
	define('ACCESS_TOKEN', 'ENTER YOUR ACCESS TOKEN HERE!');
	define('ACCESS_TOKEN_SECRET', 'ENTER YOUR ACCESS TOKEN HERE!');
	function search(array $query){
		$toa = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, ACCESS_TOKEN, ACCESS_TOKEN_SECRET);
		return $toa->get('search/tweets', $query);
	}

	// Passes the hashtag from the Slack script as a search parameter for Twitter and grabs the most recent Twitter post that matches.
	$query = array(  "q" => "#".$hashtag,  "f" => "tweets",  "lang" => "en",  "count" => 1,  "result_type" => "recent",);
	
	// Optionally set lat/long coordinates and radius for Tweets in your area
	#  "geocode" => "39.86276,-86.38439,10mi",
	$results = search($query);
	
	// Cleans up Tweets and appends user name to message for sign.
	foreach ($results->statuses as $result) {
		$tweet = $result->user->name . " (".$result->user->screen_name.") : " . $result->text;
		#$tweet = $result->text;
		$tweet = htmlspecialchars_decode($tweet);
		$tweet = "\"".str_replace("\"", "'", $tweet)."\"";
		$tweet = str_replace("â��", "'", $tweet);
		
		// Sends tweet to directly to the LED sign via the led script
		$command = './led.pl '.$tweet;
		exec($command);
	}

?>
