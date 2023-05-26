<?php

use Illuminate\Support\Facades\Route;

// Route::get('/','App\Http\Controllers\Site\SiteController@index')->name('site.index');
// Route::get('/about','App\Http\Controllers\Site\SiteController@about')->name('site.about');
// Route::get('/contact','App\Http\Controllers\Site\SiteController@contact')->name('site.contact');
// Route::get('/services/{page_id}','App\Http\Controllers\Site\SiteController@services')->name('site.services');
// Route::get('/business-units','App\Http\Controllers\Site\SiteController@business_units')->name('site.business-units');

// dashboard group
Route::group(['prefix' => 'dashboard/', 'middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\Auth\DashboardController@show')->name('auth.dashboard');
});

Route::get('/', 'App\Http\Controllers\Front\Site\SiteController@index')->name('site.index');

// hr group
Route::group(['prefix' => 'hr/', 'middleware' => ['auth', 'internal']], function () {

    // payslip
    Route::group(['prefix' => 'payslip/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\HR\PayslipController@show')->name('hr.payslips');
        Route::get('/view/{user_id}', 'App\Http\Controllers\Admin\HR\PayslipController@view')->name('hr.payslips.view');
        Route::get('/details/{payslip_id}', 'App\Http\Controllers\Admin\HR\PayslipController@details')->name('hr.payslips.details');
        Route::get('/summary/{payslip_id}', 'App\Http\Controllers\Admin\HR\PayslipController@summary')->name('hr.payslips.summary');
        Route::get('/manage/{user_id}', 'App\Http\Controllers\Admin\HR\PayslipController@manage')->name('hr.payslips.manage');
        Route::get('/delete/{user_id}', 'App\Http\Controllers\Admin\HR\PayslipController@delete')->name('hr.payslips.delete');
        Route::get('/recover/{user_id}', 'App\Http\Controllers\Admin\HR\PayslipController@recover')->name('hr.payslips.recover');
        Route::get('/create/date-select/{user_id}', 'App\Http\Controllers\Admin\HR\PayslipController@date_select')->name('hr.payslips.date-select');
        Route::post('/create/date-select', 'App\Http\Controllers\Admin\HR\PayslipController@date_select_search')->name('hr.payslips.date-select.search');
        Route::get('/create/deductions/{user_id}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\HR\PayslipController@deductions')->name('hr.payslips.deductions');
        Route::post('/create/deductions', 'App\Http\Controllers\Admin\HR\PayslipController@apply_deductions')->name('hr.payslips.deductions.apply');

        Route::get('/add/time/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@add')->name('hr.payslips.time.add');
        Route::post('/create/time', 'App\Http\Controllers\Admin\HR\TimeController@create')->name('hr.payslips.time.create');
        Route::get('/edit/time/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@edit')->name('hr.payslips.time.edit');
        Route::post('/edit/time', 'App\Http\Controllers\Admin\HR\TimeController@update')->name('hr.payslips.time.update');
        Route::get('/edit/time/approve/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@approve')->name('hr.payslips.time.approve');
        Route::get('/edit/time/disapprove/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@disapprove')->name('hr.payslips.time.disapprove');

        Route::get('/approve/time/all/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@approve_all')->name('hr.payslips.time.approve-all');
        Route::get('/disapprove/time/all/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@disapprove_all')->name('hr.payslips.time.disapprove-all');
        Route::get('/delete/time/all/{user_id}', 'App\Http\Controllers\Admin\HR\TimeController@delete_all')->name('hr.payslips.time.delete-all');

        Route::post('/update/user/resign', 'App\Http\Controllers\Admin\HR\UserController@resign')->name('hr.users.update.resign');
        Route::post('/update/user/salary', 'App\Http\Controllers\Admin\HR\UserController@salary')->name('hr.users.update.salary');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/details/{payslip_id}', 'App\Http\Controllers\Export\PayslipController@print_details')->name('hr.payslips.details.print');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\HR\PayslipController@search')->name('hr.payslips.search');
            Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\HR\PayslipController@filter')->name('hr.payslips.filter');
        });
    });
});

// accounting group
Route::group(['prefix' => 'accounting/', 'middleware' => ['auth', 'accounting']], function () {

    // payables 
    Route::group(['prefix' => 'payables/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\PayableController@show')->name('accounting.payables');
        Route::get('/view', 'App\Http\Controllers\Admin\PayableController@view')->name('accounting.payables.view');
        Route::post('/create', 'App\Http\Controllers\Admin\PayableController@create')->name('accounting.payables.create');
        Route::get('/cancel/{payable_id}', 'App\Http\Controllers\Admin\PayableController@cancel')->name('accounting.payables.cancel');
        Route::get('/recover/{payable_id}', 'App\Http\Controllers\Admin\PayableController@recover')->name('accounting.payables.recover');
        Route::get('/delete/{payable_id}', 'App\Http\Controllers\Admin\PayableController@delete')->name('accounting.payables.delete');
        Route::post('/update/date-released', 'App\Http\Controllers\Admin\PayableController@date_released')->name('accounting.payables.update.date-released');

        Route::get('/database/update', 'App\Http\Controllers\Admin\PayableController@database_update')->name('accounting.payables.database.update');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{payable_id}', 'App\Http\Controllers\Export\PayableController@print')->name('accounting.payables.print');
            Route::get('excel/{payable_id}', 'App\Http\Controllers\Export\PayableController@excel')->name('accounting.payables.excel');
            Route::get('pdf/{payable_id}', 'App\Http\Controllers\Export\PayableController@pdf')->name('accounting.payables.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\PayableController@sql')->name('accounting.payables.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\PayableController@search')->name('accounting.payables.search');
            Route::get('/{po_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\PayableController@filter')->name('accounting.payables.filter');
        });
    });

    // liquidations
    Route::group(['prefix' => 'liquidations/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@show')->name('accounting.liquidations');

        Route::get('/add', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@add')->name('accounting.liquidations.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@create')->name('accounting.liquidations.create');
        Route::get('/view/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@view')->name('accounting.liquidations.view');
        Route::get('/edit/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@edit')->name('accounting.liquidations.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@update')->name('accounting.liquidations.update');
        Route::get('/approve/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@approve')->name('accounting.liquidations.approve');
        Route::get('/disapprove/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@disapprove')->name('accounting.liquidations.disapprove');
        Route::get('/delete/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@delete')->name('accounting.liquidations.delete');
        Route::get('/recover/{liquidation_id}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@recover')->name('accounting.liquidations.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@search')->name('accounting.liquidations.search');
            Route::get('{description}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Liquidation\LiquidationController@filter')->name('accounting.liquidations.filter');
        });
    });

    // expenses
    Route::group(['prefix' => 'expenses/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Expense\ExpenseController@show')->name('accounting.expenses');

        Route::get('/add', 'App\Http\Controllers\Admin\Expense\ExpenseController@add')->name('accounting.expenses.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Expense\ExpenseController@create')->name('accounting.expenses.create');
        Route::get('/view/{expense_id}', 'App\Http\Controllers\Admin\Expense\ExpenseController@view')->name('accounting.expenses.view');
        Route::get('/edit/{expense_id}', 'App\Http\Controllers\Admin\Expense\ExpenseController@edit')->name('accounting.expenses.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Expense\ExpenseController@update')->name('accounting.expenses.update');
        Route::get('/delete/{expense_id}', 'App\Http\Controllers\Admin\Expense\ExpenseController@delete')->name('accounting.expenses.delete');
        Route::get('/recover/{expense_id}', 'App\Http\Controllers\Admin\Expense\ExpenseController@recover')->name('accounting.expenses.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Expense\ExpenseController@search')->name('accounting.expenses.search');
            Route::get('{description}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Expense\ExpenseController@filter')->name('accounting.expenses.filter');
        });
    });

    // expense companies
    Route::group(['prefix' => 'expense-companies/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Expense\CompanyController@show')->name('accounting.expense-companies');

        Route::get('/add', 'App\Http\Controllers\Admin\Expense\CompanyController@add')->name('accounting.expense-companies.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Expense\CompanyController@create')->name('accounting.expense-companies.create');
        Route::get('/view/{expense_company_id}', 'App\Http\Controllers\Admin\Expense\CompanyController@view')->name('accounting.expense-companies.view');
        Route::get('/edit/{expense_company_id}', 'App\Http\Controllers\Admin\Expense\CompanyController@edit')->name('accounting.expense-companies.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Expense\CompanyController@update')->name('accounting.expense-companies.update');
        Route::get('/delete/{expense_company_id}', 'App\Http\Controllers\Admin\Expense\CompanyController@delete')->name('accounting.expense-companies.delete');
        Route::get('/recover/{expense_company_id}', 'App\Http\Controllers\Admin\Expense\CompanyController@recover')->name('accounting.expense-companies.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Expense\CompanyController@search')->name('accounting.expense-companies.search');
            Route::get('{name}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Expense\CompanyController@filter')->name('accounting.expense-companies.filter');
        });
    });

    // payment credits
    Route::group(['prefix' => 'payment-credits/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\PaymentCreditController@show')->name('accounting.payment-credits');
        Route::get('/database/update', 'App\Http\Controllers\Admin\PaymentCreditController@database_update')->name('accounting.payment-credits.database.update');
        Route::get('/complete/{payment_credit_record_id}', 'App\Http\Controllers\Admin\PaymentCreditController@complete')->name('accounting.payment-credits.complete');

        // payment credit records
        Route::group(['prefix' => 'view/{so_number}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\PaymentCreditController@view')->name('accounting.payment-credits.view');
            Route::get('/approve/{payment_credit_record_id}', 'App\Http\Controllers\Admin\PaymentCreditRecordController@approve')->name('accounting.payment-credits.approve');
            Route::get('/disapprove/{payment_credit_record_id}', 'App\Http\Controllers\Admin\PaymentCreditRecordController@disapprove')->name('accounting.payment-credits.disapprove');
        });

        Route::post('/assign/invoice-number', 'App\Http\Controllers\Admin\PaymentCreditController@invoiceNumber')->name('accounting.payment-credits.assign.invoice-number');
        Route::post('/pay', 'App\Http\Controllers\Admin\PaymentCreditController@pay')->name('accounting.payment-credits.pay');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{so_number}', 'App\Http\Controllers\Export\PaymentCreditController@print')->name('accounting.payment-credits.print');
            Route::get('excel/{so_number}', 'App\Http\Controllers\Export\PaymentCreditController@excel')->name('accounting.payment-credits.excel');
            Route::get('pdf/{so_number}', 'App\Http\Controllers\Export\PaymentCreditController@pdf')->name('accounting.payment-credits.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\PaymentCreditController@sql')->name('accounting.payment-credits.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\PaymentCreditController@search')->name('accounting.payment-credits.search');
            Route::get('/{so_number}/{bir_number}/{invoice_number}/{name}/{salesperson_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\PaymentCreditController@filter')->name('accounting.payment-credits.filter');
        });
    });

    // cash advances
    Route::group(['prefix' => 'cash-advances/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@show')->name('accounting.cash-advances');
        Route::get('/add', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@add')->name('accounting.cash-advances.add');
        Route::post('/create', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@create')->name('accounting.cash-advances.create');

        Route::get('/approve/{cash_advance_id}', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@approve')->name('accounting.cash-advances.approve');
        Route::get('/disapprove/{cash_advance_id}', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@disapprove')->name('accounting.cash-advances.disapprove');
        Route::get('/final-approve/{cash_advance_id}', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@final_approve')->name('accounting.cash-advances.final-approve');

        // cash advance payment
        Route::group(['prefix' => 'view/{ca_number}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@view')->name('accounting.cash-advances.view');
            Route::get('/approve/{cash_advance_payment_id}', 'App\Http\Controllers\Admin\CashAdvance\PaymentController@approve')->name('accounting.cash-advance-payments.approve');
            Route::get('/disapprove/{cash_advance_payment_id}', 'App\Http\Controllers\Admin\CashAdvance\PaymentController@disapprove')->name('accounting.cash-advance-payments.disapprove');
        });

        Route::post('/assign/invoice-number', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@invoiceNumber')->name('accounting.cash-advances.assign.invoice-number');
        Route::post('/pay', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@pay')->name('accounting.cash-advances.pay');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@print')->name('accounting.cash-advances.print');
            Route::get('excel/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@excel')->name('accounting.cash-advances.excel');
            Route::get('pdf/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@pdf')->name('accounting.cash-advances.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\CashAdvanceController@sql')->name('accounting.cash-advances.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@search')->name('accounting.cash-advances.search');
            Route::get('/{ca_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CashAdvance\CashAdvanceController@filter')->name('accounting.cash-advances.filter');
        });
    });
});

