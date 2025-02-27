

<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<table style="width: 100%; text-align: center; font-size: 11px; table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th rowspan="7" style="text-align: left; vertical-align: top !important;">
                                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px">
                                    </th>
                                    <th style="font-weight: bold; width: 60%;">{{$schoolinfo->schoolname}}</th>
                                    <th rowspan="7"></th>
                                </tr>
                                <tr>
                                    <th>{{$schoolinfo->address}}</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">OFFICIAL ENROLMENT SUMMARY</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">@if($selectedacadprog == 5 || $selectedacadprog == 6 || $selectedacadprog == '0' ) {{$semester}} Semester , @endif S.Y {{$sy->sydesc}}</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">{{$selectedacadprog != 'basiced' ? $descacad : 'Basic Ed'}}</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                        </table>
                        
                            {{-- <table style="height: 20px; font-size: 10px; width: 100%;">
                                <tr>
                                    <td>SCHOOL YEAR</td>
                                    <td>: {{$sy->sydesc}}</td>
                                    <td>COLLEGE/TRACK</td>
                                    <td>: {{$trackname}}</td>
                                    <td>GENDER</td>
                                    <td>: {{$selectedgender}}</td>
                                </tr>
                                <tr>
                                    <td>DEPARTMENT</td>
                                    <td>: {{$selectedacadprog}}</td>
                                    <td>COURSE/STRAND</td>
                                    <td>: {{$strandname}}</td>
                                    <td>GRANTEE</td>
                                    <td>: {{$selectedgrantee}}</td>
                                </tr>
                                <tr>
                                    <td>GRADE LEVEL</td>
                                    <td>: {{$selectedgradelevel}}</td>
                                    <td>ADMISSION STATUS</td>
                                    <td>: {{$selectedstudentstatus}}</td>
                                    <td>STUDENT TYPE</td>
                                    <td>: {{$selectedstudenttype}}</td>
                                </tr>
                                <tr>
                                    <td>SECTION</td>
                                    <td>: {{$selectedsection}}</td>
                                    <td>MOL</td>
                                    <td>: {{$selectedmode}}</td>
                                    <td>ENROLLMENT PERIOD</td>
                                    <td>: {{$selecteddate}}</td>
                                </tr>
                            </table>
                            <table style="font-size: 11px;margin-top: 5px;">
                                <tr>
                                    <td></td>
                                </tr>
                            </table> --}}


@php
$numofstudents = 1;   
$numofstudentsall = 1;  
$html = '';
$malecount = 1;
$femalecount = 1;
@endphp

        <table border="1" cellpadding="2" style="font-size: 10px; width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style=" font-weight: bold; text-align: center;" width="5%">#</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >SID</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >LRN</th>
                    <th style=" font-weight: bold; text-align: center;" width="15%">Students</th>
                    
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Grade Level</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Section</th>
                    @if($selectedacadprog == 6)
                    <th style=" font-weight: bold; text-align: center;" width="10%" >College</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Course</th>
                    @elseif($selectedacadprog == 5)
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Track</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Strand</th>
                    @elseif($selectedacadprog == 'basiced')
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Track</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Strand</th>
                    @elseif($selectedacadprog == 0)
                    <th style=" font-weight: bold; text-align: center;" width="10%" >College/<br/>Track</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Course/<br/>Strand</th>
                    @else
                    @endif
                    <th style=" font-weight: bold; text-align: center;" width="10%" >MOL</th>
                    <th style=" font-weight: bold; text-align: center;" width="10%" >Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if($selectedacadprog < 5 && $selectedacadprog != 0)
                    <td colspan="8"  style="background-color: #b3ecff;">MALE</td>
                    @else
                    <td colspan="10"  style="background-color: #b3ecff;">MALE</td>
                    @endif
                </tr>
                {{-- @if(count($records[0]->courses) > 0)
                    @foreach($records[0]->courses as $coursekey => $course)
                    
                        <tr>
                            <td style="text-align: center" width="5%">{{$coursekey+1}}</td>                    
                            <td style="" width="50%" >{{$course->courseDesc}}</th>
                            <td style="text-align: center;">{{$course->countmale}}</td>
                            <td style="text-align: center;">{{$course->countfemale}}</th>
                            <td style="text-align: center;">{{$course->total}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="" width="5%"></td>                    
                        <td style="" width="50%" >TOTAL</th>
                        <td style="text-align: center;">{{collect($records[0]->courses)->sum('countmale')}}</td>
                        <td style="text-align: center;">{{collect($records[0]->courses)->sum('countfemale')}}</th>
                        <td style="text-align: center;">{{collect($records[0]->courses)->sum('total')}}</th>
                    </tr>
                @endif --}}
            
            @foreach($filteredstudents as $student)
                @if(strtolower($student->gender) == 'male')               {
                    @if($student->dateenrolled == null)
                        @php
                        $date = '';
                        @endphp
                    @else
                        @php
                            $date=date_create($student->dateenrolled);
                            $date = date_format($date,"m/d/Y");
                        @endphp
                    @endif
                    <tr nobr="true">
                                <td style="font-size: 10px !important; text-align: center;" width="5%">{{$malecount}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sid}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->lrn}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="15%">{{ucwords(strtolower($student->lastname)).', '.ucwords(strtolower($student->firstname)).' '.ucwords(strtolower($student->middlename))}}</td>
                                
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->levelname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sectionname}}</td>
                                @if($selectedacadprog == 6)
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>>
                                @elseif($selectedacadprog == 5)
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                                @elseif($selectedacadprog == 0)
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                                @elseif($selectedacadprog == 'basiced')
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                                @else
                                @endif
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->mol}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->studentstatus}}</td>
                            </tr>
                    @php
                    $malecount+=1;   
                    $numofstudents+=1; 
                    @endphp  
                @endif
            @endforeach
            <tr>
                @if($selectedacadprog < 5 && $selectedacadprog != 0)
                <td colspan="8"  style="background-color: #ffccff;">FEMALE</td>
                @else
                <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
                @endif
                    </tr>
                    @foreach($filteredstudents as $student)
                        @if(strtolower($student->gender) == 'female')               {
                            @if($student->dateenrolled == null)
                                @php
                                $date = '';
                                @endphp
                            @else
                                @php
                                    $date=date_create($student->dateenrolled);
                                    $date = date_format($date,"m/d/Y");
                                @endphp
                            @endif
                            <tr nobr="true">
                                        <td style="font-size: 10px !important; text-align: center;" width="5%">{{$femalecount}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sid}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->lrn}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="15%">{{ucwords(strtolower($student->lastname)).', '.ucwords(strtolower($student->firstname)).' '.ucwords(strtolower($student->middlename))}}</td>
                                        
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->levelname}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sectionname}}</td>
                                        
                    @if($selectedacadprog == 6)
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>>
                    @elseif($selectedacadprog == 5)
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                    @elseif($selectedacadprog == 0)
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                    @elseif($selectedacadprog == 'basiced')
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                    <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                    @else
                    @endif
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->mol}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->studentstatus}}</td>
                                    </tr>
                            @php
                            $femalecount+=1;   
                            $numofstudents+=1; 
                            @endphp  
                        @endif
                    @endforeach

                </tbody>
        </table>
        <br/>
        <br/>
        {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi') --}}
        <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
            <tr>
                <td></td>
                <td style="width: 30%;">Prepared by</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedby}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 30%;">Generated by</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$generatedby}}</td>
            </tr>
        </table>
        {{-- @endif --}}