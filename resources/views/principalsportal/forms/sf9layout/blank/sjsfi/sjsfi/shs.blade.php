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
        @page { size: 4in 8.5in; margin: 15px 20px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid black;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 100%; text-align: center; vertical-align: middle; border-bottom: 1px solid #000;">
                        <div class="p-0" style="width: 100%; font-weight: bold; font-size: 14px;">{{$schoolinfo[0]->schoolname}}</div>
                        <div class="p-0" style="width: 100%; font-size: 14px;">Basic Education Department</div>
                        <div class="p-0" style="width: 100%;"><img style="padding-top: 4px;" src="{{base_path()}}/public/assets/images/sjsfi/logo.png" alt="school" width="80px"></div>
                        <div class="p-0" style="width: 100%; font-size: 14px;">sss</div>
                    </td>
                </tr>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 100%; border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="14%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="86%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
                <tr>
                    <td width="14%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="86%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 100%; border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="14%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="58%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                    <td width="8%" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-left: 1px solid #000;">&nbsp;</td>
                    <td width="20%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="14%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="86%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
            </table>
            <table class="table table-sm table-bordered grades" width="100%" style="border-left: none!important;border-right: none!important;">
                <thead>
                    <tr>
                        <td width="20%" class="align-middle text-center"><b>Subjects</b></td>
                        <td width="12.5%" class="text-center align-middle">sss</td>
                        <td width="12.5%" class="text-center align-middle">sss</td>
                        <td width="12.5%" class="text-center align-middle">sss</td>
                        <td width="12.5%" class="text-center align-middle">sss</td>
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
                            <td class="text-center align-middle"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="20%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="25%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="12.5%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="12.5%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="15%" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="15%" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
                <tr>
                    <td colspan="6" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
                <tr>
                    <td colspan="6" style="border-bottom: 1px solid #000;">&nbsp;</td>
                </tr>  
                <tr>
                    <td colspan="6" class="text-center" style="border-bottom: 1px solid #000;">sss</td>
                </tr>  
            </table>
        </td>
    </tr>
</table>
<table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid black;">
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0" style="table-layout: fixed;">
            <tr>
                <td width="100%" class="text-center" style="border-bottom: 1px solid #000;">sss</td>
            </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">sss</td>
                    <td width="75%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr> 
                <tr>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">sss</td>
                    <td width="75%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr>  
                <tr>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">sss</td>
                    <td width="75%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr>  
                <tr>
                    <td width="25%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">sss</td>
                    <td width="75%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr>   
            </table> 
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center" style="border-bottom: 1px solid #000;">sss</td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="100%" class="text-center" style="border-bottom: 1px solid #000;">
                        <div style="height: 130px;">

                        </div>
                    </td>
                </tr>  
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="13%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="8%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="79%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr>  
                <tr>
                    <td width="13%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="8%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">&nbsp;</td>
                    <td width="79%" class="text-center" style="border-bottom: 1px solid #000;"></td>
                </tr> 
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="9%" class="text-center" style="border-bottom: 1px solid #000; border-right: 1px solid #000;">
                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: 3em!important;">sss</td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: 1.5em!important;">sss</td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: 1.5em!important;">sss</td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: 1.5em!important; margin-bottom: 3em!important;">sss</td>
                            </tr>
                        </table>
                    </td>
                    <td width="81%" class="text-center p-0" style="border-bottom: 1px solid #000;">
                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="60%" class="text-center" style="vertical-align: bottom!important;">
                                    <table class="table table-sm mb-0" style="table-layout: fixed;">
                                        <tr>
                                            <td width="100%" class="text-center" style="">sss</td>
                                        </tr>
                                        <tr>
                                            <td width="100%" class="text-center" style="">sss</td>
                                        </tr>
                                    </table>
                                </td>
                                <td width="40%" class="text-center" style="border-bottom: 1px solid #000; border-left: 1px solid #000;">
                                    <table class="table table-sm mb-0" style="table-layout: fixed;">
                                        <tr>
                                            <td width="100%" class="text-center" style="margin-top: 1em!important;">sss</td>
                                        </tr>
                                        <tr>
                                            <td width="100%" class="text-center" style="margin-top: .5em!important; margin-bottom: 1em!important;">sss</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" width="100%" class="text-left" style="padding-left: 4.6em;">ssss</td>
                            </tr>
                        </table>
                        <table class="table table-sm mb-0" style="table-layout: fixed;">
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: .5em!important;">
                                    sss
                                </td>
                            </tr>
                            <tr>
                                <td width="100%" class="text-center" style="margin-top: 1em!important;">
                                    sss
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table> 
        </td>
    </tr>
</table>

</body>
</html>