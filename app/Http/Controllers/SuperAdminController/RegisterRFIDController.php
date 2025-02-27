<?php

namespace App\Http\Controllers\SuperAdminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class RegisterRFIDController extends Controller
{

    public static function download_rfid(Request $request){

        $rfidcards = DB::table('rfidcard')
                        ->where('deleted',0)
                        ->get();

        $schoolinfo = Db::table('schoolinfo')
                        ->first();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();;
        $sheet = $spreadsheet->getActiveSheet();
        $row = 2;
        $sheet->setCellValue('A1','RFID #');
        foreach($rfidcards as $rfidcard){
            $sheet->setCellValue('A'.$row,$rfidcard->rfidcode);
            $sheet->getStyle('A'.$row)->getAlignment()->setHorizontal('left');
            $row += 1;
        }
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getStyle('A2:A5000')->getNumberFormat()
        ->setFormatCode('@');
        $sheet->getStyle('A2:A5000')->getAlignment()->setHorizontal('left');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        try{  ob_clean(); }catch(\Exception $e){}
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="'.$schoolinfo->abbreviation.'_RFID_list_'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHmmss').'.xlsx"');
        $writer->save("php://output");
        exit();
    }

    public static function upload_rfid(Request $request){


        $path = $request->file('input_ecr')->getRealPath();
            
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $datas = $sheet->toArray();
  
        $row = 2;
        foreach($datas as $data){
            $check = DB::table('rfidcard')
                        ->where('deleted',0)
                        ->where('rfidcode',$data[0])
                        ->count();

            if($check == 0 && is_numeric($data[0])){
                DB::table('rfidcard')
                    ->insert([
                        'rfidcode'=>$data[0],
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::Now('Asia/Manila')
                    ]);
            }
            $row  += 1;
        }

        
        return array((object)[
            'status'=>1,
            'message'=>'Uploaded Successfully'
        ]);

    }


    public static function fetch_assigned_rfid(){

        $studinfo = DB::table('studinfo')
                        ->whereNotNull('rfid')
                        ->select(
                            'rfid',
                            'id',
                            DB::raw("CONCAT(studinfo.lastname,' ',studinfo.firstname) as studentname")
                        )
                        ->get();
      

        foreach($studinfo as $item){
            DB::table('rfidcard')
                ->where('rfidcode',$item->rfid)
                ->update([
                    'holder'=>$item->id,
                    'holdertype'=>7,
                    'holdername'=>$item->studentname
                ]);
        }

        $studinfo = DB::table('teacher')
                    ->whereNotNull('rfid')
                    ->select(
                        'rfid',
                        'id',
                        DB::raw("CONCAT(teacher.lastname,' ',teacher.firstname) as studentname")
                    )
                    ->get();


        foreach($studinfo as $item){
            DB::table('rfidcard')
                ->where('rfidcode',$item->rfid)
                ->update([
                    'holder'=>$item->id,
                    'holdertype'=>1,
                    'holdername'=>$item->studentname
                ]);
        }


        return array((object)[
            'status'=>1,
            'message'=>'Successfull'
        ]);
    

    }

