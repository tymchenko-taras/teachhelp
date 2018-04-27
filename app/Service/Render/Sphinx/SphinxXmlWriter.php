<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 26.04.2018
 * Time: 18:52
 */

namespace App\Service\Render\Sphinx;

class SphinxXmlWriter
{

    public $site = 0;
    protected $_counter = 0;
    protected $writer;

    public function __construct() {
        $this->writer = new \XMLWriter();
    }

    public function startXml() {
//        $this->writer->openUri($this->_filename);
        $this->writer->openUri('php://output');
        $this->writer->setIndent(true);
        $this->writer->startDocument('1.0', 'UTF-8');
        $this->writer->startElement('sphinx:docset');
        $this->writer->flush(true);
    }

    public function endXml() {
        $this->writer->endElement();
        $this->writer->flush(true);
        $this->writer = null;
    }

    public function writeScheme(array $full_text_fields = array(), array $attrs = array()) {
        if (!empty($fields) or !empty($attrs)) {
            $this->writer->startElement('sphinx:schema');
            foreach ($full_text_fields as $field) {
                $this->writer->startElement('sphinx:field');
                $this->writer->writeAttribute('name', strtolower($field));
                $this->writer->endElement();
            }
            foreach ($attrs as $value) {
                $this->writer->startElement('sphinx:attr');
                foreach ($value as $key => $val) {
                    $this->writer->writeAttribute($key, $val);
                }
                $this->writer->endElement();
            }
            $this->writer->endElement();
            $this->writer->flush(true);
        }
    }

    private function _writeDocumentProperty($key, $value) {
        $this->writer->startElement($key);
        if (is_array($value)) {
            $c = 0;
            foreach ($value as $item) {
                $this->writer->startElement('items');
                $this->writer->writeAttribute('id', ++$c);
                $this->writer->text(str_replace('*', 'x', $item));
                $this->writer->endElement();
            }
        } else {
            $this->writer->text(str_replace('*', 'x', $value));
        }
        $this->writer->endElement();
    }

    public function writeDocument($data) {
        $this->writer->startElement('sphinx:document');
        $this->writer->writeAttribute('id', $data['itemId']);

        foreach ($data as $key => $value) {
            $this->_writeDocumentProperty($key, $value);
        }

        $this->writer->endElement();

        $this->writer->flush(true);
    }
}