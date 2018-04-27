<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 26.04.2018
 * Time: 19:23
 */


namespace App\Service\Render\Sphinx;

class   Search extends \App\Service\Render\_Base\Sphinx {

    protected $fullTextFields = array('content');
    protected $processBatchSize = 0;
    protected $saveBatchSize = 0;

    protected function GetAttributes() {
        $result = array(
            array('name' => 'itemId',       'type' => 'int', 'bits' => '16'),
            array('name' => 'b1',           'type' => 'multi'),
            array('name' => 'content',           'type' => 'string'),
        );
        foreach ($this -> languages as $lang) {
            $result[] = array('name' => 'sortIndex' . $lang -> Id, 'type' => 'int', 'bits' => '32');
        }
        return $result;
    }

    protected function getExpressions(){
        static $cache = null;
        if(is_null($cache)) {
            $cache = \App\Service\Sentence::getExpressions();
        }

        return $cache;
    }

    public function GetIds() {
        return [1];
    }

    protected function ProcessItem($record, &$item) {
        $item['content'] = $record->content;
        $item['b1'] = [];
        foreach($this -> getExpressions() as $expression) {
            if($result = preg_match('#'. $expression['expression'] .'#i', $item['content'])) {
                $item['b1'][] = $expression['id'];
            }
        }

        foreach ($this -> languages as $lang) {
            $item['sortIndex' . $lang -> Id] = !empty($record['__translation']['SortIndex'][$lang -> Id])
                ? $record['__translation']['SortIndex'][$lang -> Id]
                : $record['SortIndex'];
        }
    }

    protected function DefaultRender($ids) {
        $batch = 30000;
        for($i = 0; ; $i += $batch) {
            $records = \DB::select("select `id`,`content` from `sentence` LIMIT $i, $batch");
            if (empty($records)) break;

            foreach ($records as $record) {
                $item = array(
                    'itemId' => $record -> id,
                );
                foreach ($this->fullTextFields as $field) {
                    $text = !empty($record->{$field}) ? $record->{$field} : null;
                    $item[$field] = $text;
                }

                $this->ProcessItem($record, $item);
                $this -> writer -> writeDocument($item);
            }
        }

        return [];
    }

}