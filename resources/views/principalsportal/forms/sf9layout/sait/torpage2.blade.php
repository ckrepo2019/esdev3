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
        body { 
            /* margin:0px 10px; */
            
        }
		#watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                /* ==========  1st page  ========== */
                /* top: 9.5cm;
                bottom:   22cm;
                left:     1cm;
                opacity: 0.1; */
                
                /* ========== 2nd page  ========== */
                top: 6.5cm;
                bottom:   20cm;
                left:     .2cm;
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 8.5in 14in; margin: 30px 50px;}
        
    </style>
</head>
<body>
<div id="watermark">
    <img src="{{base_path()}}/public/assets/images/sait/sait_logo.png" alt="school" width="700px">
</div>
<table width="100%" style="table-layout: fixed; font-size: 15px;">
    <tr>
        <td width="100%" class="text-center p-0"><b>SAN AGUSTIN INSTITUTE OF TECHNOLOGY</b></td>
    </tr>
    <tr>
        <td width="100%" class="text-center p-0" style="font-size: 12px;">VALENCIA, BUKIDNON</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px;">
    <tr>
        <td width="30%" class="text-left p-0" style="border: 1px solid #000">&nbsp;&nbsp;&nbsp;OFFICE OF THE REGISTRAR</td>
        <td width="70%" class="text-left p-0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>COLLEGIATE ACADEMIC RECORD</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 12px; margin-top: 8px;">
    <tr>
        <td width="100%" class="text-left p-0" style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>OFFICE TRANSCRIPT OF RECORDS</b></td>
    </tr>
    <tr>
        <td width="100%" class="text-center p-0" style=""><b>RECORDS OF PRELIMINARY EDUCATION</b></td>
    </tr>
</table>

<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 12px;">
    <tr>
        <td width="6%" class="text-left p-0">NAME:</td> 
        <td width="36%" class="text-center p-0" style="border-bottom: 1px solid #000"></td> 
        <td width="12%" class="text-left p-0" style="">&nbsp;Candidate for:</td> 
        <td width="36%" class="text-center p-0" style="border-bottom: 1px solid #000"></td> 
        <td width="10%" class="text-center p-0"></td> 
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 10px; font-size: 14px;">
    <tr>
        <td width="100%" class="text-center p-0" style="letter-spacing: 5px;"><b>COLLEGIATE RECORDS</b></td> 
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 10px;">
    <tr>
        <td width="14%" class="text-center p-0"><b>SUBJECTS <br> NUMBERS</b></td>
        <td width="71%" colspan="4" class="text-center p-0"><b>DESCRIPTIVE TITLE</b></td>
        <td width="8%" class="text-center p-0"><b>FINAL <br> GRADE</b></td>
        <td width="7%" class="text-center p-0"><b>CREDITS</b></td>
    </tr>
</table>
<div style="height: 700px; border: 1px solid #000; border-top: none!important;">
    <div style="height: 680px;">

    </div>
    <div style="height: 20px;">
        <table width="100%" class="" style="table-layout: fixed; font-size: 10px;">
            <tr>
                <td class="text-center">x-x-x-x-x-x- NEXT PAGE PLEASE –x-x-x-x-x-x</td>
            </tr>
        </table>
    </div>
</div>
<table width="100%" class="" style="table-layout: fixed; font-size: 12px; margin-top: 2px;">
    <tr>
        <td width="17%" class="text-right p-0" style=""><b>Official Grades:</b></td> 
        <td width="83%" class="text-left p-0" style="padding-left: 15px!important;">1.0-95 - 100%, 1.1-94, 1.2 - 93, 1.3-92, 1.4-91, 1.5-90, 1.6-89, 1.7-88, 1.8-87, 1.9-86</td> 
    </tr>
    <tr>
        <td width="17%" class="text-right p-0" style=""></td> 
        <td width="83%" class="text-left p-0" style="padding-left: 15px!important;">2.0-85, 2.1-84, 2.2-83, 2.3-82, 2.4-81, 2.5-80, 2.6-79, 2.7-78, 2.8-77, 2.9-76, 3.0-75,5.0 Failed, </td> 
    </tr>
    <tr>
        <td width="17%" class="text-right p-0" style=""></td> 
        <td width="83%" class="text-left p-0" style="padding-left: 15px!important;">W (Withdrawal with Permission), WF (Withdrawal while failing), DR (Dropped),</td> 
    </tr>
    <tr>
        <td width="17%" class="text-right p-0" style=""></td> 
        <td width="83%" class="text-left p-0" style="padding-left: 15px!important;">INC (Incomplete), FA (Failure for excessive absences), NFE (No Final Exam).</td> 
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 15px; font-size: 14px;">
    <tr>
        <td width="100%" class="text-center p-0" style="letter-spacing: 5px;"><u>CERTIFICATION</u></td> 
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 10px; font-size: 12px;">
    <tr>
        <td width="100%" class="p-0" style="text-align: justify;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that the foregoing record of _____________________________ have been verified by me that the true copies of the official records substantiating the same are kept in the files of the school.  
        </td> 
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed;font-size: 14px;">
    <tr>
        <td width="100%" class="text-left p-0" style="padding-left: 5px!important;">(NOT VALID WITHOUT</td> 
    </tr>
    <tr>
        <td width="100%" class="text-left p-0" style="">SCHOOL SEAL)</td> 
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed;font-size: 12px; margin-top: 10px;">
   <tr>
        <td width="11%" class="text-left p-0">Prepared by:</td>
        <td width="27%" class="text-left p-0" style=""><u>&nbsp;&nbsp;&nbsp;APRIL JEAN B. TORREGOSA</u></td>
        <td width="7%" class="text-left p-0"></td>
        <td width="14%" class="text-left p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="8%" class="text-left p-0"></td>
        <td width="33%" class="text-right p-0" style=""><u>BLANCA H. GARDUQUE, LPT, DBM</u></td>
   </tr>
   <tr>
        <td width="11%" class="text-left p-0"></td>
        <td width="27%" class="text-center p-0" style="">Registrar's Clerk</td>
        <td width="7%" class="text-left p-0"></td>
        <td width="14%" class="text-left p-0" style="">&nbsp;Date of Issuance</td>
        <td width="8%" class="text-left p-0"></td>
        <td width="33%" class="text-center p-0" style="">College Registrar</td>
    </tr>
    <tr>
        <td width="11%" class="text-left p-0"></td>
        <td width="27%" class="text-center p-0" style=""></td>
        <td width="7%" class="text-left p-0"></td>
        <td width="14%" class="text-left p-0" style="font-size: 14px!important; ">SHEET NO.: 1</td>
        <td width="8%" class="text-left p-0"></td>
        <td width="33%" class="text-right p-0" style=""></td>
    </tr>
</table>
{{-- <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 10px; height: 300px!important;">
    <tr>
        <td class="p-0" style="vertical-align: bottom;">
            <div style="height: 300px;">
                <table width="100%" class="" style="table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td width="14%" class="text-center p-0">&nbsp;</td>
                        <td width="71%" colspan="4" class="text-center p-0"></td>
                        <td width="8%" class="text-center p-0"></td>
                        <td width="7%" class="text-center p-0"></td>
                    </tr>
                    <tr>
                        <td width="14%" class="text-center p-0">&nbsp;</td>
                        <td width="71%" colspan="4" class="text-center p-0"></td>
                        <td width="8%" class="text-center p-0"></td>
                        <td width="7%" class="text-center p-0"></td>
                    </tr>
                </table>
                <table width="100%" class="" style="table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td class="text-center">GDGDGDDG</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table> --}}
</body>
</html>
