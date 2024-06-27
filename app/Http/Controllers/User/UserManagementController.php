<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Course\CourseReadRequest;
use App\Http\Requests\User\UserWriteRequest;
use App\Interfaces\Service\User\IUserReadService;
use App\Interfaces\Service\User\IUserWriteService;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class UserManagementController extends Controller
{
    public $userWriteService;
    public $userReadService;
    public function __construct(IUserWriteService $userWriteService, IUserReadService $userReadService)
    {
        parent::__construct();
        $this->userWriteService = $userWriteService;
        $this->userReadService = $userReadService;
    }

    public function createUser(UserWriteRequest $request)
    {
        Gate::authorize('create', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->createUser($request->only('name', 'email', 'role_id'));
            $this -> cacheService -> invalidateGroupCache('User');
            return $user;
        });
    }


    public function getAllUser(CourseReadRequest $request)
    {
        Gate::authorize('getAllUser', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $users = $this -> cacheService -> storeInCache('User','allUser',$request->perPage, $request->page,function () use ($request){
                return $this->userReadService->getAllUsers($request->perPage, $request->page);
            },10);
            return $users;
        });
    }



    public function updateUser(UserWriteRequest $request)
    {
        Gate::authorize('update', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->updateUser($request->id, $request->only('name', 'email', 'role_id'));
            $this -> cacheService -> invalidateGroupCache('User');
            return $user;
        });
    }



    public function disableUser(UserWriteRequest $request)
    {
        Gate::authorize('disable', User::class);
        return $this->handleServiceCall(function () use ($request) {
            $user = $this->userWriteService->disableUser($request->id);
            $this -> cacheService -> invalidateGroupCache('User');
            return $user;
        });
    }
}
