<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class StudentDTO extends BaseDTO
{
    public $name;
    public $email;
    public $phone;

    public function __construct($applicationData)
    {
        $this->name = $applicationData['name'];
        $this->email = $applicationData['email'];
        $this->phone = $applicationData['phone'];
    }
}
