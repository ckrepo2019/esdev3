<?php

namespace App\Models\Principal;

use Illuminate\Database\Eloquent\Model;

class SPP_String extends Model
{
    public static function paginationString($pageCount,$table,$pageNumber){

        $dataString ='<ul class="pagination pagination-sm m-0 mt-3" id="'.$table.'">
            <li class="page-item"><a class="page-link" href="#">«</a></li>';
            for ($x = 1; $x<=$pageCount;$x++){
                if($x==$pageNumber){
                    $dataString .='<li class="page-item"><a class="page-link page-link-active" id="'.$x.'" href="#">'.$x.'</a></li>';
                }
                else{
                    $dataString .='<li class="page-item"><a class="page-link" id="'.$x.'" href="#">'.$x.'</a></li>';
                }
            }
            $dataString .=' <li class="page-item"><a class="page-link" href="#">»</a></li>
        </ul>';

       return $dataString;

    }
}
