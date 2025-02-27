<?php

namespace App\Models\Student\Requirements;
use DB;

use Illuminate\Database\Eloquent\Model;

class RequirementsData extends Model
{

      public static function student_requirement_list($id = null , $studid = null , $syid = null , $semid = null, $qcode = null, $levelid = null){
          
          
            $preregrequirements_list = DB::table('preregistrationreqlist')
                                        ->where('levelid',$levelid)
                                        ->leftJoin('preregistrationrequirements',function($join) use($studid,$syid,$semid,$id,$qcode){
                                            $join->on('preregistrationreqlist.id','=','preregistrationrequirements.preregreqtype');
                                            $join->where('preregistrationrequirements.deleted',0);
                                            if($id != null){
                                                $join->where('preregistrationrequirements.id',$id);
                                            }
                                            if($syid != null){
                                                $join->where('preregistrationrequirements.syid',$syid);
                                            }
                                            if($qcode != null){
                                                $join->where('preregistrationrequirements.qcode',$qcode);
                                            }
                                            else if($studid != null){
                                                $join->where('preregistrationrequirements.studid',$studid);
                                            }
                                        });
                                        
            $preregrequirements_list = $preregrequirements_list->where('preregistrationreqlist.isActive',1);
           
    
            $preregrequirements_list = $preregrequirements_list->where('preregistrationreqlist.deleted',0)
                                        ->select('picurl','description','preregistrationreqlist.id','qcode','doc_studtype')
                                        ->orderBy('preregistrationreqlist.id')
                                        ->get();


            foreach($preregrequirements_list as $item){
                $item->picurl = str_replace('/', '/' . $item->qcode . '/', $item->picurl);
            }
    
            return $preregrequirements_list;
    
        }
     
     
      
      
}
