<?php

/**
 * Plugin Name: Word count plugin
 * Description: This is a word count plugin
 * Version: 1.0.0
 * Author: MX
 * Text Domain: wcpdomain
 * Domain Path: /languages
 */
 
 class MXWordCountPlugin{
	function __construct(){
		//Start an action. array(object, method)
		add_action('admin_init', array($this, 'registerFieldsSettings'));
		add_action('admin_menu', array($this, 'wordCountSettings'));
		add_filter('the_content', array($this, 'ifWrap'));
		add_action('init', array($this, 'languages'));
	}
	function languages(){
		load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)).'/languages');
	}
	function ifWrap($content){
		//Main Query and Single Blog Post Page
		if( (is_main_query() AND is_single()) AND (get_option('wcp_wordcount', '1') OR get_option('wcp_charactercount', '1') OR get_option('wcp_readtime', '1')) ){
			return $this->createHTML($content);
		}
		return $content;
	}
	function createHTML($content){
		$html = '<h3>'.esc_html(get_option('wcp_headline', 'Post statistics')).'</h3>'; 
		//We need wordcount and readtime that is why we get it once
		if((get_option('wcp_wordcount','1') == '1') OR (get_option('wcp_readtime','1') == '1')){
			$wordsArray = explode(' ', strip_tags($content));
			$wordCount = 0;
			foreach($wordsArray as $word){
				if(mb_strlen($word) > 1){
					$wordCount++;
				}
			}
			//$wordCount = str_word_count(strip_tags($content), 0, $lettersUkr);
		}
		//Information for word counting
		if((get_option('wcp_wordcount','1') == '1')){
			// esc_html__() - protects malicious code, __() - it doesn't
			$html .= "<p><span>".esc_html__("This post contains","wcpdomain")." ". $wordCount ." ".__("words","wcpdomain")." </span><br />";
		}
		//Information for character counting
		if((get_option('wcp_charactercount','1') == '1')){
			$html .= "<span>This post contains ".mb_strlen(strip_tags($content))." characters</span><br />";
		}
		//Information about read time. 200 words per minute
		if((get_option('wcp_readtime','1') == '1')){
			$words = mb_strlen(strip_tags($content));
			if($words < 50){
				$html .= "<span>This post will take less a minute to read</span>";
			} else if(($words > 50) && ($words <= 200)){
				$html .= "<span>This post will take a minute to read</span>";
			}else{
				$html .= "<span>This post will take about ".round($words/200)." minutes to read</span>";
			}
		}
		$html .= "</p>";
		//$html .= "<p>Hello</p>";
		//To top place
		if(get_option('wcp_location','0') == '0'){
			return $html . $content;
		}
		//To bottom place
		return  $content . $html;
	}
	//Called by an action
	function registerFieldsSettings(){
		/*
			1.Section name
			2.Section's title. null - to skip
			3.A little bit of content of the top of the section (HTML)
			4.Page slug that we want to add this section to
		*/
		add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');
		/*
			1. 'option_name' column in table 'mx_options'
			2.Label text
			3.Function that outputs HTML
			4.Page Slug where we working with
			5. The section that we want to add this field to
		*/
	//Group for location ('Beginning post or end') (Select)
		add_settings_field('wcp_location', 'Display location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
		//register_settings() - registers new fields in 'mx_options' table. Call it once for each row 
		//Parameters: 1. Name of the group that this setting belongs to.
		register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));
	//Headline (text input)
		add_settings_field('wcp_headline', 'Headline text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
		register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post statistics'));
	//Word count (checkbox)
		add_settings_field('wcp_wordcount', 'Word count', array($this, 'wordcountHTML'), 'word-count-settings-page', 'wcp_first_section');
		register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
	//Character count (checkbox)
		add_settings_field('wcp_charactercount', 'Character count', array($this, 'charactercountHTML'), 'word-count-settings-page', 'wcp_first_section');
		register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
	//Read time (checkbox)
		add_settings_field('wcp_readtime', 'Read time', array($this, 'readtimeHTML'), 'word-count-settings-page', 'wcp_first_section');
		register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
	}
	
	//HTML location on a page
	 function locationHTML(){ ?>
	   <select name="wcp_location">
			<option value="0" <?php selected(get_option('wcp_location'), '0')?>>
				Beginning of post
			</option>
			<option value="1" <?php selected(get_option('wcp_location'), '1')?>>
				End of post
			</option>
	   </select>
    <?php }
	//Sanitize location
	function sanitizeLocation($input){
		if($input != '0' AND $input != '1'){
			add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginning or end');
			//Return value from DB
			return get_option('wcp_location');
		}
		return $input;
	}
	//Headline
	function headlineHTML(){ ?>
		<input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')) ?>" />
	<?php }
	
	//Word count (checkbox)
	function wordcountHTML(){ ?>
		<input type="checkbox" name="wcp_wordcount" value="1" <?php checked(get_option('wcp_wordcount'), '1') ?> />
	<?php }
	
	//Character count (checkbox)
	function charactercountHTML(){ ?>
		<input type="checkbox" name="wcp_charactercount" value="1" <?php checked(get_option('wcp_charactercount'), '1') ?> />
	<?php }
	
	//Read time (checkbox)
	function readtimeHTML(){ ?>
		<input type="checkbox" name="wcp_readtime" value="1" <?php checked(get_option('wcp_readtime'), '1') ?> />
	<?php }
	
	//Called by an action
	function wordCountSettings(){
		/*
			1.Page title; 2.Title in sidebar; 
			3.User permissions or capabilities; 
			4.Slug(short name) unique in URL of the Settings page;
			5.Function that represents HTML content of a new page;
		*/
		add_options_page('Settings for word count', __('Word count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'settingsPageHtml'));
    }
   
    function settingsPageHtml(){ ?>
		<div class="wrap">
			<h1>This is word count plugin! </h1>
			<form action="options.php" method="post">
				<?php
					settings_fields('wordcountplugin');
					do_settings_sections('word-count-settings-page');
					submit_button();
				?>
			</form>
		</div>
    <?php }
 }
 
 $mxWordCountPlugin = new MXWordCountPlugin();
 
 

