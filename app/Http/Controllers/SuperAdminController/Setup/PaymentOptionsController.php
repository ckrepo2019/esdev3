<?php

namespace App\Http\Controllers\SuperAdminController\Setup;

use Illuminate\Http\Request;
use File;
use DB;
use Image;

class PaymentOptionsController extends \App\Http\Controllers\Controller
{
        public static function payment_option_list(){
            $paymentoptions = DB::table('onlinepaymentoptions')
                                ->join('paymenttype',function($join){
                                    $join->on('onlinepaymentoptions.paymenttype','=','paymenttype.id');
                                    $join->where('paymenttype.deleted',0);
                                })
                                ->where('onlinepaymentoptions.deleted',0)
                                ->select(
                                    'onlinepaymentoptions.*',
                                    'description'
                                )
                                ->get();

            return $paymentoptions;

        }

        public static function payment_option_create(Request $request){

            try{
                $bankname = $request->get('bankname');
                $acctname = $request->get('acctname');
                $acctnum = $request->get('acctnum');
                $mobilenum = $request->get('mobilenum');
                $paymenttype = $request->get('paymenttype');
                $optionstatus = $request->get('optionstatus');
    
                DB::table('onlinepaymentoptions')
                    ->insert([
                        'paymenttype'=>$paymenttype,
                        'optionDescription'=>$bankname,
                        'accountName'=>$acctname,
                        'accountNum'=>$acctnum,
                        'mobileNum'=>str_replace("-","",$mobilenum),
                        'isActive'=>$optionstatus,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                    ]);
                
                return array((object)[
                    'status'=>1,
                    'message'=>'Payment Option Created!'
                ]);

            }
            catch(\Exception $e){
                return self::store_error($e);
            }

        }
      
        public static function payment_option_update(Request $request){

            try{
                $paymentoptionid = $request->get('paymentoptionid');
                $bankname = $request->get('bankname');
                $acctname = $request->get('acctname');
                $acctnum = $request->get('acctnum');
                $mobilenum = $request->get('mobilenum');
                $paymenttype = $request->get('paymenttype');
                $optionstatus = $request->get('optionstatus');
    
                DB::table('onlinepaymentoptions')
                    ->where('id',$paymentoptionid)
                    ->take(1)
                    ->update([
                        'updateddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'paymenttype'=>$paymenttype,
                        'optionDescription'=>$bankname,
                        'accountName'=>$acctname,
                        'accountNum'=>$acctnum,
                        'mobileNum'=>str_replace("-","",$mobilenum),
                        'isActive'=>$optionstatus,
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Payment Option updated!'
                ]);
            }
            catch(\Exception $e){
                return self::store_error($e);
            }
        }


        public static function payment_option_delete(Request $request){

            try{
                $paymentoptionid = $request->get('paymentoptionid');
                DB::table('onlinepaymentoptions')
                    ->where('id',$paymentoptionid)
                    ->take(1)
                    ->update([
                        'deleteddatetime'=>\Carbon\Carbon::now('Asia/Manila'),
                        'deleted'=>1,
                    ]);

                return array((object)[
                    'status'=>1,
                    'message'=>'Payment Option deleted!'
                ]);
            }
            catch(\Exception $e){
                return self::store_error($e);
            }
        }

        public static function store_error($e){
            DB::table('zerrorlogs')
            ->insert([
                        'error'=>$e,
                        'createdby'=>auth()->user()->id,
                        'createddatetime'=>\Carbon\Carbon::now('Asia/Manila')
                        ]);
            return array((object)[
                  'status'=>0,
                  'message'=>'Something went wrong!'
            ]);
      }

      public static function payment_option_image_upload(Request $request){
      
        $id = $request->get('payment_option_id');
        $time = \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss');
       
        $syid = DB::table('sy')
                    ->where('isactive',1)
                    ->select('id')
                    ->first()
                    ->id;

        $urlFolder = str_replace('http://','',$request->root());
        $urlFolder = str_replace('https://','',$urlFolder);

        if (! File::exists(public_path().'paymentoptions/')) {

            $path = public_path('paymentoptions');

            if(!File::isDirectory($path)){

                File::makeDirectory($path, 0777, true, true);
            }
            
        }
    
        if (! File::exists(dirname(base_path(), 1).'/'.$urlFolder.'/paymentoptions/')) {
            $cloudpath = dirname(base_path(), 1).'/'.$urlFolder.'/paymentoptions/';
            if(!File::isDirectory($cloudpath)){
                File::makeDirectory($cloudpath, 0777, true, true);
            }
        }

        $extension = 'png';
        $file = $request->file('input_paymentoptions');
        $img = Image::make($file->path());

        if($request->get('poid') == null){
            $countPaymentOptions = $id;
        }else{
            $countPaymentOptions = $request->get('poid');
        }

        $clouddestinationPath = dirname(base_path(), 1).'/'.$urlFolder.'/paymentoptions/paymentoptions'.$countPaymentOptions.'.'.$extension;
        $destinationPath = public_path('paymentoptions/paymentoptions'.$countPaymentOptions.'.'.$extension);

        $img->save($clouddestinationPath);
        $img->save($destinationPath);

        DB::table('onlinepaymentoptions')
            ->where('id',$id)
            ->take(1)
            ->update([
                'picurl'=>'paymentoptions/paymentoptions'.$countPaymentOptions.'.'.$extension.'?random='.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYYYHHss')
            ]);

        return array((object)[
            'status'=>1,
            'data'=>'Uploaded'
        ]);
     

    }
      
}
