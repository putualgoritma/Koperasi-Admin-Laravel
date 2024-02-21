<?php

namespace Modules\User\Services;

use Auth;
use Illuminate\Http\Request;
use Modules\User\Entities\Contact;
use Modules\User\Entities\User;
use Modules\User\Http\Requests\UserCreateRequest;
use Modules\User\Http\Requests\UserUpdateRequest;
use Modules\User\Services\ContactService;
use Validator;

class UserService
{

    public function index(Request $request)
    {
        if (isset($request->page)) {
            $users = User::select('*')->FilterInput($request)->SetOrderBy($request)->with('contact')
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $users = User::select('*')->FilterInput($request)->SetOrderBy($request)->with('contact')->get();
        }

        return sendResponse($users, 'User index successfully', 'plain');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user = User::where('id', $user->id)->with('role')->with('contact')->first();
            $token = $user->createToken('MyApp')->accessToken;

            $permissionsArray = array();
            if (isset($user->role)) {
                foreach ($user->role->permissions as $permissions) {
                    $permissionsArray[] = $permissions->title;
                }
            }
            unset($user->role);

            $response = [
                'user' => $user,
                'token' => $token,
                'permissions' => $permissionsArray,
            ];

            return sendResponse($response, 'User login successfully.', 'plain');
        } else {
            return sendError('Unauthorised.', ['error' => 'Unauthorised'], '404', 'plain');
        }
    }

    public function updateStatus($id, $status, $statusCheck = '')
    {
        //if user not exist
        if (!($user = User::where('id', $id)->exists())) {
            return sendError('User does not exist!', '', '404', 'plain');
        }
        $user = User::find($id);
        //if user status need check
        if ($statusCheck != '' && $user->status != $statusCheck) {
            return sendError('User status invalid', '', '404', 'plain');
        }
        $user->status = $status;
        $user->save();
        return sendResponse($user, 'User Status update successfully', 'plain');
    }

    public function store(Request $request)
    {
        //check register
        if (!isset($request->register)) {
            $register = date('Y') . '-' . date('m') . '-' . date('d');
            $request->request->add(['register' => $register]);
        }
        //check type
        if (!isset($request->type)) {
            $type = 'member';
            $request->request->add(['type' => $type]);
        }
        //check code
        if (!isset($request->code)) {
            $period = substr($request->register, 0, 4);
            $prefix = config('user.type_prefix')[$request->type];
            $code = $this->codeGenerate($request->type, $prefix, $period);
            $request->request->add(['code' => $code]);
        }
        //validate input
        $userCreateRequest = new UserCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $userCreateRequest->rules(), $userCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('User store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);
            $user = $user->refresh();
            return sendResponse($user, 'User store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if user not exist
        if (!($user = User::where('id', $request->id)->exists())) {
            return sendError('User does not exist!', '', '404', 'plain');
        }
        //validate input
        $userUpdateRequest = new UserUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $userUpdateRequest->rules($data['id']), $userUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('User update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $user = User::find($request->id);
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }
            $user->fill($data)->save();
            return sendResponse($user, 'User update successfully', 'plain');
        }
    }

    public function storeContact(Request $request)
    {
        //check if user not exist
        $requestUser = setRequest($request->user_data);
        if (!isset($request->user_data['id'])) {
            //create user
            $user = $this->store($requestUser);
            if (!$user->success) {
                return sendError($user->message, '', '404', 'plain');
            }
        } else {
            //update user
            $user = $this->update($requestUser);
            if (!$user->success) {
                return sendError($user->message, '', '404', 'plain');
            }
        }

        //check if contact exist
        if (!empty($request->contact_data)) {
            $requestContact = setRequest($request->contact_data);
            $contactService = new ContactService();
            if (!(Contact::where('id', $user->data->contact_id)->exists())) {
                //create contact
                $contact = $contactService->store($requestContact);
                $user->data->contact_id = $contact->data->id;
                $user->data->save();
            } else {
                //update contact
                $requestContact = array_merge($request->contact_data, ['id' => $user->data->contact_id]);
                $requestContact = setRequest($requestContact);
                $contact = $contactService->update($requestContact);
            }
            if (!$contact->success) {
                return sendError($contact->message, '', '404', 'plain');
            } else {
                return sendResponse($user->data, 'User Contact store successfully', 'plain');
            }
        } else {
            return sendResponse($user->data, 'User Contact store successfully', 'plain');
        }
    }

    public function codeGenerate($type, $prefix, $period = '')
    {
        if ($period == '') {
            $period = date('Y');
        }
        $ledger = User::where('type', $type)->where('register', 'LIKE', $period . '%')->orderBy('code', 'desc')->first();
        if ($ledger && (strlen($ledger->code) == config('user.code_length'))) {
            $last_code = $ledger->code;
        } else {
            $prefix = $prefix . str_replace("-", "", $period);
            $last_code = acc_codedef_generate($prefix, config('user.code_length'));
        }
        $code = acc_code_generate($last_code, config('user.code_length'), 7);
        return $code;
    }
}
