<?php
/*
Plugin Name: AutomationClinic - Content show order
Description: 
Plugin URI: http://www.AutomationClinic.com
Author: Luong Tran
Author URI: http://www.AutomationClinic.com
Version: 1.0
*/

require_once(dirname(__FILE__) . '/FlexibleShortCodeContentProcessor.php');
require_once(dirname(__FILE__) . '/SimpleShortCodeContentProcessor.php');
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


class ShowHideContentPlugin {

    public function __construct() {
        add_action('init', array($this, 'registerShortcodes'));
        add_filter('the_content', array($this, 'processContent'), 20); // Adjust priority if needed
    }

    public function registerShortcodes() {
        // Get the appropriate processor
        $processor = $this->getProcessor('');
        $processor->process(); // Register the shortcodes and filters
    }

    public function processContent($content) {
        // Get the appropriate processor based on the content
        $processor = $this->getProcessor($content);
        
        // Ensure shortcodes are processed
        $content = do_shortcode($processor->process($content));
        
        return $content;
    }

    private function getProcessor($content)
    {
        if (preg_match('/\[Categories\](.*?)\[\/Categories\]/is', $content, $categories_match)) {
            return new FlexibleShortCodeContentProcessor(); 
        } else {
            return new SimpleShortCodeContentProcessor();
        }
    }
}

new ShowHideContentPlugin();




