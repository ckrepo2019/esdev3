
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>

    <style>
       *{
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
            border: 0!important;
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
        
    #header, #header td{
        font-size: 13px;
        width: 100%;
        table-layout: fixed;
        border:0px !important;
    }
    #header th{
        table-layout: fixed;
        border:0px !important;
        font-size: 12px !important;
    }
       
    div.box{
        border: 1px solid black;
        padding: 3px;
        text-align: center;
        margin-top: 3px;
        text-transform: uppercase;
    }
    
    .cellRight{
        text-align: right;
    }
    /* body{
        padding-top: 5px !important;
        margin-top: 5px !important;
    } */
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

        {{-- <table  class="border-0">
            <tr>
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
                <td colspan="3" class="border-bottom border-left border-top">{{DB::table('schoolinfo')->first()->region}}asdasd</td>
                <td colspan="2" class="border-0 text-right">Division</td>
                <td colspan="7" class="border-bottom border-left border-top">{{DB::table('schoolinfo')->first()->division}}asdasdasd</td>
                <td colspan="2" class="border-0 text-right">District</td>
                <td colspan="8" class="border-bottom border-left border-top">{{DB::table('schoolinfo')->first()->district}}asdasd</td>
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
                <td colspan="5" class="border-bottom border-left border-top">{{\Carbon\Carbon::create('0000',$selectedmonth)->isoFormat('MMMM')}}</td>
            </tr>
        </table> --}}

        <table id="header">
            <tr>
                <th rowspan="2" style="width: 12%;">
                    
				@if($schoolinfo[0]->picurl != null)
                    <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" style="width: 80px !important;">
                @else
                @endif
                    {{-- <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" style="width: 80px !important;"> --}}
                </th>
                <th colspan="11" style="padding-left:20px;padding-right:20px; padding-top: 0px !important;vertical-align: middle;">
                    <h2 style="font-size: 20px !important; padding-top: 0px !important; margin-top: 0px !important;"><center>School Form 4 (SF4) Monthly Learner's Movement and Attendance</center></h2>
                    <small style="font-size: 12px !important;"><em><center>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</center></em></small>
                </th>
                <th colspan="2" rowspan="2" style="text-align: right;">
                    <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px">
                </th>
            </tr>
            <tr>
                <th class="cellRight" width="10%">School ID</th>
                <th style="width: 10%; !important"><div class="box">{{$schoolinfo[0]->schoolid}}</div></th>
                <th class="cellRight" style="width: 5%; !important">Region</th>
                <th><div class="box">{{$schoolinfo[0]->regDesc}}</div></th>
                <th class="cellRight" style="width:5%;">Division</th>
                <th colspan="2">
                    <div class="box">
                        {{$schoolinfo[0]->citymunDesc}}
                    </div>
                </th>
                <th class="cellRight" style="">District</th>
                <th colspan="3">
                    <div class="box">
                        {{$schoolinfo[0]->district}}
                    </div>
                </th>
            </tr>
            <tr >
                <th colspan="2" class="cellRight" style="padding-top: 5px;">School Name</th>
                <th colspan="5" style="padding-top: 5px;"><div class="box">{{$schoolinfo[0]->schoolname}}</div></th>
                <th style="padding-top: 5px;">School Year</th>
                <th class="cellRight" style="padding-top: 5px;"><div class="box">{{Session::get('schoolYear')->sydesc}}</div></th>
                <th class="cellRight" colspan="2" style="padding-top: 5px;">Report for the Month of</th>
                <th colspan="2" style="padding-top: 5px;"><div class="box">{{\Carbon\Carbon::create('0000',$selectedmonth)->isoFormat('MMMM')}}</div></th>
                <th>
                    &nbsp;
                </th>
            </tr>
        </table>
            {{-- <table  class="border-0 tableheader">
                <tr>
                    <td style="padding:0 !important; margin:0  !important;" style="" rowspan="2" colspan="4" class="text-center align-middle border-0" >
                        <img  src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" >
                    </td>
                    <td  colspan="31" class="text-center border-0" style="font-size:20px !important;">School Form 4 (SF4) Monthly Learner's Movement and Attendance<br><span class="text-center border-0 font-italic" style="font-size:11px !important; padding:0 !important">(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</span></td>
                    <td rowspan="2" colspan="6" class="text-center align-middle border-0">
                        <img  src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" >
                    </td>
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
                    <td  colspan="2" class="border-0 "></td>
                    <td class="text-right border-0" colspan="2">School Name</td>
                    <td colspan="14" class="border-bottom border-left border-top">{{$schoolinfo[0]->schoolname}}</td>
                    <td colspan="7" class="border-0 text-right">School Year</td>
                    <td colspan="5" class="border-bottom border-left border-top">{{Session::get('schoolYear')->sydesc}}</td>
                    <td colspan="5" class="border-0 text-right">Report for the Month of</td>
                    <td colspan="3" class="border-bottom border-left border-top">{{\Carbon\Carbon::create('0000',$selectedmonth)->isoFormat('MMMM')}}</td>
                </tr>
            </table> --}}
        <table class="mb-0 table-bordered table" style="min-width:300px; margin-top: 2% !important; border-top:solid 1px black; " >
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

                {{-- @for($x = 0 ; $x <20;$x++) --}}
                    @foreach($sections[0]->data as $section)
                        <tr class="text-center">
                            <td class="text-center">{{$section->levelname}}</td>
                            <td>{{$section->sectionname}}</td>
                            <td  class="text-center">
                                @if($section->lastname != null)
                                    {{$section->lastname}} , {{$section->firstname}}
                                @else
                                    NO ADVISER
                                @endif
                            </td>
                            <td>{{$section->male}}</td>
                            <td>{{$section->female}}</td>
                            <td>{{$section->male + $section->female}}</td>
            
                            <td>{{$section->maleAtt}}</td>
                            <td>{{$section->femaleAtt}}</td>
                            <td>{{$section->maleAtt + $section->femaleAtt}}</td>
            
                            <td>
                                @if($section->male != 0)
                                    {{round(($section->maleAtt / $section->male) * 100)}}%
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if($section->female != 0)
                                    {{round(($section->femaleAtt / $section->female) * 100)}}%
                                @else
                                    0
                                @endif
                            </td>
                            <td>
                                @if($section->female != 0 || $section->male != 0)
                                    {{
                                    round((($section->femaleAtt + $section->maleAtt) / ($section->female +  $section->male)) * 100)
                                    }}%
                                @else
                                    0
                                @endif
            
                            </td>
            
                            <td>{{$section->prevdropoutmale}}</td>
                            <td>{{$section->prevdropoutfemale}}</td>
                            <td>{{$section->prevdropoutmale + $section->prevdropoutfemale}}</td>
                            <td>{{$section->dropoutmale}}</td>
                            <td>{{$section->dropoutfemale}}</td>
                            <td>{{$section->dropoutmale + $section->dropoutfemale}}</td>
                            
                            <td>{{$section->dropoutmale + $section->prevdropoutmale}}</td>
                            <td>{{$section->dropoutfemale + $section->prevdropoutfemale}}</td>
                            <td>{{$section->prevdropoutmale + $section->prevdropoutfemale + $section->dropoutmale + $section->dropoutfemale}}</td>
            
                            <td>{{$section->prevtransoutmale}}</td>
                            <td>{{$section->prevtransoutfemale}}</td>
                            <td>{{$section->prevtransoutmale + $section->prevtransoutfemale}}</td>
                            <td>{{$section->transoutmale}}</td>
                            <td>{{$section->transoutfemale}}</td>
                            <td>{{$section->transoutmale + $section->transoutfemale}}</td>
            
                            <td>{{$section->transoutmale + $section->prevtransoutmale}}</td>
                            <td>{{$section->transoutfemale + $section->prevtransoutfemale}}</td>
                            <td>{{$section->prevtransoutmale + $section->prevtransoutfemale + $section->transoutmale + $section->transoutfemale}}</td>
            
                            <td>{{$section->prevtransinmale}}</td>
                            <td>{{$section->prevtransinfemale}}</td>
                            <td>{{$section->prevtransinmale + $section->prevtransinfemale}}</td>
                            <td>{{$section->transinmale}}</td>
                            <td>{{$section->transinfemale}}</td>
                            <td>{{$section->transinmale + $section->transinfemale}}</td>
            
                            <td>{{$section->transinmale + $section->prevtransinmale}}</td>
                            <td>{{$section->transinfemale + $section->prevtransinfemale}}</td>
                            <td style="border-right:hidden">{{$section->transinmale + $section->transinfemale + $section->prevtransinmale + $section->prevtransinfemale}}</td>
            
                        </tr>
                    @endforeach
                {{-- @endfor --}}


                <tr>
                    <td colspan="3">ELEMENTARY/SECONDARY:</td>
                    <td  colspan="36" style="border-right:hidden"></td>
                </tr>
        
                @for($x=0;$x<=8;$x++)
                    <tr class="text-center">
                        @if($x>0 && $x<7)
                            <td colspan="3">
                                GRADE{{$x}} / GRADE {{$x+6}}
                            </td>
                        @elseif($x==7)
                            <td colspan="3">TOTAL FOR  NON-GRADED
                                </td>
                        @elseif($x==8)
                            <td colspan="3">TOTAL</td>
                        @else
                            <td colspan="3">KINDER</td>
                        @endif
        
        
        
                        <!-- Registered Male -->
        
                        <td class="align-middle" style="font-size: 8px;">
        
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male')}} /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')}}
                            @elseif($x==8)
                                {{collect($sections[0]->data)->sum('male')}}
                            @endif
        
                        </td>
        
                        <!-- Registered Female -->
        
                        <td class="align-middle" style="font-size: 8px;">
        
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}}
                            @elseif($x==8)
                                {{collect($sections[0]->data)->sum('female')}}
                            @else
                                
                            @endif
        
        
                        </td>
        
                        <!-- Registered Total -->
        
                        <td class="align-middle" style="font-size: 8px;">
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')}}
                            @endif
                        </td>
        
                        <!-- Daily Attendance Male -->
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('maleAtt')}}
                            @endif
                        </td>
        
                        <!-- Daily Attendance Female -->
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt')}} 
        
                            @elseif($x==8)
                                {{collect($sections[0]->data)->sum('femaleAtt')}}
                            @endif
                        </td>
        
                         <!-- Daily Attendance Female -->
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt')}}
        
                            @endif
        
                        </td>
        
                        <!-- Percentage Daily Attendance Male -->
        
                        <td>
                            @if($x>0 && $x<7)
                                @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') != 0)
                                    {{round(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('male') * 100)}}%
                                @else
                                    0
                                @endif
                                /
                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') != 0)
                                    {{round(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') * 100)}}%
                                @else
                                    0
                                @endif
        
                            @elseif($x==8)
                                @if($section->male != 0)
                                    {{  (collect($sections[0]->data)->sum('maleAtt') / collect($sections[0]->data)->sum('male'))  * 100 }}%
                                @else
                                    0
                                @endif
                            @endif  
                        </td>
        
                        <!-- Percentage Daily Attendance Female -->
        
                        <td>
                            @if($x>0 && $x<7)
                                @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') != 0)
                                    {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('female') * 100}}%
                                @else
                                    0
                                @endif
                                /
                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') != 0)
                                    {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') * 100}}%
                                @else
                                    0
                                @endif
                            @elseif($x==8)
                                @if($section->female != 0)
                                    {{  (collect($sections[0]->data)->sum('femaleAtt') / collect($sections[0]->data)->sum('female'))  * 100 }}%
                                @else
                                    0
                                @endif
                            @endif
                        </td>
        
                        <!-- Percentage Daily Attendance Total -->
                        <td>
                          
                            @if($x>0 && $x<7)
                            
                                @if( collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+3)->sum('male') > 0)
                                    {{
                                        (
                                            (
                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') +
                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')
                                            )   /
                                            (
                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +
                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('male')
                                            )
        
                                        )   * 100
                                    }}%
                                @else
                                    0
                                @endif
                                /
                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') > 0)
                                    {{
                                        (
                                            (
                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') +
                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')
                                            )   /
                                            (
                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +
                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')
                                            )
        
                                        )   * 100
                                    }}%
                                @else
                                   0
                                @endif
        
        
                            @elseif($x==8)
                                @if( (collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')) > 0)
                                    {{  
                                        (
                                            (   collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt') ) /
                                            (   collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male') ) 
                                        ) * 100 
                                    }}%
                                @else
                                    0
                                @endif
                            @endif
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevdropoutmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevdropoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevdropoutfemale') + collect($sections[0]->data)->sum('prevdropoutmale')}}
                                
                            @endif
        
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('dropoutmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('dropoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('dropoutfemale') + collect($sections[0]->data)->sum('dropoutmale')}}
                                
                            @endif
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevdropoutmale') +
                                    collect($sections[0]->data)->sum('dropoutmale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') }} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevdropoutfemale') +  collect($sections[0]->data)->sum('dropoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                /
                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale') }} 
        
                            @elseif($x==8)
        
                                {{  collect($sections[0]->data)->sum('prevdropoutfemale') + 
                                collect($sections[0]->data)->sum('prevdropoutmale') + 
                                collect($sections[0]->data)->sum('dropoutfemale') +
                                collect($sections[0]->data)->sum('dropoutmale')
                                }} 
                                
                            @endif
                        </td>
        
                        {{-- transout --}}
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransoutmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('prevtransoutmale')}}
                                
                            @endif
        
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transoutmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transoutfemale') + collect($sections[0]->data)->sum('transoutmale')}}
                                
                            @endif
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransoutmale') +
                                    collect($sections[0]->data)->sum('transoutmale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') }} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('transoutfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                /
                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale') }} 
        
                            @elseif($x==8)
        
                                {{  collect($sections[0]->data)->sum('prevtransoutfemale') + 
                                    collect($sections[0]->data)->sum('prevtransoutmale') + 
                                    collect($sections[0]->data)->sum('transoutfemale') +
                                    collect($sections[0]->data)->sum('transoutmale')
                                }}                        
                            @endif
                        </td>
        
                        {{-- transin --}}
        
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransinmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransinfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('prevtransinmale')}}
                                
                            @endif
        
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transinmale')}}
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transinfemale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale') }} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('transinfemale') + collect($sections[0]->data)->sum('transinmale')}}
                                
                            @endif
                        </td>
        
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransinmale') +
                                    collect($sections[0]->data)->sum('transinmale')}}
        
                            @endif
                        </td>
                        <td>
                            @if($x>0 && $x<7)
                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                                /
                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') }} 
        
                            @elseif($x==8)
        
                                {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('transinfemale')}}
        
                            @endif
                        </td>
                        <td style="border-right:hidden">
                            @if($x>0 && $x<7)
                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                /
                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + 
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') +
                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale') }} 
        
                            @elseif($x==8)
        
                            {{  collect($sections[0]->data)->sum('prevtransinfemale') + 
                                collect($sections[0]->data)->sum('prevtransinmale') + 
                                collect($sections[0]->data)->sum('transinfemale') +
                                collect($sections[0]->data)->sum('transinmale')
                            }} 
                            @endif
                        </td>
                    </tr>
                @endfor
            </tbody>
        </table>
        <table class="table border-0">
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
        