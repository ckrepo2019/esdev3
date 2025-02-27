
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>

    <style>
       html{
            font-family: Arial, Helvetica, sans-serif;
    
        }
        table {
            font-size: 8px !important;
            border: 1px solid black;
            table-layout: fixed;
            width: 1250px !important;
            border-top:hidden !important;
        }
        table .t-lg{
            width: 250px !important;
        }
        table .t-md{
            width: 150px !important;
        }

        table .t-sm{
            width: 100px !important;
        }

        table tr td{
            vertical-align: middle !important;
            font-size: 8px !important;
            border:solid black 1px;
            padding: 4px !important;

        }

        table th{
            font-size: 8px !important;
            border:solid black 1px;
            padding:4px !important;
        }



        table{
            border-spacing: 0
        }

        td{
            border-left: hidden !important;
            border-top: hidden !important;
        }

        th{
       
            border-left: hidden !important;
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
       
    </style>

   

    
   
</head>
<body>
    <script type="text/php">
        if (isset($pdf)) {
            $x = 34;
            $y = 580;
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
                
                <td style="padding:0 !important; margin:0  !important;" style="width: 13%" rowspan="4" colspan="4" class="text-center align-middle border-0" >
                    <img  src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" >
                </td>
                <td  colspan="37" class="text-center border-0" style="font-size:20px !important;">School Form 4 (SF4) Monthly Learner's Movement and Attendance<br><span class="text-center border-0 font-italic" style="font-size:11px !important; padding:0 !important">(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</span></td>
            </tr>
           
            <tr>
                <td colspan="37" class="border-0 text-right" >&nbsp;</td>
            </tr>
             
            <tr>
                <td colspan="37" class="border-0 text-right" >&nbsp;</td>
            </tr>
           
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
            </tr>
        </table>
        

        <table class="mb-0 table-bordered table" style="min-width:300px;  border-top:solid 1px black; " >
            <thead>
                <tr class="text-center ">
                    <td style="width:5% !important; border-top:hidden" class="t-sm align-middle text-center border-top" rowspan="3">GRADE / YEAR LEVEL</td> 
                    <td style=" width:7% !important; " class="align-middle border-top" rowspan="3">SECTION</td> 
                    <td style="width:10%  !important; " class="t-md align-middle border-top" rowspan="3" >NAME OF ADVISER</td> 
                   
                    <td style="width:120px !important; " class="t-md align-middle border-top" rowspan="2" colspan="3">REGISTERED<br>LEARNERS<br>(As of End of the Month)</td>
        
                    <th style="width:200px !important; " colspan="6">ATTENDANCE</th>
                    <th style="width:320px !important; " colspan="9">DROPPED OUT</th>
                    <th style="width:315px !important; " colspan="9">TRANSFERRED OUT</th>
                    <th style="width:315px !important; border-right:hidden" colspan="9">TRANSFERRED IN</th>
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
                    <td class="align-middle" style="border-right:hidden" colspan="3">(A+B) Cumulative as of End of the Month
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
                    <td style="border-right:hidden">T</td>
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
                                  NO ADVICER
                              @endif
                        </td>
                        <td>{{$item->registered->male}}</td>
                        <td>{{$item->registered->female}}</td>
                        <td>{{$item->registered->male + $item->registered->female}}</td>
                       
                        <td>{{$item->attendance->male}}</td>
                        <td>{{$item->attendance->female}}</td>
                        <td>{{$item->attendance->male + $item->attendance->female}}</td>
                        <td></td>
                        <td></td>
                        <td></td>

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
                      <tr class="data_value">
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

                              @if($x>0 && $x<7)<td></td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td></td>
                              @else <td></td>
                              @endif

                              @if($x>0 && $x<7)<td></td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td></td>
                              @else <td></td>
                              @endif

                              @if($x>0 && $x<7)<td></td>
                              @elseif($x==7) <td></td>
                              @elseif($x==8) <td></td>
                              @else <td></td>
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

                              //transferred_in

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
        <table class="table border-0" style="margin-top:5% !important">
            <thead>
                <tr>
                    <td width="70%" class="border-0">GUIDELINES</td>
                    <td width="30%" class="border-0"><strong>Prepared and Submitted by:</strong></td>
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
        </table>
    </body>
  
    
 
</html>
        