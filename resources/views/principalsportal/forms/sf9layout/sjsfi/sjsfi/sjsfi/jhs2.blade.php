<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            width: 100%;
            margin-bottom: 0px;
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

        .td-bordered {
            border-right: 1px solid #00000;
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
        .p-1{
            padding-top: 10px!important;
            padding-bottom: 10px!important;
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
            line-height: 12px;
            height: 50px;
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
            transform-origin: 17 17;
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
        
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 4in 8.5in; margin: 15px 20px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 7px!important; padding-right: 7px!important; font-size: 10px">
                <tr>
                    <td width="30%" class="text-left p-0" style="">DepEd Form 138</td>
                    <td width="42%" class="text-left p-0" style=""></td>
                    <td width="8%" class="text-left p-0" style="">LRN</td>
                    <td width="20%" class="text-left p-0" style="border-bottom: 1px solid #000;">{{$student->lrn}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 25%; text-align: right; vertical-align: top;">
                        <div class="p-0" style="width: 100%;"><img style="padding-top: 4px;" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="40px"></div>
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: middle; margin-top: 10px;">
                        <div class="p-0" style="width: 100%; font-size: 10px;">REPUBLIC OF THE PHILIPPINES</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;">DEPARTMENT OF EDUCATION</div>
                        <div class="p-0" style="width: 100%; font-size: 10px;">REGION X</div>
                    </td>
                    <td style="width: 25%; text-align: center; vertical-align: middle;">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center" style="vertical-align: top;">
                        <!-- <div class="p-0" style="">{{$schoolinfo[0]->schoolname}}</div> -->
                        <div class="p-0" style="font-size: 11px;"><b>SAINT JOSEPH SCHOOL FOUNDATION, INC.</b></div>
                        <div class="p-0" style="font-size: 9px;">Gov. Camins Avenue, Zamboanga City</div>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="vertical-align: top;">
                        <!-- <div class="p-0" style="">{{$schoolinfo[0]->schoolname}}</div> -->
                        <div class="p-0" style="font-size: 11px;"><b>REPORT CARD</b></div>
                        <div class="p-0" style="font-size: 9px;">Junior High School Department</div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 1em; font-size: 10px;">
                <tr>
                    <td width="10%" class="text-left p-0" style="vertical-align: top;">Name:</td>
                    <td width="50%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</td>
                    <td width="9%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Age:</td>
                    <td width="12%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->age}}</td>
                    <td width="9%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Sex:</td>
                    <td width="10%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->gender[0]}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: .5em; font-size: 10px;">
                <tr>
                    <td width="16%" class="text-left p-0" style="vertical-align: top;">Curriculum:</td>
                    <td width="30%" class="text-left p-0" style="border-bottom: 1px solid #000;">K-12</td>
                    <td width="20%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Grade & Sec.</td>
                    <td width="34%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{str_replace('GRADE', '', $student->levelname)}} - {{$student->sectionname}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: .5em; font-size: 10px;">
                <tr>
                    <td width="11%" class="text-left p-0" style="vertical-align: top;">Adviser:</td>
                    <td width="49%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 9px;">{{$adviser}}</td>
                    <td width="22%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">School Year 20</td>
                    <td width="7%" class="text-left p-0" style="vertical-align: top; border-bottom: 1px solid #000;">{{substr($schoolyear->sydesc, 2,2)}}</td>
                    <td width="4%" class="text-left p-0" style="vertical-align: top;">20</td>
                    <td width="7%" class="text-left p-0" style="vertical-align: top; border-bottom: 1px solid #000;">{{substr($schoolyear->sydesc, 7,7)}}</td>
                </tr>
            </table>
            <table width="100%" class="table table-sm table-bordered grades" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <td rowspan="2"  class="align-middle text-center" width="40%" style="font-size: 9px!important;"><b>Learning Areas</b></td>
                        <td colspan="5"  class="text-center align-middle" style="font-size: 9px!important;"><b>QUARTER</b></td>
                        
                        <td rowspan="2"  class="text-center align-middle" width="17%" style="font-size: 9px!important;"><b>Remarks</b></span></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>1</b></td>
                        <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>2</b></td>
                        <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>3</b></td>
                        <td class="text-center align-middle" width="7.5%" style="font-size: 9px!important;"><b>4</b></td>
                        <td class="text-center align-middle" width="13%"  style="font-size: 9px!important;">Final <br> Rating</b></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 9px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle" style="font-size: 9px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-left" style="font-size: 10px!important;">GEN. AVERAGE</td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                        <td class="text-center align-middle" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm table-bordered mb-0" style="table-layout: fixed; margin-top: 5px; font-size: 10px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td class="text-left p-0" width="45%"><b>&nbsp;&nbsp;Descriptors:</b></td>
                    <td class="text-left p-0" width="30%"><b>&nbsp;&nbsp;Grading Scale</b></td>
                    <td class="text-left p-0" width="25%"><b>&nbsp;&nbsp;Remarks</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Outstanding</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">90-100</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Very Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">85-89</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">80-84</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Fairly Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">75-79</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;"><b>Passed</b></td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Did not Meet Expectation</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">Below 75</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;"><b>Failed</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 10px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td class="text-center">
                        <b><i>CERTIFICATE OF TRANSFER</i></b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; font-size: 10px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td width="77%" class="text-right p-0"><b><i>Eligible for transfer and admission to Grade&nbsp;</i></b></td>
                    <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 12px; padding-left: 78px; padding-right: 50px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="border-bottom: 1px solid #000;"><span style="text-transform: uppercase!important; first-line">{{$adviser}}</span></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 10px; padding-top: 2px!important;"><b><i>Teacher</i></b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 12px;">
                <tr>
                    <td width="45%" class="text-center p-0" style=""></td>
                    <td width="55%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 10px;"><b>Sr. Josephine C. Sambrano, OP, MAEd</b></td>
                </tr>
                <tr>
                    <td width="45%" class="text-center p-0" style=""></td>
                    <td width="55%" class="text-center p-0" style="font-size: 10px; padding-top: 2px!important;"><b><i>Principal</i></b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 12px;">
                        <b>TO THE PARENTS OR GUARDIANS</b>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="p-0" style="font-size: 10px; text-align: justify">
                        <b>The school seeks your cooperation so that both you and the teacher may work together for the good of your child. You are invited to visit the school and confer with the subject teacher and or the section adviser</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 12px;">
                        <b>CHARACTER BUILDING ACTIVITIES</b>
                    </td>
                </tr>
            </table>
            <table class="table-sm table table-bordered mb-0 mt-0" width="100%"  style="table-layout: fixed; margin-top: 3px; font-size: 15px;">
                <tr>
                    <td width="50%" class="text-center" style=""><b>Character Traits</b></td>
                    <td width="12.5%" class="text-center" style=""><b>1</b></td>
                    <td width="12.5%" class="text-center" style=""><b>2</b></td>
                    <td width="12.5%" class="text-center" style=""><b>3</b></td>
                    <td width="12.5%" class="text-center" style=""><b>4</b></td>
                </tr>
                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($groupitem as $item)
                        @if($item->value == 0)
                        @else
                            <tr>
                                {{-- @if($count == 0)
                                        <td width="21%" class="align-middle" style="border-right: 1px solid #fff;" rowspan="{{count($groupitem)}}">{{$item->group}}</td>
                                        @php
                                            $count = 1;
                                        @endphp
                                @endif --}}
                                <td class="align-middle" style="font-size: 10px;">{{$item->description}}</td>
                                <td class="text-center align-middle" style="font-size: 10px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 10px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 10px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 10px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </table>
            <!--<table class="table table-sm mb-0 table-bordered" style="table-layout: fixed; margin-top: 3px;">-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-center" style=""><b>Character Traits</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>1</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>2</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>3</b></td>-->
            <!--        <td width="12.5%" class="text-center" style=""><b>4</b></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">1. Honesty</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">2. Courteousness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">3. Responsibility</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">4. Resourcefulness and <br> Creativity</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">5. Fairness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">6. Leadership</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">7. Obedience</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">8. Self-Reliance</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">9. Industry</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left" style="">10. Cleanliness and Orderliness</td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--        <td width="12.5%" class="text-center" style=""></td>-->
            <!--    </tr>-->
            <!--</table>-->
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 11px;">
                        <b>GUIDELINES FOR RATING</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0 table-bordered" style="table-layout: fixed; padding-left: 112px; padding-right: 112px;">
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;">A</td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;">Very Good</td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;">B</td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;">Good</td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;">C</td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;">Fair</td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;">D</td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;">Poor</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="text-left" style="font-size: 10px;">
                        <b>ATTENDANCE RECORD</b>
                    </td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 70 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td style="padding-top: 13px!important;border: 1px solid #000; text-align: center;"></td>
                    @foreach ($attendance_setup as $item)
                        <td class="aside text-center align-middle;" width="{{$width}}%"><span style="font-size: 8px!important;"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                    @endforeach
                    <td class="text-center p-0" width="11%" style="vertical-align: middle; font-size: 8px!important;"><span style="transform-origin: 13 22; transform: rotate(-90deg); top: 0; bottom: 10;"><b>TOTAL</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td width="19%"  style="font-size: 8px!important;"><b>No. of School <br> Days</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 8px!important;"><b>No. of School <br> Days Present</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 8px!important;"><b>No. of Times <br> Tardy</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 10px;">
                        <b>Signature of Parents or Guardian</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="18%" class="text-left p-0" style="font-size: 10px;">First Grading</td>
                    <td width="72%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="22%" class="text-left p-0" style="font-size: 10px;">Second Grading</td>
                    <td width="68%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="19%" class="text-left p-0" style="font-size: 10px;">Third Grading</td>
                    <td width="71%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="21%" class="text-left p-0" style="font-size: 10px;">Fourth Grading</td>
                    <td width="69%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>