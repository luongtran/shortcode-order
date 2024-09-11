<?php

require_once(dirname(__FILE__) . '/ShortCodeContentProcessor.php');

class FlexibleShortCodeContentProcessor extends ShortCodeContentProcessor
{
    private $shortcode_content = [];

    public function process($content = null)
    {
        // Register the shortcodes
        add_shortcode('Show_Hide_Content', [$this, 'processShortCodeContent']);
        add_filter('the_content', [$this, 'output_sorted_content'], 99);
        
        return do_shortcode($content);
    }

    // Process the [Show_Hide_Content] shortcode
    public function processShortCodeContent($atts, $content = null)
    {
        $atts = shortcode_atts([
            'Category' => '',
            'id' => '',
        ], $atts);

        if (!$atts['Category'] || !$atts['id']) {
            return ''; // No category or id, return nothing.
        }

        // Store the content by category
        if (!isset($this->shortcode_content[$atts['Category']])) {
            $this->shortcode_content[$atts['Category']] = [];
        }

        // Capture the content for later sorting/output
        $content_blocks = [];
        preg_match_all('/\[Content id=(.*?)\](.*?)\[\/Content\]/is', $content, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $content_id = trim($match[1]);
            $content_id = $this->clean_id($content_id);

            // Store the processed content
            $this->shortcode_content[$atts['Category']][$content_id] = do_shortcode($match[2]);
        }

        return ''; // Content will be output later
    }

    // Output the sorted content based on URL parameters
    public function output_sorted_content($content)
    {
        // Parse URL parameters
        $query_params = $this->parse_url_params($_SERVER['REQUEST_URI']);
        $sorted_output = '';

        foreach ($query_params as $category => $order_string) {
            $order = explode('-', $order_string);

            if (isset($this->shortcode_content[$category])) {
                foreach ($order as $key) {
                    $key = $this->clean_id($key); // Clean the key to avoid issues
                    if (isset($this->shortcode_content[$category][$key])) {
                        $sorted_output .= $this->shortcode_content[$category][$key];
                    }
                }
            }
        }

        // Return sorted content or original content if no sorting is applied
        return !empty($sorted_output) ? $sorted_output : $content;
    }
}
