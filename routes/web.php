<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\CheckController;
use App\Http\Controllers\Site\CategoryController;
use App\Http\Controllers\Site\ProductController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\CheckoutController;
use App\Http\Controllers\Site\ContactsController;
use App\Http\Controllers\Site\UsersController;


use App\Http\Controllers\Admin\GBGCmsController;
use App\Http\Controllers\Admin\GBGCategoryController;
use App\Http\Controllers\Admin\GBGFalseUrlController;
use App\Http\Controllers\Admin\GBGProductController;
use App\Http\Controllers\Admin\GBGCouponController;
use App\Http\Controllers\Admin\GBGHomeFeatureManagementController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



Route::prefix('admin')->name('admin.')->group(function(){

    Route::view('/', 'admin.login')->name('login');
    Route::middleware(['guest:admin'])->group(function(){
        Route::view('/login', 'admin.login')->name('login');
        Route::any('/dologin',[AdminController::class, 'dologin'])->name('dologin');
    });

    Route::middleware(['auth:admin'])->group(function(){
        Route::view('/home', 'admin.home')->name('home');
        //Route::any('/home', [AdminController::class, 'home'])->name('home');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        
        Route::group(['prefix' => 'gbg', 'as' => 'gbg.'], function () {
            
            Route::group(['prefix' => 'cms', 'as' => 'cms.'], function () {
                Route::any('/', [GBGCmsController::class, 'list'])->name('list');
                Route::any('/add', [GBGCmsController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGCmsController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGCmsController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGCmsController::class, 'status'])->name('status');
            });

            Route::group(['prefix' => 'category', 'as' => 'category.'], function () {
                Route::any('/', [GBGCategoryController::class, 'list'])->name('list');
                Route::any('/add', [GBGCategoryController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGCategoryController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGCategoryController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGCategoryController::class, 'status'])->name('status');
                Route::get('/deleteimage/{id}', [GBGCategoryController::class, 'deleteimage'])->name('deleteimage');
            });

            Route::group(['prefix' => 'falseurl', 'as' => 'falseurl.'], function () {
                Route::any('/', [GBGFalseUrlController::class, 'list'])->name('list');
                Route::any('/add', [GBGFalseUrlController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGFalseUrlController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGFalseUrlController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGFalseUrlController::class, 'status'])->name('status');
                Route::get('/deleteimage/{id}', [GBGFalseUrlController::class, 'deleteimage'])->name('deleteimage');
            });

            Route::group(['prefix' => 'product', 'as' => 'product.'], function () {
                Route::any('/', [GBGProductController::class, 'list'])->name('list');
                Route::any('/add', [GBGProductController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGProductController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGProductController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGProductController::class, 'status'])->name('status');
                Route::get('/deleteimage/{id}', [GBGProductController::class, 'deleteimage'])->name('deleteimage');
                Route::any('/deleteattribute', [GBGProductController::class, 'deleteattribute'])->name('deleteattribute');
            });
            
            Route::group(['prefix' => 'coupon', 'as' => 'coupon.'], function () {
                Route::any('/', [GBGCouponController::class, 'list'])->name('list');
                Route::any('/add', [GBGCouponController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGCouponController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGCouponController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGCouponController::class, 'status'])->name('status');
                Route::get('/deleteimage/{id}', [GBGCouponController::class, 'deleteimage'])->name('deleteimage');
               Route::any('/deleteattribute', [GBGCouponController::class, 'deleteattribute'])->name('deleteattribute');
            });

            Route::group(['prefix' => 'homefeaturemanagement', 'as' => 'homefeaturemanagement.'], function () {
                Route::any('/', [GBGHomeFeatureManagementController::class, 'list'])->name('list');
                Route::any('/add', [GBGHomeFeatureManagementController::class, 'add'])->name('add');
                Route::any('/edit/{id}', [GBGHomeFeatureManagementController::class, 'edit'])->name('edit');
                Route::get('/delete/{id}', [GBGHomeFeatureManagementController::class, 'delete'])->name('delete');
                Route::post('/status', [GBGHomeFeatureManagementController::class, 'status'])->name('status');
                Route::get('/deleteimage/{id}', [GBGHomeFeatureManagementController::class, 'deleteimage'])->name('deleteimage');
                Route::any('/deleteattribute', [GBGHomeFeatureManagementController::class, 'deleteattribute'])->name('deleteattribute');
                Route::any('/categoryproduct', [GBGHomeFeatureManagementController::class, 'categoryproduct'])->name('categoryproduct');
            });

        });
    });
});

