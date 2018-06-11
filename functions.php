<?php

function enqueue_baekewiese_kaakeli_style() {
  wp_enqueue_style( 'baekewiese_kaakeli', get_stylesheet_directory_uri().'/style.css', array('kaakeli'), '1.1.2', 'all' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_baekewiese_kaakeli_style' );

?>
