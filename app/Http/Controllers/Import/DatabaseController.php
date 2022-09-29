<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use Mail;
use Validator;
use App\Models\Cart;
use App\Models\CartStatus;
use App\Models\Account;
use App\Models\AccountStatus;
use App\Models\User;
use App\Models\UserStatus;
use App\Models\DeliveryReceipt;
use App\Models\DeliveryReceiptStatus;
use App\Models\POSVat;
use App\Models\POSVatStatus;
use App\Models\POSDiscount;
use App\Models\POSDiscountStatus;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Supply;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptStatus;
use App\Models\Supplier;
use App\Models\ItemPhoto;
use App\Models\ItemStatus;
use App\Models\OrderStatus;
use App\Models\BrandStatus;
use App\Models\SubCategory;
use App\Models\SupplyStatus;
use App\Models\PurchaseOrder;
use App\Models\SupplierStatus;
use App\Models\CategoryStatus;
use App\Models\ItemPhotoStatus;
use App\Models\ItemSerialNumber;
use App\Models\SubCategoryStatus;
use App\Models\PurchaseOrderStatus;
use App\Models\ItemSerialNumberStatus;

class DatabaseController extends Controller
{
    public function categories(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Category;
                $data->id = $csv_data[0];
                $data->name = $csv_data[1];
                $data->description = $csv_data[2];
                $data->is_package = $csv_data[4];
                $data->sort_order = $csv_data[5];
                $data->status = $csv_data[6];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function sub_categories(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new SubCategory;
                $data->id = $csv_data[0];
                $data->category_id = $csv_data[1];
                $data->name = $csv_data[2];
                $data->description = $csv_data[3];
                $data->status = $csv_data[5];
                $data->is_package = $csv_data[6];
                $data->sort_order = $csv_data[7];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function brands(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Brand;
                $data->id = $csv_data[0];
                $data->name = $csv_data[1];
                $data->description = $csv_data[4];
                if ($csv_data[5] != 'NULL') {
                    $data->image = str_replace("main_assets/files/UploadedBrands/", "uploads/images/brands/", $csv_data[5]);
                }
                $data->status = $csv_data[6];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function items(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Item;
                $data->id = $csv_data[0];
                $data->category_id = $csv_data[1];
                $data->sub_category_id = $csv_data[2];
                $data->name = $csv_data[7];
                $data->description = $csv_data[9];
                if ($csv_data[10] != 'NULL') {
                    $data->image = str_replace("main_assets/files/UploadedItems/", "uploads/images/items/", $csv_data[10]);
                }
                if ($csv_data[11] != 'NULL') {
                    $data->barcode = $csv_data[11];
                }
                $data->status = $csv_data[12];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function item_photos(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $item = Item::find($csv_data[2]); // fetch the item
                $data = new ItemPhoto;
                $data->id = $csv_data[0];
                $data->name = $item->name . '-' . $row;
                $data->item_id = $csv_data[2];
                $data->image = str_replace("main_assets/files/UploadedItemImages/", "uploads/images/item-photos/", $csv_data[3]);
                $data->status = ItemPhotoStatus::ACTIVE;
                $data->save();

                $row++; // increment the row
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function item_serial_numbers(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new ItemSerialNumber;
                $data->id = $csv_data[0];
                $data->item_id = $csv_data[1];
                $data->code = $csv_data[2];
                $data->payment_id = $csv_data[3];
                if ($csv_data[4] == 1) {
                    $data->status = ItemSerialNumberStatus::AVAILABLE;
                } elseif ($csv_data[4] == 0) {
                    $data->status = ItemSerialNumberStatus::SOLD;
                } elseif ($csv_data[4] == 9) {
                    $data->status = ItemSerialNumberStatus::FLOATING;
                }
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function users(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 1;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new User;
                $data->id = $csv_data[0];
                $data->firstname = $csv_data[1];
                $data->lastname = $csv_data[2];
                $data->mobile = $csv_data[5];
                $data->line_address_1 = $csv_data[10];
                $data->branch_id = $csv_data[11];
                $data->role = $csv_data[12];
                if ($csv_data[13] != 'NULL') {
                    $data->email_verified_at = Carbon::now();
                }
                if (User::where('email', $csv_data[14])
                        ->exists()) {
                    $existing_email = User::where('email', $csv_data[14])
                        ->first()
                        ->email;
                    $data->email = $existing_email . '(' . $row . ')';
                } else {
                    $data->email = $csv_data[14]; 
                }
                
                $data->password = $csv_data[15];
                if ($csv_data[16] != 'NULL') {
                    $data->facebook = $csv_data[16];
                }
                if ($csv_data[17] != 'NULL') {
                    $data->google = $csv_data[17];
                }
                if ($csv_data[18] != 'NULL') {
                    $data->provider = $csv_data[18];
                }
                if ($csv_data[19] != 'NULL') {
                    $data->provider_id = $csv_data[19];
                }
                if ($csv_data[20] != 'NULL') {
                    $data->avatar = str_replace("system/files/UploadedAvatars/", "uploads/images/avatars/", $csv_data[20]);
                }
                if ($csv_data[21] != 'NULL') {
                    $data->signature = str_replace("system/files/UploadedSignatures/", "uploads/images/signatures/", $csv_data[21]);
                }
                $data->points = $csv_data[22];
                $data->status = UserStatus::ACTIVE;
                // $data->created_at = date('Y-m-d h:i:s', strtotime($csv_data[25]));
                // $data->updated_at = date('Y-m-d h:i:s', strtotime($csv_data[26]));
                $data->save();

                $row++;
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function suppliers(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Supplier;
                $data->id = $csv_data[0];
                $data->name = $csv_data[1];
                $data->description = $csv_data[2];
                $data->email = $csv_data[3];
                if ($csv_data[4] != 'NULL') {
                    $data->person = $csv_data[4];
                }
                $data->phone = $csv_data[5];
                $data->mobile = $csv_data[6];
                $data->line_address_1 = $csv_data[7];
                $data->line_address_2 = $csv_data[8];
                if ($csv_data[10] == '-1') {
                    $data->status = 0;
                } else {
                    $data->status = $csv_data[10];
                }
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function supplies(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Supply;
                $data->id = $csv_data[0];
                $data->supplier_id = $csv_data[1];
                $data->item_id = $csv_data[2];
                $data->price = $csv_data[3];
                $data->status = $csv_data[4];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function purchase_orders(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new PurchaseOrder;
                $data->id = $csv_data[0];
                $data->branch_id = $csv_data[1];
                $data->supplier_id = $csv_data[2];
                $data->reference_number = $csv_data[3];
                $data->created_by_user_id = $csv_data[8];
                $data->approved_by_user_id = $csv_data[9];
                if ($csv_data[11] != 'NULL') {
                    $data->approved_at = $csv_data[11];   
                }
                if ($csv_data[4] != 'NULL') {
                    $data->note = $csv_data[4];
                }
                $data->status = $csv_data[5];
                $data->created_at = $csv_data[6];
                $data->updated_at = $csv_data[7];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function pos_discounts(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                // if pos discount does not exist
                if (! POSDiscount::where('so_number', $csv_data[1])
                    ->exists()) {
                    $data = new POSDiscount;
                    $data->id = $csv_data[0];
                    $data->so_number = $csv_data[1];
                    if (Payment::where('so_number', $csv_data[1])
                            ->first()) {
                        $payment = Payment::where('so_number', $csv_data[1])
                                        ->first();
                        $data->payment_id = $payment->id;
                        $data->cart_id = $payment->cart_id;
                    }
                    $data->price = $csv_data[2];
                    $data->status = $csv_data[3];
                    $data->created_at = $csv_data[4];
                    $data->updated_at = $csv_data[5];
                    $data->save();
                }
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function pos_vat(Request $request)
    {
        $payments = Payment::all();
        $row = 0;
        foreach($payments->unique('so_number') as $payment) {
            // if pos vat does not exist
            if (! POSVat::where('so_number', $payment->so_number)
                ->where('payment_id', $payment->id)
                ->exists()) {
                $pos_vat = new POSVat;
                $pos_vat->so_number = $payment->so_number;
                $pos_vat->payment_id = $payment->id;
                $pos_vat->cart_id = $payment->cart_id;
                $pos_vat->price = $payment->initial_downpayment;
                $pos_vat->created_at = $payment->created_at;
                $pos_vat->updated_at = $payment->updated_at;
                $pos_vat->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function goods_receipts(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $purchase_order = PurchaseOrder::find($csv_data[1]);
                if ($purchase_order) {
                    $data = new GoodsReceipt;
                    $data->id = $csv_data[0];
                    $data->purchase_order_id = $csv_data[1];
                    $data->created_by_user_id = $purchase_order->created_by_user->id;
                    $data->reference_number = $csv_data[4];
                    if ($csv_data[6] != 'NULL') {
                        $data->note = $csv_data[6];
                    }
                    if ($csv_data[7] == 2) {
                        $data->status = GoodsReceiptStatus::CLEARED;
                    } elseif ($csv_data[7] == 1) {
                        $data->status = GoodsReceiptStatus::FULFILLING;
                    } else {
                        $data->status = GoodsReceiptStatus::CANCELLED;
                    }
                    
                    $data->created_at = $csv_data[8];
                    $data->updated_at = $csv_data[9];
                    $data->save();

                    if ($csv_data[5] != 'NULL') {
                        $delivery_receipt = new DeliveryReceipt;
                        $delivery_receipt->goods_receipt_id = $csv_data[0];
                        $delivery_receipt->received_by_user_id = 17; // user israel tala as the receiver
                        $delivery_receipt->delivery_receipt_number = $csv_data[5];
                        $delivery_receipt->status = DeliveryReceiptStatus::ACTIVE;
                        $delivery_receipt->save();
                    }
                }
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function delivery_receipts(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new DeliveryReceipt;
                $data->goods_receipt_id = $csv_data[2];
                $data->delivery_receipt_number = $csv_data[4];
                $data->received_by_user_id = 17; // user israel tala
                $data->status = $csv_data[5];
                $data->created_at = $csv_data[6];
                $data->updated_at = $csv_data[7];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function orders(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $purchase_order = PurchaseOrder::find($csv_data[1]);
                if ($purchase_order) {
                    $supply = Supply::where('supplier_id', $purchase_order->supplier->id)
                            ->where('item_id', $csv_data[4])
                            ->first() ?? null;

                    $data = new Order;
                    $data->id = $csv_data[0];
                    $data->purchase_order_id = $csv_data[1];
                    $data->goods_receipt_id = $csv_data[2];
                    $data->performed_by_user_id = $csv_data[3];
                    $data->supply_id = $supply->id ?? 0;
                    $data->item_id = $csv_data[4];
                    $data->qty = $csv_data[5];
                    $data->received_qty = $csv_data[6];
                    $data->free_qty = $csv_data[7];
                    $data->price = $csv_data[8];
                    $data->discount = $csv_data[9];
                    $data->total = str_replace(",", "", $csv_data[10]);
                    if ($csv_data[11] != 'NULL') {
                        $data->note = $csv_data[11];
                    }
                    $data->status = $csv_data[12];
                    $data->created_at = $csv_data[13];
                    $data->updated_at = $csv_data[14];
                    $data->save();
                }
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function inventories(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Inventory;
                $data->id = $csv_data[0];
                if ($csv_data[1] != 'NULL') {
                    $data->goods_receipt_id = $csv_data[1];
                }
                $data->branch_id = $csv_data[2];
                $data->item_id = $csv_data[3];
                $data->qty = $csv_data[4];
                $data->price = $csv_data[5];
                $data->agent_price = $csv_data[6];
                $data->discount = $csv_data[7];
                if ($csv_data[9] != 'NULL') {
                    $data->discount_from_date = $csv_data[9];
                }
                if ($csv_data[10] != 'NULL') {
                    $data->discount_to_date = $csv_data[10];
                }
                $data->points = $csv_data[11];
                $data->views = $csv_data[12];
                $data->status = $csv_data[13];
                $data->created_at = $csv_data[14];
                $data->updated_at = $csv_data[15];
                $data->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }

    public function payments(Request $request)
    {
        $this->validate($request, [
            'csv' => 'required'
        ]);

        // start row count
        $row = 0;

        /* read the file */
        if (($handle = fopen($request->file('csv'), "r")) !== false) {
            fgetcsv($handle);

            while (($csv_data = fgetcsv($handle, 1000, ",")) !== false) {
                $data = new Payment;
                $data->id = $csv_data[0];
                $data->so_number = $csv_data[1];
                if ($csv_data[2] != 'NULL') {
                    $data->bir_number = $csv_data[2];
                }
                if ($csv_data[3] != 'NULL') {
                    $data->invoice_number = $csv_data[3];
                }
                /* if data has a serial number */
                if ($csv_data[4] != 0) {
                    $item_serial_number = ItemSerialNumber::where('code', $csv_data[4])
                                                    ->first() ?? null; // serial number

                    if ($item_serial_number) {
                        $item_serial_number->payment_id = $csv_data[0] ?? 0; // payment id
                        $item_serial_number->status = ItemSerialNumberStatus::SOLD; // mark it as sold
                        $item_serial_number->save();
                    }
                }

                $data->account_id = Account::BDO; // save to main bank account
                $data->branch_id = $csv_data[14];
                $data->inventory_id = Inventory::where('item_id', $csv_data[9])
                                            ->where('branch_id', $csv_data[14])
                                            ->first()
                                            ->id ?? 0;
                $data->cart_id = $csv_data[7];
                $data->package_id = $csv_data[8];
                $data->item_id = $csv_data[9];

                /* if user does not exist */
                if ($csv_data[10] == 0) { 
                    if (User::where('firstname', $csv_data[12])
                            ->where('lastname', $csv_data[13])
                            ->exists()) { // find the user
                        $user = User::where('firstname', $csv_data[12])
                            ->where('lastname', $csv_data[13])
                            ->first();
                        $data->user_id = $user->id;
                    } else { // create a user
                        $user = new User;
                        $user->firstname = $csv_data[12]; // firstname
                        $user->lastname = $csv_data[13]; // lastname
                        $user->role = 'Customer';
                        $user->email = 'customer-' . $csv_data[12] . '.' . $csv_data[13] . '@tncpcwarehouse.com';
                        $user->password = bcrypt(rand());
                        $user->mobile = '-';
                        $user->branch_id = $csv_data[14];
                        $user->line_address_1 = $csv_data[15]; // address
                        $user->status = UserStatus::ACTIVE;
                        $user->save();

                        $data->user_id = $user->id;
                    }
                } else {
                    $data->user_id = $csv_data[10];
                }

                $data->authorized_user_id = $csv_data[11]; // cashier
                $data->is_pos_transaction = $csv_data[20];
                $data->is_credit = 0;
                $data->initial_downpayment = $csv_data[19];
                $data->price = $csv_data[16];
                $data->discount = $csv_data[17];
                $data->qty = $csv_data[21];
                $data->total = $csv_data[22];
                $data->real_total = $csv_data[23];

                if ($csv_data[24] == 'Cash') {
                    $data->mop = 'cash';
                } elseif ($csv_data[24] == 'Bank Deposit') {
                    $data->mop = 'bank-deposit';
                } elseif ($csv_data[24] == 'Credit') {
                    $data->mop = 'credit';
                } elseif ($csv_data[24] == 'Credit Card') {
                    $data->mop = 'credit-card';
                } elseif ($csv_data[24] == 'Cheque') {
                    $data->mop = 'cheque';
                } elseif ($csv_data[24] == 'Cash On Delivery') {
                    $data->mop = 'cod';
                }

                if ($csv_data[25] == '-1') {
                    $data->status = PaymentStatus::CANCELLED;
                } elseif ($csv_data[25] == 1) {
                    $data->status = PaymentStatus::CONFIRMED; 
                } elseif ($csv_data[25] == 2) {
                    $data->status = PaymentStatus::FOR_DELIVERY; 
                } elseif ($csv_data[25] == 3) {
                    $data->status = PaymentStatus::DELIVERED; 
                } elseif ($csv_data[25] == 0) {
                    $data->status = PaymentStatus::PENDING; 
                }
                
                if ($csv_data[31] != 'NULL') {
                    $data->delivered_date = $csv_data[31];
                }
                $data->created_at = $csv_data[32];
                $data->updated_at = $csv_data[33];
                $data->save();

                $cart = new Cart;
                $cart->inventory_id = $data->inventory_id;
                $cart->item_id = $data->item->id;
                $cart->package_id = $data->package_id;
                $cart->user_id = $data->user_id;
                $cart->authorized_user_id = $data->authorized_user_id;
                $cart->price = $data->price;
                $cart->qty = $data->qty;
                $cart->total = $data->total;
                $cart->status = CartStatus::CHECKED_OUT;
                $cart->created_at = $data->created_at;
                $cart->updated_at = $data->updated_at;
                $cart->save();
            }
        }
        $request->session()->flash('success', 'Data has been imported');
        return back();
    }
}