Route::prefix('users')->name('users.')->group(function(){
    Route::middleware(['guest:web'])->group(function(){
        // Route::view('/login', 'user.login')->name('login');
        // Route::view('/register', 'user.register')->name('register');
        // Route::any('/create-user', [UserController::class, 'create_user'])->name('create-user');
        // Route::any('/dologin',[UserController::class, 'dologin'])->name('dologin');

        Route::any('/list', [UsersController::class, 'list'])->name('list');
        Route::get('/verifyemail/{token}', [UsersController::class, 'verify']);
        Route::any('/login', [UsersController::class, 'login'])->name('login');
        Route::any('/register', [UsersController::class, 'register'])->name('register');

        Route::any('/checkout-login-process', [UsersController::class, 'checkoutLoginProcess'])->name('checkout-login-process');
        //Guest login/register during CHECKOUT
        Route::any('/checkout-guest-login-process', [UsersController::class, 'checkoutGuestLoginProcess'])->name('checkout-guest-login-process');

        Route::any('/checkout-guest-login-gmail-process', [UsersController::class, 'checkoutGuestLoginGmailProcess'])->name('checkout-guest-login-gmail-process');
    });
    Route::middleware(['auth:web'])->group(function(){
        // Route::view('/home', 'user.home')->name('home');
        // Route::post('/logout', [UserController::class, 'logout'])->name('logout');

        Route::any('/dashboard', [UsersController::class, 'dashboard'])->name('dashboard');
        Route::any('/edit-personal-information', [UsersController::class, 'editPersonalInformation'])->name('editPersonalInformation');
        Route::any('/change-password', [UsersController::class, 'changePassword'])->name('changePassword');
        Route::any('/my-orders', [UsersController::class, 'myOrders'])->name('my-orders');
        Route::any('/my-addresses', [UsersController::class, 'myAddresses'])->name('myAddresses');
        Route::any('/add-address', [UsersController::class, 'addAddress'])->name('add-address');
        Route::any('/edit-address/{id}', [UsersController::class, 'editAddress'])->name('edit-address');
        Route::any('/delete-address', [UsersController::class, 'deleteAddress'])->name('delete-address');
        Route::any('/get-country-cities', [UsersController::class, 'getCountryCities'])->name('get-country-cities');
        Route::any('/get-address', [UsersController::class, 'getAddress'])->name('get-address');
        Route::any('/session-pincode-get-address', [UsersController::class, 'sessionPincodeGetAddress'])->name('session-pincode-get-address');
        Route::any('/my-billing-address', [UsersController::class, 'myBillingAddress'])->name('my-billing-address');
        Route::any('/add-billing-address', [UsersController::class, 'addBillingAddress'])->name('add-billing-address');
        Route::any('/edit-billing-address/{id}', [UsersController::class, 'editBillingAddress'])->name('edit-billing-address');
        Route::any('/check-other-city', [UsersController::class, 'checkothercity'])->name('check-other-city');
        Route::any('/generate-invoice/{id}',[UsersController::class, 'generateInvoice'])->name('generate-invoice');
        Route::any('/logout', [UsersController::class, 'logout'])->name('logout');
    });
});

Route::any('/', [HomeController::class, 'index'])->name('home');

Route::get('/set-currency', [HomeController::class, 'set_currency'])->name('set_currency');
Route::any('/set-currency-order-summary', [HomeController::class, 'set_currency_order_summary'])->name('set_currency_order_summary');

Route::any('/loadMore', [CategoryController::class, 'loadMore'])->name('loadMore');
Route::post('/reviewpost', [ProductController::class, 'reviewpost'])->name('reviewpost');
Route::any('/attribute-details', [ProductController::class, 'getAttributeDetails'])->name('attribute-details');

Route::any('/gift-addon-add-to-cart', [CartController::class, 'giftAddonAddToCart'])->name('gift-addon-add-to-cart');

Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::any('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart');
Route::any('/remove-item/{id}', [CartController::class, 'ajxRemoveItem'])->name('remove-item');
Route::any('/update-item', [CartController::class, 'ajxUpdateCart'])->name('update-item');

Route::any('/apply-coupon', [CartController::class, 'ajxApplyCoupon'])->name('apply-coupon');
Route::any('/coupon', [CartController::class, 'ApplyCoupon'])->name('coupon');
Route::any('/remove-applied-coupon/{id}/{orderid}', [CartController::class, 'removeAppliedCoupon'])->name('remove-applied-coupon');

//Facebook Registration
Route::any('fbregister','SocialAuthController@fbregister')->name('fbregister');

//Gmail Registration
Route::any('gmailregister','SocialAuthController@gmailregister')->name('gmailregister');

//Facebook & Gmail Registration during checkout
Route::any('fbregistercheckout','SocialAuthController@fbregistercheckout')->name('fbregistercheckout');
Route::any('gmailregistercheckout','SocialAuthController@gmailregistercheckout')->name('gmailregistercheckout');

//Reset Password Section
Route::any('/reset', 'ResetPasswordController@reset')->name('reset');
Route::any('/sendResetLinkEmail', 'ForgotPasswordController@sendResetLinkEmail')->name('forgot');
Route::any('/showResetForm/{token}', 'ResetPasswordController@showResetForm')->name('showResetForm');

