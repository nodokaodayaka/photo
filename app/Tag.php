<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    protected $visible = [
        'name',
    ];

    public function tags()
    {
        return $this->belongsToMany('App\Photo', 'photo_tag')->withTimestamps();
    }
}
