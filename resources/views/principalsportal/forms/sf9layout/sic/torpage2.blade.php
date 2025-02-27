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
        @page { size: 8.5in 11in; margin: 10px 20px;  }
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="100%" class="text-center">
            <div width="100%" style="font-size: 17px;"><b>City of Malaybalay</b></div>
            <div width="100%" style="font-size: 11px; margin-top: 10px;"><i>OFFICE OF THE REGISTRAR</i></div>
            <div width="100%" style="font-size: 11px;">OFFICIAL TRANSCRIPT OF RECORDS</div>
            <div width="100%" style="font-size: 11px;">PAGE 2</div>
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 15px;">
    <tr>
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
        <td colspan="4" class="text-center" style="font-size: 11px;"><b>BUKIDNON STATE UNIVERSITY - Malaybalay City</b></td>
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
        <td colspan="2" class="text-left" style="font-size: 11px;">SUMMER: 2017</td>
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
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">HA</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Health Assessment</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">DRP</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">0</td>
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
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">HA RLE</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Health Assessment RLE</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">DRP</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">0</td>
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
    </tr>    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">PHILO 101</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Logic and Critical Thinking</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">DRP</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">0</td>
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
    </tr>    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">PHYSICS 100A</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Introduction to Physics, Lec</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">DRP</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">0</td>
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
    </tr>    <tr>
        <td width="15%" class="text-left" style="font-size: 11px; padding-left: 5px;">PHYSICS 100B</td>
        <td width="42%" class="text-left" style="font-size: 11px; padding-left: 5px;">Introduction to Physics, Lab</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">DRP</td>
        <td width="6.5%" class="text-center" style="font-size: 11px;">0</td>
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
        <td colspan="4" class="text-left" style="font-size: 11px;"><b>TOTAL NUMBER OF CREDITS EARNED</b></td>
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
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 13px!important; margin-top: 10px;">
    <tr>
        <td width="100%" class="text-center">LEGEND FOR GROUP CREDIT DISTRIBUTION</td>
    </tr>
</table>
<table width="100%" class="" style="table-layout: fixed; font-size: 10px!important; margin-top: 10px">
    <tr>
        <td width="100%" class="text-center">1. English; 2. nat Science; 3. Mathematics; 4. Social Science; 5. Professional; 6. Filipino; 7. Elective; 8. ROTC & P.E</td>
    </tr>
</table>
</body>
</html>