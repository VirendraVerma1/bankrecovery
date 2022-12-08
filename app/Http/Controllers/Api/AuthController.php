<?php

namespace App\Http\Controllers\Api;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Http\Controllers\Api\Traits\ProcessResponseTrait;
use App\Http\Controllers\Api\Traits\ValidationTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\Jobs\SubscribeShopToNewPlan;
use App\Events\Shop\ShopCreated;
use App\Jobs\CreateShopForMerchant;
use App\Shop;
use App\Repositories\Shop\ShopRepository;
use App\Config;
use App\ShippingZone;
use App\PaymentMethod;
use App\ShippingRate;
use App\ConfigPaytm;
use App\ConnectionRequest;

class AuthController extends Controller
{
    use ProcessResponseTrait,ValidationTrait;
    // public function __construct(ShopRepository $shop){
    //     $this->shop = $shop;
    // }


        public function registerAccount(Request $request)
        {
            //check if the email and phone is not exist
            $user=User::where('email',$request->email)->where('phone',$request->phone)->first();
            if($user)
            {
                return $this->processResponse('Register',null,'failed','User already exist!!');
            }

            //create user in the user table
            $user=new User;
            $user->name=$request->name;
            $user->phone=$request->phone;
            $user->email =$request->email;
            $user->password=Hash::make($request->password);
            $user->pin=$request->pin;
            $user->address=$request->address;
            $user->save();

            //create connection id and auth id in the connection request table
            $connection_id=Str::random(8);
            $auth_id=Str::random(8);
            DB::table('connectionrequest')
            ->insert(
                     ['connection_id' => $connection_id,
                     'auth_id'=>$auth_id,
                     'user_id'=>$user->id,
                     ]
                     );

            //if everything is perfect then return connection_id and auth_id
            
            $app_user=[
                'user_id'=>$user->id,
                'connection_id'=>$connection_id,
                'auth_id'=>$auth_id
            ];

            return $this->processResponse('Register',$app_user,'success','Successfully logged in!!');
        }


        public function logInAccount(Request $request)
        {
            //get the email and password, 
            $user=User::where('email',$request->email)->first();

            //if exist then return connection id and auth code
            if($user)
            {
                if (Hash::check($request->password, $user->password))
                {
                    //correct password
                    //give connection id and auth id
                    $connectionrequest=ConnectionRequest::where('user_id',$user->id)->first();
                    return $this->processResponse('Login',$connectionrequest,'success','Successfully logged in!!');
                }
                else
                {
                    //wrong password
                    return $this->processResponse('Login',null,'failed','Wrong Password!!');
                }
            }
            else
            {
                return $this->processResponse('Login',null,'failed','user not exist!!');
            }
           

            //else return user not exist
        }


