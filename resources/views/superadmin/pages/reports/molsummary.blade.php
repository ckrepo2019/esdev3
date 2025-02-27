<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mode of Learning Summary</title>
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
            font-family: Arial, sans-serif;
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

        .text-red{
            color: red;
            border: solid 1px black;
        }
    

        .table .thead-light th {
            color: #495057;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0,0,0,.05);
        }

        .page_break { page-break-before: always; }
        @page { size: 8.5in 11in; margin: .25in;  }
        
    </style>
</head>
<body>

    @if($datatype == 1)
        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo->schoolname}}</h3>
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
        <table class="table mb-0 table-sm" style="font-size:13px;">
            <tr>
                <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>Mode of Learning Summary</b></td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0">SCHOOL YEAR: {{$syinfo->sydesc}}</td>
            </tr>
        </table>  
        <br>
        <br>
        <table class="table" width="100%">
            <tr>
                <td width="40%" class="p-0">
                    <table class="table table-bordered table-sm table-striped" width="100%">
                        <thead>
                            <tr>
                                <td colspan="2"><b>By Mode of Learning</b></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="70%"><b>Enrolled Students</b></td>
                                <td width="30%"  class="text-center">{{collect($all_enrolled)->count()}}</td>
                            </tr>
                            @foreach ($mol as $item)
                                <tr>
                                    <td><b>{{$item->description}}</b></td>
                                    <td  class="text-center">{{collect($all_enrolled)->where('studmol',$item->id)->count()}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td ><b>Not Assigned</b></td>
                                <td  class="text-center">{{collect($all_enrolled)->where('studmol',0)->count()}}</td>
                            </tr>
                        </tbody>
                    
                    </table>
                </td>
                <td width="60%">
                </td>
            </tr>
        </table>

        @foreach ($mol as $item)
            <table class="table table-bordered table-sm table-striped" width="100%">
                <thead>
                    <tr>
                        <td colspan="3"><b>{{$item->description}}</b></td>
                    </tr>
                
                    @php
                        $temp_students = collect($all_enrolled)->where('studmol',$item->id)->values();
                    @endphp
                    @if(count($temp_students) > 0)
                        <tr>
                            <td width="40%">Student</td>
                            <td width="30%">Grade level</td>
                            <td width="30%">Section</td>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if(count($temp_students) > 0)
                        @foreach($temp_students as $stud_item)
                            <tr>
                                <td >{{$stud_item->student}}</td>
                                <td >{{$stud_item->levelname}}</td>
                                <td >{{$stud_item->sectionname}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3">No Students Assigned</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        <table class="table table-bordered table-sm table-striped" width="100%">
            <thead>
                <tr>
                    <td colspan="3"><b>Not Assigned</b></td>
                </tr>
            
                @php
                    $temp_students = collect($all_enrolled)->where('studmol',0)->values();
                @endphp
                @if(count($temp_students) > 0)
                    <tr>
                        <td width="40%">Student</td>
                        <td width="30%">Grade level</td>
                        <td width="30%">Section</td>
                    </tr>
                @endif
            </thead>
            <tbody>
                @if(count($temp_students) > 0)
                    @foreach($temp_students as $stud_item)
                        <tr>
                            <td>{{$stud_item->student}}</td>
                            <td>{{$stud_item->levelname}}</td>
                            <td>{{$stud_item->sectionname}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">No Students Assigned</td>
                    </tr>
                @endif
            </tbody>
        </table>

    @elseif($datatype == 2)
        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo->schoolname}}</h3>
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
        <table class="table mb-0 table-sm" style="font-size:13px;">
            <tr>
                <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>Mode of Learning Summary</b></td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0">SCHOOL YEAR: {{$syinfo->sydesc}}</td>
            </tr>
        </table>  
        <br>
        <br>
        @php
            $mol_wid =  55 / collect($mol)->count();
        @endphp
        <table class="table table-bordered table-sm table-striped" width="100%">
            <thead>
                <tr>
                    <td colspan="{{collect($mol)->count() + 3}}"><b>By Grade Level</b></td>
                </tr>
                <tr>
                    <th width="20%" class=" align-middle text-left">Grade Level</th>
                    <th width="12%" class="text-center  align-middle">Enrolled</th>
                    @foreach($mol as $item)
                        <th width="{{$mol_wid}}%" class="text-center  align-middle">{{$item->description}}</th>
                    @endforeach
                    <th width="13%" class="text-center align-middle">Not Assigned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gradelevel as $item)
                    <tr>
                        <td >{{$item->levelname}}</td>
                        <td class="text-center">{{collect($all_enrolled)->where('levelid',$item->id)->count()}}</td>
                        @foreach($mol as $mol_item)
                            <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',$mol_item->id)->where('levelid',$item->id)->count()}}</td>
                        @endforeach
                        <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',0)->where('levelid',$item->id)->count()}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-right"><b>Total<b></td>
                    <td class="text-center">{{collect($all_enrolled)->count()}}</td>
                    @foreach($mol as $mol_item)
                        <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',$mol_item->id)->count()}}</td>
                    @endforeach
                    <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',0)->count()}}</td>
                </tr>
            </tbody>
        </table>
        @foreach ($gradelevel as $item)
            @php
                $temp_students = collect($all_enrolled)->sortBy('sectionname')->sortBy('student')->where('levelid',$item->id)->values();
            @endphp
            @if(count($temp_students) != 0)
                <table class="table table-bordered table-sm table-striped" width="100%">
                    <thead>
                        <tr>
                            <td colspan="4"><b>{{$item->levelname}}</b></td>
                        </tr>
                    
                    
                        @if(count($temp_students) > 0)
                            <tr>
                                <td width="30%">Student</td>
                                <td width="15%">Grade level</td>
                                <td width="30%">Section</td>
                                <td width="25%">Mode of Learning</td>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(count($temp_students) > 0)
                            @foreach($temp_students as $stud_item)
                                <tr>
                                    <td >{{$stud_item->student}}</td>
                                    <td >{{$stud_item->levelname}}</td>
                                    <td >{{$stud_item->sectionname}}</td>
                                    <td >{{$stud_item->description != null ? $stud_item->description : 'No Assigned'}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No Students Assigned</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endif
        @endforeach

    @elseif($datatype == 3)
        <table class="table mb-0 table-sm header" style="font-size:13px;">
            <tr>
                <td width="20%" rowspan="2" class="text-right align-middle p-0">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px">
                </td>
                <td width="60%" class="p-0 text-center" >
                    <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo->schoolname}}</h3>
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

        
        <table class="table mb-0 table-sm" style="font-size:13px;">
            <tr>
                <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>Mode of Learning Summary</b></td>
            </tr>
            <tr>
                <td width="100%" class="text-center p-0">SCHOOL YEAR: {{$syinfo->sydesc}}</td>
            </tr>
        </table>  
        <br>
        <br>
        @php
            $mol_wid =  55 / collect($mol)->count();
        @endphp
        <table class="table table-bordered table-sm table-striped" width="100%">
            <thead>
                <tr>
                    <td colspan="{{collect($mol)->count() + 3}}"><b>By Section</b></td>
                </tr>
                <tr>
                    <th width="20%" class=" align-middle text-left">Section Grade Level</th>
                    <th width="12%" class="text-center  align-middle">Enrolled</th>
                    @foreach($mol as $item)
                        <th width="{{$mol_wid}}%" class="text-center  align-middle">{{$item->description}}</th>
                    @endforeach
                    <th width="13%" class="text-center align-middle">Not Assigned</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $item)
                    <tr>
                        <td>{{$item->sectionname}}</td>
                        <td class="text-center">{{collect($all_enrolled)->where('sectionid',$item->id)->count()}}</td>
                        @foreach($mol as $mol_item)
                            <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',$mol_item->id)->where('sectionid',$item->id)->count()}}</td>
                        @endforeach
                        <td class="text-center  align-middle">{{collect($all_enrolled)->where('studmol',0)->where('sectionid',$item->id)->count()}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @foreach ($sections as $item)
            <table class="table table-bordered table-sm table-striped" width="100%">
                <thead>
                    <tr>
                        <td colspan="4"><b>{{$item->sectionname}}</b></td>
                    </tr>
                
                    @php
                        $temp_students = collect($all_enrolled)->sortBy('sectionname')->sortBy('student')->where('sectionid',$item->id)->values();
                    @endphp
                    @if(count($temp_students) > 0)
                        <tr>
                            <td width="30%">Student</td>
                            <td width="15%">Grade level</td>
                            <td width="30%">Section</td>
                            <td width="25%">Mode of Learning</td>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @if(count($temp_students) > 0)
                        @foreach($temp_students as $stud_item)
                            <tr>
                                <td >{{$stud_item->student}}</td>
                                <td >{{$stud_item->levelname}}</td>
                                <td >{{$stud_item->sectionname}}</td>
                                <td >{{$stud_item->description != null ? $stud_item->description : 'No Assigned'}}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No Students Assigned</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endforeach
        
    @endif


    
    
</body>
</html>