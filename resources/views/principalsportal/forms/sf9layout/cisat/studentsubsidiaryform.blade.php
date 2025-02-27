<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            width: 100%;
            margin-bottom: 0px;
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

        .td-bordered {
            border-right: 1px solid #00000;
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
        .p-1{
            padding-top: 10px!important;
            padding-bottom: 10px!important;
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
            line-height: 12px;
            height: 50px;
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
            transform-origin: 17 17;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: #cfcfcf; 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        @page {  
            margin:20px 20px;
            
        }
        
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 22.2cm 28.6cm; margin: 20px 1.5cm 1.4cm;}
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="7%" class="text-left"><img style="" src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="50px"></td>
        {{-- <td width="95%" class="text-center" style="font-size: 20px;"></td> --}}
        <td width="93%" class="text-center p-0">
            <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-left p-0" style="font-size: 20px;"><b>&nbsp;&nbsp;&nbsp;CHILDREN'S INTEGRATED SCHOOL OF ALTA TIERRA, INC.</b></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 p-0" style="table-layout: fixed;">
                <tr>
                    <td width="85%" class="p-0" style="font-size: 20px;">
                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size: 14.7px;"><b>(Formerly Children's Center Grade School)</b></td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size: 10px;">PHASE V, ALTA TIERRA VILLAGE, JARO, ILOILO CITY</td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size: 10.5px;"><img style="" src="{{base_path()}}/public/assets/images/cisat/telephonedesign.png" alt="school" width="10px">&nbsp;-(033) 320-6303 / 330-8231</td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center p-0" style="font-size: 14.7px; margin-top: 5px!important;"><b>STUDENT'S SUBSIDIARY LEDGER</b></td>
                            </tr>
                        </table>
                    </td>
                    <td width="15%" class="p-0">
                        <div style="border: 1px solid #000; width: 1in; height: 1in;">
                            <br><br>
                            <span style="font-size: 12px; padding-top: 20px!important;">1 x 1 <br> PICTURE</span>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed;">
    <tr>
        <td width="5%" style="font-size: ">LRN:</td>
        <td width="45%" class="text-left" style="border-bottom: 1px solid #000;"></td>
        <td width="50%"></td>
    </tr>
</table>
</body>
</html>