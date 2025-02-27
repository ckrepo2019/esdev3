<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$section_info->sectionname}}</title>
    <style>

        .page_break { page-break-before: always; }
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
            font-size: 9px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        @page {  
            margin:0;
            
        }
        body { 
            margin:0;
            
        }

        @page { size: 5.5in 8.5in; margin: 50px;  }
        
    </style>
</head>
<body>  
    @foreach($enrolledstud as $item)
        <table class="table table-sm grades " width="100%">
            <thead>
                <tr>
                    <td width="30%" rowspan="2" class="text-right align-middle"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="60px"></td>
                    <td width="70%" class="p-0" ><h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3></td>
                </tr>
                <tr>
                    <td class="p-0">{{$schoolinfo[0]->address}}</td>
                </tr>
            </thead>
        </table>
        <table class="table table-bordere table-sm grades" width="100%">
            <thead>
                <tr>
                    <td width="100%" class="p-0 text-center" style="font-size: 15px !important"><b>
                            @if($gradelevel->acadprogid == 2)
                                KINDERGARTEN
                            @elseif($gradelevel->acadprogid == 3)
                                GRADE SCHOOL
                            @elseif($gradelevel->acadprogid == 4)
                                HIGH SCHOOL
                            @elseif($gradelevel->acadprogid == 5)
                                SENIOR HIGH SCHOOL
                            @endif
                        REPORT CARD
                    </b></td>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-sm grades" width="100%">
            <thead>
                <tr>
                    <td width="70%">
                        <b>NAME: {{$item->fullname}}</b>
                    </td>
                    <td width="30%"><b>GRADE: {{$gradelevel->levelname}}</b></td>
                </tr>
                <tr>
                    <td width="50%"><b>GENDER: {{$item->gender}}</b></td>
                    <td width="50%"><b>SCHOOL YEAR: {{$schoolyear->sydesc}}</b></td>
                </tr>
                <tr>
                    <td colspan="2"><b>LRN: {{$item->lrn}}</b></td>
                </tr>
            </thead>
        </table>
        <table class="table table-bordered table-sm grades" width="100%">
            <thead>
                <tr>
                    <td rowspan="2"  class="align-middle text-center" width="40%"><b>SUBJECTS</b></td>
                    <td colspan="4"  class="text-center align-middle"><b>PERIODIC RATINGS</b></td>
                    <td rowspan="2"  class="text-center align-middle"><b>Final Rating</b></td>
                    <td rowspan="2"  class="text-center align-middle"><b>Action Takent</b></span></td>
                </tr>
                <tr>
                    <td class="text-center align-middle" width="10%"><b>1</b></td>
                    <td class="text-center align-middle" width="10%"><b>2</b></td>
                    <td class="text-center align-middle" width="10%"><b>3</b></td>
                    <td class="text-center align-middle" width="10%"><b>4</b></td>
                </tr>
            </thead>
            
            
            <tbody>
                @foreach ($item->grades as $item_grade)
                    <tr>
                        <td style="padding-left:{{$item_grade->subjCom != null ? '2rem':'.25rem'}}" >{{$item_grade->subjdesc!=null ? $item_grade->subjdesc : null}}</td>
                        <td class="text-center align-middle">{{$item_grade->quarter1 != null ? $item_grade->quarter1:''}}</td>
                        <td class="text-center align-middle">{{$item_grade->quarter2 != null ? $item_grade->quarter2:''}}</td>
                        <td class="text-center align-middle">{{$item_grade->quarter3 != null ? $item_grade->quarter3:''}}</td>
                        <td class="text-center align-middle">{{$item_grade->quarter4 != null ? $item_grade->quarter4:''}}</td>
                        <td class="text-center align-middle">{{isset($item_grade->finalrating) ? $item_grade->finalrating:''}}</td>
                        <td class="text-center align-middle">{{isset($item_grade->actiontaken) ? $item_grade->actiontaken:''}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right" colspan="5">GENERAL AVERAGE</td>
                    <td class="text-center {{collect($item->finalgrade)->first()->quarter1 < 75 ? 'bg-red':''}}">{{collect($item->finalgrade)->first()->finalrating}}</td>
                    <td class="text-center" style="font-size: 8px !important">{{collect($item->finalgrade)->first()->actiontaken}}</td>
                </tr>
            </tbody>
        </table>  
        <table class="table table-bordered table-sm grades" width="100%">
            <thead>
                <tr>
                    <td width="40%" class="align-middle text-center">MONTH</td>
                    <td width="15%" class="align-middle text-center">DAYS IN SCHOOL</td>
                    <td width="15%" class="align-middle text-center">DAYS PRESENT</td>
                    <td width="15%" class="align-middle text-center">DAYS ABSENT</td>
                    <td width="15%" class="align-middle text-center">TIMES TARDY</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($item->attendance as $att_item)
                    <tr>
                        <td class="text-center align-middle" >{{\Carbon\Carbon::create(null, $att_item->month)->isoFormat('MMMM')}}</td>
                        <td class="text-center align-middle" >{{$att_item->days}}</td>
                        <td class="text-center align-middle" >{{$att_item->present}}</td>
                        <td class="text-center align-middle" >{{$att_item->absent}}</td>
                        <td class="text-center align-middle" >0</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="page_break"></div>
        <table  class="table table-sm grades mb-0" width="100%">
            <tr>
                <th class="text-center ">REPORT ON LEARNER'S OBSERVED VALUES</th>
            </tr>
        </table>
        

        <table class="table table-sm grades" width="100%" style="margin-top:20px !important">
            <tbody>
                <tr>
                    <td width="100%"><b>Promoted to</b> / Retained in : <u>{{$gradelevel->levelname}}</u></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-sm grades" width="100%" style="margin-top:10px !important">
            <tbody>
                <tr>
                    <td width="100%">Eligible for Transfre and Admission to: <u>{{$gradelevel->levelname}}</u></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-sm grades" width="100%" style="margin-top:20px !important">
            <tbody>
                <tr>
                    <td width="100%">Date: <u>{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</u></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-sm grades" width="100%" style="margin-top:40px !important">
            <tbody>
                <tr>
                    <td width="50%" class="text-center align-middle"><u>{{$adviser}}</u></td>
                    <td width="50%"></td>
                </tr>
                <tr>
                    <td class="text-center align-middle">Class Adviser</td>
                    <td ></td>
                </tr>
            </tbody>
        </table>
        <table class="table table-sm grades" width="100%" style="margin-top:40px !important">
            <tbody>
                <tr>
                    <td width="1000%" class="text-center align-middle"><u>{{$principal_info[0]->name}}</u></td>
                </tr>
                <tr>
                    <td class="text-center align-middle">{{$principal_info[0]->title}}</td>
                </tr>
            </tbody>
        </table>
        <div class="page_break"></div>

    @endforeach
    
                

                
          
</div>

</body>
</html>