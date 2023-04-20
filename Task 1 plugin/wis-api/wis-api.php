<?php

/**
 * Plugin Name: WIS API
 * Description: Adds a custom widget to display data from WIS' webservice.
 * Version: 1.0
 * Author: Paulo Lopes
 */

// Register the widget
function wis_api_widget_register() {
    register_widget( 'WIS_Api_Widget' );
}
add_action( 'widgets_init', 'wis_api_widget_register' );

// Define the widget class
class WIS_Api_Widget extends WP_Widget {
    // Constructor
    function __construct() {
        parent::__construct(
            'wis_api_widget',
            __( 'WIS API Widget', 'text_domain' ),
            array( 'description' => __( 'Adds a custom widget to display data from web international services.', 'text_domain' ) )
        );
    }

    // Widget output
    function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        echo '<div class="wis-api-widget">';
        $this->get_marvel_character(1009368);
        echo '</div>';

        echo $args['after_widget'];
    }

    // Below code in case of configure the widget
    // Widget settings form
    function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : '';

        echo '<p>';
        echo '<label for="' . $this->get_field_id( 'title' ) . '">' . __( 'Title:', 'text_domain' ) . '</label>';
        echo '<input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '" type="text" value="' . esc_attr( $title ) . '">';
        echo '</p>';
    }

    // Widget settings update
    function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';

        return $instance;
    } 

    function get_marvel_character($characterId) {
        $publicKey = '4cc4b61aeaa09da63f8c79284d80fc60';
        $privateKey = '182613e79a32539ce249aab8b2b34e0619b351e2';

        // Generate a timestamp
        $timestamp = time();

        // Generate the hash
        $hash = md5($timestamp . $privateKey . $publicKey);

        //Params for geo-targeting to send to the webservice
        //It can be providede by another plugin
        $latitude = -10120123;
        $longitude = 101202102;

        // Set up the API endpoint URL with your API key, timestamp, and hash
        $url = 'https://gateway.marvel.com/v1/public/characters/' . $characterId . '?apikey=' . $publicKey . '&ts=' . $timestamp . '&hash=' . $hash;

        // Make a request to the API using the WordPress HTTP API
        $response = wp_remote_get($url);
    
        // Parse the response JSON
        $data = json_decode(wp_remote_retrieve_body($response));

        // Extract the desired information, such as the character's name and thumbnail image URL
        $name = $data->data->results[0]->name;
        $thumbnailUrl = $data->data->results[0]->thumbnail->path . '.' . $data->data->results[0]->thumbnail->extension;
    
        // Display the information and image in your WordPress page or post
        echo '<h2>' . $name . '</h2>';
        echo '<img src="' . $thumbnailUrl . '">';
    }
}