<?php

use Illuminate\Support\Facades\Route;

// dashboard group
Route::group(['prefix' => 'dashboard/', 'middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\Auth\DashboardController@show')->name('auth.dashboard');
});

Route::get('/', 'App\Http\Controllers\Front\Site\SiteController@index')->name('site.index');
Route::get('/help', 'App\Http\Controllers\Front\Site\SiteController@help')->name('site.help');
Route::get('/contact', 'App\Http\Controllers\Front\Site\SiteController@contact')->name('site.contact');
Route::get('/about', 'App\Http\Controllers\Front\Site\SiteController@about')->name('site.about');
Route::get('/terms', 'App\Http\Controllers\Front\Site\SiteController@terms')->name('site.terms');
Route::get('/privacy', 'App\Http\Controllers\Front\Site\SiteController@privacy')->name('site.privacy');

// front mail group
Route::group(['prefix' => 'mail/'], function () {
    Route::post('inquire/item', 'App\Http\Controllers\Front\Mail\ItemController@inquire')->name('mail.items.inquire');
    Route::post('inquire/package', 'App\Http\Controllers\Front\Mail\PackageController@inquire')->name('mail.packages.inquire');
    Route::post('inquire/build', 'App\Http\Controllers\Front\Mail\BuildController@inquire')->name('mail.build.inquire');
    Route::post('contact', 'App\Http\Controllers\Front\Mail\FrontController@contact')->name('mail.contact');
    Route::post('newsletter', 'App\Http\Controllers\Front\Mail\FrontController@newsletter')->name('mail.newsletter');
});

// frontend group
Route::group(['prefix' => 'p/', 'middleware' => ['auth']], function () {
    Route::get('/', 'App\Http\Controllers\Front\Auth\ProfileController@show')->name('front.auth.profile');
    Route::get('/edit', 'App\Http\Controllers\Auth\ProfileController@edit')->name('front.auth.profile.edit');
    Route::post('/edit', 'App\Http\Controllers\Auth\ProfileController@update')->name('front.auth.profile.update');
});

// build group
Route::group(['prefix' => 'build/'], function () {
    Route::get('/', 'App\Http\Controllers\Front\Build\BuildController@show')->name('build');
    Route::post('/create', 'App\Http\Controllers\Front\Build\BuildController@create')->name('build.carts.create');
    Route::get('/delete/{build_item_id}', 'App\Http\Controllers\Front\Build\BuildController@delete')->name('build.items.delete');

    Route::get('/inquire', 'App\Http\Controllers\Front\Build\BuildController@inquire')->name('build.inquire');

    /* transition */
    /*
    motherboard > processor > memory > power supply > power supply sleeves > graphics > aio > air cooling > aux fan > monitor > keyboard > mouse > headset > mic > casing > storage
    */

    // motherboard group
    Route::group(['prefix' => 'motherboard/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\MotherboardController@show')->name('build.motherboard');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\MotherboardController@create')->name('build.motherboard.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\MotherboardController@update')->name('build.motherboard.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\MotherboardController@search')->name('build.motherboard.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\MotherboardController@filter')->name('build.motherboard.filter');
        });
    });

    // processor group
    Route::group(['prefix' => 'processor/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\ProcessorController@show')->name('build.processor');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\ProcessorController@create')->name('build.processor.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\ProcessorController@update')->name('build.processor.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\ProcessorController@search')->name('build.processor.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\ProcessorController@filter')->name('build.processor.filter');
        });
    });

    // memory group
    Route::group(['prefix' => 'memory/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\MemoryController@show')->name('build.memory');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\MemoryController@create')->name('build.memory.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\MemoryController@update')->name('build.memory.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\MemoryController@search')->name('build.memory.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\MemoryController@filter')->name('build.memory.filter');
        });
    });

    // graphics group
    Route::group(['prefix' => 'graphics/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\GraphicsController@show')->name('build.graphics');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\GraphicsController@create')->name('build.graphics.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\GraphicsController@update')->name('build.graphics.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\GraphicsController@search')->name('build.graphics.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\GraphicsController@filter')->name('build.graphics.filter');
        });
    });

    // power supply group
    Route::group(['prefix' => 'power-supply/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\PowerSupplyController@show')->name('build.power-supply');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\PowerSupplyController@create')->name('build.power-supply.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\PowerSupplyController@update')->name('build.power-supply.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\PowerSupplyController@search')->name('build.power-supply.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\PowerSupplyController@filter')->name('build.power-supply.filter');
        });
    });

    // casing group
    Route::group(['prefix' => 'casing/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\CasingController@show')->name('build.casing');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\CasingController@create')->name('build.casing.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\CasingController@update')->name('build.casing.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\CasingController@search')->name('build.casing.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\CasingController@filter')->name('build.casing.filter');
        });
    });

    // aio group
    Route::group(['prefix' => 'aio/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\AIOController@show')->name('build.aio');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\AIOController@create')->name('build.aio.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\AIOController@update')->name('build.aio.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\AIOController@search')->name('build.aio.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\AIOController@filter')->name('build.aio.filter');
        });
    });

    // air cooling group
    Route::group(['prefix' => 'air-cooling/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\AirCoolingController@show')->name('build.air-cooling');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\AirCoolingController@create')->name('build.air-cooling.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\AirCoolingController@update')->name('build.air-cooling.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\AirCoolingController@search')->name('build.air-cooling.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\AirCoolingController@filter')->name('build.air-cooling.filter');
        });
    });

    // aux-fan group
    Route::group(['prefix' => 'aux-fan/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\AuxFanController@show')->name('build.aux-fan');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\AuxFanController@create')->name('build.aux-fan.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\AuxFanController@update')->name('build.aux-fan.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\AuxFanController@search')->name('build.aux-fan.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\AuxFanController@filter')->name('build.aux-fan.filter');
        });
    });

    // power-supply-sleeves group
    Route::group(['prefix' => 'power-supply-sleeves/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\PowerSupplySleevesController@show')->name('build.power-supply-sleeves');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\PowerSupplySleevesController@create')->name('build.power-supply-sleeves.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\PowerSupplySleevesController@update')->name('build.power-supply-sleeves.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\PowerSupplySleevesController@search')->name('build.power-supply-sleeves.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\PowerSupplySleevesController@filter')->name('build.power-supply-sleeves.filter');
        });
    });

    // monitor group
    Route::group(['prefix' => 'monitor/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\MonitorController@show')->name('build.monitor');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\MonitorController@create')->name('build.monitor.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\MonitorController@update')->name('build.monitor.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\MonitorController@search')->name('build.monitor.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\MonitorController@filter')->name('build.monitor.filter');
        });
    });

    // keyboard group
    Route::group(['prefix' => 'keyboard/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\KeyboardController@show')->name('build.keyboard');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\KeyboardController@create')->name('build.keyboard.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\KeyboardController@update')->name('build.keyboard.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\KeyboardController@search')->name('build.keyboard.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\KeyboardController@filter')->name('build.keyboard.filter');
        });
    });

    // mouse group
    Route::group(['prefix' => 'mouse/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\MouseController@show')->name('build.mouse');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\MouseController@create')->name('build.mouse.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\MouseController@update')->name('build.mouse.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\MouseController@search')->name('build.mouse.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\MouseController@filter')->name('build.mouse.filter');
        });
    });

    // headset group
    Route::group(['prefix' => 'headset/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\HeadsetController@show')->name('build.headset');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\HeadsetController@create')->name('build.headset.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\HeadsetController@update')->name('build.headset.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\HeadsetController@search')->name('build.headset.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\HeadsetController@filter')->name('build.headset.filter');
        });
    });

    // mic group
    Route::group(['prefix' => 'mic/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\MicController@show')->name('build.mic');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\MicController@create')->name('build.mic.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\MicController@update')->name('build.mic.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\MicController@search')->name('build.mic.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\MicController@filter')->name('build.mic.filter');
        });
    });

    // storage group
    Route::group(['prefix' => 'storage/'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Build\StorageController@show')->name('build.storage');
        Route::get('/create/{inventory_id}', 'App\Http\Controllers\Front\Build\StorageController@create')->name('build.storage.create');
        Route::post('/update', 'App\Http\Controllers\Front\Build\StorageController@update')->name('build.storage.update');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Front\Build\StorageController@search')->name('build.storage.search');
            Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Build\StorageController@filter')->name('build.storage.filter');
        });
    });

    Route::get('/result', 'App\Http\Controllers\Front\Build\BuildController@result')->name('build.result');
});

