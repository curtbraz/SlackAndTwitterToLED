# Description
This script watches a Slack channel and posts the most recent message to a scrolling Betabrite LED sign until terminated.  If the most recent message contains a Hashtag, it searches Twitter and posts the most recent Twitter post matching that Hashtag on a specified interval or until another message in the Slack channel is posted.  Run it in a Cron job on a Raspberry Pi for zero maintenance and place it anywhere!  Big thanks to Abraham Williams for the twitteroauth scripts!

# Instructions
1) Obtain Slack API Information (https://api.slack.com/)

2) Obtain Twitter API Information (https://apps.twitter.com/)

3) Edit "run.php" to include Slack Token and Channel

4) Edit "twitter.php" to include API Tokens and Keys

5) Modify "led.pl" near the bottom to customize LED colors and scrolling styles

6) Execute "run.php" and ptionally set it up in a keep-alive Cron job (* * * * * ps aux |grep slack |grep -v "grep"; if [ $? -eq 1 ]; then php /home/pi/SlackAndTwitterToLED/run.php; fi")


Enjoy! :)
