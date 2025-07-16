<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Models\CombinedOrder;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CheckoutController;
use DB;
use Schema;
use Session;

class BkashController extends Controller
{
    private $base_url;
    public function __construct()
    {
        if (get_setting('bkash_sandbox', 1)) {
            $this->base_url = "https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/";
        } else {
            $this->base_url = "https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/";
        }
    }

    public function pay()
    {
        $amount = 0;
        if (Session::has('payment_type')) {
            if (Session::get('payment_type') == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));
                $amount = round($combined_order->grand_total);
            } elseif (Session::get('payment_type') == 'wallet_payment') {
                $amount = round(Session::get('payment_data')['amount']);
            } elseif (Session::get('payment_type') == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail(Session::get('payment_data')['customer_package_id']);
                $amount = round($customer_package->amount);
            } elseif (Session::get('payment_type') == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail(Session::get('payment_data')['seller_package_id']);
                $amount = round($seller_package->amount);
            }
        }

        // Session::forget('bkash_token');
        // Session::put('bkash_token', $this->getToken());
        Session::put('amount', $amount);
        return redirect()->route('bkash.create_payment');
    }

    public function create_payment()
    {
            $auth = $this->auth();
        $requestbody = array(
            'mode' => '0011',
            'payerReference' => ' ',
            'callbackURL' => route('bkash.callback'),
            'amount' => Session::get('amount'),
            'currency' => 'BDT',
            'intent' => 'sale',
            'merchantInvoiceNumber' => "Inv" . Date('YmdH') . rand(1000, 10000)
        );
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
             'Authorization:' . $auth,
            'X-APP-Key:' . env('BKASH_CHECKOUT_APP_KEY')
        );

        $url = curl_init($this->base_url . 'checkout/create');
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        return redirect(json_decode($resultdata)->bkashURL);
    }
    
     function getIdTokenFromRefreshToken($refresh_token){
	
	    $request_data = array('app_key'=> env('BKASH_CHECKOUT_APP_KEY'), 'app_secret'=>env('BKASH_CHECKOUT_APP_SECRET'), 'refresh_token'=>$refresh_token);
        $request_data_json=json_encode($request_data);

        $header = array(
                'Content-Type:application/json',
                'username:'.env('BKASH_CHECKOUT_USER_NAME'),
                'password:'.env('BKASH_CHECKOUT_PASSWORD')
                );
       
        $url = curl_init($this->base_url.'checkout/token/refresh');
        curl_setopt($url,CURLOPT_HTTPHEADER, $header);
        curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url,CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        curl_close($url);
        // dd($resultdata);
		$resultarr = json_decode($resultdata,true);
		if(isset($resultarr['id_token']))return $resultarr['id_token'];
        return null;
    	
    }
 public function auth(){
        
       
        
        if(get_setting('bkash_sandbox', 1)){
            $sandbox = true;
        }else{
            $sandbox = false;
        }
        
        

  
        if (!Schema::hasTable('bkash_token')) {
            DB::beginTransaction();
            Schema::create('bkash_token', function ($table) {
                $table->boolean('sandbox_mode')->notNullable();
                $table->bigInteger('id_expiry')->notNullable();
                $table->string('id_token', 2048)->notNullable();
                $table->bigInteger('refresh_expiry')->notNullable();
                $table->string('refresh_token', 2048)->notNullable();
            });
            $insertedRows = DB::table('bkash_token')->insert([
                'sandbox_mode' => 1, 
                'id_expiry' => 0, 
                'id_token' => 'id_token', 
                'refresh_expiry' => 0, 
                'refresh_token' => 'refresh_token',
            ]);
            
            if ($insertedRows > 0) {
                
                // echo 'Row inserted successfully.';
            } else {
                echo 'Error inserting row.';
            }
            
            
            
            $insertedRows = DB::table('bkash_token')->insert([
                'sandbox_mode' => 0, 
                'id_expiry' => 0, 
                'id_token' => 'id_token', 
                'refresh_expiry' => 0, 
                'refresh_token' => 'refresh_token', 
            ]);
            
            if ($insertedRows > 0) {
                // echo 'Row inserted successfully.';
                
            } else {
                echo 'Error inserting row.';
            }
            // DB::commit();
        }
        
        
        DB::beginTransaction();
        
        $tokenData = DB::table('bkash_token')->where('sandbox_mode', $sandbox)->first();

        if ($tokenData) {
            // Access the token data
            $idExpiry = $tokenData->id_expiry;
            $idToken = $tokenData->id_token;
            $refreshExpiry = $tokenData->refresh_expiry;
            $refreshToken = $tokenData->refresh_token;
            
            if($idExpiry>time()){
                // dd("Id token from db: ".$idToken);
                // Log::info('Id token from db: '.$sandbox.$idToken);
                return $idToken;
            }
            if($refreshExpiry>time()){
                $idToken = $this->getIdTokenFromRefreshToken($refreshToken);
				if($idToken != null){
					$updatedRows = DB::table('bkash_token')
						->where('sandbox_mode',$sandbox)
						->update([
							'id_expiry' => time() + 3300, // Set new expiry time
							'id_token' => $idToken,
						]);
					
					if ($updatedRows > 0) {
						DB::commit();
						//echo 'Rows updated successfully.';
					} else {
						//echo 'Error updating rows.';
					}
					// dd("Id token from refresh api: ".$idToken);
					// Log::info('Id token from refresh api: '.$sandbox.$idToken);
					return $idToken;
				}
            }
            // Do something with the token data
        } else {
            echo 'Token not found.';
        }
        
        
        $request_data = array('app_key'=> env('BKASH_CHECKOUT_APP_KEY'), 'app_secret'=>env('BKASH_CHECKOUT_APP_SECRET'));
        $request_data_json=json_encode($request_data);

        $header = array(
			'Content-Type:application/json',
			'username:'.env('BKASH_CHECKOUT_USER_NAME'),
			'password:'.env('BKASH_CHECKOUT_PASSWORD')
		);
       
        $url = curl_init($this->base_url.'checkout/token/grant');
        curl_setopt($url,CURLOPT_HTTPHEADER, $header);
        curl_setopt($url,CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url,CURLOPT_POSTFIELDS, $request_data_json);
        curl_setopt($url,CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

        $resultdata = curl_exec($url);
        curl_close($url);
        // dd($resultdata);
        $idToken = json_decode($resultdata)->id_token;
        
        $updatedRows = DB::table('bkash_token')
            ->where('sandbox_mode',$sandbox)
            ->update([
                'id_expiry' => time() + 3300, // Set new expiry time
                'id_token' => $idToken,
                'refresh_expiry' => time() + 864000,
                'refresh_token' => json_decode($resultdata)->refresh_token,
            ]);
        
        if ($updatedRows > 0) {
            DB::commit();
            //echo 'Rows updated successfully.';
        } else {
            //echo 'Error updating rows.';
        }
        // dd("Id token from grant api: ".$idToken);
        // Log::info('Id token from grant api: '.$sandbox.$idToken);
        return $idToken;
	    

    }


    // public function getToken()
    // {
    //     $request_data = array('app_key' => env('BKASH_CHECKOUT_APP_KEY'), 'app_secret' => env('BKASH_CHECKOUT_APP_SECRET'));
    //     $request_data_json = json_encode($request_data);

    //     $header = array(
    //         'Content-Type:application/json',
    //         'username:' . env('BKASH_CHECKOUT_USER_NAME'),
    //         'password:' . env('BKASH_CHECKOUT_PASSWORD')
    //     );

    //     $url = curl_init($this->base_url . 'checkout/token/grant');
    //     curl_setopt($url, CURLOPT_HTTPHEADER, $header);
    //     curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
    //     curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($url, CURLOPT_POSTFIELDS, $request_data_json);
    //     curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
    //     curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);

    //     $resultdata = curl_exec($url);
    //     curl_close($url);

    //     $token = json_decode($resultdata)->id_token;
    //     return $token;
    // }

    public function callback(Request $request)
    {
        $allRequest = $request->all();
        if (isset($allRequest['status']) && $allRequest['status'] == 'failure') {
            return view('frontend.bkash.fail')->with([
                'errorMessage' => 'Payment Failure'
            ]);
        } else if (isset($allRequest['status']) && $allRequest['status'] == 'cancel') {
            return view('frontend.bkash.fail')->with([
                'errorMessage' => 'Payment Cancelled'
            ]);
        } else {

            $resultdata = $this->execute($allRequest['paymentID']);
            Session::forget('payment_details');
            Session::put('payment_details', $resultdata);

            $result_data_array = json_decode($resultdata, true);
            if (array_key_exists("statusCode", $result_data_array) && $result_data_array['statusCode'] != '0000') {
                return view('frontend.bkash.fail')->with([
                    'errorMessage' => $result_data_array['statusMessage'],
                ]);
            } else if (array_key_exists("message", $result_data_array)) {
                // if execute api failed to response
                sleep(1);
                $resultdata = json_decode($this->query($allRequest['paymentID']));

                if ($resultdata->transactionStatus == 'Initiated') {
                    return redirect()->route('bkash.create_payment');
                }
            }

            return redirect()->route('bkash.success');
        }
    }

    public function execute($paymentID)
    {

        $auth =   $this->auth();

        $requestbody = array(
            'paymentID' => $paymentID
        );
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . env('BKASH_CHECKOUT_APP_KEY')
        );

        $url = curl_init($this->base_url . 'checkout/execute');
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }

    public function query($paymentID)
    {

        $auth = $this->auth();

        $requestbody = array(
            'paymentID' => $paymentID
        );
        $requestbodyJson = json_encode($requestbody);

        $header = array(
            'Content-Type:application/json',
            'Authorization:' . $auth,
            'X-APP-Key:' . env('BKASH_CHECKOUT_APP_KEY')
        );

        $url = curl_init($this->base_url . 'checkout/payment/status');
        curl_setopt($url, CURLOPT_HTTPHEADER, $header);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_POSTFIELDS, $requestbodyJson);
        curl_setopt($url, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($url, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $resultdata = curl_exec($url);
        curl_close($url);

        return $resultdata;
    }


    public function success(Request $request)
    {
        $payment_type = Session::get('payment_type');

        if ($payment_type == 'cart_payment') {
            return (new CheckoutController)->checkout_done(Session::get('combined_order_id'), $request->payment_details);
        }
        if ($payment_type == 'wallet_payment') {
            return (new WalletController)->wallet_payment_done(Session::get('payment_data'), $request->payment_details);
        }
        if ($payment_type == 'customer_package_payment') {
            return (new CustomerPackageController)->purchase_payment_done(Session::get('payment_data'), $request->payment_details);
        }
        if ($payment_type == 'seller_package_payment') {
            return (new SellerPackageController)->purchase_payment_done(Session::get('payment_data'), $request->payment_details);
        }
    }
}