Route::any('/cart-checkout', [CheckoutController::class, 'cartCheckout'])->name('cart-checkout');
Route::any('/checkout', [CheckoutController::class, 'checkoutProcess'])->name('checkout-process');
Route::any('/checkout-message', [CheckoutController::class, 'checkoutMessage'])->name('checkout-message');

//Route::group(['middleware' => 'auth:web'], function () {
    Route::any('/checkout-step-delivery-address', [CheckoutController::class, 'checkoutStepDeliveryAddress'])->name('checkout-step-delivery-address');

    Route::any('/add-new-delivery-address', [CheckoutController::class, 'addNewDeliveryAddress'])->name('add-new-delivery-address');
    Route::any('/delivery-address-update-cart', [CheckoutController::class, 'deliveryAddressUpdateCart'])->name('delivery-address-update-cart');
    Route::any('/checkout-edit-address/{id}', [CheckoutController::class, 'checkouteditAddress'])->name('checkout-edit-address');

    Route::any('/checkout-step-billing-address', [CheckoutController::class, 'checkoutStepBillingAddress'])->name('checkout-step-billing-address');
    Route::any('/add-update-billing-address', [CheckoutController::class, 'addUpdateBillingAddress'])->name('add-update-billing-address');
    Route::any('/update-billing-address-id-cart', [CheckoutController::class, 'updateBillingAddressIdCart'])->name('update-billing-address-id-cart');

    Route::any('/checkout-step-existing-message', [CheckoutController::class, 'checkoutStepExistingMessage'])->name('checkout-step-existing-message');
    Route::any('/add-update-message', [CheckoutController::class, 'addUpdateMessage'])->name('add-update-message');
    Route::any('/checkout-step-order-summary', [CheckoutController::class, 'checkoutStepOrderSummary'])->name('checkout-step-order-summary');

    
    Route::any('/order-placed', [CheckoutController::class, 'orderPlaced'])->name('order-placed');
    Route::any('/thank-you-user/{id?}', [CheckoutController::class, 'thankYou'])->name('thank-you-user');
    Route::any('/payment-cancelled', [CheckoutController::class, 'paymentCancelled'])->name('payment_cancelled');
    Route::any('/payment-error', [CheckoutController::class, 'paymentError'])->name('payment-error');


    //Paypal smart button routes//
    Route::any('/paypalOrderPlacedDetails', 'PaypalController@orderPlacedDetails')->name('paypalOrderPlacedDetails');
    Route::any('/pay-with-paypal-success', 'PaypalController@paywithpaypalSuccess')->name('pay-with-paypal-success');
    Route::any('/pay-success', 'PaypalController@paySuccess')->name('pay-success');
    Route::any('/pay-failed', 'PaypalController@payFailed')->name('pay-failed');
    //****END********//

//});

//Contact Us Section
Route::any('/contact-us', [ContactsController::class, 'contct'])->name('contact-us'); 
Route::any('/reload-captcha', [ContactsController::class, 'reloadcaptcha'])->name('reload-captcha');
Route::any('/contact-ticket/{id}', [ContactsController::class, 'contactTicket'])->name('contact-ticket');
Route::any('/view-ticket-details', [ContactsController::class, 'viewTicketDetails'])->name('view-ticket-details');
Route::any('/contact-status', [ContactsController::class, 'contactStatus'])->name('contact-status');

//For CMS pages Only//
Route::any('/bulk-orders', [HomeController::class, 'bulkOrders'])->name('bulk-orders');
Route::any('/terms-and-conditions', [HomeController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::any('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
Route::any('/substitution-policy', [HomeController::class, 'substitution_policy'])->name('substitution-policy');
Route::any('/career', [HomeController::class, 'career'])->name('career');
Route::any('/delivery-locations', [HomeController::class, 'deliveryLocations'])->name('delivery-locations');
Route::any('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::any('/faq', [HomeController::class, 'faq'])->name('faq');
Route::any('/payment', [HomeController::class, 'payment'])->name('payment');
Route::any('/disclaimer', [HomeController::class, 'disclaimer'])->name('disclaimer');
Route::any('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');
Route::any('/refund-policy', [HomeController::class, 'refundPolicy'])->name('refund-policy');
Route::any('/shipping-policy', [HomeController::class, 'shippingPolicy'])->name('shipping-policy');
Route::any('/cancellation-policy', [HomeController::class, 'cancellationPolicy'])->name('cancellation-policy');

Route::any('/order-status', [HomeController::class, 'orderStatus'])->name('order-status');
Route::any('/order-status-details', [HomeController::class, 'orderStatusDetails'])->name('order-status-details');

// Route::prefix('Site')->name('site.')->group(function(){
//     Route::any('/', [HomeController::class, 'index'])->name('home');
// });

Route::get('/{query}', [CheckController::class, 'index'])->where('query','.+');
