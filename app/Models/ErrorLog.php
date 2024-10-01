<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;
    public function http_request()
    {
        return $this->belongsTo(Quiz::class);
    }
}
