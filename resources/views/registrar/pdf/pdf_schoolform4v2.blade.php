
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>

    <style>
        @page{
            margin: 35px 40px;;
            size: 13in 8.5in;
        }
       html{
            font-family: Arial, Helvetica, sans-serif;
    
        }
        table {
            font-size: 8px !important;
            /* border: 1px solid black; */
            /* table-layout: fixed; */
            width: 100% !important;
            /* border-top:hidden !important; */
            page-break-inside: always;
            border-collapse: collapse !important;
        }
        /* table .t-lg{
            width: 250px !important;
        }
        table .t-md{
            width: 150px !important;
        }

        table .t-sm{
            width: 100px !important;
        } */

        table tr td{
            vertical-align: middle !important;
            /* font-size: 8px !important; */
            /* border:solid black 1px; */
            /* padding: 4px !important; */

        }

        table th{
            /* font-size: 8px !important; */
            /* border:solid black 1px; */
            /* padding:4px !important; */
        }



        /* table{
            border-collapse: collapse
        } */

        /* td{
            border-left: hidden !important;
            border-top: hidden !important;
        } */

        th{
       
            /* border-left: hidden !important; */
            /* border-top: hidden !important; */
        }
        

        .text-center {
            text-align: center!important;
        }      
        .align-middle {
            vertical-align: middle!important;
        } 
        

        .text-right {
            text-align: right!important;
        }
        .text-left {
            text-align: left!important;
        }

        .border-0 {
            border: none !important;
        }

        .border-bottom {
            border-bottom: 1px solid black!important;
        }
        .border-left {
            border-left: 1px solid black!important;
        }
        .border-top {
            border-top: 1px solid black!important;
        }

        .font-italic {
            font-style: italic!important;
        }
        img {
            width:50%;
            display: block;
        }

        footer {
                position: fixed; 
                bottom: -30px; 
                height: 50px; 
                text-align: center;
                line-height: 35px;
                font-size:8px;
            }
        
      .data_value{
            text-align:center !important;
      }
      th, td {
          padding: 1px !important;
      }
      tr{
        page-break-inside: always !important;
      }
       #table-footer-1 th,#table-footer-1 td{
        
        padding: 5px !important;
        vertical-align: middle;
       }
    </style>

   

    
   
</head>
@php

$signatories = DB::table('signatory')
    ->where('form','form4')
    ->where('syid', Session::get('schoolYear')->id)
    ->where('deleted','0')
    ->get();

    $signatory_name = '';
if(count($signatories) == 0)
{
    $signatory_name = DB::table('schoolinfo')->first()->authorized;
}else{
    
    $signatory_name = $signatories[0]->name;
}


$signatoriesv2 = DB::table('signatory')
        ->where('form','form4')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->get();


