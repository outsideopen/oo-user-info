<?php 
/*
Plugin Name: User IP
Plugin URI: http://adresseip.vpndock.com/plugin-adresse-ip-utilisateur-wordpress/
Description: Show User IP address on public page, post or sidebar widget with a shortcode.
Author: VPN Dock
Version: 1.0.001
Author URI: http://vpndock.com/
License: GPL2
*/

 
if(is_admin())
	{
		// add FAQ link on plugin page
		function user_ip_faq_link($links)
		{ 
			$faq_link = '<a href="http://wordpress.org/plugins/user-ip/faq/" target="_blank">FAQ</a>'; 
			//array_unshift($links, $faq_link);
			array_push($links, $faq_link); 
			return $links; 
		}
		
		$plugin = plugin_basename(__FILE__); 
		add_filter("plugin_action_links_$plugin", 'user_ip_faq_link' );
	}
 

// shortcode [user_ip]
function display_user_ip()
{
        $user_ip = $_SERVER['REMOTE_ADDR'];
	$user_host = $_SERVER['REMOTE_HOST'];
	return $user_ip . '<br>' . $user_host;
}

add_shortcode('user_ip', 'display_user_ip');

// use shortcodes on Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

