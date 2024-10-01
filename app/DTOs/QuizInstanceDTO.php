<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class QuizInstanceDTO extends BaseDTO
{
    public $quiz_id;
    public $assigned_to;
    public $duration;
    public $scheduled_at;
    public $deadline_at;

    public function __construct($quizInstanceData)
    {
        $this->quiz_id = $quizInstanceData['quiz_id'];
        $this->assigned_to = $quizInstanceData['assigned_to'];
        $this->scheduled_at = $quizInstanceData['scheduled_at'];
        $this->deadline_at = $quizInstanceData['deadline_at'];
        $this->duration = $quizInstanceData['duration'];
    }
}
