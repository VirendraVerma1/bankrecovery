<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DB;
use File;
use App\Data;
use Illuminate\Support\Str;
use App\ExcelFile;

class UploadScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'excel:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'uploading my data';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public $isonmission=false;

    public function handle()
    {
        $content = File::get("public/isonmission.txt");
        if($content=="true")
        {
            //task
            //check for the is process status
            //get the file name
            //get the process id
            //store all the data in the array
            //update to the database
            //update process id 

            
            $this->DataInsertion();
            $this->DataDeletion();
            
            if($this->isonmission==false)
            {
                File::put("public/isonmission.txt","false");
            }
        }

        return Command::SUCCESS;
    }

    public function DataInsertion()
    {
        $set=1000;
        $excelfile=ExcelFile::where('status','in process')->first();
        if($excelfile)
        {
            $this->isonmission=true;
            $data = array_map('str_getcsv', file("public/".$excelfile->filename));
            $csv_header = array_slice($data, $excelfile->processedid,$set);

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
                $excelfile->processedid=$excelfile->processedid+$set;
                $excelfile->save();
            }
            else
            {
                $excelfile->status="done";
                $excelfile->save();
                echo "done";
            }            
        }
        
    }



    public function DataDeletion()
    {
        $set=1000;
        $excelfile=ExcelFile::where('status','in delete')->first();
        if($excelfile)
        {
            $this->isonmission=true;
            $data = array_map('str_getcsv', file("public/".$excelfile->filename));

            $indexing=$excelfile->processedid-$set;
            if($indexing<0)
                $indexing=0;
            $csv_header = array_slice($data, $indexing,$set);

            if($csv_header)
            {
                
                $complete_data=[];
                foreach($csv_header as $dat)
                {
                    array_push($complete_data,$dat[0]);
                }
    
                DB::table('data')->whereIn('agreementno',$complete_data)->delete();
                $excelfile->processedid=$indexing;
                $excelfile->save();
            }
            if($indexing==0)
            {
                File::delete("public/".$excelfile->filename);
                $excelfile->delete();
            }       
        }
    }
}
