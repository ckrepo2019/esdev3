<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\Models\Principal\SPP_Gradelevel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Crypt;

class SPP_Privelege extends Model
{
    public static function storePriveleg(
        $userid = null,
        $usertype = null,
        $priv = null
    ){

        

        $data = [
            'sid'=>$userid,
            'privut'=>$usertype,
            'priv' => $priv
        ];

        $validator = self::validateform($data);

      

        if ($validator->fails()) {

            toast('Error!','error')->autoClose(2000)->toToast($position = 'top-right');

            return back()->withErrors($validator)->withInput()->with('invalidpriv',true);
        }
        else{

     

            try{    

                $userid = Crypt::decrypt($userid);
                $priv = Crypt::decrypt($priv);

                date_default_timezone_set('Asia/Manila');
                $date = date('Y-m-d H:i:s');

                DB::table('faspriv')->updateOrInsert (
                    ['userid' => $userid,'usertype' => $usertype,'deleted' => '0'],
                    ['privelege' => $priv,
                    'updatedby' => auth()->user()->id,
                    'updateddatetime' => $date
                ]);

          

                toast('Successfull','success')->autoClose(2000)->toToast($position = 'top-right');

                return "1";
    

            }catch (\Exception $e) {
               
                return "Please dont alter data";

            }
          
        }

    }

    public static function updatepriv(
        $privid = null,
        $priv = null,
        $userid = null,
        $type = null
    ){


        date_default_timezone_set('Asia/Manila');
        $date = date('Y-m-d H:i:s');

        $query =  DB::table('faspriv');

        if($privid!=null){

            $query->where('id',Crypt::decrypt($privid));

        }

        if($userid!=null){

            $query->where('userid',$userid);

        }

        if($type!=null){

            $query->where('usertype',$type);

        }

        if($priv == null){

            $query->update([
                            'deleted'=>1,
                            'updatedby'=>auth()->user()->id,
                            'updateddatetime'=>auth()->user()->id
                        ]);
        }
        else{

            $query->update([
                        'privelege'=>Crypt::decrypt($priv),
                        'updatedby'=>auth()->user()->id,
                        'updateddatetime'=>auth()->user()->id
                    ]);

        }



    }


    public static function validateform(
        $data
    ){

        $message = [
            'privut.required'=>'User type is required',
            'priv.required'=>'Privelege is required',
           
        ];

        $validator = Validator::make($data, [
            'privut' => 'required',
            'priv' => 'required',
        ], $message);

        return $validator;

    }



}
