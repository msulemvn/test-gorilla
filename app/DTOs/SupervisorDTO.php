<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class SupervisorDTO extends BaseDTO
{
    public $account_id;

    public function __construct($applicationData)
    {
        $this->account_id = $applicationData['account_id'];
    }
}
