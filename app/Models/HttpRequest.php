<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HttpRequest extends Model
{
    use HasFactory;
    protected $casts = [
        'payload' => 'collection'
    ];

    protected $fillable = [
        'session_id',
        'user_id',
        'ip',
        'ajax',
        'url',
        'payload',
        'status_code'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function error_log()
    {
        return $this->hasOne(ErrorLog::class);
    }
}
