<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class QuizRecordDTO extends BaseDTO
{
    public $quiz_instance_id;
    public $score;
    public $recording;

    public function __construct($quizRecordData)
    {
        $this->quiz_instance_id = $quizRecordData['quiz_instance_id'];
        $this->score = $quizRecordData['score'];
        $this->recording = $quizRecordData['recording'];
    }
}
