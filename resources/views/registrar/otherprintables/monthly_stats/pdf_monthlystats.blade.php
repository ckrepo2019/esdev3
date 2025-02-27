<html>
    <header>
        <style>
            @page{
                margin: 0.5in;
                size: 8.5in 11in;
            }
            td, th{
                padding: 2px;
            }
            table {
                border-collapse: collapse;
            }
            html{
                font-family: Arial, Helvetica, sans-serif;        
            }
        </style>
    </header>
    <body>
        <table style="width: 100%; table-layout: fixed; margin-bottom: 20px;">
            <tr>
                <td style="text-align: right;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"/></td>
                <td style="width: 60%; text-align: center; vertical-align: top;">
                    <div style="width: 100%; font-size: 20px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                    <div style="width: 100%; font-size: 12px;">{{DB::table('schoolinfo')->first()->address}}</div>
                    {{-- <div style="width: 100%; font-size: 15px;">Tel. No. (064) 572-4020</div> --}}
                    {{-- <div style="width: 100%; font-size: 18px;">OFFICE OF THE REGISTRAR</div> --}}
                </td>
                <td>&nbsp;</td>
            </tr>
        </table> 
        @php
            $array_levels = array_chunk(collect($gradelevels)->toArray(), 6);
        @endphp
        <table style="font-size: 10px; width: 100%;" border="1">
            <thead>
                <tr>
                    <th>Grade Level</th>
                    <th style="width: 3% !important;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($array_levels as $gradelevel)                    
                    <tr>
                        @for($x = 0; $x < 6; $x++)
                            <td class="p-0">{{$gradelevel[$x]->levelname ?? ''}}</td>
                            <td class="" style="vertical-align: middle; text-align: center;">@if(isset($gradelevel[$x])){{collect($students)->where('levelid',$gradelevel[$x]->id)->count()}}@endif</td>
                        @endfor
                    </tr>
                @endforeach
                <tr>
                    <th colspan="11" class="text-right" style=" text-align: right;">TOTAL</th>
                    <th class="p-0" style="vertical-align: middle; text-align: center;">{{count($students)}}</th>
                </tr>
            </tbody>
            <table id="table-students" class="table table-hover" style="font-size: 10px; width: 100%; margin-top: 10px;" border="1">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: left;">SID</th>
                        <th style="width: 10%; text-align: left;">LRN</th>
                        <th style="width: 27%;  text-align: left;">Student</th>
                        <th style=" text-align: left;">Level</th>
                        <th style=" text-align: left;">Admission Status</th>
                        <th>Date Enrolled</th>
                        <th>Date Updated</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{$student->sid}}</td>
                            <td>{{$student->lrn}}</td>
                            <td>{{$student->studentname}}</td>
                            <td>{{$student->levelname}}</td>
                            <td>{{$student->studentstatus}}</td>
                            <td>{{date('M d, Y', strtotime($student->dateenrolled))}}</td>
                            <td>{{date('M d, Y', strtotime($student->lastdate))}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </table>       
    </body>
</html>