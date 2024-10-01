<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizInstance extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at'];
    protected $hidden = ['updated_at'];
    public function quizRecord()
    {
        return $this->has(QuizRecord::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
