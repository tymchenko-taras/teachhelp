<?php
/**
 * Created by PhpStorm.
 * User: ttymchenko
 * Date: 07.05.2018
 * Time: 17:29
 */


namespace App;
use Illuminate\Database\Eloquent\Model;

class Sentence extends Model {

    protected $table = 'sentence';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grammarConstruction() {
        return $this->hasMany('App\SentenceGrammarConstruction');
    }

}