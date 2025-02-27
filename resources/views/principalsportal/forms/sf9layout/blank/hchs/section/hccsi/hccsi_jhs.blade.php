<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
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
            margin-bottom: 0;
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

        td{
            padding-left: 5px;
            padding-right: 5px;
        }
        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: 10px 15px;  }
        
    </style>
</head>
<body>
    @php
        $pic_url = base_path().'/public/'.DB::table('schoolinfo')->first()->picurl;
    @endphp
    @foreach($enrolledstud as $item)
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <td width="50%" style="vertical-align: top;">
                    <table class="table" style="width: 100%; table-layout: fixed;">
                        <tr>
                            <td width="30%" style="text-align: left;">
                                <img src="{{$pic_url}}" alt="school" width="100px">
                            </td>
                            <td style="width: 60%; text-align: center;">
                                <div style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo->schoolname}}</div>
                                <div style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</div>
                                <div style="width: 100%; font-size: 12px;">{{$schoolinfo->address}}</div>
                                <div style="width: 100%; font-size: 12px; margin-top: 15px;"><b>Report on Learning Progress and Achievements</b></div>
                                <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div>
                            </td>
                            <td width="10%"></td>
                        </tr>
                        <hr>
                        <tr>
                            <table style="width: 100%; font-size: 11px; margin-top: 5px;" >
                                <tr>
                                    <td width="50%" class="text-left p-0"><b>{{$item->fullname}}</b></td>
                                    <td width="10%" class="text-left p-0"></td>
                                    <td width="20%" class="text-right p-0">LRN1:</td>
                                    <td width="20%" class="text-right p-0"><b>{{$item->lrn}}</b></td>
                                </tr>
                                <tr>
                                    <td width="50%" class="text-left p-0">{{$gradelevel->levelname}} - {{$section_info->sectionname}}</td>
                                    <td width="10%" class="text-left p-0"></td>
                                    <td width="20%" class="text-right p-0"></td>
                                    <td width="20%" class="text-right p-0"></td>
                                </tr>
                                <tr>
                                    <td width="30%" class="text-left p-0">{{$item->gender}} / Age:  </td>
                                    <td width="10%" class="text-left p-0"></td>
                                    <td width="60%" class="text-right p-0" colspan="2">Curriculum: K12 Basic Education Curriculum</td>
                                </tr>
                            </table>
                        </tr>
                    </table>
                {{--  --}}
                    
                    @php
                        $acadtext = '';
                        if($gradelevel->acadprogid == 3){
                            $acadtext = 'GRADE SCHOOL';
                        }else if($gradelevel->acadprogid == 4){
                            $acadtext = 'JUNIOR HIGH SCHOOL';
                        }
                    @endphp
                

                    <br>
                    <table class="table table-sm table-bordered grades" width="100%">
                        <thead>
                            <tr>
                                <td colspan="7" class="align-middle text-center"><b>ACADEMIC ACHIEVEMENT</b></td>
                            </tr>
                            <tr>
                                <td rowspan="2"  class="align-middle text-center" width="40%"><b>LEARNING AREAS</b></td>
                                <td colspan="4"  class="text-center align-middle">Quarter</b></td>
                                <td rowspan="2"  class="text-center align-middle" width="15%" >AVERAGE</b></td>
                                <td rowspan="2"  class="text-center align-middle" width="15%" >REMARKS</b></span></td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle" width="7.5%">1</td>
                                <td class="text-center align-middle" width="7.5%">2</td>
                                <td class="text-center align-middle" width="7.5%">3</td>
                                <td class="text-center align-middle" width="7.5%">4</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->grades as $item_grade)
                                <tr>
                                    <td style="padding-left:{{$item_grade->subjCom != null ? '2rem':'.25rem'}}" >{{$item_grade->subjdesc!=null ? $item_grade->subjdesc : null}}</td>
                                    <td class="text-center align-middle">{{$item_grade->quarter1 != null ? $item_grade->quarter1:''}}</td>
                                    <td class="text-center align-middle">{{$item_grade->quarter2 != null ? $item_grade->quarter2:''}}</td>
                                    <td class="text-center align-middle">{{$item_grade->quarter3 != null ? $item_grade->quarter3:''}}</td>
                                    <td class="text-center align-middle">{{$item_grade->quarter4 != null ? $item_grade->quarter4:''}}</td>
                                    <td class="text-center align-middle">{{isset($item_grade->finalrating) ? $item_grade->finalrating:''}}</td>
                                    <td class="text-center align-middle">{{isset($item_grade->actiontaken) ? $item_grade->actiontaken:''}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                                <td class="text-center">{{collect($item->finalgrade)->first()->finalrating}}</td>
                                <td class="text-center" style="font-size: 8px !important">{{collect($item->finalgrade)->first()->actiontaken}}</td>
                            </tr>
                        </tbody>
                    </table>

               
                    <table style="width: 100%; margin-top: 5px;">
                        <tr>
                            <td  width="100%" class="p-0">
                                @php
                                    $width = count($item->attendance) != 0? 55 / count($item->attendance) : 0;
                                @endphp
                                <table class="table table-bordered table-sm grades mb-0" width="100%">
                                    <tr>
                                        <td width="18%" style="padding-top: 13px!important;border: 1px solid #000; text-align: center;">ATTENDANCE</td>
                                        @foreach ($item->attendance as $att_item)
                                            <td class="aside text-center align-middle;" width="{{$width}}%"><span style="text-transform: uppercase;">{{\Carbon\Carbon::create(null, $att_item->month)->isoFormat('MMM')}}</span></td>
                                        @endforeach
                                        <td class="text-center" width="10%" style="vertical-align: middle; font-size: 12px!important;"><span>TOTAL</span></td>
                                    </tr>
                                    <tr class="table-bordered">
                                        <td >Days of School</td>
                                        @foreach ($item->attendance as $att_item)
                                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->days : '' }}</td>
                                        @endforeach
                                        <td class="text-center align-middle">{{collect($item->attendance)->sum('days')}}</td>
                                    </tr>
                                    <tr class="table-bordered">
                                        <td>Days of Present </td>
                                        @foreach ($item->attendance as $att_item)
                                            <td class="text-center align-middle">{{$att_item->days != 0 ? $att_item->present : ''}}</td>
                                        @endforeach
                                        <td class="text-center align-middle" >{{collect($item->attendance)->where('days','!=',0)->sum('present')}}</td>
                                    </tr>
                                    <tr class="table-bordered">
                                        <td>Days of Tardy</td>
                                        @foreach ($item->attendance as $att_item)
                                            <td class="text-center align-middle" >{{$att_item->days != 0 ? $att_item->absent : ''}}</td>
                                        @endforeach
                                        <td class="text-center align-middle" >{{collect($item->attendance)->sum('absent')}}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>

                </td>

                
                <td width="50%" style="height; 100px; vertical-align: top;">
                    <table class="table table-sm" style="font-size: 10px!important; margin-top: 10px;" width="100%">
                        <tr>
                            <td width="100%" class="p-0">
                                <table class="table-sm table table-bordered mb-0 mt-0" width="100%">
                                        <tr>
                                            <td colspan="6" class="align-middle text-center">Reports on Learner's Observed Values</td>
                                        </tr>
                                        <tr>
                                            <td rowspan="2" colspan="2" class="align-middle text-center">Observed Values and Attitudes</td>
                                            <td colspan="4" class="cellRight"><center>Quarter</center></td>
                                        </tr>
                                        <tr>
                                            <td width="7.5%"><center>1</center></td>
                                            <td width="7.5%"><center>2</center></td>
                                            <td width="7.5%"><center>3</center></td>
                                            <td width="7.5%"><center>4</center></td>
                                        </tr>
                                        {{-- ========================================================== --}}
                                        @php
                                            $rv = $item->observedvalues[0]->rv;
                                        @endphp
                                        @foreach (collect($item->observedvalues[0]->checkGrades)->groupBy('group') as $groupitem)
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach ($groupitem as $descitem)
                                                @if($descitem->value == 0)
                                                @else
                                                    <tr >
                                                        @if($count == 0)
                                                                <td width="21%" class="align-middle" rowspan="{{count($groupitem)}}">{{$descitem->group}}</td>
                                                                @php
                                                                    $count = 1;
                                                                @endphp
                                                        @endif
                                                        <td class="align-middle" style="border-left: 1px solid #fff; !important" >{{$descitem->description}}</td>
                                                        <td class="text-center align-middle">
                                                            @foreach ($rv as $key=>$rvitem)
                                                                {{$descitem->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                            @endforeach 
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @foreach ($rv as $key=>$rvitem)
                                                                {{$descitem->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                            @endforeach 
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @foreach ($rv as $key=>$rvitem)
                                                                {{$descitem->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                                            @endforeach 
                                                        </td>
                                                        <td class="text-center align-middle">
                                                            @foreach ($rv as $key=>$rvitem)
                                                                {{$descitem->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                            @endforeach 
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        {{-- ========================================================== --}}
                                        
                                </table>
                                <table class="table-sm table" width="100%" style="border: 1px solid #000;" >
                                    <tr>
                                        <td width="22%" class="p-0" style="margin-left: 10px!important;margin-top: 5px!important;">Marking Code</td>
                                        <td width="25%" class="p-0" style="margin-top: 5px!important;"><b>AO</b> - Always Observed</td>
                                        <td width="53%" class="p-0" style="margin-top: 5px!important;margin-bottom: 5px!important;"><b>AO</b> - Sometimes Observed</td>
                                    </tr>
                                </table>
                              
                                <table width="100%" style="font-size:10px!important;" class="table table-sm">
                                    <tr>
                                        <td width="17%" class="p-0"></td>
                                        <td width="33%" class="p-0">Eligible for transfer and admission to</td>
                                        <td width="19%" class="p-0 text-center" style="border-bottom:1px solid #000;">{{$gradelevel->levelname}}</td>
                                        <td width="5%" class="p-0">Date</td>
                                        <td width="14%" class="p-0  text-center" style="border-bottom:1px solid #000;">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</td>
                                        <td width="12%" class="p-0"></td>
                                    </tr>
                                </table>
                                <br>
                                <br>
                                <table style="width: 100%; padding-top: 25px; text-align: center;">
                                    <tr>
                                        <td class="p-0" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;">{{$adviser}}</td>
                                        <td class="p-0" width="10%" style=""></td>
                                        <td class="p-0" width="45%" style="border-bottom: 1px solid #000; font-size: 12px;">{{$principal_info[0]->name}}</td>
                                    </tr>
                                    <tr>
                                        <td class="p-0" width="45%" style="text-align: center; font-size: 10px;"><b>Class Adviser</b></td>
                                        <td class="p-0" width="10%" style=""></td>
                                        <td class="p-0" width="45%" style="text-align: center; font-size: 10px;"><b>{{$principal_info[0]->title}}</b></td>
                                    </tr>
                                </table>
                                <br>
                                <table width="100%" style="border-top: 1px solid #000;">
                                    <tr style="font-size:10px;">
                                        <td class="text-center" width="100%"><b>CANCELLATION OF TRANSFER ELIGIBILITY</b></td>
                                    </tr>
                                </table>
                                <table width="100%">
                                    <tr style="font-size:10px;">
                                        <td width="19%"></td>
                                        <td width="30%">Has been admitted to (School)</td>
                                        <td width="41%" style="border-bottom:1px solid #000;" class="p-0"></td>
                                        <td width="10%"></td>
                                    </tr>
                                </table>
                                <table width="100%">
                                    <tr style="font-size:10px;">
                                        <td width="19%"></td>
                                        <td class="text-right" width="25%">Principal / Registrar</td>
                                        <td width="23%" style="border-bottom:1px solid #000;"></td>
                                        <td width="5%">Date</td>
                                        <td width="9%" style="border-bottom:1px solid #000;"></td>
                                        <td width="19%"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    @endforeach

</body>
</html>