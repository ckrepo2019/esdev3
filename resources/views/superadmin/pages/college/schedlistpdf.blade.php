<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding: 0 !important;
        }
       
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0 !important; 
        }

        .mt-0{
            margin-top: 0 !important; 
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        @page {  
            margin:30px 50px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }

        .text-muted {
            color: #6c757d!important;
        }

      

        @page { size: 13in 8.5in; margin: 10px 40px;  }
        
    </style>
</head>
    <body>  
        @php
            $classsched = $schedlist->data[0]->college_classsched;
            $enrolled = $schedlist->data[0]->enrolled;
            $scheddetail = $schedlist->data[0]->scheddetail;
            $schedgroup = $schedlist->data[0]->sched_group_detail;
        @endphp
         <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo->schoolname}}</h3>
                </td>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                
                </td>
            </tr>
            <tr>
                <td class="p-0 text-center">
                    {{$schoolinfo->address}}
                </td>
            </tr>
        </table>
        <table class="table mb-0 table-sm" style="font-size:13px;">
            <tr>
                <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>List of Schedules</b></td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0">{{$seminfo->semester}}, {{$syinfo->sydesc}}</td>
            </tr>
        </table>  
        <br>
        <table class="table table-striped table-sm table-bordered table-head-fixed p-0"  width="100%" >
            <thead>
                  <tr>
                        <th width="5%" style="font-size:.7rem !important">Class Type</th>
                        <th width="7%" style="font-size:.7rem !important">Subject Code</th>
                        <th width="12%" class="p-0 align-middle pl-2 text-center" class="p-0 align-middle pl-2">Section</th>
                        <th width="26%" class="align-middle">Descriptive Title</th>
                        <th class="p-0 align-middle text-center" width="4%" >Unit/s</th>
                        <th class="text-center p-0 align-middle" width="4%">Cap.</th>
                        <th class="text-center p-0 align-middle" width="6%">Students</th>
                        <th width="20%" class="align-middle">Schedule</th>
                        <th width="16%" class="align-middle">Instructor</th>
                  </tr>
                  @foreach($classsched as $item)
                        <tr>
                                <td class="align-middle">
                                    <i>@if($item->section_specification == 1)
                                        Regular
                                    @elseif($item->section_specification == 2)
                                        Special
                                    @endif</i>
                                </td>
                                <td class="p-0 align-middle pl-2 text-center" class="p-0 align-middle pl-2">{{$item->subjCode}}</td>
                                <td class="p-0 align-middle pl-2 text-center" class="p-0 align-middle pl-2">
                                    @php
                                        $group = collect($schedgroup)->where('schedid',$item->id)->values();
                                        $tempschedgroup = '';
                                        if(count($group) > 0){
                                            foreach ($group as $groupitem) {
                                                if($groupitem->courseabrv != null){
                                                    $tempschedgroup = $groupitem->courseabrv.'-'.($groupitem->levelid-16).' '.$groupitem->schedgroupdesc;
                                                }else{
                                                    $tempschedgroup = $groupitem->collegeabrv.'-'.($groupitem->levelid-16).' '.$groupitem->schedgroupdesc;
                                                }
                                                
                                            }
                                        }
                                    @endphp
                                    {{$tempschedgroup}}
                                </td>
                                <td class="align-middle">{{$item->subjDesc}}</td>
                                <td class="p-0 align-middle text-center">{{$item->lecunits + $item->labunits}}</td>
                                <td class="text-center p-0 align-middle">{{$item->capacity}}</td>
                                <td class="text-center p-0 align-middle">
                                    @php
                                        $enroll_count = collect($enrolled)
                                                            ->where('schedid',$item->id)
                                                            ->first();
                                    @endphp
									@if(isset($enroll_count))
										{{$enroll_count->enrolled}}
									@endif
                                </td>
                                <td>
                                    @php
                                        $temp_data = collect($scheddetail)->where('headerID',$item->id)->values();
                                        $temp_sched = array();
                                        $text = '';
                                        if(count($temp_data) > 0){
                                            foreach ($temp_data as $temp_data_item) {
                                                $check = collect($temp_sched)
                                                            ->where('stime',$temp_data_item->stime)
                                                            ->where('etime',$temp_data_item->etime)
                                                            ->where('schedotherclass',$temp_data_item->schedotherclass)
                                                            ->where('roomid',$temp_data_item->roomid)
                                                            ->count();
                                                if($check == 0){
                                                    array_push($temp_sched,(object)[
                                                        'schedotherclass'=>$temp_data_item->schedotherclass,
                                                        'roomname'=>$temp_data_item->roomname,
                                                        'etime'=>$temp_data_item->etime,
                                                        'stime'=>$temp_data_item->stime,
                                                        'days'=>[],
                                                        'roomid'=>$temp_data_item->roomid
                                                    ]);

                                                    $check = collect($temp_sched)
                                                            ->where('stime',$temp_data_item->stime)
                                                            ->where('etime',$temp_data_item->etime)
                                                            ->where('schedotherclass',$temp_data_item->schedotherclass)
                                                            ->where('roomid',$temp_data_item->roomid)
                                                            ->keys()
                                                            ->all();
                                                    array_push($temp_sched[$check[0]]->days,$temp_data_item->day);
                                                }else{
                                                    $check = collect($temp_sched)
                                                            ->where('stime',$temp_data_item->stime)
                                                            ->where('etime',$temp_data_item->etime)
                                                            ->where('schedotherclass',$temp_data_item->schedotherclass)
                                                            ->where('roomid',$temp_data_item->roomid)
                                                            ->keys()
                                                            ->all();

                                                    array_push($temp_sched[$check[0]]->days,$temp_data_item->day);

                                                }
                                            }

                                            $count = 0;
                                            
                                        }
                                    @endphp

                                        @foreach ($temp_sched as $temp_sched_item) 
                                            @php     
                                                $text = '';                              
                                                if($temp_sched_item->schedotherclass != null){
                                                    $text .= substr($temp_sched_item->schedotherclass,0,3).'.: ';
                                                }
                                                
                                                $text .= \Carbon\Carbon::create($temp_sched_item->stime)->isoFormat('hh:mm A').' - '.\Carbon\Carbon::create($temp_sched_item->etime)->isoFormat('hh:mm A');

                                                if(count($temp_sched_item->days) > 0){
                                                    $text .= ' / ';
                                                }

                                                foreach ($temp_sched_item->days as $temp_sched_item_days) {

                                                            $text .= $temp_sched_item_days == 1 ? 'M' :'';
                                                            $text .= $temp_sched_item_days == 2 ? 'T' :'';
                                                            $text .= $temp_sched_item_days == 3 ? 'W' :'';
                                                            $text .= $temp_sched_item_days == 4 ? 'Th' :'';
                                                            $text .= $temp_sched_item_days == 5 ? 'F' :'';
                                                            $text .= $temp_sched_item_days == 6 ? 'Sat' :'';
                                                            $text .= $temp_sched_item_days == 7 ? 'Sun' :'';
                                                }
                                                if($temp_sched_item->roomname != null){
                                                    $text .= ' / '.$temp_sched_item->roomname;
                                                }

                                                $count += 1;
                                            @endphp
                                            {{$text}}
                                            @if(count($temp_sched) != $count)
                                                <br>
                                            @endif

                                        @endforeach


                                   

                                </td>
                                <td class="align-middle">
                                    @if($item->lastname != null)
                                        {{$item->lastname}} , {{$item->firstname}}                        
                                    @endif
                                </td>
                        </tr>
                  @endforeach
            </thead>
      </table>
    </body>
</html>