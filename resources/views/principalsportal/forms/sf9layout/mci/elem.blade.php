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
        @page { size: 11in 8.5in; margin: 20px 20px 0px;}
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="padding-right: 15px!important;">
            <table width="100%" class="table table-sm table-bordered grades" style="">
                <thead>
                    <tr>
                        <td rowspan="2"  class="align-middle text-center" width="50%" style="font-size: 14px!important;"><b style="color: #0070C0">LEARNING AREAS</b></td>
                        <td colspan="4"  class="text-center align-middle" style="font-size: 14px!important;"><b>Quarter</b></td>
                        <td class="text-center align-middle" width="10%"  style="font-size: 10px!important; padding-top: 10px!important;"><b style="color: #0070C0">Final <br> Grade</b></td>
                        
                        <td class="text-center align-middle" width="12%" style="font-size: 10px!important;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" width="7%" style="font-size: 14px!important;"><b>1</b></td>
                        <td class="text-center align-middle" width="7%" style="font-size: 14px!important;"><b>2</b></td>
                        <td class="text-center align-middle" width="7%" style="font-size: 14px!important;"><b>3</b></td>
                        <td class="text-center align-middle" width="7%" style="font-size: 14px!important;"><b>4</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 12px!important;"><b>{{$item->subjdesc!=null ? $item->subjdesc : null}}</b></td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    {{-- <tr>
                        <td class="text-left" style="font-size: 14px!important;"><b style="color: #0070C0">General Average</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                        <td class="text-center align-middle" style="font-size: 10px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                    </tr> --}}
                </tbody>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="78%" class="text-right" style="font-size: 13px!important;"><b style="color: #0070C0">General Average:&nbsp;</b></td>
                    <td width="10%" class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 13px!important;">{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</td>
                    <td width="12%" class="text-center align-middle" style="font-size: 13px!important;">{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed;">
                <tr>
                   <td width="35.5%" rowspan="2" class="text-center" style="font-size: 12px;"><b style="color: #0070C0">Descriptors</b></td>
                   <td width="35.5%" rowspan="2" class="text-center" style="font-size: 12px;"><b style="color: #0070C0">Grading Scale</b></td>
                   <td width="29%" class="text-center"><span style="color: #0070C0"><b style="font-size: 12px;">Honors Record</b> <br> <span style="font-size: 10px;">(Grading Period)</span></span></td>
                </tr>
                <tr>
                    <td width="29%" class="text-left" style="font-size: 13px;"><b>1.</b></td>
                 </tr>
                 <tr>
                   <td width="35.5%" class="text-left" style="font-size: 12px;"><b>With Highest Honor</b></td>
                   <td width="35.5%" class="text-center" style="font-size: 12px;"><b>98-100</b></td>
                    <td width="29%" class="text-left" style="font-size: 13px;"><b>2.</b></td>
                 </tr>
                 <tr>
                    <td width="35.5%" class="text-left" style="font-size: 12px;"><b>With High Honor</b></td>
                    <td width="35.5%" class="text-center" style="font-size: 12px;"><b>95-97</b></td>
                     <td width="29%" class="text-left" style="font-size: 13px;"><b>3.</b></td>
                  </tr>
                  <tr>
                    <td width="35.5%" class="text-left" style="font-size: 12px;"><b>With Honor</b></td>
                    <td width="35.5%" class="text-center" style="font-size: 12px;"><b>90-94</b></td>
                     <td width="29%" class="text-left" style="font-size: 13px;"><b>4.</b></td>
                  </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 12px; color: #FFC000;"><b>Attendance Record</b></td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 73 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%">
                <tr>
                    <td style="padding-top: 10px!important;border: 1px solid #000; text-align: center;">&nbsp;</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle;" width="{{$width}}%"><span style="font-size: 9px!important;"><b>{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</b></span></td>
                    @endforeach
                    <td class="text-center p-0" width="8%" style="vertical-align: top; font-size: 9px!important;"><span style=""><b>Total</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td width="19%"  style="font-size: 9px!important; padding-bottom: 8px!important;"><b>Days of School</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 9px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 9px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 9px!important; padding-bottom: 8px!important;"><b>Days Present</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 9px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 9px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td style="font-size: 9px!important; padding-bottom: 8px!important;"><b>Times Late</b></td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-0" style="font-size: 9px!important;"></td>
                    @endforeach
                    <td class="text-center align-middle p-0" style="font-size: 9px!important;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 9px;">Has advance Credit in</td>
                    <td width="30%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="14%" class="text-left p-0" style="font-size: 9px;">&nbsp;Lacks Credit in</td>
                    <td width="36%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 3px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="28%" class="text-left p-0" style="font-size: 9px; color: #0070C0">Eligible for Admission to Grade:</td>
                    <td width="36%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="36%" class="text-left p-0" style="font-size: 9px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 12px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="26%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="38%" class="text-left p-0" style="font-size: 9px;"></td>
                    <td width="36%" class="text-left p-0" style="font-size: 9px;"></td>
                </tr>
                <tr>
                    <td width="26%" class="text-center p-0" style="font-size: 9px;"><b>Principal</b></td>
                    <td width="38%" class="text-left p-0" style="font-size: 9px;"></td>
                    <td width="36%" class="text-left p-0" style="font-size: 9px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 1px; padding-left: 10px!important; padding-right: 10px!important; border-top: 2px solid #000;">
                <tr>
                    <td></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 3px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="36%" class="text-left p-0" style="font-size: 9px;"><b>Cancellation of transfer eligibility as of</b></td>
                    <td width="23%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="41%" class="text-left p-0" style="font-size: 9px;"><b>This student has been admitted</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="2%" class="text-left p-0" style="font-size: 9px;"><b>to</b></td>
                    <td width="28%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="14%" class="text-center p-0" style="font-size: 9px;"><b>Grade/College</b></td>
                    <td width="42%" class="text-left p-0" style="font-size: 9px; border-bottom: 1px solid #000;"></td>
                    <td width="14%" class="text-center p-0" style="font-size: 9px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px; padding-left: 10px!important; padding-right: 10px!important;">
                <tr>
                    <td width="28%"></td>
                    <td width="44%" class="text-center p-0" style="font-size: 9px; border-bottom: 1px solid #000;">CYNTHIA H. ORTEGA, M.A.</td>
                    <td width="28%"></td>
                </tr>
                <tr>
                    <td width="28%"></td>
                    <td width="44%" class="text-center p-0" style="font-size: 9px;0;"><i><b>Principal/Registrar</b></i></td>
                    <td width="28%"></td>
                </tr>
            </table>
        </td>
        <td width="25%" class="p-0" style="padding-left: 13px!important; padding-right: 13px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14.5px;">
                        <b>Signature of Parent/Guardian</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;font-size: 14.5px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="">1<sup>st</sup>.</td>
                    <td width="83%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;font-size: 14.5px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="">2<sup>nd</sup>.</td>
                    <td width="83%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;font-size: 14.5px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="">3<sup>rd</sup>.</td>
                    <td width="83%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px;font-size: 14.5px;">
                <tr>
                    <td width="12%" class="text-left p-0" style="">4<sup>th</sup>.</td>
                    <td width="83%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="5%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 30px;font-size: 14px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="">1.</td>
                    <td width="87%" class="p-0" style="text-align: justify;">This card must be signed by the parent or guardian and must be returned to the adviser within one week of date of issuance.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;font-size: 14px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="">2.</td>
                    <td width="87%" class="p-0" style="text-align: justify;">Parents of MARIAN COLLEGE students are always welcome at school for personal interview concerning the progress of their children.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;font-size: 14px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="">3.</td>
                    <td width="87%" class="p-0" style="text-align: justify;">To pass the year, a student must have an average of at least 75% in every subject in his/her final ratings.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;font-size: 14px;">
                <tr>
                    <td width="13%" class="text-left p-0" style="">4.</td>
                    <td width="87%" class="p-0" style="text-align: justify;">Every student is expected to perform the daily assignment. It is recommended that three (3) hours outside of class time to be devoted each day to the careful preparation of lessons.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 80px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;border-bottom: 2px solid #000;">Cynthia H. Ortega, MA</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">Principal</td>
                </tr>
            </table>
        </td>
        <td width="25%" class="p-0" style="padding-left: 15px!important; padding-right: 15px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14.5px;">
                        SF 9
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center">
                        <div style="font-size: 11px"><b>Republic of the Philippines</b></div>
                        <div style="font-size: 13px"><b><i>Department of Education</i></b></div>
                        <div style="font-size: 11.6px;">Region IX, Zamboanga Peninsula</div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center">
                        <div class="p-0" style="width: 100%;"><img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="140px"></div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 18px; color: #0070C0"><b>MARIAN COLLEGE, Inc.</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 13px; color: #FFC000;"><b>Ipil, Zamboanga Sibugay</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    {{-- <td width="100%" class="text-center p-0" style="font-size: 18px;border-bottom: 1px solid #000;"><b>MACEY JAMES F. SUMATRA</b></td> --}}
                    <td width="100%" class="text-center p-0" style="font-size: 18px;border-bottom: 1px solid #000;"><b>{{$student->firstname.' '.$student->middlename[0].'. '.$student->lastname}}</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 18px;">Name</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 40px;">
                <tr>
                    {{-- <td width="100%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;">Ipil Heights, Ipil, Zamboanga Sibugay</td> --}}
                    <td width="100%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;">{{$student->barangay.', '.$student->city.', '.$student->province}}</td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">Address</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="15%" class="text-left p-0" style="font-size: 14px;">Age:</td>
                    <td width="20%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;">{{$student->age}}</td>
                    <td width="10%" class="text-left p-0" style="font-size: 14px;"></td>
                    <td width="25%" class="text-left p-0" style="font-size: 14px;">Gender:</td>
                    <td width="30%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;">{{$student->gender}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 14px;">Grade:</td>
                    <td width="10%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;">{{str_replace('GRADE', '', $student->levelname)}}</td>
                    <td width="3%" class="text-left p-0" style="font-size: 14px;"></td>
                    <td width="25%" class="text-left p-0" style="font-size: 14px;">Section:</td>
                    <td width="42%" class="text-center p-0" style="font-size: 11px; border-bottom: 1px solid #000;">{{$student->sectionname}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px;">
                <tr>
                    <td width="40%" class="text-left p-0" style="font-size: 14px;">School Year:</td>
                    <td width="35%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;"><b>{{$schoolyear->sydesc}}</b></td>
                    <td width="25%" class="text-left p-0" style="font-size: 14px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 70px;">
                <tr>
                    <td width="18%" class="text-left p-0" style="font-size: 14px;">LRN:</td>
                    <td width="87%" class="text-center p-0" style="font-size: 14px;border-bottom: 1px solid #000;"><b>{{$student->lrn}}</b></td>
                    <td width="5%" class="text-left p-0" style="font-size: 14px;"></td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>
</body>
</html>