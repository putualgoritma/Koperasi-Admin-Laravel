<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Modules\User\Entities\Permission;
use Modules\User\Http\Requests\PermissionCreateRequest;
use Modules\User\Http\Requests\PermissionUpdateRequest;
use Validator;

class PermissionService
{
    
    public function index(Request $request)
    {
        if (isset($request->page)) {
            $permissions = Permission::select('*')->FilterInput($request)->SetOrderBy($request)
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $permissions = Permission::select('*')->FilterInput($request)->SetOrderBy($request)->get();
        }

        return sendResponse($permissions, 'Permission index successfully', 'plain');
    }
    
   public function store(Request $request)
    {
        //validate input
        $permissionCreateRequest = new PermissionCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $permissionCreateRequest->rules(), $permissionCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Permission store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $permission = Permission::create($data);
            $permission = $permission->refresh();
            return sendResponse($permission, 'Permission store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if permission not exist
        if (!($permission = Permission::where('id', $request->id)->exists())) {
            return sendError('Permission does not exist!', '', '404', 'plain');
        }
        //validate input
        $permissionUpdateRequest = new PermissionUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $permissionUpdateRequest->rules($data['id']), $permissionUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Permission update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $permission = Permission::find($request->id);
            $permission->fill($data)->save();
            return sendResponse($permission, 'Permission update successfully', 'plain');
        }
    }
}
