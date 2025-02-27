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
            @page { size: 8.5in 11in; margin: .25in;  }
            
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
                    <b style="font-size:1rem !important">Enrolment by Religious Affiliation</b>
                </td>
            </tr>
        </table>

      

        <table class="table table-sm ">
            <tr>
                <td width="50%">
                    <table class="table table-sm table-bordered" >
                        <tr>
                            <td colspan="4" class="text-center align-middle" >ALL GRADE LEVEL</td>
                        </tr>
                        <tr>
                            <td rowspan="2" class="text-center align-middle" width="20%">YEAR</td>
                            <td colspan="2" class="text-center align-middle">CHRISTIAN</td>
                            <td rowspan="2" class="text-center align-middle" width="20%">ISLAM</td>
                        </tr>
                        <tr>
                            <td class="text-center align-middle" width="30%">CATHOLIC</td>
                            <td class="text-center align-middle" width="30%">NON-CATHOLIC</td>
                        </tr>
                        @foreach($gradelevel as $item)
                            <tr>
                                <td class="text-center align-middle">{{strtoupper(str_replace(' COLLEGE','',$item->levelname))}}</td>
                                <td class="text-center align-middle">{{collect($students)->where('levelid',$item->id)->where('religionid',1)->count()}}</td>
                                <td class="text-center align-middle">{{collect($students)->where('levelid',$item->id)->whereNotIn('religionid',[null,0,1,12,21])->count()}}</td>
                                <td class="text-center align-middle">{{collect($students)->where('levelid',$item->id)->whereIn('religionid',[12,21])->count()}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-center align-middle">TOTAL</td>
                            <td class="text-center align-middle">{{collect($students)->where('religionid',1)->count()}}</td>
                            <td class="text-center align-middle">{{collect($students)->whereNotIn('religionid',[null,0,1,12,21])->count()}}</td>
                            <td class="text-center align-middle">{{collect($students)->whereIn('religionid',[12,21])->count()}}</td>
                        </tr>
                    </table>
                    <table class="table table-sm mb-0 table-bordered">
                        <tr>
                            <td  class="align-middle" width="40%">ENROLLED STUDENTS:</td>
                            <td  class="align-middle" width="70%">{{collect($students)->count()}}</td>
                        </tr>
                        <tr>
                            <td  class="align-middle" >ASSIGNED:</td>
                            <td  class="align-middle" >{{collect($students)->whereNotIn('religionid',[null,0])->count()}}</td>
                        </tr>
                        <tr>
                            <td  class="align-middle" >NOT ASSIGNED:</td>
                            <td  class="align-middle" >{{collect($students)->whereIn('religionid',[null,0])->count()}}</td>
                        </tr>
                    </table>
                   
                    <table class="table table-sm" >
                        <tr>
                            <td class="text-right align-middle" width="100%" style="font-size:.5rem !important"><i>Date Generated: {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY hh:mm A')}}</i></td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    @foreach ($academicprogram as $acadprog_item)
                        @php
                            $acad_gradelevel = collect($gradelevel)->where('acadprogid',$acadprog_item->id)->values();
                        @endphp
                        <table class="table table-sm table-bordered small-text" >
                            <tr>
                                <td colspan="4" class="text-center align-middle" >{{strtoupper($acadprog_item->progname)}}</td>
                            </tr>
                            <tr>
                                <td rowspan="2" class="text-center align-middle p-1" width="20%">YEAR</td>
                                <td colspan="2" class="text-center align-middle p-1">CHRISTIAN</td>
                                <td rowspan="2" class="text-center align-middle p-1" width="20%">ISLAM</td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle p-1" width="30%">CATHOLIC</td>
                                <td class="text-center align-middle p-1" width="30%">NON-CATHOLIC</td>
                            </tr>
                            @foreach($acad_gradelevel as $item)
                                <tr>
                                    <td class="text-center align-middle p-1">{{strtoupper(str_replace(' COLLEGE','',$item->levelname))}}</td>
                                    <td class="text-center align-middle p-1">{{collect($students)->where('levelid',$item->id)->where('religionid',1)->count()}}</td>
                                    <td class="text-center align-middle p-1">{{collect($students)->where('levelid',$item->id)->whereNotIn('religionid',[null,0,1,12,21])->count()}}</td>
                                    <td class="text-center align-middle p-1">{{collect($students)->where('levelid',$item->id)->whereIn('religionid',[12,21])->count()}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-center align-middle p-1">TOTAL</td>
                                <td class="text-center align-middle p-1">{{collect($students)->whereIn('levelid',collect($acad_gradelevel)->pluck('id'))->where('religionid',1)->count()}}</td>
                                <td class="text-center align-middle p-1">{{collect($students)->whereIn('levelid',collect($acad_gradelevel)->pluck('id'))->whereNotIn('religionid',[null,0,1,12,21])->count()}}</td>
                                <td class="text-center align-middle p-1">{{collect($students)->whereIn('levelid',collect($acad_gradelevel)->pluck('id'))->whereIn('religionid',[12,21])->count()}}</td>
                            </tr>
                        </table>
                    @endforeach


                </td>
            </tr>
        </table>
        <!--<div class="page_break"></div>-->
     
        @php
            $chunk_students = array_chunk(collect($students)->where('religionid',1)->toArray(), 60);
            $width = 100 / 3;
            $studcount_1= 1;
            $studcount_2= 1+60;
            $studcount_3= 1+(60*2);
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
                    <td colspan="2"><b>CATHOLIC STUDENT LIST</b></td>
                    <td class="text-right">Page: {{$x+1}}</td>
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
                                    $studcount_1 += (60*2);
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
                                        $studcount_2 += 60*2;
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
                                        $studcount_3 += (60*2);
                                    @endphp
                                @endif
                            @endif
                        </table>
                    </td>
                </tr>
            </table> 
         
                
            @endif
        @endfor
        
        @php
            $chunk_students = array_chunk(collect($students)->whereNotIn('religionid',[null,0,1,12,21])->toArray(), 60);
            $width = 100 / 3;
            $studcount_1= 1;
            $studcount_2= 1+60;
            $studcount_3= 1+(60*2);
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
                        <td colspan="2"><b>NON-CATHOLIC STUDENT LIST</b></td>
                        <td class="text-right">Page: {{$x+1}}</td>
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
                                        $studcount_1 += (60*2);
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
                                            $studcount_2 += 60*2;
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
                                            $studcount_3 += (60*2);
                                        @endphp
                                    @endif
                                @endif
                            </table>
                        </td>
                    </tr>
                </table> 
            
            @endif
        @endfor
        
        @php
            $chunk_students = array_chunk(collect($students)->whereIn('religionid',[12,21])->toArray(), 60);
            $width = 100 / 3;
            $studcount_1= 1;
            $studcount_2= 1+60;
            $studcount_3= 1+(60*2);
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
                        <td colspan="2"><b>ISLAM STUDENT LIST</b></td>
                        <td class="text-right">Page: {{$x+1}}</td>
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
                                        $studcount_1 += (60*2);
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
                                            $studcount_2 += 60*2;
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
                                            $studcount_3 += (60*2);
                                        @endphp
                                    @endif
                                @endif
                            </table>
                        </td>
                    </tr>
                </table> 
            @endif
        @endfor
        
        @php
            $chunk_students = array_chunk(collect($students)->whereIn('religionid',[null,0])->toArray(), 60);
            $width = 100 / 3;
            $studcount_1= 1;
            $studcount_2= 1+60;
            $studcount_3= 1+(60*2);
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
                        <td class="text-right">Page: {{$x+1}}</td>
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
                                        $studcount_1 += (60*2);
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
                                            $studcount_2 += 60*2;
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
                                            $studcount_3 += (60*2);
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