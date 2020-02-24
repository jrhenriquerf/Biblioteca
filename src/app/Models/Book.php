<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'image'];

    protected $dates = ['deleted_at'];

    public function authors() {
        return $this->belongsToMany('App\Models\Author', 'book_authors');
    }

    public function lendings() {
        return $this->belongsToMany('App\Models\Lending', 'book_lendings');
    }
}