$odd = array();
$even = array();
foreach (collect($signatoriesv2)->toArray() as $k => $v) {
    if ($k % 2 == 0) {
        $even[] = $v;
    }
    else {
        $odd[] = $v;
    }
}
@endphp
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 34;
            $y = 590;
            $text = "Page {PAGE_NUM} of {PAGE_COUNT} pages";
            $font = null;
            $size = 7;
            $color = array(0,0,0);
            $word_space = 0.0;  //  default
            $char_space = 0.0;  //  default
            $angle = 0.0;   //  default
            $pdf->page_text($x, $y, $text, $font, $size, $color);
        }
    </script>
        <table  class="border-0 table">
            <tr class="border-0">
                
                <td style="padding:0 !important; margin:0  !important;"  rowspan="4" style="width: 15%" rowspan="4" colspan="4" class="text-center align-middle border-0" >
                    <img  @if (str_contains($schoolinfo[0]->picurl, '?')) src="{{base_path()}}/public/{{substr($schoolinfo[0]->picurl, 0, strpos($schoolinfo[0]->picurl, "?"))}}" @else src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" @endif alt="school" style="width: 70px;">
                </td>
                <td  colspan="33"  rowspan="4" class="text-center border-0" style="font-size:20px !important; font-weight: bold;">School Form 4 (SF4) Monthly Learner's Movement and Attendance<br><span class="text-center border-0 font-italic" style="font-size:11px !important; padding:0 !important">(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</span></td>
                <td style="padding:0 !important; margin:0  !important; text-align: right;"  rowspan="4" style="width: 15%" rowspan="4" colspan="4" class="text-center align-middle border-0" >
                    <img  src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" style="width: 70px;">
                </td>
            </tr>
{{--            
           <tr>
                <td colspan="2" class="border-0 text-right">School ID</td>
                <td colspan="4" class="border-bottom border-left border-top">{{$schoolinfo[0]->schoolid}}</td>
                <td colspan="2" class="border-0 text-right">Region</td>
                <td colspan="3" class="border-bottom border-left border-top">{{$schoolinfo[0]->regDesc}}</td>
                <td colspan="2" class="border-0 text-right">Division</td>
                <td colspan="7" class="border-bottom border-left border-top">{{$schoolinfo[0]->citymunDesc}}</td>
                <td colspan="2" class="border-0 text-right">District</td>
                <td colspan="8" class="border-bottom border-left border-top">{{$schoolinfo[0]->district}}</td>
                <td colspan="7" class="border-0"></td>
            </tr>
              
            <tr>
                <td  class="border-0" colspan="41" style="padding:0 !important">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 5%" colspan="2" class="border-0 "></td>
                <td style="width: 8%" class="text-right border-0" colspan="2">School Name</td>
                <td colspan="15" class="border-bottom border-left border-top">{{$schoolinfo[0]->schoolname}}</td>
                <td colspan="7" class="border-0 text-right">School Year</td>
                <td colspan="5" class="border-bottom border-left border-top">{{Session::get('schoolYear')->sydesc}}</td>
                <td colspan="5" class="border-0 text-right">Report for the Month of</td>
                <td colspan="5" class="border-bottom border-left border-top">{{\Carbon\Carbon::create('0000',$month)->isoFormat('MMMM')}}</td>
            </tr> --}}
        </table>
        
        <table style="width: 100%; border: none;  page-break-inside: always !important; border-collapse: collapse; font-size: 11px !important;">
            <thead>
                <tr style="font-size: 11px;">
                    <th style="width: 20%; text-align: right; border: none; border-right: 1px solid black;">School ID</th>
                    <th style="width: 13%; border: 1px solid black !important;">{{$schoolinfo[0]->schoolid}}</th>
                    <th style="width: 5%; text-align: right; border: none; border-right: 1px solid black; border-left: 1px solid black !important;">Region</th>
                    <th style="width: 13%; border: 1px solid black !important; ">{{DB::table('schoolinfo')->first()->regiontext ?? $schoolinfo[0]->regDesc}}</th>
                    <th style="width: 8%; text-align: right; border: none; border-left: 1px solid black !important; border-right: 1px solid black;">Division</th>
                    <th style="width: 21%; border: 1px solid black !important;"  colspan="2">{{DB::table('schoolinfo')->first()->divisiontext ?? $schoolinfo[0]->citymunDesc}}</th>
                    <th style="width: 10%; text-align: right; border: none; border-left: 1px solid black !important; border-right: 1px solid black;">District</th>
                    <th style="width: 20%; border: 1px solid black !important;">{{DB::table('schoolinfo')->first()->districttext ?? $schoolinfo[0]->district}}</th>
                </tr>
            </thead>
            <tr style="font-size: 11px;">
                <th style="text-align: right; border: none; border-right: 1px solid black;">School Name</th>
                <th colspan="3" style="border: 1px solid black !important;">{{$schoolinfo[0]->schoolname}}</th>
                <th style="text-align: right; border: none; border-left: 1px solid black !important; border-right: 1px solid black !important;">School Year</th>
                <th style="border: 1px solid black !important;">{{DB::table('sy')->where('id', $syid)->first()->sydesc}}</th>
                <th style="text-align: right; border: none; border-right: 1px solid black;" colspan="2">Report for the Month of</th>
                <th style="border: 1px solid black !important;">{{\Carbon\Carbon::create('0000',$month)->isoFormat('MMMM')}}</th>
            </tr>
        </table>
