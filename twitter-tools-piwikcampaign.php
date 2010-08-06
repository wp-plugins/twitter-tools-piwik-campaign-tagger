<?php
/*
Plugin Name: Twitter Tools - Piwik Campaign Tagger
Plugin URI: http://www.retosphere.de/offenenetze/wordpress-plugins/
Description: Tag URLs posted to Twitter with piwik_campaign=twitter and then shorten with bit.ly-URL shortener
Version: 0.1
Author: Reto Mantz
Author URI: http://www.retosphere.de/offenenetze/
*/

//ini_set('display_errors', '1'); ini_set('error_reporting', E_ALL);

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );


function tag_piwikcampaign($long_url) {
	/* thanks for info about the bit.ly shortening and the code to http://blog.stevieswebsite.de/2010/01/urls-kurzen-mit-php-url-shortener-apis/ */
	$tag = "";
	$long_url.="?";
	// check if our tag is the first parameter in the URL and add "?" or "&" depending on it.
	if ((strpos($long_url, "?")>0) && (empty($long_url)!==0))
		$tag .= "&";
	else
		$tag .= "?";
	$tag .= "piwik_campaign=twitter";
	$long_url .= $tag;
    $result = "";
    $handle = @fopen("http://bit.ly/api?url=".urlencode($long_url), "rb");
    if($handle){
      while (!feof($handle)) {
        $result .= fgets($handle,2000);
      }
      fclose($handle);
    }
    else{
      throw new Exception("Shortening URL failed.");
    }
    return $result;
}

// now add the tagger to the Twitter Tools API hook
add_filter('tweet_blog_post_url', 'tag_piwikcampaign');

?>