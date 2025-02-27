<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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

        @font-face {
            font-family: chinese;
            src: url('{{base_path()}}/public/DroidSansFallback.ttf') format('truetype');
        }
        .chineseLanguage { font-family: chinese; }
        body {font-family: DejaVu Sans, sans-serif;}
        
        @page { size: 4in 8.5in; margin: 15px 20px;  }
        
    </style>
</head>
<body style="">
<table class="table table-sm" style="table-layout: fixed;">
    <tr>
        <td class="p-0">
            {{-- <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td style="width: 20%; text-align: center; vertical-align: middle; border-bottom: 1px solid #000;">
                        <div class="p-0" style="width: 100%;"><img style="padding-top: 4px;" src="{{base_path()}}/public/assets/images/sjsfi/logo.png" alt="school" width="60px"></div>
                    </td>
                    <td style="width: 60%; text-align: center; vertical-align: middle; border-bottom: 1px solid #000; margin-top: 10px;">
                        <div class="p-0" style="width: 100%; font-weight: bold; font-size: 14px;"></div>
                        <div class="p-0" style="width: 100%; font-size: 14px;">Basic Education Department</div>
                    </td>
                    <td style="width: 20%; text-align: center; vertical-align: middle; border-bottom: 1px solid #000;">
                    </td>
                </tr>
            </table> --}}
            <table class="table table-sm" width="100%" style="margin-bottom:10px !important">
                <tr>
                    <td width="30%" class="text-right chineseLanguage align-middle  p-0" rowspan="2"  ><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="60px"></td>
                    <td width="40%" class="text-center chineseLanguage align-middle p-0" style="font-size:1rem !important;">三 寶 顏 巿</td>
                    <td width="30%" class="text-center chineseLanguage align-middle  p-0" rowspan="2"></td>
                </tr>
                <tr>
                      <td class="text-center chineseLanguage align-middle  p-0" style="font-size:1rem !important;">忠 義   中   學</td>
                </tr>
               
          </table>
        </td>
    </tr>
    <tr>
       
           
            
                
                      

                <td class="p-0" style="border: 1px solid black;">
                      <table class="table table-sm" width="100%">
                            <tr>
                                <td width="100%" class="text-center chineseLanguage align-middle"  style="font-size:1.2rem !important; border-bottom: 1px solid #000;">學 生 成 績 報 告 表</td>
                            </tr>
                      </table>
          
           
                      <table class="table table-sm mb-0" width="100%">
                            <tr>
                                  <td width="10%" class="text-center  align-middle" style="border-bottom: 1px solid #000;"><span class="chineseLanguage">二</span> O</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$geninfo[0]->systart}}</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">年</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$geninfo[0]->monthstart}}</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">月到</td>
                                  <td width="10%" class="text-center  align-middle" style="border-bottom: 1px solid #000;"><span class="chineseLanguage">二</span> O</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">年</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$geninfo[0]->monthend}}</td>
                                  <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">月</td>
                            </tr>
                      </table>
             
            
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                        <td width="12%" class="text-center chineseLanguage align-middle" style="border: 1px solid #000;">姓名</td>
                        <td width="37%" class="text-center chineseLanguage align-middle" style="border: 1px solid #000;">{{$studinfo[0]->studname}}</td>
                        <td width="12%" class="text-center chineseLanguage align-middle" style="border: 1px solid #000;">西名</td>
                        <td width="37%" class="text-center align-middle" style="border: 1px solid #000; font-size:.5rem !important">{{$eng_studinfo[0]->student}}</td>
                </tr>
            </table>
               
            <table class="table table-sm mb-0 " width="100%">
                <tr >
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$studinfo[0]->gradelevel}}</td>
                        <td width="20%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">學部第</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$studinfo[0]->year}}</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">年級</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$studinfo[0]->group}}</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">組</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">{{$studinfo[0]->classnumber}}</td>
                        <td width="10%" class="text-center chineseLanguage align-middle" style="border-bottom: 1px solid #000;">號</td>
                </tr>
            </table>
              
        
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="20%" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding-top: 10px!important; padding-bottom: 10px!important;" class="chineseLanguage text-center align-middle">生日</td>
                    <td width="57%" style="border-bottom: 1px solid #000;" class=" align-middle">{{$studinfo[0]->birthday}}</td>
                    <td width="8%" style="border-bottom: 1px solid #000; border-right: 1px solid #000; border-left: 1px solid #000;" class="chineseLanguage text-center align-middle">性别</td>
                    <td width="15%" style="border-bottom: 1px solid #000;" class="chineseLanguage align-middle">{{$studinfo[0]->sex}}</td>
                </tr> 
            </table>
            <table class="table table-sm mb-0" style="table-layout: fixed;">
                <tr>
                    <td width="20%" style="border-bottom: 1px solid #000; border-right: 1px solid #000; padding-top: 10px!important; padding-bottom: 10px!important;" class="chineseLanguage text-center align-middle">住址</td>
                    <td width="80%" style="border-bottom: 1px solid #000;" class=" align-middle">{{$studinfo[0]->address}}</td>
                </tr> 
            </table>
            <table class="table table-sm table-bordered grades" width="100%" style="border-left: none!important;border-right: none!important;">
                <thead>
                    <tr>
                        <td width="30%" class="align-middle text-center " ><span class="chineseLanguage">考次</span> / <span class="chineseLanguage">成績</span> / <span class="chineseLanguage">科目</span</td>
                        <td width="10%" class="text-center align-middle chineseLanguage">一</td>
                        <td width="10%" class="text-center align-middle chineseLanguage">二</td>
                        <td width="10%" class="text-center align-middle chineseLanguage">三</td>
                        <td width="10%" class="text-center align-middle chineseLanguage">四</td>
                        <td width="15%" class="text-center align-middle chineseLanguage">平均</td>
                        <td width="15%" class="text-center align-middle chineseLanguage">積分</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chinesesubjects as $item)
                        @php
                            $temp_studgrades = collect($studgrades)->where('subjid',$item->id)->values();
                            if(count($temp_studgrades) == 0){
                                $temp_studgrades = array((object)[
                                    'q1'=>null,
                                    'q2'=>null,
                                    'q3'=>null,
                                    'q4'=>null,
                                    'fr'=>null
                            ]);
                            }
                        @endphp

                        <tr>
                            <td width="30%" class="text-center align-middle chineseLanguage">{{$item->subject}}</td>
                            <td width="10%" class="text-center align-middle">{{$temp_studgrades[0]->q1}}</td>
                            <td width="10%" class="text-center align-middle">{{$temp_studgrades[0]->q2}}</td>
                            <td width="10%" class="text-center align-middle">{{$temp_studgrades[0]->q3}}</td>
                            <td width="10%" class="text-center align-middle">{{$temp_studgrades[0]->q4}}</td>
                            <td width="15%" class="text-center align-middle">{{$temp_studgrades[0]->fr}}</td>
                            <td width="15%" class="text-center align-middle chineseLanguage"></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                      <td width="30%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">全  級 人  數</td>
                      <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                      <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                      <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">名次</td>
                      <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                      <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">總平均</td>
                      <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                </tr>
          </table>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="30%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">上  課   日</td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="30%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">缺  席   日</td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="30%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;">操 行</td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                    <td width="15%" class="text-center align-middle chineseLanguage" style="border: 1px solid #000;"></td>
                </tr>
            </table>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="20%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="30%" class="text-center align-middle chineseLanguage" >該生下學年應升留在</td>
                    <td width="30%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="20%" class="text-center align-middle chineseLanguage" >年級肄業</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="p-0">
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="20%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="20%" class="text-center align-middle chineseLanguage" >級任:</td>
                    <td width="20%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="20%" class="text-center align-middle chineseLanguage" >校長:</td>
                    <td width="20%" class="text-center align-middle chineseLanguage" ></td>
                </tr>
            </table>
            <br>
            <table class="table table-sm mb-0 " width="100%">
                <tr>
                    <td width="15%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="15%" class="text-center align-middle" ><span class="chineseLanguage">二</span> O</td>
                    <td width="10%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="10%" class="text-center align-middle chineseLanguage">年</td>
                    <td width="10%" class="text-center align-middle chineseLanguage"></td>
                    <td width="10%" class="text-center align-middle chineseLanguage">月</td>
                    <td width="15%" class="text-center align-middle chineseLanguage" ></td>
                    <td width="15%" class="text-center align-middle chineseLanguage">日</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
{{-- <table class="table table-sm mb-0" style="table-layout: fixed; border: 1px solid black;" hidden>
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
</table> --}}
</body>
</html>