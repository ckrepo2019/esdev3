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
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
            
        }
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 13in 8.5in; margin: .25in .25in;}
        
    </style>
</head>
<body>
<table width="100%" style="table-layout: fixed;">
    <tr>
        <td width="33%" style="padding-right: .25in!important">
            <table width="100%" style="background-color: green; ">
                <tr>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                    <td width="50%" class="text-center" style="font-size: 11px;">
                        <span>Republic of the Philippines</span><br>
                        <span>Department of Education</span><br>
                        <span>REGION XI</span><br>
                        <span>Division of Davao Oriental</span><br>
                        <span>MANAY DISTRICT</span>
                    </td>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="100%" class="text-center">
                        <span>MARYKNOLL SCHOOL OF MANAY INC.</span><br>
                    </td>
                </tr>
            </table>
        </td>
        <td width="33%">
            <table width="100%" style="background-color: blue; ">
                <tr>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                    <td width="50%" class="text-center" style="font-size: 11px;">
                        <span>Republic of the Philippines</span><br>
                        <span>Department of Education</span><br>
                        <span>REGION XI</span><br>
                        <span>Division of Davao Oriental</span><br>
                        <span>MANAY DISTRICT</span>
                    </td>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="100%" class="text-center">
                        <span>MARYKNOLL SCHOOL OF MANAY INC.</span><br>
                    </td>
                </tr>
            </table>
        </td>
        <td width="34%" style="padding-left: .25in!important;">
            <table width="100%" style="background-color: gray; ">
                <tr>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                    <td width="50%" class="text-center" style="font-size: 11px; background-color: green;">
                        <span>Republic of the Philippines</span><br>
                        <span>Department of Education</span><br>
                        <span>REGION XI</span><br>
                        <span>Division of Davao Oriental</span><br>
                        <span>MANAY DISTRICT</span>
                    </td>
                    <td width="25%" class="text-center">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="100%" class="text-center">
                        <span><b>MARYKNOLL SCHOOL OF MANAY INC.</b></span><br>
                    </td>
                </tr>
                <tr>
                    <td width="100%" class="text-center">
                        <img src="{{base_path()}}/public/assets/images/" alt="school" width="70px">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table style="width: 100%; table-layout: fixed;">
    <tr>
        
    </tr>
</table>
</body>
</html>