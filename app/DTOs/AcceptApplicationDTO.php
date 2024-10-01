<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class AcceptApplicationDTO extends BaseDTO
{
    public string $status;

    public function __construct($status)
    {
        $this->status = $status;
    }
}
