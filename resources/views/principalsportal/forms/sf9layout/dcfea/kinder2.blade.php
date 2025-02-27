<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> -->
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
       .p-1 {
            padding-top: 8px!important;
            padding-bottom: 8px!important;
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
            font-size: 10px !important;
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
            /* line-height: 15px; */
            /* height: 35px; */
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
            /* transform-origin: 10 10; */
            /* transform: rotate(-90deg); */
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
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 11in 8.5in; margin: 30px 30px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; padding-right: 50px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                        <b>TEACHER'S SIGNATURE/COMMENT</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="20%" class="text-center p-1" style=""></td>
                    <td width="75%" class="text-center p-1" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="25%" class="text-center p-1" style=""></td>
                </tr>
                <tr>
                    <td width="20%" class="text-center p-1" style=""></td>
                    <td width="75%" class="text-center p-1" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="25%" class="text-center p-1" style=""></td>
                </tr>
                <tr>
                    <td width="20%" class="text-center p-1" style=""></td>
                    <td width="75%" class="text-center p-1" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="25%" class="text-center p-1" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 80px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                        <b>PARENT'S SIGNATURE</b>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px;">
                <tr>
                    <td width="20%" class="text-center p-1" style=""></td>
                    <td width="75%" class="text-center p-1" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="25%" class="text-center p-1" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 80px;">
                <tr>
                    <td width="33%" class="text-left p-0" style="font-size: 14px;">To be classified as</td>
                    <td width="67%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;">Has advance units in</td>
                    <td width="67%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
                <tr>
                    <td width="33%" class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;">&nbsp;</td>
                    <td width="67%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px;">
                <tr>
                    <td width="20%" class="text-left p-0" style="font-size: 14px;">Lacks units in</td>
                    <td width="80%" class="text-left p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 25px;">
                <tr>
                    <td width="10%" class="text-left p-0" style="font-size: 14px;">Date</td>
                    <td width="35%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
                    <td width="55%" class="text-left p-0" style=""></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 35px;">
                <tr>
                    <td width="10%" class="text-center p-0"></td>
                   <td width="35%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 14px;"><b></b></td>
                   <td width="10%" class="text-center p-0"></td>
                   <td width="45%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 14px;"><b>MARIE THERESE C. NAPURA</b></td>
                </tr>
                <tr>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="35%" class="text-center p-0" style="font-size: 14px;">Adviser</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="45%" class="text-center p-0" style="font-size: 14px;">Officer-In-Charge</td>
                 </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="padding-left: 15px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="60%" class="text-left p-0" style="font-size: 12px;">
                        <b><i>DepED FORM 9</i></b>
                    </td>
                    <td width="7%" class="text-left  p-0" style="font-size: 12px;"><i>LRN: </i></td>
                    <td width="33%" class="text-left p-0" style="font-size: 12px; border: 1px solid #000; margin-top: 2px!important;">&nbsp;</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 15px">
                <tr>
                    <td width="25%" class="text-right p-0" style="">
                        <img style="" src="{{base_path()}}/public/assets/images/dcfea/kagawaran.png" alt="school" width="60px">
                    </td>
                    <td width="50%" class="text-center p-0" style="">
                        <div style="font-size: 13px;">Republic of the Philippines</div>
                        <div style="font-size: 13px;"><b>DEPARTMENT OF EDUCATION</b></div>
                        <div style="font-size: 13px;">Region VI - Western Visayas</div>
                        <div style="font-size: 13px;">Division of Iloilo</div>
                    </td>
                    <td width="25%" class="text-left p-0" style="">
                        <img style="" src="{{base_path()}}/public/assets/images/dcfea/dcfea.png" alt="school" width="60px">
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px!important;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 13px;"><b>DOANE CHRISTIAN FELLOWSHIP ACADEMY (DCFeA) MIAGAO, ILOILO INC.</b></td>
                </tr>
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 11px;">Kirayan Sur, Miagao, Iloilo</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important;">
                <tr>
                    <td width="19.5%" class="text-center p-0" style="font-size: 13px;"></td>
                    <td width="61%" class="text-center p-0" style="font-size: 21px!important;">
                        <div style="border: 3px solid #000; padding-top: 10px!important; border-radius: 10px!important;"><b>PROGRESS REPORT CARD</b></div>
                    </td>
                    <td width="19.5%" class="text-center p-0" style="font-size: 13px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important; padding-left: 10px!important;">
                <tr>
                    <td width="19.5%" class="text-left p-0" style="font-size: 14px;">Name:</td>
                    <td width="80.5" class="text-left p-0" style="border-bottom: 1px solid #000; font-size: 18px;"><b><i>{{$student->lastname.', '.$student->firstname.' '.$student->middlename[0].'.'}}</i></b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important; padding-left: 10px!important;">
                <tr>
                    <td width="19.5%" class="text-left p-0" style="font-size: 14px;">Age:</td>
                    <td width="27.5" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 15px;">{{$student->age}}</td>
                    <td width="14%" class="text-left p-0" style=""></td>
                    <td width="10%" class="text-left p-0" style="font-size: 14px;">Sex:</td>
                    <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 15px;">{{$student->gender}}</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 10px!important; padding-left: 10px!important;">
                <tr>
                    <td width="19.5%" class="text-left p-0" style="font-size: 14px;">Level:</td>
                    <td width="27.5" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 15px;">{{str_replace('GRADE', '', $student->levelname)}}</td>
                    <td width="14%" class="text-left p-0" style=""></td>
                    <td width="10%" class="text-left p-0" style="font-size: 14px;">S.Y.:</td>
                    <td width="29%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 15px;">{{$schoolyear->sydesc}}</td>
                </tr>
            </table>
            <table class="table table-sm" style="table-layout: fixed; margin-top: 20px!important; padding-left: 10px!important;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px;"><i>Dear Parents,</i></td>
                </tr>
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px; padding-left: 60px!important; padding-top: 5px!important;"><i>This report card shows the ability and the progress your child has</i></td>
                </tr>
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;"><i>made in the different learning areas as well as his/her progress in</i></td>
                </tr>
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;"><i>character development.</i></td>
                </tr>
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px; padding-left: 60px!important; padding-top: 5px!important;"><i>The school welcomes you if you desire to know more about the</i></td>
                </tr>
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px; padding-top: 5px!important;"><i>progress of your child.</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 10px!important;">
                <tr>
                   <td width="40%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 14px;"><b></b></td>
                   <td width="10%" class="text-center p-0"></td>
                   <td width="50%" class="text-center p-0" style="border-bottom: 1px solid #000; font-size: 14px;"><b>MARIE THERESE C. NAPURA</b></td>
                </tr>
                <tr>
                    <td width="40%" class="text-center p-0" style="font-size: 14px;">Teacher-In-Charge</td>
                    <td width="10%" class="text-center p-0"></td>
                    <td width="50%" class="text-center p-0" style="font-size: 14px;">Officer-In-Charge</td>
                 </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px!important; padding-left: 5px!important;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px;">
                        <div style="border: 2px solid #000; padding-top: 10px; padding-bottom: 10px; padding-left: 10px!important; padding-right: 5px!important;border-radius: 20px;">
                            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 10px!important;">
                                <tr>
                                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                                        <b>CERTIFICATE OF TRANSFER</b>
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 5px!important;">
                                <tr>
                                    <td width="23%" class="text-left p-0" style="font-size: 11px;">Admitted to Grade</td>
                                    <td width="14%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                    <td width="5%" class="text-left p-0" style="font-size: 11px;"></td>
                                    <td width="37%" class="text-left p-0" style="font-size: 11px;">Eligible for Admission to Grade</td>
                                    <td width="21%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm" style="table-layout: fixed;">
                                <tr>
                                    <td width="100%" class="text-left p-0" style="font-size: 11px;">Approved:</td>
                                </tr>
                            </table>
                            <table class="table table-sm" style="table-layout: fixed;">
                                <tr>
                                    <td width="11%" class="text-left p-0" style="font-size: 11px;">&nbsp;</td>
                                    <td width="31%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                    <td width="12%" class="text-left p-0" style="font-size: 11px;"></td>
                                    <td width="37%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                    <td width="10%" class="text-left p-0" style="font-size: 11px;"></td>
                                </tr>
                                <tr>
                                    <td width="11%" class="text-left p-0" style="font-size: 11px;">&nbsp;</td>
                                    <td width="31%" class="text-center p-0" style="font-size: 11px;">Officer-in-Charge</td>
                                    <td width="12%" class="text-left p-0" style="font-size: 11px;"></td>
                                    <td width="37%" class="text-center p-0" style="font-size: 11px;">Teacher</td>
                                    <td width="10%" class="text-left p-0" style="font-size: 11px;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm" style="table-layout: fixed;">
                                <tr>
                                    <td width="15%" class="text-left p-0" style="font-size: 11px;">Admitted in</td>
                                    <td width="27%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                    <td width="16%" class="text-left p-0" style="font-size: 11px;"></td>
                                    <td width="7%" class="text-left p-0" style="font-size: 11px;">Date:</td>
                                    <td width="36%" class="text-left p-0" style="font-size: 11px; border-bottom: 1px solid #000;"></td>
                                </tr>
                            </table>
                            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px!important;">
                                <tr>
                                   <td width="30%"></td>
                                   <td width="40%" class="text-center" style="border-bottom: 1px solid #000; font-size: 11px;"></td>
                                   <td width="30%"></td>
                                </tr>
                                <tr>
                                    <td width="30%"></td>
                                    <td width="40%" class="text-center" style="font-size: 11px;">Officer-in-Charge</td>
                                    <td width="30%"></td>
                                 </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
        </td>
    </tr>
</table>
<br>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="50%" class="p-0" style="vertical-align: top; padding-right: 50px!important;">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                        <b>PROGRESS ON LEARNER'S PROGRESS IN LEARNING</b>
                    </td>
                </tr>
            </table>
            <table width="100%" class="table table-sm table-bordered grades" style="">
                <thead>
                    <tr>
                        <td rowspan="2"  class="align-middle text-center" width="43%" style="font-size: 12px!important;"><b>LEARNING AREAS</b></td>
                        <td colspan="4"  class="text-center align-middle" style="font-size: 12px!important;"><b>Grading Period</b></td>
                        <td class="text-center align-middle" width="9%"  style="font-size: 12px!important; padding-top: 10px!important;"><b>Final <br> Rating</b></td>
                        
                        <td class="text-center align-middle" width="16%" style="font-size: 12px!important;"><b>Remarks</b></td>
                    </tr>
                    <tr>
                        <td class="text-center align-middle" width="8%" style="font-size: 14px!important;"><b>1</b></td>
                        <td class="text-center align-middle" width="8%" style="font-size: 14px!important;"><b>2</b></td>
                        <td class="text-center align-middle" width="8%" style="font-size: 14px!important;"><b>3</b></td>
                        <td class="text-center align-middle" width="8%" style="font-size: 14px!important;"><b>4</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($studgrades as $item)
                        <tr>
                            <td class="p-1" style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}; font-size: 12px!important;">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{isset($item->finalrating) ? $item->finalrating:''}}</td>
                            <td class="text-center align-middle" style="font-size: 12px!important;">{{isset($item->actiontaken) ? $item->actiontaken:''}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-right p-1" style="font-size: 12px!important;"><b>General Average&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
                        <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}" style="font-size: 12px!important;"><b>{{isset(collect($finalgrade)->first()->finalrating) ? collect($finalgrade)->first()->finalrating : ''}}</b></td>
                        <td class="text-center align-middle" style="font-size: 12px!important;"><b>{{isset(collect($finalgrade)->first()->actiontaken) ? collect($finalgrade)->first()->actiontaken : ''}}</b></td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;"><b><i>Descriptors</i></b></td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;"><b><i>Grading Scale</i></b></td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><b><i>Remarks</i></b></td>
                </tr>
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;">Outstanding</td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;">90 - 100</td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><i>Passed</i></td>
                </tr>
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;">Very Satisfactory</td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;">85 - 89</td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><i>Passed</i></td>
                </tr>
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;">Satisfactory</td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;">80 - 84</td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><i>Passed</i></td>
                </tr>
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;">Fairly Satisfactory</td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;">75 - 79</td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><i>Passed</i></td>
                </tr>
                <tr>
                    <td width="51%" class="text-left p-0" style="font-size: 13px; padding-left: 30px!important;">Did Not Meet Expectation</td>
                    <td width="24%" class="text-center p-0" style="font-size: 13px;">Below 75</td>
                    <td width="26%" class="text-center p-0" style="font-size: 13px;"><i>Failed</i></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; margin-top: 20px;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                        <b>REPORT ON ATTENDANCE</b>
                    </td>
                </tr>
            </table>
            @php
                $width = count($attendance_setup) != 0? 60 / count($attendance_setup) : 0;
            @endphp
            <table class="table table-bordered table-sm grades mb-0" width="100%" style="margin-top: 10px;">
                <tr>
                    <td class="p-1" style="border: 1px solid #000; text-align: center;font-size: 9px!important;">
                        <div style="">Day \ Month</div>
                    </td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center p-1" width="{{$width}}%"><span style="font-size: 9px!important;">{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</span></td>
                    @endforeach
                    <td class="text-center p-0" width="11%" style="vertical-align: middle; font-size: 9px!important;"><span style=""><b>Total</b></span></td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-left p-1" width="29%"  style="font-size: 9px!important;">No. of School Days</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{$item->days != 0 ? $item->days : '' }}</td>
                    @endforeach
                    <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{collect($attendance_setup)->sum('days')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-left p-1" style="font-size: 8px!important;">No. of School Days Present</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{$item->days != 0 ? $item->present : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{collect($attendance_setup)->where('days','!=',0)->sum('present')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-left p-1" style="font-size: 9px!important;">No. of School Days Absent</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-1" >{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-1" >{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
                <tr class="table-bordered">
                    <td class="text-left p-1" style="font-size: 8px!important;">No. of Times Tardy</td>
                    @foreach ($attendance_setup as $item)
                        <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{$item->days != 0 ? $item->absent : ''}}</td>
                    @endforeach
                    <td class="text-center align-middle p-1" style="font-size: 9px!important;">{{collect($attendance_setup)->sum('absent')}}</td>
                </tr>
            </table>
        </td>
        <td width="50%" class="p-0" style="vertical-align: top;">
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 10px!important;">
                <tr>
                    <td width="100%" class="text-center p-0" style="font-size: 14px;">
                        <b>PROGRESS ON LEARNER'S VALUE AND ATTITUDE</b>
                    </td>
                </tr>
            </table>
            <table class="table table-bordered table-sm mb-0" style="table-layout: fixed; font-size: 13px;">
                <tr>
                    <td width="70%" class="text-center" style=""><b>Traits</b></td>
                    <td width="5%" class="text-center" style=""><b>1</b></td>
                    <td width="5%" class="text-center" style=""><b>2</b></td>
                    <td width="5%" class="text-center" style=""><b>3</b></td>
                    <td width="5%" class="text-center" style=""><b>4</b></td>
                    <td width="10%" class="text-center" style=""><b>Final</b></td>
                </tr>
                @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                    @phpw
                        $count = 0;
                    @endphp
                    @foreach ($groupitem as $item)
                        @if($item->value == 0)
                        @else
                            <tr>
                                <td class="align-middle" style="font-size: 13px;">{{$item->description}}</td>
                                <td class="text-center align-middle" style="font-size: 13px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 13px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 13px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td class="text-center align-middle" style="font-size: 13px;">
                                    @foreach ($rv as $key=>$rvitem)
                                        {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                    @endforeach 
                                </td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                <tr>
                    <td class="text-center" style="font-size: 13px;"><b>AVERAGE</b></td>
                    <td class="text-center" style="font-size: 13px;"></td>
                    <td class="text-center" style="font-size: 13px;"></td>
                    <td class="text-center" style="font-size: 13px;"></td>
                    <td class="text-center" style="font-size: 13px;"></td>
                    <td class="text-center" style="font-size: 13px;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 5px!important;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 14px;">Note: Not applicable due to distance learning.</td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed; padding-left: 5px!important; margin-top: 70px;">
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 13px;"></td>
                    <td width="65%" class="text-left p-0" style="font-size: 14px;"><b>Guidelines for Rating</b></td>
                </tr>
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 13px;"></td>
                    <td width="65%" class="text-left p-0" style="font-size: 14px; padding-top: 10px!important;"><b>A</b> (Very Good) = 93% - 98%</td>
                </tr>
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 13px;"></td>
                    <td width="65%" class="text-left p-0" style="font-size: 14px;"><b>B</b> (Good) = 87% - 92%</td>
                </tr>
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 13px;"></td>
                    <td width="65%" class="text-left p-0" style="font-size: 14px;"><b>C</b> (Fair) = 81% - 86%</td>
                </tr>
                <tr>
                    <td width="35%" class="text-left p-0" style="font-size: 13px;"></td>
                    <td width="65%" class="text-left p-0" style="font-size: 14px;"><b>D</b> (Poor) = 75% - 80%</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>