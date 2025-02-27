<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Information Summary</title>
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
        @page { size: 11in 8.5in; margin: .25in;  }
        
    </style>
</head>
<body>
    @php
        $count = 1;
    @endphp
    @if($datatype == 1)
        

    @elseif($datatype == 2)
        
        @foreach ($gradelevel as $item)
         
            @php
                $temp_students = collect($contact)->sortBy('sectionname')->sortBy('student')->where('levelid',$item->id)->values();
            @endphp
          
            @if(count($temp_students) != 0)

                <table class="table mb-0 table-sm header" style="font-size:13px;">
                    <tr>
                        <td width="20%" rowspan="2" class="text-right align-middle p-0">
                            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
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
                <br>
                <table class="table mb-0 table-sm" style="font-size:13px;">
                    <tr>
                        <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>Contact Information</b></td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-center p-0">SCHOOL YEAR: {{$syinfo->sydesc}}</td>
                    </tr>
                </table>  
                <br>
                <table class="table table-bordered table-sm table-striped" width="100%" style="font-size: .6rem !important">
                    <thead>
                        <tr>
                            <td colspan="3"><b>{{$item->levelname}}</b></td>
                            <td colspan="3"><b>Students: </b>{{count($temp_students)}}</td>
                        </tr>
                    
                    
                        @if(count($temp_students) > 0)
                            <tr>
                                <td width="20%">Student</td>
                                <td width="16%">Section</td>
                                <td width="16%">Mother Contact #</td>
                                <td width="16%">Father Contact #</td>
                                <td width="16%">Guardian Contact #</td>
                                <td width="16%">Incase of Emergency</td>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(count($temp_students) > 0)
                            @foreach($temp_students as $stud_item)
                                <tr>
                                    <td >{{$stud_item->student}}<br>
                                        @if($stud_item->contactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->contactno}}
                                        @endif
                                    </td>
                                    <td >
                                        {{$stud_item->sectionname}}<br>
                                        {{$item->levelname}}
                                    </td>
                                    <td >
                                        @if($stud_item->mothername == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->mothername,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->mcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->mcontactno}}
                                        @endif
                                      
                                    </td>
                                    <td >
                                        @if($stud_item->fathername == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->fathername,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->fcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->fcontactno}}
                                        @endif
                                    </td>
                                    <td >
                                        @if($stud_item->guardianname == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->guardianname,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->gcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->gcontactno}}
                                        @endif
                                    </td>
                                    <td >
                                        @if($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->mothername,20,'...')}}<br>
                                            @if($stud_item->mcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->mcontactno}}
                                            @endif
                                        @elseif($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->fathername,20,'...')}}<br>
                                            @if($stud_item->fcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->fcontactno}}
                                            @endif
                                        @elseif($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->guardianname,20,'...')}}<br>
                                            @if($stud_item->gcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->gcontactno}}
                                            @endif
                                        @else
                                            <span style="color:red">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No Students Assigned</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @php
                    $count += 1;
                @endphp
                @if($count != count($sections))
                    <div class="page_break"></div>
                @endif
                
            @endif
        
        @endforeach

    @elseif($datatype == 3)
        @foreach ($sections as $item)
                
            @php
                $temp_students = collect($contact)->sortBy('sectionname')->sortBy('student')->where('sectionid',$item->id)->values();
            @endphp
        
            @if(count($temp_students) != 0)

                <table class="table mb-0 table-sm header" style="font-size:13px;">
                    <tr>
                        <td width="20%" rowspan="2" class="text-right align-middle p-0">
                            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="70px">
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
                <br>
                <table class="table mb-0 table-sm" style="font-size:13px;">
                    <tr>
                        <td width="100%" class="text-center p-0"  style="font-size:15px; !important"><b>Contact Information</b></td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-center p-0">SCHOOL YEAR: {{$syinfo->sydesc}}</td>
                    </tr>
                </table>  
                <br>
                <table class="table table-bordered table-sm table-striped" width="100%" style="font-size: .6rem !important">
                    <thead>
                        <tr>
                            <td colspan="3"><b>{{$item->sectionname}} ( {{$item->levelname}} )</b></td>
                            <td colspan="3"><b>Students: </b>{{count($temp_students)}}</td>
                        </tr>
                    
                    
                        @if(count($temp_students) > 0)
                            <tr>
                                <td width="20%">Student</td>
                                <td width="16%">Section</td>
                                <td width="16%">Mother Contact #</td>
                                <td width="16%">Father Contact #</td>
                                <td width="16%">Guardian Contact #</td>
                                <td width="16%">Incase of Emergency</td>
                            </tr>
                        @endif
                    </thead>
                    <tbody>
                        @if(count($temp_students) > 0)
                            @foreach($temp_students as $stud_item)
                                <tr>
                                    <td >{{$stud_item->student}}<br>
                                        @if($stud_item->contactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->contactno}}
                                        @endif
                                    </td>
                                    <td >
                                        {{$stud_item->sectionname}}<br>
                                        {{$item->levelname}}
                                    </td>
                                    <td >
                                        @if($stud_item->mothername == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->mothername,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->mcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->mcontactno}}
                                        @endif
                                      
                                    </td>
                                    <td >
                                        @if($stud_item->fathername == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->fathername,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->fcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->fcontactno}}
                                        @endif
                                    </td>
                                    <td >
                                        @if($stud_item->guardianname == null)
                                            <span style="color:red">Not Specified</span>
                                        @else
                                            {{Str::limit($stud_item->guardianname,20,'...')}}
                                        @endif
                                        <br>
                                        @if($stud_item->gcontactno == null)
                                            <span style="color:red">No Contact</span>
                                        @else
                                            {{$stud_item->gcontactno}}
                                        @endif
                                    </td>
                                    <td >
                                        @if($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->mothername,20,'...')}}<br>
                                            @if($stud_item->mcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->mcontactno}}
                                            @endif
                                        @elseif($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->fathername,20,'...')}}<br>
                                            @if($stud_item->fcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->fcontactno}}
                                            @endif
                                        @elseif($stud_item->ismothernum == 1)
                                            {{Str::limit($stud_item->guardianname,20,'...')}}<br>
                                            @if($stud_item->gcontactno == null)
                                                <span style="color:red">No Contact</span>
                                            @else
                                                {{$stud_item->gcontactno}}
                                            @endif
                                        @else
                                            <span style="color:red">Not Assigned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No Students Assigned</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                @php
                    $count += 1;
                @endphp
                @if($count != count($sections))
                    <div class="page_break"></div>
                @endif
            @endif

        @endforeach
      
        
    @endif


    
    
</body>
</html>