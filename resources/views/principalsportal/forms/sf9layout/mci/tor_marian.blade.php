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
        @page { size: 8.5in 11in; margin: 20px 35px;  }
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="25%" class="text-center" style="vertical-align: top;">
            <img src="{{base_path()}}/public/assets/images/mci/logo.png" alt="school" width="120px" height="110px">
        </td>
        <td width="50%" class="text-center" style="vertical-align: top;">
            <div class="p-0" style="font-size: 30px; color: #0070C0"><b>MARIAN COLLEGE</b></div>
            <div class="p-0" style="font-size: 12px;"><b>IPIL, ZAMBOANGA SIBUGAY</b></div>
            <div class="p-0" style="font-size: 14px;"><b>OFFICE OF THE REGISTRAR</b></div>
            <div class="p-0" style="font-size: 12px;"><b>COLLEGIATE DEPARTMENT</b></div>
            <div class="p-0" style="font-size: 16px; margin-top: 5px;"><b>OFFICIAL TRANSCRIPT OF RECORD</b></div>
        </td>
        <td width="25%" class="text-center" style="vertical-align: top;">
            <div style="font-size: 10px;">
                <div class="text-center" style="height: 3.8cm; width: 3.8cm; border: 1px solid #000;"><span><br><br><br><br><br><b>PHOTO</b></span></div>
            </div>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 3px;">
    <tr>
        <td width="18%" class="text-left p-0">NAME</td>
        <td width="32%" class="text-left p-0">:</td>
        <td width="5%" class="text-left p-0">AGE :</td>
        <td width="4%" class="text-left p-0"></td>
        <td width="5%" class="text-left p-0">SEX</td>
        <td width="12%" class="text-left p-0">:FEMALE</td>
        <td width="12%" class="text-left p-0">CIVIL STATUS :</td>
        <td width="12%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="18%" class="text-left p-0">DATE OF BIRTH</td>
        <td width="32%" class="text-left p-0">:</td>
        <td width="14%" class="text-left p-0">PLACE OF BIRTH</td>
        <td width="36%" class="text-left p-0">:</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="18%" class="text-left p-0">PARENT/GUARDIAN</td>
        <td width="32%" class="text-left p-0">:</td>
        <td width="14%" class="text-left p-0">ADDRESS</td>
        <td width="36%" class="text-left p-0">:</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 14.3px; margin-top: 5px;">
    <tr>
        <td width="100%" class="text-center p-0"><b>RECORDS OF PRELIMINARY GRADUATION</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 5px;">
    <tr>
        <td width="30%" class="text-left p-0">PRIMARY GRADES COMPLETED</td>
        <td width="20%" class="text-left p-0">:</td>
        <td width="26%" class="text-left p-0"></td>
        <td width="24%" class="text-left p-0">SY :</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="30%" class="text-left p-0">INTERMEDIATE GRADES COMPLETED</td>
        <td width="20%" class="text-left p-0">:</td>
        <td width="26%" class="text-left p-0"></td>
        <td width="24%" class="text-left p-0">SY :</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="30%" class="text-left p-0">SECONDARY COURSE COMPLETED</td>
        <td width="20%" class="text-left p-0">:</td>
        <td width="26%" class="text-left p-0"></td>
        <td width="24%" class="text-left p-0">SY :</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px;">
    <tr>
        <td width="63.5%" class="text-left p-0">TITLE OF DEGREE : <b>BACHELORS OF SECONDARY EDUCATION (BSED)</b></td>
        <td width="22%" class="text-left p-0">DATE OF GRADUATION</td>
        <td width="14.5%" class="text-left p-0">:</td>
    </tr>
    <tr>
        <td width="63.5%" class="text-left p-0">MAJOR<span style="padding-left: 69px!important;">:</span></td>
        <td width="22%" class="text-left p-0">MINOR</td>
        <td width="14.5%" class="text-left p-0">:</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 10px; border-top: 1px solid #000;">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;border-top: 1px solid #000">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" class="mb-0" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="15%" class="text-center"><b>COURSE NO.</b></td>
        <td width="35%" class="text-right"><b>DESCRIPTIVE TITLE</b></td>
        <td width="20%" class="text-center"></td>
        <td width="10%" class="text-center"><b>FINAL</b></td>
        <td width="10%" class="text-center"><b>R-EXAM</b></td>
        <td width="10%" class="text-center"><b>CREDIT</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;border: 1px solid #000;">
    <tr>
        <td class="p-0">
            <table width="100%" class="" style="table-layout: fixed; margin-top: 2px; font-size: 12px;border-top: 1px solid #000">
                <tr>
                    <td width="15%" class="text-center" style="border-right: 1px solid #000;">&nbsp;</td>
                    <td width="35%" class="text-right" style="border-right: 1px solid #000;">&nbsp;</td>
                    <td width="20%" class="text-center" style="border-right: 1px solid #000;">&nbsp;</td>
                    <td width="10%" class="text-center" style="border-right: 1px solid #000;">&nbsp;</td>
                    <td width="10%" class="text-center" style="border-right: 1px solid #000;">&nbsp;</td>
                    <td width="10%" class="text-center">&nbsp;</td>
                </tr>
            </table>
            <table width="100%" style="table-layout: fixed; font-size: 12px;border-top: 1px solid #000">
                <tr>
                    <td></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 9.1px;">
    <tr>
        <td width="100%" class="text-center" style=""><b>OFFICIAL MARKS :[EXCELLENT 95-100] [VERY GOOD 90-94.9] [ABOVE AVE. 85-89.9] [AVERAGE 80-84.9] [BELOW AVE. 76-79.9] [PASSED 75] FAILED LESS 75]</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; border-top: 1px solid #000;">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;border-top: 1px solid #000">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px;">
    <tr>
        <td width="15%" class="text-left" style="font-size: 12px;">REMARKS :</td>
        <td width="85%" class="text-left" style="font-size: 12px;"><i><b>CONTINUE NEXT SHEET</b></i></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 30px;">
    <tr>
        <td width="30%" class="text-center" style="font-size: 12px; border-bottom: 1.5px solid #000;"><b>JOSIEROSE D. ARBILLO</b></td>
        <td width="35%" class="text-center" style="font-size: 12px;"></td>
        <td width="30%" class="text-center" style="font-size: 12px; border-bottom: 1.5px solid #000;"><b>GINA C. LAGANG</b></td>
    </tr>
    <tr>
        <td width="30%" class="text-center" style="font-size: 9px;">PREPARED BY</td>
        <td width="35%" class="text-center" style="font-size: 12px;"></td>
        <td width="30%" class="text-center" style="font-size: 9px;">COLLEGE REGISTRAR</td>
    </tr>
    <tr>
        <td width="30%" class="text-center" style="font-size: 9px;"></td>
        <td width="35%" class="text-center" style="font-size: 11px;">Sunday, June 19, 2022</td>
        <td width="30%" class="text-center" style="font-size: 9px;"></td>
    </tr>
    <tr>
        <td width="30%" class="text-center" style="font-size: 9px;"></td>
        <td width="35%" class="text-center" style="font-size: 12px;"><b>(NOT VALID WITHOUT COLLEGE SEAL)</b></td>
        <td width="30%" class="text-center" style="font-size: 9px;"></td>
    </tr>
</table>
</body>
</html>