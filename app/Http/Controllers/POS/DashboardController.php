<?php

namespace App\Http\Controllers\POS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Auth;
use Mail;
use Validator;
use App\Models\User;
use App\Models\Cart;
use App\Models\Item;
use App\Models\POSVat;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Country;
use App\Models\Payment;
use App\Models\Deduction;
use App\Models\Inventory;
use App\Models\ItemStatus;
use App\Models\UserStatus;
use App\Models\CartStatus;
use App\Models\POSDiscount;
use App\Models\BranchStatus;
use App\Models\POSVatStatus;
use App\Models\AccountStatus;
use App\Models\CountryStatus;
use App\Models\PaymentStatus;
use App\Models\DeductionStatus;
use App\Models\InventoryStatus;
use App\Models\ItemSerialNumber;
use App\Models\POSDiscountStatus;
use App\Models\ItemSerialNumberStatus;

class DashboardController extends Controller
{
    public function show()
    {
        return redirect()->route('pos.customer.new');
    }

    public function new()
    {
        $countries = Country::where('status', CountryStatus::ACTIVE)
                        ->get();

        return view('admin.pos.new', compact(
            'countries'
        ));
    }

    public function create(Request $request)
    {
        $rules = [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'mobile' => 'required',
            'email' => 'nullable',
            'country_id' => 'required',
            'line_address_1' => 'nullable',
            'line_address_2' => 'nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $data = request()->all(); // get all request
        $data['status'] = UserStatus::PENDING; // if you want to insert to a specific column
        $data['password'] = bcrypt(rand());
        User::create($data); // create data in a model

        // email variables for use function
        $user = User::where('email', $request->email)->first(); // fetch the user data after creations
        $name = $user->name;
        $email = $user->email;
        $subject = 'One more step! Please activate your account.';

        // send mail to user
        // Mail::send('auth.emails.register', [
        //     'user' => $user
        // ], function ($message) use ($name, $email, $subject) {
        //     $message->to($email, $name)
        //     ->from(env('APP_EMAIL'))
        //     ->subject($subject);
        // });

        $request->session()->flash('success', 'Data has been added');

        return redirect()->route('pos.standard', [$user->id]);
    }

    public function existing()
    {
        $users = User::where('status', '!=', UserStatus::INACTIVE)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('admin.pos.existing', compact(
            'users'
        ));
    }

    public function search(Request $request)
    {
        $name = $request->name ?? '*';
        $role = $request->role ?? '*';
        $status = $request->status ?? '*';
        $from_date = $request->from_date ?? '*';
        $to_date = $request->to_date ?? '*';

        return redirect()->route('pos.customer.existing.filter', [$name, $role, $status, $from_date, $to_date])->withInput();
    }

    public function filter($name, $role, $status, $from_date, $to_date)
    {
        $query = User::orderBy('created_at', 'desc');

        if ($name != '*') {
            $query->whereRaw("concat(firstname, ' ', lastname) like '%" . $name . "%' ");
            
            /* original query */
            // $query->where('firstname', $name);
            // $query->orWhere('lastname', $name);
        }

        if ($role != '*') {
            $query->where('role', $role);
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

        $users = $query->paginate(15);

        return view('admin.pos.existing', compact(
            'users'
        ));
    }
}
