<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['name'];

    public function setNameAttribute($value): void {
        $this->attributes['name'] = ucwords(strtolower(trim($value)));
    }
}
