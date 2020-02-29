<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'surname'];

    protected $dates = ['deleted_at'];

    public function books() {
        return $this->belongsToMany('App\Models\Book', 'books_authors');
    }

    public function getSurnameAttribute($name) {
        if ($this->trashed()) {
            return "{$name} (Deleted)";
        }
        return "$name";
    }
}
