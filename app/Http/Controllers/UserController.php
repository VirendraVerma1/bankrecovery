<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\User;
use App\UserType;
use Auth;


class UserController extends Controller
{
    function test_html()
    {
        return view('admin.tempdashboard');
    }

    function get_all_users()
    {
        $users=User::paginate(50);
        return view('admin.dashboard',compact('users'));
    }

    function user_edit($id)
    {
        $user=User::find($id);
        $selected_type=$user->type;
        $user_types=UserType::all();
        return view('admin.users.edituser',compact('user','user_types','selected_type'));
    }

    function user_update(Request $request)
    {
        
        $user=User::where('email',$request->email )->first();
        $user->name=$request->name;
        $user->type=$request->user_type;
        $user->password=Hash::make($request->password);
        $user->phone=$request->phone;
        $user->adhar=$request->adhar;
        $user->pan=$request->pan;
        $user->voter=$request->voterid;
        $user->bank_name=$request->bank_account_name;
        $user->account=$request->bank_account_number;
        $user->ifsc_code=$request->ifsc_code;
        $user->save();
        session()->flash('success', 'user data updated successfully');
        return redirect()->route('users');
    }

    function open_add_user_page()
    {
        $user_types=UserType::all();

        return view('admin.users.adduser',compact('user_types'));
    }

    function user_add(Request $request)
    {
        $user=User::where('email',$request->email)->first();
        if($user)
        {
            session()->flash('warning', 'user already exist');
            return redirect()->back();
        
        }

        $user=new User();
        $user->name=$request->name;
        $user->type=$request->user_type;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $user->phone=$request->phone;
        $user->adhar=$request->adhar;
        $user->pan=$request->pan;
        $user->voter=$request->voterid;
        $user->bank_name=$request->bank_account_name;
        $user->account=$request->bank_account_number;
        $user->ifsc_code=$request->ifsc_code;
        $user->save();
        session()->flash('success', 'user added successfully');
        return redirect()->route('users');
    }

    function user_delete($id)
    {
        $users=User::find($id);
        if($users->email==Auth::user()->email)
        {
            Auth::logout();
            $users->delete();
            session()->flash('success', 'user deleted successfully');
            return redirect('/login');
        }
        else{
            $users->delete();
            session()->flash('success', 'user deleted successfully');
            return redirect()->back();
        }
        
    }


    public function logout() 
    {
        Auth::logout();
        return redirect('/login');
    }
}
