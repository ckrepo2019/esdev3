<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class Attendance extends Model
{
    protected $table = 'studattendance';
    protected $fillable = [
        'studid', 'rfid', 'syid','present', 'absent','cc','attdate','tdate','remarks'
    ];
}
