<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 26.04.2018
 * Time: 19:13
 */


namespace App\Service\Render\_Base;

abstract class Sphinx  extends Recordset {

    protected $languages = array();
    protected $fullTextFields = array('Name');
    protected $sellStatuses = array('CanBuy' => 2, 'CanPreorder' => 1, 'Empty' => 0);
    protected $facetConfig = array();
    protected $checkFacetId = true;
    /**
     * @var SphinxXmlWriter
     */
    protected $writer = null;

    protected function endXml(){
        echo '</sphinx:docset>';
    }

    protected function writeXmlItem($item){
        echo PHP_EOL, '<sphinx:document id="', $item['itemId'],'">';
        foreach($item as $name => $value){
            if(is_array($value)){
                $value = array_values($value);
                foreach($value as $i => $v){
                    $value[ $i ] = "<items id='$i'>$v</items>";
                }
                $value = PHP_EOL . implode(PHP_EOL, $value) . PHP_EOL;
            } else {
                $value = htmlspecialchars($value);
            }

            echo PHP_EOL, " <$name>$value</$name>";
        }
        echo PHP_EOL, '</sphinx:document>';
    }

    protected function startXml(){
        echo '<?xml version="1.0" encoding="UTF-8"?>', PHP_EOL, ' <sphinx:docset>', PHP_EOL, '  <sphinx:schema>';
        foreach($this -> fullTextFields as $item){
            echo PHP_EOL, '   <sphinx:field name="'. $item .'"/>';
        }
        foreach($this -> GetAttributes() as $item){
            echo PHP_EOL, '   <sphinx:attr';
            foreach($item as $i => $v){
                echo " $i='$v'";
            }
            echo '/>';
        }
        echo PHP_EOL, '  </sphinx:schema>';
    }

    protected function GetAttributes() {
        $result = array(
            array('name' => 'itemId', 'type' => 'int', 'bits' => '32'),
        );
        return $result;
    }

    protected function getItemMultiplier($record){
        return 1;
    }

    protected function getItemFacetId($record){
        if(!empty($this -> facetConfig[ $record['FacetId'] ])){
            return $this -> facetConfig[ $record['FacetId'] ]['integer-id'];
        }
    }

    protected function getItemSellStatus($record){
        return $record['CanBuy'] ? $this -> sellStatuses['CanBuy']
            : ($record['CanPreorder'] ? $this -> sellStatuses['CanPreorder'] : $this -> sellStatuses['Empty']);
    }

    protected function Prepare() {;
        $this -> writer = new \App\Service\Render\Sphinx\SphinxXmlWriter();
        $this -> writer -> startXml();
        $this -> writer -> writeScheme($this -> fullTextFields, $this -> GetAttributes());
    }

    abstract protected function ProcessItem($record, &$item);

    protected function DefaultRender($ids) {
        $result = array();
        foreach ($ids as $record) {
            $item = array(
                'itemId' => $record['id'],
            );
            foreach ($this -> fullTextFields as $field) {
                $text = !empty($record[ $field ]) ? $record[ $field ] : null;
                $text = !empty($record['__translation'][$field]) ? implode(' ', $record['__translation'][$field]) : $text;

                if ($text) {
                    $item[strtolower($field)] = preg_replace('#\{\{[pi],\d+\}\}#i', '', $text);
                }
            }

            $this -> ProcessItem($record, $item);

            $result[] = $item;
        }
        return $result;
    }

    protected function Save($records) {
        foreach ($records as $record) {
            if($record){
                $this -> writer -> writeDocument($record);
            }
        }
    }

    protected function Cleanup($ids) {
        $this -> writer -> endXml();
    }

}