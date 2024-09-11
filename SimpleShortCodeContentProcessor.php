<?php
require_once(dirname(__FILE__) . '/ShortCodeContentProcessor.php');
class SimpleShortCodeContentProcessor extends ShortCodeContentProcessor
{

	public function process()
	{
        echo "<br/> -------- SimpleShortCodeContentProcessor ------ <br/>";
		add_shortcode('Show_Hide_Content', [$this, 'processShortCodeContent']);
	}

	public function processShortCodeContent($atts, $content = null)
	{
		$atts = shortcode_atts(['category' => '', 'id' => ''], $atts);
		//var_dump($content);
        $category = $atts['category'];
        $id = $atts['id'];
        
        if (empty($category) || empty($id)) {
            return '';
        }

        // Parse the URL to get the parameters in the order they appear
        //$url = $_SERVER['QUERY_STRING'];
        $query_params = $this->parse_url_params($_SERVER['REQUEST_URI']);
        
        $ordered_content = '';

        foreach ($query_params as $param_name => $param_value) {
            if ($param_name === $category) {
                $params = explode('-', $param_value);

                preg_match_all('/\[Content id=([^\]]+)\](.*?)\[\/Content\]/s', $content, $matches, PREG_SET_ORDER);

                $content_blocks = [];
                foreach ($matches as $match) {
                    $content_id = $this->clean_id($match[1]);;
                    $content_blocks[$content_id] = $match[2];
                }

                foreach ($params as $param) {
                    if (isset($content_blocks[$param])) {
                        $ordered_content .= $content_blocks[$param];
                    }
                }
            }
        }

        //var_dump($ordered_content);

        return do_shortcode($ordered_content);
	}

}