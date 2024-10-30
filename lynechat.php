<?php

/**
 * Plugin Name:       Lynechat
 * Description:       Embed real time chat on your web page
 * Version:           1.0.0
 * Author:            Lynechat
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */


 /**
 * Activate the plugin.
 */
function lynechat_activate() { 
    
}

 /**
 * Deactivate the plugin.
 */
function lynechat_deactivate() { 
    
}

function add_lynechat_scripts_header() {
    wp_enqueue_style( 'lynechat-embed', "https://lynechat.com/css/embed.css" );
}
add_action( 'wp_head', 'add_lynechat_scripts_header' );

 
function add_lynechat_scripts_body() {
    $url = get_option( 'lynechat_url_text' );
    $btnText = get_option( 'lynechat_button_text' ) ? get_option( 'lynechat_button_text' ) : null;
    $btnImgUrl = get_option( 'lynechat_button_image' );
    $btnCss = get_option( 'lynechat_button_css' ) ? get_option( 'lynechat_button_css' ) : null;

    if($btnCss) {
        wp_add_inline_style("lynechat-embed", ".lynechat-btn-user-override-css {".$btnCss."}");
    } else {
        wp_add_inline_style("lynechat-embed", ".lynechat-btn-user-override-css {}");
    }

    if($btnImgUrl) {
        wp_add_inline_style("lynechat-embed", '.lynechat-chat-btn {
            content: "";
            background-image: url('.$btnImgUrl.');
            background-repeat: no-repeat;
            background-size: 54px;
            background-position: 0px center;
            background-color: transparent !important;
            display: block;
            text-indent: -9999em;
            position: absolute;
            bottom: 0px;
            right: 0px;
            width: 54px;
            height: 54px;
        }'); 
    }
    
    if($url) {
        wp_enqueue_script("lynechat-embed", "https://lynechat.com/js/embed.js");
        wp_add_inline_script("lynechat-embed", 'initLynechat("'.$url.'", "'.$btnText.'", "lynechat-btn-user-override-css");');
    }
}

add_action( 'wp_body_open', 'add_lynechat_scripts_body' );

function lynechat_settings_page_content() {
    echo '<div class="wrap">
	<h1>Lynechat Settings</h1>
	<form method="post" action="options.php">';
 
		settings_fields( 'lynechat_field_settings' ); // settings group name
		do_settings_sections( 'lynechat-settings-slug' ); // just a page slug
		submit_button();
 
    echo '</form></div>';
}

function lynechat_settings_page() {
    // Add the menu item and page
    $page_title = 'Lynechat Settings Page';
    $menu_title = 'Lynechat';
    $capability = 'manage_options';
    $slug = 'lynechat_settings';
    $callback ='lynechat_settings_page_content';
    $icon = 'dashicons-admin-plugins';
    $position = 100;

    add_options_page( $page_title, $menu_title, $capability, $slug, $callback, 2 );
}

add_action( 'admin_menu', 'lynechat_settings_page' );

function lynechat_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit( wp_redirect( admin_url( 'options-general.php?page=lynechat_settings' ) ) );
    }
}
add_action( 'activated_plugin', 'lynechat_activation_redirect' );


function lynechat_register_setting(){
 
	register_setting(
		'lynechat_field_settings', // settings group name
		'lynechat_url_text', // option name
		'sanitize_text_field' // sanitization function
    );
    
    register_setting(
		'lynechat_field_settings', // settings group name
		'lynechat_button_text', // option name
		'sanitize_text_field' // sanitization function
    );
    
    register_setting(
		'lynechat_field_settings', // settings group name
		'lynechat_button_image', // option name
		'sanitize_text_field' // sanitization function
    );
    
    register_setting(
		'lynechat_field_settings', // settings group name
		'lynechat_button_css', // option name
		'sanitize_text_field' // sanitization function
	);
 
	add_settings_section(
		'some_settings_section_id', // section ID
		'', // title (if needed)
		'', // callback function (if needed)
		'lynechat-settings-slug' // page slug
	);
 
	add_settings_field(
		'lynechat_url_text',
		'Lyne url',
		'lynechat_text_field_html', // function which prints the field
		'lynechat-settings-slug', // page slug
		'some_settings_section_id', // section ID
		array(
			'label_for' => 'lynechat_url_text',
			'class' => '', // for <tr> element
		)
    );
    
    add_settings_field(
		'lynechat_button_text',
		'Button Text',
		'lynechat_button_text_html', // function which prints the field
		'lynechat-settings-slug', // page slug
		'some_settings_section_id', // section ID
		array(
			'label_for' => 'lynechat_button_text',
			'class' => '', // for <tr> element
		)
    );
    
    add_settings_field(
		'lynechat_button_image',
		'Button Image url',
		'lynechat_button_image_html', // function which prints the field
		'lynechat-settings-slug', // page slug
		'some_settings_section_id', // section ID
		array(
			'label_for' => 'lynechat_button_image',
			'class' => '', // for <tr> element
		)
    );
    
    add_settings_field(
		'lynechat_button_css',
		'Button CSS',
		'lynechat_button_css_html', // function which prints the field
		'lynechat-settings-slug', // page slug
		'some_settings_section_id', // section ID
		array(
			'label_for' => 'lynechat_button_css',
			'class' => '', // for <tr> element
		)
	);
 
}
 
function lynechat_text_field_html(){
 
	$value = get_option( 'lynechat_url_text' );
 
	printf(
        '<input type="text" id="lynechat_url_text" name="lynechat_url_text" value="%s" required />
        <h5>Format: https://lynechat.com/app/widget/details/{username}/{linkTitle}</h5>
        ',
		esc_attr( $value )
	);
 
}

function lynechat_button_text_html(){
 
	$value = get_option( 'lynechat_button_text' );
 
	printf(
        '<input type="text" id="lynechat_button_text" name="lynechat_button_text" value="%s" />
        <h5>Note: Default text is "Chat with us!"</h5>
        ',
		esc_attr( $value )
	);
 
}

function lynechat_button_image_html(){
 
	$value = get_option( 'lynechat_button_image' );
 
	printf(
        '<input type="text" id="lynechat_button_image" name="lynechat_button_image" value="%s" />
        <h5>Note: Button image field will override button text</h5>
        ',
		esc_attr( $value )
	);
 
}

function lynechat_button_css_html(){
 
	$value = get_option( 'lynechat_button_css' );
 
	printf(
        '<textarea id="lynechat_button_css" name="lynechat_button_css" rows="4" cols="50" > %s </textarea>
        <h5>Note: Button css will override both - button text and image and Button css should be valid css properties, separated by semicolon(;)</h5>
        ',
		esc_attr( $value )
	);
 
}

add_action( 'admin_init',  'lynechat_register_setting' );

if ( isset( $_GET['settings-updated'] ) ) {
    //form has been submitted
}

register_activation_hook( __FILE__, 'lynechat_activate');

register_deactivation_hook( __FILE__, 'lynechat_deactivate');
