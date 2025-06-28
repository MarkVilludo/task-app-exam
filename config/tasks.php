<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Task Schedule of cleanup
    |--------------------------------------------------------------------------
    |
    */

    //default is 30 days but we can set it to 1 day for testing
    'schedule_cleanup' => env('TASK_SCHEDULE_CLEANUP', 30),
];