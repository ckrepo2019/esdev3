<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
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

            .p-1{
                padding: .25rem !important;
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

            
            .small-text td{
                padding-top: .1rem;
                padding-bottom: .1rem;
                font-size: .55rem !important;
            }

            .studentinfo td{
                padding-top: .1rem;
                padding-bottom: .1rem;
            
            }

            .text-red{
                color: red;
                border: solid 1px black;
            }


            
            .page_break { page-break-before: always; }
            @page { size: 11in 8.5in; margin: .25in;  }
            
        </style>
    </head>
    <body>
        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:18px !important">{{$schoolinfo->schoolname}}</h3>
                </td>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                
                </td>
            </tr>
            <tr>
                <td class="p-0 text-center">
                    {{$schoolinfo->address}}
                </td>
            </tr>
        </table>

        <table class="table mb-0 table-sm">
            <tr>
                <td class="text-center">
                    <b style="font-size:1rem !important">Number of Students Who Belong to Ethnic Groups</b>
                </td>
            </tr>
        </table>

        @php
            $column_width = 80 / count($gradelevel);
        @endphp

        <table class="table mb-0 table-sm table-bordered" style="font-size:6pt !important">
            <tr>
                <td rowspan="2" class="align-middle">ETHNIC GROUP</td>
                <td colspan="{{count($gradelevel) + 1}}" class="text-center">GRADE LEVEL</td>
            </tr>
            <tr>
                @foreach ($gradelevel as $gradelevel_item)
                    <td width="{{$column_width}}%" class="text-center">{{strtoupper(str_replace(' COLLEGE','',$gradelevel_item->levelname))}}</td>
                @endforeach
                <td class="text-center align-middle"><b>TOTAL</b></td>
            </tr>
            @foreach($ethnic as $ethnic_item)
                <tr>
                    <td >{{$ethnic_item->egname}}</td>
                    @foreach ($gradelevel as $gradelevel_item)
                        <td class="text-center">{{collect($students)->where('levelid',$gradelevel_item->id)->where('egid',$ethnic_item->id)->count()}}</td>
                       
                    @endforeach
                    <td  class="text-center"><b>{{collect($students)->where('egid',$ethnic_item->id)->count()}}</b></td>
                </tr>
            @endforeach
            <tr>
                <td>NOT ASSIGNED</td>
                @foreach ($gradelevel as $gradelevel_item)
                    <td class="text-center">{{collect($students)->whereIn('egid',[null,0])->where('levelid',$gradelevel_item->id)->count()}}</td>
                @endforeach
                <td  class="text-center"><b>{{collect($students)->whereIn('egid',[null,0])->count()}}</b></td>
            </tr>
            <tr>
                <td >TOTAL</td>
                @foreach ($gradelevel as $gradelevel_item)
                    <td class="text-center">{{collect($students)->whereNotIn('egid',[null,0])->where('levelid',$gradelevel_item->id)->count()}}</td>
                @endforeach
                <td  class="text-center"><b>{{collect($students)->whereNotIn('egid',[null,0])->count() + collect($students)->whereIn('egid',[null,0])->count()}}</b></td>
            </tr>
        </table>
        <table class="table table-sm mb-0 " style="margin-top:.5rem !important; font-size:7pt !important" ">
            <tr>
                <td  class="align-middle p-0" width="15%">ENROLLED STUDENTS:</td>
                <td  class="align-middle p-0" width="85%"><b>{{collect($students)->count()}}</b></td>
            </tr>
            <tr>
                <td  class="align-middle p-0" >ASSIGNED:</td>
                <td  class="align-middle p-0" ><b>{{collect($students)->whereNotIn('egid',[null,0])->count()}}</b></td>
            </tr>
            <tr>
                <td  class="align-middle p-0" >NOT ASSIGNED:</td>
                <td  class="align-middle p-0" ><b>{{collect($students)->whereIn('egid',[null,0])->count()}}</b></td>
            </tr>
        </table>
        <table class="table table-sm mb-0 " style="margin-top:.5rem !important; font-size:7pt !important" ">
            <tr>
                <td colspan="2" class="p-0" ><i style="font-size:.5rem !important">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</i></td>
            </tr>
        </table>
        @php
            $page = 0;
        @endphp
        @foreach($ethnic as $ethnic_item)
            @php
                $chunk_students = array_chunk(collect($students)->where('egid',$ethnic_item->id)->toArray(), 45);
                $width = 100 / 3;
                $studcount_1= 1;
                $studcount_2= 1+45;
                $studcount_3= 1+(45*2);
                $stud_index_1 = 0;
                $stud_index_2 = 1;
                $stud_index_3 = 2;
                $sheet_count = count($chunk_students);
            @endphp
            
            @for($x = 0; $x <= count($chunk_students) / 3; $x++ )
                @if($stud_index_1 < $sheet_count )
                    @php
                        $page += 1;
                    @endphp
                    <div class="page_break"></div>
                    <table class="table mb-0 table-sm">
                        
                        <tr>
                            <td colspan="2"><b>{{strtoupper($ethnic_item->egname)}}</b></td>
                            <td class="text-right">Page: {{$page}}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="p-0" style="padding-left:.25rem !important"><i style="font-size:.5rem !important">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</i></td>
                        </tr>
                        <tr>
                            <td width="{{$width}}%">
                                <table class="table mb-0 table-sm table-bordered">
                                    @if($stud_index_1 < $sheet_count)
                                        @foreach($chunk_students[$stud_index_1] as $item)
                                            <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_1}}. {{$item->studentname}} </td></tr>
                                            @php
                                                $studcount_1 += 1;
                                            @endphp
                                        @endforeach
                                        @php
                                            $stud_index_1 += 3;
                                            $studcount_1 += (45*2);
                                        @endphp
                                    @endif
                                </table>
                            </td>
                            <td width="{{$width}}%">
                                <table class="table mb-0 table-sm table-bordered">
                                    @if($stud_index_2 <= $sheet_count)
                                        @if($stud_index_2 < $sheet_count)
                                            @foreach($chunk_students[$stud_index_2] as $item)
                                                <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_2}}. {{$item->studentname}} </td></tr>
                                                @php
                                                    $studcount_2 += 1;
                                                @endphp
                                            @endforeach
                                            @php
                                                $stud_index_2 += 3;
                                                $studcount_2 += 45*2;
                                            @endphp
                                        @endif
                                    @endif
                                </table>
                            </td>
                            <td width="{{$width}}%">
                                <table class="table mb-0 table-sm table-bordered">
                                    @if($stud_index_3 < $sheet_count)
                                        @if($stud_index_3 <= $sheet_count)
                                            @foreach($chunk_students[$stud_index_3] as $item)
                                                <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_3}}. {{$item->studentname}} </td></tr>
                                                @php
                                                
                                                    $studcount_3 += 1;
                                                @endphp
                                            @endforeach
                                            @php
                                                $stud_index_3 += 3;
                                                $studcount_3 += (45*2);
                                            @endphp
                                        @endif
                                    @endif
                                </table>
                            </td>
                        </tr>
                    </table> 
                @endif
            @endfor
        @endforeach
        
        @php
            $chunk_students = array_chunk(collect($students)->whereIn('egid',[null,0])->toArray(), 45);
            $width = 100 / 3;
            $studcount_1= 1;
            $studcount_2= 1+45;
            $studcount_3= 1+(45*2);
            $stud_index_1 = 0;
            $stud_index_2 = 1;
            $stud_index_3 = 2;
            $sheet_count = count($chunk_students);
        @endphp

        @for($x = 0; $x <= count($chunk_students) / 3; $x++ )
            @if($stud_index_1 < $sheet_count )
                <div class="page_break"></div>
                <table class="table mb-0 table-sm">
                    
                    <tr>
                        <td colspan="2"><b>NOT ASSIGNED</b></td>
                        <td class="text-right">Page: {{$page+1}}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="p-0" style="padding-left:.25rem !important"><i style="font-size:.5rem !important">Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</i></td>
                    </tr>
                    <tr>
                        <td width="{{$width}}%">
                            <table class="table mb-0 table-sm table-bordered">
                                @if($stud_index_1 < $sheet_count)
                                    @foreach($chunk_students[$stud_index_1] as $item)
                                        <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_1}}. {{$item->studentname}} </td></tr>
                                        @php
                                            $studcount_1 += 1;
                                        @endphp
                                    @endforeach
                                    @php
                                        $stud_index_1 += 3;
                                        $studcount_1 += (45*2);
                                    @endphp
                                @endif
                            </table>
                        </td>
                        <td width="{{$width}}%">
                            <table class="table mb-0 table-sm table-bordered">
                                @if($stud_index_2 <= $sheet_count)
                                    @if($stud_index_2 < $sheet_count)
                                        @foreach($chunk_students[$stud_index_2] as $item)
                                            <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_2}}. {{$item->studentname}} </td></tr>
                                            @php
                                                $studcount_2 += 1;
                                            @endphp
                                        @endforeach
                                        @php
                                            $stud_index_2 += 3;
                                            $studcount_2 += 45*2;
                                        @endphp
                                    @endif
                                @endif
                            </table>
                        </td>
                        <td width="{{$width}}%">
                            <table class="table mb-0 table-sm table-bordered">
                                @if($stud_index_3 < $sheet_count)
                                    @if($stud_index_3 <= $sheet_count)
                                        @foreach($chunk_students[$stud_index_3] as $item)
                                            <tr><td class="p-0" style="padding-left:.25rem !important">{{$studcount_3}}. {{$item->studentname}} </td></tr>
                                            @php
                                            
                                                $studcount_3 += 1;
                                            @endphp
                                        @endforeach
                                        @php
                                            $stud_index_3 += 3;
                                            $studcount_3 += (45*2);
                                        @endphp
                                    @endif
                                @endif
                            </table>
                        </td>
                    </tr>
                </table> 
            @endif
        @endfor
       
    </body>
</html>