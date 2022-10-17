<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Client;
use App\Models\ClientStatus;
use App\Models\ClientContact;
use App\Models\ClientContactStatus;

class ClientContactController extends Controller
{
    public function create(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'position' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/clients', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/clients/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ClientContactStatus::ACTIVE; // if you want to insert to a specific column
        ClientContact::create($data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.clients');
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'person' => 'required',
            'position' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'line_address_1' => 'required',
            'line_address_2' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = $request->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/clients', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/clients/' . $image_name; // save the destination of the file to the database
        }

        $client_contact = ClientContact::find($request->client_contact_id);
        $client_contact->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');
        return redirect()->route('admin.clients');
    }

    public function recover(Request $request, $client_contact_id)
    {
        $client_contact = ClientContact::find($client_contact_id);
        $client_contact->status = ClientContactStatus::ACTIVE; // mark data as active
        $client_contact->save();

        $request->session()->flash('success', 'Data has been recovered');
        return back();
    }

    public function delete(Request $request, $client_contact_id)
    {
        $client_contact = ClientContact::find($client_contact_id);
        $client_contact->status = ClientContactStatus::INACTIVE; // mark data as inactive
        $client_contact->save();

        $request->session()->flash('success', 'Data has been deleted');
        return back();
    }
}