// admin group
Route::group(['prefix' => 'admin/', 'middleware' => ['auth', 'internal']], function () {
    // users
    Route::group(['prefix' => 'users/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\UserController@show')->name('admin.users');
        Route::get('/add', 'App\Http\Controllers\Admin\UserController@add')->name('admin.users.add');
        Route::get('/corporate/add', 'App\Http\Controllers\Admin\UserController@corporate_add')->name('admin.users.corporate.add');
        Route::post('/create', 'App\Http\Controllers\Admin\UserController@create')->name('admin.users.create');
        Route::post('/corporate/create', 'App\Http\Controllers\Admin\UserController@corporate_create')->name('admin.users.corporate.create');
        Route::get('/view/{user_id}', 'App\Http\Controllers\Admin\UserController@view')->name('admin.users.view');
        Route::get('/edit/{user_id}', 'App\Http\Controllers\Admin\UserController@edit')->name('admin.users.edit');
        Route::get('/corporate/edit/{user_id}', 'App\Http\Controllers\Admin\UserController@corporate_edit')->name('admin.users.corporate.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\UserController@update')->name('admin.users.update');
        Route::post('/corporate/edit', 'App\Http\Controllers\Admin\UserController@corporate_update')->name('admin.users.corporate.update');
        Route::get('/resend/email/{user_id}', 'App\Http\Controllers\Admin\UserController@resend')->name('admin.users.resend.email');
        Route::post('/set/password', 'App\Http\Controllers\Admin\UserController@password')->name('admin.users.set-password');
        Route::get('/delete/{user_id}', 'App\Http\Controllers\Admin\UserController@delete')->name('admin.users.delete');
        Route::get('/recover/{user_id}', 'App\Http\Controllers\Admin\UserController@recover')->name('admin.users.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\UserController@search')->name('admin.users.search');
            Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\UserController@filter')->name('admin.users.filter');
        });
    });

    // inventories
    Route::group(['prefix' => 'inventories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Inventory\InventoryController@show')->name('internals.inventories');
        Route::get('/view/{branch_id}', 'App\Http\Controllers\Admin\Inventory\InventoryController@view')->name('internals.inventories.view');

        // management
        Route::group(['prefix' => 'manage/{branch_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\Inventory\InventoryController@manage')->name('internals.inventories.manage');
            Route::post('/set-price', 'App\Http\Controllers\Admin\Inventory\InventoryController@price')->name('internals.inventories.items.set-price');
            Route::post('/set-discount', 'App\Http\Controllers\Admin\Inventory\InventoryController@discount')->name('internals.inventories.items.set-discount');
            Route::post('/set-barcode', 'App\Http\Controllers\Admin\Inventory\InventoryController@barcode')->name('internals.inventories.items.set-barcode');

            // management
            Route::group(['prefix' => 'landing-price/{inventory_id}'], function () {
                Route::get('/', 'App\Http\Controllers\Admin\Inventory\ReceiveRecordController@masterlist')->name('internals.inventories.landing-price.masterlist');
                Route::post('/create', 'App\Http\Controllers\Admin\Inventory\ReceiveRecordController@create')->name('internals.inventories.landing-price.create');
            });

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\Inventory\InventoryController@search')->name('internals.inventories.items.search');
                Route::get('/{barcode}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Inventory\InventoryController@filter')->name('internals.inventories.items.filter');
            });
        });

        // item serial numbers
        Route::group(['prefix' => 'serial-numbers/items/{item_id}/'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\Item\SerialNumberController@show')->name('internals.inventories.items.serial-numbers');
            Route::post('/create', 'App\Http\Controllers\Admin\Item\SerialNumberController@create')->name('internals.inventories.items.serial-numbers.create');
            Route::get('/edit/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@edit')->name('internals.inventories.items.serial-numbers.edit');
            Route::post('/edit', 'App\Http\Controllers\Admin\Item\SerialNumberController@update')->name('internals.inventories.items.serial-numbers.update');
            Route::post('/assign-so', 'App\Http\Controllers\Admin\Item\SerialNumberController@assignSO')->name('internals.inventories.items.serial-numbers.assign-so');

            Route::get('/available/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@available')->name('internals.inventories.items.serial-numbers.available');
            Route::get('/floating/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@floating')->name('internals.inventories.items.serial-numbers.floating');
            Route::get('/revert/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@revert')->name('internals.inventories.items.serial-numbers.revert');
            Route::get('/sold/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@sold')->name('internals.inventories.items.serial-numbers.sold');
            Route::get('/delete/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@delete')->name('internals.inventories.items.serial-numbers.delete');
            Route::get('/recover/{item_serial_number_id}', 'App\Http\Controllers\Admin\Item\SerialNumberController@recover')->name('internals.inventories.items.serial-numbers.recover');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\Item\SerialNumberController@search')->name('internals.inventories.items.serial-numbers.search');
                Route::get('/{code}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Item\SerialNumberController@filter')->name('internals.inventories.items.serial-numbers.filter');
            });
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{company_id}', 'App\Http\Controllers\Export\InventoryController@print')->name('internals.inventories.print');
            Route::get('excel/{company_id}', 'App\Http\Controllers\Export\InventoryController@excel')->name('internals.inventories.excel');
            Route::get('pdf/{company_id}', 'App\Http\Controllers\Export\InventoryController@pdf')->name('internals.inventories.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\InventoryController@sql')->name('internals.inventories.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Inventory\CompanyController@search')->name('internals.inventories.companies.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Inventory\CompanyController@filter')->name('internals.inventories.companies.filter');
        });
    });

    // items
    Route::group(['prefix' => 'items/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Item\ItemController@show')->name('admin.items');
        Route::get('/add', 'App\Http\Controllers\Admin\Item\ItemController@add')->name('admin.items.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Item\ItemController@create')->name('admin.items.create');
        Route::get('/view/{item_id}', 'App\Http\Controllers\Admin\Item\ItemController@view')->name('admin.items.view');
        Route::get('/edit/{item_id}', 'App\Http\Controllers\Admin\Item\ItemController@edit')->name('admin.items.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Item\ItemController@update')->name('admin.items.update');
        Route::get('/delete/{item_id}', 'App\Http\Controllers\Admin\Item\ItemController@delete')->name('admin.items.delete');
        Route::get('/recover/{item_id}', 'App\Http\Controllers\Admin\Item\ItemController@recover')->name('admin.items.recover');

        // items
        Route::group(['prefix' => 'photos/{item_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\Item\PhotoController@show')->name('admin.items.photos');
            Route::post('/create', 'App\Http\Controllers\Admin\Item\PhotoController@create')->name('admin.items.photos.create');
            Route::post('/edit', 'App\Http\Controllers\Admin\Item\PhotoController@update')->name('admin.items.photos.update');
            Route::get('/delete/{item_photo_id}', 'App\Http\Controllers\Admin\Item\PhotoController@delete')->name('admin.items.photos.delete');
            Route::get('/recover/{item_photo_id}', 'App\Http\Controllers\Admin\Item\PhotoController@recover')->name('admin.items.photos.recover');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\Item\PhotoController@search')->name('admin.items.photos.search');
                Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Item\PhotoController@filter')->name('admin.items.photos.filter');
            });
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Item\ItemController@search')->name('admin.items.search');
            Route::get('/{barcode}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Item\ItemController@filter')->name('admin.items.filter');
        });
    });

    // suppliers
    Route::group(['prefix' => 'suppliers/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\SupplierController@show')->name('admin.suppliers');
        Route::get('/add', 'App\Http\Controllers\Admin\SupplierController@add')->name('admin.suppliers.add');
        Route::post('/create', 'App\Http\Controllers\Admin\SupplierController@create')->name('admin.suppliers.create');
        Route::get('/view/{supplier_id}', 'App\Http\Controllers\Admin\SupplierController@view')->name('admin.suppliers.view');
        Route::get('/edit/{supplier_id}', 'App\Http\Controllers\Admin\SupplierController@edit')->name('admin.suppliers.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\SupplierController@update')->name('admin.suppliers.update');
        Route::get('/delete/{supplier_id}', 'App\Http\Controllers\Admin\SupplierController@delete')->name('admin.suppliers.delete');
        Route::get('/recover/{supplier_id}', 'App\Http\Controllers\Admin\SupplierController@recover')->name('admin.suppliers.recover');

        // manage
        Route::group(['prefix' => 'manage/{supplier_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\SupplierController@manage')->name('admin.suppliers.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\SupplyController@masterlist')->name('admin.suppliers.items.masterlist');

            // supplies
            Route::group(['prefix' => 'supplies'], function () {
                Route::post('/create', 'App\Http\Controllers\Admin\SupplyController@create')->name('admin.supplies.create');
                Route::post('/update/price', 'App\Http\Controllers\Admin\SupplyController@price')->name('admin.supplies.update.price');
                Route::get('/recover/{supply_id}', 'App\Http\Controllers\Admin\SupplyController@recover')->name('admin.supplies.recover');
                Route::get('/delete/{supply_id}', 'App\Http\Controllers\Admin\SupplyController@delete')->name('admin.supplies.delete');

                // for searching supplies
                Route::group(['prefix' => 'search/'], function () {
                    Route::post('/', 'App\Http\Controllers\Admin\SupplyController@search')->name('admin.supplies.search');
                    Route::get('/{name}', 'App\Http\Controllers\Admin\SupplyController@filter')->name('admin.supplies.filter');
                });
            });
        });

        // for searching suppliers
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\SupplierController@search')->name('admin.suppliers.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\SupplierController@filter')->name('admin.suppliers.filter');
        });
    });

    // clients
    Route::group(['prefix' => 'clients/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Client\ClientController@show')->name('admin.clients');
        Route::get('/add', 'App\Http\Controllers\Admin\Client\ClientController@add')->name('admin.clients.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Client\ClientController@create')->name('admin.clients.create');
        Route::get('/view/{supplier_id}', 'App\Http\Controllers\Admin\Client\ClientController@view')->name('admin.clients.view');
        Route::get('/edit/{supplier_id}', 'App\Http\Controllers\Admin\Client\ClientController@edit')->name('admin.clients.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Client\ClientController@update')->name('admin.clients.update');
        Route::get('/delete/{supplier_id}', 'App\Http\Controllers\Admin\Client\ClientController@delete')->name('admin.clients.delete');
        Route::get('/recover/{supplier_id}', 'App\Http\Controllers\Admin\Client\ClientController@recover')->name('admin.clients.recover');

        // manage
        Route::group(['prefix' => 'manage/{supplier_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\Client\ClientController@manage')->name('admin.clients.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\SupplyController@masterlist')->name('admin.clients.items.masterlist');

            // supplies
            Route::group(['prefix' => 'supplies'], function () {
                // Route::post('/create', 'App\Http\Controllers\Admin\SupplyController@create')->name('admin.supplies.create');
                // Route::post('/update/price', 'App\Http\Controllers\Admin\SupplyController@price')->name('admin.supplies.update.price');
                // Route::get('/recover/{supply_id}', 'App\Http\Controllers\Admin\SupplyController@recover')->name('admin.supplies.recover');
                // Route::get('/delete/{supply_id}', 'App\Http\Controllers\Admin\SupplyController@delete')->name('admin.supplies.delete');

                // for searching supplies
                // Route::group(['prefix' => 'search/'], function () {
                //     Route::post('/', 'App\Http\Controllers\Admin\SupplyController@search')->name('admin.supplies.search');
                //     Route::get('/{name}', 'App\Http\Controllers\Admin\SupplyController@filter')->name('admin.supplies.filter');
                // });
            });
        });

        // for searching clients
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Client\ClientController@search')->name('admin.clients.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Client\ClientController@filter')->name('admin.clients.filter');
        });
    });

    // client contacts
    Route::group(['prefix' => 'clients/contacts/'], function () {
        Route::post('/edit', 'App\Http\Controllers\Admin\Client\ContactController@update')->name('admin.clients.contact.update');
        Route::post('/create', 'App\Http\Controllers\Admin\Client\ContactController@create')->name('admin.clients.contact.create');
        Route::get('/delete/{client_id}', 'App\Http\Controllers\Admin\Client\ContactController@delete')->name('admin.clients.contact.delete');
        Route::get('/recover/{client_id}', 'App\Http\Controllers\Admin\Client\ContactController@recover')->name('admin.clients.contact.recover');
    });

    // brands
    Route::group(['prefix' => 'brands/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\BrandController@show')->name('admin.brands');
        Route::get('/add', 'App\Http\Controllers\Admin\BrandController@add')->name('admin.brands.add');
        Route::post('/create', 'App\Http\Controllers\Admin\BrandController@create')->name('admin.brands.create');
        Route::get('/edit/{brand_id}', 'App\Http\Controllers\Admin\BrandController@edit')->name('admin.brands.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\BrandController@update')->name('admin.brands.update');
        Route::get('/delete/{brand_id}', 'App\Http\Controllers\Admin\BrandController@delete')->name('admin.brands.delete');
        Route::get('/recover/{brand_id}', 'App\Http\Controllers\Admin\BrandController@recover')->name('admin.brands.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\BrandController@search')->name('admin.brands.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\BrandController@filter')->name('admin.brands.filter');
        });
    });

    // deductions
    Route::group(['prefix' => 'deductions/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\DeductionController@show')->name('admin.deductions');
        Route::get('/add', 'App\Http\Controllers\Admin\DeductionController@add')->name('admin.deductions.add');
        Route::post('/create', 'App\Http\Controllers\Admin\DeductionController@create')->name('admin.deductions.create');
        Route::get('/edit/{deduction_id}', 'App\Http\Controllers\Admin\DeductionController@edit')->name('admin.deductions.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\DeductionController@update')->name('admin.deductions.update');
        Route::get('/delete/{deduction_id}', 'App\Http\Controllers\Admin\DeductionController@delete')->name('admin.deductions.delete');
        Route::get('/recover/{deduction_id}', 'App\Http\Controllers\Admin\DeductionController@recover')->name('admin.deductions.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\DeductionController@search')->name('admin.deductions.search');
            Route::get('/{type}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\DeductionController@filter')->name('admin.deductions.filter');
        });
    });

    // branches
    Route::group(['prefix' => 'companies/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\CompanyController@show')->name('admin.companies');
        Route::get('/add', 'App\Http\Controllers\Admin\CompanyController@add')->name('admin.companies.add');
        Route::post('/create', 'App\Http\Controllers\Admin\CompanyController@create')->name('admin.companies.create');
        Route::get('/edit/{branch_id}', 'App\Http\Controllers\Admin\CompanyController@edit')->name('admin.companies.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\CompanyController@update')->name('admin.companies.update');
        Route::get('/delete/{branch_id}', 'App\Http\Controllers\Admin\CompanyController@delete')->name('admin.companies.delete');
        Route::get('/recover/{branch_id}', 'App\Http\Controllers\Admin\CompanyController@recover')->name('admin.companies.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CompanyController@search')->name('admin.companies.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CompanyController@filter')->name('admin.companies.filter');
        });
    });

    // accounts
    Route::group(['prefix' => 'accounts/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\AccountController@show')->name('admin.accounts');
        Route::get('/add', 'App\Http\Controllers\Admin\AccountController@add')->name('admin.accounts.add');
        Route::post('/create', 'App\Http\Controllers\Admin\AccountController@create')->name('admin.accounts.create');
        Route::get('/edit/{account_id}', 'App\Http\Controllers\Admin\AccountController@edit')->name('admin.accounts.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\AccountController@update')->name('admin.accounts.update');
        Route::get('/delete/{account_id}', 'App\Http\Controllers\Admin\AccountController@delete')->name('admin.accounts.delete');
        Route::get('/recover/{account_id}', 'App\Http\Controllers\Admin\AccountController@recover')->name('admin.accounts.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\AccountController@search')->name('admin.accounts.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\AccountController@filter')->name('admin.accounts.filter');
        });
    });

    // categories
    Route::group(['prefix' => 'categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\CategoryController@show')->name('admin.categories');
        Route::get('/add', 'App\Http\Controllers\Admin\CategoryController@add')->name('admin.categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\CategoryController@create')->name('admin.categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\CategoryController@edit')->name('admin.categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\CategoryController@update')->name('admin.categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\CategoryController@delete')->name('admin.categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\CategoryController@recover')->name('admin.categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CategoryController@search')->name('admin.categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CategoryController@filter')->name('admin.categories.filter');
        });
    });

    // project categories
    Route::group(['prefix' => 'project-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Project\CategoryController@show')->name('admin.project-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\Project\CategoryController@add')->name('admin.project-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Project\CategoryController@create')->name('admin.project-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\Project\CategoryController@edit')->name('admin.project-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Project\CategoryController@update')->name('admin.project-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\Project\CategoryController@delete')->name('admin.project-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\Project\CategoryController@recover')->name('admin.project-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Project\CategoryController@search')->name('admin.project-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Project\CategoryController@filter')->name('admin.project-categories.filter');
        });
    });

    // project sub categories
    Route::group(['prefix' => 'project-sub-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Project\SubCategoryController@show')->name('admin.project-sub-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\Project\SubCategoryController@add')->name('admin.project-sub-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Project\SubCategoryController@create')->name('admin.project-sub-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\Project\SubCategoryController@edit')->name('admin.project-sub-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Project\SubCategoryController@update')->name('admin.project-sub-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\Project\SubCategoryController@delete')->name('admin.project-sub-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\Project\SubCategoryController@recover')->name('admin.project-sub-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Project\SubCategoryController@search')->name('admin.project-sub-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Project\SubCategoryController@filter')->name('admin.project-sub-categories.filter');
        });
    });

    // liquidation categories
    Route::group(['prefix' => 'liquidation-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Liquidation\CategoryController@show')->name('admin.liquidation-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\Liquidation\CategoryController@add')->name('admin.liquidation-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Liquidation\CategoryController@create')->name('admin.liquidation-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\Liquidation\CategoryController@edit')->name('admin.liquidation-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Liquidation\CategoryController@update')->name('admin.liquidation-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\Liquidation\CategoryController@delete')->name('admin.liquidation-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\Liquidation\CategoryController@recover')->name('admin.liquidation-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Liquidation\CategoryController@search')->name('admin.liquidation-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Liquidation\CategoryController@filter')->name('admin.liquidation-categories.filter');
        });
    });

    // sub categories
    Route::group(['prefix' => 'sub-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\SubCategoryController@show')->name('admin.sub-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\SubCategoryController@add')->name('admin.sub-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\SubCategoryController@create')->name('admin.sub-categories.create');
        Route::get('/edit/{sub_category_id}', 'App\Http\Controllers\Admin\SubCategoryController@edit')->name('admin.sub-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\SubCategoryController@update')->name('admin.sub-categories.update');
        Route::get('/delete/{sub_category_id}', 'App\Http\Controllers\Admin\SubCategoryController@delete')->name('admin.sub-categories.delete');
        Route::get('/recover/{sub_category_id}', 'App\Http\Controllers\Admin\SubCategoryController@recover')->name('admin.sub-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CategoryController@search')->name('admin.categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CategoryController@filter')->name('admin.categories.filter');
        });
    });

    // boards
    Route::group(['prefix' => 'boards/tasks'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Board\TaskController@show')->name('internals.boards.tasks');
        Route::post('/create', 'App\Http\Controllers\Admin\Board\TaskController@create')->name('internals.boards.tasks.create');
        Route::get('/view/{task_id}', 'App\Http\Controllers\Admin\Board\TaskController@view')->name('internals.boards.tasks.view');
        Route::get('/edit/{task_id}', 'App\Http\Controllers\Admin\Board\TaskController@edit')->name('internals.boards.tasks.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Board\TaskController@update')->name('internals.boards.tasks.update');
        Route::get('/delete/{task_id}', 'App\Http\Controllers\Admin\Board\TaskController@delete')->name('internals.boards.tasks.delete');
        Route::get('/recover/{task_id}', 'App\Http\Controllers\Admin\Board\TaskController@recover')->name('internals.boards.tasks.recover');
    });

    // projects
    Route::group(['prefix' => 'projects/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\Project\ProjectController@show')->name('internals.projects');
        Route::get('/add', 'App\Http\Controllers\Admin\Project\ProjectController@add')->name('internals.projects.add');
        Route::post('/create', 'App\Http\Controllers\Admin\Project\ProjectController@create')->name('internals.projects.create');
        Route::get('/view/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@view')->name('internals.projects.view');
        Route::get('/edit/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@edit')->name('internals.projects.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\Project\ProjectController@update')->name('internals.projects.update');
        Route::get('/approve/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@approve')->name('internals.projects.approve');
        Route::post('/disapprove', 'App\Http\Controllers\Admin\Project\ProjectController@disapprove')->name('internals.projects.disapprove');
        Route::get('/done/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@done')->name('internals.projects.done');
        Route::get('/open-for-editing/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@open_for_editing')->name('internals.projects.open-for-editing');
        Route::get('/delete/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@delete')->name('internals.projects.delete');
        Route::get('/recover/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@recover')->name('internals.projects.recover');
        Route::get('/for-approval/{project_id}', 'App\Http\Controllers\Admin\Project\ProjectController@for_approval')->name('internals.projects.for-approval');

        Route::post('/margin', 'App\Http\Controllers\Admin\Project\ProjectController@margin')->name('internals.projects.update.margin');
        Route::post('/vat-rate', 'App\Http\Controllers\Admin\Project\ProjectController@vat_rate')->name('internals.projects.update.vat-rate');
        Route::post('/usd-rate', 'App\Http\Controllers\Admin\Project\ProjectController@usd_rate')->name('internals.projects.update.usd-rate');
        Route::post('/has-usd', 'App\Http\Controllers\Admin\Project\ProjectController@has_usd')->name('internals.projects.update.has-usd');
        Route::post('/asf', 'App\Http\Controllers\Admin\Project\ProjectController@asf')->name('internals.projects.update.asf');
        Route::post('/vat', 'App\Http\Controllers\Admin\Project\ProjectController@vat')->name('internals.projects.update.vat');
        Route::post('/terms', 'App\Http\Controllers\Admin\Project\ProjectController@terms')->name('internals.projects.update.terms');
        Route::post('/duration-date', 'App\Http\Controllers\Admin\Project\ProjectController@duration_date')->name('internals.projects.update.duration-date');
        Route::post('/signed-ce', 'App\Http\Controllers\Admin\Project\ProjectController@signed_ce')->name('internals.projects.update.signed-ce');
        Route::post('/start-date', 'App\Http\Controllers\Admin\Project\ProjectController@start_date')->name('internals.projects.update.start-date');
        Route::post('/end-date', 'App\Http\Controllers\Admin\Project\ProjectController@end_date')->name('internals.projects.update.end-date');

        // manage
        Route::group(['prefix' => 'manage/{project_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\Project\ProjectController@manage')->name('internals.projects.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\SupplyController@masterlist')->name('internals.projects.items.masterlist');

            // tasks
            Route::group(['prefix' => 'tasks/'], function () {
                Route::get('/', 'App\Http\Controllers\Admin\Project\TaskController@show')->name('internals.projects.tasks');
                Route::post('/create', 'App\Http\Controllers\Admin\Project\TaskController@create')->name('internals.projects.tasks.create');
                Route::get('/view/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@view')->name('internals.projects.tasks.view');
                Route::get('/edit/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@edit')->name('internals.projects.tasks.edit');
                Route::post('/edit', 'App\Http\Controllers\Admin\Project\TaskController@update')->name('internals.projects.tasks.update');
                Route::get('/done/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@done')->name('internals.projects.tasks.done');
                Route::get('/not-done/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@not_done')->name('internals.projects.tasks.not-done');
                Route::get('/delete/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@delete')->name('internals.projects.tasks.delete');
                Route::get('/recover/{task_id}', 'App\Http\Controllers\Admin\Project\TaskController@recover')->name('internals.projects.tasks.recover');
            });
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@print_ce')->name('internals.exports.projects.print.ce');
            Route::get('print/internal-ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@print_internal_ce')->name('internals.exports.projects.print.internal-ce');
            Route::get('excel/{project_id}', 'App\Http\Controllers\Export\ProjectController@excel')->name('internals.exports.projects.excel');

            Route::get('pdf/ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@pdf_ce')->name('internals.exports.projects.pdf.ce');
            Route::get('pdf/internal-ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@pdf_internal_ce')->name('internals.exports.projects.pdf.internal-ce');

            Route::get('sql', 'App\Http\Controllers\Admin\Report\ProjectController@sql')->name('internals.exports.projects.sql');
        });

        // for searching projects
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Project\ProjectController@search')->name('internals.projects.search');
            Route::get('/{name}/{client}/{client_cost}/{prepared_by_user_id}/{budget}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\Project\ProjectController@filter')->name('internals.projects.filter');
        });
    });

    // details
    Route::group(['prefix' => 'details'], function () {
        Route::get('/add/{project_id}', 'App\Http\Controllers\Admin\Project\DetailController@add')->name('internals.projects.details.add');
        Route::get('/edit/{project_id}', 'App\Http\Controllers\Admin\Project\DetailController@edit')->name('internals.projects.details.edit');
        Route::post('/create', 'App\Http\Controllers\Admin\Project\DetailController@create')->name('internals.projects.details.create');
        Route::post('/update', 'App\Http\Controllers\Admin\Project\DetailController@update')->name('internals.projects.details.update');
        Route::post('/update/price', 'App\Http\Controllers\Admin\Project\DetailController@price')->name('internals.projects.details.update.price');

        Route::get('/approve/{id}','App\Http\Controllers\Admin\Project\DetailController@approve')->name('internals.projects.details.approve');
        Route::get('/disapprove/{id}','App\Http\Controllers\Admin\Project\DetailController@disapprove')->name('internals.projects.details.disapprove');
        Route::get('/activate/{id}','App\Http\Controllers\Admin\Project\DetailController@activate')->name('internals.projects.details.activate');
        Route::get('/deactivate/{id}','App\Http\Controllers\Admin\Project\DetailController@deactivate')->name('internals.projects.details.deactivate');
        Route::get('/recover/{project_detail_id}', 'App\Http\Controllers\Admin\Project\DetailController@recover')->name('internals.projects.details.recover');
        Route::get('/delete/{project_detail_id}', 'App\Http\Controllers\Admin\Project\DetailController@delete')->name('internals.projects.details.delete');

        // for searching project
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\Project\DetailController@search')->name('internals.projects.details.search');
            Route::get('/{name}', 'App\Http\Controllers\Admin\Project\DetailController@filter')->name('internals.projects.details.filter');
        });
    });

    // cv
    Route::group(['prefix' => 'cv/'], function () {
        Route::get('/','App\Http\Controllers\Admin\CV\CVController@show')->name('internals.cv');
        Route::get('/view/{reference_number}','App\Http\Controllers\Admin\CV\CVController@view')->name('internals.cv.view');
        Route::post('/create','App\Http\Controllers\Admin\CV\CVController@create')->name('internals.cv.create');
        Route::get('/done/{cv_id}','App\Http\Controllers\Admin\CV\CVController@done')->name('internals.cv.done');
        Route::get('/open-for-editing/{cv_id}','App\Http\Controllers\Admin\CV\CVController@open_for_editing')->name('internals.cv.open-for-editing');

        Route::get('/custom/add','App\Http\Controllers\Admin\CV\CVController@add_custom')->name('internals.cv.custom.add');
        Route::post('/custom/create','App\Http\Controllers\Admin\CV\CVController@create_custom')->name('internals.cv.custom.create');
        Route::get('/custom/edit/{cv_id}','App\Http\Controllers\Admin\CV\CVController@edit_custom')->name('internals.cv.custom.edit');
        Route::post('/custom/update','App\Http\Controllers\Admin\CVController@update_custom')->name('internals.cv.custom.update');
        Route::get('/custom/manage/{cv_id}','App\Http\Controllers\Admin\CV\CVController@manage')->name('internals.cv.custom.manage');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{reference_number}', 'App\Http\Controllers\Export\CVController@print')->name('internals.exports.cv.print');
            Route::get('print/custom/{reference_number}', 'App\Http\Controllers\Export\CVController@print_custom')->name('internals.exports.cv.print.custom');
            Route::get('excel/{cv_id}', 'App\Http\Controllers\Export\CVController@excel')->name('internals.exports.cv.excel');
            Route::get('pdf/{cv_id}', 'App\Http\Controllers\Export\CVController@pdf')->name('internals.exports.cv.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\CVController@sql')->name('internals.exports.cv.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CV\CVController@search')->name('internals.cv.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CV\CVController@filter')->name('internals.cv.filter');
        });
    });

    // cv details
    Route::group(['prefix' => 'cv/details'], function () {
        Route::post('/create','App\Http\Controllers\Admin\CV\DetailController@create')->name('internals.cv.details.create');
        Route::post('/update','App\Http\Controllers\Admin\CV\DetailController@update')->name('internals.cv.details.update');
        Route::get('/approve/{cv_detail_id}','App\Http\Controllers\Admin\CV\DetailController@approve')->name('internals.cv.details.approve');
        Route::get('/disapprove/{cv_detail_id}','App\Http\Controllers\Admin\CV\DetailController@disapprove')->name('internals.cv.details.disapprove');
        Route::get('/activate/{cv_detail_id}','App\Http\Controllers\Admin\CV\DetailController@activate')->name('internals.cv.details.activate');
        Route::get('/deactivate/{cv_detail_id}','App\Http\Controllers\Admin\CV\DetailController@deactivate')->name('internals.cv.details.deactivate');
        Route::get('/delete/{cv_detail_id}', 'App\Http\Controllers\Admin\CV\DetailController@delete')->name('internals.cv.details.delete');
    });

    // brf
    Route::group(['prefix' => 'brf/'], function () {
        Route::get('/','App\Http\Controllers\Admin\BRF\BRFController@show')->name('internals.brf');

        Route::get('/view/{id}','App\Http\Controllers\Admin\BRF\BRFController@view')->name('internals.brf.view');
        Route::get('/add/users','App\Http\Controllers\Admin\BRF\BRFController@add_user')->name('internals.brf.users.add');
        Route::get('/edit/users/{id}','App\Http\Controllers\Admin\BRF\BRFController@edit_user')->name('internals.brf.users.edit');
        Route::get('/add/suppliers','App\Http\Controllers\Admin\BRF\BRFController@add_supplier')->name('internals.brf.suppliers.add');
        Route::get('/edit/suppliers/{id}','App\Http\Controllers\Admin\BRF\BRFController@edit_supplier')->name('internals.brf.suppliers.edit');
        Route::post('/create/users','App\Http\Controllers\Admin\BRF\BRFController@create_user')->name('internals.brf.users.create');
        Route::post('/update/users','App\Http\Controllers\Admin\BRF\BRFController@update_user')->name('internals.brf.users.update');
        Route::post('/create/suppliers','App\Http\Controllers\Admin\BRF\BRFController@create_supplier')->name('internals.brf.suppliers.create');
        Route::post('/update/suppliers','App\Http\Controllers\Admin\BRF\BRFController@update_supplier')->name('internals.brf.suppliers.update');
        Route::post('/update','App\Http\Controllers\Admin\BRF\BRFController@update')->name('internals.brf.update');
        Route::get('/approve/{id}','App\Http\Controllers\Admin\BRF\BRFController@approve')->name('internals.brf.approve');
        Route::post('/disapprove','App\Http\Controllers\Admin\BRF\BRFController@disapprove')->name('internals.brf.disapprove');
        Route::get('/activate/{id}','App\Http\Controllers\Admin\BRF\BRFController@activate')->name('internals.brf.activate');
        Route::get('/deactivate/{id}','App\Http\Controllers\Admin\BRF\BRFController@deactivate')->name('internals.brf.deactivate');
        Route::get('/delete/{brf_id}', 'App\Http\Controllers\Admin\BRF\BRFController@delete')->name('internals.brf.delete');

        Route::get('/for-approval/{brf_id}', 'App\Http\Controllers\Admin\BRF\BRFController@for_approval')->name('internals.brf.for-approval');
        Route::get('/for-final-approval/{brf_id}', 'App\Http\Controllers\Admin\BRF\BRFController@for_final_approval')->name('internals.brf.for-final-approval');
        Route::get('/send-to-finance/{reference_number}', 'App\Http\Controllers\Admin\BRF\BRFController@send_to_finance')->name('internals.brf.send-to-finance');
        Route::get('/for-release/{brf_id}', 'App\Http\Controllers\Admin\BRF\BRFController@for_release')->name('internals.brf.for-release');
        Route::get('/released/{brf_id}', 'App\Http\Controllers\Admin\BRF\BRFController@released')->name('internals.brf.released');

        Route::post('/mark-as-released', 'App\Http\Controllers\Admin\BRF\BRFController@mark_as_released')->name('internals.brf.mark-as-released');
        Route::post('/release-file/upload', 'App\Http\Controllers\Admin\BRF\BRFController@release_file')->name('internals.brf.file.upload');
        Route::get('/release-file/delete/{brf_file_id}', 'App\Http\Controllers\Admin\BRF\BRFController@delete_release_file')->name('internals.brf.file.delete');

        Route::get('/database/update', 'App\Http\Controllers\Admin\BRF\BRFController@database_update')->name('internals.brf.database.update');

        // manage
        Route::group(['prefix' => 'manage/{brf_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\BRF\BRFController@manage')->name('internals.brf.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\BRF\BRFController@masterlist')->name('internals.brf.items.masterlist');
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{brf_id}', 'App\Http\Controllers\Export\BRFController@print')->name('internals.exports.brf.print');
            Route::get('excel/{brf_id}', 'App\Http\Controllers\Export\BRFController@excel')->name('internals.exports.brf.excel');
            Route::get('pdf/{brf_id}', 'App\Http\Controllers\Export\BRFController@pdf')->name('internals.exports.brf.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\BRFController@sql')->name('internals.exports.brf.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\BRF\BRFController@search')->name('internals.brf.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\BRF\BRFController@filter')->name('internals.brf.filter');
        });
    });

    // brf details
    Route::group(['prefix' => 'brf/details'], function () {
        Route::post('/create','App\Http\Controllers\Admin\BRF\DetailController@create')->name('internals.brf.details.create');
        Route::post('/update','App\Http\Controllers\Admin\BRF\DetailController@update')->name('internals.brf.details.update');
        Route::get('/approve/{brf_detail_id}','App\Http\Controllers\Admin\BRF\DetailController@approve')->name('internals.brf.details.approve');
        Route::get('/disapprove/{brf_detail_id}','App\Http\Controllers\Admin\BRF\DetailController@disapprove')->name('internals.brf.details.disapprove');
        Route::get('/activate/{brf_detail_id}','App\Http\Controllers\Admin\BRF\DetailController@activate')->name('internals.brf.details.activate');
        Route::get('/deactivate/{brf_detail_id}','App\Http\Controllers\Admin\BRF\DetailController@deactivate')->name('internals.brf.details.deactivate');
        Route::get('/delete/{brf_detail_id}', 'App\Http\Controllers\Admin\BRF\DetailController@delete')->name('internals.brf.details.delete');
    });

    // purchase orders
    Route::group(['prefix' => 'purchase-orders/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\PurchaseOrderController@show')->name('internals.purchase-orders');
        Route::get('/view/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@view')->name('internals.purchase-orders.view');
        Route::get('/add', 'App\Http\Controllers\Admin\PurchaseOrderController@add')->name('internals.purchase-orders.add');
        Route::post('/create', 'App\Http\Controllers\Admin\PurchaseOrderController@create')->name('internals.purchase-orders.create');
        Route::get('/edit/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@edit')->name('internals.purchase-orders.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\PurchaseOrderController@update')->name('internals.purchase-orders.update');

        Route::post('/grpo', 'App\Http\Controllers\Admin\PurchaseOrderController@grpo')->name('internals.purchase-orders.grpo');
        Route::post('/finalize', 'App\Http\Controllers\Admin\PurchaseOrderController@finalize')->name('internals.purchase-orders.finalize');

        Route::get('/recover/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@recover')->name('internals.purchase-orders.recover');
        Route::get('/cancel/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@cancel')->name('internals.purchase-orders.cancel');
        Route::get('/approve/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@approve')->name('internals.purchase-orders.approve');
        Route::get('/disapprove/{purchase_order_id}', 'App\Http\Controllers\Admin\PurchaseOrderController@disapprove')->name('internals.purchase-orders.disapprove');

        // manage
        Route::group(['prefix' => 'manage/{purchase_order_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\PurchaseOrderController@manage')->name('internals.purchase-orders.manage');
            Route::get('/orders/masterlist', 'App\Http\Controllers\Admin\OrderController@masterlist')->name('internals.purchase-orders.orders.masterlist');

            // orders
            Route::group(['prefix' => 'orders'], function () {
                Route::post('/create', 'App\Http\Controllers\Admin\OrderController@create')->name('internals.orders.create');
                Route::post('/update/price', 'App\Http\Controllers\Admin\OrderController@price')->name('internals.orders.update.price');
                Route::post('/update/discount', 'App\Http\Controllers\Admin\OrderController@discount')->name('internals.orders.update.discount');
                Route::post('/update/qty', 'App\Http\Controllers\Admin\OrderController@qty')->name('internals.orders.update.qty');
                Route::post('/update/free-qty', 'App\Http\Controllers\Admin\OrderController@freeQty')->name('internals.orders.update.free-qty');
                Route::get('/recover/{order_id}', 'App\Http\Controllers\Admin\OrderController@recover')->name('internals.orders.recover');
                Route::get('/delete/{order_id}', 'App\Http\Controllers\Admin\OrderController@delete')->name('internals.orders.delete');

                // for searching orders
                Route::group(['prefix' => 'search/'], function () {
                    Route::post('/', 'App\Http\Controllers\Admin\OrderController@search')->name('internals.orders.search');
                    Route::get('/{name}', 'App\Http\Controllers\Admin\OrderController@filter')->name('internals.orders.filter');
                });
            });
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{purchase_order_id}', 'App\Http\Controllers\Export\PurchaseOrderController@print')->name('internals.exports.purchase-orders.print');
            Route::get('excel/{purchase_order_id}', 'App\Http\Controllers\Export\PurchaseOrderController@excel')->name('internals.exports.purchase-orders.excel');
            Route::get('pdf/{purchase_order_id}', 'App\Http\Controllers\Export\PurchaseOrderController@pdf')->name('internals.exports.purchase-orders.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\PurchaseOrderController@sql')->name('internals.exports.purchase-orders.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\PurchaseOrderController@search')->name('internals.purchase-orders.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\PurchaseOrderController@filter')->name('internals.purchase-orders.filter');
        });
    });

    // goods receipts
    Route::group(['prefix' => 'goods-receipts/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\GoodsReceiptController@show')->name('internals.goods-receipts');
        Route::get('/view/{goods_receipt_id}', 'App\Http\Controllers\Admin\GoodsReceiptController@view')->name('internals.goods-receipts.view');
        Route::get('/add', 'App\Http\Controllers\Admin\GoodsReceiptController@add')->name('internals.goods-receipts.add');
        Route::post('/create', 'App\Http\Controllers\Admin\GoodsReceiptController@create')->name('internals.goods-receipts.create');
        Route::get('/edit/{goods_receipt_id}', 'App\Http\Controllers\Admin\GoodsReceiptController@edit')->name('internals.goods-receipts.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\GoodsReceiptController@update')->name('internals.goods-receipts.update');

        Route::post('/grpo', 'App\Http\Controllers\Admin\GoodsReceiptController@grpo')->name('internals.goods-receipts.grpo');
        Route::post('/finalize', 'App\Http\Controllers\Admin\GoodsReceiptController@finalize')->name('internals.goods-receipts.finalize');

        Route::get('/recover/{goods_receipt_id}', 'App\Http\Controllers\Admin\GoodsReceiptController@recover')->name('internals.goods-receipts.recover');
        Route::get('/cancel/{goods_receipt_id}', 'App\Http\Controllers\Admin\GoodsReceiptController@cancel')->name('internals.goods-receipts.cancel');
        Route::get('/clear/{goods_receipt_id}', 'App\Http\Controllers\Admin\GoodsReceiptController@clear')->name('internals.goods-receipts.clear');

        // manage
        Route::group(['prefix' => 'manage/{goods_receipt_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\GoodsReceiptController@manage')->name('internals.goods-receipts.manage');

            // delivery receipts
            Route::group(['prefix' => 'delivery-receipts'], function () {
                Route::post('/create', 'App\Http\Controllers\Admin\DeliveryReceiptController@create')->name('internals.goods-receipts.delivery-receipts.create');
                Route::get('/delete/{delivery_receipt_id}', 'App\Http\Controllers\Admin\DeliveryReceiptController@delete')->name('internals.goods-receipts.delivery-receipts.delete');
            });

            // orders
            Route::group(['prefix' => 'orders'], function () {
                Route::post('/receive', 'App\Http\Controllers\Admin\OrderController@receive')->name('internals.goods-receipts.orders.receive');
                Route::post('/return', 'App\Http\Controllers\Admin\OrderController@return')->name('internals.goods-receipts.orders.return');

                // for searching orders
                Route::group(['prefix' => 'search/'], function () {
                    Route::post('/', 'App\Http\Controllers\Admin\OrderController@search')->name('internals.orders.goods-receipts.search');
                    Route::get('/{name}', 'App\Http\Controllers\Admin\OrderController@filter')->name('internals.orders.goods-receipts.filter');
                });
            });
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{goods_receipt_id}', 'App\Http\Controllers\Export\GoodsReceiptController@print')->name('internals.exports.goods-receipts.print');
            Route::get('excel/{goods_receipt_id}', 'App\Http\Controllers\Export\GoodsReceiptController@excel')->name('internals.exports.goods-receipts.excel');
            Route::get('pdf/{goods_receipt_id}', 'App\Http\Controllers\Export\GoodsReceiptController@pdf')->name('internals.exports.goods-receipts.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\GoodsReceiptController@sql')->name('internals.exports.goods-receipts.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\GoodsReceiptController@search')->name('internals.goods-receipts.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\GoodsReceiptController@filter')->name('internals.goods-receipts.filter');
        });
    });

    // return inventories
    Route::group(['prefix' => 'return-inventories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@show')->name('internals.return-inventories');
        Route::get('/view/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@view')->name('internals.return-inventories.view');
        Route::get('/add', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@add')->name('internals.return-inventories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@create')->name('internals.return-inventories.create');
        Route::get('/edit/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@edit')->name('internals.return-inventories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@update')->name('internals.return-inventories.update');
        
        Route::get('/recover/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@recover')->name('internals.return-inventories.recover');
        Route::get('/cancel/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@cancel')->name('internals.return-inventories.cancel');
        Route::get('/approve/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@approve')->name('internals.return-inventories.approve');
        Route::get('/disapprove/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@disapprove')->name('internals.return-inventories.disapprove');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@print')->name('internals.exports.return-inventories.print');
            Route::get('excel/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@excel')->name('internals.exports.return-inventories.excel');
            Route::get('pdf/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@pdf')->name('internals.exports.return-inventories.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\ReturnInventoryController@sql')->name('internals.exports.return-inventories.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@search')->name('internals.return-inventories.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ReturnInventory\ReturnInventoryController@filter')->name('internals.return-inventories.filter');
        });
    });

    // reports group
    Route::group(['prefix' => 'reports/', 'middleware' => ['auth', 'internal']], function () {
        // sales
        Route::group(['prefix' => 'sales/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\PaymentController@show')->name('admin.reports.sales');

            // month
            Route::group(['prefix' => 'month/{month}'], function () {
                Route::get('/', 'App\Http\Controllers\Report\PaymentController@month')->name('admin.reports.sales.month');
            });

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\PaymentController@search')->name('admin.reports.sales.search');
                Route::get('/{from_date}/{to_date}', 'App\Http\Controllers\Report\PaymentController@filter')->name('admin.reports.sales.filter');
            });
        });

        // projects
        Route::group(['prefix' => 'projects/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\ProjectController@show')->name('admin.reports.projects');

            // month
            Route::group(['prefix' => 'month/{month}'], function () {
                Route::get('/', 'App\Http\Controllers\Report\ProjectController@month')->name('admin.reports.projects.month');
            });

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\ProjectController@search')->name('admin.reports.projects.search');
                Route::get('/{from_date}/{to_date}', 'App\Http\Controllers\Report\ProjectController@filter')->name('admin.reports.projects.filter');
            });
        });

        // payment credits
        Route::group(['prefix' => 'payment-credits/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\PaymentCreditController@show')->name('admin.reports.payment-credits');
            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\PaymentCreditController@search')->name('admin.reports.payment-credits.search');
                Route::get('{salesperson_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Report\PaymentCreditController@filter')->name('admin.reports.payment-credits.filter');
                Route::get('not-in/{salesperson_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Report\PaymentCreditController@not_in_filter')->name('admin.reports.payment-credits.not-in.filter');
            });
        });

        // payables
        Route::group(['prefix' => 'payables/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\PayableController@show')->name('admin.reports.payables');
            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\PayableController@search')->name('admin.reports.payables.search');
                Route::get('{status}/{from_date}/{to_date}', 'App\Http\Controllers\Report\PayableController@filter')->name('admin.reports.payables.filter');
            });
        });

        // expenses
        Route::group(['prefix' => 'expenses/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\ExpenseController@show')->name('admin.reports.expenses');
            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\ExpenseController@search')->name('admin.reports.expenses.search');
                Route::get('{from_date}/{to_date}', 'App\Http\Controllers\Report\ExpenseController@filter')->name('admin.reports.expenses.filter');
            });
        });

        // items
        Route::group(['prefix' => 'items/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\ItemController@show')->name('admin.reports.items');
            Route::post('/view', 'App\Http\Controllers\Report\ItemController@view_filter')->name('admin.reports.items.view.filter');
            Route::get('/view/{customer_id}/{from_date}/{to_date}', 'App\Http\Controllers\Report\ItemController@view')->name('admin.reports.items.view');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\ItemController@search')->name('admin.reports.items.search');
                Route::get('/{name}/{from_date}/{to_date}', 'App\Http\Controllers\Report\ItemController@filter')->name('admin.reports.items.filter');
            });
        });

        // customers
        Route::group(['prefix' => 'customers/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\CustomerController@show')->name('admin.reports.customers');
            Route::post('/view', 'App\Http\Controllers\Report\CustomerController@view_filter')->name('admin.reports.customers.view.filter');
            Route::get('/view/{customer_id}/{mop}/{from_date}/{to_date}', 'App\Http\Controllers\Report\CustomerController@view')->name('admin.reports.customers.view');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\CustomerController@search')->name('admin.reports.customers.search');
                Route::get('/{name}/{mop}/{from_date}/{to_date}', 'App\Http\Controllers\Report\CustomerController@filter')->name('admin.reports.customers.filter');
            });
        });

        // cash-advances
        Route::group(['prefix' => 'cash-advances/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\CashAdvanceController@show')->name('admin.reports.cash-advances');
            Route::post('/view', 'App\Http\Controllers\Report\CashAdvanceController@view_filter')->name('admin.reports.cash-advances.view.filter');
            Route::get('/view/{user_id}/{from_date}/{to_date}', 'App\Http\Controllers\Report\CashAdvanceController@view')->name('admin.reports.cash-advances.view');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\CashAdvanceController@search')->name('admin.reports.cash-advances.search');
                Route::get('/{name}/{from_date}/{to_date}', 'App\Http\Controllers\Report\CashAdvanceController@filter')->name('admin.reports.cash-advances.filter');
            });
        });

        // logs
        Route::group(['prefix' => 'activity-logs/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\ActivityLogController@show')->name('admin.reports.activity-logs');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\ActivityLogController@search')->name('admin.reports.activity-logs.search');
                Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Report\ActivityLogController@filter')->name('admin.reports.activity-logs.filter');
            });
        });
    });
});

