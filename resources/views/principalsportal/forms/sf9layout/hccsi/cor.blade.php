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
        @page { size: 8.5in 11in; margin: 10px 15px;  }
        
    </style>
</head>
<body>
<table style="width: 100%; table-layout: fixed;" class="">
    <tr>
        <td width="25%" class="text-center" style="vertical-align: middle;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px">
        </td>
        <td width="50%" class="text-center" style="vertical-align: middle; font-size: 13px;">
            <span><b>{{$schoolinfo[0]->schoolname}}</b></span><br>
            <span>Km. 9 Sasa, Davao City</span><br>
            <span><b>Records and Admission Office</b></span><br><br>
            <span><b>CERTIFICATE OF REGISTRATION</b></span><br>
        </td>
        <td width="25%"></td>
    </tr>
</table>

<table width="100%" class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid #000; margin-top: 5px;">
    <tr>
        <td class="text-center" style="font-size: 12px;"><b>[1000808433] LARONG, SILVESTRE JR. ORACION</b></td>
    </tr>
    <tr>
        <td class="text-center" style="font-size: 10px;">Bachelor of Science in Business Administration Major in Marketing Management, Fourth Year</td>
    </tr>
    <tr>
        <td class="text-center" style="font-size: 11px;">AY {{$schoolyear->sydesc}} 2nd Semester</td>
    </tr>
</table>


<table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important; background-color: #32038f; color: #fff;">
    <tr>
        <th width="7.5%" class="text-left" style="padding-left: 5px;">Class</th>
        <th width="12.5%" class="text-left" style="">Subj. Code</th>
        <th width="30%" class="text-left" style="">Subject Title</th>
        <th width="5%" class="text-center" style="">Units</th>
        <th width="18.5%" class="text-left" style="">Schedule</th>
        <th width="18.5%" class="text-left" style="">Instructor</th>
        <th width="8%" class="text-left" style="">Room</th>
    </tr>
</table>
<table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;border: 1px solid #000;!important">
    <tr>
        <td width="7.5%" class="text-left" style="padding-left: 5px;">5356</td>
        <td width="12.5%" class="text-left" style="">Prac</td>
        <td width="30%" class="text-left" style="">Practicum</td>
        <td width="5%" class="text-center" style="">6.0</td>
        <td width="18.5%" class="text-left" style=""></td>
        <td width="18.5%" class="text-left" style="">SABORNIDO, Jean Teresita S.</td>
        <td width="8%" class="text-left" style="">Room 104</td>
    </tr>
</table>
<table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
    <tr>
        <td width="100%">
            <span>This is your official certificate of registration. Please check and verify thoroughly the correctness of these data. If you have question or verification on.</span><br>
            <span>the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE or you may call us at +63 82 2330013</span>
        </td>
    </tr>
</table>
<br>
<table width="100%" class="table table-sm mb-0 mt-0" style="table-layout: fixed; font-size: 9px;">
    <tr>
        <td center="p-0" width="60%"></td>
        <td class="text-center p-01" width="20%"><b>CHRISTINE J. CASILAGAN</b></td>
        <td center="p-0" width="20%"></td>
    </tr>
    <tr>
        <td center="p-0" width="60%"></td>
        <td class="text-center p-0" width="20%"><b>Registar</b></td>
        <td center="p-0" width="20%"></td>
    </tr>
</table>
</body>
</html>