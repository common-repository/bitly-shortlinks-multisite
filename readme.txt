=== Bit.ly Shortlinks Multisite (Uses OAuth 2 API) ===
Contributors: spiffyd
Donate link: http://denislam.com/
Tags: bitly, shortlink, shortlinks, url shortener, multisite, oauth2
Requires at least: 3.0
Tested up to: 3.5.2
Stable tag: 1.2
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl.html

This plugin replaces the default WordPress shortlinks with Bit.ly shortlinks for your single site or multisite WordPress network.

== Description ==

WordPress generates shortlinks for your posts and pages. By default it uses the `?p=` with the post ID added to it, but if you have a rather long domain name this isn't very useful. If you use [Bit.ly](http://bit.ly), this plugin will help you replace the shortlink WordPress generates with a proper Bit.ly shortlink. 

This plugin uses the latest Bit.ly OAuth 2 draft specification API instead of its deprecated V3 API and enables you to use generic access token to automatically enable Bit.ly shortlinks in your entire multisite network without the need for each site user to have to tinker with any settings or authentication configurations.

== Installation ==

1. Upload the `plugin` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Insert this code to define your bit.ly generic access token in your `wp-config.php` at the end right above "That's all, stop editing! Happy blogging.":

`define('BITLY_ACCESS_TOKEN', 'your_generic_access_token_goes_here');`

You can get your generic access token here: [https://bitly.com/a/oauth_apps](https://bitly.com/a/oauth_apps)

= Multisite Installation =
Install and set up your access token the same way as a single site installation but make sure you Network Activate the plugin.

= How To Call Bitly Shortlinks In Your Templates =
I've included the function `get_bitly($url)` to help developers easily shorten URLs in their templates.

*Example Usage:*

`<?php
	$url = 'http://YourLongURL.com';
	echo get_bitly($url); // display your shortened URL
?>`

== Screenshots ==

1. Screenshot of Bit.ly shortlinks in action.

== Changelog ==

= 1.2 =
* Fixed bug that occurs when `WP_ DEBUG` is on (Thanks to Luke Childs for the bug find.)

= 1.1 =
* Added `get_bitly()` function.

= 1.0 =
* Initial release.