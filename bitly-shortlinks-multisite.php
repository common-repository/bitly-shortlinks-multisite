<?php
/*
Plugin Name: Bit.ly Shortlinks Multisite (OAuth 2 Compatible)
Version: 1.2
Plugin URI: http://wordpress.org/extend/plugins/bitly-shortlinks-multisite/
Description: This plugin replaces the default WordPress shortlinks with Bit.ly shortlinks for your single site or multisite WordPress network.
Author: Denis Lam
Author URI: http://denislam.com/
License: GPL v3

Bit.ly Shortlinks Multisite Plugin
Copyright (C) 2013, Denis Lam - d@denislam.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function denis_bitly_shortlink($url, $id, $context, $allow_slugs) {
	if ( ( is_singular() && !is_preview() ) || $context == 'post' ) {
		$url = get_permalink( $id );
		$short = get_post_meta($id, '_denis_bitlylink', true);
		
		if ( !defined('BITLY_ACCESS_TOKEN') ) {
				$short = 'You must have a generic access token. Get it here: https://bitly.com/a/oauth_apps';
		}
		else {
			$req = 'https://api-ssl.bitly.com/v3/shorten?access_token='.BITLY_ACCESS_TOKEN.'&longUrl='.$url;
			$resp = wpbitly_curl( $req );
				
			if ( is_array( $resp ) && $resp['status_code'] == 200 && isset($resp['data']['expand']) && $resp['data']['expand'][0]['long_url'] == $url )	
				
				return false;
			delete_post_meta( $id, '_denis_bitlylink' );
		}
		
		$req = 'https://api-ssl.bitly.com/v3/shorten?access_token='.BITLY_ACCESS_TOKEN.'&longUrl='.$url;
		$resp = wpbitly_curl( $req );
				
		if ( !is_wp_error( $resp ) && is_array( $resp ) && 200 == $resp['status_code'] ) {				
			$short = $resp['data']['url'];
			update_post_meta( $id, '_denis_bitlylink', $short);
		}
		return $short;
	}	
	return false;
}
add_filter( 'pre_get_shortlink', 'denis_bitly_shortlink', 99, 4 );

// Create the get_bitly global function for developers
function get_bitly($url) {
		$req = 'https://api-ssl.bitly.com/v3/shorten?access_token='.BITLY_ACCESS_TOKEN.'&longUrl='.$url;
		$resp = wpbitly_curl( $req );
				
		if ( !is_wp_error( $resp ) && is_array( $resp ) && 200 == $resp['status_code'] ) {				
			$short = $resp['data']['url'];
		}
		return $short;
}

/**
 * WP Bit.ly wrapper for cURL - this method relies on the ability to use cURL
 * or file_get_contents. If cURL is not available and allow_url_fopen is set
 * to false this method will fail and the plugin will not be able to generate
 * shortlinks.
 */

function wpbitly_curl( $url )
{
	global $wpbitly;

	if ( ! isset( $url ) )
		return false;

	if ( function_exists( 'curl_init' ) )
	{

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url );
		$result = curl_exec($ch);
		curl_close($ch);

	}
	else
	{
		$result = file_get_contents( $url );
	}

	if ( ! empty( $result ) )
		return json_decode( $result, true );

	return false;

}