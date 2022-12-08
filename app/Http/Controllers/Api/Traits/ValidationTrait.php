<?php

namespace app\Http\Controllers\Api\Traits;
use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\User;

Trait ValidationTrait{
    /*public function keyGenerator(){

        //$api_key = Str::random(20);
        return $this->processResponse('Api_Key',"y9O2fffDuVFFWgynkYwP",'success','This is your api_key');
       
       }*/
   
       public function get_connection_id(Request $request)
       {
          //echo $request->api_key;
           if($request->api_key=="dukan_y9O2fffDuVFFWgynkYwP")
           {
               $connection_id= Str::random(30);
               DB::table('connection_request')->insert(
                   ['connection_id' => $connection_id]
               );
   
               return $this->processResponse('Connection_id',$connection_id,'success','This is your connection id');
           }
           else{
   
               return $this->processResponse(null,null,'error','API_key not matched');
           }
       }

       private function validate_connection_id($key)
       {
          //echo $key;
          $connection=DB::table('connection_request')->where('connection_id',$key)->count();
          if($connection>0)
            return true;
          else
               return false;
       }

      private function validate_request($connection,$auth)
       {
          $user=DB::table('connection_request')->where('connection_id',$connection)->where('auth_code',$auth)->get();
           if(count($user)>0)
           {
               $user[0]->user_id;
               $users = User::where('id',$user[0]->user_id)->first();
               return $users;
           }
       }
}