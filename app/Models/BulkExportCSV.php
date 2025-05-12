<?php

namespace App\Models;

//use Jenssegers\Mongodb\Eloquent\Model;
use Illuminate\Database\Eloquent\Model;

class BulkExportCSV extends Model
{
    
    public function __construct(array $attributes = []){
        $this->connection = config('bulkexportcsv.db_connection');
        parent::__construct($attributes);
    }
    
    protected $table = "bulk_export_csv";

    protected $guarded = [];  public function handleCSV($bulkExportConfig)
    {
        $csv_path = $bulkExportConfig->csv_path;

   
    }

    public function handleFailedCSV($bulkExportConfig)
    {
        $csv_path = $bulkExportConfig->csv_path; //CSV may not exist if 'delete_csv_if_job_failed' mention in configuration is true
        $error = \App\Models\BulkExportCSV::where('jobs_id', $bulkExportConfig->jobs_id)->first()->error;
        //If job was failed, error will be "Jobs Exception: ......."
        //If this method itself thrown an exception error will be "Method Exception: ......."
    
    }



}
