<?php

	/**
	* Plugin Name: UVjagtPro - Load more post list 
	* Description: This plugin creates a page when plugin is activated.
	* Author: Kim Nyegaard Andreasen
	* Version: 1.0
	*/

	include "pagetemplater.php";

	/*######################################################################################################
	########################################################################################################
	########################################################################################################
	########################################################################################################
	############################### Parsing variables to script ############################################
	########################################################################################################
	########################################################################################################
	########################################################################################################
	########################################################################################################*/

	function wordpress_my_load_more_scripts() 
	{
		//gets the global query var object
		//global $wp_query; 

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	 	$query_args = array(
			'posts_per_page' 	=> 3, // Value "-1" displays all products in feed	
            'post_type' 		=> 'artikler',
            'paged'				=> $paged
        );

        $wp_query = new WP_Query( $query_args);

		if (!isset( $wp_query ))
    	{
    		debug_to_console("Query is NULL!");

    		return;
    	}

		// In most cases it is already included on the page and this line can be removed
		wp_enqueue_script('jquery');
	 
		// register our main script but do not enqueue it yet
		wp_register_script( 'my_loadmore', plugins_url( 'myloadmore.js', __FILE__ ), array('jquery') );

		$postArray = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ), // WordPress AJAX
			'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
			'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
			'max_page' => $wp_query->max_num_pages,
			'results' => $wp_query->found_posts
		);

		// now the most interesting part
		// we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
		// you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()

		wp_localize_script( 'my_loadmore', 'load_more_params', $postArray);
	 
	 	wp_enqueue_script( 'my_loadmore' );

	}

	add_action( 'wp_enqueue_scripts', 'wordpress_my_load_more_scripts' );

	/*######################################################################################################
	########################################################################################################
	########################################################################################################
	########################################################################################################
	############################### AJAX handler ###########################################################
	########################################################################################################
	########################################################################################################
	########################################################################################################
	########################################################################################################*/

	add_action( 'wp_ajax_load_more', 'load_more_ajax_handler' ); // wp_ajax_{action}
	add_action( 'wp_ajax_nopriv_load_more', 'load_more_ajax_handler' ); // wp_ajax_nopriv_{action}

	function load_more_ajax_handler()
	{
		if (isset( $wp_query ))
    	{
    		debug_to_console("Query is not NULL!");
    	}

		// prepare our arguments for the query
		$args = json_decode( stripslashes( $_POST['query'] ), true );

		debug_to_console($args);

		$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
		$args['post_status'] = 'publish';

		// it is always better to use WP_Query but not here
		query_posts( $args );
	 
		if( have_posts() ) :

			echo "We have post(s)!";
	
			// run the loop
			while( have_posts() ): the_post();
	 
				echo "A post!";
				// look into your theme code how the posts are inserted, but you can use your own HTML of course
				// do you remember? - my example is adapted for Twenty Seventeen theme
				// get_template_part( 'arkiv', 'standard' );
				// for the test purposes comment the line above and uncomment the below one
				the_title();
	 
	 
			endwhile;
	 
		endif;

		debug_to_console("Die!");

		wp_die(); // here we exit the script and even no wp_reset_query() required!
	} 
?>