<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\Role\ManagingRoleUserRequest;
use App\Interfaces\Service\Role\IManagingRoleUserWriteService;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Gate;

class ManagingRoleUserController extends Controller
{
    public $roleService;

    public function __construct(IManagingRoleUserWriteService $roleService) {
        $this->roleService = $roleService;
    }



    public function changeRoleToUser(ManagingRoleUserRequest $request){
        Gate::authorize('changeRoleToUser', Role::class);
        return $this -> handleServiceCall(function () use ($request){
            $user = $this->roleService->changeUserRole($request -> id, $request -> role_id);
            return $user;
        });
        
    }
}
