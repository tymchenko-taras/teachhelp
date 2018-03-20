<?php


namespace App;


use Illuminate\Database\Eloquent\Model;


class Pattern extends Model
{

    protected $table = 'pattern';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'comment'
    ];
}