    public static function expire_rfid(Request $request){

        try{

            //self::fetch_assigned_rfid();
            $dataid = $request->get('dataid');
            $datatype = $request->get('datatype');

            $info = (object)[];

            if($dataid != null && $dataid != ''){
                $info = Db::table('rfidcard')
                            ->where('id',$dataid)
                            ->first();
            }

            DB::table('studinfo')
                ->where(function($query) use($info,$datatype){
                    if(isset($info->id)){
                        if($info->holdertype == 7){
                            $query ->where('id',$info->holder);
                        }
                    }
                    if($datatype == 1){
                        $query->whereNull('id');
                    }else{
                        $query->whereNotNull('id');
                    }
                })
                ->update([
                    'rfid'=>null,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            DB::table('teacher')
                ->where(function($query) use($info,$datatype){
                    if(isset($info->id)){
                        if($info->holdertype == 1){
                            $query->where('id',$info->holder);
                        }
                    }
                    if($datatype == 7){
                        $query->whereNull('id');
                    }else{
                        $query->whereNotNull('id');
                    }
                })
                ->update([
                    'rfid'=>null,
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                ]);


            DB::table('rfidcard')
                ->where(function($query) use($info,$dataid,$datatype){
                    if($dataid != null && $dataid != ''){
                        $query->where('id',$dataid);
                    }
                    if($datatype == 1 || $datatype == 7){
                        $query->where('holdertype',$datatype);
                    } 
                })
                ->where('isexpired',0)
                ->update([
                    'type'=>'expired',
                    'isexpired'=>1,
                    'dateexpire'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

           
            DB::table('rfidcard_renewal')
                ->where(function($query) use($info,$datatype){
                    if(isset($info->id)){
                        $query->where('holderid',$info->holder);
                        $query->where('holdertype',$info->holdertype);
                    }
                    if($datatype == 1 || $datatype == 7){
                        $query->where('holdertype',$datatype);
                    } 
                })
                ->where('deleted',0)
                ->whereNull('dateexpired')
                ->update([
                    'updatedby'=>auth()->user()->id,
                    'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                    'dateexpired'=>\Carbon\Carbon::now('Asia/Manila')
                ]);

            return array((object)[
                'status'=>1,
                'message'=>'Successfull'
            ]);
    

        }catch(\Exception $e){
            return self::store_error($e,auth()->user()->id);
        }

        
    }


    public static function rfidcard_list(Request $request){

        // return $request->all();
        $search = $request->get('search');
        $search = $search['value'];
        $type = $request->get('filter_type');
        $assignment = $request->get('filter_assignment');
        $status = $request->get('filter_status');
        $holdertype = $request->get('filter_holdertype');
        $statusdate = $request->get('filter_enrollmentdate');
        $regdate = $request->get('filter_regdate');

        $rfid_list_temp = Db::table('rfidcard')
                        ->where(function($query) use($search,$type,$assignment,$status,$holdertype,$regdate,$statusdate){
                            if($search != null){
                                $query->orWhere('holdername','like','%'.$search.'%');
                                $query->orWhere('rfidcode','like','%'.$search.'%');
                            }
                            if($type != null){
                                $query->where('type',$type);
                            }
                            if($status != null){
                                $query->where('isexpired',$status);
                            }
                            if($holdertype != null){
                                $query->where('holdertype',$holdertype);
                            }
                            if($regdate != null){
                                $temptransdate = explode(' - ',$regdate);
                                $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                                $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                                $query->whereBetween('createddatetime', [$startdate,$enddate]);
                            }

                            if($statusdate != null){
                                $temptransdate = explode(' - ',$statusdate);
                                $startdate = \Carbon\Carbon::create($temptransdate[0].' 00:00')->isoFormat('YYYY-MM-DD');
                                $enddate = \Carbon\Carbon::create($temptransdate[1].' 24:00')->isoFormat('YYYY-MM-DD');
                                $query->whereBetween('dateexpire', [$startdate,$enddate]);
                            }

                            if($assignment != null){
                                if($assignment == 0){
                                    $query->whereNull('holder');
                                }else{
                                    $query->whereNotNull('holder');
                                }
                            }
                        })
                        ->where('deleted',0);

        $rfid_count = $rfid_list_temp
                        ->count();

        $rfid_list =  $rfid_list_temp 
                            ->take($request->get('length'))
                            ->skip($request->get('start'))
                            ->get();
        

       

        $rfid_renewal_list = Db::table('rfidcard_renewal')
                                ->whereIn('rfid',collect($rfid_list)->pluck('id'))
                                ->where('deleted',0)
                                ->get();
                    
        

        foreach($rfid_list as $item){
            $item->createddatetime = \Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
            $item->datetime = \Carbon\Carbon::create($item->dateexpire)->isoFormat('MMM DD, YYYY hh:mm A');
            $item->date = \Carbon\Carbon::create($item->dateexpire)->isoFormat('MM/DD/YYYY');
            $item->regdate =  \Carbon\Carbon::create($item->createddatetime)->isoFormat('MM/DD/YYYY');
            

            if($item->type == 'renewal' || $item->type == 'new'){
                $rfid_renewal_list_detail = collect($rfid_renewal_list)
                                        ->where('holderid',$item->holder)
                                        ->whereNull('dateexpired')
                                        ->first();
                                       
                if(isset($rfid_renewal_list)){
                    $item->datetime = \Carbon\Carbon::create($rfid_renewal_list_detail->createddatetime)->isoFormat('MMM DD, YYYY hh:mm A');
                    $item->date = \Carbon\Carbon::create($rfid_renewal_list_detail->createddatetime)->isoFormat('MM/DD/YYYY');
                }
            }

        }

        $active = DB::table('rfidcard')
                    ->where('isexpired',0)
                    ->where('deleted',0)
                    ->count();

        return @json_encode((object)[
            'data'=>$rfid_list,
            'active'=>$active,
            'recordsTotal'=>$rfid_count,
            'recordsFiltered'=>$rfid_count
        ]);
    }
    
    public static function date_reg_count(){

        $bydate = DB::table('rfidcard')
                    ->select(DB::raw('DATE_FORMAT(createddatetime, "%Y-%m-%d") as regdate'))
                    ->where('deleted',0)
                    ->groupBy('regdate')
                    ->orderByDesc('createddatetime')
                    ->get();

        foreach($bydate as $item){
            $item->count = DB::table('rfidcard')
                                ->where('createddatetime','like','%'.$item->regdate.'%')
                                ->where('deleted',0)
                                ->count();
        }
        return $bydate;

    }

    public static function rfid_delete(Request $request){

        try{
            $id = $request->get('dataid');

            $check = Db::table('rfidcard')
                        ->where('id',$id)
                        ->first();

            if($check->holder != null){
                return array((object)[
                    'status'=>0,
                    'message'=>'Card is already used!'
                ]);
            }else{
                DB::table('rfidcard')
                    ->where('id',$id)
                    ->update([
                        'deleted'=>1,
                        'deletedby'=>auth()->user()->id,
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Deleted successfully!'
                ]);
            }
        }catch(\Exception $e){
            return self::store_error($e,auth()->user()->id);
        }
    }
   
    public function storerfid($id,$schoolid){

        try{
            $rfidCount = DB::table('rfidcard')
                        ->where('rfidcode',$id)
                        ->where('deleted',0)
                        ->count();

            if(  $rfidCount == 0){

                DB::table('rfidcard')
                    ->insert([
                        'rfidcode'=>$id,
                        'rfidschoolid'=>$schoolid,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'RFID Registered!'
                ]);

            }{
                return array((object)[
                    'status'=>0,
                    'message'=>'Already Exist!'
                ]);
            }
        }catch(\Exception $e){
            return self::store_error($e,auth()->user()->id);
        }
    }

    public static function store_error($e,$userid = null){
        DB::table('zerrorlogs')
            ->insert([
                'error'=>$e,
                'createdby'=>$userid,
                'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
            ]);
        return array((object)[
              'status'=>0,
              'message'=>'Something went wrong!'
        ]);
    }

    public static function create_logs($message = null, $id = null,$userid = null){
       DB::table('logs') 
         ->insert([
              'dataid'=>$id,
              'module'=>1,
              'message'=>$message,
              'createdby'=>$userid,
              'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
        ]);
    }

}
