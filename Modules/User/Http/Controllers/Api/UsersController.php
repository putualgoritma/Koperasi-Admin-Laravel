<?php

namespace Modules\User\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Controller;
use Modules\User\Entities\User;
use Modules\User\Events\UserRegistered;
use Modules\User\Services\UserService;
use Modules\User\Transformers\UserResource;

class UsersController extends Controller
{

    public function test()
    {
        $type = 'member';
        $prefix = config('user.type_prefix')[$type];
        return $prefix;
    }

    public function login(Request $request, UserService $userService)
    {
        //set user
        $user = $userService->login($request);
        if (!$user->success) {
            return sendError($user->message, '', '404');
        }

        return sendResponse($user->data, 'User login successfully.');
    }

    public function index(Request $request, UserService $userService)
    {
        //set user
        $user = $userService->index($request);
        if (!$user->success) {
            return sendError($user->message, '', '404');
        }

        return sendResponse($user->data, 'User list successfully.');
    }

    public function store(Request $request, UserService $userService)
    {
        //set user
        $user = $userService->store($request);
        if (!$user->success) {
            return sendError($user->message, '', '404');
        }

        return sendResponse(new UserResource($user->data), 'User register successfully.');
    }

    public function register(Request $request, UserService $userService)
    {
        //set userContact
        $userContact = $userService->storeContact($request);
        if (!$userContact->success) {
            return sendError($userContact->message, '', '404');
        }
        event(new UserRegistered($userContact->data));

        return sendResponse(new UserResource($userContact->data), 'User contact successfully.');
    }

    public function registered($id, UserService $userService)
    {
        //set user
        $user = $userService->updateStatus($id, 'registered', 'register');
        if (!$user->success) {
            return sendError($user->message, '', '404');
        }
        //event(new UserRegistered($user->data));

        return sendResponse(new UserResource($user->data), 'Register approved successfully.');
    }

    public function updateProfile(Request $request, UserService $userService)
    {
        //set userContact
        $userContact = $userService->storeContact($request);
        if (!$userContact->success) {
            return sendError($userContact->message, '', '404');
        }

        return sendResponse(new UserResource($userContact->data), 'Update User Profile successfully.');
    }

    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return sendError('User not found.');
        }

        return sendResponse(new UserResource($user), 'User retrieved successfully.');
    }

    public function update(Request $request, UserService $userService)
    {
    //set userContact
        $userContact = $userService->update($request);
        if (!$userContact->success) {
            return sendError($userContact->message, '', '404');
        }

        return sendResponse(new UserResource($userContact->data), 'Update User successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return sendResponse([], 'User deleted successfully.');
    }
}
