<?php

/**
 * Plugin Name: Extra sentence plugin
 * Description: Plugin for adding an extra sentence
 * Version: 1.0.0
 * Author: MX
 */
 
 add_filter('the_content', 'addExtraSentenceToPost');
 
 function addExtraSentenceToPost($content){
	if(is_single() && is_main_query()){// is_page()
		return $content."<p>Hello from MX</p>";
	}
	return $content;
 }