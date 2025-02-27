
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <style>
        .font-one{
            font-family: Arial, Helvetica, sans-serif !important;
            font-stretch: semi-expanded
    ;
        }
        .font-two{
            font-family: 'Bookman', 'URW Bookman L', serif;
            
        }
        .table-records td{
            padding: 0px;
        }
          *{
              
            font-family: Arial, Helvetica, sans-serif;
          }
          table{
              border-collapse: collapse;
          }
        @page { margin: 30px 50px 0px; size: 8.5in 13in}
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 320px;}
        footer { position: fixed; bottom: 30; left: 0px; right: 0px; height: 190px;}
        /* p { page-break-after: always;} */
        p:last-child { page-break-after: never; }
        
        #watermark {
            position: fixed;
            font-size: 11px;
            font-family: Arial, Helvetica, sans-serif !important;
            /** 
                Set a position in the page for your image
                This should center it vertically
            **/
            bottom:   0.5cm;
            left:     0.2cm;
            opacity: 1;

            /** Change image dimensions**/
            /* width:    8cm;
            height:   8cm; */

            /** Your watermark should be behind every content**/
            z-index:  -1000;
        }
      </style>
        {{-- @if($format == 'for_so_application')
        <style>
            footer { position: fixed; bottom: 0; left: 0px; right: 0px; height: 190px; border: 1px solid black;}
        </style>
        @else
        <style>
            footer { position: fixed; bottom: 0; left: 0px; right: 0px; height: 190px; border: 1px solid black;}
        </style>
        @endif --}}
    </head>
    <body>
        
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_text(30, 970, "SHEET - {PAGE_NUM} of {PAGE_COUNT}", '', 12, array(0,0,0));
            }
        </script> 
        @php
            $address = '';
            if($studentinfo->street != null)
            {
                $address.=$studentinfo->street.', ';
            }
            if($studentinfo->barangay != null)
            {
                $address.=$studentinfo->barangay.', ';
            }
            if($studentinfo->city != null)
            {
                $address.=$studentinfo->city.', ';
            }
            if($studentinfo->province != null)
            {
                $address.=$studentinfo->province;
            }
            // $guardianinfo = DB::table('studinfo')
            //     ->where('id',$studinfo->id)
            //     ->first();
            $guardianname = '';
            if($studentinfo->ismothernum == 1)
            {
                $guardianname = $studentinfo->mothername;
            }
            if($studentinfo->isfathernum == 1)
            {
                $guardianname = $studentinfo->fathername;
            }
            if($studentinfo->isguardannum == 1)
            {
                $guardianname = $studentinfo->guardianname ?? '';
            }
            
            if($studentinfo->isguardannum ==1)
            {
                $address = '';
                if($studentinfo->street != null)
                {
                    $address.=$studentinfo->street.', ';
                }
                if($studentinfo->barangay != null)
                {
                    $address.=$studentinfo->barangay.', ';
                }
                if($studentinfo->city != null)
                {
                    $address.=$studentinfo->city.', ';
                }
                if($studentinfo->province != null)
                {
                    $address.=$studentinfo->province;
                }
            }
                $today = date("Y-m-d");

                try{
                    $diff = date_diff(date_create($studentinfo->dob), date_create($today));
    
                    $studentinfo->age = $diff->format('%y');
    
                    $firstcomparison = ['01','02','03','04','05'];
    
                    if(in_array(date('m', strtotime($studentinfo->dob)),$firstcomparison)){
    
                        $studentinfo->age = ((int)$student->age - 1);
    
                    }
                }catch(\Exception $error)
                {
                    $studentinfo->age = null;
                }

            function lower_case($given_subjname)
            {
                $exclude = array('and','in','of','the','on','at','or','for','as','sa');
                $subj_desc = strtolower($given_subjname);
                $words = explode(' ', $subj_desc);
                foreach($words as $key => $word) {
                    if($key == 0)
                    {
                        $words[$key] = ucfirst($word);
                    }else{
                        if(in_array($word, $exclude)) {
                            continue;
                        }
                        $words[$key] = ucfirst($word);
                    }
                }
                return $subjectname = implode(' ', $words);
            }
        @endphp
        <header>
            <table style="width: 100%; table-layout: fixed; text-align: center;">
                <tr>
                    <td rowspan="7" style="vertical-align: top;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="90px" /></td>
                    <td style="width: 67.5%; color: #0099ff; font-size: 20px; font-weight: bold; letter-spacing: 1px;">{{$schoolinfo->schoolname}}</td>
                    <td rowspan="7" style="vertical-align: top; padding: 0px;">
                        @if($format == 'for_graduate_stud')
                            @if($getphoto)
                                @if($studentinfo->picurl != null || $getphoto->picurl != null)
                                    @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                                    <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style="width: 140px; height: 130px; margin: 0px; position: absolute;" />
                                    @else
                                        @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                            <img src="{{base_path()}}/public/{{$studentinfo->picurl}}" style="width: 140px; height: 130px; margin: 0px; position: absolute;" /> 
                                        @else
                                        @endif
                                    @endif
                                @endif
                            @else
                                
                                @if($studentinfo->picurl != null )
                                    @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                        <img src="{{base_path()}}/public/{{$studentinfo->picurl}}" style="width: 140px; height: 130px; margin: 0px; position: absolute;" /> 
                                    @else
                                    <div style="width: 130px; height: 120px; text-align: center; vertical-align: middle; border: 1px solid black; padding: 3% 5%; font-family:  'Times New Roman', Georgia, serif;">
                                        GRADUATION PICTURE with name
                                    </div>
                                        {{-- @php
                                        
                                            if(strtoupper($studentinfo->gender) == 'FEMALE'){
                                                $avatar = 'avatar/S(F) 1.png';
                                            }
                                            else{
                                                $avatar = 'avatar/S(M) 1.png';
                                            }
                                        @endphp
                                        <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 140px; height: 130px; margin: 0px; position: absolute; border: 1px solid black;" > --}}
                                        
                                    @endif
                                @else
                                    <div style="width: 130px; height: 120px; text-align: center; vertical-align: middle; border: 1px solid black; padding: 3% 5%; font-family:  'Times New Roman', Georgia, serif;">
                                        GRADUATION PICTURE with name
                                    </div>
                                    {{-- <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 140px; height: 140px; margin: 0px; position: absolute;" > --}}
                                @endif
                            {{-- <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 140px; height: 140px; margin: 0px; position: absolute;" > --}}
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="color: #993366; font-size: 16px; text-align: center;">Don Julian Rodriguez Avenue, Ma-a, Davao City</td>
                </tr>
                <tr>
                    <td style="font-size: 14px; text-align: center;"><sup>Tel. No. 287-8398</sup></td>
                </tr>
                <tr>
                    <td style="line-height: 13px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="line-height: 13px; text-align: center; font-weight: bold; font-size: 18px;">OFFICE OF THE COLLEGE REGISTRAR</td>
                </tr>
                <tr>
                    <td style="line-height: 25px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="line-height: 13px; text-align: center; font-weight: bold; font-family:  'Times New Roman', Georgia, serif; font-size: 18px;">OFFICIAL TRANSCRIPT OF COLLEGIATE RECORDS</td>
                    {{-- <td></td> --}}
                </tr>
            </table>
            <table style="width: 100%; border: 1px solid black; margin-top: 15px;">
                <tr style="font-weight: bold;">
                    <td style="border-bottom: 1px solid black;">{{$studentinfo->lastname}}</td>
                    <td style="border-bottom: 1px solid black;">{{$studentinfo->firstname}}</td>
                    <td style="width: 20%; border-bottom: 1px solid black; border-right: 1px solid black">{{$studentinfo->middlename}}</td>
                    <td style="width: 25%; border-bottom: 1px solid black;">&nbsp;&nbsp;&nbsp;&nbsp;{{$studentinfo->sid}}</td>
                </tr>
                <tr style="font-size: 10px;">
                    <td>Last Name</td>
                    <td>First Name</td>
                    <td style="border-right: 1px solid black">Middle Name</td>
                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Student Number</td>
                </tr>
            </table>
        </header>
        @if($format != 'for_so_application')
        <footer>
            <div style="width: 100%; border: 1px solid black; padding: 2px 1px; font-size: 11px; margin-top: 5px;">
                <span style="font-weight: bold;">GRADING SYSTEM:</span> Averaging- 100% Highest Passing Grade; 75% Lowest Passing Grade; 65% Lowest Failing Grade; 'DRP' Dropped.
            </div><br/>
            <table style="width: 100%; table-layout: fixed; font-size: 9px;">
                <tr>
                    <td style="width: 5%;"></td>
                    <td style="width: 8%;">Cleared by:</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">{{$clearedby}}</td>
                    <td style="width: 17%; text-align: right;">Prepared and Checked by:</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedncheckedby}}</td>
                    <td style="width: 17%; text-align: right;">Verified and Released by:</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">{{$verifiednreleasedby}}</td>
                    <td style="width: 5%;"></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px; text-align: center; margin-top: 15px;">
                <tr>
                    <td>NOTE: This transcript is valid only when it bears the seal of the school and the original signature in ink of the Registrar.</td>
                </tr>
                <tr>
                    <td>Any erasure or alteration made on the entries of this form renders this transcript invalid.</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px;">
                <tr>
                    <td>School Seal</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                <tr>
                    <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','School Treasurer')->first()->name ?? ''}}&nbsp;</td>
                    <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                    <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                </tr>
                <tr>
                    <th>School Treasurer</th>
                    <th>OIC - Registrar</th>
                    <th>College Dean</th>
                </tr>
            </table>
        </footer>
        @endif
        <main style="margin-top: 210px;">
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-top: 200px;">
                <tr>
                    <td style="width: 11%;">Date of Birth:</td>
                    <td style="width: 25%; border-bottom: 1px solid black;">
                        {{$details->dob != null ? date('m/d/Y', strtotime($details->dob)) : ''}}
                    </td>
                    <td style="width: 15%; text-align: right;">Place of Birth:&nbsp;&nbsp;</td>
                    <td style="width: 30%; border-bottom: 1px solid black;">
                       {{$details->pob}}</td>
                    <td style="text-align: right;">Sex:</td>
                    <td style="width: 12%; border-bottom: 1px solid black;">
                        {{$details->gender}}</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="text-align: center; padding-top: 3px;"><sup>Month/Day/Year</sup></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 3px;"><sup>Municipality/City/Province</sup></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px;">
                <tr>
                    <td style="width: 9%;">Citizenship:</td>
                    <td style="width: 27%; border-bottom: 1px solid black;">
                        {{$details->citizenship}}
                    </td>
                    <td style="width: 11%; text-align: right;">Religion:&nbsp;&nbsp;</td>
                    <td style="width: 34%; border-bottom: 1px solid black;" > {{$studentinfo->religionname ?? ''}}</td>
                    <td style="width: 10%; text-align: right;">ACR No.:</td>
                    <td style="border-bottom: 1px solid black;">
                        {{$details->acrno}}
                    </td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px !important;;">
                <tr>
                    <td style="width: 7%;">Address:</td>
                    <td style="width: 29%; border-bottom: 1px solid black;">{{$studentinfo->city}}</td>
                    <td style="width: 3%;"></td>
                    <td style="width: 42%; border-bottom: 1px solid black;">{{$studentinfo->province}}</td>
                    <td style="text-align: right;">Civil Status:</td>
                    <td style="border-bottom: 1px solid black;">
                        {{$details->civilstatus}}
                    </td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="text-align: center; padding-top: 3px;"><sup>City</sup></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 3px;"><sup>Provincial</sup></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px; line-height: 10px;">
                <tr>
                    <td style="width: 6%; ">Father:</td>
                    <td style="width: 30%; border-bottom: 1px solid black;">{{$studentinfo->fathername}}</td>
                    <td style="width: 10%; text-align: right">Mother:&nbsp;</td>
                    <td style="width: 35%; border-bottom: 1px solid black;">{{$studentinfo->mothername}}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px; margin-top: 5px; line-height: 10px;">
                <tr>
                    <td style="width: 27%;">Primary Grades completed at</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->elemcourse}}</td>
                    <td style="width: 6%; text-align: right;">Year</td>
                    <td colspan="2" style="width: 13%; border-bottom: 1px solid black;">{{$details->elemsy}}</td>
                </tr>
                <tr>
                    <td>Intermediate Grades completed at</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->intermediatecourse ?? ''}}</td>
                    <td style="text-align: right;">Year</td>
                    <td colspan="2" style="border-bottom: 1px solid black;"> {{$details->intermediatesy ?? ''}}</td>
                </tr>
                <tr>
                    <td>High School Course completed at</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->secondcourse}}</td>
                    <td style="text-align: right;">Year</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->secondsy}}</td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed;">
                <tr style=" font-size: 11px !important;">
                    <td style="width: 8%; ">Admission</td>
                    <td colspan="2" style="width: 19%;">{{$details->basisofadmission}}</td>
                    <td style="width: 17%; border-bottom: 1px solid black; text-align: center;">{{$details->admissiondatestr != null ? date('m/d/Y', strtotime($details->admissiondatestr)) : ''}}</td>
                    <td style="width: 5%;"></td>
                    <td style="width: 7%; border-bottom: 1px solid black; text-align: center;">{{$details->admissionsem ?? ''}}</td>
                    <td style="width: 8%;"></td>
                    <td style="width: 9%; border-bottom: 1px solid black; text-align: center;">{{$details->admissionsy ?? ''}}</td>
                    <td></td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="width: 11%; text-align: center; padding-top: 3px; border-top: 1px solid black;"><sup>Basis</sup></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 4px;"><sup>Date</sup></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 4px;"><sup>Semester</sup></td>
                    <td></td>
                    <td style="text-align: center; padding-top: 4px;"><sup>School Year</sup></td>
                    <td></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px;">
                <tr>
                    <td style="width: 9%;">Admitted to</td>
                    <td colspan="2">
                        {{$details->collegeof ?? ''}}
                    </td>
                    <td style="width: 66%; border-bottom: 1px solid black;">
                        {{$details->degree ?? ''}}
                    </td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td style="width: 20%; text-align: center; padding-top: 4px; border-top: 1px solid black;"><sup>College/Department</sup></td>
                    <td style="width: 7%; "></td>
                    <td style="text-align: center; padding-top: 4px; border-top: 1px solid black;"><sup>Course/Major</sup></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 11px; border: 1px solid black;">
                <tr>
                    <td style="width: 10%;">&nbsp;&nbsp;Graduated</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->graduationdegree ?? ''}}</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->graduationmajor ?? ''}}</td>
                    <td style="border-bottom: 1px solid black;">{{$details->graduationhonors ?? ''}}</td>
                </tr>
                <tr style="font-size: 11px;">
                    <td></td>
                    <td colspan="2" style="padding-top: 4px;"><sup>Degree</sup></td>
                    <td colspan="2" style="padding-top: 4px;"><sup>Major (Concentration)/Minor</sup></td>
                    <td style="padding-top: 4px;"><sup>Title/Honors</sup></td>
                </tr>
                <tr>
                    <td colspan="2" style="width: 15%;">&nbsp;&nbsp;Date of Graduation:</td>
                    <td style="border-bottom: 1px solid black;">{{$details->graduationdate}}</td>
                    <td style="width: 25%; text-align: right;">&nbsp;&nbsp;Special Order (B) Number:&nbsp;&nbsp;</td>
                    <td colspan="2" style="border-bottom: 1px solid black;">{{$details->specialorder}}</td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                </tr>
            </table>
            @php
                $firstcountrows = 0;
                $firstrowsperpage = 24;
                $countrows = 0;
                $rowsperpage = 38;

                $numberofrows = ($numberofrows+14);
                $dividerows = ($numberofrows)/$rowsperpage;
                if (is_numeric($dividerows)) {
                        $whole = floor($dividerows);
                        $fraction = $dividerows - $whole;

                        // if decimal            
                        if ($fraction > 0)
                        {
                            $dividerows = ((int)explode('.',$dividerows)[0])+1;
                        }
                        // else
                        // if integer
                            // do sth 
                }
                $initialschool = null;
                $initialcourse = null;
        
            @endphp
            @if(count($records)>0)
                <table style="width: 100%; table-layout: fixed; margin-top: 5px;">
                    <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                        <th style="width: 20%; border: 1px solid black;">Term</th>
                        <td style="border: 1px solid black;"></td>
                        <th colspan="2" style="border: 1px solid black;">Grades</th>
                        <td style="width: 6%; border: 1px solid black;"></td>
                    </tr>
                    <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                        <th style="border: 1px solid black;">Course Number</th>
                        <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                        <th style="width: 5%; border: 1px solid black;">Final</th>
                        <th style="width: 9%; border: 1px solid black;">Re-examination<br/>Completion</th>
                        <th style="border: 1px solid black;">Credits</th>
                    </tr>                
                    @foreach($records as $key=>$record)
                        @php
                            $firstcountrows+=2;
                            $subjnum = count($record->subjdata);
                            $break = 0;
                            if($key == 0)
                            {
                                $initialschool = $record->schoolname;
                                    $initialcourse = $record->coursename;
                            }else{
                                if($records[$key-1]->schoolname != $record->schoolname)
                                {
                                    $initialschool = $record->schoolname;
                                }else{
                                    $initialschool = null;
                                }
                                if($records[$key-1]->coursename != $record->coursename)
                                {
                                    $initialcourse = $record->coursename;
                                }else{
                                    $initialcourse = null;
                                }
                            }
                            // $firstcountrows+=2;
                        @endphp
                        <tr style="font-size: 12px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>@if($record->semid == 1)FIRST SEMESTER @elseif($record->semid == 2)SECOND SEMESTER @else SUMMER @endif</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>{{$initialschool}}</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        <tr style="font-size: 12px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold; vertical-align: top;"><u>{{$record->sydesc}}</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold; font-size: 10.5px;"><u>{{$initialcourse}}</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        @if(collect($record->subjdata)->count()>0)
                            @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                                @php
                                
                                $subj->display = 0;
                                @endphp
                                <tr style="font-size: 12px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; vertical-align: top; text-align: center;">{{$subj->subjcode}}</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">{{lower_case($subj->subjdesc)}}</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjgrade > 0 ? round($subj->subjgrade,2) : ''}}</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjreex}}</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjgrade > 0 ? $subj->subjunit : ''}}</td>
                                </tr>
                                @php 
                                $subj->display = 1;
                                if($firstcountrows == $firstrowsperpage)
                                {
                                    $break+=1;
                                    break;
                                }else{
        
                                    $firstcountrows+=1;
                                }
                                @endphp
                            @endforeach
                        @endif
                        @php
                        if($firstcountrows == $firstrowsperpage)
                        {   
                            // $firstcountrows+=1;
                            if(collect($record->subjdata)->count() == 0)
                            {
                                $record->display = 1;
                                break;
                            }else{
                                if(collect($record->subjdata)->count() == $subjnum)
                                {
                                    $record->display = 1;
                                    break;
                                }
                            }
                        }else{
                            if(collect($record->subjdata)->count() == 0)
                            {
                                $record->display = 1;
                            }else{
                                if(collect($record->subjdata)->count() == $subjnum)
                                {
                                    $record->display = 1;
                                }
                                if($break>0)
                                {
                                    break;
                                }
                            }
                        }
                        @endphp
                    @endforeach
                    @for($x = $firstcountrows; $x < $firstrowsperpage; $x++)
                    <tr style="font-size: 11.5px;">
                        <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                    </tr>
                    @endfor
                    @php
                        $records = collect($records)->where('display','0')->values();
                    @endphp
                    <tr style="font-size: 11.5px;">
                        <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">
                            @if(count($records) == 0)
                            1 of 1
                            @else
                            1 of {{$dividerows}}
                            @endif
                        </td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                    </tr>
                    @if($details->remarks == null)
                        <tr style="font-size: 9px;">
                            <td colspan="5" style="border: 1px solid black; text-align: center;">
                                CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                <br/>
                                Any entry below this line is not valid
                            </td>
                        </tr>
                    @else
                        <tr style="font-size: 9px;">
                            <td colspan="5" style="border: 1px solid black; text-align: center;">
                                CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                <br/>
                                Any entry below this line is not valid
                            </td>
                        </tr>
                        <tr style="font-size: 15px;">
                            <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                REMARKS: {{$details->remarks}}
                            </td>
                        </tr>
                    @endif
                </table>
                @if($format == 'for_so_application')
                    <div style="width: 100%; border: 1px solid black; padding: 2px 1px; font-size: 11px; margin-top: 5px;">
                        <span style="font-weight: bold;">GRADING SYSTEM:</span> Averaging- 100% Highest Passing Grade; 75% Lowest Passing Grade; 65% Lowest Failing Grade; 'DRP' Dropped.
                    </div>
                    <table style="width: 100%; table-layout: fixed; font-size: 9px; margin-top: 7px;">
                        <tr>
                            <td></td>
                            <td>Prepared and Checked by:</td>
                            <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedncheckedby}}</td>
                            <td>Verified and Released by:</td>
                            <td style="border-bottom: 1px solid black; text-align: center;">{{$verifiednreleasedby}}</td>
                            <td></td>
                        </tr>
                    </table>
                    <table style="width: 100%; table-layout: fixed; font-size: 10px; text-align: center; margin-top: 10px;">
                        <tr>
                            <td>NOTE: This transcript is valid only when it bears the seal of the school and the original signature in ink of the Registrar.</td>
                        </tr>
                        <tr>
                            <td>Any erasure or alteration made on the entries of this form renders this transcript invalid.</td>
                        </tr>
                    </table>
                    @if(count($records) == 0)
                        <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px;">
                            <tr>
                                <td>School Seal</td>
                            </tr>
                        </table>
                        <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                            <tr>
                                <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                                <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                            </tr>
                            <tr>
                                <th>OIC - Registrar</th>
                                <th>College Dean</th>
                            </tr>
                        </table>
                    @else
                        @if($dividerows == 1)
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 10px;">
                                <tr>
                                    <th></th>
                                    <th style="border: 1px solid black; font-weight: bolder;">C E R T I F I C A T I O N</th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-indent: 50px;">We hereby certify that the foregoing records of <u style="font-weight: bold;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename != null ? $studentinfo->middlename[0].'. ' : ''}} {{$studentinfo->suffix}}</u>, a candidate for graduation in this institution have been verified by us and that true copies of the official records substantiating the same are kept in the file of our school.</td>
                                </tr>
                            </table>
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px;">
                                <tr>
                                    <td>School Seal</td>
                                </tr>
                            </table>
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                                <tr>
                                    <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                                    <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                                </tr>
                                <tr>
                                    <th>OIC - Registrar</th>
                                    <th>College Dean</th>
                                </tr>
                            </table>
                        @else
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px;">
                                <tr>
                                    <td>School Seal</td>
                                </tr>
                            </table>
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                                <tr>
                                    <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                                    <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                                </tr>
                                <tr>
                                    <th>OIC - Registrar</th>
                                    <th>College Dean</th>
                                </tr>
                            </table>
                        @endif
                    @endif
                @endif
                @if(count($records)>0)
                    @for($xpage = 1; $xpage < 3; $xpage++)
                        @php
                            $countrows = 0;
                            $rowsperpage = 38;
                        @endphp
                        <div style=" page-break-after: always;"></div>
                        <div style="width: 100%; font-size: 8px; text-align: center; margin-top: 220px;">
                            Continued from Page {{$xpage}}
                        </div>
                        <table style="width: 100%; table-layout: fixed; margin-top: 10px;" >
                            <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                                <th style="width: 20%; border: 1px solid black;">Term</th>
                                <td style="border: 1px solid black;"></td>
                                <th colspan="2" style="border: 1px solid black;">Grades</th>
                                <td style="width: 6%; border: 1px solid black;"></td>
                            </tr>
                            <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                                <th style="border: 1px solid black;">Course Number</th>
                                <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                                <th style="width: 5%; border: 1px solid black;">Final</th>
                                <th style="width: 9%; border: 1px solid black;">Re-examination<br/>Completion</th>
                                <th style="border: 1px solid black;">Credits</th>
                            </tr>     
                            @foreach($records as $key=>$record)
                                @php
                                    $record->subjdata = collect($record->subjdata)->where('display','0')->values();
                                    $countrows+=2;
                                    $subjnum = count($record->subjdata);
                                    $break = 0;
                                    if($key == 0)
                                    {
                                        $initialschool = $record->schoolname;
                                            $initialcourse = $record->coursename;
                                    }else{
                                        if($records[$key-1]->schoolname != $record->schoolname)
                                        {
                                            $initialschool = $record->schoolname;
                                        }else{
                                            $initialschool = null;
                                        }
                                        if($records[$key-1]->coursename != $record->coursename)
                                        {
                                            $initialcourse = $record->coursename;
                                        }else{
                                            $initialcourse = null;
                                        }
                                    }
                                    // $firstcountrows+=2;
                                @endphp
                                <tr style="font-size: 12px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>@if($record->semid == 1)FIRST SEMESTER @elseif($record->semid == 2)SECOND SEMESTER @else SUMMER @endif</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>{{$initialschool}}</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 12px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold; vertical-align: top;"><u>{{$record->sydesc}}</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold; font-size: 10.5px;"><u>{{$initialcourse}}</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @if(count($record->subjdata)>0)
                                    @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                                        @php
                                        
                                        $subj->display = 0;
                                        @endphp
                                        <tr style="font-size: 12px;">
                                            <td style="border-left: 1px solid black; border-right: 1px solid black; vertical-align: top; text-align: center;">{{$subj->subjcode}}</td>
                                            <td style="border-left: 1px solid black; border-right: 1px solid black;">{{lower_case($subj->subjdesc)}}</td>
                                            <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjgrade > 0 ? round($subj->subjgrade,2) : ''}}</td>
                                            <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjreex}}</td>
                                            <td style="border-left: 1px solid black; border-right: 1px solid black; text-align: center;">{{$subj->subjgrade > 0 ? $subj->subjunit : ''}}</td>
                                        </tr>
                                        @php 
                                        $subj->display = 1;
                                        if($countrows == $rowsperpage)
                                        {
                                            $break+=1;
                                            break;
                                        }else{
                
                                            $countrows+=1;
                                        }
                                        @endphp
                                    @endforeach
                                @endif
                                @php
                                if($countrows == $rowsperpage)
                                {   
                                    if(count($record->subjdata) == 0)
                                    {
                                        $record->display = 1;
                                        break;
                                    }else{
                                        if(count($record->subjdata) == $subjnum)
                                        {
                                            $record->display = 1;
                                            break;
                                        }
                                    }
                                }else{
                                    if(count($record->subjdata) == 0)
                                    {
                                        $record->display = 1;
                                    }else{
                                        if(count($record->subjdata) == $subjnum)
                                        {
                                            $record->display = 1;
                                        }
                                        if($break>0)
                                        {
                                            break;
                                        }
                                    }
                                }
                                @endphp
                            @endforeach
                            @for($x = $countrows; $x < $rowsperpage; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            @php
                                $records = collect($records)->where('display','0')->values();
                            @endphp
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">
                                    {{$xpage+1}} of {{$dividerows}}
                                </td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            </tr>
                            @if($details->remarks == null)
                                <tr style="font-size: 9px;">
                                    <td colspan="5" style="border: 1px solid black; text-align: center;">
                                        CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                        <br/>
                                        Any entry below this line is not valid
                                    </td>
                                </tr>
                            @else
                                <tr style="font-size: 9px;">
                                    <td colspan="5" style="border: 1px solid black; text-align: center;">
                                        CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                        <br/>
                                        Any entry below this line is not valid
                                    </td>
                                </tr>
                                <tr style="font-size: 15px;">
                                    <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                        REMARKS: {{$details->remarks}}
                                    </td>
                                </tr>
                            @endif
                        </table>
                        @if($format == 'for_so_application')
                            <div style="width: 100%; border: 1px solid black; padding: 2px 1px; font-size: 11px; margin-top: 5px;">
                                <span style="font-weight: bold;">GRADING SYSTEM:</span> Averaging- 100% Highest Passing Grade; 75% Lowest Passing Grade; 65% Lowest Failing Grade; 'DRP' Dropped.
                            </div>
                            <table style="width: 100%; table-layout: fixed; font-size: 9px; margin-top: 7px;">
                                <tr>
                                    <td></td>
                                    <td>Prepared and Checked by:</td>
                                    <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedncheckedby}}</td>
                                    <td>Verified and Released by:</td>
                                    <td style="border-bottom: 1px solid black; text-align: center;">{{$verifiednreleasedby}}</td>
                                    <td></td>
                                </tr>
                            </table>
                            <table style="width: 100%; table-layout: fixed; font-size: 10px; text-align: center; margin-top: 10px;">
                                <tr>
                                    <td>NOTE: This transcript is valid only when it bears the seal of the school and the original signature in ink of the Registrar.</td>
                                </tr>
                                <tr>
                                    <td>Any erasure or alteration made on the entries of this form renders this transcript invalid.</td>
                                </tr>
                            </table>
                            @if($dividerows == ($xpage+1))
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 15px;">
                                    <tr>
                                        <th></th>
                                        <th style="border: 1px solid black; font-weight: bolder;">C E R T I F I C A T I O N</th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    <tr>
                                        <td colspan="3" style="text-indent: 50px;">We hereby certify that the foregoing records of <u style="font-weight: bold;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename != null ? $studentinfo->middlename[0].'. ' : ''}} {{$studentinfo->suffix}}</u>, a candidate for graduation in this institution have been verified by us and that true copies of the official records substantiating the same are kept in the file of our school.</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 30px;">
                                    <tr>
                                        <td>School Seal</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                                        <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <th>OIC - Registrar</th>
                                        <th>College Dean</th>
                                    </tr>
                                </table>
                            @else
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px;">
                                    <tr>
                                        <td>School Seal</td>
                                    </tr>
                                </table>
                                <table style="width: 100%; table-layout: fixed; font-size: 10px; margin-top: 20px; text-align: center;">
                                    <tr>
                                        <td style="text-align: center; font-weight: bold;">&nbsp;{{collect($signatories)->where('title','OIC - Registrar')->first()->name ?? ''}}&nbsp;</td>
                                        <td style="text-align: center; font-weight: bold;">&nbsp;{{$collegedeanname}}&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <th>OIC - Registrar</th>
                                        <th>College Dean</th>
                                    </tr>
                                </table>
                            @endif
                        @endif
                    @endfor
                @endif
            @else
                    <table style="width: 100%; table-layout: fixed; margin-top: 5px;">
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                            <th style="width: 20%; border: 1px solid black;">Term</th>
                            <td style="border: 1px solid black;"></td>
                            <th colspan="2" style="border: 1px solid black;">Grades</th>
                            <td style="width: 7%; border: 1px solid black;"></td>
                        </tr>
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                            <th style="border: 1px solid black;">Course Number</th>
                            <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                            <th style="width: 6%; border: 1px solid black;">Final</th>
                            <th style="width: 10%; border: 1px solid black;">Re-examination<br/>Completion</th>
                            <th style="border: 1px solid black;">Credits</th>
                        </tr>  
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(PROGRAM)</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        @for($x = 0; $x < 11; $x++)
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                        </tr>
                        @endfor
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                        </tr>
                        @for($x = 0; $x < 11; $x++)
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                        </tr>
                        @endfor
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">
                                @if($format == 'for_so_application')
                                1 of 3
                                @elseif($format == 'for_inactive_stud')
                                1 of 2
                                @else
                                1 of 4
                                @endif
                            </td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr style="font-size: 9px;">
                            <td colspan="5" style="border: 1px solid black; text-align: center;">
                                CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                <br/>
                                Any entry below this line is not valid
                            </td>
                        </tr>
                        @if($format == 'for_so_application')
                            <tr style="font-size: 15px;">
                                <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                    REMARKS: For Evaluation Purposes (Not Valid for Transfer)
                                </td>
                            </tr>
                        @elseif($format == 'for_graduate_stud')
                            <tr style="font-size: 15px;">
                                <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                    REMARKS: For Employment Purposes (Not Valid for Transfer)
                                </td>
                            </tr>
                        @endif
                    </table>
                
                <div style=" page-break-after: always;"></div>
                <div style="width: 100%; font-size: 8px; text-align: center; margin-top: 220px;">
                    Continued from Page 1
                </div>
                <table style="width: 100%; table-layout: fixed; margin-top: 10px;" >
                    <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                        <th style="width: 20%; border: 1px solid black;">Term</th>
                        <td style="border: 1px solid black;"></td>
                        <th colspan="2" style="border: 1px solid black;">Grades</th>
                        <td style="width: 7%; border: 1px solid black;"></td>
                    </tr>
                    <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                        <th style="border: 1px solid black;">Course Number</th>
                        <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                        <th style="width: 6%; border: 1px solid black;">Final</th>
                        <th style="width: 10%; border: 1px solid black;">Re-examination<br/>Completion</th>
                        <th style="border: 1px solid black;">Credits</th>
                    </tr>          
                    @if(count($records)>0)
                    @else
                        @if($format == 'for_so_application')
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(PROGRAM)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 10; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 10; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 5; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 8; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                        @elseif($format == 'for_inactive_stud')
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 40; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                        @else
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 4; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 11; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 11; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 7; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                        @endif   
                        <tr style="font-size: 11.5px;">
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">
                                @if($format == 'for_so_application')
                                2 of 3
                                @elseif($format == 'for_inactive_stud')
                                2 of 2
                                @else
                                2 of 4
                                @endif
                            </td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr style="font-size: 9px;">
                            <td colspan="5" style="border: 1px solid black; text-align: center;">
                                CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                <br/>
                                Any entry below this line is not valid
                            </td>
                        </tr>
                        @if($format == 'for_so_application')
                            <tr style="font-size: 15px;">
                                <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                    REMARKS: For Evaluation Purposes (Not Valid for Transfer)
                                </td>
                            </tr>
                        @elseif($format == 'for_graduate_stud')
                            <tr style="font-size: 15px;">
                                <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                    REMARKS: For Employment Purposes (Not Valid for Transfer)
                                </td>
                            </tr>
                        @endif
                    @endif
                </table>
                @if($format != 'for_inactive_stud')
                    <div style=" page-break-after: always;"></div>
                    <div style="width: 100%; font-size: 8px; text-align: center; margin-top: 220px;">
                        Continued from Page 2
                    </div>
                    <table style="width: 100%; table-layout: fixed; margin-top: 10px;" >
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                            <th style="width: 20%; border: 1px solid black;">Term</th>
                            <td style="border: 1px solid black;"></td>
                            <th colspan="2" style="border: 1px solid black;">Grades</th>
                            <td style="width: 7%; border: 1px solid black;"></td>
                        </tr>
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                            <th style="border: 1px solid black;">Course Number</th>
                            <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                            <th style="width: 6%; border: 1px solid black;">Final</th>
                            <th style="width: 10%; border: 1px solid black;">Re-examination<br/>Completion</th>
                            <th style="border: 1px solid black;">Credits</th>
                        </tr>         
                        @if(count($records)>0)
                        @else
                            @if($format == 'for_so_application')
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 11; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 12; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 12; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                            @else
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>CHRISTIAN COLLEGES OF SOUTHEAST ASIA-DAVAO CITY</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 4; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>FIRST SEMESTER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 11; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 11; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SUMMER</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                </tr>
                                @for($x = 0; $x < 7; $x++)
                                <tr style="font-size: 11.5px;">
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                    <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                </tr>
                                @endfor
                            @endif   
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">
                                    @if($format == 'for_so_application')
                                    3 of 3
                                    @else
                                    3 of 4
                                    @endif
                                </td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 9px;">
                                <td colspan="5" style="border: 1px solid black; text-align: center;">
                                    CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                    <br/>
                                    Any entry below this line is not valid
                                </td>
                            </tr>
                            @if($format == 'for_so_application')
                                <tr style="font-size: 15px;">
                                    <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                        REMARKS: For Evaluation Purposes (Not Valid for Transfer)
                                    </td>
                                </tr>
                            @elseif($format == 'for_graduate_stud')
                                <tr style="font-size: 15px;">
                                    <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                        REMARKS: For Employment Purposes (Not Valid for Transfer)
                                    </td>
                                </tr>
                            @endif
                        @endif
                    @endif
                </table> 
                @if($format == 'for_graduate_stud')
                    <div style=" page-break-after: always;"></div>
                    <div style="width: 100%; font-size: 8px; text-align: center; margin-top: 220px;">
                        Continued from Page 3
                    </div>
                    <table style="width: 100%; table-layout: fixed; margin-top: 10px;" >
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 10px; font-weight: bold;">
                            <th style="width: 20%; border: 1px solid black;">Term</th>
                            <td style="border: 1px solid black;"></td>
                            <th colspan="2" style="border: 1px solid black;">Grades</th>
                            <td style="width: 7%; border: 1px solid black;"></td>
                        </tr>
                        <tr style="font-family:  'Times New Roman', Georgia, serif !important; font-size: 9px; font-weight: 900 !important;">
                            <th style="border: 1px solid black;">Course Number</th>
                            <th style="font-size: 12px; border: 1px solid black;">Descriptive Title of the Course</th>
                            <th style="width: 6%; border: 1px solid black;">Final</th>
                            <th style="width: 10%; border: 1px solid black;">Re-examination<br/>Completion</th>
                            <th style="border: 1px solid black;">Credits</th>
                        </tr>       
                        @if(count($records)>0)
                        @else
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>SECOND SEMESTER</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u>(School Year)</u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;"><u></u></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"></td>
                            </tr>
                            @for($x = 0; $x < 36; $x++)
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            @endfor
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;NOTE: GRADUATED with the degree of (PROGRAM) on (DATE) with Special Order No.</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; font-weight: bold;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;"><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u>, s. (YEAR) dated (DATE).</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black;">&nbsp;</td>
                            </tr>
                            <tr style="font-size: 11.5px;">
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black; text-align: center;">4 of 4
                                </td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                                <td style="border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr style="font-size: 9px;">
                                <td colspan="5" style="border: 1px solid black; text-align: center;">
                                    CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA  X X X Transcript Closed X X X CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA CCSA 
                                    <br/>
                                    Any entry below this line is not valid
                                </td>
                            </tr>
                            <tr style="font-size: 15px;">
                                <td colspan="5" style="border: 1px solid black; font-weight: bold;">
                                    REMARKS: For Employment Purposes (Not Valid for Transfer)
                                </td>
                            </tr>
                        @endif
                    </table> 
                @endif
            @endif
        </main>
    </body>
</html>