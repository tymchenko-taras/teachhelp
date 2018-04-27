<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 27.04.2018
 * Time: 14:45
 */

class Base {

    // text should be highlighted
    public $highlightPattern = array();

    /**
     * Sometimes we need to highlight not just object fields like 'Id' or 'Description' but something more nested
     * for ex. serialNumbers for order(order has a lot of products with a lot of serial numbers). that's why $text is here
     * @param $field
     * @param null $text
     * @return mixed|null
     */
    public function highlightField($field, $text = null){
        if (is_null($text) && !empty($this -> $field)){
            $text = $this -> $field;
        }

        if ($text && $pattern = $this -> getHighlightPatternForField($field)){
            $text = preg_replace($pattern, '<span class="highlight-word">$0</span>', $text);
        }

        return $text;
    }

    protected function getHighlightPatternForField($field){
        static $result = array();
        if(!isset($result[ $field ])){
            $pattern = '';
            if(isset($this -> highlightPattern[ $field ])){
                $words = $this -> highlightPattern[ $field ];
                $patterns = array();
                foreach (is_array($words) ? $words : array($words) as $item) {
                    if ($item != '') {
                        $patterns['\b(' . preg_quote(trim($item, '*')) . '[\\w]{0,2}\b)'] = true;
                    }
                }
                if (count($patterns) > 0) {
                    $pattern = '#' . implode('|', array_keys($patterns)) . '#iu';
                }
            }
            $result[ $field ] = $pattern;
        }

        return $result[ $field ];
    }
}