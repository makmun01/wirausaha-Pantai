<?php

namespace App\Exceptions;

use RuntimeException;

class SecurityException extends RuntimeException
{
    // Raised on tenant data isolation or RBAC security boundary violations
}