// auth profile group
Route::group(['prefix' => 'profile/', 'middleware' => ['auth', 'internal']], function () {
    Route::get('/', 'App\Http\Controllers\Auth\ProfileController@show')->name('auth.profile'); 
    Route::get('/view/{user_id}', 'App\Http\Controllers\Auth\ProfileController@view')->name('auth.profile.view');
    Route::get('/edit', 'App\Http\Controllers\Auth\ProfileController@edit')->name('auth.profile.edit');
    Route::post('/edit', 'App\Http\Controllers\Auth\ProfileController@update')->name('auth.profile.update');
});

// sql routes
Route::group(['prefix' => 'sql/'], function () {
    Route::post('/confirm', 'App\Http\Controllers\Admin\DatabaseController@confirm')->name('admin.sql.confirm');
    Route::get('/export', 'App\Http\Controllers\Admin\DatabaseController@export')->name('admin.sql.export');
});

// authentication group
Route::group(['prefix' => 'auth/'], function () {
    Route::get('login', 'App\Http\Controllers\Auth\AuthController@login')->name('login');
    Route::post('login', 'App\Http\Controllers\Auth\AuthController@attempt')->name('auth.attempt');

    // Route::get('register', 'App\Http\Controllers\Auth\AuthController@register')->name('register');
    // Route::post('register', 'App\Http\Controllers\Auth\AuthController@create')->name('auth.create');

    // Route::get('forgot', 'App\Http\Controllers\Auth\AuthController@forgot')->name('auth.forgot');
    // Route::post('reset', 'App\Http\Controllers\Auth\AuthController@reset')->name('auth.reset');
    // Route::post('change/password', 'App\Http\Controllers\Auth\AuthController@change')->name('auth.change.password');

    Route::post('change/password', 'App\Http\Controllers\Auth\ProfileController@change_password')->name('auth.profile.change-password');

    Route::group(['prefix' => 'email/'], function () {
        Route::get('verify/{email}/{user_id}', 'App\Http\Controllers\Auth\MailController@verify')->name('auth.email.verify');
        Route::get('reset/{email}/{user_id}', 'App\Http\Controllers\Auth\MailController@reset')->name('auth.email.reset');
    });

    // socialite group
    Route::group(['prefix' => 'socialite/'], function () {
        Route::get('login/{provider}/callback', 'App\Http\Controllers\Auth\SocialiteController@callback')->name('socialite.login');
        Route::get('/redirect/{service}', 'App\Http\Controllers\Auth\SocialiteController@redirect')->name('socialite.redirect');
        Route::get('/callback/{service}', 'App\Http\Controllers\Auth\SocialiteController@callback');
    });

    Route::get('logout', 'App\Http\Controllers\Auth\AuthController@logout')->name('logout');
});

