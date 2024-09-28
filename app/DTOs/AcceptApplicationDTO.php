<?php

namespace App\DTOs;

use App\DTOs\BaseDTO;

class AcceptApplicationDTO extends BaseDTO
{
    public int $accepted;

    public function __construct($status)
    {
        $this->accepted = $status;
    }
}
