<?php
namespace App\Classes;

use voku\helper\HtmlDomParser;

class WebParser
{
    /**
     * Html object from Url.
     *
     * @var object
     */
    private $html;
    
    /**
     * Html object from Url.
     *
     * @var string
     */
    private $url;

    function __construct($url = null, $html = null)
    {
        $this->url = $url;
        $this->html = $html;
    }

    /**
     * Parse url html data
     *
     * @return json
     */
    public function parse($url)
    {
        $data = [];

        try{    
            $this->html = HtmlDomParser::file_get_html($url);
            $this->url = $url;

        } catch (\Throwable $th) {
            return 'Invalid URL';
            exit;
        }
        
        $links = $this->resource('a', 'href');
        $images = $this->resource('img', 'src');
        $script = $this->resource('script', 'src');
        $styles = $this->resource('link', 'href');

        $data = array_merge([
            'links' => $links,
            'images' => $images,
            'scripts' => $script,
            'styles' => $styles
        ]);

        $data = json_encode($data, JSON_PRETTY_PRINT);
        $data = preg_replace('/\\\\/', '', $data);

        return $data;
    }

    /**
     * Get specific resource.
     *
     * @return array
     */
    private function resource($tag, $tagAttribute)
    {
        $output = [];

        $data = $this->html->find($tag);

        if ($data !== false)
        {
            foreach ($data as $element)
            {
                $attribute = $element->{$tagAttribute};

                if($attribute)
                {
                    // Check if attribute has specified url if not add url from base
                    if(!parse_url($attribute, PHP_URL_HOST))
                    {
                        $baseUrl = parse_url($this->url, PHP_URL_SCHEME) . '://' . parse_url($this->url, PHP_URL_HOST);
                        $attribute = $baseUrl . $attribute;
                    }

                    $output []= $attribute;
                }
            }
        }
        return $output;
    }
}