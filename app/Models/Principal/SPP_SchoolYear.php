<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;
use Crypt;

class SPP_SchoolYear extends Model
{
    public static function loadAllSchoolYear(){

        return DB::table('sy')->orderBy('sdate','desc')->get();

    }

    public static function getSchoolYear( 
        $skip = null, 
        $take = null, 
        $searchid = null,
        $searchinfo = null,
        $type = 'all')
    {

        $data = array();
        $schoolYearQuery = DB::table('sy');

        if($searchinfo != null){
            $schoolYearQuery->where(function($query) use($searchinfo){
                $query->where('sy.sydesc','like',$searchinfo.'%');
            });
        }

        if($searchid!=null){

            $schoolYearQuery->where('id',$searchid);
        }

        $count = $schoolYearQuery->count();

        if($type == 'first'){
            $schoolYearQuery->first();
        }


        if($take!=null){
            $schoolYearQuery->take($take);
        }

        if($skip!=null){
            $schoolYearQuery->skip(($skip-1)*$take);
        }

        $schoolYear = $schoolYearQuery->get();

        array_push($data,(object)['data'=>$schoolYear,'count'=>$count]);

        return $data;

    }

    public static function storeschoolyear($request){

        $activeSy = DB::table('sy')->where('isactive','1')->count();

        if($activeSy == 0){

            $active = '1';

        }
        else{

            $active = '0';
            
        }

        $stime = Carbon::create($request->get('sdate'));
        $etime = Carbon::create($request->get('edate'));

        if($stime->isoFormat('YYYY') == $etime->isoFormat('YYYY')){

            $sydesc = 'S - '.$etime->isoFormat('YYYY');

        }
        else{

            $sydesc = $stime->isoFormat('YYYY').'-'.$etime->isoFormat('YYYY');

        }


        $checkShoolYear = DB::table('sy')->where('sydesc',$sydesc)->get();

        if(count($checkShoolYear) == 0){

            DB::table('sy')->insert([
                'sydesc'=>$sydesc,
                'sdate'=>$stime->isoFormat('YYYY-MM-DD'),
                'edate'=>$etime->isoFormat('YYYY-MM-DD'),
                'isactive'=>$active,
                'createdby'=>auth()->user()->id,
                'createddatetime'=>Carbon::now('Asia/Manila')
            ]);

            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
                
            return back();
        }
        else{

            toast('Error!','warning')->autoClose(2000)->toToast($position = 'top-right');
                
            return back();

        }

    }

    public static function setschoolyearactive(
            $id,
            $section_detail = false
        ){

        $activeSy = self::getActiveSchoolYear();

        
        $gradeSetups = DB::table('gradessetup')->where('syid',$activeSy->id)->get();

        foreach( $gradeSetups as $item){

            DB::table('gradessetup')
                ->updateOrInsert(
                    [
                        'subjid'=>$item->subjid,
                        'levelid'=>$item->levelid,
                        'syid'=> $id
                    ],
                    [
                    'writtenworks'=>$item->writtenworks,
                    'performancetask'=>$item->performancetask,
                    'qassesment'=>$item->qassesment,
                    'first'=>$item->first,
                    'second'=>$item->second,
                    'third'=>$item->third,
                    'fourth'=>$item->fourth,
                    'createdby'=>$item->createdby,
                    'createddatetime'=>Carbon::now('Asia/Manila')
                ]);


        }


        if(isset($activeSy)){
           

            $userid = auth()->user()->id;

            // date_default_timezone_set('Asia/Manila');
            // $date = date('Y-m-d H:i:s');

            DB::table('sections')->update([
                    'teacherid'=>null,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>Carbon::now('Asia/Manila')
                ]);

            $teacheracadprog = DB::table('teacheracadprog')->where('syid',$id)->count();

            if($teacheracadprog == 0){

            $teacheracadprog = DB::table('teacheracadprog')
                                ->where('syid',$activeSy->id)
                                ->get();

                foreach($teacheracadprog as $item){

                    DB::table('teacheracadprog')
                        ->updateOrInsert(
                        [   'syid'=>$id, 
                            'teacherid'=>$item->teacherid, 
                            'deleted'=>'0',
                            'acadprogid'=>$item->acadprogid,
                        ],
                        [
                            'createdby'=>$item->createdby,
                            'createddatetime'=>Carbon::now('Asia/Manila')
                        ]
                    );
                
                    
                }

            }
        }

        DB::table('sy')
            ->where('isactive','1')
            ->update([
                'isactive'=>'0',
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>Carbon::now('Asia/Manila')
            ]);

        DB::table('sy')
            ->where('id',$id)
            ->update([
                'isactive'=>'1',
                'updatedby'=>auth()->user()->id,
                'updateddatetime'=>Carbon::now('Asia/Manila')
            ]);

        toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');
        return back();

    }

    public static function getActiveSchoolYear(){

        return DB::table('sy')->where('isactive','1')->first();
        
    }


    public static function adminupdatesy(
        $id = null,
        $sdate = null,
        $edate = null
    ){

        $stime = Carbon::create($sdate);
        $etime = Carbon::create($edate);

        if($stime->isoFormat('YYYY') == $etime->isoFormat('YYYY')){

            $sydesc = 'S - '.$etime->isoFormat('YYYY');

        }
        else{

            $sydesc = $stime->isoFormat('YYYY').'-'.$etime->isoFormat('YYYY');

        }

        $checkShoolYear = DB::table('sy')->where('sydesc',$sydesc)->where('id','!=',Crypt::decrypt($id))->get();

        // return $sydesc;

        if(count($checkShoolYear) == 0){

            // return $etime->isoFormat('YYYY-MM-DD');

            DB::table('sy')->where('id',Crypt::decrypt($id))
                    ->update([
                        'sydesc'=>$sydesc,
                        'sdate'=>$stime->isoFormat('YYYY-MM-DD'),
                        'edate'=>$etime->isoFormat('YYYY-MM-DD'),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>Carbon::now('Asia/Manila')
                    ]);
            
            toast('Success!','success')->autoClose(2000)->toToast($position = 'top-right');
        
            return back();
        }
        else{

            toast('Error!','warning')->autoClose(2000)->toToast($position = 'top-right');
                
            return back();

        }

        return back();

    }
}
