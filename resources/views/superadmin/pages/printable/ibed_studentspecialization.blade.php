<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Specialization</title>
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


        .bg-danger {
            background-color: #dc3545!important;
        }
        @page { size: 8.5in 11in; margin: .25in;  }
        
    </style>
</head>
<body>


    <table class="table table-sm mb-0" style="font-size:13px;">
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

    <table class="table table-sm header" style="font-size:13px;">
        <tr>
            <td class="text-center"><b style="font-size:15px !important">Subject Specialization Report ({{$sy->sydesc}})</b></td>
        </tr>
    </table>
    
    <table class="table table-sm table-bordered">
        <tr>
            <td colspan="6"><b>Subjects:</b></td>
        </tr>
        <tr>
            <td  width="8%"><b>Code</b></td>
            <td  width="32%"><b>Description</b></td>
            <td class="text-center" width="15%"><b>Q1</b></td>
            <td class="text-center" width="15%"><b>Q2</b></td>
            <td class="text-center" width="15%"><b>Q3</b></td>
            <td class="text-center" width="15%"><b>Q4</b></td>
        </tr>
        @foreach($subjects[0]->subjects as $subjitem)
            <tr>
                <td >{{$subjitem->subjcode}}</td>
                <td >{{$subjitem->subjdesc}}</td>
                <td class="text-center">{{collect($students)->where('q1subj',$subjitem->id)->count()}}</td>
                <td class="text-center">{{collect($students)->where('q2subj',$subjitem->id)->count()}}</td>
                <td class="text-center">{{collect($students)->where('q3subj',$subjitem->id)->count()}}</td>
                <td class="text-center">{{collect($students)->where('q4subj',$subjitem->id)->count()}}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2"><b>Not Assigned</b></td>
            <td class="text-center">{{collect($students)->where('q1',0)->count()}}</td>
            <td class="text-center">{{collect($students)->where('q2',0)->count()}}</td>
            <td class="text-center">{{collect($students)->where('q3',0)->count()}}</td>
            <td class="text-center">{{collect($students)->where('q4',0)->count()}}</td>
        </tr>
        <tr>
            <td colspan="2""><b>Student Count</b></td>
            <td class="text-center">{{collect($students)->count()}}</td>
            <td class="text-center">{{collect($students)->count()}}</td>
            <td class="text-center">{{collect($students)->count()}}</td>
            <td class="text-center">{{collect($students)->count()}}</td>
        </tr>
    </table>

    @if($type == 'section')
        @foreach($sections as $sectionitem)
            <table class="table table-sm table-bordered">
                <tr>
                    <td colspan="5"><b>{{ $sectionitem->levelname}} - {{ $sectionitem->sectionname}}</b></td>
                </tr>
                <tr>
                    <td " width="40%"><b>Student</b></td>
                    <td class="text-center" width="15%"><b>Q1</b></td>
                    <td class="text-center" width="15%"><b>Q2</b></td>
                    <td class="text-center" width="15%"><b>Q3</b></td>
                    <td class="text-center" width="15%"><b>Q4</b></td>
                </tr>
                @php
                    $filteredstudents = collect($students)->where('sectionid',$sectionitem->id)->sortBy('student')->values();
                    $count = 1;
                @endphp
                @if(count($students) > 0)
                    @foreach($filteredstudents as $Studentitem)
                        <tr>
                            <td>{{$count}}. {{$Studentitem->student}}</td>
                            <td class="text-center {{$Studentitem->q1 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext1}}</td>
                            <td class="text-center {{$Studentitem->q2 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext2}}</td>
                            <td class="text-center {{$Studentitem->q3 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext3}}</td>
                            <td class="text-center {{$Studentitem->q4 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext4}}</td>
                        </tr>
                        @php
                            $count += 1;
                        @endphp
                    @endforeach
                    <tr>
                        <td style="padding-left: 20px !important"><b>Summary:</b> {{ $sectionitem->levelname}} - {{ $sectionitem->sectionname}}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td style="padding-left: 30px !important"><b>Student Count</b></td>
                        <td class="text-center">{{collect($filteredstudents)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->count()}}</td>
                    </tr>
                    @foreach(collect($subjects[0]->info)->where('levelid',$sectionitem->levelid)->values() as $subjitem)
                        <tr>
                            <td style="padding-left: 30px !important">{{$subjitem->subjcode}} - {{$subjitem->subjdesc}}</td>
                            <td  class="text-center">{{collect($filteredstudents)->where('q1subj',$subjitem->subjid)->count()}}</td>
                            <td  class="text-center">{{collect($filteredstudents)->where('q2subj',$subjitem->subjid)->count()}}</td>
                            <td  class="text-center">{{collect($filteredstudents)->where('q3subj',$subjitem->subjid)->count()}}</td>
                            <td  class="text-center">{{collect($filteredstudents)->where('q4subj',$subjitem->subjid)->count()}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="padding-left: 30px !important"><b>Not Assigned</b></td>
                        <td class="text-center">{{collect($filteredstudents)->where('q1',0)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->where('q2',0)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->where('q3',0)->count()}}</td>
                        <td class="text-center">{{collect($filteredstudents)->where('q4',0)->count()}}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5">No Students Enrolled</td>
                    </tr>
                @endif
            </table>
        @endforeach
    @elseif($type == 'bysubject')
        @foreach($subjects[0]->subjects as $subjitem)
            <table class="table table-sm table-bordered">
                <tr>
                    <td colspan="6"><b>{{ $subjitem->subjcode}} - {{ $subjitem->subjdesc}}</b></td>
                </tr>
                <tr>
                    <td width="30%"><b>Student</b></td>
                    <td width="30%"><b>Grade Level / Section</b></td>
                    <td class="text-center" width="10%"><b>Q1</b></td>
                    <td class="text-center" width="10%"><b>Q2</b></td>
                    <td class="text-center" width="10%"><b>Q3</b></td>
                    <td class="text-center" width="10%"><b>Q4</b></td>
                </tr>
                @php
                    $filteredstudents = collect($students)->map(function($item) use($subjitem){
                        if($item->q1subj == $subjitem->id || $item->q2subj == $subjitem->id || $item->q3subj == $subjitem->id || $item->q4subj == $subjitem->id){
                            return $item;
                        }
                    });

                    $filteredstudents = collect($filteredstudents)->whereNotNull()->values();
                    $count = 1;
                @endphp
                @foreach($filteredstudents as $Studentitem)
                    <tr>
                        <td>{{$count}}. {{$Studentitem->student}}</td>
                        <td>{{$Studentitem->levelname}} - {{$Studentitem->sectionname}}</td>
                        <td class="text-center {{$Studentitem->q1 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext1}}</td>
                        <td class="text-center {{$Studentitem->q2 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext2}}</td>
                        <td class="text-center {{$Studentitem->q3 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext3}}</td>
                        <td class="text-center {{$Studentitem->q4 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext4}}</td>
                    </tr>
                    @php
                        $count += 1;
                    @endphp
                @endforeach
                
            </table>

        @endforeach
        <table class="table table-sm table-bordered">
            <tr>
                <td colspan="6"><b>Not Assigned</b></td>
            </tr>
            <tr>
                <td width="30%"><b>Student</b></td>
                <td width="30%"><b>Grade Level / Section</b></td>
                <td class="text-center" width="10%"><b>Q1</b></td>
                <td class="text-center" width="10%"><b>Q2</b></td>
                <td class="text-center" width="10%"><b>Q3</b></td>
                <td class="text-center" width="10%"><b>Q4</b></td>
            </tr>
            @php
                    $filteredstudents = collect($students)->map(function($item) use($subjitem){
                        if($item->q1 == 0 || $item->q2 == 0 || $item->q3 == 0 || $item->q4 == 0){
                            return $item;
                        }
                    });

                    $filteredstudents = collect($filteredstudents)->whereNotNull()->values();
                    $count = 1;
                @endphp
                @foreach($filteredstudents as $Studentitem)
                    <tr>
                        <td>{{$count}}. {{$Studentitem->student}}</td>
                        <td>{{$Studentitem->levelname}} - {{$Studentitem->sectionname}}</td>
                        <td class="text-center {{$Studentitem->q1 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext1}}</td>
                        <td class="text-center {{$Studentitem->q2 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext2}}</td>
                        <td class="text-center {{$Studentitem->q3 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext3}}</td>
                        <td class="text-center {{$Studentitem->q4 == 0 ? 'bg-danger':''}}">{{$Studentitem->subjtext4}}</td>
                    </tr>
                    @php
                        $count += 1;
                    @endphp
                @endforeach
        </table>
    @endif
    

</body>
</html>