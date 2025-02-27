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
        @page { size: 8.5in 11in; margin: 10px 40px;  }
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="100%" class="text-center">
            <div width="100%" style="font-size: 11px; margin-top: 10px;"><i>OFFICE OF THE REGISTRAR</i></div>
            <div width="100%" style="font-size: 11px;">OFFICIAL TRANSCRIPT OF RECORDS</div>
            <div width="100%" style="font-size: 11px;">PAGE 3</div>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px;">
    <tr>{{-- <td>{{$student->firstname.' '.$student->middlename.' '.$student->lastname}}</td> --}}
        <td width="10%" class="text-left" style="font-size: 13px;">Name:</td>
        <td width="26.6666666667%" class="text-center" style="font-size: 20px;"><b>{{$student->lastname}}</b></td>
        <td width="26.6666666667%" class="text-center" style="font-size: 20px;"><b>{{$student->firstname}}</b></td>
        <td width="26.6666666667%" class="text-center" style="font-size: 20px;"><b>{{$student->middlename}}</b></td>
        <td width="10%" class="text-center"></td>
    </tr>
    <tr>
        <td width="10%" class="text-left" style=""></td>
        <td width="26.6666666667%" class="text-center" style="font-size: 12px;">(Last Name)</td>
        <td width="26.6666666667%" class="text-center" style="font-size: 12px;">(First Name)</td>
        <td width="26.6666666667%" class="text-center" style="font-size: 12px;">(Middle Name)</td>
        <td width="10%" class="text-center"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px;">
    <tr>
        <td width="100%" class="text-center">
            <div width="100%" style="font-size: 16px;"><b><i>COLLEGIATE RECORD</i></b></div>
        </td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 10px!important;">
    <tr>
        <td rowspan="2" width="15%" class="text-center" style="font-size: 12px; vertical-align: top;"><b>SUBJECT <br> NUMBER</b></td>
        <td rowspan="2" width="42%" class="text-center" style="font-size: 12px; vertical-align: top;"><b>DESCRIPTIVE TITLE</b></td>
        <td rowspan="2" width="6.5%" class="text-center" style="font-size: 12px; vertical-align: top;"><b>FINAL <br> GRADE</b></td>
        <td rowspan="2" width="6.5%" class="text-center" style="font-size: 12px; vertical-align: top;"><b>CREDIT <br>UNITS </b></td>
        <td colspan="10" width="30%" class="text-center" style="font-size: 12px; vertical-align: top;"><b>CREDIT DISTRIBUTION</b></td>
    </tr>
    <tr>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>1</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>2</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>3</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>4</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>5</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>6</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>7</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>8</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"><b>9</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
    </tr>
    <tr>
        <td colspan="4" class="text-center" style="font-size: 11px;"><b>SAN ISIDRO COLLEGE - Malaybalay City</b></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="text-left" style="font-size: 11px;">BSN - SUMMER: 2019</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;"></td>
        <td width="6.5%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">HEALTH ED</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Health Education</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">2.75</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">3</td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">ENG 3</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Speech and Oral Communication</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">2.50</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">3</td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>    
    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">ICT 1</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Informatics</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">2.25</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">3</td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;"></td>
        <td width="3%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
    {{-- <tr>
        <td colspan="2" class="text-left" style="font-size: 11px; padding-left: 5px; background-left: none!important;"></td>
        <td colspan="12" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr> --}}
</table>
<table width="100%" class="" style="table-layout: fixed;">
    <tr>
        <td width="57%" class="text-center"></td>
        <td width="43%" class="p-0">
            <table width="100%" class="table-bordered" style="table-layout: fixed; font-size: 11px!important;">
                <tr>
                    <td width="100%" class="text-center">SUMMARY OF UNITS</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">I&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;English</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">II&nbsp;&nbsp;&nbsp;&nbsp;Science</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">III&nbsp;&nbsp;&nbsp;Mathematics</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">IV&nbsp;&nbsp;&nbsp;&nbsp;Social Science</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">V&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Professional</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">VI&nbsp;&nbsp;&nbsp;&nbsp;Filipino</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">VII&nbsp;&nbsp;&nbsp;Elective</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">VIII&nbsp;&nbsp;NSTP (CWTS, ROTC, LTS)</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;">IX&nbsp;&nbsp;&nbsp;&nbsp;PE</td>
                </tr>
                <tr>
                    <td width="100%" class="text-left" style="padding-left: 5px;"><b>TOTAL CREDITS EARNED</b></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 11px; margin-top: 15px;">
    <tr>
        <td width="17%" class="text-left p-0" style="vertical-align: top;"><b>OFFICIAL GRADES:</b></td>
        <td width="83%" class="p-0" style="padding-left: 5px!important; text-align: justify!important;"><b>1.00 (99-100), 1.25 (96-98) Excellent, 1.50 (93-95) Superior, 1.75 (90-92) 2.00 (87-89) Very Satisfactory, 2.25 (84-86), 2.50 (81-83) Satisfactory, 2.75 (78-80), Good, 3.00 (75-77) Fair, 3.25-5.00 (74-below) Failure (F), DR (Dropped) FA (Failure due to absences)</b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 11px; margin-top: 15px;">
    <tr>
        <td width="15%" class="text-left p-0" style="vertical-align: top;">REMARKS:</td>
        <td width="83%" class="text-left p-0"><b><u>Eligible for graduation leading to the degree of Bachelor of Science in Nursing (BSN).</u></b></td>
    </tr>
    <tr>
        <td width="15%" class="text-center p-0" style="vertical-align: top;"></td>
        <td width="83%" class="text-left p-0">(Not valid without School Seal)</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 10px">
    <tr>
        <td width="100%" class="text-center" style="font-size: 13px; letter-spacing: 3px;"><b><u>CERTIFICATION</u></b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 15px">
    <tr>
        <td width="100%" class="text-left" style="font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; I hereby certify that the foregoing records of <b><u>{{$student->student}}</u></b> have been verified and that same are kept in the files of our school.</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 30px">
    <tr>
        <td width="15%" class="text-left p-0" style="font-size: 11px;">Prepared by:</td>
        <td width="45%" class="text-left p-0" style="font-size: 11px;"><u>LILIBETH G. TIMBAL</u></td>
        <td width="40%" class="text-left p-0" style="font-size: 11px;">CERTIFIED BY:</td>
    </tr>
    <tr>
        <td width="15%" class="text-left p-0" style="font-size: 11px;"></td>
        <td width="45%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;Office Staff IV-B</td>
        <td width="40%" class="text-left p-0" style="font-size: 11px;"></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-top: 30px">
    <tr>
        <td width="15%" class="text-left p-0" style="font-size: 11px;">Checked by:</td>
        <td width="45%" class="text-left p-0" style="font-size: 11px;"><u>ANNABEL L. MEÑOZA</u></td>
        <td width="40%" class="text-left p-0" style="font-size: 11px;"><u>NESTOR D. AZNAR JR.</u></td>
    </tr>
    <tr>
        <td width="15%" class="text-left p-0" style="font-size: 11px;"></td>
        <td width="45%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Office Staff</td>
        <td width="40%" class="text-left p-0" style="font-size: 11px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;College Registrar</td>
    </tr>
</table>
</body>
</html>