<?php

namespace App\Http\Controllers\Admin\Client;

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

class ClientController extends Controller
{
    public function show()
    {
        $clients = Client::orderBy('created_at', 'desc')
                    ->where('status', '!=', ClientStatus::INACTIVE)
                    ->paginate(15);

        return view('admin.clients.show', compact(
            'clients'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('admin.clients.filter', [$name, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $status, $from_date, $to_date)
    {
        $query = Client::where('status', '!=', ClientStatus::INACTIVE)
                    ->orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if ($status != '*') {
            $query->where('status', $status);
        }

        /* date filter */
        // if they provided both from date and to date
        if ($from_date != '*' && $to_date != '*') {
            $query->whereBetween('created_at', [$from_date . ' 00:00:00', $to_date . ' 23:59:59']);
        }
        /* date filter */

        $clients = $query->paginate(15);

        return view('admin.clients.show', compact(
            'clients'
        ));
    }

    public function add()
    {
        return view('admin.clients.add');
    }

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

        $data = request()->only('name', 'description'); // get all request
        $contact_data = request()->all();

        if ($request->file('image')) { // if the file is present
            $image_name = $request->name . '-' . time() . '.' . $request->file('image')->getClientOriginalExtension(); // set unique name for that file
            $request->file('image')->move('uploads/images/clients', $image_name); // move the file to the laravel project
            $data['image'] = 'uploads/images/clients/' . $image_name; // save the destination of the file to the database
        }

        $data['status'] = ClientStatus::ACTIVE; // if you want to insert to a specific column
        $client = Client::create($data); // create data in a model

        $contact_data['client_id'] = $client->id;
        $contact_data['name'] = $request->person;
        $contact_data['status'] = ClientContactStatus::ACTIVE; // if you want to insert to a specific column
        ClientContact::create($contact_data); // create data in a model

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('admin.clients');
    }

    public function view($client_id)
    {
        $client = Client::find($client_id);
        $contact = ClientContact::where('status', ClientContactStatus::ACTIVE)
                            ->latest()
                            ->first();

        return view('admin.clients.view', compact(
            'client',
            'contact',
        ));
    }

    public function edit($client_id)
    {
        $client = Client::find($client_id);

        return view('admin.clients.edit', compact(
            'client'
        ));
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
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

        $client = Client::find($request->client_id);
        $client->fill($data)->save();

        $request->session()->flash('success', 'Data has been updated');

        return redirect()->route('admin.clients');
    }

    public function recover(Request $request, $client_id)
    {
        $client = Client::find($client_id);
        $client->status = ClientStatus::ACTIVE; // mark data as active
        $client->save();

        $request->session()->flash('success', 'Data has been recovered');

        return back();
    }

    public function delete(Request $request, $client_id)
    {
        $client = Client::find($client_id);
        $client->status = ClientStatus::INACTIVE; // mark data as inactive
        $client->save();

        $request->session()->flash('success', 'Data has been deleted');

        return back();
    }
}
