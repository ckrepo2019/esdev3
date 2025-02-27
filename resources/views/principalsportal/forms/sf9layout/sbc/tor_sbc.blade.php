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
        @page { size: 8.5in 14in; margin: 20px 45px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="30%" style="text-align: left;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="width: 40%; text-align: center; vertical-align: top;">
            <div style="width: 100%; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
            <div style="width: 100%; font-size: 10px;">{{$schoolinfo[0]->address}}</div>
            {{-- <div style="width: 100%; font-size: 10px;">Tel/Fax.: 088-221-2368/ 088-221-2440</div> --}}
        </td>
        <td width="30%" style="">
            {{-- <div style="font-size: 10px;">
                <div class="text-center" style="height: 2.54cm; width: 2.54cm; border: 1px solid #000;"><span><br><br><br> 1x1 <br> PICTURE</span></div>
            </div> --}}
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="100%" class="text-center" style="font-size: 25px;"><b>CERTIFICATION</b></td>
    </tr>
</table>
<table class="" style="width: 100%; table-layout: fixed; font-size: 15px; margin-top: 20px;">
    <tr>
        <td width="19%" class="text-left" style="">Name of Student:</td>
        <td width="31%" class="text-left" style="">{{$student->student}}</td>
        <td width="9%" class="text-left" style=""></td>
        <td width="19%" class="text-left" style="">Status:</td>
        <td width="22%" class="text-left" style="">Enrolled</td>
    </tr>
    <tr>
        <td width="19%" class="text-left" style="">Student ID:</td>
        <td width="31%" class="text-left" style="">18-22728</td>
        <td width="9%" class="text-left" style=""></td>
        <td width="19%" class="text-left" style="">Academic Year:</td>
        <td width="22%" class="text-left" style="">2ND SEMESTER</td>
    </tr>
    <tr>
        <td width="19%" class="text-left" style="">Course:</td>
        <td width="31%" class="text-left" style="">BSBA</td>
        <td width="9%" class="text-left" style=""></td>
        <td width="19 %" class="text-left" style="">Term/Semester:</td>
        <td width="22%" class="text-left" style="">{{$schoolyear->sydesc}}</td>
    </tr>
    <tr>
        <td width="19%" class="text-left" style="">Year Level:</td>
        <td width="31%" class="text-left" style="">3rd Year</td>
        <td width="9%" class="text-left" style=""></td>
        <td width="19%" class="text-left" style="">Date Enrolled:</td>
        <td width="22%" class="text-left" style="">January 4, 2021</td>
    </tr>
</table>
<table class="" style="width: 100%; table-layout: fixed; font-size: 15px; margin-top: 20px;">
    <tr>
        <td width="100%" class="text-left" style="">This is to certify that <u>{{$student->student}}</u> is enrolled in <b>Souther Baptist College</b> for the <u>2ND SEMESTER, {{$schoolyear->sydesc}}</u></td>
    </tr>
</table>
<table class="" style="width: 100%; table-layout: fixed; font-size: 15px; margin-top: 20px;">
    <tr>
        <td width="100%" class="text-left" style=""><b>Period Covered:</b> &nbsp;&nbsp;&nbsp;&nbsp;2ND SEMESTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$schoolyear->sydesc}}</td>
    </tr>
</table>
<table class="table-bordered" style="width: 100%; table-layout: fixed; font-size: 11px; margin-top: 20px;">
    <tr>
        <td width="12%" class="text-left" style="padding-left: 5px;"><b>Subject <br>Code</b></td>
        <td width="30%" class="text-center" style=""><b>Descriptive Title</b></td>
        <td width="6%" class="text-left" style="padding-left: 5px;"><b>Units</b></td>
        <td width="22%" class="text-center" style=""><b>Day & Time</b></td>
        <td width="8%" class="text-center" style="padding-left: 5px;"><b>Room</b></td>
        <td width="22%" class="text-left" style="padding-left: 5px;"><b>Instructor</b></td>
    </tr>
    <tr>
        <td width="12%" class="text-left" style="padding-left: 5px;">FMPR 3062</td>
        <td width="30%" class="text-left" style="padding-left: 5px;">Capital Markets</td>
        <td width="6%" class="text-right" style="padding-right: 5px;">3.00</td>
        <td width="22%" class="text-center" style="">MWF - 07:00 AM - 08:00 AM</td>
        <td width="8%" class="text-center" style="">LCB 2</td>
        <td width="22%" class="text-left" style="padding-left: 5px;">AZUCENA, AMELIA G</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; ">
    <tr>
        <td width="100%" class="text-left" style="padding-left: 0;">Total units enrolled: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>24.00</b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; margin-top: 20px">
    <tr>
        <td width="42%" class="text-left" style="padding-left: 0;">Tuition fee (total units x amount/unit):</td>
        <td width="18%" class="text-right" style="padding-left: 0;">6,775.20</td>
        <td width="40%" class="text-left" style=""></td>
    </tr>
    <tr>
        <td width="42%" class="text-left" style="padding-left: 0;">Other School Fees:</td>
        <td width="18%" class="text-right" style="border-bottom: 1px solid #000;">33,420.41</td>
        <td width="40%" class="text-left" style=""></td>
    </tr>
    <tr>
        <td width="42%" class="text-left" style="padding-left: 0;">Total Tuition and Other School fees:</td>
        <td width="18%" class="text-right" style=""><b>40,195.611</b></td>
        <td width="40%" class="text-left" style=""></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; margin-top: 20px;">
    <tr>
        <td width="100%" class="text-left" style="padding-left: 0;">This is to certify further that for the <b>1ST SEMESTER and {{$schoolyear->sydesc}},</b> the academic performance of Mr./Ms. <b>{{$student->student}}</b> is as follows:</td>
    </tr>