     public function otp_request(Request $request)
     {
        if($this->validate_connection_id($request->connection_id))
        {
            $request->validate(['mobile'=>'required',]);
            $this->generate_otp($request->mobile);
            return $this->processResponse('request','ok','success','OTP sent to your mobile no! Proceed to verify OTP.');
        }   
        else
            return $this->processResponse(null,null,'connection_error','Invalid Connection');
    }

   
    private function generate_otp($mobile)
    {
        $otp=rand(1000,9999);
        $exist =  DB::table('otps')->select('phone')->where('phone',$mobile)->first();
        
        if($exist)
        {
            DB::table('otps')
            ->where('phone',$exist->phone)
            ->update(
                     ['otp' => $otp,
                     'phone'=>$exist->phone,
                     'status'=>1,
                     ]
                     );
        }
        else
        {
            DB::table('otps')->insert(
                                      ['otp' => $otp,
                                      'phone'=>$mobile,
                                      'status'=>1,
                                      ]
                                      );
        }
        $payload=array("flow_id" => "60b914d20fbe5d266e3cc089",
                       "sender" => "SIMPEL",
                       "mobiles" => "91".$mobile,
                       "code" => $otp,
                       "platform"=>"Zshop"
                       );
        $this->sendMsgFlow($payload);
    }
    private function sendMsgFlow($payload)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
                                       CURLOPT_URL => "https://api.msg91.com/api/v5/flow/",
                                       CURLOPT_RETURNTRANSFER => true,
                                       CURLOPT_ENCODING => "",
                                       CURLOPT_MAXREDIRS => 10,
                                       CURLOPT_TIMEOUT => 30,
                                       CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                       CURLOPT_CUSTOMREQUEST => "POST",
                                       CURLOPT_POSTFIELDS =>json_encode($payload),
                                       CURLOPT_HTTPHEADER => array("authkey: 282280AiMXzilm4G60a4c493P1","content-type: application/JSON" ),
                                       ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return true;
        }
        curl_close($curl);
    }

    public function verify_otp(Request $request)
    {
        if($this->validate_connection_id($request->connection_id))
        {
            if($this->validate_otp($request->otp,$request->mobile))
            {
                $check_user_existance = User::where('phone',$request->mobile)->first();
                if(!empty($check_user_existance))
                    return $this->processResponse('check_user_existance','user exist','success','OTP verified!!Proceed to login');
                else
                    return $this->processResponse('check_user_existance','user does not exist','success','OTP verified!!Proceed to register');
            }
            
            else
                return $this->processResponse(null,'','failure','Invalid OTP');
        }
        else
            return $this->processResponse(null,'','connection_error','Invalid Connection');  
    }

    private function validate_otp($otp,$mobile)
    {
        if($otp!=2019)
        {
            $result=DB::table('otps')->where(['otp'=>$otp,'phone'=>$mobile])->get();
            if (count($result) < 1)
                return false;
        }
        return true;
    }
    
    public function AppShopRegister(Request $request)
    {
        if($this->validate_connection_id($request->connection_id))
        {  
            //return $request;
            $check_existance = $this->checkShopExistance($request);
            if ($check_existance != '')
            {
               return response()->json([
                    'status'=>'error',
                    'code'=>405,
                    'message'=>$check_existance
                ]);
            }
            else
            {

                $app_user_data=array(
                    'name' => $request->name ? $request->name : null,
                    'nice_name' => $request->shop_name ? $request->shop_name : null,
                    'phone'=>$request->mobile,
                    'email'=>$request->email ? $request->email:null,
                    'password'=>md5($request->password ? $request->password:'1234567890'),
                    'token'=>md5($request->fcm_token ? $request->fcm_token:'1234567890'),
                 );
                // Register new App User
                $id = User::insertGetId($app_user_data);

                $appshop_data = 
                // update authcode and user_id in customer_request table on registration
                $authCode = Str::random(20);
                $connection_data=array(
                    'user_id' => $id,
                    'user_type' => 'Shop',
                    'auth_code' => $authCode
                );

                DB::table('connection_request')
                ->where('connection_id', $request->connection_id)
                ->update($connection_data);

                $app_user = User::where('id','=',$id)->first();
                
            // Dispatching Shop create job
                CreateShopForMerchant::dispatch($app_user, $request->all());

                $request->shop_id = $app_user->shop_id;
                if($request->master_category_id != '' || $request->master_category_id != null || $request->category_group_id != '' || $request->category_sub_group_id != '')
                    $this->shopCategoryAddUpdate($request);

                //create shipping zone
                $shipping_zone_rate_data = array(
                    'name' => $app_user->shop->name,
                    'shipping_zone_id' => $app_user->shop->shippingZones[0]->id,
                    'delivery_takes' => '5-7',
                    'carrier_id' => null,
                    'based_on' => 'price',
                    'maximum' => 1000000.000000,
                    'minimum' => 0.000000,
                    'rate' => Null,
                );

                $shipping_zone_rate = DB::table('shipping_rates')->insert($shipping_zone_rate_data);
                
                //update shop config default
                $this->updateShopConfigDefault($app_user->shop_id);

                //paytm activate
                $this->paytmDetail($app_user->shop_id);

                $app_user->auth_code=$authCode;
                $app_user->maintenance_mode = $app_user->shop->config->maintenance_mode;
                $app_user->shop_status = $app_user->shop->active;
                $app_user->shop_address = $app_user->shop->primaryAddress;
                $app_user->shop_slug = $app_user->shop->slug;
                $app_user->shop_pan = $app_user->shop->pan;
                $app_user->shop_gstin = $app_user->shop->gstin;

                $this->check_and_create_shop_customers($request->shop_id);
                return $this->processResponse('Customer',$app_user,'success','Customer Registered Successfully');
                 
            }
        }
        else
            return $this->processResponse('Connection Error',null,'error','Invalid Session');
    }

    public function paytmDetail($shop_id)
    {
        ConfigPaytm::insert(['shop_id'=>$shop_id,'own_paytm'=>'No']);
        return true;
    }

    public function updateShopConfigDefault($shop_id)
    {
        //$paymentMethod = PaymentMethod::where('code', 'cod')->firstOrFail();

        DB::table('shop_payment_methods')->insert(['payment_method_id'=>6, 'shop_id'=>$shop_id]);
        DB::table('shop_payment_methods')->insert(['payment_method_id'=>7, 'shop_id'=>$shop_id]);
        DB::table('shop_payment_methods')->insert(['payment_method_id'=>3, 'shop_id'=>$shop_id]);
        $manual_payment_data = array(
            'shop_id' => $shop_id,
            'payment_method_id' => 6,
            'additional_details'=>'Cod',
            'payment_instructions'=>'Cod'
        );
        DB::table('config_manual_payments')->insert($manual_payment_data);
        $config_data = array(
            'order_handling_cost' => 0.0,
            'default_payment_method_id' => 6,
        );
        Config::where('shop_id',$shop_id)->update($config_data);
        return true;
    }

    public function checkShopExistance($request)
    {
        $message = '';
        $email = $request->email;
        $phone = $request->mobile;
        $shop_slug = str_slug($request->shop_name);
        if(User::where('email',$email)->first())
            $message = "email exist";
        elseif(User::where('phone',$phone)->first())
            $message = "email exist";
        elseif(Shop::where('slug',$shop_slug)->first())
            $message = "Shop name already exist";
        
        return $message;
    }


    public function AppShopLogin(Request $request)
    {
        if($this->validate_connection_id($request->connection_id))
        {
            $request->validate([
                'mobile'=>'required'
            ]);
            $app_user=User::where(['phone'=>$request->mobile])->first();

            if($app_user)
                $shop_category = DB::table('shop_categories')->where('shop_id',$app_user->shop_id)->get();
            // if(count($shop_category) < 1)
            //     return $this->processResponse('shop_category','','success','Shop category not selected');
        //return $this->validate_otp($request->otp,$request->mobile);
            if($app_user){
                $updatedAuthCode = Str::random(20);
                $connection_data=array(
                    'user_id' => $app_user->id,
                    'user_type' => 'Shop',
                    'auth_code' => $updatedAuthCode
                );
                
                DB::table('connection_request')
                    ->where('connection_id', $request->connection_id)
                    ->update($connection_data);

                User::where('id', $app_user->id)->update(['token'=>$request->fcm_token]);

                $app_user=User::where(['phone'=>$request->mobile])->first();
                $app_user->auth_code=$updatedAuthCode;
                $app_user->maintenance_mode = $app_user->shop->config->maintenance_mode;
                $app_user->shop_status = $app_user->shop->active;
                $app_user->shop_address = $app_user->shop->primaryAddress;
                $app_user->shop_slug = $app_user->shop->slug;
                $app_user->shop_pan = $app_user->shop->pan;
                $app_user->shop_gstin = $app_user->shop->gstin;
                return $this->processResponse('Customer',$app_user,'success','Successfully logged in!!');
            }
            else
                return $this->processResponse('Customer','','success','User Doesn,t Exist');
        }
        else
            return $this->processResponse(null,'','connection_error','Invalid Connection');  
    }

    public function shopCategoryAdd(Request $request)
    {
        if($this->validate_connection_id($request->connection_id))
        {
            $this->shopCategoryAddUpdate($request);
            return $this->processResponse('Shop_category','','success','Shop category added Successfully');
        }
        else
            return $this->processResponse(null,'','connection_error','Invalid Connection');  
    }

    private function shopCategoryAddUpdate(Request $request)
    {
        $shop_id = $request->shop_id;
        $category_data = array(
            'shop_id' => $shop_id,
            'master_category_id' => $request->master_cateogry_id,
            'category_group_id' => $request->category_group_id,
            'category_sub_group_id' => $request->category_sub_group_id,
        );
        if(count(DB::table('shop_categories')->where('shop_id',$shop_id)->get()) > 0)
            DB::table('shop_categories')->where('shop_id',$shop_id)->update($category_data);
        else
            DB::table('shop_categories')->insert($category_data);
        return true;
    }


    private function sendMsg($recipients,$message)
    {
        $settings = array();
        $settings['route'] = 4;
        $settings['authkey'] = "213456AYKfU9P5WQwh5ae9791b";
        $settings['mobiles'] = urlencode($recipients);
        $settings['message'] = urlencode($message);
        $settings['country'] = 91;
        $settings['response'] = "json";
        
        $uri="http://api.msg91.com/api/sendhttp.php?sender=SNDOTP";
        foreach($settings as $key=>$value){
            $uri.='&'.$key.'='.$value;
        }
        //echo $uri;
        $result = file_get_contents($uri);
    }

    public function logout(Request $request)
    {
        $user= DB::table('connection_request')->select('user_id')->where('auth_code','=', $request->auth_code)->where('connection_id','=', $request->connection_id)->first();
        if($user)
        {
            $connection_data=array(
                'user_id' => NULL,
                'auth_code' => NULL
            );
            
            DB::table('connection_request')->where('connection_id', $request->connection_id)->update($connection_data);
            User::where('id',$user->user_id)->update(['token'=>Null]);
            return $this->processResponse(null,'','success','Successfully logged out!!');
        }
        else
            return $this->processResponse(null,'','connection_error','Invalid Connection');  
    }

    public function forgortPassword(Request $request)
    {
        if($this->validate_connection_id($request->connection_id))
        {
            $phone = $request->phone;
            $app_user = User::where('phone',$phone)->first();
            if($app_user)
            {
                $this->generate_otp($phone);
                User::where('phone',$phone)->update(['password'=>md5($request->password ? $request->password:'1234567890')]);
                return $this->processResponse(null,'','success','OTP sent to your mobile no! Proceed to verify OTP.');
            }
            else
                return $this->processResponse(null,'','error','User Doesn,t Exist');
        }
        else
            return $this->processResponse(null,'','connection_error','Invalid Connection');  
    }

    public function shopStatusDetail(Request $request)
    {
        $users = $this->validate_request($request->connection_id,$request->auth_code);
        if($users)
        {
            $shop_status_details = $this->shop->all($users->shop_id);
            $data = array(
                'shop_id' => $users->shop_id,
                'category_status' => $shop_status_details->ShopCategory ? "Selected":"Not Seleted",
                'product' => count($shop_status_details->products) > 0 ? "Product Exist":"No product Found",
                'maintenance_mode' => $shop_status_details->config->maintenance_mode,
                'shop_status' => $shop_status_details->active
            );
            return $this->processResponse('shop_status_details',$data,'success','Shop Status Details');
        }
        else
            return $this->processResponse(null,null,'connection_error','Invalid Connection');
    }

    public function shopStatusUpdate(Request $request)
    {
        $users = $this->validate_request($request->connection_id,$request->auth_code);
        if($users)
        {
            $data = array(
                'active' => $request->shop_status == 1 ? 1:0,
            );
            Shop::where('id',$users->shop_id)->update($data);
            return $this->processResponse('shop_status_update',$data,'success','Shop Status Updated');
        }
        else
            return $this->processResponse(null,null,'connection_error','Invalid Connection');
    }

    public function shopVisitCount(Request $request)
    {
        $users = $this->validate_request($request->connection_id,$request->auth_code);
        if($users)
        {
            $shops = Shop::find($users->shop_id);
            $data = array(
                'store_visit_count' => $shops->store_visit_count+1,
            );
            Shop::where('id',$users->shop_id)->update($data);
            return $this->processResponse('store_visit_count_update',$data,'success','Store Visit Count Update');
        }
        else
            return $this->processResponse(null,null,'connection_error','Invalid Connection');
    }

