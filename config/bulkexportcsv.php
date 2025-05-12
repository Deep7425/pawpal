<?php

return [
    /*
    * Number of Records to be fetched per job
    */
    'records_per_job' => 700000,

    /*
    * records will be fetched in chunks for better performance
    */
    'chunks_of_records_per_job' => 10,

    /*
    * Directory where CSV will be prepared inside storage folder   
    */
    'dir' => 'app/public/exportCSV',


    'call_on_csv_success' => [
        'namespace' => 'App\Models\BulkExportCSV', 
        'method' => 'handleCSV'
    ],
    'call_on_csv_failure' => [
        'namespace' => 'App\Models\BulkExportCSV', 
        'method' => 'handleFailedCSV'
    ],

    /*
    * Database connection for bulk_export_csv table  
    */
    'db_connection' => env('DB_CONNECTION', 'mysql'),

    /*
    * Queue connection for jobs  
    */
    'queue_connection' => env('QUEUE_CONNECTION', 'database'),

    /*
    * Name of queue where job will be dispatched  
    */
    'queue' => 'BulkExportCSV',

    /*
    * Name of queue job batch   
    */
    'batch_name' => 'Bulk Export CSV',

    /*
    * The number of seconds the job can run before timing out
    * null takes default value
    * The pcntl PHP extension must be installed in order to specify job timeouts   
    */
    'job_timeout' => null,

    /*
    * if any job fails, it stops CSV preparation process
    * Decide whether partial CSV prepared should get deleted or not   
    */
    'delete_csv_if_job_failed' => false

];