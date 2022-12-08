<?php
namespace app\Http\Controllers\Api\Traits;

Trait ProcessResponseTrait{
    public function processResponse($key="data",$data=null,$status,$message=null)
    {
        
        if($status=='success'){
            return response()->json([
                'status'=>$status,
                 $key=>$data,
                'message'=>$message,
                'code'=>200,
            ]);
                
        }
        else if($status == 'failure')
        {
            return response()->json([
                'status'=>$status,
                'message'=>$message,
                'code'=>000000,
            ]);
        }
        else if($status == 'connection_error')
        {
            return response()->json([
                'status'=>'Connection Failure',
                'message'=>'Invalid Connection',
                'code'=>100000,
            ]);
        }
        else{
            return response()->json([
                'status'=>$status,
                'message'=>$message,
                'code'=>404,
            ]);
        }
    }

}

?>