// ecommerce group
Route::group(['prefix' => 'shop/'], function () {
    Route::get('/', 'App\Http\Controllers\Front\Shop\ShopController@show')->name('shop');

    // for searching
    Route::group(['prefix' => 'search/'], function () {
        Route::post('/', 'App\Http\Controllers\Front\Shop\InventoryController@search')->name('shop.search');
        Route::get('/{name}/{brand_id}/{category_id}/{sub_category_id}', 'App\Http\Controllers\Front\Shop\InventoryController@filter')->name('shop.filter');
    });

    // items
    Route::group(['prefix' => '/items/view/{inventory_id}'], function () {
        Route::get('/', 'App\Http\Controllers\Front\Shop\InventoryController@view')->name('shop.items.view');

        // auth middleware
        Route::group(['middleware' => ['auth']], function () {
        // reviews
        Route::group(['prefix' => 'reviews/', 'middleware' => ['auth']], function () {
            Route::get('/', 'App\Http\Controllers\Front\Shop\ReviewController@show')->name('shop.items.reviews');
            Route::get('/add', 'App\Http\Controllers\Front\Shop\ReviewController@add')->name('shop.items.reviews.add');
            Route::post('/create', 'App\Http\Controllers\Front\Shop\ReviewController@create')->name('shop.items.reviews.create');
            Route::get('/delete/{review_id}', 'App\Http\Controllers\Front\Shop\ReviewController@delete')->name('shop.carts.delete');
            });
        });
    });

    Route::get('/track', 'App\Http\Controllers\Front\Shop\PaymentController@track')->name('shop.payments.track');
    Route::post('/find', 'App\Http\Controllers\Front\Shop\PaymentController@find')->name('shop.payments.find');

    // carts
    Route::group(['prefix' => 'carts/', 'middleware' => ['auth']], function () {
        Route::get('/', 'App\Http\Controllers\Front\Shop\CartController@show')->name('shop.carts');
        Route::post('/create', 'App\Http\Controllers\Front\Shop\CartController@create')->name('shop.carts.create');
        Route::get('/checkout', 'App\Http\Controllers\Front\Shop\CartController@checkout')->name('shop.carts.checkout');
        Route::post('/checkout/create', 'App\Http\Controllers\Front\Shop\PaymentController@create')->name('shop.carts.checkout.create');
        Route::get('/delete/{cart_id}', 'App\Http\Controllers\Front\Shop\CartController@delete')->name('shop.carts.delete');
    });

    // wishlists
    Route::group(['prefix' => 'wishlists/', 'middleware' => ['auth']], function () {
        Route::get('/', 'App\Http\Controllers\Front\Shop\WishlistController@show')->name('shop.wishlists');
        Route::get('/create/item/{wishlist_id}', 'App\Http\Controllers\Front\Shop\WishlistController@item')->name('shop.wishlists.item.create');
        Route::get('/create/package/{wishlist_id}', 'App\Http\Controllers\Front\Shop\WishlistController@package')->name('shop.wishlists.package.create');
        Route::get('/delete/{wishlist_id}', 'App\Http\Controllers\Front\Shop\WishlistController@delete')->name('shop.wishlists.delete');
    });

    // orders
    Route::group(['prefix' => 'orders/', 'middleware' => ['auth']], function () {
        Route::get('/', 'App\Http\Controllers\Front\Shop\PaymentController@show')->name('shop.orders');
    });
});

// pos group
Route::group(['prefix' => 'pos/', 'middleware' => ['auth', 'accounting']], function () {
    Route::get('/', 'App\Http\Controllers\POS\DashboardController@show')->name('pos');
    Route::get('/new', 'App\Http\Controllers\POS\DashboardController@new')->name('pos.customer.new');
    Route::post('/new/create', 'App\Http\Controllers\POS\DashboardController@create')->name('pos.customer.new.create');
    Route::get('/existing', 'App\Http\Controllers\POS\DashboardController@existing')->name('pos.customer.existing');

    Route::post('/set-qty', 'App\Http\Controllers\POS\CartController@qty')->name('pos.standard.set-qty');
    Route::post('/checkout', 'App\Http\Controllers\POS\CartController@checkout')->name('pos.checkout');
    Route::post('/credit', 'App\Http\Controllers\POS\CartController@credit')->name('pos.credit');
    // customer group
    Route::group(['prefix' => 'customer/{user_id}', 'middleware' => ['auth', 'accounting']], function () {
        // standard
        Route::group(['prefix' => 'standard/'], function () {
            Route::get('/', 'App\Http\Controllers\POS\Standard\CartController@show')->name('pos.standard');
            Route::post('/scan', 'App\Http\Controllers\POS\Standard\CartController@scan')->name('pos.standard.scan');
            Route::get('/delete/{cart_id}', 'App\Http\Controllers\POS\Standard\CartController@delete')->name('pos.standard.delete');
        });

        // no serial
        Route::group(['prefix' => 'no-serial/'], function () {
            Route::get('/', 'App\Http\Controllers\POS\NoSerial\CartController@show')->name('pos.no-serial');
            Route::post('/scan', 'App\Http\Controllers\POS\NoSerial\CartController@scan')->name('pos.no-serial.scan');
            Route::get('/delete/{cart_id}', 'App\Http\Controllers\POS\NoSerial\CartController@delete')->name('pos.no-serial.delete');
        });

        // bulk
        Route::group(['prefix' => 'bulk/'], function () {
            Route::get('/', 'App\Http\Controllers\POS\Bulk\CartController@show')->name('pos.bulk');
            Route::post('/scan', 'App\Http\Controllers\POS\Bulk\CartController@scan')->name('pos.bulk.scan');
            Route::get('/delete/{cart_id}', 'App\Http\Controllers\POS\Bulk\CartController@delete')->name('pos.bulk.delete');
        });

        // service-order
        Route::group(['prefix' => 'service-order/'], function () {
            Route::get('/', 'App\Http\Controllers\POS\ServiceOrder\CartController@show')->name('pos.service-order');
            Route::post('/create', 'App\Http\Controllers\POS\ServiceOrder\CartController@create')->name('pos.service-order.create');
            Route::get('/delete/{service_order_id}', 'App\Http\Controllers\POS\ServiceOrder\CartController@delete')->name('pos.service-order.delete');

            Route::post('/checkout', 'App\Http\Controllers\POS\ServiceOrder\CartController@checkout')->name('pos.service-order.checkout');
        });
    });

    // for searching
    Route::group(['prefix' => 'search/'], function () {
        Route::post('/', 'App\Http\Controllers\POS\DashboardController@search')->name('pos.customer.existing.search');
        Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\POS\DashboardController@filter')->name('pos.customer.existing.filter');
    });
});

