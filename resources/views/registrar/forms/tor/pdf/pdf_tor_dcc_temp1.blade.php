<!DOCTYPE html>
<html lang="en" class="no-js">
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <title>TOR - Board Exam</title><meta charset="UTF-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{base_path()}}/storage/fonts/dcc/font-gothic/font.css">
        {{-- <link rel="stylesheet" href="{{base_path()}}/storage/fonts/dcc/font-gothic-bold/font/gothic_bold.css"> --}}
        <style type="text/css">
            *, html, table, body {
                font-family: gothic, sans-serif !important;
            }
            @page { margin: 100px 10px 50px; size: 8.5in 13in;}
            table{
                border-collapse: collapse;
            }
            .border-right{
                border-right: 1px solid black;
            }
            .border-top{
                border-top: 1px solid black;
            }
            .border-left{
                border-left: 1px solid black;
            }
            .border-bottom{
                border-bottom: 1px solid black;
            }
            .v-align-middle{
                vertical-align: middle;
            }
            .v-align-top{
                vertical-align: top;
            }
            .text-center{
                text-align: center;
            }
            .text-bold{
                font-weight: bold;
            }
            .border{
                border: 1px solid black;
            }
            #watermark-1 {
                top: -75px;
                position: absolute;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                /* bottom:   22cm;
                left:     4cm; */
                opacity: 1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            #watermark-1-2 {
                top: -45px;
                position: absolute;
                width: 120px; 

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                right: 1cm;
                /* bottom:   22cm;
                left:     4cm; */
                opacity: 1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            #watermark-2 {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   22cm;
                left:     4cm;
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            #watermark-3 {
                position: absolute;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   10px;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                opacity: 1;
                border-bottom: 1px solid black;
                width: 100%;
                margin: 0px 29.5px;
            }
            #watermark-4 {
                top: -22px;
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                /* bottom:   22cm;
                /* bottom:   22cm;
                left:     4cm; */

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                /* z-index:  -1000; */
            }
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 320px;}
        </style>
    </head>
    <body>       
  
        @php
            $studentname = $studentinfo->lastname.', '.$studentinfo->firstname.' '.$studentinfo->suffix.' '.$studentinfo->middlename;
            function lower_case($string_)
            {
                $exclude = array('and','in','of','the','on','at','or','for','as','sa');
                $subj_desc = strtolower($string_);
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
            $pagecount = '';
            if ( isset($pdf) ) {
            $pagecount = $pdf->text();
            }
        @endphp
        <script type="text/php">
            if ( isset($pdf) ) {
                {{-- $pdf->page_text(290, 910, "page {PAGE_NUM} of {PAGE_COUNT} pages", '', 8, array(0,0,0)); --}}
                $x = 285;  //X-axis i.e. vertical position 
                $y = 910; //Y-axis horizontal position
                $text = "page {PAGE_NUM} of {PAGE_COUNT} pages";  //format of display message
                $font =  $fontMetrics->get_font("helvetica","italic");
                $size = 10;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default
                $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);

            }
        </script>
        <div id="watermark-1">
            <img src="{{asset('assets/images/dcc/tor_header.png')}}" style="width: 100%; height: 190px;"/>
        </div> 
        <div id="watermark-1-2">@if($getphoto)
            @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" style="width: 120px; border: 1px solid black;" draggable="false" id="image-view"/>
                @else
                    @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                        {{-- <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;"  draggable="false"  id="image-view"/>  --}}
                        <img src="{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 120px; border: 1px solid black;"  draggable="false"  id="image-view"/> 
                    @else
                    PHOTO
                    @endif
                @endif
            @else
                
                @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                    {{-- <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;" draggable="false"   id="image-view"/>  --}}
                    <img src="{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 120px; border: 1px solid black;"draggable="false"   id="image-view"/> 
                    <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 120px; border: 1px solid black;" >
                @else
                &nbsp;
                {{-- @php
                
                    if(strtoupper($studentinfo->gender) == 'FEMALE'){
                        $avatar = 'avatar/S(F) 1.png';
                    }
                    else{
                        $avatar = 'avatar/S(M) 1.png';
                    }
                @endphp
                <img src="{{base_path()}}/public/{{$avatar}}" alt="student" style="width: 120px; border: 1px solid black;" > --}}
                @endif
            @endif
            {{-- <div style=" width: 120px; border: 1px solid black; text-align: justofy; font-size: @if(strlen($studentname)>20)8px @else 12px @endif;">{{$studentname}}</div> --}}
        </div> 
        <div id="watermark-2">
            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="500px" />
        </div>
        {{-- <main style="top: 0px;"> --}}
            <table style="width: 100%; margin: 13px 20px 0px;  font-size: 12.5px; table-layout: fixed; margin-top: 120px; font-weight: bold;" class="fontsforweb_fontid_9785">
                <tr>
                    <td style="width: 49%;" class="v-align-top">Name: &nbsp;&nbsp;<u style="font-size: 12px;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{ucwords(strtolower($studentinfo->middlename))}}</u></td>
                    <td colspan="2" class="v-align-top">Degree: &nbsp;&nbsp;<u style="font-size: 12px;">{{$details->degree}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Date of Birth: &nbsp;&nbsp;<u>{{$studentinfo->dob != null ? date('m/d/Y',strtotime($studentinfo->dob)) : ''}}</u></td>
                    <td colspan="2" class="v-align-top">Date Awarded: &nbsp;&nbsp;<u>{{$details->graduationdate != null ? date('F d, Y', strtotime($details->graduationdate)) : ''}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Address: &nbsp;&nbsp;<u style="font-size: 12px;">{{$address}}</u></td>
                    <td colspan="2" class="v-align-top">Other records of Graduation: &nbsp;&nbsp;<u>{{$details->otherrecords ?? ''}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Parent/Guardian: &nbsp;&nbsp;<u>{{$guardianname}}</u></td>
                    <td class="v-align-top">Intermediate: &nbsp;&nbsp;<u>{{$details->intermediatecourse ?? ''}}</u></td>
                    <td style="width: 15%;" class="v-align-top"><u>{{$details->intermediatesy ?? ''}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Address: &nbsp;&nbsp;<u style="font-size: 12px;">{{$address}}</u></td>
                    <td class="v-align-top">Junior HS: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>{{$details->juniorschoolname ?? ''}}</u></td>
                    <td class="v-align-top"><u>{{$details->juniorschoolyear ?? ''}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Basis of Admission: &nbsp;&nbsp;<u>{{$details->seniorschoolname ?? ''}}</u></td>
                    <td class="v-align-top">Senior HS: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>{{$details->seniorschoolyear ?? ''}}</u></td>
                    <td class="v-align-top"><u>{{$details->basisofadmission}}</u></td>
                </tr>
                <tr>
                    <td class="v-align-top">Student ID No.: &nbsp;&nbsp;<u>{{$studentinfo->sid}}</u></td>
                    <td colspan="2" class="v-align-top">NSTP Serial No.: &nbsp;&nbsp;<u>{{$details->nstpserialno ?? ''}}</u></td>
                </tr>
            </table>
            
            @php
                $rep_sydesc = '';
                $rep_syid = 0;
                $rep_schoolname = '';
                $rep_schooladdress = '';
                $rep_coursename = '';
                $subjects_count = 0;
            @endphp
            {{-- <div style="width: 100%; @if($numberofrows > 20) page-break-after: always; @endif"> --}}
                <table style="width: 100%; margin: 10px 30px;  font-size: 12px; table-layout: fixed; page-break-inside: always;">
                    <thead>
                        <tr>
                            <th rowspan="2" style="width: 27%; border: 1px solid black;">Course & No.</th>
                            <th style="width: 56%; border: 1px solid black;">Collegiate Record</th>
                            <th colspan="2" style="border: 1px solid black;">Grades</th>
                        </tr>
                        <tr>
                            <th style="border: 1px solid black;">DESCRIPTIVE TITLE OF COURSE</th>
                            <th style="border: 1px solid black;">Final</th>
                            <th style="border: 1px solid black;">Credit</th>
                        </tr>
                    </thead>
                    @if(count($records)>0)
                        @foreach($records as $record)
                            @php
                            $rep_sydesc = $record->sydesc;
                            $rep_syid = $record->syid;
                            @endphp
                            @if($record->schoolname != $rep_schoolname)
                                @php
                                $rep_schoolname = $record->schoolname;
                                $rep_schooladdress = $record->schooladdress;
                                $rep_coursename = $record->coursename;
                                @endphp
                                <tr>
                                    <td class="border-right border-left"></td>
                                    <th class="border-right border-left">{{$rep_schoolname}}<br/>{{$rep_schooladdress}}<br/>{{$rep_coursename}}</th>
                                    <td class="border-right border-left"></td>
                                    <td class="border-right border-left"></td>
                                </tr>
                            @endif
                            <tr>
                                <td class="border-right border-left text-bold"><u>{{$record->semid == 1 ? '1st Semester' : ($record->semid == 2 ? '2nd Semester' : 'Summer')}} {{$record->sydesc}}</u></td>
                                <td class="border-right border-left"></td>
                                <td class="border-right border-left"></td>
                                <td class="border-right border-left"></td>
                            </tr>
                            @if(count($record->subjdata)>0)
                                @foreach($record->subjdata as $eachsubject)
                                    <tr style="line-height: 10px;">
                                        <td class="border-right border-left v-align-top">{{$eachsubject->subjcode}}</td>
                                        <td class="border-right border-left">{{lower_case($eachsubject->subjdesc)}}</td>
                                        <td class="border-right border-left v-align-top text-center">{{$eachsubject->eqgrade}}</td>
                                        <td class="border-right border-left v-align-top text-center">{{$eachsubject->subjcredit}}</td>
                                    </tr>
                                    @php
                                        $subjects_count+=1;
                                    @endphp
                                @endforeach
                            @else
                            <tr>
                                <td class="border-right border-left v-align-top">&nbsp;</td>
                                <td class="border-right border-left"></td>
                                <td class="border-right border-left v-align-top text-center"></td>
                                <td class="border-right border-left v-align-top text-center"></td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                    <tr>
                        <th colspan="4" class="border">*** -dcc -dcc-dcc-dcc-dcc-dcc-dcc-dcc- TRANSCRIPT&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CLOSED -dcc-dcc-dcc-dcc-dcc-dcc-dcc--dcc ***</th>
                    </tr>
                    <tr>
                        <td colspan="4" class="border text-bold" style="padding: 7px 80px;">
                            @php
                                if (strpos(strtolower($rep_coursename), 'major') !== false) {
                                    $rep_coursename = explode(' major', strtolower($rep_coursename));
                                    $rep_coursename = lower_case($rep_coursename[0]);
                                }
                            @endphp
                            <p style="text-align: justify;">Graduated from the Four-Year Collegiate Normal Course leading to the degree of {{collect($records)->last()->coursename}} as of {{$details->graduationdate != null ? date('F d, Y', strtotime($details->graduationdate)) : '_____________'}}.</p>
                            <p style="text-align: justify;">Exempted from the requirements of Special Order for graduation from the {{$rep_coursename}} since the course is Level II Re-accredited by the Association of Christian Schools, Colleges and Universities Accrediting Council, Inc. of which Davao Central College is a member.</p>
                        </td>
                    </tr>
                </table>
                <div style="width: 100%; margin: 0px 30px;  font-size: 15px;" class="text-bold">
                    GRADING SYSTEM USED: 1.0 -100 1.1 - 99 1.2 - 98 1.3 - 97  1.4 - 96  1.5 - 95  1.6 - 94  1.7 - 93 1.8 - 92
        1.9 - 91 2.0 - 90 2.1 - 89 2.2 - 88 2.3 - 87 2.4 - 86 2.5 - 85 2.6 - 84   2.7 - 83 2.8 - 82 2.9 – 81 3.0 - 80  
        3.1 - 79 3.2 - 78 3.3 - 77 3.4 - 76 3.5 - 75  5.0 - Failed 7.0 – Incomplete 9.0 - Dropped
                </div>
                <table style="width: 100%; margin: 10px 30px 0px;  font-size: 12px; table-layout: fixed; page-break-inside: avoid;">
                    <tr>
                        <td class="text-bold v-align-top">REMARKS:&nbsp;&nbsp;&nbsp;&nbsp;<u>{{$details->remarks}}</u></td>
                        <td rowspan="3" class="v-align-top" style="width:7%;">NOTE:</td>
                        <td rowspan="3" class="v-align-top" style="width:25%; font-size: 11px; text-align: justify;">Unless signed by the Registrar and sealed this record is only a statement   of    the   student’s progress to date and not an official transcript. <strong>Erasures or alterations</strong> make this record null and void.</td>
                    </tr>
                    <tr>
                        <td class="v-align-top">Issued on: &nbsp;&nbsp;&nbsp;&nbsp;{{date('F d, Y', strtotime($dateissued))}}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            {{-- </div> --}}
            @if($numberofrows > 20)
            <div id="watermark-4">
                <table style="width: 100%;  font-size: 12px;margin: 0px 30px; table-layout: fixed; " >
                    <tr>
                        <td style="width: 50%;">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T/R of {{$studentinfo->sid}} {{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{ucwords(strtolower($studentinfo->middlename))}}</td>
                        <td style="text-align: right;">OR No.: {{$or ?? ''}}</td>
                        <td style="text-align: right;">{{date('F d, Y')}}</td>
                    </tr>
                </table>
            </div>
            @endif
            @php
                $signatories = DB::table('signatory')
                        ->where('form','tor')
                        ->where('syid', $rep_syid)
                        ->where('deleted','0')
                        ->get();
                
                if(count($signatories) == 0)
                {
                    $signatories = DB::table('signatory')
                        ->where('form','tor')
                        ->where('syid', $rep_syid)
                        ->where('deleted','0')
                        ->where('acadprogid',0)
                        ->get();
                
                    // if(count($signatories)>0)
                    // {
                    //     if(collect($signatories)->where('levelid', $levelid)->count() == 0)
                    //     {
                    //         $signatories = collect($signatories)->where('levelid',0)->values();
                    //     }else{
                    //         $signatories = collect($signatories)->where('levelid', $levelid)->values();
                    //     }
                    // }
                
                    
                }else{
                    // if(collect($signatories)->where('levelid', $levelid)->count() == 0)
                    // {
                    //     $signatories = collect($signatories)->where('levelid',0)->values();
                    // }else{
                    //     $signatories = collect($signatories)->where('levelid', $levelid)->values();
                    // }
                }
                @endphp
                {{-- {{collect($signatories)}} --}}
            <table style="width: 100%; margin: 10px 30px;  font-size: 11px; table-layout: fixed; page-break-inside: avoid;">
                @if(count($signatories)==0)
                <tr>
                    <td>Prepared by:</td>
                    <td style="width: 10%;"></td>
                    <td></td>
                    <td style="width: 10%;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center text-bold"></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">Data Processor</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td>Accounts Cleared:</td>
                    <td></td>
                    <td>Record Verified:</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center text-bold"></td>
                    <td>&nbsp;</td>
                    <td class="text-center text-bold"></td>
                    <td>&nbsp;</td>
                    <td class="text-center text-bold"></td>
                </tr>
                <tr>
                    <td class="text-center">Student’s Accounts</td>
                    <td></td>
                    <td class="text-center">Records Supervisor</td>
                    <td></td>
                    <td class="text-center">Registrar (Consultant)</td>
                </tr>
                @else
                <tr>
                    <td>{{isset($signatories[0]) ? $signatories[0]->title : 'Prepared by:'}}</td>
                    <td style="width: 10%;"></td>
                    <td></td>
                    <td style="width: 10%;"></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center text-bold">{{isset($signatories[0]) ? $signatories[0]->name : ''}}</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center">{{isset($signatories[0]) ? $signatories[0]->description : 'Data Processor'}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td>{{isset($signatories[1]) ? $signatories[1]->title : 'Accounts Cleared: '}}</td>
                    <td></td>
                    <td>{{isset($signatories[2]) ? $signatories[2]->title : 'Record Verified: '}}</td>
                    <td></td>
                    <td>{{isset($signatories[3]) ? $signatories[3]->title : ''}}</td>
                </tr>
                <tr>
                    <td colspan="5">&nbsp;</td>
                </tr>
                <tr>
                    <td class="text-center text-bold">{{isset($signatories[1]) ? $signatories[1]->name : ''}}</td>
                    <td>&nbsp;</td>
                    <td class="text-center text-bold">{{isset($signatories[2]) ? $signatories[2]->name : ''}}</td>
                    <td>&nbsp;</td>
                    <td class="text-center text-bold">{{isset($signatories[3]) ? $signatories[3]->name : ''}}</td>
                </tr>
                <tr>
                    <td class="text-center">{{isset($signatories[1]) ? $signatories[1]->description : 'Student’s Accounts '}}</td>
                    <td></td>
                    <td class="text-center">{{isset($signatories[2]) ? $signatories[2]->description : 'Records Supervisor'}}</td>
                    <td></td>
                    <td class="text-center">{{isset($signatories[3]) ? $signatories[3]->description : 'Registrar (Consultant)'}}</td>
                </tr>
                @endif
            </table>
    </body>
</html>