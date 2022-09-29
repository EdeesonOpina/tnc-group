<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Account;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number', // company receipt
        'bir_number', // receipt from bir
        'account_id', // company bank
        'inventory_id',
        'cart_id',
        'package_id',
        'item_id',
        'user_id', // customer
        'authorized_user_id', // employee
        'salesperson_id', // salesperson
        'branch_id', // where was the item purchased
        'discount', // discount in peso
        'price',
        'cost',
        'initial_downpayment',
        'payment_receipt_id',
        'remaining_balance',
        'is_credit', // apply credit basis or no
        'is_completion',
        'is_pos_transaction', // is the transaction made on pos
        'qty',
        'total', // w deductions
        'real_total', // w/o deductions
        'mop', // mode of payment
        'bank',
        'reference_name',
        'reference_number',
        'deposit_slip',
        'proof',
        'remarks',
        'delivered_date',
        'qty',
        'status',
    ];

    public function payment_receipt()
    {
        return $this->hasOne(PaymentReceipt::class, 'id', 'payment_receipt_id');
    }

    public function account()
    {
        return $this->hasOne(Account::class, 'id', 'account_id');
    }

    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'id', 'inventory_id');
    }

    public function goods_receipt()
    {
        return $this->hasOne(GoodsReceipt::class, 'id', 'goods_receipt_id');
    }

    public function item()
    {
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'id', 'cart_id');
    }

    public function package()
    {
        return $this->hasOne(Package::class, 'id', 'package_id');
    }

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function authorized_user()
    {
        return $this->hasOne(User::class, 'id', 'authorized_user_id');
    }

    public function salesperson()
    {
        return $this->hasOne(User::class, 'id', 'salesperson_id');
    }

    public function pos_grand_total_sales($from_date, $to_date)
    {
        return $this->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    /* pos sales count */
    public function pos_cash_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function pos_bank_deposit_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function pos_credit_card_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'credit-card')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function pos_cheque_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'cheque')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function pos_credit_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'credit')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function pos_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }
    /* pos sales count */

    /* pos sales total */
    public function pos_cash_total($from_date, $to_date)
    {
        return $this->where('mop', 'cash')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function pos_bank_deposit_total($from_date, $to_date)
    {
        return $this->where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function pos_credit_card_total($from_date, $to_date)
    {
        return $this->where('mop', 'credit-card')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function pos_cheque_total($from_date, $to_date)
    {
        return $this->where('mop', 'cheque')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function pos_credit_total($from_date, $to_date)
    {
        return $this->where('mop', 'credit')
                    ->where('is_pos_transaction', 1)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }
    /* pos sales total */

    /* online sales count */
    public function online_paypal_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'paypal')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function online_bank_deposit_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function online_stripe_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'stripe')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function online_cod_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('mop', 'cod')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }

    public function online_count($from_date, $to_date)
    {
        return $this->distinct('so_number')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->count();
    }
    /* online sales count */

    /* online sales total */
    public function online_paypal_total($from_date, $to_date)
    {
        return $this->where('mop', 'paypal')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function online_bank_deposit_total($from_date, $to_date)
    {
        return $this->where('mop', 'bank-deposit')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function online_stripe_total($from_date, $to_date)
    {
        return $this->where('mop', 'stripe')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function online_cod_total($from_date, $to_date)
    {
        return $this->where('mop', 'cod')
                    ->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }

    public function online_total($from_date, $to_date)
    {
        return $this->where('is_pos_transaction', 0)
                    ->whereBetween('created_at', [
                        $from_date, $to_date
                    ])->sum('total');
    }
    /* online sales total */
}