// operations group
Route::group(['prefix' => 'operations/', 'middleware' => ['auth', 'accounting']], function () {
    // service-orders
    Route::group(['prefix' => 'service-orders/'], function () {

        // jo group
        Route::group(['prefix' => 'jo/', 'middleware' => ['auth', 'accounting']], function () {
            Route::get('/', 'App\Http\Controllers\JO\DashboardController@show')->name('jo');
            Route::get('/new', 'App\Http\Controllers\JO\DashboardController@new')->name('jo.customer.new');
            Route::post('/new/create', 'App\Http\Controllers\JO\DashboardController@create')->name('jo.customer.new.create');
            Route::get('/existing', 'App\Http\Controllers\JO\DashboardController@existing')->name('jo.customer.existing');

            Route::post('/set-qty', 'App\Http\Controllers\JO\CartController@qty')->name('jo.set-qty');
            Route::post('/checkout', 'App\Http\Controllers\JO\CartController@checkout')->name('jo.checkout');

            // customer group
            Route::group(['prefix' => 'customer/{user_id}', 'middleware' => ['auth', 'accounting']], function () {
                // service-order
                Route::group(['prefix' => 'service-order/'], function () {
                    Route::get('/', 'App\Http\Controllers\JO\ServiceOrder\CartController@show')->name('jo.service-order');
                    Route::post('/create', 'App\Http\Controllers\JO\ServiceOrder\CartController@create')->name('jo.service-order.details.create');
                    Route::get('/delete/{service_order_id}', 'App\Http\Controllers\JO\ServiceOrder\CartController@delete')->name('jo.service-order.details.delete');

                    Route::post('/checkout', 'App\Http\Controllers\JO\ServiceOrder\CartController@checkout')->name('jo.service-order.checkout');
                });
            });

            // technical
            Route::group(['prefix' => 'assign/technical/{so_number}'], function () {
                Route::get('/', 'App\Http\Controllers\Admin\TechnicalController@show')->name('operations.service-orders.technical');
                Route::get('/assign/{user_id}', 'App\Http\Controllers\Admin\TechnicalController@assign')->name('operations.service-orders.technical.assign');

                // for searching
                Route::group(['prefix' => 'search/'], function () {
                    Route::post('/', 'App\Http\Controllers\Admin\TechnicalController@search')->name('operations.service-orders.technical.search');
                    Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\TechnicalController@filter')->name('operations.service-orders.technical.filter');
                });
            });

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\JO\DashboardController@search')->name('jo.customer.existing.search');
                Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\JO\DashboardController@filter')->name('jo.customer.existing.filter');
            });
        });

        Route::get('/', 'App\Http\Controllers\Admin\ServiceOrderController@show')->name('operations.service-orders');
        Route::get('/view/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@view')->name('operations.service-orders.view');
        Route::post('/create/serial-number', 'App\Http\Controllers\Admin\ServiceOrderController@serial_number')->name('operations.service-orders.create.serial-number');
        Route::post('/update/price', 'App\Http\Controllers\Admin\ServiceOrderController@price')->name('operations.service-orders.update.price');
        Route::post('/assign/technical', 'App\Http\Controllers\Admin\ServiceOrderController@technical')->name('operations.service-orders.assign.technical');
        Route::post('/assign/mop', 'App\Http\Controllers\Admin\ServiceOrderController@mop')->name('operations.service-orders.assign.mop');
        Route::post('/assign/date-out', 'App\Http\Controllers\Admin\ServiceOrderController@date_out')->name('operations.service-orders.assign.date-out');
        Route::post('/assign/action-taken', 'App\Http\Controllers\Admin\ServiceOrderController@action_taken')->name('operations.service-orders.assign.action-taken');
        Route::post('/assign/back-job', 'App\Http\Controllers\Admin\ServiceOrderController@back_job')->name('operations.service-orders.assign.back-job');

        Route::get('/confirm/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@confirm')->name('operations.service-orders.confirm');
        Route::get('/for-repair/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@for_repair')->name('operations.service-orders.for-repair');
        Route::get('/for-release/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@for_release')->name('operations.service-orders.for-release');
        Route::get('/completed/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@completed')->name('operations.service-orders.completed');
        Route::get('/cancel/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@cancel')->name('operations.service-orders.cancel');
        Route::get('/delete/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@delete')->name('operations.service-orders.delete');
        Route::get('/recover/{jo_number}', 'App\Http\Controllers\Admin\ServiceOrderController@recover')->name('operations.service-orders.recover');

        Route::get('/delete/single/{service_order_id}', 'App\Http\Controllers\Admin\ServiceOrderController@delete')->name('operations.service-orders.single.delete');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{jo_number}', 'App\Http\Controllers\Export\ServiceOrderController@print')->name('operations.service-orders.print');
            Route::get('excel/{jo_number}', 'App\Http\Controllers\Export\ServiceOrderController@excel')->name('operations.service-orders.excel');
            Route::get('pdf/{jo_number}', 'App\Http\Controllers\Export\ServiceOrderController@pdf')->name('operations.service-orders.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\ServiceOrderController@sql')->name('operations.service-orders.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ServiceOrderController@search')->name('operations.service-orders.search');
            Route::get('/{jo_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ServiceOrderController@filter')->name('operations.service-orders.filter');
        });
    });
});