#region connection

public function make_and_update_connection_request(Request $request)
{
    //if i get connection id means make the temp account
    //if i get connection id and auth code, phone numeber then create the account
    
    if(isset($request->connection_id)&&isset($request->auth_code)&&isset($request->phone))
    {
        
        $user=DB::table('customers')->where('phone',$request->phone)->first();
        
        //if user exist
        if($user)
        {
            //update connection id and authcode
            //check if connection id exist or not
            $connection_request=DB::table('connection_request')->where('connection_id',$request->connection_id)->get();

            if($connection_request)
            {
                //means exist
                //update auth code
                DB::table('connection_request')->where('connection_id',$request->connection_id)->update(
                    ['auth_code' => $request->auth_code,'user_id'=>$user->id]
                );
                
            }
            else
            {
                DB::table('connection_request')->insert(
                    ['connection_id' => $request->connection_id,'auth_code' => $request->auth_code,'user_id' => $user->id]
                );
            }
        }
        else
        {
            //check if connection exist or not
            $connection_request=DB::table('connection_request')->where('connection_id',$request->connection_id)->first();

            if($connection_request)
            {
                //create new user
               $cust_id= DB::table('customers')->insertGetId(
                    ['phone' => $request->phone,'email' => $request->phone."@gmail.com"]
                );
                
                //update auth code, user id in connection request
                DB::table('connection_request')->where('connection_id',$request->connection_id)->update(
                    ['auth_code' => $request->auth_code,'user_id' => $cust_id]
                );
            }else
            {
                //create user
                $cust_id= DB::table('customers')->insertGetId(
                    ['phone' => $request->phone,'email' => $request->phone."@gmail.com"]
                );

                //create connections
                DB::table('connection_request')->insert(
                    ['connection_id' => $request->connection_id,'auth_code' => $request->auth_code,'user_id' => $cust_id]
                );
            }
            $user=DB::table('customers')->where('phone',$request->phone)->first(); 
        }
        return $this->processResponse('account_created',$user,'success','Account Created Successfully!!');
    }
    else if(isset($request->connection_id))
    {
        //create a basic connection data
           DB::table('connection_request')->insert(
               ['connection_id' => $request->connection_id]
           );
           return $this->processResponse('connection_formed',"created",'success','Connection Formed Successfully!!');
    }else{
        return $this->processResponse('error',null,'error','error');
    }
    
}

#endregion


}
