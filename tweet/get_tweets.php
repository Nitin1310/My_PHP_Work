<?php
require_once('twitter_proxy.php');
// Twitter OAuth Config options
$oauth_access_token = '1689976796-Puk4MT1LZUmnwHgi84By0PftJSSyNc22Z5HtICw';
$oauth_access_token_secret = 'MLVOqsHXHPRT82nP7jxMUN24HzzBBxsA3HcPbsADJbjEn';
$consumer_key = 'dhV0OHKe2HnQuU1SbxurUywOu';
$consumer_secret = 'iITcqLrfo9rgH2pI3zrGKTjNBoOGqULf5VZfFvIZbnCYkvy4ai';
$user_id = '16085390';
$screen_name = 'nittwt';
$count = 5;
$twitter_url = 'statuses/user_timeline.json';
$twitter_url .= '?user_id=' . $user_id;
$twitter_url .= '&screen_name=' . $screen_name;
$twitter_url .= '&count=' . $count;
// Create a Twitter Proxy object from our twitter_proxy.php class
$twitter_proxy = new TwitterProxy(
	$oauth_access_token,			// 'Access token' on https://apps.twitter.com
	$oauth_access_token_secret,		// 'Access token secret' on https://apps.twitter.com
	$consumer_key,					// 'API key' on https://apps.twitter.com
	$consumer_secret,				// 'API secret' on https://apps.twitter.com
	$user_id,						// User id (http://gettwitterid.com/)
	$screen_name,					// Twitter handle
	$count							// The number of tweets to pull out
);
// Invoke the get method to retrieve results via a cURL request
$tweets = $twitter_proxy->get($twitter_url);
echo $tweets;
?>