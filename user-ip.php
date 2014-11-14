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
 

// Found here: http://php.net/manual/en/function.get-browser.php#101125
function getBrowser() 
{ 
    $u_agent = $_SERVER['HTTP_USER_AGENT']; 
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
    
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Internet Explorer'; 
        $ub = "MSIE"; 
    } 
    elseif(preg_match('/Firefox/i',$u_agent)) 
    { 
        $bname = 'Mozilla Firefox'; 
        $ub = "Firefox"; 
    } 
    elseif(preg_match('/Chrome/i',$u_agent)) 
    { 
        $bname = 'Google Chrome'; 
        $ub = "Chrome"; 
    } 
    elseif(preg_match('/Safari/i',$u_agent)) 
    { 
        $bname = 'Apple Safari'; 
        $ub = "Safari"; 
    } 
    elseif(preg_match('/Opera/i',$u_agent)) 
    { 
        $bname = 'Opera'; 
        $ub = "Opera"; 
    } 
    elseif(preg_match('/Netscape/i',$u_agent)) 
    { 
        $bname = 'Netscape'; 
        $ub = "Netscape"; 
    } 
    
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
    
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
    
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
    
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
} 

// shortcode [user_ip]
function display_user_ip()
{
        $user_ip = $_SERVER['REMOTE_ADDR'];
	$user_host = $_SERVER['REMOTE_HOST'];
	$browser = getBrowser();
	
	return 
	  $user_ip . "<br>" .
	  $user_host . "<br>" .
	  $browser['name'] . "<br>" . 
	  $browser['platform'];
}

add_shortcode('user_ip', 'display_user_ip');

// use shortcodes on Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