// qr code group
Route::group(['prefix' => 'qr/'], function () {
    // items
    Route::group(['prefix' => 'items/'], function () {
        Route::get('/view/{item_id}', 'App\Http\Controllers\QR\ItemController@view')->name('qr.items.view');
    });

    // items
    Route::group(['prefix' => 'site/'], function () {
        Route::get('/home', 'App\Http\Controllers\QR\SiteController@home')->name('qr.items.home');
    });
});

// share group
Route::group(['prefix' => 'share/'], function () {
    // projects
    Route::group(['prefix' => 'projects/'], function () {
        Route::get('/view/{slug}/{reference_number}', 'App\Http\Controllers\Share\ProjectController@view')->name('share.projects.view');
        Route::post('/update/conforme-signature', 'App\Http\Controllers\Share\ProjectController@conforme_signature')->name('internals.projects.update.conforme-signature');
    });
});

// imports
Route::group(['prefix' => 'imports/', 'middleware' => ['auth', 'internal']], function () {
    Route::post('/', 'App\Http\Controllers\Import\DatabaseController@check')->name('admin.database.import.check');
    Route::post('users', 'App\Http\Controllers\Import\DatabaseController@users')->name('admin.database.import.users');
    Route::post('categories', 'App\Http\Controllers\Import\DatabaseController@categories')->name('admin.database.import.categories');
    Route::post('sub-categories', 'App\Http\Controllers\Import\DatabaseController@sub_categories')->name('admin.database.import.sub-categories');
    Route::post('brands', 'App\Http\Controllers\Import\DatabaseController@brands')->name('admin.database.import.brands');
    Route::post('items', 'App\Http\Controllers\Import\DatabaseController@items')->name('admin.database.import.items');
    Route::post('item-photos', 'App\Http\Controllers\Import\DatabaseController@item_photos')->name('admin.database.import.item-photos');
    Route::post('item-serial-numbers', 'App\Http\Controllers\Import\DatabaseController@item_serial_numbers')->name('admin.database.import.item-serial-numbers');
    Route::post('suppliers', 'App\Http\Controllers\Import\DatabaseController@suppliers')->name('admin.database.import.suppliers');
    Route::post('supplies', 'App\Http\Controllers\Import\DatabaseController@supplies')->name('admin.database.import.supplies');
    Route::post('purchase-orders', 'App\Http\Controllers\Import\DatabaseController@purchase_orders')->name('admin.database.import.purchase-orders');
    Route::post('orders', 'App\Http\Controllers\Import\DatabaseController@orders')->name('admin.database.import.orders');
    Route::post('goods-receipts', 'App\Http\Controllers\Import\DatabaseController@goods_receipts')->name('admin.database.import.goods-receipts');
    Route::post('delivery-receipts', 'App\Http\Controllers\Import\DatabaseController@delivery_receipts')->name('admin.database.import.delivery-receipts');
    Route::post('inventories', 'App\Http\Controllers\Import\DatabaseController@inventories')->name('admin.database.import.inventories');
    Route::post('payments', 'App\Http\Controllers\Import\DatabaseController@payments')->name('admin.database.import.payments');
    Route::post('pos-discounts', 'App\Http\Controllers\Import\DatabaseController@pos_discounts')->name('admin.database.import.pos-discounts');
    Route::get('pos-vat', 'App\Http\Controllers\Import\DatabaseController@pos_vat')->name('admin.database.import.pos-vat');
});

