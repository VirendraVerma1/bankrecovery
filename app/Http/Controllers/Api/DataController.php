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
use App\Data;
use App\ConnectionRequest;

class DataController extends Controller
{
    use ProcessResponseTrait,ValidationTrait;
    
    public function searchData(Request $request)
    {
        //search in agreement no,regnum,chasisnum,enginenum
        // if($this->validate_user_id($request->user_id))
        // {
           
        // }
        // else
        // {
        //     return $this->processResponse('Search',null,'failed','No user Found');
        // }

        $data=Data::where('agreementno', 'like', '%' . $request->search . '%')
        ->orWhere('regdnum', 'like', '%' . $request->search . '%')
        ->orWhere('chasisnum', 'like', '%' . $request->search . '%')
        ->orWhere('enginenum', 'like', '%' . $request->search . '%')->limit(50)->get();
        return $this->processResponse('Search',$data,'success','Here are the results');
        
    }

}
