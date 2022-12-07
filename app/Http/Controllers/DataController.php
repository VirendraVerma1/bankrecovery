<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportData;
use App\Imports\ImportUserType;
use App\User;
use App\Data;
use App\ExcelFile;
use DB;
use Auth;


class DataController extends Controller
{
    public function data()
    {
        //$all_data=Data::paginate(50);
        $excelfile=ExcelFile::paginate(50);
        $users=User::paginate(50);
        return view('data.index',compact('excelfile','users'));
    }

    public function importView(Request $request)
    {
        return view('importFile');
    }
  
    public function import(Request $request)
    {
        $path = 'uploads/';
        $file = $this->fileUpload($request->file('file'), $path);
        try {

            $data=Excel::import(new ImportData, $file);
           
            $excelData=new ExcelFile();
            $excelData->filename=$file;
            $excelData->storedname=$request->file('file')->getClientOriginalName();
            $excelData->save();
            session()->flash('success', 'data uploaded successfully');
          } catch (\Exception $e) {
          
              //return $e->getMessage();
                // $data=Excel::import(new ImportData, $file);
                // $excelData=new ExcelFile();
                // $excelData->filename=$file;
                // $excelData->storedname=$request->file('file')->getClientOriginalName();
                // $excelData->save();
                session()->flash('danger', 'failed to upload');
          }

        return redirect()->back();
    }

    function data_delete($id)
    {
        $data=Data::find($id);
        $data->delete();
        session()->flash('success', 'data deleted successfully');
        return redirect()->back();
    }

    function data_delete_by_file($id)
    {
        $file=ExcelFile::find($id);
        $excelfiledata=Excel::toArray(new ImportData,$file->filename);
        // $datas=Data::select('id','agreementno')->get();

        $all_matched_ids=[];
        foreach($excelfiledata[0] as $excel)
        {
            array_push($all_matched_ids,$excel[0]);
        }
        
        
        
        DB::table('data')->whereIn('agreementno',$all_matched_ids)->delete();
        $file->delete();
        session()->flash('success', 'data deleted successfully');
        return redirect()->back();
    }


    public function fileUpload($request_file_name, $path)
    {
            $random = rand(0,10000);
            $file= $request_file_name;
            
            $file_name = $request_file_name;
            $file_extension = "xlsx";
            $new_name = time().'.xlsx';
            $destinationPath = public_path($path);
            $file->move($destinationPath, $random.$new_name);
            return $path.$random.$new_name;
            
    }

}

?>