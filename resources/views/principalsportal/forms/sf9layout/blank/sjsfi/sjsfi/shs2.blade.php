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
            background-color: #cfcfcf; 
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
        @page { size: 4in 8.5in; margin: 5px 20px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 7px!important; padding-right: 7px!important; font-size: 10px">
                <tr>
                    <td width="30%" class="text-left p-0" style="font-size: 8px;">DepEd Form 138</td>
                    <td width="42%" class="text-left p-0" style=""></td>
                    <td width="6%" class="text-left p-0" style="font-size: 8px;">LRN</td>
                    <td width="22%" class="text-left p-0" style="border-bottom: 1px solid #000;font-size: 8px;">{{$student->lrn}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 25%; text-align: right; vertical-align: top;">
                    </td>
                    <td style="width: 50%; text-align: center; vertical-align: middle; margin-top: 10px;">
                        <div class="p-0" style="width: 100%; font-size: 8px;">Republic of the Philippines</div>
                        <div class="p-0" style="width: 100%; font-size: 8px;">Department of Education</div>
                        <div class="p-0" style="width: 100%; font-size: 8px;">Region IX</div>
                    </td>
                    <td style="width: 25%; text-align: center; vertical-align: middle;">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 15%; text-align: center;" valign="top">
                    <div class="p-0" style="width: 100%;"><img style="" src="{{base_path()}}/public/assets/images/sjsfi/logo.png" alt="school" width="40px"></div>
                    </td>
                    <td width="60%" class="text-center" style="vertical-align: middle;">
                        <div class="p-0" style="font-size: 12px;"><b>Saint Joseph School Foundation Inc.</b></div>
                        <div class="p-0" style="font-size: 9px;">Gov. Camins Road, Canelar</div>
                        <div class="p-0" style="font-size: 9px;">Zamboanga City, Philippines</div>
                    </td>
                    <td style="width: 15%; text-align: center; vertical-align: middle;">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center" style="vertical-align: top;">
                        <div class="p-0" style="font-size: 11px;"><b>REPORT CARD</b></div>
                        <div class="p-0" style="font-size: 9px;">School Year {{$schoolyear->sydesc}}</div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; font-size: 10px;">
                <tr>
                    <td width="10%" class="text-left p-0" style="vertical-align: top;">Name:</td>
                    <td width="53%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</td>
                    <td width="7%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Age:</td>
                    <td width="12%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->age}}</td>
                    <td width="8%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Sex:</td>
                    <td width="10%" class="text-center p-0" style="border-bottom: 1px solid #000;">{{$student->gender[0]}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: .1em; font-size: 10px;">
                <tr>
                    <td width="17%" class="text-left p-0" style="vertical-align: top;">Grade Level:</td>
                    <td width="4%" class="text-center p-0" style="border-bottom: 1px solid #000;">
                        @if($student->levelid == 14) 11 @else 12 @endif
                    </td>
                    <td width="4%" class="text-left p-0" style=""></td>
                    <td width="20%" class="text-left p-0" style="vertical-align: top; padding-left: 5px!important;">Track/Strand:</td>
                    <td width="55%" class="text-left p-0" style="border-bottom: 1px solid #000; font-size: 6px; vertical-align: middle!important;">&nbsp;{{$strandInfo->strandname}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: .1em; font-size: 10px;">
                <tr>
                    <td width="11%" class="text-left p-0" style="vertical-align: top;">Adviser:</td>
                    <td width="49%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 9px;">{{$adviser}}</td>
                    <td width="40%" class="text-left p-0" style=""></td>
                </tr>
            </table>
         

            @php
                $x = 1;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="margin-top: 5px;">
                <tr style="background-color: #7a7a7a;">
                    <td width="53%" rowspan="2"  class="text-center align-top" style="font-size: 8px!important;">SUBJECTS</td>
                    <td width="20%" colspan="2"  class="text-center align-middle" style="font-size: 8px!important;">QUARTER</td>
                    <td width="27%" rowspan="2"  class="text-center align-middle" style="font-size: 8px!important;">1st SEMESTER <br> FINAL GRADE</td>
                </tr>
                <tr style="background-color: #7a7a7a;">
                    @if($x == 1)
                        <td width="12%" class="text-center align-middle">1</td>
                        <td width="8%" class="text-center align-middle">2</td>
                    @elseif($x == 2)
                        <td width="12%" class="text-center align-middle">3</td>
                        <td width="8%" class="text-center align-middle">4</td>
                    @endif
                </tr>
                <tr class="trhead">
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <tr class="trhead">
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <tr class="trhead">
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
            
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td class="text-right" colspan="3" style="font-size: 9px!important;">Gen. Ave for Second semester</td>
                    <td class="text-center" style="font-weight: bold; font-size: 9px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                </tr>
            </table>
            @php
                $x = 2;
            @endphp
            <table width="100%" class="table table-bordered table-sm grades mb-0" style="margin-top: 5px;">
                <tr style="background-color: #7a7a7a;">
                    <td width="53%" rowspan="2"  class="text-center align-top p-0" style="font-size: 8px!important;">SUBJECTS</td>
                    <td width="20%" colspan="2"  class="text-center align-middle p-0" style="font-size: 8px!important;">QUARTER</td>
                    <td width="27%" rowspan="2"  class="text-center align-middle p-0" style="font-size: 8px!important;">2nd SEMESTER <br> FINAL GRADE</td>
                </tr>
                <tr style="background-color: #7a7a7a;">
                    @if($x == 1)
                        <td width="12%" class="text-center align-middle">1</td>
                        <td width="8%" class="text-center align-middle">2</td>
                    @elseif($x == 2)
                        <td width="12%" class="text-center align-middle">3</td>
                        <td width="8%" class="text-center align-middle">4</td>
                    @endif
                </tr>
                <tr class="trhead">
                    <td class="p-0" style="text-align: left; font-size: 7px!important;" colspan="4"><b>&nbsp;Core</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <tr class="trhead">
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Applied</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
                <tr class="trhead">
                    <td class="p-0" style="text-align: left;font-size: 7px!important;" colspan="4"><b>&nbsp;Specialized</b></td>
                </tr>
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                    <tr>
                        <td style="font-size: 7px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                        @if($x == 1)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                        @elseif($x == 2)
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                        @endif
                        <td class="text-center align-middle" style="font-size: 8px!important;">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    </tr>
                @endforeach
            
                <tr>
                    @php
                        $genave = collect($finalgrade)->where('semid',$x)->first();
                    @endphp
                    <td class="text-right" colspan="3" style="font-size: 9px!important;">Gen. Ave for Second semester</td>
                    <td class="text-center" style="font-weight: bold; font-size: 9px!important;">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                </tr>
            </table>











            
            <table class="table table-sm table-bordered mb-0" style="table-layout: fixed; margin-top: 5px; font-size: 8px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Descriptors:</td>
                    <td class="text-left p-0" width="30%">&nbsp;&nbsp;Grading Scale</td>
                    <td class="text-left p-0" width="25%">&nbsp;&nbsp;Remarks</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Outstanding</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">90-100</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;">Passed</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Very Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">85-89</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;">Passed</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">80-84</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;">Passed</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Fairly Satisfactory</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">75-79</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;">Passed</td>
                </tr>
                <tr>
                    <td class="text-left p-0" width="45%">&nbsp;&nbsp;Did not Meet Expectation</td>
                    <td class="text-left p-0" width="30%" style="padding-left: 20px!important;">Below 75</td>
                    <td class="text-left p-0" width="25%" style="padding-left: 10px!important;">Failed</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; font-size: 7px;">
                <tr>
                    <td width="38%" class="text-left p-0">Eligible for transfer and admission to</td>
                    <td width="27%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="45%" class="text-left p-0" style="">From 137-A will be forwarded upon</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 2px; font-size: 7px;">
                <tr>
                    <td width="38%" class="text-left p-0">Request has advance/lacks credits in</td>
                    <td width="27%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="45%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 2px; font-size: 7px;">
                <tr>
                    <td width="8%" class="text-left p-0">Date:</td>
                    <td width="18%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="4%" class="text-center p-0">20:</td>
                    <td width="5%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="75%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <!-- <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px; font-size: 10px; padding-left: 20px; padding-right: 20px;">
                <tr>
                    <td width="77%" class="text-right p-0"><b><i>Eligible for transfer and admission to Grade&nbsp;</i></b></td>
                    <td width="23%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table> -->
            <!-- <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 12px; padding-left: 78px; padding-right: 50px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="border-bottom: 1px solid #000;"><span style="text-transform: lowercase!important; first-line">{{$adviser}}</span></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 10px; padding-top: 2px!important;"><b><i>Teacher</i></b></td>
                </tr>
            </table> -->
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; font-size: 8px; padding-right: 10px!important;">
                <tr>
                    <td width="55%" class="text-center p-0" style=""></td>
                    <td width="45%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 8px;">Sr. Josephine C. Sambrano, OP, MAEd</td>
                </tr>
                <tr>
                    <td width="55%" class="text-center p-0" style=""></td>
                    <td width="45%" class="text-center p-0" style="font-size: 7px; padding-top: 2px!important;">Principal</td>
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
                        TO THE PARENTS OR GUARDIANS
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="p-0" style="font-size: 10px; text-align: justify">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school seeks your cooperation so that both you and the teacher may work together for the good of your child. You are invited to visit the school and confer with the subject teacher and or the section adviser
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 15px;">
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
            <!--        <td width="50%" class="text-center p-0" style=""><b>Character Traits</b></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""><b>1</b></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""><b>2</b></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""><b>3</b></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""><b>4</b></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;1. Honesty</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;2. Courteousness</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;3. Responsibility</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;4. Resourcefulness and <br> &nbsp;Creativity</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;5. Fairness</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;6. Leadership</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;7. Obedience</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;8. Self-Reliance</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;9. Industry</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--    <tr>-->
            <!--        <td width="50%" class="text-left p-0" style="">&nbsp;10. Cleanliness and Orderliness</td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--        <td width="12.5%" class="text-center p-0" style=""></td>-->
            <!--    </tr>-->
            <!--</table>-->
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 15px;">
                        <b>GUIDELINES FOR RATING</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0 table-bordered" style="table-layout: fixed; padding-left: 90px; padding-right: 90px;">
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;"><b>A</b></td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;"><b>Very Good</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;"><b>B</b></td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;"><b>Good</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;"><b>C</b></td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;"><b>Fair</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 10px;"><b>D</b></td>
                    <td width="60%" class="text-center p-0" style="font-size: 10px;"><b>Poor</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 12px;">
                        REPORT ON ATTENDANCE
                    </td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 79 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td class="diagonal" style="padding-top: 13px!important;border: 1px solid #000; text-align: center;font-size: 8px!important;">
                        <div style="padding-top: -10px;">Mon <br>th</div>
                        <div style="padding-top: 15px;">Day</div>
                    </td>
                    @foreach ($attendance_setup as $item)
                        <td class="aside text-center align-middle;" width="{{$width}}%"><span style="font-size: 8px!important;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center p-0" width="11%" style="vertical-align: middle; font-size: 8px!important;"><span style="transform-origin: 13 22; transform: rotate(-90deg); top: 0; bottom: 10;"><b>TOTAL</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" width="10%"  style="font-size: 8px!important;">No. of School <br> Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" style="font-size: 8px!important;">No. of School <br> Days Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-center" style="font-size: 8px!important;">No. of Times <br> Tardy</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 8px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="100%" class="text-center" style="font-size: 12px; text-transform: uppercase;">
                        Signature of Parents or Guardian
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="22%" class="text-left p-0" style="font-size: 12px;">First Grading</td>
                    <td width="68%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 12px;">Second Grading</td>
                    <td width="64%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="23%" class="text-left p-0" style="font-size: 12px;">Third Grading</td>
                    <td width="67%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;">
                <tr>
                    <td width="25%" class="text-left p-0" style="font-size: 12px;">Fourth Grading</td>
                    <td width="65%" class="text-left p-0" style="font-size: 10px; border-bottom: 1px solid #000;"></td>
                    <td width="10%" class="text-left p-0" style=""></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>