<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Modules\User\Entities\Role;
use Modules\User\Http\Requests\RoleCreateRequest;
use Modules\User\Http\Requests\RoleUpdateRequest;
use Validator;

class RoleService
{
    
    public function index(Request $request)
    {
        if (isset($request->page)) {
            $roles = Role::select('*')->FilterInput($request)->SetOrderBy($request)
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $roles = Role::select('*')->FilterInput($request)->SetOrderBy($request)->get();
        }

        return sendResponse($roles, 'Role index successfully', 'plain');
    }
    
   public function store(Request $request)
    {
        //validate input
        $roleCreateRequest = new RoleCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $roleCreateRequest->rules(), $roleCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Role store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $role = Role::create($data);
            $role->permissions()->sync($request->permissions);
            $role = $role->refresh();
            return sendResponse($role, 'Role store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if role not exist
        if (!($role = Role::where('id', $request->id)->exists())) {
            return sendError('Role does not exist!', '', '404', 'plain');
        }
        //validate input
        $roleUpdateRequest = new RoleUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $roleUpdateRequest->rules($data['id']), $roleUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Role update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $role = Role::find($request->id);
            $role->fill($data)->save();
            $role->permissions()->sync($request->permissions);
            return sendResponse($role, 'Role update successfully', 'plain');
        }
    }
}