</table>
<table class="" style="width: 100%; table-layout: fixed; font-size: 15px; margin-top: 20px;">
    <tr>
        <td width="100%" class="text-left" style="padding-left: 0;"><b>Period Covered:</b> &nbsp;&nbsp;&nbsp;&nbsp;1ST SEMESTER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$schoolyear->sydesc}}</td>
    </tr>
</table>
<table class="table-bordered" style="width: 100%; table-layout: fixed; font-size: 11px; padding-left: 5px; padding-right: 5px;">
    <tr>
        <td width="15%" class="text-center"><b>Subject <br> Code</b></td>
        <td width="35%" class="text-center"><b>Descriptive Title</b></td>
        <td width="13%" class="text-center"><b>Units</b></td>
        <td width="15%" class="text-center"><b>Grade</b></td>
        <td width="22%" class="text-center"><b>Remarks</b></td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="padding-left: 5px;">FMPR 3051</td>
        <td width="35%" class="text-left" style="padding-left: 5px;">Credits Analysis And Reporting</td>
        <td width="13%" class="text-center">3.00</td>
        <td width="15%" class="text-center">1.50</td>
        <td width="22%" class="text-center"></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; padding-left: 5px; padding-right: 5px;">
    <tr>
        <td width="100%" class="text-left" style="padding-left: 0;"><b>General Weighted Avg. (GWA): &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.00</b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; margin-top: 60px;">
    <tr>
        <td width="55%" class="text-left" style="padding-left: 0;"><b>Household per Capita Income of Parents/Guardian/s:</b></td>
        <td width="15%" class="text-center" style="border-bottom: 1px solid #000;"><b>5000</b></td>
        <td width="30%" class="text-left" style=""></td>
    </tr>
    <tr>
        <td width="55%" class="text-left" style="padding-left: 0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued for scholarship purposes/s.</td>
        <td width="15%" class="text-center" style=""></td>
        <td width="30%" class="text-left" style=""></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px;">
    <tr>
        <td width="74%" class="text-left" style="padding-left: 0;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>06/28/2022</u>&nbsp;&nbsp;at&nbsp;&nbsp;<u>Souther Baptist College, Mlang, Cotabato&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
        <td width="26%" class="text-left" style=""></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; margin-top: 40px;">
    <tr>
       <td width="12%" class="text-left"></td>
       <td width="33%" class="text-left">Certified by:</td>
       <td width="10%" class="text-left"></td>
       <td width="35%" class="text-left"></td>
       <td width="10%" class="text-left"></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 15px; margin-top: 40px;">
    <tr>
        <td width="12%" class="text-left"></td>
        <td width="30%" class="text-center" style="border-bottom: 1px solid #000;">AMELIA G. AZUCENA, MBE</td>
        <td width="13%" class="text-left"></td>
        <td width="31%" class="text-center" style="border-bottom: 1px solid #000;">MERIAM S. FRASCO, MBE</td>
        <td width="14%" class="text-left"></td>
     </tr>
     <tr>
        <td width="12%" class="text-left"></td>
        <td width="30%" class="text-center" style="">Finance Officer</td>
        <td width="13%" class="text-left"></td>
        <td width="31%" class="text-center" style="">Registrar</td>
        <td width="14%" class="text-left"></td>
     </tr>
</table>
</body>
</html>