<br/>
        <table style="width: 100%; border-collapse: collapse" border="1" >
            <thead>
                <tr class="text-center ">
                    <td style="width:5% !important;" class="t-sm align-middle text-center" rowspan="3">GRADE / YEAR LEVEL</td> 
                    <td style=" width:8% !important; " class="align-middle" rowspan="3">SECTION</td> 
                    <td style="width:10%  !important; " class="align-middle" rowspan="3" >NAME OF ADVISER</td> 
                   
                    <td style="width:10% !important; " class="align-middle" rowspan="2" colspan="3">REGISTERED<br>LEARNERS<br>(As of End of the Month)</td>
        
                    <th style="width:13% !important; " colspan="6">ATTENDANCE</th>
                    <th style="width:18% !important; " colspan="9">No Longer Paricipating in Learning Activiliec</th>
                    <th style="width:18% !important; " colspan="9">TRANSFERRED OUT</th>
                    <th style="width:18% !important; " colspan="9">TRANSFERRED IN</th>
                </tr>
                <tr class="text-center">
                    <td class="align-middle"  colspan="3">Daily Average</td>
                    <td class="align-middle"  colspan="3">Percentage for the Month</td>
        
                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                    <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                        </td>
        
                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                    <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                        </td>
        
                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                    <td class="align-middle" style="" colspan="3">(A+B) Cumulative as of End of the Month
                        </td>
        
                </tr>
                <tr class="text-center">
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
        
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
        
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
        
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
        
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td>T</td>
                    <td>M</td>
                    <td>F</td>
                    <td style="">T</td>
                </tr>
                
            </thead>
            <tbody>
                 @foreach($data as $item)
                  <tr class="data_value">
                        <td>{{$item->levelname}}</td>
                        <td>{{$item->sectionname}}</td>
                        <td  class="text-center">
                              @if($item->lastname != null)
                                  {{$item->lastname}} , {{$item->firstname}}
                              @else
                                  NO ADVISER
                              @endif
                        </td>
                        <td>{{$item->registered->male}}</td>
                        <td>{{$item->registered->female}}</td>
                        <td>{{$item->registered->male + $item->registered->female}}</td>
                       
                        <td>{{$item->attendance->male}}</td>
                        <td>{{$item->attendance->female}}</td>
                        <td>{{$item->attendance->male + $item->attendance->female}}</td>
                        <td>{{$item->attendance->ptm_m}}</td>
                        <td>{{$item->attendance->ptm_f}}</td>
                        <td>{{$item->attendance->ptm_t}}</td>

                        <td>{{$item->dropped_out_a->male}}</td>
                        <td>{{$item->dropped_out_a->female}}</td>
                        <td>{{$item->dropped_out_a->male + $item->dropped_out_a->female}}</td>
                        <td>{{$item->dropped_out_b->male}}</td>
                        <td>{{$item->dropped_out_b->female}}</td>
                        <td>{{$item->dropped_out_b->male + $item->dropped_out_a->female}}</td>

                        <td>{{$item->dropped_out_a->male + $item->dropped_out_b->male}}</td>
                        <td>{{$item->dropped_out_a->female + $item->dropped_out_b->female}}</td>
                        <td>{{$item->dropped_out_a->male + $item->dropped_out_b->male + $item->dropped_out_a->female + $item->dropped_out_b->female}}</td>

                        <td>{{$item->transferred_out_a->male}}</td>
                        <td>{{$item->transferred_out_a->female}}</td>
                        <td>{{$item->transferred_out_a->male + $item->transferred_out_b->female}}</td>
                        <td>{{$item->transferred_out_b->male}}</td>
                        <td>{{$item->transferred_out_b->female}}</td>
                        <td>{{$item->transferred_out_b->male + $item->transferred_out_b->female}}</td>

                        <td>{{$item->transferred_out_a->male + $item->transferred_out_b->male}}</td>
                        <td>{{$item->transferred_out_a->female + $item->transferred_out_b->female}}</td>
                        <td>{{$item->transferred_out_a->male + $item->transferred_out_b->male + $item->transferred_out_a->female + $item->transferred_out_b->female}}</td>
                  
                        <td>{{$item->transferred_in_a->male}}</td>
                        <td>{{$item->transferred_in_a->female}}</td>
                        <td>{{$item->transferred_in_a->male + $item->transferred_in_a->female}}</td>
                        <td>{{$item->transferred_in_b->male}}</td>
                        <td>{{$item->transferred_in_b->female}}</td>
                        <td>{{$item->transferred_in_b->male + $item->transferred_in_b->female}}</td>

                        <td>{{$item->transferred_in_a->male + $item->transferred_in_b->male}}</td>
                        <td>{{$item->transferred_in_a->female + $item->transferred_in_b->female}}</td>
                        <td>{{$item->transferred_in_a->male + $item->transferred_in_b->male + $item->transferred_in_a->female + $item->transferred_in_b->female}}</td>
                  </tr>
                 @endforeach


                <tr>
                    <td colspan="3">ELEMENTARY/SECONDARY:</td>
                    <td  colspan="36" style="border-right:hidden"></td>
                </tr>
                  @for($x=0;$x<=8;$x++)
                      <tr  style="text-align: center;">
                              @if($x>0 && $x<7) <td colspan="3"> GRADE{{$x}} / GRADE {{$x+6}} </td>
                              @elseif($x==7) <td colspan="3">TOTAL FOR  NON-GRADED</td>
                              @elseif($x==8) <td colspan="3">TOTAL</td>
                              @else <td colspan="3">KINDER</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('registered.male')}} / {{collect($data)->where('sortid',$x+6+3)->sum('registered.male')}}</td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('registered.male')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('registered.male')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('registered.female')}} / {{collect($data)->where('sortid',$x+6+3)->sum('registered.female')}} </td>
                              @elseif($x==7)<td></td>
                              @elseif($x==8)<td>{{collect($data)->sum('registered.female')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('registered.female')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('registered.male') + collect($data)->where('sortid',$x+3)->sum('registered.female')}} / {{collect($data)->where('sortid',$x+6+3)->sum('registered.male') + collect($data)->where('sortid',$x+6+3)->sum('registered.female') }} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('registered.total')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('registered.female') + collect($data)->where('acadprogid',2)->sum('registered.male')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.male')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.male')}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('attendance.male')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.male')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.female')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.female')}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('attendance.female')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.female')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.male') + collect($data)->where('sortid',$x+3)->sum('attendance.female')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.male') + collect($data)->where('sortid',$x+6+3)->sum('attendance.female') }} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('attendance.total')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.female') + collect($data)->where('acadprogid',2)->sum('attendance.male')}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.ptm_m')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.ptm_m')}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum('attendance.ptm_m')}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.ptm_m')}}</td>
                              @endif

                            @if($x>0 && $x<7) 
                                <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.ptm_f')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.ptm_f')}} </td>
                            @elseif($x==7) <td></td>
                            @elseif($x==8) <td>{{collect($data)->sum('attendance.ptm_f')}}</td>
                            @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.ptm_f')}}</td>
                            @endif

                            @if($x>0 && $x<7) 
                                <td>{{collect($data)->where('sortid',$x+3)->sum('attendance.ptm_t')}} / {{collect($data)->where('sortid',$x+6+3)->sum('attendance.ptm_t')}} </td>
                            @elseif($x==7) <td></td>
                            @elseif($x==8) <td>{{collect($data)->sum('attendance.ptm_t')}}</td>
                            @else <td>{{collect($data)->where('acadprogid',2)->sum('attendance.ptm_t')}}</td>
                            @endif

                              @php
                                    $string_male = 'dropped_out_a.male';
                                    $string_female = 'dropped_out_a.female';
                                    $string_total = 'dropped_out_a.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male = 'dropped_out_b.male';
                                    $string_female = 'dropped_out_b.female';
                                    $string_total = 'dropped_out_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male_a = 'dropped_out_a.male';
                                    $string_female_a = 'dropped_out_a.female';
                                    $string_total_a = 'dropped_out_a.total';

                                    $string_male_b = 'dropped_out_b.male';
                                    $string_female_b = 'dropped_out_b.female';
                                    $string_total_b = 'dropped_out_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male_a) + collect($data)->where('sortid',$x+3)->sum($string_male_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male_a) + collect($data)->where('sortid',$x+6+3)->sum($string_male_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male_a) + collect($data)->sum($string_male_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male_a) + collect($data)->where('acadprogid',2)->sum($string_male_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female_a) + collect($data)->where('sortid',$x+3)->sum($string_female_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female_a) + collect($data)->where('sortid',$x+6+3)->sum($string_female_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female_a) + collect($data)->sum($string_female_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female_a) + collect($data)->where('acadprogid',2)->sum($string_female_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total_a) + collect($data)->where('sortid',$x+3)->sum($string_total_b) }} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total_a) + collect($data)->where('sortid',$x+6+3)->sum($string_total_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total_a) + collect($data)->sum($string_total_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total_a) + collect($data)->where('acadprogid',2)->sum($string_total_b)}}</td>
                              @endif

                              //transferrd_out

                              @php
                                    $string_male = 'transferred_out_a.male';
                                    $string_female = 'transferred_out_a.female';
                                    $string_total = 'transferred_out_a.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male = 'transferred_out_b.male';
                                    $string_female = 'transferred_out_b.female';
                                    $string_total = 'transferred_out_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male_a = 'transferred_out_a.male';
                                    $string_female_a = 'transferred_out_a.female';
                                    $string_total_a = 'transferred_out_a.total';

                                    $string_male_b = 'transferred_out_b.male';
                                    $string_female_b = 'transferred_out_b.female';
                                    $string_total_b = 'transferred_out_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male_a) + collect($data)->where('sortid',$x+3)->sum($string_male_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male_a) + collect($data)->where('sortid',$x+6+3)->sum($string_male_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male_a) + collect($data)->sum($string_male_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male_a) + collect($data)->where('acadprogid',2)->sum($string_male_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female_a) + collect($data)->where('sortid',$x+3)->sum($string_female_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female_a) + collect($data)->where('sortid',$x+6+3)->sum($string_female_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8)  <td>{{collect($data)->sum($string_female_a) + collect($data)->sum($string_female_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female_a) + collect($data)->where('acadprogid',2)->sum($string_female_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total_a) + collect($data)->where('sortid',$x+3)->sum($string_total_b) }} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total_a) + collect($data)->where('sortid',$x+6+3)->sum($string_total_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total_a) + collect($data)->sum($string_total_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total_a) + collect($data)->where('acadprogid',2)->sum($string_total_b)}}</td>
                              @endif

                              @php
                                    $string_male = 'transferred_in_a.male';
                                    $string_female = 'transferred_in_a.female';
                                    $string_total = 'transferred_in_a.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male = 'transferred_in_b.male';
                                    $string_female = 'transferred_in_b.female';
                                    $string_total = 'transferred_in_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total)}}</td>
                              @endif

                              @php
                                    $string_male_a = 'transferred_in_a.male';
                                    $string_female_a = 'transferred_in_a.female';
                                    $string_total_a = 'transferred_in_a.total';

                                    $string_male_b = 'transferred_in_b.male';
                                    $string_female_b = 'transferred_in_b.female';
                                    $string_total_b = 'transferred_in_b.total';
                              @endphp
                              
                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_male_a) + collect($data)->where('sortid',$x+3)->sum($string_male_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_male_a) + collect($data)->where('sortid',$x+6+3)->sum($string_male_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_male_a) + collect($data)->sum($string_male_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_male_a) + collect($data)->where('acadprogid',2)->sum($string_male_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_female_a) + collect($data)->where('sortid',$x+3)->sum($string_female_b)}} / {{collect($data)->where('sortid',$x+6+3)->sum($string_female_a) + collect($data)->where('sortid',$x+6+3)->sum($string_female_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_female_a) + collect($data)->sum($string_female_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_female_a) + collect($data)->where('acadprogid',2)->sum($string_female_b)}}</td>
                              @endif

                              @if($x>0 && $x<7) 
                                    <td>{{collect($data)->where('sortid',$x+3)->sum($string_total_a) + collect($data)->where('sortid',$x+3)->sum($string_total_b) }} / {{collect($data)->where('sortid',$x+6+3)->sum($string_total_a) + collect($data)->where('sortid',$x+6+3)->sum($string_total_b)}} </td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td>{{collect($data)->sum($string_total_a) + collect($data)->sum($string_total_b)}}</td>
                              @else <td>{{collect($data)->where('acadprogid',2)->sum($string_total_a) + collect($data)->where('acadprogid',2)->sum($string_total_b)}}</td>
                              @endif
                      </tr>
                  @endfor
            </tbody>
        </table>
        <div style="font-size: 11px; margin: 10px 0px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Mortality (Death)</div>
        {{-- <br/> --}}

        <table style="width: 100%;!important;">
            <tr>
                <th style="width: 50%;padding-left: 10px; none; vertical-align: top;">
                    <table id="table-footer-1" style="width: 100%; margin-left: 30px; border-collapse: collapse;" border='1'>
                        <tr>
                            <th style="width: 25%; vertical-align: middle; "><div>Previous Month/s</div><br/></th>
                            <th>&nbsp;</th>
                            <th style="width: 25%; vertical-align: middle;"><div>For the Month</div><br/></th>
                            <th>&nbsp;</th>
                            <th style="width: 25%; vertical-align: middle;"><div>Cumulative as of End of Month</div><br/></th>
                            <th>&nbsp;</th>
                        </tr>
                    </table>
                </th>
                <th style="width: 5%; border: none;"></th>
                <th style="width: 20%; border: none; vertical-align: top;">
                    @if(count($even)>0)                    
                        <table style="width: 100%;">
                            @foreach($even as $eacheven)
                                <tr>
                                    <td>{{$eacheven->title}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid black; text-align: center;"><br/>{{$eacheven->name}}</td>
                                </tr>
                                <tr>
                                    <td style=" text-align: center;">{{$eacheven->description}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                    <div style="text-align: left;">Prepared and Submitted by:</div>
                    <br/>
                    <div style="text-align: center;">{{$signatory_name}}</div>
                    @endif
                </th>
                <th style="width: 5%; border: none;"></th>
                <th style="width: 20%; border: none; vertical-align: top;">
                    @if(count($odd)>0)                    
                        <table style="width: 100%;">
                            @foreach($odd as $eachodd)
                                <tr>
                                    <td>{{$eachodd->title}}</td>
                                </tr>
                                <tr>
                                    <td style="border-bottom: 1px solid black; text-align: center;"><br/>{{$eachodd->name}}</td>
                                </tr>
                                <tr>
                                    <td style=" text-align: center;">{{$eachodd->description}}</td>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        @if(count($signatoriesv2)==0)
                        <table style="width: 100%">
                            <tr>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="border-bottom: 1px solid black;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">Generated thru LIS</td>
                            </tr>
                        </table>  
                        @endif
                    @endif
                </th>
            </tr>
            {{-- <tr>
                <th style="text-align: center; border: none;">                  
                    &nbsp;
                </th>
                <th style="text-align: center; border: none;">
                    <table style="width: 100%">
                        <tr>
                            <td style="border-bottom: 1px solid black;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="text-align: center;"><sup>Generated thru LIS</sup></td>
                        </tr>
                    </table>                    
                </th>
            </tr> --}}
        </table>
        {{-- <table class="table border-0" style="margin-top:5% !important">
            <thead>
                <tr>
                    <td width="70%" class="border-0">GUIDELINES</td>
                    <td width="30%" class="border-0"><stronbg>Prepared and Submitted by:</stronbg></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border-0">1. This form shall be accomplished every end of the month using the summary box of SF2 submitted by the teachers/advisers to update figures for the month. </td>
                    <td class="border-0"></td>
                </tr>
                <tr>
                    <td class="border-0">2. Furnish the Division Office with a copy a week after June 30, October 30 & March 31</td>
                    <td class="border-0 text-center">__________________________________________</td>
                </tr>
                <tr>
                    <td class="text-center border-0" ></td>
                    <td class="text-center border-0" >(Signature of School Head over Printed Name)</td>
                </tr>
            </tbody>
        </table> --}}
    </body>
</html>
        