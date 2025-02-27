<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .ordinal{
            font-variant-numeric: ordinal;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .cell-background{
            background-color: grey;
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
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 11in; margin: .4in .40in;}
    </style>
</head>
<body>
   <table class="p-0" width="100%" style="">
        <tr>
        <td width="100%"><img src="{{base_path()}}/public/assets/images/pssc/PSSHEADER.png" alt="" style="width: 7.5in"></td>
        </tr>
   </table>
   <table width="100%" class="align-middle text-center" style="margin-top: 1in;">
        <td style="font-size: 20pt; text-transform: uppercase"><b>Certificate of Enrolment</td>
   </table>
   <table class="p-0" width="100%" style="margin: 0cm 1cm; margin-top: 1cm;">
        <tr>
            <td width="28%" style="font-family:Arial, Helvetica, sans-serif; font-size: 11pt; text-indent: .4in">This is to certify that</td>
            <td width="22%" style="border-bottom: 1.3px solid black"></td>
            <td width="14%" style="font-family:Arial, Helvetica, sans-serif; font-size: 11pt">, with LRN.</td>
            <td width="16%" style="border-bottom: 1.3px solid black"></td>
            <td width="20%" style="font-family:Arial, Helvetica, sans-serif; font-size: 11pt">0 is enrolled as</td>
        </tr>
        <tr>
            <td colspan="1" style="border-bottom: 1px solid black"></td>
            <td colspan="4"> learner at the Passionist Sisters' School (Catmon), Inc. this</td>
        </tr>
        <tr>
            <td colspan="2">School Year <u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$schoolyear->sydesc}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.</td>
        </tr>
   </table>
   <table width="100%" style="margin-top: .3in; font-size: 11pt">
        <tr>
            <td style="text-indent: .8in; font-size: 11pt">This is to certify further that she is of <u>good moral character</u> and a law-abiding learner.</td>
        </tr>
   </table>
   <table width="100%" style="margin-top: .3in; font-size: 11pt">
        <tr>
            <td style="text-indent: .45in;padding-left: .4in; letter-spacing: .6px">This cerfication is issued upon the request of the above-named <u>for whatever legal<br>purpose that may best serve her.</u></td>
        </tr>
   </table>
   <table width="100%" style="margin-top: 7px; font-size: 11pt">
        <tr>
            <td width="21%"style="padding-left: .8in">Given this</td>
            <td width="10%" style="border-bottom: 1.5px solid black"></td>
            <td width="7.8%"> day of</td>
            <td width="24.2%" style="border-bottom: 1.5px solid black"></td>
            <td>.</td>
        </tr>
   </table>
   <table width="100%" class="text-center" style="margin-top: .8in; font-size: 11pt">
    <tr>
        <td>{{$principal_info[0]->name}}</td>
    </tr>
    <tr>
        <td>School Principal</td>
    </tr>
    </table>
    <table width="100%" style="margin-top: .4in; font-size: 11pt">
        <tr>
            <td width="80%"></td>
            <td width="10%" style="font-size: 6.5pt; color: blue">NOT VALID<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;without<br>SCHOOL SEAL</td>
            <td width="10%"></td>
        </tr>
    </table>
    <table class="p-0" width="100%" style="margin-top: 1in">
        <tr>
        <td width="100%"><img src="{{base_path()}}/public/assets/images/pssc/q1.png" alt="" style="width: 7.5in"></td>
        </tr>
   </table>
</body>
</html>