// accounting group
Route::group(['prefix' => 'accounting/', 'middleware' => ['auth', 'accounting']], function () {
    // payments
    Route::group(['prefix' => 'payments/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\PaymentController@show')->name('accounting.payments');
        Route::get('/view/{so_number}', 'App\Http\Controllers\Admin\PaymentController@view')->name('accounting.payments.view');
        Route::post('/create/serial-number', 'App\Http\Controllers\Admin\PaymentController@serial_number')->name('accounting.payments.create.serial-number');
        Route::post('/update/discount', 'App\Http\Controllers\Admin\PaymentController@discount')->name('accounting.payments.update.discount');
        Route::post('/update/vat', 'App\Http\Controllers\Admin\PaymentController@vat')->name('accounting.payments.update.vat');
        Route::post('/assign/mop', 'App\Http\Controllers\Admin\PaymentController@mop')->name('accounting.payments.assign.mop');
        Route::post('/assign/bir-number', 'App\Http\Controllers\Admin\PaymentController@birNumber')->name('accounting.payments.assign.bir-number');
        Route::post('/assign/invoice-number', 'App\Http\Controllers\Admin\PaymentController@invoiceNumber')->name('accounting.payments.assign.invoice-number');
        Route::post('/create/credit', 'App\Http\Controllers\Admin\PaymentController@credit')->name('accounting.payments.credit.create');
        Route::post('/upload/proof', 'App\Http\Controllers\Admin\PaymentController@proof')->name('accounting.payments.upload.proof');
        Route::post('/assign/note', 'App\Http\Controllers\Admin\PaymentController@note')->name('accounting.payments.assign.note');

        // salesperson
        Route::group(['prefix' => 'assign/salesperson/{so_number}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\SalespersonController@show')->name('accounting.payments.salesperson');
            Route::get('/assign/{user_id}', 'App\Http\Controllers\Admin\SalespersonController@assign')->name('accounting.payments.salesperson.assign');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\SalespersonController@search')->name('accounting.payments.salesperson.search');
                Route::get('/{name}/{role}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\SalespersonController@filter')->name('accounting.payments.salesperson.filter');
            });
        });

        Route::get('/confirm/{so_number}', 'App\Http\Controllers\Admin\PaymentController@confirm')->name('accounting.payments.confirm');
        Route::get('/delivery/{so_number}', 'App\Http\Controllers\Admin\PaymentController@delivery')->name('accounting.payments.delivery');
        Route::get('/delivered/{so_number}', 'App\Http\Controllers\Admin\PaymentController@delivered')->name('accounting.payments.delivered');
        Route::get('/cancel/{so_number}', 'App\Http\Controllers\Admin\PaymentController@cancel')->name('accounting.payments.cancel');
        Route::get('/recover/{so_number}', 'App\Http\Controllers\Admin\PaymentController@recover')->name('accounting.payments.recover');
        Route::get('/completion/{so_number}', 'App\Http\Controllers\Admin\PaymentController@completion')->name('accounting.payments.completion');
        Route::get('/standalone/{so_number}', 'App\Http\Controllers\Admin\PaymentController@standalone')->name('accounting.payments.standalone');

        Route::get('/return/single/{payment_id}', 'App\Http\Controllers\Admin\PaymentController@return')->name('accounting.payments.single.return');
        Route::get('/delete/single/{payment_id}', 'App\Http\Controllers\Admin\PaymentController@delete')->name('accounting.payments.single.delete');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{so_number}', 'App\Http\Controllers\Export\PaymentController@print')->name('accounting.payments.print');
            Route::get('excel/{so_number}', 'App\Http\Controllers\Export\PaymentController@excel')->name('accounting.payments.excel');
            Route::get('pdf/{so_number}', 'App\Http\Controllers\Export\PaymentController@pdf')->name('accounting.payments.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\PaymentController@sql')->name('accounting.payments.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\PaymentController@search')->name('accounting.payments.search');
            Route::get('/{so_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\PaymentController@filter')->name('accounting.payments.filter');
        });
    });

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
        Route::get('/', 'App\Http\Controllers\Admin\LiquidationController@show')->name('accounting.liquidations');

        Route::get('/add', 'App\Http\Controllers\Admin\LiquidationController@add')->name('accounting.liquidations.add');
        Route::post('/create', 'App\Http\Controllers\Admin\LiquidationController@create')->name('accounting.liquidations.create');
        Route::get('/view/{expense_id}', 'App\Http\Controllers\Admin\LiquidationController@view')->name('accounting.liquidations.view');
        Route::get('/edit/{expense_id}', 'App\Http\Controllers\Admin\LiquidationController@edit')->name('accounting.liquidations.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\LiquidationController@update')->name('accounting.liquidations.update');
        Route::get('/delete/{expense_id}', 'App\Http\Controllers\Admin\LiquidationController@delete')->name('accounting.liquidations.delete');
        Route::get('/recover/{expense_id}', 'App\Http\Controllers\Admin\LiquidationController@recover')->name('accounting.liquidations.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\LiquidationController@search')->name('accounting.liquidations.search');
            Route::get('{description}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\LiquidationController@filter')->name('accounting.liquidations.filter');
        });
    });

    // expenses
    Route::group(['prefix' => 'expenses/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ExpenseController@show')->name('accounting.expenses');

        Route::get('/add', 'App\Http\Controllers\Admin\ExpenseController@add')->name('accounting.expenses.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ExpenseController@create')->name('accounting.expenses.create');
        Route::get('/view/{expense_id}', 'App\Http\Controllers\Admin\ExpenseController@view')->name('accounting.expenses.view');
        Route::get('/edit/{expense_id}', 'App\Http\Controllers\Admin\ExpenseController@edit')->name('accounting.expenses.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ExpenseController@update')->name('accounting.expenses.update');
        Route::get('/delete/{expense_id}', 'App\Http\Controllers\Admin\ExpenseController@delete')->name('accounting.expenses.delete');
        Route::get('/recover/{expense_id}', 'App\Http\Controllers\Admin\ExpenseController@recover')->name('accounting.expenses.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ExpenseController@search')->name('accounting.expenses.search');
            Route::get('{description}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ExpenseController@filter')->name('accounting.expenses.filter');
        });
    });

    // expense companies
    Route::group(['prefix' => 'expense-companies/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ExpenseCompanyController@show')->name('accounting.expense-companies');

        Route::get('/add', 'App\Http\Controllers\Admin\ExpenseCompanyController@add')->name('accounting.expense-companies.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ExpenseCompanyController@create')->name('accounting.expense-companies.create');
        Route::get('/view/{expense_company_id}', 'App\Http\Controllers\Admin\ExpenseCompanyController@view')->name('accounting.expense-companies.view');
        Route::get('/edit/{expense_company_id}', 'App\Http\Controllers\Admin\ExpenseCompanyController@edit')->name('accounting.expense-companies.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ExpenseCompanyController@update')->name('accounting.expense-companies.update');
        Route::get('/delete/{expense_company_id}', 'App\Http\Controllers\Admin\ExpenseCompanyController@delete')->name('accounting.expense-companies.delete');
        Route::get('/recover/{expense_company_id}', 'App\Http\Controllers\Admin\ExpenseCompanyController@recover')->name('accounting.expense-companies.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ExpenseCompanyController@search')->name('accounting.expense-companies.search');
            Route::get('{name}/{category_id}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ExpenseCompanyController@filter')->name('accounting.expense-companies.filter');
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
        Route::get('/', 'App\Http\Controllers\Admin\CashAdvanceController@show')->name('accounting.cash-advances');
        Route::get('/add', 'App\Http\Controllers\Admin\CashAdvanceController@add')->name('accounting.cash-advances.add');
        Route::post('/create', 'App\Http\Controllers\Admin\CashAdvanceController@create')->name('accounting.cash-advances.create');

        // cash advance payment
        Route::group(['prefix' => 'view/{ca_number}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\CashAdvanceController@view')->name('accounting.cash-advances.view');
            Route::get('/approve/{cash_advance_id}', 'App\Http\Controllers\Admin\CashAdvancePaymentController@approve')->name('accounting.cash-advances.approve');
            Route::get('/disapprove/{cash_advance_id}', 'App\Http\Controllers\Admin\CashAdvancePaymentController@disapprove')->name('accounting.cash-advances.disapprove');
        });

        Route::post('/assign/invoice-number', 'App\Http\Controllers\Admin\CashAdvanceController@invoiceNumber')->name('accounting.cash-advances.assign.invoice-number');
        Route::post('/pay', 'App\Http\Controllers\Admin\CashAdvanceController@pay')->name('accounting.cash-advances.pay');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@print')->name('accounting.cash-advances.print');
            Route::get('excel/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@excel')->name('accounting.cash-advances.excel');
            Route::get('pdf/{ca_number}', 'App\Http\Controllers\Export\CashAdvanceController@pdf')->name('accounting.cash-advances.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\CashAdvanceController@sql')->name('accounting.cash-advances.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\CashAdvanceController@search')->name('accounting.cash-advances.search');
            Route::get('/{ca_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\CashAdvanceController@filter')->name('accounting.cash-advances.filter');
        });
    });
});

