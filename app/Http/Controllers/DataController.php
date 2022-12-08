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
use File;
use Illuminate\Support\Str;

class DataController extends Controller
{
    public function data()
    {
        //$all_data=Data::paginate(50);
        $excelfile=ExcelFile::paginate(50);
        $users=User::paginate(50);
        // $all_data=Data::paginate(10000);
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
            //Task to done
            //store the file
            //update the processedid of the excelfile
            //update the status to pending
            //update the isonmission.txt file to true



            //$data=Excel::toArray(new ImportData, $file);
            //$data=Excel::import(new ImportData, $file);
           
            $excelData=new ExcelFile();
            $excelData->filename=$file;
            $excelData->storedname=$request->file('file')->getClientOriginalName();
            $excelData->processedid=1;
            $excelData->status="in process";
            $excelData->save();

            $this->setdata("true");

            session()->flash('success', 'data is being processed');
          } catch (\Exception $e) {
                File::delete($file);
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
        $file->status="in delete";
        $file->save();
        $this->setdata("true");
        // $excelfiledata=Excel::toArray(new ImportData,$file->filename);
        // // $datas=Data::select('id','agreementno')->get();

        // $all_matched_ids=[];
        // foreach($excelfiledata[0] as $excel)
        // {
        //     array_push($all_matched_ids,$excel[0]);
        // }
        
        // DB::table('data')->whereIn('agreementno',$all_matched_ids)->delete();
        // File::delete("uploads/".$file->filename);
        // $file->delete();
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


    public function isshedulingon()
    {
        $content = File::get("isonmission.txt");
            if($content=="true")
            {
                $string = Str::random(10);
                $values = array('name' => $string);
                DB::table('test')->insert($values);
            }
        dd($content);
    }


    public function filetest()
    {
        //task
        //check for the is process status
        //get the file name
        //get the process id
        //store all the data in the array
        //update to the database
        //update process id 


        $excelfile=ExcelFile::where('status','in process')->first();
        if($excelfile)
        {
            $data = array_map('str_getcsv', file($excelfile->filename));
            $csv_header = array_slice($data, $excelfile->processedid,1000);

            if($csv_header)
            {
                $complete_data=array();
                foreach($csv_header as $dat)
                {
                    $fillable = [
                        'agreementno' => $dat[0],
                        'region' => $dat[1],
                        'branch' => $dat[2],
                        'customername' => $dat[3],
                        'gv' => $dat[4],
                        'make_model' => $dat[5],
                        'regdnum' => $dat[6],
                        'chasisnum' => $dat[7],
                        'enginenum' => $dat[8],
                        'rrmname' => $dat[9],
                        'rrmemail' => $dat[10],
                        'expirydate' => $dat[11],
                    ];
                    array_push($complete_data,$fillable);
                }
    
    
                Data::insert($complete_data);
                $excelfile->processedid=$excelfile->processedid+1000;
                $excelfile->save();
            }
            else
            {
                $this->setdata("false");
            }            
        }
    }

}

?>