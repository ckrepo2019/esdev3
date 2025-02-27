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
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

            
        /* .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
        } */
        .p-0 {
            padding-top: 0px !important;
            padding-bottom: 0px !important;
        }
        .mt-0 {
            margin-top: 0px !important;
            margin-bottom: 0px !important;
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
        body { 
            /* margin:0px 10px; */
            /* font-family: Verdana, Geneva, Tahoma, sans-serif !important; */
            font-family: Arial, Helvetica, sans-serif;
            
        }
        
            .check_mark {
                /* font-family: ZapfDingbats, sans-serif; */
            }
        @page { size: 11in 8.5in; margin: 20px 20px;  }
        
        #watermark1 {
        opacity: 1;
                position: absolute;
                top: 20%;
                left: 30%;
                opacity: .5;
                /* transform-origin: 10 10; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    50cm;
                height:   50cm; */

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    </style>
</head>
<body>  
    <table width="100%" style="table-layout: fixed;">
        <tr>
        <td width="50%" class="p-0" valign="top" style="padding-right: 15px!important;">
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td class="text-left">SHJMS FORM 138</td> 
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-right">
                            <div style="padding-top: 5px;"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px" height="55px"></div>
                        </td>
                        <td width="60%" class="text-center">
                            <div>Republic of the Philippines</div>
                            <div>DepEd Region 10</div>
                            <div>Division of City Schools, Cagayan de Oro City</div>
                            <div style="padding-top: 4px; font-size: 10px; color: #FF0000;"><b>SACRED HEART OF JESUS MONTESSORI SCHOOL</b></div>
                            <div>J.R. Borja Extension, Gusa, Cagayan de Oro City</div>
                            <div style="padding-top: 5px;"><b>Junior High School Report Card</b></div>
                        </td>
                        <td width="20%"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td width="20%" class="text-left">Name:</td>
                        <td width="80%" class="text-left"><u>{{$student->student}}</u></td>
                    </tr>
                    <tr>
                        <td width="20%" class="text-left">LRN:</td>
                        <td width="80%" class="text-left"><u>{{$student->lrn}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">Year & Section:</td>
                        <td width="52%" class=""><u>{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</u></td>
                        <td width="6%" class="text-left">Sex:</td>
                        <td width="22%" class=""><u>{{$student->gender}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">School Year:</td>
                        <td width="52%" class=""><u>{{$schoolyear->sydesc}}</u></td>
                        <td width="6%" class="text-left">Age:</td>
                        <td width="22%" class=""><u>{{$student->age}}</u></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px;">
                    <tr>
                        <td width="20%" class="text-left">Class Adviser:</td>
                        <td width="80%" class="text-left"><u>{{$adviser}}</u></td>
                    </tr>
                </table>
                <table width="100%" class="table table-sm table-bordered grades mb-0" style="table-layout: fixed; margin-top: 10px;">
                    <thead>
                        <tr>
                            <td rowspan="2" class="p-0 align-middle text-center" width="30%" style="font-size: 12px!important;"><b>SUBJECTS</b></td>
                            <td colspan="4"  class="p-0 text-center align-middle" width="48%" style="font-size: 12px!important;"><b>RATINGS</b></td>
                            <td rowspan="2" class="p-0 text-center align-middle" width="10%" style="font-size: 12px!important;"><b>Final <br> Rating</b></td>
                            <td rowspan="2" class="p-0 text-center align-middle" width="12%" style="font-size: 12px!important;"><b>Action <br> Taken</b></span></td>
                        </tr>
                        <tr>
                            <td class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>1</b></td>
                            <td class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>2</b></td>
                            <td class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>3</b></td>
                            <td class="p-0 text-center align-middle" style="font-size: 12px!important;"><b>4</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($studgrades as $item)
                            <tr>
                                <td class="p-0" style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 10px!important;">&nbsp;{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                <td class="p-0 text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                <td class="p-0 text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                <td class="p-0 text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                <td class="p-0 text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                <td class="p-0 text-center align-middle">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                                <td class="p-0 text-center align-middle" style="font-size: 10px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="p-0 text-left" style="font-size: 12px!important;">&nbsp;<b>General Average</b></td>
                            <td class="text-center p-0"><b></b></td>
                            <td class="text-center p-0"><b></b></td>
                            <td class="text-center p-0"><b></b></td>
                            <td class="text-center p-0"><b></b></td>
                            <td class="text-center p-0 {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                            <td class="text-center align-middle p-0" ><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                        </tr>
                        <tr>
                            <td class="p-0 text-left" style="font-size: 12px!important;">&nbsp;<b>Grading Plan Used</b></td>
                            <td class="p-0 text-center" colspan="6"><b>AVERAGING</b></td>
                        </tr>
                    </tbody>
                </table>
                
                @php
                    $width = count($attendance_setup) != 0? 63 / count($attendance_setup) : 0;
                @endphp
                <table width="100%" class="table table-bordered table-sm grades mb-0" style="table-layout: fixed;">
                    <tr>
                        <td class="p-0" width="30%" style="border: 1px solid #000; text-align: center;"></td>
                        @foreach ($attendance_setup as $item)
                            <td class="text-center align-middle p-0" width="{{$width}}%"><span style="font-size: 12px!important">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                        @endforeach
                        <td class="text-center p-0" width="7%" style="vertical-align: middle; font-size: 12px!important;"><span><b>Total</b></span></td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of School Days</td>
                        @foreach ($attendance_setup as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->days : '' }}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Present</td>
                        @foreach ($attendance_setup as $item)
                            <td class="p-0 text-center align-middle">{{$item->days != 0 ? $item->present : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle" >{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                    </tr>
                    <tr class="table-bordered">
                        <td class="p-0">&nbsp;No. of Days Tardy</td>
                        @foreach ($attendance_setup as $item)
                            <td class="p-0 text-center align-middle" >{{$item->days != 0 ? $item->absent : ''}}</td>
                        @endforeach
                        <td class="p-0 text-center align-middle" >{{collect($attendance_setup)->sum('absent')}}</td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 30px;">
                    <tr>
                        <td width="30%" class="p-0 text-left">&nbsp;Eligible admission to:</td>
                        <td width="25%" class="p-0 text-left border-bottom">&nbsp;</td>
                        <td width="45%" class="p-0 text-left"></td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 35px;">
                    <tr>
                        <td width="30%" class="p-0 text-center"><b><u>Cathy R. Balaba</u></b></td>
                        <td width="40%" class="p-0 text-left">&nbsp;</td>
                        <td width="30%" class="p-0 text-center"><b><u>MS. MERLINA BRITOS</u></b></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-center">Class Adviser</td>
                        <td class="p-0 text-left">&nbsp;</td>
                        <td class="p-0 text-center">Academic Head</td>
                    </tr>
                </table>
                <table class="table" width="100%" style="font-size: 12px; margin-top: 5px;">
                    <tr>
                        <td width="30%" class="p-0 text-center"></td>
                        <td width="40%" class="p-0 text-center"><b><u>MS. ANN B. NERI</u></b></td>
                        <td width="30%" class="p-0 text-center"></td>
                    </tr>
                    <tr>
                        <td class="p-0 text-center"></td>
                        <td class="p-0 text-center">School Director</td>
                        <td class="p-0 text-center"></td>
                    </tr>
                </table>

            </td> 
            <td width="50%" class="" style="padding-left: 15px;" valign="top;">
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 100px;">
                    <tr>
                        <td class="text-center"><b>TO THE PARENT OR GUARDIAN</b></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 10px; margin-top: 5px;">
                    <tr>
                        <td class="text-left">1. Report Card is issued to the parents at the close of every quarter.</td>
                    </tr>
                    <tr>
                        <td class="text-left">2. The parents or guardian is required to sign the card anf return it to the adviser.</td>
                    </tr>
                    <tr>
                        <td class="text-left">3. Parents are encouraged to confer with the teachers concerning their child's performance.</td>
                    </tr>
                    <tr>
                        <td class="text-left">4. Parents are requested to see the adviser and subject teachers if their child is getting 74% and below. <br>
                            &nbsp;&nbsp;&nbsp;&nbsp;A grade of 75% is required for passing any subject and for promotion to the next level.
                        </td>
                    </tr>
                </table>
                <table class="table table-bordered grades mb-0"  width="100%" style="table-layout: fixed;">
                    <tr>
                        <td colspan="7" class="text-center p-0" style="border: 1px solid #000 !important; margin-top: 5px;">
                            <div>
                                <b>PERSONAL GROWTH AND DEVELOPMENT/CONDUCT</b>
                            </div>
                        </td>
                    </tr>
                      <tr>
                          {{-- <td rowspan="2" width="18%" class="text-center align-middle p-0" style="font-size: 12px!important;"><b>Core Values</b></td> --}}
                          <td colspan="3" rowspan="2" class="text-center align-middle  p-0" style="font-size: 12px!important;"><b>Behavior Statements</b></td>
                          <td colspan="4" class="text-center p-0" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 12px!important;"><b>Quarter</b></td>
                      </tr>
                      <tr>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 12px!important;"><b>1</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 12px!important;"><b>2</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 12px!important;"><b>3</b></td>
                          <td width="7%" class="align-middle text-center" style="padding-top: 5px!important; padding-bottom: 5px!important;font-size: 12px!important;"><b>4</b></td>
                      </tr>
                      @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                        @php
                            $count = 0;
                            $counter = 0;
                        @endphp
                        @foreach ($groupitem as $item)
                            @php
                                $counter++;
                            @endphp
                            @if($item->value == 0)
                            @else
                                <tr>
                                    @if($count == 0)
                                            <td width="14%" class="text-left p-0" style="font-size: 12px; vertical-align: top; padding-top: 5px!important; padding-bottom: 5px!important;" rowspan="{{count($groupitem)}}"><b>&nbsp;&nbsp;{{$item->group}}</b></td>
                                            @php
                                                $count = 1;
                                            @endphp
                                    @endif
                                    <td width="8%" class="text-center p-0">
                                        {{$counter}}
                                    </td>
                                    {{-- <td class="align-middle" style="font-size: 10px;"><b>{{$item->group}}</b></td> --}}
                                    <td width="50%"  class="align-middle p-0" style="font-size: 12px;padding-left: 3px!important;">{{$item->description}}</td>
                                    <td class="text-center p-0 align-middle" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                    <td class="text-center align-middle p-0" style="font-size: 8px; padding-top: 5px!important; padding-bottom: 5px!important;">
                                        @foreach ($rv as $key=>$rvitem)
                                            {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                        @endforeach 
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0"><b>Guideline for Rating</b></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 2px;">
                    <tr>
                        <td width="59%" class="text-right p-0">A - Excellent (95-100)</td>
                        <td width="41%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;C. Good (85-89)</td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 2px;">
                    <tr>
                        <td width="56%" class="text-right p-0">B. Very Good (90-94)</td>
                        <td width="44%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;D. Fair (80-84)</td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0"><b>SIGNATURE OF PARENT OR GUARDIAN</b></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td width="22%" class="text-left p-0">First Quarter</td>
                        <td width="78%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td width="22%" class="text-left p-0">Second Quarter</td>
                        <td width="78%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td width="22%" class="text-left p-0">Third Quarter</td>
                        <td width="78%" class="text-left p-0 border-bottom"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td class="text-center p-0"><b>CANCELLATION OF TRANSFER ELIGIBILTY</b></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
                    <tr>
                        <td width="25%" class="text-left p-0">Has been admitted to:</td>
                        <td width="75%" class="text-left p-0 border-bottom"></td>
                    </tr>
                </table>
                <table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 30px;">
                    <tr>
                        <td width="14%" class="text-left p-0"></td>
                        <td width="21%" class="text-left p-0 border-bottom"></td>
                        <td width="32%" class="text-left p-0"></td>
                        <td width="33%" class="text-left p-0 border-bottom"></td>
                    </tr>
                    <tr>
                        <td class="text-left p-0"></td>
                        <td class="text-center p-0">Date</td>
                        <td class="text-left p-0"></td>
                        <td class="text-center p-0">Principal</td>
                    </tr>
                </table>
            </td> 
        </tr>
    </table>
</body>
</html>