// admin group
Route::group(['prefix' => 'admin/', 'middleware' => ['auth', 'internal']], function () {
    // users
    Route::group(['prefix' => 'users/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\UserController@show')->name('admin.users');
        Route::get('/add', 'App\Http\Controllers\Admin\UserController@add')->name('admin.users.add');
        Route::post('/create', 'App\Http\Controllers\Admin\UserController@create')->name('admin.users.create');
        Route::get('/view/{user_id}', 'App\Http\Controllers\Admin\UserController@view')->name('admin.users.view');
        Route::get('/edit/{user_id}', 'App\Http\Controllers\Admin\UserController@edit')->name('admin.users.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\UserController@update')->name('admin.users.update');
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
        Route::get('/', 'App\Http\Controllers\Admin\InventoryController@show')->name('internals.inventories');
        Route::get('/view/{branch_id}', 'App\Http\Controllers\Admin\InventoryController@view')->name('internals.inventories.view');

        // management
        Route::group(['prefix' => 'manage/{branch_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\InventoryController@manage')->name('internals.inventories.manage');
            Route::post('/set-price', 'App\Http\Controllers\Admin\InventoryController@price')->name('internals.inventories.items.set-price');
            Route::post('/set-discount', 'App\Http\Controllers\Admin\InventoryController@discount')->name('internals.inventories.items.set-discount');
            Route::post('/set-barcode', 'App\Http\Controllers\Admin\InventoryController@barcode')->name('internals.inventories.items.set-barcode');

            // management
            Route::group(['prefix' => 'landing-price/{inventory_id}'], function () {
                Route::get('/', 'App\Http\Controllers\Admin\InventoryReceiveRecordController@masterlist')->name('internals.inventories.landing-price.masterlist');
                Route::post('/create', 'App\Http\Controllers\Admin\InventoryReceiveRecordController@create')->name('internals.inventories.landing-price.create');
            });

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\InventoryController@search')->name('internals.inventories.items.search');
                Route::get('/{barcode}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\InventoryController@filter')->name('internals.inventories.items.filter');
            });
        });

        // item serial numbers
        Route::group(['prefix' => 'serial-numbers/items/{item_id}/'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\ItemSerialNumberController@show')->name('internals.inventories.items.serial-numbers');
            Route::post('/create', 'App\Http\Controllers\Admin\ItemSerialNumberController@create')->name('internals.inventories.items.serial-numbers.create');
            Route::get('/edit/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@edit')->name('internals.inventories.items.serial-numbers.edit');
            Route::post('/edit', 'App\Http\Controllers\Admin\ItemSerialNumberController@update')->name('internals.inventories.items.serial-numbers.update');
            Route::post('/assign-so', 'App\Http\Controllers\Admin\ItemSerialNumberController@assignSO')->name('internals.inventories.items.serial-numbers.assign-so');

            Route::get('/available/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@available')->name('internals.inventories.items.serial-numbers.available');
            Route::get('/floating/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@floating')->name('internals.inventories.items.serial-numbers.floating');
            Route::get('/revert/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@revert')->name('internals.inventories.items.serial-numbers.revert');
            Route::get('/sold/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@sold')->name('internals.inventories.items.serial-numbers.sold');
            Route::get('/delete/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@delete')->name('internals.inventories.items.serial-numbers.delete');
            Route::get('/recover/{item_serial_number_id}', 'App\Http\Controllers\Admin\ItemSerialNumberController@recover')->name('internals.inventories.items.serial-numbers.recover');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\ItemSerialNumberController@search')->name('internals.inventories.items.serial-numbers.search');
                Route::get('/{code}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ItemSerialNumberController@filter')->name('internals.inventories.items.serial-numbers.filter');
            });
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{jo_number}', 'App\Http\Controllers\Export\InventoryController@print')->name('internals.inventories.print');
            Route::get('excel/{jo_number}', 'App\Http\Controllers\Export\InventoryController@excel')->name('internals.inventories.excel');
            Route::get('pdf/{jo_number}', 'App\Http\Controllers\Export\InventoryController@pdf')->name('internals.inventories.pdf');
            Route::get('sql', 'App\Http\Controllers\Export\InventoryController@sql')->name('internals.inventories.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\InventoryCompanyController@search')->name('internals.inventories.companies.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\InventoryCompanyController@filter')->name('internals.inventories.companies.filter');
        });
    });

    // items
    Route::group(['prefix' => 'items/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ItemController@show')->name('admin.items');
        Route::get('/add', 'App\Http\Controllers\Admin\ItemController@add')->name('admin.items.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ItemController@create')->name('admin.items.create');
        Route::get('/view/{item_id}', 'App\Http\Controllers\Admin\ItemController@view')->name('admin.items.view');
        Route::get('/edit/{item_id}', 'App\Http\Controllers\Admin\ItemController@edit')->name('admin.items.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ItemController@update')->name('admin.items.update');
        Route::get('/delete/{item_id}', 'App\Http\Controllers\Admin\ItemController@delete')->name('admin.items.delete');
        Route::get('/recover/{item_id}', 'App\Http\Controllers\Admin\ItemController@recover')->name('admin.items.recover');

        // items
        Route::group(['prefix' => 'photos/{item_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\ItemPhotoController@show')->name('admin.items.photos');
            Route::post('/create', 'App\Http\Controllers\Admin\ItemPhotoController@create')->name('admin.items.photos.create');
            Route::post('/edit', 'App\Http\Controllers\Admin\ItemPhotoController@update')->name('admin.items.photos.update');
            Route::get('/delete/{item_photo_id}', 'App\Http\Controllers\Admin\ItemPhotoController@delete')->name('admin.items.photos.delete');
            Route::get('/recover/{item_photo_id}', 'App\Http\Controllers\Admin\ItemPhotoController@recover')->name('admin.items.photos.recover');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\ItemPhotoController@search')->name('admin.items.photos.search');
                Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ItemPhotoController@filter')->name('admin.items.photos.filter');
            });
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ItemController@search')->name('admin.items.search');
            Route::get('/{barcode}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ItemController@filter')->name('admin.items.filter');
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
        Route::get('/', 'App\Http\Controllers\Admin\ClientController@show')->name('admin.clients');
        Route::get('/add', 'App\Http\Controllers\Admin\ClientController@add')->name('admin.clients.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ClientController@create')->name('admin.clients.create');
        Route::get('/view/{supplier_id}', 'App\Http\Controllers\Admin\ClientController@view')->name('admin.clients.view');
        Route::get('/edit/{supplier_id}', 'App\Http\Controllers\Admin\ClientController@edit')->name('admin.clients.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ClientController@update')->name('admin.clients.update');
        Route::get('/delete/{supplier_id}', 'App\Http\Controllers\Admin\ClientController@delete')->name('admin.clients.delete');
        Route::get('/recover/{supplier_id}', 'App\Http\Controllers\Admin\ClientController@recover')->name('admin.clients.recover');

        // manage
        Route::group(['prefix' => 'manage/{supplier_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\ClientController@manage')->name('admin.clients.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\SupplyController@masterlist')->name('admin.clients.items.masterlist');

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

        // for searching clients
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ClientController@search')->name('admin.clients.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ClientController@filter')->name('admin.clients.filter');
        });
    });

    // client contacts
    Route::group(['prefix' => 'clients/contacts/'], function () {
        Route::post('/edit', 'App\Http\Controllers\Admin\ClientContactController@update')->name('admin.clients.contact.update');
        Route::post('/create', 'App\Http\Controllers\Admin\ClientContactController@create')->name('admin.clients.contact.create');
        Route::get('/delete/{client_id}', 'App\Http\Controllers\Admin\ClientContactController@delete')->name('admin.clients.contact.delete');
        Route::get('/recover/{client_id}', 'App\Http\Controllers\Admin\ClientContactController@recover')->name('admin.clients.contact.recover');
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
        Route::get('/', 'App\Http\Controllers\Admin\ProjectCategoryController@show')->name('admin.project-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\ProjectCategoryController@add')->name('admin.project-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ProjectCategoryController@create')->name('admin.project-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\ProjectCategoryController@edit')->name('admin.project-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ProjectCategoryController@update')->name('admin.project-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\ProjectCategoryController@delete')->name('admin.project-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\ProjectCategoryController@recover')->name('admin.project-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ProjectCategoryController@search')->name('admin.project-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ProjectCategoryController@filter')->name('admin.project-categories.filter');
        });
    });

    // project sub categories
    Route::group(['prefix' => 'project-sub-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ProjectSubCategoryController@show')->name('admin.project-sub-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\ProjectSubCategoryController@add')->name('admin.project-sub-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ProjectSubCategoryController@create')->name('admin.project-sub-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\ProjectSubCategoryController@edit')->name('admin.project-sub-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ProjectSubCategoryController@update')->name('admin.project-sub-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\ProjectSubCategoryController@delete')->name('admin.project-sub-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\ProjectSubCategoryController@recover')->name('admin.project-sub-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ProjectSubCategoryController@search')->name('admin.project-sub-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ProjectSubCategoryController@filter')->name('admin.project-sub-categories.filter');
        });
    });

    // liquidation categories
    Route::group(['prefix' => 'liquidation-categories/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\LiquidationCategoryController@show')->name('admin.liquidation-categories');
        Route::get('/add', 'App\Http\Controllers\Admin\LiquidationCategoryController@add')->name('admin.liquidation-categories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\LiquidationCategoryController@create')->name('admin.liquidation-categories.create');
        Route::get('/edit/{category_id}', 'App\Http\Controllers\Admin\LiquidationCategoryController@edit')->name('admin.liquidation-categories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\LiquidationCategoryController@update')->name('admin.liquidation-categories.update');
        Route::get('/delete/{category_id}', 'App\Http\Controllers\Admin\LiquidationCategoryController@delete')->name('admin.liquidation-categories.delete');
        Route::get('/recover/{category_id}', 'App\Http\Controllers\Admin\LiquidationCategoryController@recover')->name('admin.liquidation-categories.recover');

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\LiquidationCategoryController@search')->name('admin.liquidation-categories.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\LiquidationCategoryController@filter')->name('admin.liquidation-categories.filter');
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

    // projects
    Route::group(['prefix' => 'projects/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\ProjectController@show')->name('internals.projects');
        Route::get('/add', 'App\Http\Controllers\Admin\ProjectController@add')->name('internals.projects.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ProjectController@create')->name('internals.projects.create');
        Route::get('/view/{project_id}', 'App\Http\Controllers\Admin\ProjectController@view')->name('internals.projects.view');
        Route::get('/edit/{project_id}', 'App\Http\Controllers\Admin\ProjectController@edit')->name('internals.projects.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ProjectController@update')->name('internals.projects.update');
        Route::get('/approve/{project_id}', 'App\Http\Controllers\Admin\ProjectController@approve')->name('internals.projects.approve');
        Route::get('/disapprove/{project_id}', 'App\Http\Controllers\Admin\ProjectController@disapprove')->name('internals.projects.disapprove');
        Route::get('/done/{project_id}', 'App\Http\Controllers\Admin\ProjectController@done')->name('internals.projects.done');
        Route::get('/delete/{project_id}', 'App\Http\Controllers\Admin\ProjectController@delete')->name('internals.projects.delete');
        Route::get('/recover/{project_id}', 'App\Http\Controllers\Admin\ProjectController@recover')->name('internals.projects.recover');

        Route::post('/asf', 'App\Http\Controllers\Admin\ProjectController@asf')->name('internals.projects.update.asf');
        Route::post('/vat', 'App\Http\Controllers\Admin\ProjectController@vat')->name('internals.projects.update.vat');
        Route::post('/terms', 'App\Http\Controllers\Admin\ProjectController@terms')->name('internals.projects.update.terms');
        Route::post('/duration-date', 'App\Http\Controllers\Admin\ProjectController@duration_date')->name('internals.projects.update.duration-date');
        Route::post('/start-date', 'App\Http\Controllers\Admin\ProjectController@start_date')->name('internals.projects.update.start-date');
        Route::post('/end-date', 'App\Http\Controllers\Admin\ProjectController@end_date')->name('internals.projects.update.end-date');

        // manage
        Route::group(['prefix' => 'manage/{project_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\ProjectController@manage')->name('internals.projects.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\SupplyController@masterlist')->name('internals.projects.items.masterlist');
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@print_ce')->name('internals.exports.projects.print.ce');
            Route::get('print/internal-ce/{project_id}', 'App\Http\Controllers\Export\ProjectController@print_internal_ce')->name('internals.exports.projects.print.internal-ce');
            Route::get('excel/{project_id}', 'App\Http\Controllers\Export\ProjectController@excel')->name('internals.exports.projects.ce.excel');
            Route::get('pdf/{project_id}', 'App\Http\Controllers\Export\ProjectController@pdf')->name('internals.exports.projects.ce.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\ProjectController@sql')->name('internals.exports.projects.sql');
        });

        // for searching projects
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ProjectController@search')->name('internals.projects.search');
            Route::get('/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ProjectController@filter')->name('internals.projects.filter');
        });
    });

    // details
    Route::group(['prefix' => 'details'], function () {
        Route::post('/create', 'App\Http\Controllers\Admin\ProjectDetailController@create')->name('internals.projects.details.create');
        Route::post('/update', 'App\Http\Controllers\Admin\ProjectDetailController@update')->name('internals.projects.details.update');
        Route::post('/update/price', 'App\Http\Controllers\Admin\ProjectDetailController@price')->name('internals.projects.details.update.price');
        Route::get('/approve/{id}','App\Http\Controllers\Admin\ProjectDetailController@approve')->name('internals.projects.details.approve');
        Route::get('/disapprove/{id}','App\Http\Controllers\Admin\ProjectDetailController@disapprove')->name('internals.projects.details.disapprove');
        Route::get('/activate/{id}','App\Http\Controllers\Admin\ProjectDetailController@activate')->name('internals.projects.details.activate');
        Route::get('/deactivate/{id}','App\Http\Controllers\Admin\ProjectDetailController@deactivate')->name('internals.projects.details.deactivate');
        Route::get('/recover/{project_detail_id}', 'App\Http\Controllers\Admin\ProjectDetailController@recover')->name('internals.projects.details.recover');
        Route::get('/delete/{project_detail_id}', 'App\Http\Controllers\Admin\ProjectDetailController@delete')->name('internals.projects.details.delete');

        // for searching project
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ProjectDetailController@search')->name('internals.projects.details.search');
            Route::get('/{name}', 'App\Http\Controllers\Admin\ProjectDetailController@filter')->name('internals.projects.details.filter');
        });
    });

    // brf
    Route::group(['prefix' => 'brf/'], function () {
        Route::get('/','App\Http\Controllers\Admin\BRFController@show')->name('internals.brf');

        Route::get('/view/{id}','App\Http\Controllers\Admin\BRFController@view')->name('internals.brf.view');
        Route::get('/add','App\Http\Controllers\Admin\BRFController@add')->name('internals.brf.add');
        Route::post('/create','App\Http\Controllers\Admin\BRFController@create')->name('internals.brf.create');
        Route::post('/update','App\Http\Controllers\Admin\BRFController@update')->name('internals.brf.update');
        Route::get('/approve/{id}','App\Http\Controllers\Admin\BRFController@approve')->name('internals.brf.approve');
        Route::get('/disapprove/{id}','App\Http\Controllers\Admin\BRFController@disapprove')->name('internals.brf.disapprove');
        Route::get('/activate/{id}','App\Http\Controllers\Admin\BRFController@activate')->name('internals.brf.activate');
        Route::get('/deactivate/{id}','App\Http\Controllers\Admin\BRFController@deactivate')->name('internals.brf.deactivate');
        Route::get('/delete/{brf_id}', 'App\Http\Controllers\Admin\BRFController@delete')->name('internals.brf.delete');

        // manage
        Route::group(['prefix' => 'manage/{brf_id}'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\BRFController@manage')->name('internals.brf.manage');
            Route::get('/items/masterlist', 'App\Http\Controllers\Admin\BRFController@masterlist')->name('internals.brf.items.masterlist');
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
            Route::post('/', 'App\Http\Controllers\Admin\BRFController@search')->name('internals.brf.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\BRFController@filter')->name('internals.brf.filter');
        });
    });

    // brf details
    Route::group(['prefix' => 'brf/details'], function () {
        Route::post('/create','App\Http\Controllers\Admin\BRFDetailController@create')->name('internals.brf.details.create');
        Route::post('/update','App\Http\Controllers\Admin\BRFDetailController@update')->name('internals.brf.details.update');
        Route::get('/approve/{brf_detail_id}','App\Http\Controllers\Admin\BRFDetailController@approve')->name('internals.brf.details.approve');
        Route::get('/disapprove/{brf_detail_id}','App\Http\Controllers\Admin\BRFDetailController@disapprove')->name('internals.brf.details.disapprove');
        Route::get('/activate/{brf_detail_id}','App\Http\Controllers\Admin\BRFDetailController@activate')->name('internals.brf.details.activate');
        Route::get('/deactivate/{brf_detail_id}','App\Http\Controllers\Admin\BRFDetailController@deactivate')->name('internals.brf.details.deactivate');
        Route::get('/delete/{brf_detail_id}', 'App\Http\Controllers\Admin\BRFDetailController@delete')->name('internals.brf.details.delete');
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
        Route::get('/', 'App\Http\Controllers\Admin\ReturnInventoryController@show')->name('internals.return-inventories');
        Route::get('/view/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@view')->name('internals.return-inventories.view');
        Route::get('/add', 'App\Http\Controllers\Admin\ReturnInventoryController@add')->name('internals.return-inventories.add');
        Route::post('/create', 'App\Http\Controllers\Admin\ReturnInventoryController@create')->name('internals.return-inventories.create');
        Route::get('/edit/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@edit')->name('internals.return-inventories.edit');
        Route::post('/edit', 'App\Http\Controllers\Admin\ReturnInventoryController@update')->name('internals.return-inventories.update');
        
        Route::get('/recover/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@recover')->name('internals.return-inventories.recover');
        Route::get('/cancel/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@cancel')->name('internals.return-inventories.cancel');
        Route::get('/approve/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@approve')->name('internals.return-inventories.approve');
        Route::get('/disapprove/{return_inventory_id}', 'App\Http\Controllers\Admin\ReturnInventoryController@disapprove')->name('internals.return-inventories.disapprove');

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@print')->name('internals.exports.return-inventories.print');
            Route::get('excel/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@excel')->name('internals.exports.return-inventories.excel');
            Route::get('pdf/{return_inventory_id}', 'App\Http\Controllers\Export\ReturnInventoryController@pdf')->name('internals.exports.return-inventories.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\ReturnInventoryController@sql')->name('internals.exports.return-inventories.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\ReturnInventoryController@search')->name('internals.return-inventories.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\ReturnInventoryController@filter')->name('internals.return-inventories.filter');
        });
    });

    // rma
    Route::group(['prefix' => 'rma/'], function () {
        Route::get('/', 'App\Http\Controllers\Admin\RMAController@show')->name('internals.rma');
        Route::get('/add', 'App\Http\Controllers\Admin\RMAController@add')->name('internals.rma.add');
        Route::post('/find', 'App\Http\Controllers\Admin\RMAController@find')->name('internals.rma.find');
        Route::post('/delivery-receipt/find', 'App\Http\Controllers\Admin\RMAController@delivery_receipt')->name('internals.rma.delivery-receipt.find');
        Route::post('/action-taken', 'App\Http\Controllers\Admin\RMAItemController@action_taken')->name('internals.rma.action-taken');
        Route::get('/view/{reference_number}', 'App\Http\Controllers\Admin\RMAController@view')->name('internals.rma.view');
        Route::get('/manage/{reference_number}', 'App\Http\Controllers\Admin\RMAController@manage')->name('internals.rma.manage');
        Route::get('/for-warranty/{reference_number}', 'App\Http\Controllers\Admin\RMAController@for_warranty')->name('internals.rma.for-warranty');
        Route::get('/waiting/{reference_number}', 'App\Http\Controllers\Admin\RMAController@waiting')->name('internals.rma.waiting');
        Route::get('/for-release/{reference_number}', 'App\Http\Controllers\Admin\RMAController@for_release')->name('internals.rma.for-release');
        Route::get('/out-of-warranty/{reference_number}', 'App\Http\Controllers\Admin\RMAController@out_of_warranty')->name('internals.rma.out-of-warranty');
        Route::get('/cleared/{reference_number}', 'App\Http\Controllers\Admin\RMAController@cleared')->name('internals.rma.cleared');
        Route::get('/cancel/{reference_number}', 'App\Http\Controllers\Admin\RMAController@cancel')->name('internals.rma.cancel');

        // suppliers
        Route::group(['prefix' => 'manage/{reference_number}/suppliers/'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\RMASupplierController@show')->name('internals.rma.suppliers');
            Route::get('/select/{inventory_id}', 'App\Http\Controllers\Admin\RMASupplierController@select')->name('internals.rma.suppliers.select');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\RMASupplierController@search')->name('internals.rma.suppliers.search');
                Route::get('/{name}', 'App\Http\Controllers\Admin\RMASupplierController@filter')->name('internals.rma.suppliers.filter');
            });
        });

        // items
        Route::group(['prefix' => 'manage/{reference_number}/items/'], function () {
            Route::get('/', 'App\Http\Controllers\Admin\RMAItemController@show')->name('internals.rma.items');
            Route::get('/select/{inventory_id}', 'App\Http\Controllers\Admin\RMAItemController@select')->name('internals.rma.items.select');
            Route::get('/review/{inventory_id}', 'App\Http\Controllers\Admin\RMAItemController@review')->name('internals.rma.items.review');
            Route::post('/create', 'App\Http\Controllers\Admin\RMAItemController@create')->name('internals.rma.items.create');
            Route::get('/delete/{return_inventory_item_id}', 'App\Http\Controllers\Admin\RMAItemController@delete')->name('internals.rma.items.delete');

            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Admin\RMAItemController@search')->name('internals.rma.items.search');
                Route::get('/{barcode}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\RMAItemController@filter')->name('internals.rma.items.filter');
            });
        });

        // item-serial-numbers
        Route::group(['prefix' => 'item-serial-numbers/'], function () {
            Route::post('/create', 'App\Http\Controllers\Admin\RMAItemSerialNumberController@create')->name('internals.rma.item-serial-numbers.create');
            Route::get('/recover/{rma_item_serial_number_id}', 'App\Http\Controllers\Admin\RMAItemSerialNumberController@recover')->name('internals.rma.item-serial-numbers.recover');
            Route::get('/delete/{rma_item_serial_number_id}', 'App\Http\Controllers\Admin\RMAItemSerialNumberController@delete')->name('internals.rma.item-serial-numbers.delete');
        });

        // exports
        Route::group(['prefix' => 'exports/'], function () {
            Route::get('print/{return_inventory_id}', 'App\Http\Controllers\Export\RMAController@print')->name('internals.exports.rma.print');
            Route::get('print/customer/{return_inventory_id}', 'App\Http\Controllers\Export\RMAController@customer_print')->name('internals.exports.rma.customer.print');
            Route::get('print/supplier/{return_inventory_id}', 'App\Http\Controllers\Export\RMAController@supplier_print')->name('internals.exports.rma.supplier.print');
            Route::get('excel/{return_inventory_id}', 'App\Http\Controllers\Export\RMAController@excel')->name('internals.exports.rma.excel');
            Route::get('pdf/{return_inventory_id}', 'App\Http\Controllers\Export\RMAController@pdf')->name('internals.exports.rma.pdf');
            Route::get('sql', 'App\Http\Controllers\Admin\Report\RMAController@sql')->name('internals.exports.rma.sql');
        });

        // for searching
        Route::group(['prefix' => 'search/'], function () {
            Route::post('/', 'App\Http\Controllers\Admin\RMAController@search')->name('internals.rma.search');
            Route::get('/{reference_number}/{name}/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Admin\RMAController@filter')->name('internals.rma.filter');
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

        // rma
        Route::group(['prefix' => 'rma/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\RMAController@show')->name('admin.reports.rma');
            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\RMAController@search')->name('admin.reports.rma.search');
                Route::get('/{status}/{from_date}/{to_date}', 'App\Http\Controllers\Report\RMAController@filter')->name('admin.reports.rma.filter');
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

        // service-orders
        Route::group(['prefix' => 'service-orders/'], function () {
            Route::get('/', 'App\Http\Controllers\Report\ServiceOrderController@show')->name('admin.reports.service-orders');
            // for searching
            Route::group(['prefix' => 'search/'], function () {
                Route::post('/', 'App\Http\Controllers\Report\ServiceOrderController@search')->name('admin.reports.service-orders.search');
                Route::get('{from_date}/{to_date}', 'App\Http\Controllers\Report\ServiceOrderController@filter')->name('admin.reports.service-orders.filter');
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

    Route::get('register', 'App\Http\Controllers\Auth\AuthController@register')->name('register');
    Route::post('register', 'App\Http\Controllers\Auth\AuthController@create')->name('auth.create');

    Route::get('forgot', 'App\Http\Controllers\Auth\AuthController@forgot')->name('auth.forgot');
    Route::post('reset', 'App\Http\Controllers\Auth\AuthController@reset')->name('auth.reset');
    Route::post('change/password', 'App\Http\Controllers\Auth\AuthController@change')->name('auth.change.password');

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

    // payments
    Route::group(['prefix' => 'payments/'], function () {
        Route::get('/view/{so_number}', 'App\Http\Controllers\QR\PaymentController@view')->name('qr.payments.view');
    });

    // rma
    Route::group(['prefix' => 'rma/'], function () {
        Route::get('/view/{so_number}', 'App\Http\Controllers\QR\RMAController@view')->name('qr.rma.view');
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
});

// ajax routes
Route::group(['prefix' => 'ajax/'], function () {
    Route::get('select/sub-categories/{category_id}', 'App\Http\Controllers\Admin\AjaxController@sub_categories')->name('ajax.sub-categories');
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
});