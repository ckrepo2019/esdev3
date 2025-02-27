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
        @page { size: 8.5in 14in; margin: 30px 70px;}
        
    </style>
</head>
<body>
{{-- <div id="watermark">
    <img src="{{base_path()}}/public/assets/images/sait/sait_logo.png" alt="school" width="700px">
</div> --}}
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="100%" class="text-center p-0">
            <img src="{{base_path()}}/public/assets/images/sait/header2.png" alt="school" width="100%"> 
        </td>
    </tr>
</table>
<br>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left"><b>November 12, 2021</b></td>
    </tr>
</table>
<br>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left"><b>RAUL C. ALVAREZ JR., Ph.D., CESO III</b></td> 
    </tr>
    <tr>
        <td width="100%" class="text-left">Director IV</td>
    </tr>
    <tr>
        <td width="100%" class="text-left">Commission on Higher Education</td>
    </tr>
    <tr>
        <td width="100%" class="text-left">Region X</td>
    </tr>
    <tr>
        <td width="100%" class="text-left">Cagayan de Oro City 9000</td>
    </tr>
    <tr>
        <td width="100%" class="text-left">Philippines</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">Dear<b> Regional Director Alvarez:</b></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">Greetings!</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="" style="text-align: justify">This is in reference to the Memorandum from the Executive Director dated October 26, 2021 entitled “AY 2021-2022 Higher Education Data Collection”.</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">We are pleased to submit the following documents:</td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td colspan="5" class="text-left">Private HEIs, Local Universities and Colleges (LUCs), and Other Government Schools (OGS)</td>
    </tr>
    <tr>
        <td width="17.5%" class="text-left">HEI Name</td>
        <td width="15%" class="text-left">Form A</td>
        <td width="17.5%" class="text-left">Form BC</td>
        <td width="17%" class="text-left">Form E2</td>
        <td width="33%" class="text-left">List of Graduates</td>
    </tr>
    <tr>
        <td width="17.5%" class="text-left">HEI A</td>
        <td width="15%" class="text-left"></td>
        <td width="17.5%" class="text-left"></td>
        <td width="17%" class="text-left"></td>
        <td width="33%" class="text-left"></td>
    </tr>
</table>
<br>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="" style="text-align: justify">We are certifying the completeness and accuracy of this submitted information. For any questions, you may contact Blanca H. Garduque through saitcollegeregistrar@gmail.com.</td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">Thank you very much.</td>
    </tr>
</table>
<br>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">Sincerely yours,</td>
    </tr>
</table>
<br>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 15px;">
    <tr>
        <td width="100%" class="text-left">BLANCA H. GARDUQUE, LPT, DBM</td>
    </tr>
    <tr>
        <td width="100%" class="text-left">College Registrar</td>
    </tr>
</table>
</body>
</html>
