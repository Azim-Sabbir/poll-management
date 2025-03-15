<?php
namespace App\Exceptions;

use Exception;

class AlreadyVotedException extends Exception
{
    public function __construct()
    {
        parent::__construct('You have already voted', 400);
    }
}
