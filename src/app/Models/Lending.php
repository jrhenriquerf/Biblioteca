<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    protected $fillable = ['user_id', 'date_start', 'date_end', 'date_finish'];

    public function users() {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function books() {
        return $this->belongsToMany('App\Models\Book', 'books_lendings');
    }
}
