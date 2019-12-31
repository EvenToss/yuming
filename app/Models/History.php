<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = ['time','pc_weight','m_weight','pc_vocabulary','m_vocabulary'];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