// test routes
Route::group(['prefix' => 'test/'], function () {
    Route::get('/version', 'App\Http\Controllers\Test\TestController@version');
    Route::get('/email', 'App\Http\Controllers\Test\TestController@email');
    Route::get('/qr', 'App\Http\Controllers\Test\TestController@qr');
});

// ajax routes
Route::group(['prefix' => 'ajax/'], function () {
    Route::get('select/sub-categories/{category_id}', 'App\Http\Controllers\Admin\AjaxController@sub_categories')->name('ajax.sub-categories');
    Route::get('select/projects/sub-categories/{category_id}', 'App\Http\Controllers\Admin\AjaxController@project_sub_categories')->name('ajax.projects.sub-categories');
    Route::get('select/companies/{category_id}', 'App\Http\Controllers\Admin\AjaxController@companies')->name('ajax.companies');
});

// query routes
Route::group(['prefix' => 'q/', 'middleware' => ['auth', 'internal']], function () {
    /* create or update payment receipts and payments into one */
    Route::get('/payment-receipts', 'App\Http\Controllers\Admin\QueryController@payment_receipts')->name('query.payment-receipts');
    Route::get('/pos-discount', 'App\Http\Controllers\Admin\QueryController@pos_discount')->name('query.pos-discount');
    Route::get('/pos-vat', 'App\Http\Controllers\Admin\QueryController@pos_vat')->name('query.pos-vat');
    Route::get('/payments', 'App\Http\Controllers\Admin\QueryController@payments')->name('query.payments');
    Route::get('/salespersons', 'App\Http\Controllers\Admin\QueryController@salespersons')->name('query.salespersons');
    Route::get('/costing', 'App\Http\Controllers\Admin\QueryController@costing')->name('query.costing');

    /* create or update payments that are existing status credit */
    Route::get('/payment-credits', 'App\Http\Controllers\Admin\QueryController@payment_credits')->name('query.payment-credits');

    /* update users that are almost the same and transfer all their records into one */
    Route::get('/users', 'App\Http\Controllers\Admin\QueryController@users')->name('query.users');

    /* check if payment credits are overdue or not */
    Route::get('/overdue', 'App\Http\Controllers\Admin\QueryController@overdue')->name('query.overdue');

    /* update vat and discount */
    Route::get('/discount', 'App\Http\Controllers\Admin\QueryController@discount')->name('query.discount');
    Route::get('/vat', 'App\Http\Controllers\Admin\QueryController@vat')->name('query.vat');

    /* create or update payments that are existing status credit */
    Route::get('/item-serial-numbers', 'App\Http\Controllers\Admin\QueryController@item_serial_numbers')->name('query.item-serial-numbers');

    /* verify internal users */
    Route::get('/verify/internal/users', 'App\Http\Controllers\Admin\QueryController@verify_internal_users')->name('query.verify.internal.users');

    /* verify brf */
    Route::get('/verify/brf', 'App\Http\Controllers\Admin\QueryController@verify_brf')->name('query.verify.brf');

    /* modify brf */
    Route::get('/modify/done/projects', 'App\Http\Controllers\Admin\QueryController@modify_projects')->name('query.modify.projects');
});