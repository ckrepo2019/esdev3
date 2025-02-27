<!DOCTYPE html>
<html lang="en">
    <head>
        <title>SHS - School Form 10 (This template is provided by the school)</title>
        <style>
            * { font-family: Arial, Helvetica, sans-serif; line-height: 11px;}
            @page { margin: 15px 20px 0px 20px; size: {{$papersize != null ? $papersize : '8.5in 13in'}};} /*8.5 13*/

            #table1 td{
                padding: 0px;
            }
            table {
                border-collapse: collapse;
            }
            #table2{
                margin-top: 2px;
                font-size: 11px;
            }

            input[type="checkbox"] {
            /* position: relative; */
            top: 2px;
            box-sizing: content-box;
            width: 14px;
            height: 14px;
            margin: 0 5px 0 0;
            cursor: pointer;
            -webkit-appearance: none;
            border-radius: 2px;
            background-color: #fff;
            border: 1px solid #b7b7b7;
            }

            input[type="checkbox"]:before {
            content: '';
            display: block;
            }

            input[type="checkbox"]:checked:before {
            width: 4px;
            height: 9px;
            margin: 0px 4px;
            border-bottom: 2px solid ;
            border-right: 2px solid ;
            transform: rotate(45deg);
            }
            .text-center{
                text-align: center;
            }
            table{
                page-break-inside: avoid !important; 
            }
        </style>
    </head>
    <body>
        @php
            $guardianinfo = DB::table('studinfo')
                ->where('id',$studinfo->id)
                ->first();
            $guardianname = '';
            if($guardianinfo->isfathernum == 1)
            {
                $explodename = explode(',',$guardianinfo->fathername);
                if(count($explodename)>1)
                {
                    $guardianname.='MR. AND MRS. ';
                    $explodelastname = $explodename[0];
                    
                    $firstname = explode(' ',$explodename[1]);
                    if(count($firstname) < 3)
                    {
                        $guardianname.=$firstname[0];
                    }
                    else
                    {
                        $guardianname.=$firstname[0].' '.$firstname[1].' ';
                    }
                    $guardianname.=$explodelastname;
                }
            }
            if($guardianinfo->ismothernum == 1)
            {
                $guardianname = $guardianinfo->mothername;
            }
            if($guardianinfo->isguardannum == 1)
            {
                $guardianname = $guardianinfo->guardianname;
            }
            $address = '';
            if($guardianinfo->street != null)
            {
                $address.=$guardianinfo->street.', ';
            }
            if($guardianinfo->barangay != null)
            {
                $address.=$guardianinfo->barangay.', ';
            }
            if($guardianinfo->city != null)
            {
                $address.=$guardianinfo->city.', ';
            }
            if($guardianinfo->province != null)
            {
                $address.=$guardianinfo->province;
            }
            $studstatdate = '';
            $sh_enrolledstud = DB::table('sh_enrolledstud')
                ->where('studid', $studinfo->id)
                ->where('levelid', $studinfo->levelid)
                ->where('semid', $studinfo->semid)
                ->where('deleted','0')
                ->first();
        
            if($sh_enrolledstud)
            {
                if($sh_enrolledstud->dateenrolled == null)
                {
        
                }else{
                    $studstatdate.=date('F d, Y', strtotime($sh_enrolledstud->dateenrolled));
                }
            }else{
        
            }
            function lower_case($word)
            {
                return \App\Http\Controllers\SchoolFormsController::lowercase_word($word);
            }
        
        @endphp
        @if(count($records)>0)
            @foreach($records as $recordkey => $record)
                <table style="width: 100%" id="table1">
                    <tr>
                        <td width="10%" rowspan="5"><sup style="font-size: 9px;">SF10-SHS</sup><br/><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="65px"></td>
                        <td style="text-align:center; font-size: 11px;">Republic of the Philippines</td>
                        <td width="10%" style="text-align:right;" rowspan="5"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="90px"></td>
                    </tr>
                    <tr>
                        <td style="text-align:center; font-size: 11px;">Department of Education</td>
                    </tr>
                    <tr>
                        <td style="text-align:center; font-size: 13px; font-weight: bold; text-transform: uppercase;">Learner's Permanent Academic Record for Senior High School </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; font-size: 13px; font-weight: bold;">(SF10-SHS)</td>
                    </tr>
                    <tr style="line-height: 5px;font-size: 11px;">
                        <td style="text-align:center; font-style: italic;">(Formerly Form 137)</td>
                    </tr>
                </table>
                <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                <table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.5rem !important;">
                    <tr>
                        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">LEARNER'S INFORMATION</td>
                    </tr>
                </table>
                <table class="table table-sm" width="100%" style="font-size: 10px !important; margin-top:.2rem !important;">
                    <tr>
                        <td style="width: 10%;">LAST NAME:</td>
                        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->lastname}}</td>
                        <td style="width: 10%;">FIRST NAME:</td>
                        <td style="width: 25%; border-bottom: 1px solid black;">{{$studinfo->firstname}} {{$studinfo->suffix}}</td>
                        <td style="width: 11%;">MIDDLE NAME:</td>
                        <td style="width: 19%; border-bottom: 1px solid black;">{{$studinfo->middlename}}</td>
                    </tr>
                </table>
                <table class="table table-sm" width="100%" style="font-size: 10px !important;">
                    <tr>
                        <td style="width: 18%; ">Date of Birth (MM/DD/YYYY):</td>
                        <td style="width: 15%; border-bottom: 1px solid black;">{{\Carbon\Carbon::create($studinfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
                        <td style="width: 8%;">Birth Place:</td>
                        <td style="width: 29%; border-bottom: 1px solid black; font-size: 8px !important; ">{{$address}}</td>
                        <td style="width: 3%; text-align: right;">Sex:</td>
                        <td style="width: 7%; border-bottom: 1px solid black;">{{$studinfo->gender}}</td>
                        <td style="width: 5%;">LRN:</td>
                        <td style="width: 15%; border-bottom: 1px solid black;">{{$studinfo->lrn}}</td>
                    </tr>
                </table>
                <table class="table table-sm" width="100%" style="font-size: 10px !important;">
                    <tr>
                        <td style="width: 10%; ">Parent/Guardian:</td>
                        <td style="width: 20%; border-bottom: 1px solid black; font-size: 10px !important;">{{$guardianname}}</td>
                        <td style="width: 7%;">Address:</td>
                        <td style="width: 25%; border-bottom: 1px solid black; font-size: 8px !important;">{{$address}}</td>
                        <td colspan="2" style="width: 20%;">Date of Admission (MM/DD/YYYY):</td>
                        <td colspan="2" style="border-bottom: 1px solid black; width: 12%;">{{$studstatdate}}</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.2rem !important;">
                    <tr>
                        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;"> ELIGIBILITY FOR SHS ENROLMENT</td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important; margin-top:.2rem !important;">
                    <tr>
                        <td width="2%" style="border:solid 1px black" class="text-center">
                            @if($eligibility->completerhs == '1')
                            <b>/</b>
                            @endif
                        </td>
                        <td width="16%">
                            High School Completer*
                        </td>
                        <td width="7%">
                            Gen. Ave: 
                        </td>
                        <td width="7%" style="border-bottom: 1px solid;">
                            @if($eligibility->completerhs == '1')
                                {{$eligibility->genavehs}}
                            @endif
                        </td>
                        <td width="2%"></td>
                        <td width="2%" style="border:solid 1px black" class="text-center">
                            @if($eligibility->completerjh == '1')
                                <b>/</b>
                            @endif
                        </td>
                        <td width="21%">
                            Junior High School Completer*
                        </td>
                        <td width="7%">
                            Gen. Ave: 
                        </td>
                        <td width="7%" style="border-bottom: 1px solid;">
                            @if($eligibility->completerjh == '1')
                                {{$eligibility->genavejh}}
                            @endif
                        </td>
                        <td width="30%"></td>
                    </tr>
                </table>
                <table class="table table-sm table-bordered" width="100%" style="font-size: 10px !important;">
                    <tr>
                        <td width="28%">Date of Graduation/Completing (MM/DD/YYYY):</td>
                        <td style="border-bottom: 1px solid;"  width="9%">
                            {{$eligibility->graduationdate}}
                        </td>
                        <td  width="10%">Name of School:</td>
                        <td style="border-bottom: 1px solid;font-size: 9px !important;"  width="25%">
                            {{$eligibility->schoolname}}
                        </td>
                        <td style=""  width="10%">School Address:</td>
                        <td style="border-bottom: 1px solid;font-size: 9px !important;"  width="18%">
                            {{$eligibility->schooladdress}}
                        </td>
                    </tr>
                </table>
        
                <table class="table table-sm table-bordered" width="100%" style="font-size: 11px !important; margin-top:.2rem !important;">
                    <tr>
                        <td class="text-center" style="font-weight: bold; background-color: #aba9a9; border: 1px solid black;">SCHOLASTIC RECORD</td>
                    </tr>
                </table>
                <div style="width: 100%; line-height: 4px;">&nbsp;</div>
                <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                    <thead style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">
                        <tr>
                            <td colspan="6">
                                <table style="width: 100%; font-size: 9px;">
                                    <tr>
                                        <td style="width: 5%;">School:</td>
                                        <td style="width: 38%; border-bottom: 1px solid black;">{{$record[0]->schoolname}}</td>
                                        <td style="width: 7%;">School ID:</td>
                                        <td style="width: 7%; border-bottom: 1px solid black;">{{$record[0]->schoolid}}</td>
                                        <td style="width: 10%;">GRADE LEVEL:</td>
                                        <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[0]->levelname)}}</td>
                                        <td style="width: 2%;">SY:</td>
                                        <td style="width: 13%; border-bottom: 1px solid black;">{{$record[0]->sydesc}}</td>
                                        <td style="width: 4%;">SEM:</td>
                                        <td style="width: 6%; border-bottom: 1px solid black;">@if($record[0]->semid == 1) 1st @elseif($record[0]->semid == 2) 2nd @endif</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; font-size: 9px;">
                                    <tr>
                                        <td style="width: 10%;">TRACK/STRAND:</td>
                                        <td style="width: 60%; border-bottom: 1px solid black;">{{$record[0]->trackname}} / {{$record[0]->strandname}}</td>
                                        <td style="width: 7%;">SECTION:</td>
                                        <td style="width: 23%; border-bottom: 1px solid black;">{{$record[0]->sectionname}}</td>
                                    </tr>
                                </table>
                                <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                            <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                            <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                            <th rowspan="2" style="width: 10%; border: solid 1px black;">SEM FINAL<br/>GRADE</th>
                            <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                        </tr>
                        <tr >
                            <th style="width: 8%; border: solid 1px black;">1</th>
                            <th style="width: 8%; border: solid 1px black;">2</th>
                        </tr>
                    </thead>
                    <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                        @if(collect($record[0]->grades)->where('semid',$record[0]->semid)->unique('subjdesc')->count()>0)
                            @php
                                $gen_ave_for_sem = 0;
                                $with_final_rating = true;
                            @endphp
                            @foreach(collect($record[0]->grades)->where('semid',$record[0]->semid)->unique('subjdesc') as $grade)
                                @php
                                    $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                                    $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                                    $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                                @endphp
                                @if($record[0]->type == 2)
                                    @if(strtolower($grade->subjdesc) != 'general average')
                                        <tr>
                                            <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                            <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->q1 < 75) color: red; @endif">{{$grade->q1}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->q2 < 75) color: red; @endif">{{$grade->q2}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->finalrating}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->remarks}}</td>
                                        </tr>
                                    @endif
                                @else
                                    @if(strtolower($grade->subjdesc) != 'general average')
                                        <tr>
                                            <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
                                            <td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->q1 < 75) color: red; @endif">@if($grade->q1>0){{number_format($grade->q1)}}@endif</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->q2 < 75) color: red; @endif">@if($grade->q2>0){{number_format($grade->q2)}}@endif</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">@if($grade->finalrating>0){{$grade->finalrating}}@endif</td>
                                            <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->remarks}}</td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            
                            @if($record[0]->type == 1)
                                @if(count($record[0]->subjaddedforauto)>0)
                                    @foreach($record[0]->subjaddedforauto as $customsubjgrade)
                                        <tr>
                                            <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                            <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->q1 < 75) color: red; @endif">{{number_format($customsubjgrade->q1)}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->q2 < 75) color: red; @endif">{{number_format($customsubjgrade->q2)}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->finalrating < 75) color: red; @endif">{{$customsubjgrade->finalrating}}</td>
                                            <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->finalrating < 75) color: red; @endif">{{$customsubjgrade->actiontaken}}</td>
                                        </tr>
                                    @endforeach
                                    <?php try{ ?> 
                                        <tr style="font-weight: bold;">
                                            @php
                                                $finalratingoverall = number_format((collect($record[0]->grades)->where('semid',$record[0]->semid)->sum('finalrating')+collect($record[0]->subjaddedforauto)->sum('finalrating'))/(collect($record[0]->grades)->where('semid',$record[0]->semid)->count()+collect($record[0]->subjaddedforauto)->count()))
                                            @endphp
                                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                            <td class="text-center" style="border: solid 1px black;">{{ $finalratingoverall }}</td>
                                            <td class="text-center" style="border: solid 1px black;">{{ $finalratingoverall ? $finalratingoverall >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                                        </tr>
                                        
                                    <?php }catch(\Exception $e){ ?>
                                        <tr style="font-weight: bold;">
                                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                            <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                                            <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                                        </tr>
                                    <?php } ?>
                                @else
                                <tr style="font-weight: bold;">
                                    @php
                                        $with_final = collect($record[0]->grades)->where('semid',$record[0]->semid)->where('q1','!=,',null)->count() == 0 && collect($record[0]->grades)->where('semid',$record[0]->semid)->where('q2','!=,',null)->count() == 0 ? true:false;
                                    @endphp
                                    <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                    <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',$record[0]->semid)->count()) : '' }}</td>
                                    <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[0]->grades)->where('semid',$record[0]->semid)->count()) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                                </tr>
                                @endif
                            @elseif($record[0]->type == 2)
                                @if(count($record[0]->grades) > 1)
                                    @foreach($record[0]->grades as $grade)
                                        @if(strtolower($grade->subjdesc) == 'general average')
                                            <tr style="font-weight: bold;">
                                                <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                                <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                                <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                            @endif
                            
                        @else
                        
                            @for($x=0; $x<10; $x++)
                                <tr >
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                    <td style="border: solid 1px black;">&nbsp;</td>
                                </tr>
                            @endfor
                        @endif
                    <tbody>
                </table>
                
                @php
                    $certncorrectname = '';
                    $certncorrectdesc = '';
                    $datechecked = '';       
                    $eachsemremarks = '';
                    if(collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first())
                    {
                        $signinfo = collect($eachsemsignatories)->where('levelid', $record[0]->levelid)->where('semid',$record[0]->semid)->first();
                        $certncorrectname = $signinfo->certncorrectname;
                        $certncorrectdesc = $signinfo->certncorrectdesc;
                        $eachsemremarks = $signinfo->remarks;
                        $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                    }else{
                        $certncorrectname = $record[0]->recordincharge;
                        $certncorrectdesc = "SHS-School Record's In-Charge";
                        $datechecked = $record[0]->datechecked != null ?  date('M d, Y',strtotime($record[0]->datechecked)) : '';
                    }
                @endphp
                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 2px;">
                    <tr>
                        <td style="width: 10%;">REMARKS:</td>
                        <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                    </tr>
                    {{-- <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr> --}}
                </table>
                <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td>Prepared by:</td>
                        <td></td>
                        <td>&nbsp;&nbsp;Certified True and Correct:</td>
                        <td></td>
                        <td>Date Checked (MM/DD/YYYY)</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                    </tr>
                    <tr style="font-size:  9px;">
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[0]->teachername}}</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$certncorrectname}}</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$datechecked}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Signature of Adviser over Printed Name</td>
                        <td></td>
                        <td class="text-center">{{$certncorrectdesc}}</td>
                        <td></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                </table>
                <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                @php
                    $eachsemremedial_teachername = '';
                @endphp
                <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                    @if(collect($record[0]->remedials)->contains('type','2'))
                        @php
                            $eachsemremedial_header = collect($record[0]->remedials)->where('type','2')->values();
                            $eachsemremedial_teachername = $eachsemremedial_header[0]->teachername;
                        @endphp
                        <tr>
                            <td style="width: 15%;">REMEDIAL CLASSES</td>
                            <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                            <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->datefrom != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->datefrom)) : ''}}</td>
                            <td style="width: 13%;">to (MM/DD/YYYY):</td>
                            <td style="width: 10%; border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->dateto != null ? date('m/d/Y', strtotime($eachsemremedial_header[0]->dateto)) : ''}}</td>
                            <td>SCHOOL:</td>
                            <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolname}}</td>
                            <td>SCHOOL ID:</td>
                            <td style="border-bottom: 1px solid black;">{{$eachsemremedial_header[0]->schoolid}}</td>
                        </tr>
                    @else
                    <tr>
                        <td style="width: 15%;">REMEDIAL CLASSES</td>
                        <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;"></td>
                        <td style="width: 13%;">to (MM/DD/YYYY):</td>
                        <td style="width: 10%; border-bottom: 1px solid black;"></td>
                        <td>SCHOOL:</td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td>SCHOOL ID:</td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                    @endif
                </table>
                <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 9px; text-transform: uppercase;" border="1">
                    
                    <tr style="font-size: 9px !important;">
                        <th style="width: 10%;">INDICATE IF
                            SUBJECT IS
                            CORE, APPLIED,
                            OR
                            SPECIALIZED</th>
                        <th style="width: 40%;">SUBJECTS</th>
                        <th style="width: 10%;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')SEM @endif FINAL<br/>GRADE</th>
                        <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                        <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                        <th style="width: 10%;">ACTION TAKEN</th>
                    </tr>
                    @if(collect($record[0]->remedials)->contains('type','1'))
                        @foreach($record[0]->remedials as $remedial)
                            @if($remedial->type == 1)
                                <tr>
                                    <td class="text-center">{{$remedial->subjectcode}}</td>
                                    <td>{{$remedial->subjectname}}</td>
                                    <td class="text-center">{{$remedial->finalrating}}</td>
                                    <td class="text-center">{{$remedial->remclassmark}}</td>
                                    <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                    <td class="text-center">{{$remedial->remarks}}</td>
                                </tr>
                            @endif
                        @endforeach
                    @else
                        @for($x = 0; $x<3 ; $x++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    @endif 
                </table>
                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 2px;">
                    <tr>
                        <td style="width: 20%;">Name of Teacher/Adviser:</td>
                        <td style="width: 60%; border-bottom: 1px solid black;" colspan="2">{{$eachsemremedial_teachername}}</td>
                        <td style="width: 10%;">Signature:</td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                </table>
                    
                @if(count($record) == 2)
                    <div style="width: 100%; line-height: 4px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                        <thead style="border-left: 2px solid black; border-right: 2px solid black; border-top: 2px solid black">
                            <tr>
                                <td colspan="6">
                                    <table style="width: 100%; font-size: 10px;">
                                        <tr>
                                            <td style="width: 5%;">School:</td>
                                            <td style="width: 38%; border-bottom: 1px solid black;">{{$record[1]->schoolname}}</td>
                                            <td style="width: 7%;">School ID:</td>
                                            <td style="width: 7%; border-bottom: 1px solid black;">{{$record[1]->schoolid}}</td>
                                            <td style="width: 10%;">GRADE LEVEL:</td>
                                            <td style="width: 8%; border-bottom: 1px solid black;">{{preg_replace('/\D+/', '', $record[1]->levelname)}}</td>
                                            <td style="width: 2%;">SY:</td>
                                            <td style="width: 13%; border-bottom: 1px solid black;">{{$record[1]->sydesc}}</td>
                                            <td style="width: 4%;">SEM:</td>
                                            <td style="width: 6%; border-bottom: 1px solid black;">@if($record[1]->semid == 1) 1st @elseif($record[1]->semid == 2) 2nd @endif</td>
                                        </tr>
                                    </table>
                                    <table style="width: 100%; font-size: 10px;">
                                        <tr>
                                            <td style="width: 10%;">TRACK/STRAND:</td>
                                            <td style="width: 60%; border-bottom: 1px solid black;">{{$record[1]->trackname}} / {{$record[1]->strandname}}</td>
                                            <td style="width: 7%;">SECTION:</td>
                                            <td style="width: 23%; border-bottom: 1px solid black;">{{$record[1]->sectionname}}</td>
                                        </tr>
                                    </table>
                                    <div style="width: 100%; line-height: 3px;">&nbsp;</div>
                                </td>
                            </tr>
                            <tr>
                                <th rowspan="2" style="width: 10%; border: solid 1px black;">INDICATE IF SUBJECT IS CORE, APPLIED, OR SPECIALIZED</th> //grade 11 1st sem
                                <th rowspan="2" style="width: 40%; border: solid 1px black;">SUBJECTS</th>
                                <th colspan="2" style="width: 10%; border: solid 1px black;">Quarter</th>
                                <th rowspan="2" style="width: 10%; border: solid 1px black;">SEM FINAL<br/>GRADE</th>
                                <th rowspan="2" style="width: 10%; border: solid 1px black;">ACTION<br/>TAKEN</th>
                            </tr>
                            <tr >
                                <th style="width: 8%; border: solid 1px black;">1</th>
                                <th style="width: 8%; border: solid 1px black;">2</th>
                            </tr>
                        </thead>
                        <tbody  style="border-left: 2px solid black; border-right: 2px solid black; border-bottom: 2px solid black">
                            @if(collect($record[1]->grades)->where('semid',$record[1]->semid)->unique('subjdesc')->count()>0)
                                @php
                                    $gen_ave_for_sem = 0;
                                    $with_final_rating = true;
                                @endphp
                                @foreach(collect($record[1]->grades)->where('semid',$record[1]->semid)->unique('subjdesc') as $grade)
                                    @php
                                        $with_final_rating = $grade->q1 != null && $grade->q2 != null ? true : false;
                                        $average = $with_final_rating ? ($grade->q1 + $grade->q2 ) / 2 : '';
                                        $gen_ave_for_sem += $with_final_rating ? number_format($average) : 0;
                                    @endphp
                                    @if($record[1]->type == 2)
                                        @if(strtolower($grade->subjdesc) != 'general average')
                                            <tr>
                                                <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
												<td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->q1 < 75) color: red; @endif">{{$grade->q1}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->q2 < 75) color: red; @endif">{{$grade->q2}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->finalrating}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->remarks}}</td>
                                            </tr>
                                        @endif
                                    @else
                                        @if(strtolower($grade->subjdesc) != 'general average')
                                            <tr>
                                                <td class="text-center" style="border: solid 1px black;">{{$grade->subjcode}}</td>
												<td style="border: solid 1px black;">{{lower_case($grade->subjdesc)}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->q1 < 75) color: red; @endif">@if($grade->q1>0){{number_format($grade->q1)}}@endif</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->q2 < 75) color: red; @endif">@if($grade->q2>0){{number_format($grade->q2)}}@endif</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">@if($grade->finalrating>0){{$grade->finalrating}}@endif</td>
                                                <td class="text-center" style="border: solid 1px black; @if($grade->finalrating < 75) color: red; @endif">{{$grade->remarks}}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                
                                @if($record[1]->type == 1)
                                    @if(count($record[1]->subjaddedforauto)>0)
                                        @foreach($record[1]->subjaddedforauto as $customsubjgrade)
                                            <tr>
                                                <td class="text-center" style="border: solid 1px black;">{{$customsubjgrade->subjcode}}</td>
                                                <td style="border: solid 1px black;">{{$customsubjgrade->subjdesc}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->q1 < 75) color: red; @endif">{{number_format($customsubjgrade->q1)}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->q2 < 75) color: red; @endif">{{number_format($customsubjgrade->q2)}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->finalrating < 75) color: red; @endif">{{$customsubjgrade->finalrating}}</td>
                                                <td class="text-center" style="border: solid 1px black; @if($customsubjgrade->finalrating < 75) color: red; @endif">{{$customsubjgrade->actiontaken}}</td>
                                            </tr>
                                        @endforeach
                                        <?php try{ ?> 
                                            <tr style="font-weight: bold;">
                                                @php
                                                    $finalratingoverall = number_format((collect($record[1]->grades)->where('semid',$record[1]->semid)->sum('finalrating')+collect($record[1]->subjaddedforauto)->sum('finalrating'))/(collect($record[1]->grades)->where('semid',$record[1]->semid)->count()+collect($record[1]->subjaddedforauto)->count()))
                                                @endphp
                                                <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                                <td class="text-center" style="border: solid 1px black;">{{ $finalratingoverall }}</td>
                                                <td class="text-center" style="border: solid 1px black;">{{ $finalratingoverall ? $finalratingoverall >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                                            </tr>
                                            
                                        <?php }catch(\Exception $e){ ?>
                                            <tr style="font-weight: bold;">
                                                <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                                <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                                                <td class="text-center" style="border: solid 1px black;">&nbsp;</td>
                                            </tr>
                                        <?php } ?>
                                    @else                            
                                        <tr style="font-weight: bold;">
                                            @php
                                                $with_final = collect($record[1]->grades)->where('semid',$record[1]->semid)->where('q1','!=,',null)->count() == 0 && collect($record[1]->grades)->where('semid',$record[1]->semid)->where('q2','!=,',null)->count() == 0 ? true:false;
                                            @endphp
                                            <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[1]->grades)->where('semid',$record[1]->semid)->count()) : '' }}</td>
                                            <td class="text-center" style="border: solid 1px black;">{{ $with_final ? number_format($gen_ave_for_sem / collect($record[1]->grades)->where('semid',$record[1]->semid)->count()) >= 75 ? 'PASSED' : 'FAILED' : '' }}</td>
                                        </tr>
                                    @endif
                                @elseif($record[1]->type == 2)
                                    @if(count($record[1]->grades) > 1)
                                        @foreach($record[1]->grades as $grade)
                                            @if(strtolower($grade->subjdesc) == 'general average')
                                                <tr style="font-weight: bold;">
                                                    <td colspan="4" style="text-align: right; border: solid 1px black;">General Average</td>
                                                    <td class="text-center" style="border: solid 1px black;">{{$grade->finalrating}}</td>
                                                    <td class="text-center" style="border: solid 1px black;">{{$grade->remarks}}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endif
                                
                            @else
                            
                                @for($x=0; $x<10; $x++)
                                    <tr >
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                        <td style="border: solid 1px black;">&nbsp;</td>
                                    </tr>
                                @endfor
                            @endif
                        <tbody>
                    </table>
                <div style="width: 100%; line-height: 2px;">&nbsp;</div>
                
                {{-- @if(count($record[1]->attendance)==0)
                
                    <table style="width: 100%; table-layout: fixed; font-size: 10px; border: 1px solid black;" border="1">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tr>
                            <td>No. of School Days</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            
                        </tr>
                        <tr>
                            <td>No. of Days Present</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>No. of Days Absent</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
        
                @else --}}
                {{-- {{collect($record[1]->attendance[1])}} --}}
                    <table style="width: 100%; table-layout: fixed; font-size: 8.5px; border: 1px solid black;" border="1">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width: 20%; line-height: 5px;">&nbsp;</th>
                                @if(isset($record[0]->attendance[0]))
                                <th colspan="{{count($record[0]->attendance[0])+1}}">1st Sem</th>
                                @endif
                                @if(isset($record[1]->attendance[1]))
                                <th colspan="{{count($record[1]->attendance[1])+1}}">2nd Sem</th>
                                @endif
                            </tr>
                            <tr>
                                @if(isset($record[0]->attendance[0]))
                                @if(count($record[0]->attendance[0])>0)
                                    @foreach($record[0]->attendance[0] as $att)
                                        <th>{{substr($att->monthdesc,0,3)}}</th>
                                    @endforeach
                                @endif
                                <th>Total</th>
                                @endif
                                @if(isset($record[1]->attendance[1]))
                                @if(count($record[1]->attendance[1])>0)
                                    @foreach($record[1]->attendance[1] as $att)
                                        <th>{{substr($att->monthdesc,0,3)}}</th>
                                    @endforeach
                                    <th>Total</th>
                                @endif
                                @endif
                            </tr>
                        </thead>
                        <tr>
                            <td>No. of School Days</td>
                            @if(isset($record[0]->attendance[0]))
                            @if(count($record[0]->attendance[0])>0)
                                @foreach($record[0]->attendance[0] as $att)
                                    <td style="text-align: center;">{{$att->days}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[0]->attendance[0])->sum('days')}}</td>
                            @endif
                            @if(isset($record[1]->attendance[1]))
                            @if(count($record[1]->attendance[1])>0)
                                @foreach($record[1]->attendance[1] as $att)
                                    <td style="text-align: center;">{{$att->days}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[1]->attendance[1])->sum('days')}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>No. of Days Present</td>
                            @if(isset($record[0]->attendance[0]))
                            @if(count($record[0]->attendance[0])>0)
                                @foreach($record[0]->attendance[0] as $att)
                                    <td style="text-align: center;">{{$att->present}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[0]->attendance[0])->sum('present')}}</td>
                            @endif
                            @if(isset($record[1]->attendance[1]))
                            @if(count($record[1]->attendance[1])>0)
                                @foreach($record[1]->attendance[1] as $att)
                                    <td style="text-align: center;">{{$att->present}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[1]->attendance[1])->sum('present')}}</td>
                            @endif
                        </tr>
                        <tr>
                            <td>No. of Days Absent</td>
                            @if(isset($record[0]->attendance[0]))
                            @if(count($record[0]->attendance[0])>0)
                                @foreach($record[0]->attendance[0] as $att)
                                    <td style="text-align: center;">{{$att->absent}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[0]->attendance[0])->sum('absent')}}</td>
                            @endif
                            @if(isset($record[1]->attendance[1]))
                            @if(count($record[1]->attendance[1])>0)
                                @foreach($record[1]->attendance[1] as $att)
                                    <td style="text-align: center;">{{$att->absent}}</td>
                                @endforeach
                            @endif
                            <td style="text-align: center;">{{collect($record[1]->attendance[1])->sum('absent')}}</td>
                            @endif
                        </tr>
                    </table>
                {{-- @endif --}}
                
                @php
                    $certncorrectname = '';
                    $certncorrectdesc = '';
                    $datechecked = '';       
                    $eachsemremarks = '';
                    if(collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first())
                    {
                        $signinfo = collect($eachsemsignatories)->where('levelid', $record[1]->levelid)->where('semid',$record[1]->semid)->first();
                        $certncorrectname = $signinfo->certncorrectname;
                        $certncorrectdesc = $signinfo->certncorrectdesc;
                        $eachsemremarks = $signinfo->remarks;
                        $datechecked = $signinfo->datechecked != null ? date('m/d/Y', strtotime($signinfo->datechecked)) : '';
                    }else{
                        $certncorrectname = $record[1]->recordincharge;
                        $certncorrectdesc = "SHS-School Record's In-Charge";
                        $datechecked = $record[1]->datechecked != null ?  date('M d, Y',strtotime($record[1]->datechecked)) : '';
                    }
                @endphp
                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-bottom: 2px;">
                    <tr>
                        <td style="width: 10%;">REMARKS:</td>
                        <td style="border-bottom: 1px solid black;" colspan="4">{{$eachsemremarks}}</td>
                    </tr>
                    {{-- <tr>
                        <td colspan="5">&nbsp;</td>
                    </tr> --}}
                </table>
                <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                    <tr>
                        <td>Prepared by:</td>
                        <td></td>
                        <td>&nbsp;&nbsp;Certified True and Correct:</td>
                        <td></td>
                        <td>Date Checked (MM/DD/YYYY)</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="line-height: 2px;">&nbsp;</td>
                    </tr>
                    <tr style="font-size:  9px;">
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$record[1]->teachername}}</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$certncorrectname}}</td>
                        <td style="width: 5%;"></td>
                        <td style="width: 30%; border-bottom: 1px solid black; text-align: center;">{{$datechecked}}</td>
                    </tr>
                    <tr>
                        <td class="text-center">Signature of Adviser over Printed Name</td>
                        <td></td>
                        <td class="text-center">{{$certncorrectdesc}}</td>
                        <td></td>
                        <td class="text-center"></td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                </table>
                    {{-- <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                        <tr>
                            <td style="width: 15%;">REMEDIAL CLASSES</td>
                            <td style="width: 20%;">Conducted from (MM/DD/YYYY):</td>
                            <td style="width: 10%; border-bottom: 1px solid black;"></td>
                            <td style="width: 13%;">to (MM/DD/YYYY):</td>
                            <td style="width: 10%; border-bottom: 1px solid black;"></td>
                            <td>SCHOOL:</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td>SCHOOL ID:</td>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td colspan="9"></td>
                        </tr>
                    </table>
                    <div style="width: 100%; line-height: 1px;">&nbsp;</div>
                    <table style="width: 100%; table-layout: fixed; border: 2px solid black; font-size: 10px; text-transform: uppercase;" border="1">
                        <tr>
                            <th style="width: 10%;">INDICATE IF
                                SUBJECT IS
                                CORE, APPLIED,
                                OR
                                SPECIALIZED</th>
                            <th style="width: 40%;">SUBJECTS</th>
                            <th style="width: 10%;">SEM FINAL<br/>GRADE</th>
                            <th style="width: 10%;">REMEDIAL<br/>CLASS<br/>MARK</th>
                            <th style="width: 10%;">RECOMPUTED<br/>FINAL GRADE</th>
                            <th style="width: 10%;">ACTION TAKEN</th>
                        </tr>
                        @if(count($record[1]->remedials)>0)
                            @if(collect($record[1]->remedials)->contains('type','1'))
                                @foreach($record[1]->remedials as $remedial)
                                    @if($remedial->type == 1)
                                        <tr>
                                            <td class="text-center">{{$remedial->subjectcode}}</td>
                                            <td>{{$remedial->subjectname}}</td>
                                            <td class="text-center">{{$remedial->finalrating}}</td>
                                            <td class="text-center">{{$remedial->remclassmark}}</td>
                                            <td class="text-center">{{$remedial->recomputedfinal}}</td>
                                            <td class="text-center">{{$remedial->remarks}}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endif 
                        @else
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endif
                    </table>
                    <table style="width: 100%; table-layout: fixed; font-size: 10px;">
                        <tr>
                            <td style="width: 20%;">Name of Teacher/Adviser:</td>
                            <td style="width: 60%; border-bottom: 1px solid black;" colspan="2"></td>
                            <td style="width: 10%;">Signature:</td>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td colspan="5">&nbsp;</td>
                        </tr>
                    </table> --}}
                @endif
                @if($recordkey == 0)
                    <div style="page-break-after: always"></div>
                @endif
            @endforeach
        @endif
        <table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
            <tr>
                <td style="width:20%"><strong>Track/Strand Accomplished:</strong></td>
                <td style="border-bottom: 1px solid;width:45%; text-align: center;">
                    {{$footer->strandaccomplished}}
                </td>
                <td style="width:20%"><strong>SHS General Average:</strong></td>
                <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgenave}}</td>
            </tr>
        </table>
        <table style="text-align: none; text-transform:none; font-size: 10px; width: 100%;">
            <tr>
                <td style="width:20%"><strong>Awards/Honors Received:</strong></td>
                <td style="border-bottom: 1px solid;width:40%">
                    {{$footer->honorsreceived}}
                </td>
                <td style="width:28%"><strong>Date of SHS Graduation (MM/DD/YYYY):</strong></td>
                <td style="border-bottom: 1px solid; text-align: center;">{{$footer->shsgraduationdate}}</td>
            </tr>
        </table>
        <table style="font-size: 10px; width: 100%;">
            <tr>
                <td style="width: 60%;"><strong>Certified by:</strong></td>
                <td style="width: 40%;"><strong>Place School Seal Here:</strong></td>
            </tr>
            <tr>
                <td style="border-right: 1px solid;">
                    <table style="width: 100%">
                        <tr>
                            <td style="width: 60%;">
                                <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                    PANIAMOGAN, GERALD C.
                                    @else
                                    {{strtoupper(DB::table('schoolinfo')->first()->authorized)}}
                                    @endif
                                </div>
                            </td>
                            <td style="width: 40%; ">
                                <div style="width: 90%; border-bottom: 1px solid; text-align: center;">
                                    <strong>{{$footer->datecertified}}</strong>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div style="width: 90%;">
                                    <center>Signature of School Head over Printed Name</center>
                                </div>
                            </td>
                            <td>
                                <div style="width: 90%;">
                                    <center>Date</center>
                                </div>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <div style="width: 95%; border: 1px solid;padding: 5px;">
                        <strong>NOTE:</strong>
                        <br>
                        <small>
                            <em>
                                This permanent record or a photocopy of this permanent record that bears the seal of the school and the original signature in ink of the School Head shall be considered valid for all legal purposes. Any erasure or alteration made on this copy should be validated by the School Head.
                                <br>
                                If the student transfers to another school, the originating school should produce one (1) certified true copy of this permanent record for safekeeping. The receiving school shall continue filling up the original form.
                                <br>
                                Upon graduation, the school form which the student graduated should keep the original form and produce one (1) certified true copy for the Division Office.
                            </em>
                        </small>
                    </div>
                </td>
                <td></td>
            </tr>
            {{-- <tr>
                <td colspan="2">
                    <strong>Copy for: </strong> 
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <strong>Date Issued (MM/DD/YYYY): <u>{{$footer->datecertified}}</u></strong>
                </td>
            </tr> --}}
        </table>
        <table style="font-size: 11px; width: 60%; margin-top: 3px;">
            <tr>
                <td style="width: 20%;">
                    <strong>Copy for: </strong> 
                </td>
                <td style="font-size: 14px;">
                    <strong><u>{{$footer->copyforupper}}</u></strong> 
                </td>
            </tr>
            <tr>
                <td></td>
                <td style="font-size: 11px;">
                    {{$footer->copyforlower}}
                </td>
            </tr>
        </table>
    </body>
</html>