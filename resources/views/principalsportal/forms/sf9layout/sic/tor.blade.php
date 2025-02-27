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
        @page { size: 8.5in 11in; margin: 10px 35px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="30%" style="text-align: right;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
        </td>
        <td style="width: 40%; text-align: center;">
            <div style="width: 100%; font-weight: bold; font-size: 18px;">{{$schoolinfo[0]->schoolname}}</div>
            <div style="width: 100%; font-size: 10px;">{{$schoolinfo[0]->address}}</div>
            <div style="width: 100%; font-size: 10px;">Tel/Fax.: 088-221-2368/ 088-221-2440</div>
            {{-- <div style="width: 100%; font-size: 12px;">AY: {{$schoolyear->sydesc}}</div> --}}
        </td>
        <td width="30%" style="">
            <div style="font-size: 10px;">
                <div class="text-center" style="height: 2.54cm; width: 2.54cm; border: 1px solid #000;"><span><br><br><br> 1x1 <br> PICTURE</span></div>
            </div>
        </td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="10%" style="text-align: left; font-size: 12px;">Student No.</td>
        <td width="20%" style="text-align: left; border-bottom: 1px solid #000;"></td>
        <td style="width: 40%; text-align: center;">
            <div class="text-center" style="font-size: 12px;"><b>OFFICE OF THE REGISTRAR</b></div>
        </td>
        <td width="30%" style=""></td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 10px;">
    <tr>
        <td width="100%" style="text-align: left; font-size: 12px;">Official Transcript of Record</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 10px;">
    <tr>
        <td width="100%" style="text-align: center; font-size: 13px;"><b>RECORD OF CANDIDATE FOR GRADUATION FROM COLLEGIATE COURSE</b></td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px;">
    <tr>
        <td width="6%" style="text-align: left; font-size: 12px;">Name:</td>
        <td width="31.3333333333%" style="text-align: left; border-bottom: 1px solid #000;"></td>
        <td width="31.3333333333%" style="text-align: left; border-bottom: 1px solid #000;"></td>
        <td width="31.3333333333%" style="text-align: left; border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="6%" style="text-align: left; font-size: 12px;"></td>
        <td width="31.3333333333%" style="text-align: center; font-size: 12px;">(Last)</td>
        <td width="31.3333333333%" style="text-align: center; font-size: 12px;">(First)</td>
        <td width="31.3333333333%" style="text-align: center; font-size: 12px;">(Middle)</td>
    </tr>
</table>
<table style="width: 100%; table-layout: fixed; margin-top: 15px;">
    <tr>
        <td width="55%" style="vertical-align: top;">
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Home Address:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Date of Birth:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Place of Birth:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Fathe's Name:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Mother's Name:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
        </td>
        <td width="3%"></td>
        <td width="42%" style="vertical-align: top;">
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Civil Status:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Sex:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="35%" class="p-0" style="text-align: left;">Date of Admission:</td>
                    <td width="65%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="40%" class="p-0" style="text-align: left;">Admission Credential:</td>
                    <td width="60%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="17%" class="p-0" style="text-align: left;">Religion:</td>
                    <td width="83%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td width="22%" class="p-0" style="text-align: left;">Citizenship:</td>
                    <td width="78%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; margin-top: 15px;">
    <tr>
        <td width="15%" class="text-center" style="font-size: 12px;"><b>COMPLETED <br> COURSE</b></td>
        <td width="35%" class="text-center" style="font-size: 12px;"><b>NAME OF SCHOOL</b></td>
        <td width="35%" class="text-center" style="font-size: 12px;"><b>ADDRESS</b></td>
        <td width="15%" class="text-center" style="font-size: 12px;"><b>SCHOOL <br> YEAR</b></td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="font-size: 10px; padding-left: 5px!important;">Primary</td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="15%" class="text-left" style="font-size: 10px;"></td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="font-size: 10px; padding-left: 5px!important;">Intermediate</td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="15%" class="text-left" style="font-size: 10px;"></td>
    </tr>
    <tr>
        <td width="15%" class="text-left" style="font-size: 10px; padding-left: 5px!important;">Secondary</td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="35%" class="text-left" style="font-size: 10px;"></td>
        <td width="15%" class="text-left" style="font-size: 10px;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 10px;">
    <tr>
        <td width="14%" class="text-left p-0" style="font-size: 10px; padding-left: 5px!important;">Special Order ( ) No.</td>
        <td width="46%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
        <td width="12%" class="text-left p-0" style="font-size: 10px; padding-left: 5px!important;">Date of Issuance:</td>
        <td width="28%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; font-size: 10px;">
    <tr>
        <td width="15%" class="text-left p-0" style="font-size: 10px; padding-left: 5px!important;">Title/Degree Conferred.</td>
        <td width="45%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
        <td width="13%" class="text-left p-0" style="font-size: 10px; padding-left: 5px!important;">Date of Graduation:</td>
        <td width="27%" class="p-0" style="text-align: left; border-bottom: 1px solid #000;"></td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; margin-top: 5px;">
    <tr>
        <td rowspan="2" width="15%" class="text-center" style="font-size: 12px;"><b>SUBJECT <br> CODE</b></td>
        <td rowspan="2" width="35%" class="text-center" style="font-size: 12px;"><b>DESCRIPTIVE TITLE</b></td>
        <td rowspan="2" width="10%" class="text-center" style="font-size: 12px;"><b>COURSE <br> GRADE</b></td>
        <td rowspan="2" width="10%" class="text-center" style="font-size: 12px;"><b>CREDITS</b></td>
        <td colspan="9" width="30%" class="text-center" style="font-size: 12px;"><b>CREDIT DISTRIBUTION</b></td>
    </tr>
    <tr>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>1</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>2</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>3</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>4</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>5</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>6</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>7</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b>1</b></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"><b></b></td>
    </tr>
    <tr>
        <td width="15%" class="text-center" style="font-size: 11px;"></td>
        <td width="35%" class="text-center" style="font-size: 11px;"></td>
        <td width="10%" class="text-center" style="font-size: 11px;"></td>
        <td width="10%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
    <tr>
        <td width="15%" class="text-center" style="font-size: 11px;"></td>
        <td width="35%" class="text-center" style="font-size: 11px;"></td>
        <td width="10%" class="text-center" style="font-size: 11px;"></td>
        <td width="10%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;"></td>
        <td width="3.33333333333%" class="text-center" style="font-size: 11px;">&nbsp;</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px;">
    <tr>
        <td width="20%" class="text-left p-0" style="font-size: 10px; vertical-align: top; font-size: 12px;"><b>OFFICIAL GRADES:</b></td>
        <td width="80%" class="p-0" style="font-size: 10px; padding-left: 5px!important; text-align: justify!important;">1.00 (99-100), 1.25 (96-98) Excellent, 1.50 (93-95) Superior, 1.75 (90-92) 2.00 (87-89) Very Satisfactory, 2.25 (84-86), 2.50 (81-83) Satisfactory, 2.75 (78-80), Good, 3.00 (75-77) Fair, 3.25-5.00 (74-below Failure (F), DR (Dropped)FA (Failure due to absences)</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px; margin-top: 10px;">
    <tr>
        <td width="20%" class="text-left p-0" style="vertical-align: top; font-size: 12px;"><b>REMARKS:</b></td>
        <td width="80%" class="p-0"></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px; margin-top: 10px;">
    <tr>
        <td width="100%" class="text-left p-0" style="font-size: 12px; vertical-align: top; border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="100%" class="text-left p-0" style="font-size: 12px; vertical-align: top; padding-left: 40px!important;"><b>(Not valid without School Seal)</b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px; margin-top: 10px;">
    <tr>
        <td width="100%" class="text-center p-0" style="vertical-align: top; font-size: 12px;"><b>CERTIFICATION</b></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px; margin-top: 10px;">
    <tr>
        <td width="41%" class="text-left p-0" style="vertical-align: top; font-size: 13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that the foregoing records of</td>
        <td width="4%" class="text-center p-0"></td>
        <td width="36%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;"></td>
        <td width="4%" class="text-center p-0"></td>
        <td width="15%" class="text-left p-0" style="font-size: 12px">have been verified</td>
    </tr>
    <tr>
        <td width="41%" class="text-left p-0" style="font-size: 12px;">and that same are kept in the files of our school.</td>
        <td width="4%" class="text-center p-0"></td>
        <td width="36%" class="text-left p-0"></td>
        <td width="4%" class="text-center p-0"></td>
        <td width="15%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; margin-left: 5px; margin-right: 5px; margin-top: 20px;">
    <tr>
        <td width="10%" class="text-left p-0" style="font-size: 12px">Prepared by:</td>
        <td width="30%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;"></td>
        <td width="4%" class="text-center p-0"></td>
        <td width="22%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;"></td>
        <td width="4%" class="text-center p-0"></td>
        <td width="30%" class="text-left p-0" style="font-size: 12px; border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
        <td width="10%" class="text-left p-0"></td>
        <td width="30%" class="text-left p-0"></td>
        <td width="4%" class="text-center p-0"></td>
        <td width="22%" class="text-center p-0" style="font-size: 12px;">Date of Issuance</td>
        <td width="4%" class="text-center p-0"></td>
        <td width="30%" class="text-center p-0" style="font-size: 12px;">College Registrar</td>
    </tr>
</table>
</body>
</html>