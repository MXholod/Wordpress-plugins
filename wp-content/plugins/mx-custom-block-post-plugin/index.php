<?php

/**
 * Plugin Name: Custom block post plugin
 * Description: This plugin is for interaction with a post content
 * Version: 1.0.0
 * Author: MX
 */
 
 if(!defined('ABSPATH')) exit; // Exit if accessed directly
 
 class MxCustomBlockPostPlugin{
	function __construct(){
		//add_action('enqueue_block_editor_assets', array($this, 'adminAssets'));
		add_action('init', array($this, 'adminAssets'));
	}
	
	function adminAssets(){
		//Try to load JS file
		//wp_enqueue_script('mxcustomblock', plugin_dir_url(__FILE__).'build/index.js', array('wp-blocks', 'wp-element'));
		wp_register_script('mxcustomblock', plugin_dir_url(__FILE__).'build/index.js', array('wp-blocks', 'wp-element'));
		register_block_type("myplugin/mx-custom-block-post-plugin", array(
			//Telling Wordpress which JS file to load
			'editor_script' => 'mxcustomblock',
			'render_callback' => array($this, 'generateHTML')
		));
	}
	function generateHTML($attributes){
		//return "<p>Banana color is <span>". $attributes['bananaColor'] ."</span></p>
			//<p>Kiwi color is always <span>". $attributes['kiwiColor'] ."</span></p>";
		ob_start();?>
		<p>Banana color is <span><?php echo esc_html($attributes['bananaColor']); ?></span>
		<br />
		Kiwi color is always <span><?php echo esc_html($attributes['kiwiColor']); ?></span></p>
		<?php return ob_get_clean();
	}
 }
 
 $mxCustomBlockPostPlugin = new MxCustomBlockPostPlugin();