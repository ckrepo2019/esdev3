<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;
use DB;

class SPP_PermissionRequest extends Model
{
    public static function getRequestPermissions(
        $skip = null, 
        $take = null, 
        $searchid = null,
        $search = null,
        $type = 'all',
        $reqid = null,
        $userid = null
    ){
        $data = array();

        $perreqQuery = $perreq = DB::table('perreq')
                            ->join('perreqdetail',function($join){
                                $join->on('perreq.id','=','perreqdetail.headerid');
                            })
                            ->join('users','perreqdetail.approvedby','=','users.id')
                            ->leftJoin('users as senderinfo','perreq.createdby','=','senderinfo.id')
                            ->where('perreqtype','1');
                            
                         

        if($reqid != null){

            $perreqQuery->where('perreqdetail.reqid',$reqid);

        }

        if($userid != null){
          
            $perreqQuery->where('perreqdetail.approvedby',$userid);

        }
       

        if($type == 'status'){

            return  $perreqQuery->select('perreq.status')->distinct()->orderBy('createddatetime','desc')->first();

        }
        else if($type == 'pendingsyrequest'){

            return  $perreqQuery->where('status','0')->get();

        }
        // else if($type == 'all'){

        //     $perreqQuery->whereIn('status',['0','1']);

        // }
        else if($type == 'teacher'){

            $perreqQuery->whereIn('status',['0','1','2','3']);

        }


        $perreq->when('perreqtype == 1',function($perreq){

            $perreq->join('sy','perreqdetail.reqid','=','sy.id');

        });

        $perreqQuery->select(
            'status',
            'users.name',
            'senderinfo.name as sendername',
            'perreqtype',
            'perreq.id',
            'perreqdetail.response',
            'sy.sydesc'
        );

        $count = $perreqQuery->count();

        if($take!=null){
            $perreqQuery->take($take);
        }

        if($skip!=null){

            $perreqQuery->skip(($skip-1)*$take);
            
        }

        $perreq = $perreqQuery->get();

        array_push($data,(object)['data'=>$perreq,'count'=>$count]);

        return $data;
    
    }

    public static function cancelrequest($id){

        DB::table('perreq')
                    ->join('perreqdetail',function($join) use($id){
                        $join->on('perreq.id','=','perreqdetail.headerid');
                        $join->where('perreqdetail.reqid',$id);
                    })
                    ->update([
                        'perreq.status'=>'3'
                    ]);

    }

}
