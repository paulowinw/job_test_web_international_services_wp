<?php
/*
Plugin Name: WIS HTML Shortcode Plugin
Description: Adds a custom shortcode to generate HTML output.
Version: 1.0
Author: Paulo Lopes
*/

function wis_html_shortcode() {
    $output = '<div class="wis-html"><h2>Hello, world!</h2><p>This is some custom HTML generated by a shortcode in the WIS HTML Shortcode Plugin.</p></div>';
    return $output;
}
add_shortcode( 'wis_html_shortcode', 'wis_html_shortcode' );