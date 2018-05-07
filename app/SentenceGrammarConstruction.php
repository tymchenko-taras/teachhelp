<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 07.05.2018
 * Time: 17:29
 */


namespace App;
use Illuminate\Database\Eloquent\Model;

class SentenceGrammarConstruction extends Model {

    protected $table = 'sentence_grammar_construction';
    protected $fillable = array('grammar_construction_id', 'sentence_id', 'is');

    public function sentence() {
        return $this->belongsTo('App\Sentence');
    }

}