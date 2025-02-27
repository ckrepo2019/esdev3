<html>
    <head>
        <title>Promotional Report</title>
        <style>
            td{
                padding: 1px;
            }
            * { font-family: Arial, Helvetica, sans-serif; }
            @page{
                size: 14in 8.5in;
                margin: 50px 30px 10px 30px;
            }
            .rotate {
                text-align: center;
                white-space: nowrap;
                vertical-align: middle;
                width: 1.5em;
                padding: 10px !important;
                font-size: 11px;
            }
            .rotate div {
                -moz-transform: rotate(90.0deg);  /* FF3.5+ */
                -o-transform: rotate(90.0deg);  /* Opera 10.5 */
                -webkit-transform: rotate(90.0deg);  /* Saf3.1+, Chrome */
                        filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
                    margin-left: -10em;
                    margin-right: -10em;
            }
            table{
                border-collapse: collapse;
            }
            .last { position: absolute; bottom: -100px; left: 0px; right: 0px; height: 50px;}

        .watermark {
                    position: fixed;
    
                    /** 
                        Set a position in the page for your image
                        This should center it vertically
                    **/
                    bottom:   0cm;
                    left:    10cm;
                    /* opacity: 0.1; */
    
                    /** Change image dimensions**/
                    /* width:    8cm;
                    height:   8cm; */
    
                    /** Your watermark should be behind every content**/
                    /* z-index:  -1000; */
                }
        </style>
    </head>
    <body>
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_text(550, 575, "Page {PAGE_NUM} of {PAGE_COUNT}", '', 9, array(0,0,0));

            }
        </script> 
        @php
        
        if($maxsubjects == 0)
        {
            $eachcell = 65;
        }else{
            $eachcell  = (65/$maxsubjects);
        }
        $studentsperpage  = array_chunk(collect($students)->toArray(), 21);
        $studentcount = 0;
        $schoolname = DB::table('schoolinfo')->first()->schoolname;
        $firstpageno = $firstpageno;
    @endphp
        @if($tabno == 1)
        <br/>
        <br/>
        <div style="width: 100%; text-align: center; font-size: 14px;">{{$schoolname}}</div>
        <div style="width: 100%; text-align: center; font-size: 12px;">{{DB::table('schoolinfo')->first()->address}}</div>
        <br/>
        <div style="width: 100%; text-align: center; font-size: 14px;">PROMOTIONAL REPORT</div>
        <div style="width: 100%; text-align: center; font-size: 14px;">{{$seminfo->semester}} {{$syinfo->sydesc}}</div>
        <br/>
        @endif
        @foreach($studentsperpage as $keypage=>$eachpage)
            <table style="width: 100%; font-size: 8.50px !important; table-layout: fixed;" border="1">
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 2%;">NO.</th>
                        <th colspan="3" style="width: 20%;">STUDENT NAME</th>
                        <th rowspan="2" class='rotate' style="width: 2%;"><div>SEX</div></th>
                        <th rowspan="2" style="width: 10%;">NAME OF HEI</th>
                        <th rowspan="2" style="width: 4%;" class='rotate'><div>COURSE</div></th>
                        <th rowspan="2" style="width: 5%;" >MAJOR</th>
                        <th rowspan="2" class='rotate' style="width: 2%;"><div>YEAR</div></th>
                        <th rowspan="2" style="width: 5%;" >BIRTH<br/>DATE</th>
                        <th rowspan="2" style="width: 5%;" >S.Y.</th>
                        <th rowspan="2" style="width:2%;" >SEM</th>
                        @for($x = 0; $x < $maxsubjects; $x++)
                        <th rowspan="2" class='rotate' style="width:3%;"><div>SUB-{{$x+1}}</div></th>
                        <th rowspan="2" class='rotate' style="width:2%;"><div>UNITS</div></th>
                        <th rowspan="2" class='rotate' style="width:2%;"><div>GRADE</div></th>
                        @endfor
                        <th rowspan="2" class='rotate' style="width: 2%; font-size: 8px"><div>TOTAL<br>UNITS</div></th>
                    </tr>
                    <tr>
                        <th>LAST</th>
                        <th>FIRST</th>
                        <th>MIDDLE</th>
                    </tr>
                </thead>
                @foreach(collect($eachpage)->where('display','1')->values() as $key=>$student)
                    <tr>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->idno}}</td>
                        <td style=" vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->lastname}}</td>
                        <td style=" vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->firstname}}</td>
                        <td style=" vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->middlename}}</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->gender[0]}}</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{ucwords(strtolower($schoolname))}}</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}"> @if($student->major != ' ' && $student->major != '' && $student->major != null) {{str_replace(ucwords(strtolower($student->major)), '', $student->courseabrv)}} @else {{$student->courseabrv}} @endif</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">@if(strlen($student->major) <= 8){{$student->major}}@else <span style="font-size: 6px !important;">{{$student->major}}</span>@endif</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$student->yearid}}</td>
                        <td style=" vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$syinfo->sydesc}}</td>
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{$seminfo->id == '1' ? '1ST' : ($seminfo->id == '2' ? '2ND' : 'Summer')}}</td>
                        @for($x = 0; $x < $maxsubjects; $x++)
                            <td style=" font-size: 8px; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode}} @endif</td>
                            <td style="text-align: center; font-size: 8px; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif</td>
                            <td style="text-align: center; font-size: 8px; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjgrade}} @endif</td>
                        @endfor
                        <td style="text-align: center; vertical-align: top; {{$keypage> 0 ? 'height: 10px;' : ''}}">{{collect($student->subjects)->sum('subjunit')}}</td>
                    </tr>
                @endforeach
            </table>
            <div class="last" style="vertical-align: bottom; bottom: 0; width: 100%; font-size: 12px; text-align: right; padding-right: 400px;
            @if(isset($studentsperpage[$keypage+1])) page-break-after: always; @endif
            ">
                Pages {{$firstpageno}} of {{$lastpageno}}
                @php
                    $firstpageno+=1;
                @endphp
            </div>
        @endforeach
        @if($firstpageno >= $lastpageno)
        @if($firstpageno == $lastpageno)
            <table style="width: 100%; font-size: 9px; table-layout: fixed; page-break-before: always;">
        @elseif($firstpageno > $lastpageno)
                @php
                $firstpageno -= 1;
                @endphp
            <table style="width: 100%; font-size: 9px; table-layout: fixed;">
        @endif
                <thead>
                    <tr>
                        <td>Note:</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr style="text-align: left !important;">
                        <th style="width: 4%;">No.</th>
                        <th style="width: 15%;">Abbreviation of Program</th>
                        <th>Full Name of Program</th>
                    </tr>
                </thead>
                @if(count($courses)>0)
                    @foreach($courses as $coursekey=>$course)
                        <tr>
                            <td>{{$coursekey+1}}</td>
                            <td>{{$course->courseabrv}}</td>
                            <td>{{ucwords(strtolower($course->coursename))}} @if($course->major != ' ' && $course->major != '' && $course->major != null) Major in {{ucwords(strtolower($course->major))}}@endif</td>
                        </tr>
                    @endforeach
                @endif
            </table>
            <br/>
            <br/>
            <table style="width: 100%; font-size: 13px; table-layout: fixed;">
                <tr>
                    <td colspan="2" style="width: 35%; text-align: center;">Submitted by:</td>
                    <td colspan="3" style="width: 65%; padding-left: 35%;">Noted by:</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;</td>
                    <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$registrar}}&nbsp;</td>
                    <td style="width: 30%;"></td>
                    <td style="width: 20%; border-bottom: 1px solid black; text-align: center;">&nbsp;{{$president}}&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td style=" text-align: center;">Registrar</td>
                    <td></td>
                    <td style=" text-align: center;">Principal</td>
                    <td></td>
                </tr>
            </table> 
            <div class="last" style="vertical-align: bottom; bottom: 0; width: 100%; font-size: 12px; text-align: right; padding-right: 400px;
            
            ">
                
                Pages {{$firstpageno}} of {{$lastpageno}}
            </div>
        @endif
    </body>
</html>