<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GradeDetail extends Model
{
    protected $table = 'gradesdetail';
    protected $fillable = [
            'studid','wwws', 'ptwsqaws',	'wwps',	'ptps', 'qaps',	'wwtotal', 'pttotal', 'qatotal', 'ig',	'qg',	'ww1',	'ww2',	'ww3',	'ww4',	'ww5',	'ww6',	'ww7',	'ww8',	'ww9',	'ww0','pt1',	'pt2',	'pt3',	'pt4',	'pt5', 'pt6', 'pt7', 'pt8',	'pt9', 'pt0', 'qa1', 'remarks'
        ];
}
