<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Juzi extends Model
{
    protected $table = 'juzis';

    protected $fillable = ['year','title','domain_id'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
