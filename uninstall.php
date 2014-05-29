<?php

// If uninstall was not called from WordPress, then exit
if( !defined( 'WP_UNINSTALL_PLUGIN') )
	exit ();


// Delete all plugin related fields from the options table
delete_option( 'multimedia_feedback_business_alias' );
delete_option( 'multimedia_feedback_interface' );
delete_option( 'multimedia_feedback_button_width' );

