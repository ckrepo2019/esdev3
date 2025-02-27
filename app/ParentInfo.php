<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentInfo extends Model
{
    protected $table = 'parents_info';

    protected $fillable = [
            'user_info_id','guardian_name', 'g_contact_number'
        ];
        public $timestamps = false;
}
