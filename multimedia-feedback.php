<?php
/**
 * @Multimedia Customer Feedback
 */
/*
Plugin Name: Multimedia Customer Feedback
Plugin URI: http://www.grabimo.com
Description: Inspire your customers to provide candid feedback in video, audio, photo, and text formats. Make it easy for business to reward customers for sharing experience, increase customer satisfaction, and improve product or service quality. 
Version: 1.2.0
Author: Grabimo
Author URI: http://www.grabimo.com
License: GPLv2 or later
*/

/*  Copyright 2014  Grabimo  (email : admin@grabimo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// --- add settings -----------------------------------------
// http://codex.wordpress.org/Function_Reference/add_options_page
add_action('admin_menu', 'multimedia_feedback_menu');

function multimedia_feedback_menu() {
	add_options_page('Multimedia Customer Feedback', '<img style="position:relative;top:4px" src="//www.grabimo.com/download/grabimo16x16.png"/>&nbsp;Feedback', 'manage_options','multimedia_feedback_settings_page', 'multimedia_feedback_settings_callback_function' );
}

function multimedia_feedback_settings_callback_function() {
	echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
	echo '<h2>Multimedia Customer Feedback</h2>';
	echo '<form action="options.php" method="POST">';
	settings_fields( 'multimedia_feedback_settings_group' );
  do_settings_sections( 'multimedia_feedback_settings_page' );
  submit_button();
  echo '</form>';
	echo '</div>';
}

// http://codex.wordpress.org/Settings_API
// Add a section, field and settings during admin_init
function multimedia_feedback_settings() {
	// Add the section to General settings so we can add our fields to it
	add_settings_section(
		'multimedia_feedback_setting_section',
		'',
		'multimedia_feedback_setting_section_callback_function',
		'multimedia_feedback_settings_page'
	);
	
	// business alias created after signup at https://www.grabimo.com
	add_settings_field(
		'multimedia_feedback_business_alias',
		'Business Alias',
		'multimedia_feedback_setting_field_alias_callback_function',
		'multimedia_feedback_settings_page',
		'multimedia_feedback_setting_section'
	);
	
	// "Call for feedback" button width
	add_settings_field(
		'multimedia_feedback_interface',
		'User Interface',
		'multimedia_feedback_setting_field_interface_callback_function',
		'multimedia_feedback_settings_page',
		'multimedia_feedback_setting_section'
	);		
	
	// "Call for feedback" button width
	add_settings_field(
		'multimedia_feedback_button_width',
		'Button Width',
		'multimedia_feedback_setting_field_width_callback_function',
		'multimedia_feedback_settings_page',
		'multimedia_feedback_setting_section'
	);	
 	
	// Register our setting so that $_POST handling is done for us and
	// our callback function just has to echo the <input>
 	register_setting( 'multimedia_feedback_settings_group', 'multimedia_feedback_business_alias' );
 	register_setting( 'multimedia_feedback_settings_group', 'multimedia_feedback_interface' );		
 	register_setting( 'multimedia_feedback_settings_group', 'multimedia_feedback_button_width' );	
} 
add_action( 'admin_init', 'multimedia_feedback_settings' );
 
// section callback function
function multimedia_feedback_setting_section_callback_function() {
	//echo '<p>Multimedia Feedback</p>';
}

// field callback function: business Alias
function multimedia_feedback_setting_field_alias_callback_function() {
	// create the setting field for multimedia customer feedback
	$value = get_option( 'multimedia_feedback_business_alias', 'example' );
	$output = '<input type="text" id="multimedia_feedback_business_alias" name="multimedia_feedback_business_alias" value="' . $value . '" /> <p class="description">To create your alias, sign up at <a href="https://www.grabimo.com">https://www.grabimo.com</a>.';
	if ($value == 'example') {
		$output = $output . ' The alias, "example", is for demo only.</p>';
	} else {
		$output = $output . '</p>';
	}
	
	echo $output;
}

// field callback function: UI selection, button or embed
function multimedia_feedback_setting_field_interface_callback_function() {
	// get default UI option
	$value = get_option( 'multimedia_feedback_interface', 'button' );
	$output = '<input name="multimedia_feedback_interface" type="radio" value="button" ' . checked('button', $value, false) . '>Button with popup lightbox&nbsp;&nbsp;&nbsp;<input name="multimedia_feedback_interface" type="radio" value="embed" ' . checked( 'embed', $value, false) . '>iFrame embedding';
	
	echo $output;
}

// field callback function: button width
function multimedia_feedback_setting_field_width_callback_function() {
	// create the setting field for multimedia customer feedback
	$value = get_option( 'multimedia_feedback_button_width', 200 );
	$output = '<input type="text" id="multimedia_feedback_button_width" name="multimedia_feedback_button_width" value="' . $value . '" /> <p class="description">Set the width (pixels) of the feedback button, e.g., 200.</p>';
	
	echo $output;
}

// --- add javascrit and CSS file on webpage head ------------
function multimedia_feedback_files() {
	// add Javscript
	wp_enqueue_script( 'multimedia-feedback-js-file', '//www.grabimo.com/download/mf.js' );	
	
	// CSS file
	wp_enqueue_style( 'multimedia-feedback-css-file', '//www.grabimo.com/download/mf.css' );
}
add_action( 'wp_enqueue_scripts', 'multimedia_feedback_files' );

// --- create the short code, the Feedback button will dispay on webpage ---
function multimedia_feedback_short_code() {
	// retrieve business alias from the admin setting page, after registration at https://www.grabimo.com 
	$businessAlias = get_option( 'multimedia_feedback_business_alias', 'example' );
	$width = intval(get_option( 'multimedia_feedback_button_width', 200 ));
	$interface = get_option( 'multimedia_feedback_interface', 'button' );
	
	if ('button' == $interface) {
		// show the feedback image button, after click it, the iframe lightbox pop up without leaving your site
		return '<input type="image" style="width:' . $width . 'px" src="//www.grabimo.com/download/multimedia-feedback.png" id="grabimo-feedback" onclick="grab_multimedia_feedback.startFlow(\'' . $businessAlias . '\')">';
	} else {
		return  '<iframe src="//www.grabimo.com/app/addGig.html?alias=' . $businessAlias . '&compact" title="Multimedia feedback" scrolling="no" style="border: none; overflow: hidden; height: 518px; width: 678px; max-width: 100%"></iframe>';
	}
}
add_shortcode('grab-multimedia-feedback', 'multimedia_feedback_short_code');   

// --- add action links for on the Plugins page ---
function multimedia_feedback_admin_action_links($links, $file) {
	static $my_plugin;
	if (!$my_plugin) {
		$my_plugin = plugin_basename(__FILE__);
	}
	
	if ($file == $my_plugin) {
		$settings_link = '<img style="position:relative;top:4px" src="//www.grabimo.com/download/grabimo16x16.png"/>&nbsp;<a href="'. get_admin_url(null, 'options-general.php?page=multimedia_feedback_settings_page') .'">Settings</a>';
		array_unshift($links, $settings_link);
	}
	
	return $links;
}

add_filter('plugin_action_links', 'multimedia_feedback_admin_action_links', 10, 2);