<?php 
/*
Plugin Name: OO User Info 
Plugin URI: https://github.com/outsideopen/wp-user-info
Description: User information shortcodes, such as IP address, browser name and version, and platform.
Author: Alex Standke - Outside Open
Version: 1.0
Author URI: http://outsideopen.com
License: GPL2
*/

// Get browser information 
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

// [user_browser_name]
function user_browser_name() {
	$browser = getBrowser();
	return $browser['name'];
}
add_shortcode('user_browser_name', 'user_browser_name');

// [user_browser_version]
function user_browser_version() {
	$browser = getBrowser();
	return $browser['version'];
}
add_shortcode('user_browser_version', 'user_browser_version');

// [user_browser_name_and_version]
function user_browser_name_and_version() {
	$browser = getBrowser();
	return $browser['name'] . ": " . $browser['version'];
}
add_shortcode('user_browser_name_and_version', 'user_browser_name_and_version');

// [user_browser_platform]
function user_browser_platform() {
	$browser = getbrowser();
	return $browser['platform'];
}
add_shortcode('user_browser_platform', 'user_browser_platform');

// [user_ip]
function user_ip() {
	$user_ip = $_SERVER['REMOTE_ADDR'];
	return $user_ip;
}
add_shortcode('user_ip', 'user_ip');

// [user_agent]
function user_agent() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	return $user_agent;
}
add_shortcode('user_agent', 'user_agent');

// [user_host]
function user_host() {
	$user_host = $_SERVER['REMOTE_HOST'];
	return $user_host;
}
add_shortcode('user_host', 'user_host');

// [user_tld]
function user_tld() {
	$user_host = user_host();
	$split_host = explode('.', $user_host);
	$tld = implode('.', array_slice($split_host, -2, 2, true));
	return $tld;
}
add_shortcode('user_tld', 'user_tld');

// [user_browser_info]
function user_browser_info() {
	$ua=getBrowser();
	$yourbrowser= $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'];
	return $yourbrowser;
}
add_shortcode('user_browser_info', 'user_browser_info');

// [user_info]
function user_info()
{
	$browser = getBrowser();
	
	return 
	  "IP: " . user_ip() . "<br>" .
	  "Domain: " . user_tld() . "<br>" .
	  "Browser: " . user_browser_name() . "<br>" . 
	  "Platform: " . user_browser_platform();
}
add_shortcode('user_info', 'user_info');

add_filter('widget_text', 'do_shortcode');
