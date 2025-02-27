<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <style>
            
            * {
                
                font-family: Arial, Helvetica, sans-serif;
            }
            table, td, th{
                border-collapse: collapse;
                padding: 0px;
            }
    @page {size: 8.5in 13in; margin: 25px 60px;}
    header { position: fixed; top: 5px; left: 0px; right: 0px; height: 240px; }
    footer { position: fixed; bottom: 0px; left: 0px; right: 0px; height: 50px;}
    /* p { page-break-after: always;} */
    p:last-child { page-break-after: never; }
    
    .rotate {
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        width: 1.5em;
        padding: 2px 0px;
        font-size: 11px;
    }
    .rotate div {
        -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
        -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
        -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
                filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
            -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
            margin-left: -10em;
            margin-right: -10em;
    }
        </style>
    </head>
    <body>
        @php
            $preparedby = DB::table('teacher')->where('userid', auth()->user()->id)->where('deleted','0')->first();
            $address = '';
            if($studinfo->street != null)
            {
                $address.=$studinfo->street.', ';
            }
            if($studinfo->barangay != null)
            {
                $address.=$studinfo->barangay.', ';
            }
            if($studinfo->city != null)
            {
                $address.=$studinfo->city.', ';
            }
            if($studinfo->province != null)
            {
                $address.=$studinfo->province;
            }   
            
            $guardianname = '';
            if($studinfo->ismothernum == 1)
            {
                $guardianname = $studinfo->mothername;
            }
            if($studinfo->isfathernum == 1)
            {
                $guardianname = $studinfo->fathername;
            }
            if($studinfo->isguardannum == 1)
            {
                $guardianname = $studinfo->guardianname ?? '';
            }
        @endphp
        <div style="font-size: 11px;">CHED FORM IX</div>
  <header>
        <table style="width: 100%; table-layout: fixed; font-size: 12px; border-bottom: 2px solid black;">
            <tr>
                <td style="text-align: right;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" style="width: 60px;"/></td>
                <td style="width: 35%; text-align: center;">
                    <span style="font-size: 18px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</span><br/>
                    {{DB::table('schoolinfo')->first()->address}}
                </td>
                <td></td>
            </tr>
            <tr>
                <th colspan="3" style="text-align: center;">APPLICATION FOR GRADUATION FROM COLLEGIATE COURSE</td>
            </tr>
        </table>
        <table style="width: 100%; table-layout: fixed; font-size: 10.5px;">
            <tr>
                <td style="width: 15%;">Name</td>
                <td style="width: 40%;" colspan="2">: {{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{$studinfo->middlename}}</td>
                <td style="width: 5%;">Age</td>
                <td style="width: 5%;">: {{$studinfo->age}}</td>
                <td style="width: 5%;">Sex</td>
                <td>: {{$studinfo->gender}}</td>
                <td style="width: 10%;">Civil Status</td>
                <td>: {{$details->civilstatus}}</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>: {{date('F d, Y', strtotime($studinfo->dob))}}</td>
                <td>Place of Birth</td>
                <td colspan="6">: {{$studinfo->pob}}</td>
            </tr>
            <tr>
                <td>City Address</td>
                <td>: {{$studinfo->city}}, {{$studinfo->province}}</td>
                <td>Home Address</td>
                <td colspan="6">: {{$studinfo->street}}, {{$studinfo->barangay}}</td>
            </tr>
            <tr>
                <td>Parent/Guardian</td>
                <td colspan="8">: {{$guardianname}}</td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td colspan="4" style="text-align: center;">ENTRANCE DATA</td>
                <td colspan="3">: {{$details->entrancedata}}</td>
            </tr>
        </table>
        <div style="width: 100%; font-size: 10px;">RECORDS FOR PRELIMINARY EDUCATION</div>
        <div style="width: 100%; font-size: 10px; text-align: center; font-weight: bold;">(School Address)</div>
        <table style="width: 100%; table-layout: fixed; font-size: 10.5px;">
            <tr>
                <td colspan="2">Primary Grades Completed</td>
                <td colspan="2">:</td>
                <td style="width: 5%;">SY</td>
                <td style="width: 15%;">: {{$details->elemsy}}</td>
            </tr>
            <tr>
                <td colspan="2">Intermediate Grades Completed</td>
                <td colspan="2">: {{$details->intermediategrades}}</td>
                <td>SY</td>
                <td>:</td>
            </tr>
            <tr>
                <td colspan="2">Secondary Course Completed</td>
                <td colspan="2">: {{$details->secondarygrades}}</td>
                <td>SY</td>
                <td>: {{$details->secondsy}}</td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 20%;">Title of Degree</td>
                <td colspan="2">: {{$coursename}}</td>
                <td colspan="2">Date of Graduation</td>
                <td>: {{$details->graduationdate}}</td>
            </tr>
            <tr>
                <td>Major</td>
                <td colspan="2">: {{$major}}</td>
                <td colspan="2">Minor</td>
                <td>:</td>
            </tr>
        </table>
        {{-- <div style="width: 100%; font-size: 12px; text-align: center;">
            {{DB::table('schoolinfo')->first()->schoolname}}<br/>
            {{DB::table('schoolinfo')->first()->address}}<br/>
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
            Tel No. (064) 572-4020
            @endif
        </div>
        <br/> --}}
        {{-- <table style="width: 100%; font-size: 11.5px; table-layout: fixed;">
            <tr>
                <th colspan="2">APPLICATION FOR SPECIAL ORDER(FORM IX)</th>
                <td colspan="2" style="text-align: center;"><em>RECORD OF CANDIDATE FOR GRADUATION</em></td>
            </tr>
            <tr>
                <td>Name</td>
                <td style="width: 35%; text-transform: uppercase;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->suffix}}</td>
                <td colspan="2" style="width: 50%;">CANDIDATE FOR TITLE/DEGREE</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>{{date('m/d/Y', strtotime($studinfo->dob))}}</td>
                <td colspan="2">{{$degree}}</td>
            </tr>
            <tr>
                <td>Place of Birth</td>
                <td>{{$address}}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Parent/Guardian</td>
                <td></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td>Address</td>
                <td></td>
                <td colspan="2" style="text-align: center;"><em>PRELIMINARY EDUCATION</em></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Entrance Data</td>
                <td rowspan="2" style="vertical-align: top;">{{$entrancedata}}</td>
                <td style="vertical-align: top; text-align: center;">Completed</td>
                <td style="vertical-align: top; text-align: center;">Name of School/Year</td>
            </tr>
            <tr>
                <td style="vertical-align: top;"></td>
                <td style="vertical-align: top; font-weight: bold;">INTERMEDIATE</td>
                <td style="vertical-align: top; font-weight: bold;"></td>
            </tr>
            <tr>
                <td style="vertical-align: top;"></td>
                <td></td>
                <td style="vertical-align: top; font-weight: bold;">SECONDARY</td>
                <td style="vertical-align: top; font-weight: bold;"></td>
            </tr>
        </table> --}}
  </header>
  
  <footer>
    {{-- <table style="width: 100%; font-size: 12px; padding-top: 15px;">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="width: 50%; text-align: center;">C E R T I F I C A T I O N</td>
        </tr>
        <tr>
            <td colspan="5">Grading System:</td>
        </tr>
        <tr style="font-size: 11px;">
            <td>1.00=98-100</td>
            <td>1.75=89-91</td>
            <td>2.50=80-82</td>
            <td>5.00=Failure</td>
            <td rowspan="4" style="text-align: justify; vertical-align: top; padding-left: 7px;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I hereby certify that forgoing records of <u>&nbsp;{{$studinfo->firstname}} {{$studinfo->middlename}} {{$studinfo->lastname}} {{$studinfo->suffix}}&nbsp;</u>, a candidate for graduation in the <span style="font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</span> has been verified by me and that true copies of the official records substantiating the same as kept in the files of this college.
            </td>
        </tr>
        <tr style="font-size: 11px;">
            <td>1.25=95-97</td>
            <td>2.00=86-88</td>
            <td>2.75=77-79</td>
            <td>NG=No Grade</td>
        </tr>
        <tr style="font-size: 11px;">
            <td>1.50=92-94</td>
            <td>2.25=83-85</td>
            <td>3.00=75-76</td>
            <td>DRP=Dropped</td>
        </tr>
        <tr style="font-size: 11px;">
            <td></td>
            <td></td>
            <td></td>
            <td>INC=Incomplete</td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="4" style="vertical-align: top; text-align: justify; font-size: 11px;">
            One unit of credit is one hour lecture or recitation or two to three hours of laboratory each week for a period of one semester.</td>
            <td style=" padding-left: 7px;">
                Date of Graduation:
                <br/>
                Date Issued:
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <br/>
    <br/> --}}
    <table style="width: 100%; font-size: 11px; table-layout: fixed; text-align: center;">
        <tr>
            <td>{{ucwords(strtolower($preparedby->firstname))}} @if($preparedby->middlename != null){{$preparedby->middlename[0]}}.@endif {{ucwords(strtolower($preparedby->lastname))}} {{$preparedby->suffix}}</td>
            <td>{{date('F d, Y')}}</td>
            <td>{{$collegereg}}</td>
        </tr>
        <tr style="text-align: center;">
            <td><em>Prepared by</em></td>
            <td><em>Date</em></td>
            <td><em>College Registrar</em></td>
        </tr>
        {{-- <tr>
            <td>Prepared by:</td>
            <td>Checked by:</td>
            <td style="font-size: 15px; font-weight: bold;">{{$collegereg}}</td>
        </tr>
        <tr>
            <td>{{ucwords(strtolower($preparedby->title))}}. {{ucwords(strtolower($preparedby->firstname))}} @if($preparedby->middlename != null){{$preparedby->middlename[0]}}.@endif {{ucwords(strtolower($preparedby->lastname))}} {{$preparedby->suffix}}</td>
            <td>{{$checkedby}}</td>
            <td style="font-size: 14px;">College Registrar</td>
        </tr> --}}
    </table>
  </footer>
  <main>
        <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 260px !important;">
            <thead>
                <tr>
                    <td colspan="{{((int)count($subjgroups))+5}}"></td>
                </tr>
                <tr>
                    <th rowspan="2" style="width: 11%; border: 1px solid black;">COURSE NO.</th>
                    <th rowspan="2" style="width: 34%; border: 1px solid black; text-align: center;">DESCRIPTIVE TITLE</th>
                    <th rowspan="2" style="width: 5%; border: 1px solid black; text-align: center; font-size: 8px;" class='rotate'><div>Rating</div></th>
                    <th rowspan="2" style="width: 5%; border: 1px solid black; text-align: center; font-size: 8px;" class='rotate'><div>Re-<br/>Exam</div></th>
                    <th rowspan="2" style="width: 5%; border: 1px solid black; text-align: center; font-size: 8px;" class='rotate'><div>Units</div></th>
                    <th colspan="{{count($subjgroups)}}" style="border: 1px solid black;">CREDITS EARNED BY GROUP</th>
                </tr>
                @if(count($subjgroups)>0)
                <tr>
                    @foreach($subjgroups as $subjgroup)
                    <th style=" border-top: 2px solid black; border: 1px solid black; text-align: center;">{{$subjgroup->sortnum}}</th>
                    @endforeach
                </tr>
                @endif
            </thead>
            @if(collect($collectgradelevels)->where('syid','>','0')->count()>0)
                @foreach(collect($collectgradelevels)->where('syid','>','0')->values() as $eachkey=>$collectgradelevel)
                    <tr>
                        <td colspan="{{((int)count($subjgroups))+5}}" style="padding-top: 3px;">
                            @if($collectgradelevel->syid > 0)
                            <u>{{$collectgradelevel->sydesc}} @if($collectgradelevel->semid == 1)FIRST SEMESTER @elseif($collectgradelevel->semid == 2)SECOND SEMESTER  @else SUMMER @endif {{DB::table('schoolinfo')->first()->schoolname}} - {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}
                            </u>
                            @endif
                        </td>
                    </tr>
                    @if(count($collectgradelevel->subjects)>0)
                        @foreach($collectgradelevel->subjects as $eachsubject)
                            <tr>
                                <td>
                                    {{$eachsubject['subjCode']}}
                                </td>
                                <td>{{$eachsubject['subjDesc']}}
                                </td>
                                <td style="text-align: center;">{{$eachsubject['subjgrade']}}</td>
                                <td></td>
                                <td style="text-align: center;">{{$eachsubject['units']}}
                                </td>
                                @if(count($subjgroups)>0)
                                    @foreach($subjgroups as $key=>$subjgroup)
                                    @php
                                        if(!isset($subjgroup->unitsearned))
                                        {
                                            $subjgroup->unitsearned = 0;
                                        }
                                        if($subjgroup->id == $eachsubject['subjgroupid'])
                                        {
                                        $subjgroup->unitsearned = ($subjgroup->unitsearned+$subjgroup->unitsreq);
                                        }
                                    @endphp
                                    <td style="text-align: center;">@if($subjgroup->id == $eachsubject['subjgroupid']) {{$eachsubject['units']}} @endif
                                    </td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td style="">
                            </td>
                            <td style="">
                            </td>
                            <td style=""></td>
                            <td style=""></td>
                            <th style="">{{collect($collectgradelevel->subjects)->sum('units')}}
                            </th>
                            @if(count($subjgroups)>0)
                                @foreach($subjgroups as $subjgroup)
                                <td style=""></td>
                                @endforeach
                            @endif
                        </tr>
                    @endif
                    @php    
                    
                        if($eachkey<=2)
                        {
                            $collectgradelevel->display = 1;
                            if($eachkey == 2)
                            {                                
                                break;
                            }
                        }
                    @endphp
                @endforeach
            @endif
        </table>
    @if(collect($collectgradelevels)->where('display','0')->where('syid','>','0')->count()>0)
        @php
            $collectgradelevels = collect($collectgradelevels)->where('display','0')->where('syid','>','0')->values();
        @endphp
        <div style="width: 100%; text-align: center; font-size: 12px;">
            *************** CONTINUE ON PAGE 2 ***************
        </div>
      <table style="width: 100%; table-layout: fixed; font-size: 11px; border-bottom: 2px solid black; page-break-before: always;padding-top: 250px !important;">
            <thead>
                <tr>
                    <th style="width: 15%; border-top: 2px solid black; border-bottom: 2px solid black; border-left: 2px solid black;">COURSE NO.</th>
                    <th style="width: 35%; border-top: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black; text-align: center;">DESCRIPTIVE TITLE</th>
                    <th style="border-top: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black;text-align: center;">FINAL</th>
                    <th style="border-top: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black;text-align: center;">UNIT</th>
                    @if(count($subjgroups)>0)
                        @foreach($subjgroups as $subjgroup)
                        <th style=" border-top: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black; text-align: center;">{{$subjgroup->sortnum}}</th>
                        @endforeach
                    @endif
                </tr>
            </thead>
            @if(collect($collectgradelevels)->where('display','0')->where('syid','>','0')->count()>0)
                @foreach(collect($collectgradelevels)->where('display','0')->where('syid','>','0')->values() as $key=>$collectgradelevel)
                    <tr>
                        <td colspan="2" style="border-right: 2px solid black; border-left: 2px solid black;">@if($collectgradelevel->syid > 0)<u>{{DB::table('schoolinfo')->first()->schoolname}} - {{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</u> @endif</td>
                        <td style="border-right: 2px solid black;"></td>
                        <td style="border-right: 2px solid black;"></td>
                        @if(count($subjgroups)>0)
                            @foreach($subjgroups as $subjgroup)
                            <td style="border-right: 2px solid black;"></td>
                            @endforeach
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2" style="border-right: 2px solid black; border-left: 2px solid black;"><u>@if($collectgradelevel->semid == 1)FIRST SEMESTER {{$collectgradelevel->sydesc}} @elseif($collectgradelevel->semid == 2)SECOND SEMESTER {{$collectgradelevel->sydesc}} @else SUMMER @endif</u></td>
                        <td style="border-right: 2px solid black;"></td>
                        <td style="border-right: 2px solid black;"></td>
                        @if(count($subjgroups)>0)
                            @foreach($subjgroups as $subjgroup)
                            <td style="border-right: 2px solid black;"></td>
                            @endforeach
                        @endif
                    </tr>
                    @if(count($collectgradelevel->subjects)>0)
                        @foreach($collectgradelevel->subjects as $eachsubject)
                            <tr>
                                <td style="border-left: 2px solid black;">
                                    {{$eachsubject['subjCode']}}
                                </td>
                                <td style="border-right: 2px solid black;">{{$eachsubject['subjDesc']}}
                                </td>
                                <td style="border-right: 2px solid black;"></td>
                                <td style="border-right: 2px solid black; text-align: center;">{{$eachsubject['units']}}
                                </td>
                                @if(count($subjgroups)>0)
                                    @foreach($subjgroups as $key=>$subjgroup)
                                    @php
                                        if(!isset($subjgroup->unitsearned))
                                        {
                                            $subjgroup->unitsearned = 0;
                                        }
                                        if($subjgroup->id == $eachsubject['subjgroupid'])
                                        {
                                        $subjgroup->unitsearned = ($subjgroup->unitsreq);
                                        }
                                    @endphp
                                    <td style="border-right: 2px solid black; text-align: center;">@if($subjgroup->id == $eachsubject['subjgroupid']) {{$eachsubject['units']}} @endif
                                    </td>
                                    @endforeach
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td style="border-left: 2px solid black;">
                            </td>
                            <td style="border-right: 2px solid black;">
                            </td>
                            <td style="border-right: 2px solid black; border-right: 2px solid black;"></td>
                            <th style="border-top: 2px solid black;border-right: 2px solid black;">{{collect($collectgradelevel->subjects)->sum('units')}}
                            </th>
                            @if(count($subjgroups)>0)
                                @foreach($subjgroups as $subjgroup)
                                <td style="border-right: 2px solid black;"></td>
                                @endforeach
                            @endif
                        </tr>
                    @endif
                    @php    
                        if($key<=3)
                        {
                            $collectgradelevel->display = 1;
                        }
                        if($key == 3)
                        {                                
                            break;
                        }
                    @endphp
                @endforeach
            @endif
        </table>
    @endif
    <div style="width: 100%; text-align: center; font-size: 10px;">
        [xxxxxx END OF RECORD xxxxxx]
    </div>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <div style="width: 100%; text-align: center; font-size: 10px;">
         S&nbsp;&nbsp;&nbsp;&nbsp;U&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;M&nbsp;&nbsp;&nbsp;&nbsp;A&nbsp;&nbsp;&nbsp;&nbsp;R&nbsp;&nbsp;&nbsp;&nbsp;Y
    </div>
    {{-- {{collect($subjgroups)->pluck('unitsearned')}} --}}
    @if(count($subjgroups)>0)
        <table style="width: 100%; font-size: 10px;">
            <tr>
                <th style="text-align: left;"></th>
                <th style="width: 40%; text-align: left;"></th>
                <td style="text-align: center;">REQUIRED UNITS</td>
                <td style="text-align: center;">CREDITS EARNED</td>
            </tr>
            @foreach($subjgroups as $keysubjgroup=>$subjgroup)
                <tr>
                    <td>{{$keysubjgroup+1}}. GROUP {{$subjgroup->sortnum}}</td>
                    <td>- {{$subjgroup->description}} </td>
                    <td style="text-align: center;">{{number_format($subjgroup->unitsreq)}}</td>
                    <td style="text-align: center;">{{number_format($subjgroup->unitsearned)}}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: center;"> </td>
                <td style="text-align: center;"> </td>
                <td style="text-align: center;">{{collect($subjgroups)->sum('unitsreq')}}</td>
                <td style="text-align: center;">{{collect($subjgroups)->sum('unitsearned')}}</td>
            </tr>
        </table>
        <br/>
    @endif
    <table style="width: 100%; font-size: 10px;">
        <tr>
            <td>GRADING SYSTEM</td>
        </tr>
        <tr>
            <td>REMARKS</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>CERTIFICATION</td>
        </tr>
        <tr>
            <td style="text-align: justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                I hereby certify {{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{$studinfo->middlename}}, a candidate for graduation in this institution, having been verified by me and that original of the official records substantiating same are kept in the file for our school. I do certify that this student enrolled in our institution on FIRST SEMESTER of the current school year.
            </td>
        </tr>
    </table>
  </main>
    </body>
</html>
