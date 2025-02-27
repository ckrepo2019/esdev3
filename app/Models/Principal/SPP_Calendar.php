<?php

namespace App\Models\Principal;
use DB;
use Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Principal\SPP_SchoolYear;
use \Carbon\Carbon;


use Illuminate\Database\Eloquent\Model;

class SPP_Calendar extends Model
{
   
    protected $table = 'schoolcal';


    public static function getHolidayType(){

        return DB::table('scholidaytype')->get();

    }

    public static function getEventType(
        $class = null
    ){
        
        if($class!=null){

            return DB::table('schoolcaltype')->where('type',$class)->orderBy('sort')->get();

        }

    }

    public static function getHoliday(
        $skip = null,
        $take = null,
        $holidayid = null,
        $holidayinfo = null,
        $syid = null
    ){
        $holiday = DB::table('schoolcal')
                    ->join('schoolcaltype',function($join){
                        $join->on('schoolcal.type','=','schoolcaltype.id');
                    });

        if($syid !=null ){

            $holiday->join('sy',function($join) use($syid){
                $join->on('schoolcal.syid','=','sy.id');
                $join->where('sy.id',$syid);
            });

        }
        else{

            // $holiday->join('sy',function($join){
            //     $join->on('schoolcal.syid','=','sy.id');
            //     $join->where('sy.isactive','1');
            // });
        }

        if($holidayid!=null){

            $holiday->where('schoolcal.id',$holidayid);

        }

 

        if($holidayinfo!=null){

            $holiday->where(function($query) use($holidayinfo){
                $query->where('schoolcal.description','like',$holidayinfo.'%');
                $query->orWhere('schoolcaltype.typename','like',$holidayinfo.'%');
            });
            
        }

        $holiday->join('sy',function($join) use($syid){
                        $join->on('schoolcal.syid','=','sy.id');
                    });
        
        $holiday->select(   'schoolcal.*',
                            'schoolcaltype.typename as typedesc',
                            'schoolcaltype.id as typeid',
                            'schoolcaltype.type as eventtype',
                            'sy.sydesc'
                        )
                ->where('schoolcal.deleted','0')
                ->orderby('datefrom');

        $count = $holiday->count();

        if($take!=null){

            $holiday->take($take);

        }

        if($skip!=null){

            $holiday->skip(($skip-1)*$take);
        }

        $data = array();

        $holidays = $holiday->distinct()->get();
    
        array_push($data,(object)['data'=>$holidays,'count'=>$count]);

        return $data;    
       

       
    }

    public static function insertHoliday(
        $date = null,
        $description = null,
        $type = null,
        $noclass = 0,
        $annual = 0,
        $si = null
       
    ){

        $data = [
            'holiday'=>$date,
            'des'=>$description,
            'type'=>$type,
            'si'=>$si,
            'noclass'=>$noclass,
            'annual'=>$annual,
        ];

        $validator = self::validateHolidayForm($data);


        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput();

        }
        else{

            date_default_timezone_set('Asia/Manila');
            $todaydate = date('Y-m-d H:i:s');

            $syid = DB::table('sy')->where('isactive','1')->first();
    
            $dateexplode = explode(' - ',$date);

            $sdate = str_replace('/','-',$dateexplode[0]);
            $edate = str_replace('/','-',$dateexplode[1]);

            DB::table('schoolcal')->insert([
                'type'=>$type,
                'datefrom'=>$sdate,
                'dateto'=>$edate,
                'syid'=>$syid->id,
                'description'=>strtoupper($description),
                'noclass'=>$noclass,
                'annual'=>$annual,
                'deleted'=>'0',
                'createdby'=>auth()->user()->id,
                'createddatetime'=> Carbon::now('Asia/Manila')
            ]);

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();
        }
    }

    public static function updateHoliday(
        $date = null,
        $description = null,
        $type = null,
        $noclass = 0,
        $annual = 0,
        $si = null
    ){

        $data = [
            'holiday'=>$date,
            'des'=>$description,
            'type'=>$type,
            'si'=>$si,
            'noclass'=>$noclass,
            'annual'=>$annual
        ];

        $validator = self::validateHolidayForm($data);

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');
            return back()->withErrors($validator)->withInput()->with('update','update');

        }
        else{

            date_default_timezone_set('Asia/Manila');
            $todaydate = date('Y-m-d H:i:s');

            $syid = DB::table('sy')->where('isactive','1')->first();
    
            $dateexplode = explode(' - ',$date);

            $sdate = str_replace('/','-',$dateexplode[0]);
            $edate = str_replace('/','-',$dateexplode[1]);

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            DB::table('schoolcal')
                ->where('id',$si)
                ->update([
                    'type'=>$type,
                    'datefrom'=>$sdate,
                    'dateto'=>$edate,
                    'syid'=>$syid->id,
                    'description'=>strtoupper($description),
                    'deleted'=>'0',
                    'noclass'=>$noclass,
                    'annual'=>$annual,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=> Carbon::now('Asia/Manila')
                ]);

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
            return back();
        }

    }

    public static function removeHoliday($id){

        DB::table('schoolcal')->where('id',$id)
                ->update([
                    'deleted'=>'1',
                    'deletedby'=>auth()->user()->id,
                    'deleteddatetime'=>Carbon::now('Asia/Manila')
                ]);

        toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();
    }

    public static function validateHolidayForm($data){


        $message = [
            'des.required'=>'Event name is required.',
            'des.unique'=>'This event is already in the Calendar',
            'type.required'=>'Event type is required.',
            'type.unique'=>'Event already exist.'
        ];
        


        $validator = Validator::make($data, [
            'des' => ['required',Rule::unique('schoolcal','description')->where(function($query){
                return $query->where('deleted','0')->where('syid',SPP_SchoolYear::getActiveSchoolYear()->id);
            })->ignore($data['si'],'id')],
            'type' => 'required'
        ], $message);


        return $validator;

    }

    
}
