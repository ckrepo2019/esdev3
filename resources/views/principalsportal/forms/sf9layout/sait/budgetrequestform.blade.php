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
        @page { size: 8.5in 11in; margin: 40px 70px;}
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="15%" class="text-left p-0" style="vertical-align: top;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px">
        </td>
        <td width="65%" class="text-left p-0" style="vertical-align: middle;">
            <div style="width: 100%; font-weight: bold; font-size: 15px; margin-top: 10px; letter-spacing: 1px;"><b>SAN AGUSTIN INSTITUTE OF TECHNOLOGY</b></div>
            <div style="width: 100%;font-size: 13px;">Fr. Manlio Caroselli St., Valencia City, Bukidnon</div>
            <div style="width: 100%; font-size: 13px;">Telephone Number : 828-1499 | Email Add : <span style="color: rgb(26, 137, 206)"><u>saitvalencia1960@gmail.com</u></span></div>
            <div style="width: 100%; font-size: 13px;">Website : <span style="color: rgb(26, 137, 206)">https://www.sait.edu.ph</span></div>
        </td>
        <td width="20%" class="text-left p-0" style="vertical-align: middle;">
            <img src="{{base_path()}}/public/assets/images/sait/sait_iso.png" alt="school" width="130px">
        </td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; border-top: 5px solid rgb(26, 137, 206);">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; border-top: 1px solid rgb(26, 137, 206); margin-top: 2px;">
    <tr>
        <td></td>
    </tr>
</table>
<table width="100%" style="table-layout: fixed; margin-top: 10px; font-size: 16px;">
    <tr>
        <td width="100%" class="text-center"><b>BUDGET REQUEST FORM</b></td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; margin-top: 10px; font-size: 16px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="28%" class="text-left p-0"><b>&nbsp;Requesting Person:</b> <br>&nbsp;</td>
        <td width="72%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="28%" class="text-left p-0"><b>&nbsp;Department:</b></td>
        <td width="72%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="28%" class="text-left p-0"><b>&nbsp;Date:</b></td>
        <td width="72%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="28%" class="text-left p-0"><b>&nbsp;Purpose:</b><br>&nbsp; <br> <b>&nbsp;Place:</b> <br><b>&nbsp;Date:</b></td>
        <td width="72%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" class="table-bordered" style="table-layout: fixed; margin-top: 30px; font-size: 16px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td colspan="2" style="text-align: justify;"><b>Faculty and Staff Development</b> (Includes travel or reimbursement for training or courses)</td>
    </tr>
    <tr>
        <td width="50%" class="text-center p-0"><b>Description</b></td>
        <td width="50%" class="text-center p-0"><b>Cost</b></td>
    </tr>
    <tr>
        <td width="50%" class="text-left p-0">&nbsp;Registration Fee (seminar/webinar)</td>
        <td width="50%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="50%" class="text-left p-0">&nbsp;Membership-Institutional</td>
        <td width="50%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="50%" class="text-left p-0">&nbsp;Fare/Fuel</td>
        <td width="50%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="50%" class="text-left p-0">&nbsp;Accommodation</td>
        <td width="50%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="50%" class="text-left p-0">&nbsp;Meals</td>
        <td width="50%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="50%" class="text-center p-0"><b>TOTAL COST</b></td>
        <td width="50%" class="text-center p-0"></td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 30px; font-size: 16px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="100%" class="text-left p-0">Prepared by:</td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 30px; font-size: 16px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="40%" class="text-center p-0" style="border-bottom: 1px solid #000;">&nbsp;</td>
        <td width="60%" class="text-center p-0" style=""></td>
    </tr>
    <tr>
        <td width="40%" class="text-left p-0" style=""><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Faculty/Staff</i></td>
        <td width="60%" class="text-center p-0" style=""></td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 20px; font-size: 16px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="100%" class="text-left p-0">Noted by:</td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 30px; font-size: 13px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="30%" class="text-center p-0" style="border-bottom: 1px solid #000;">GLADY G. EROISA, MS-CAR</td>
        <td width="25%" class="text-left p-0"></td>
        <td width="35%" class="text-center p-0" style="border-bottom: 1px solid #000;"></td>
        <td width="10%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="30%" class="text-center p-0"><i>HRMD-Officer</i></td>
        <td width="25%" class="text-left p-0"></td>
        <td width="35%" class="text-center p-0"><i>Department Head</i></td>
        <td width="10%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 20px; font-size: 13px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="100%" class="text-left p-0">Approved by:</td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 30px; font-size: 13px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="33%" class="text-center p-0" style="border-bottom: 1px solid #000;">SR. ELIZABETH BAG-AO, MCM</td>
        <td width="25%" class="text-left p-0"></td>
        <td width="35%" class="text-center p-0" style="border-bottom: 1px solid #000;">SR. TERESITA S. SAJELAN, MCM</td>
        <td width="7%" class="text-left p-0"></td>
    </tr>
    <tr>
        <td width="33%" class="text-center p-0"><i>VP for Admin and Finance</i></td>
        <td width="25%" class="text-left p-0"></td>
        <td width="35%" class="text-center p-0"><i>School President</i></td>
        <td width="7%" class="text-left p-0"></td>
    </tr>
</table>
<table width="100%" class="table" style="table-layout: fixed; margin-top: 60px; font-size: 14px; margin-left: 20px; margin-right: 40px;">
    <tr>
        <td width="100%" class="text-left p-0">HRO-FO-O12 (001) (01-2021)</td>
    </tr>
</table>
</body>
</html>
