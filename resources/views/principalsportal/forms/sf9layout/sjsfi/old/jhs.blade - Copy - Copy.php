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
        
		
		 .check_mark {
               font-family: ZapfDingbats, sans-serif;
            }
        @page { size: 4in 8.5in; margin: 10px 15px;  }
        
    </style>
</head>
<body>

<table class="table" style="width: 100%; table-layout: fixed;">
    <tr>
        <td width="20%" style="text-align: left;">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px">
        </td>
        <td style="width: 60%; text-align: center; vertical-align: middle;">
            <span class="p-0" style="width: 100%; font-weight: bold; font-size: 12px;">{{$schoolinfo[0]->schoolname}}</span>
            <span class="p-0" style="width: 100%; font-size: 12px; margin-top: 7px;">Basic Education Department</span>
        </td>
        <td width="20%"></td>
    </tr>
</table>

<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td class="text-center">ssss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td class="text-center">ssss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="20%" class="text-center">ssss</td>
            <td width="35%" class="text-center"></td>
            <td width="10%" class="text-center">ssss</td>
            <td width="35%" class="text-center"></td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm mb-0 p-0" style="table-layout: fixed; border: 1px solid #000;">
        <tr>
            <td width="40%" class="text-center">sss</td>
            <td width="30%" class="text-center">sss</td>
            <td width="15%" class="text-center">sss</td>
            <td width="15%" class="text-center">sss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center">ssss</td>
            <td width="50%" class="text-center"></td>
            <td width="10%" class="text-center">ssss</td>
            <td width="15%" class="text-center"></td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    {{-- <table width="100%" class="table mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center td-bordered">ssss</td>
            <td width="75%" class="text-center"></td>
        </tr>
    </table> --}}
    <table class="table table-sm table-bordered grades" width="100%">
        <thead>
            <tr>
                <td width="25%" class="align-middle text-center"><b>Subjects</b></td>
                <td width="11.25%" class="text-center align-middle">sss</td>
                <td width="11.25%" class="text-center align-middle">sss</td>
                <td width="11.25%" class="text-center align-middle">sss</td>
                <td width="11.25%" class="text-center align-middle">sss</td>
                <td width="15%" class="text-center align-middle">sss</td>
                <td width="15%" class="text-center align-middle">sss</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($studgrades as $item)
                <tr>
                    <td class="text-center align-middle" style="" >sss</td>
                    <td class="text-center align-middle">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td class="text-center align-middle">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    <td class="text-center align-middle">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                    <td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                </tr>
            @endforeach
            {{-- <tr>
                <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                <td class="text-center {{collect($finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{collect($finalgrade)->first()->finalrating}}</td>
                <td class="text-center" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
            </tr> --}}
        </tbody>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center">ssss</td>
            <td width="22.50%" class="text-center"></td>
            <td width="22.50%" class="text-left">ssss</td>
            <td width="30%" class="text-left">ssss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center">ssss</td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
        </tr>
    </table>
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center">ssss</td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
        </tr>
    </table>
    <table width="100%" class="table table-sm table-bordered mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="25%" class="text-center">ssss</td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="11.25%" class="text-center"></td>
            <td width="11.25%%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
            <td width="15%" class="text-center"></td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm mb-0 p-0" style="table-layout: fixed; border: 1px solid #000">
        <tr>
            <td width="70%" class="text-center">ssss</td>
            <td width="30%" class="text-center">ssss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="36.25%" class="text-center">ssss</td>
            <td width="63.75%" class="text-center">ssss</td>
        </tr>
    </table>
</div>
<div width="100%" style="">
    <table width="100%" class="table table-sm mb-0 p-0" style="table-layout: fixed;">
        <tr>
            <td width="" class="text-right">ssss</td>
            <td width="" class="text-right">ssss</td>
            <td width="" class="text-right">ssss</td>
            <td width="" class="text-right">ssss</td>
        </tr>
    </table>
</div>
{{-- <table width="100%" class="table table-bordered mb-0 p-0" style="table-layout: fixed;">
    <tr>
        <td class="text-center">ssss</td>
    </tr>
</table>
<table width="100%" class="table table-bordered mb-0 p-0" style="table-layout: fixed;">
    <tr>
        <td class="text-center">ssss</td>
    </tr>
</table> --}}
</body>
</html>