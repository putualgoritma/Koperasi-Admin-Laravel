<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Modules\User\Entities\Contact;
use Modules\User\Entities\ContactAddress;
use Modules\User\Http\Requests\ContactCreateRequest;
use Modules\User\Http\Requests\ContactUpdateRequest;
use Validator;

class ContactService
{
    public function index(Request $request)
    {
        if (isset($request->page)) {
            $contacts = Contact::select('*')->FilterInput($request)->SetOrderBy($request)
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $contacts = Contact::select('*')->FilterInput($request)->SetOrderBy($request)->get();
        }

        return sendResponse($contacts, 'Contact index successfully', 'plain');
    }
    
    public function store(Request $request)
    {
        //validate input
        $contactCreateRequest = new ContactCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $contactCreateRequest->rules(), $contactCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Contact store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $contact = Contact::create($data);
            $contact = $contact->refresh();
            if (isset($request->contact_address)) {
                //set address
                foreach ($request->contact_address as $key => $address) {
                    if (!empty($address)) {
                        $dataAddress = array_merge($address, ['contact_id' => $contact->id]);
                        $contactAddress = ContactAddress::create($dataAddress);
                    }
                }}
            return sendResponse($contact, 'Contact store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if contact not exist
        if (!(Contact::where('id', $request->id)->exists())) {
            return sendError('Contact does not exist', '', '404', 'plain');
        }
        //validate input
        $contactUpdateRequest = new ContactUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $contactUpdateRequest->rules(), $contactUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Contact update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $contact = Contact::find($request->id);
            $contact->fill($data)->save();
            if (isset($request->contact_address)) {
                //reset address
                ContactAddress::where('contact_id',$contact->id)->delete();
                //set address
                foreach ($request->contact_address as $key => $address) {
                    if (!empty($address)) {
                        $dataAddress = array_merge($address, ['contact_id' => $contact->id]);
                        $contactAddress = ContactAddress::create($dataAddress);
                    }
                }}
            return sendResponse($contact, 'Contact update successfully', 'plain');
        }
    }
}
