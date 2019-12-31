<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table = 'domains';

    protected $fillable = ['url'];

    public function histories()
    {
       return $this->hasMany(History::class);
    }

    public function juzi()
    {
        return $this->hasMany(Juzi::class);
    }
}
