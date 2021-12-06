<?php

Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']],
    function () {

        Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {

            Route::get('/', 'WelcomeController@index')->name('index');
            //bussinessType routes
              Route::resource('bussinessType', 'BussinessTypeController')->except(['show']);
            //cities routes
            Route::resource('cities', 'CitiesController')->except(['show']);
            //regions routes
            Route::resource('regions', 'RegionsController')->except(['show']);

            //category routes
            Route::resource('categories', 'CategoryController')->except(['show']);
            //productprovider routes
            Route::resource('productprovider', 'ProductProviderController')->except(['show']);
            //product routes
            Route::resource('products', 'ProductController')->except(['show']);
Route::get('findproviderWitheID/{id}','ProductController@findproviderWitheID');

            //client routes
            Route::resource('clients', 'ClientController')->except(['show']);
            Route::resource('clients.orders', 'Client\OrderController')->except(['show']);

            //order routes
            Route::resource('orders', 'OrderController');
            Route::get('/orders/{order}/products', 'OrderController@products')->name('orders.products');

                Route::any('changestatus', 'OrderController@changestatus');
                
                  Route::any('addnotes', 'OrderController@addnotes')->name('addnotes');
                  Route::any('updatenotes', 'OrderController@updatenotes')->name('updatenotes');
            //complaints routes
            Route::resource('complaints', 'ComplaintsController');
              //bankbalance routes
            Route::resource('bankbalance', 'BankBalanceController');
            //user routes
            Route::resource('users', 'UserController')->except(['show']);

        });//end of dashboard routes
    });


