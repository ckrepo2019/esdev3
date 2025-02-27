

<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }
</style>
<table style="width: 100%; text-align: center; font-size: 11px;">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold;">{{$schoolinfo->schoolname}}</th>
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
                                    <th style="font-weight: bold;">S.Y {{$sy->sydesc}}</th>
                                </tr>
                                <tr>
                                    <th style="font-weight: bold;">{{$descacad}}</th>
                                </tr>
                                <tr>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                        </table>
                            <table style="height: 20px; font-size: 10px; width: 100%;">
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
                            </table>


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
                    <th style="" width="5%">#</th>                    
                    <th style="" width="40%" >Section</th>
                    <th style="">Male</th>
                    <th style="">Female</th>
                    <th style="">Total</th>
                </tr>
            </thead>
            <tbody>
                @if(count($records[0]->sections) > 0)
                    @foreach($records[0]->sections as $sectionkey => $section)
                    
                        <tr>
                            <td style="text-align: center" width="5%">{{$sectionkey+1}}</td>                    
                            <td style="" width="50%" >{{$section->levelname}} - {{$section->sectionname}}</th>
                            <td style="text-align: center;">{{$section->countmale}}</td>
                            <td style="text-align: center;">{{$section->countfemale}}</th>
                            <td style="text-align: center;">{{$section->total}}</th>
                        </tr>
                    @endforeach
                    <tr>
                        <td style="" width="5%"></td>                    
                        <td style="" width="50%" >TOTAL</th>
                        <td style="text-align: center;">{{collect($records[0]->sections)->sum('countmale')}}</td>
                        <td style="text-align: center;">{{collect($records[0]->sections)->sum('countfemale')}}</th>
                        <td style="text-align: center;">{{collect($records[0]->sections)->sum('total')}}</th>
                    </tr>
                @endif
            
            {{-- @foreach($filteredstudents as $student)
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
                                <td style="font-size: 10px !important; text-align: left;" width="5%">{{$malecount}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sid}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->lrn}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="15%">{{ucwords(strtolower($student->lastname)).', '.ucwords(strtolower($student->firstname)).' '.ucwords(strtolower($student->middlename))}}</td>
                                
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->levelname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sectionname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
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
                        <td colspan="10"  style="background-color: #ffccff;">FEMALE</td>
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
                                        <td style="font-size: 10px !important; text-align: left;" width="5%">{{$malecount}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sid}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->lrn}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="15%">{{ucwords(strtolower($student->lastname)).', '.ucwords(strtolower($student->firstname)).' '.ucwords(strtolower($student->middlename))}}</td>
                                        
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->levelname}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->sectionname}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->trackname}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->strandcode}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->mol}}</td>
                                        <td style="font-size: 10px !important; text-align: left;" width="10%">{{$student->studentstatus}}</td>
                                    </tr>
                            @php
                            $femalecount+=1;   
                            $numofstudents+=1; 
                            @endphp  
                        @endif
                    @endforeach --}}

                </tbody>
        </table>
        <br/>
        <br/>
        
        <table style="width: 100%; font-size: 11px;">
            <tr>
                <td></td>
                <td>Prepared by:</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                {{-- <td style="border-bottom: 1px solid black;">{{$preparedby}}</td> --}}
                <td style="width: 70%;">&nbsp;</td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedby}}</td>
            </tr>
        </table>
        <br/>
        <br/>
        
        <table style="width: 100%; font-size: 11px;">
            <tr>
                <td></td>
                <td>Generated by:</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                {{-- <td style="border-bottom: 1px solid black;">{{$preparedby}}</td> --}}
                <td style="width: 70%;">&nbsp;</td>
                <td style="border-bottom: 1px solid black; text-align: center;">{{$generatedby}}</td>
            </tr>
        </table>