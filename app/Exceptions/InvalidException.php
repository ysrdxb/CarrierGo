<?php
 
namespace App\Exceptions;
 
use Exception;
 
class InvalidException extends Exception
{
    // ...
 
    /**
     * Get the exception's context information.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return 'bye';
    }
}