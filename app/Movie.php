<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'thumb_url',
        'movie_url',
        'actor',
        'view',
        'status'
    ];

}
