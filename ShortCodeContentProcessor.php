<?php

class ShortCodeContentProcessor
{
    protected $processContent;
	public function __construct()
	{
		//add_shortcode('Categories', [$this, 'reg_categories_shortcode']);
		//add_shortcode('Show_Hide_Content', [$this, 'processShortCodeContent'], 20);
	}

	public function reg_categories_shortcode($atts, $content = null)
    {
        echo  "<br/> -------- reg_categories_shortcode ---------- <br/>";
        var_dump($content);
        $this->processContent = do_shortcode($content);
        //return $this->processContent;
        
        return $content;
    }
	
	protected function clean_id($id) {
        // Remove any unwanted characters and return a cleaned id.
        $id = str_replace(['“', '”', '8221', '8243'], '', $id);
        return preg_replace('/[^\w-]/', '', $id);
    }

    protected function parse_url_params($url) {
        $params = [];
        $parts = parse_url($url);
        if (isset($parts['query'])) {
            parse_str($parts['query'], $params);
        }
        return $params;
    }
}