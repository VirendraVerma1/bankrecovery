<?php

namespace App\Imports;

use App\Data;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class ImportData implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Data([

            'agreementno' => $row[0],
            'region' => $row[1],
            'branch' => $row[2],
            'customername' => $row[3],
            'gv' => $row[4],
            'make_model' => $row[5],
            'regdnum' => $row[6],
            'chasisnum' => $row[7],
            'enginenum' => $row[8],
            'rrmname' => $row[9],
            'rrmemail' => $row[10],
            'expirydate' => $row[11],

        ]);
    }

    public function startRow(): int 
    {
         return 1;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    
}
