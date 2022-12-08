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
use App\ConnectionRequest;

class DataController extends Controller
{
    use ProcessResponseTrait,ValidationTrait;
    
    public function searchData(Request $request)
    {
        //search in agreement no,regnum,chasisnum,enginenum
        $data=Data::where($field, 'like', '%' . $search . '%')->get();
    }

}
