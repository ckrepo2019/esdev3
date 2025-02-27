<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <style>
            
            * {
                
                font-family: Arial, Helvetica, sans-serif;
            }
            table, td, th{
                border-collapse: collapse;
            }
    @page {size: 8.5in 13in; margin: 45px 70px;}
    header { position: fixed; top: 5px; left: 0px; right: 0px; height: 240px; }
    footer { position: fixed; bottom: 20px; left: 0px; right: 0px; height: 250px; }
    /* p { page-break-after: always;} */
    p:last-child { page-break-after: never; }
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
            $parent = '';
            if($studinfo->ismothernum == 1)
            {
                $parent = $studinfo->mothername;
            }
            elseif($studinfo->isfathernum == 1)
            {
                $parent = $studinfo->fathername;
            }
            elseif($studinfo->isguardannum == 1)
            {
                $parent = $studinfo->guardianname;
            }
        @endphp
  <header>
        
		<table style="width: 100%; table-layout: fixed;">
			<tr>
				<td style="text-align: right; vertical-align: top;" width="10%">
					<img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px">
				</td>
				<td style="text-align: center;"  width="80%">
					 <div style="width: 100%; font-size: 12px; text-align: center;">
						{{DB::table('schoolinfo')->first()->schoolname}}<br/>
						{{DB::table('schoolinfo')->first()->address}}<br/>
						@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
						Tel No. (064) 572-4020
						@endif
					</div>
				</td>
				<td  width="10%"></td>
			</tr>
		</table>
        <br/>
        <table style="width: 100%; font-size: 11.5px; table-layout: fixed;">
            <tr>
                <th colspan="2">APPLICATION FOR SPECIAL ORDER(FORM IX)</th>
                <td colspan="2" style="text-align: center;"><em>RECORD OF CANDIDATE FOR GRADUATION</em></td>
            </tr>
            <tr>
                <td>Name</td>
                <td style="width: 35%; text-transform: uppercase;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{$studinfo->middlename}}</td>
                <td colspan="2" style="width: 50%;">CANDIDATE FOR TITLE/DEGREE</td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td>{{date('m/d/Y', strtotime($studinfo->dob))}}</td>
                <td colspan="2">{{$degree != null ? $degree : $coursename}}</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Place of Birth</td>
                <td>{{$studinfo->pob != null ? $studinfo->pob : ($details->pob ?? '')}}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Parent/Guardian</td>
                <td>{{$parent}}</td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Address</td>
                <td>{{$address}}</td>
                <td colspan="2" style="text-align: center;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')<em>PRELIMINARY EDUCATION</em>@endif</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Entrance Data</td>
                <td rowspan="2" style="vertical-align: top;">{{$entrancedata}}</td>
                <td style="vertical-align: top; text-align: center;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')Completed @endif</td>
                <td style="vertical-align: top; text-align: center;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')Name of School/Year @endif</td>
            </tr>
            <tr>
                <td style="vertical-align: top;"></td>
                <td style="vertical-align: top; font-weight: bold;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')INTERMEDIATE @endif</td>
                <td style="vertical-align: top; font-weight: bold;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){{$details->intermediatecourse.' /' ?? ''}} {{$details->intermediatesy ?? ''}} @endif</td>
            </tr>
            <tr>
                <td style="vertical-align: top;"></td>
                <td></td>
                <td style="vertical-align: top; font-weight: bold;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')SECONDARY @endif</td>
                <td style="vertical-align: top; font-weight: bold;"> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'){{$details->secondcourse.' /' ?? ''}} {{$details->secondsy ?? ''}} @endif</td>
            </tr>
        </table>
  </header>
  
  <footer>
    <table style="width: 100%; font-size: 12px; padding-top: 15px;">
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
    <br/>
    <table style="width: 100%; font-size: 12px; table-layout: fixed; text-align: center;">
        <tr>
            <td>Prepared by:</td>
            <td>Checked by:</td>
            <td style="font-size: 15px; font-weight: bold;">{{$collegereg}}</td>
        </tr>
        <tr>
            <td>{{ucwords(strtolower($preparedby->title))}}. {{ucwords(strtolower($preparedby->firstname))}} @if($preparedby->middlename != null){{$preparedby->middlename[0]}}.@endif {{ucwords(strtolower($preparedby->lastname))}} {{$preparedby->suffix}}</td>
            <td>{{$checkedby}}</td>
            <td style="font-size: 14px;">College Registrar</td>
        </tr>
    </table>
  </footer>
  <main>
        <table style="width: 100%; table-layout: fixed; font-size: 11px; border-bottom: 2px solid black;padding-top: 250px !important;">
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
            @if(collect($collectgradelevels)->where('syid','>','0')->count()>0)
                @foreach(collect($collectgradelevels)->where('syid','>','0')->values() as $eachkey=>$collectgradelevel)
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
                                <td style="border-right: 2px solid black; text-align: center;">{{$eachsubject['subjgrade']}}</td>
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
                                        $subjgroup->unitsearned = ($subjgroup->unitsearned+$subjgroup->unitsreq);
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
    <div style="width: 100%; text-align: center; font-size: 11px;">
        *************** Closed ***************
        <br/>
        Recommended for graduation from the Two-Year Course leading to the title of {{$degree}}.
    </div>
    {{-- {{collect($subjgroups)->pluck('unitsearned')}} --}}
    @if(count($subjgroups)>0)
        <table style="width: 100%; font-size: 11px; margin-left: 80px; margin-right: 80px;" border="1">
            <tr>
                <th style="width: 50%; text-align: left;">Subject Groupingss</th>
                <td style="text-align: center;">Units Required</td>
                <td style="text-align: center;">Units Earned</td>
            </tr>
            @foreach($subjgroups as $subjgroup)
                {{-- @php
                    $unitsearned = 0;
                    foreach($collectgradelevels as $eachcollectgradelevel)
                    {
                        if(count($eachcollectgradelevel->subjects)>0)
                        {
                            foreach($eachcollectgradelevel->subjects as $eachsubject)
                            {
                                if($subjgroup->id == $eachsubject['subjgroupid'])
                                {
                                    $unitsearned+=$eachsubject['units'];
                                }
                            }
                        }
                    }
                    $subjgroup->unitsearned = $unitsearned;
                @endphp --}}
                <tr>
                    <td>{{$subjgroup->sortnum}}. {{$subjgroup->description}} </td>
                    <td style="text-align: center;">{{number_format($subjgroup->unitsreq)}}</td>
                    <td style="text-align: center;">{{number_format($subjgroup->unitsearned)}}</td>
                </tr>
            @endforeach
            <tr>
                <td style="text-align: center;">Total</td>
                <td style="text-align: center;">{{collect($subjgroups)->sum('unitsreq')}}</td>
                <td style="text-align: center;">{{collect($subjgroups)->sum('unitsearned')}}</td>
            </tr>
        </table>
    @endif
  </main>
    </body>
</html>
