<?php

namespace App\Policies;

use App\Interfaces\Service\Permission\IPermissionVerificationService;

abstract class Policy
{
    public $permissionService;
    /**
     * Create a new class instance.
     */
    public function __construct(IPermissionVerificationService $permissionService)
    {
        $this->permissionService = $permissionService;
    }
}
