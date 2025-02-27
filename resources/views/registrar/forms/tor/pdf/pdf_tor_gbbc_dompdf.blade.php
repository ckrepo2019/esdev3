
<html>
<head>
  <style>
    .font-one{
        font-family:  "Times New Roman", Georgia, serif;
        font-stretch: semi-expanded
;
    }
    .font-two{
        font-family: 'Bookman', 'URW Bookman L', serif;
        
    }
    td{
        padding: 0px;
    }
      *{
          
        font-family:  "Times New Roman", Georgia, serif;
      }
      table{
          border-collapse: collapse;
      }
    @page { margin: 80px 25px; size: 8.5in 14in}
    header { position: fixed; top: -60px; left: 0px; right: 0px; height: 250px; }
    footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 250px; }
    /* p { page-break-after: always;} */
    p:last-child { page-break-after: never; }
    
    #watermark {
                position: fixed;

                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   22cm;
                left:     1cm;
                opacity: 0.1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
  </style>
</head>
<body>
    <div id="watermark">
        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="700px" />
    </div>
    @php
    // $registrarname = '';

    // $registrar = DB::table('teacher')
    //     ->where('userid', auth()->user()->id)
    //     ->first();
        
    // if($registrar)
    // {
    //     if($registrar->firstname != null)
    //     {
    //         $registrarname.=$registrar->firstname.' ';
    //     }
    //     if($registrar->middlename != null)
    //     {
    //         $registrarname.=$registrar->middlename[0].'. ';
    //     }
    //     if($registrar->lastname != null)
    //     {
    //         $registrarname.=$registrar->lastname;
    //     }
    // }
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
    @endphp
  <header>
      @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'gbbc')
      <table style="width: 100%; table-layout: fixed;">
        <tr>
            <td style="width: 20%; text-align: right;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"/></td>
            <td colspan="3"><img src="{{base_path()}}/public/assets/images/gbbc/tor-header.png" alt="school" width="520px"/></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td rowspan="2">&nbsp;</td>
            <td style="text-align: center; font-weight: bolder; color: #2d7691; font-size: 17px;  padding-bottom: 20px; padding-top: 20px;">Office of the Registrar</td>
            <td rowspan="4" colspan="2"style="width: 25%; border: 1px solid black; height: 100px;">&nbsp;</td>
        </tr>
        <tr>
            <td style="text-align: center; font-weight: bolder; color: #2d6c91; font-size: 25px;">Offical Transcript of Record</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
    </table>
        @else 
        {{-- <p>Contact CK-IT Department for the TOR header setting</p> --}}
      @endif
    {{-- <table style="width: 100%;">
        <thead>
            <tr nobr="true">
                <th style="width: 20%; text-align: right !important;">
                    <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"/>
                </th>
                <th style="width: 80%;" colspan="2">
                    <img src="{{base_path()}}/public/assets/images/gbbc/tor-header.png" alt="school" width="500px"/>
                </th>
            </tr>
            <tr nobr="true">
                <th rowspan="2" style="width: 20%; "></th>
                <th style="width: 60%; height: 60px; text-align: center; font-weight: bolder; color: #2d7691; font-size: 17px; line-height: 40px;" class="font-one">Office of the Registrar</th>
                <th style="width: 20%; height: 120px; vertical-align: top; padding: 0px;" rowspan="2">
                </th>
            </tr>
            <tr nobr="true">
                <th colspan="3" style="width: 60%;text-align: center; font-weight: bolder; color: #2d6c91; font-size: 23px; height: 60px;" class="font-one">Offical Transcript of Record</th>
            </tr>
        </thead>
    </table> --}}
</header>
  <footer>
    <table style="width: 100%; font-size: 12px; font-weight: bold; text-align: left !important;">
        <thead>
            <tr>
                <th style="width: 10%;">Remarks:</th>
                <th style="border-bottom: 1px solid black;">{{$details->remarks}}</th>
                {{-- <th style="width: 5%;"></th>/ --}}
            </tr>
        </thead>
        <tr>
            {{-- <td>&nbsp;</td> --}}
            <td colspan="2" style="border-bottom: 1px solid black;">&nbsp;</td>
            {{-- <td style="width: 5%;">&nbsp;</td> --}}
        </tr>
    </table>
    <table style="width: 100%; font-size: 12px; text-align: left !important;">
        <thead>
            <tr>
                {{-- <th style="width: 2%;">&nbsp;</th> --}}
                <th style="width: 95%;">GRADING SYSTEM:</th>
                <td style="width: 5%;">&nbsp;</td>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; font-size: 12px; text-align: left !important;">
        <thead>
            <tr>
                <th style="width: 2%;"></th>
                <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;95-100-Denotes Excellent</th>
                <th style="width: 28%;">80-84&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;Denotes Satisfactory</th>
                <th style="width: 20%;">W&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;Withdrawn</th>
                <th style="width: 21%;">&nbsp;&nbsp;FD&nbsp;&nbsp;-&nbsp;Failure Debarred</th>
            </tr>
            <tr>
                <th style="width: 2%;"></th>
                <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;90-94&nbsp;&nbsp;-Denotes Very Good</th>
                <th style="width: 28%;">75-79&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- &nbsp;Denotes Fair</th>
                <th style="width: 20%;">Inc.&nbsp;&nbsp;-&nbsp;&nbsp;Incomplete</th>
                <th style="width: 21%;">&nbsp;&nbsp;Drp&nbsp;&nbsp;-&nbsp;&nbsp;Dropped</th>
            </tr>
            <tr>
                <th style="width: 2%;"></th>
                <th style="width: 29%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;85-89-Denotes Good</th>
                <th style="width: 28%;">74 & below&nbsp;&nbsp;- &nbsp;Signifies Failure</th>
                <th style="width: 41%;" colspan="2">WF&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;Withdrawn Failure</th>
                {{-- <th style="width: 21%;"></th> --}}
            </tr>
        </thead>
    </table>
    {{-- registrar
    assistantreg
    or
    dateissued --}}
    <table style="width: 100%; font-size: 12px; text-align: left !important;">
        <thead>
            <tr>
                {{-- <th style="width: 2%;">&nbsp;</th> --}}
                <th colspan="2" style="width: 9%; font-weight: bold;">Note:</th>
                <th style="width: 91%; font-weight: bold;"></th>
            </tr>
            <tr>
                <th style="width: 2%;">&nbsp;</th>
                <th style="width: 7%;">&nbsp;</th>
                <th style="width: 91%;">
                    &nbsp;&nbsp;&nbsp;This transcript is valid only when it bears the seal of the College and the original signature in ink of the Registrar. Any erasures or alteration made on the entries of this form renders this transcript null and void.
                </th>
            </tr>
        </thead>
    </table>
    <br/>
    <table style="width: 100%; text-align: left !important;">
        <thead style="font-size: 12px;">
            {{-- <tr>
                <th colspan="2" style="width: 100%;">&nbsp;</th>
            </tr> --}}
            <tr>
                <th style="width: 10%;">&nbsp;</th>
                <th style="width: 90%; padding-left: 20%;">Prepared by:</th>
            </tr>
        </thead>
    </table>
    <table style="width: 100%; font-size: 12px;">
        <tr>
            <th style="width: 30%;">&nbsp;</th>
            <th style="width: 40%; text-align: center;">{{$assistantreg}}</th>
            <th style="width: 30%;">&nbsp;</th>
        </tr>
    </table>
    <table style="width: 100%; font-size: 12px;">
        <tr>
            <td style="width: 30%; font-size: 12px;"><em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Seal</em></td>
            <td style="width: 40%; font-size: 12px; text-align: center;">Assistant Registrar</td>
            <th style="width: 30%; text-align: center;">{{$registrar}}</th>
        </tr>
        <tr>
            <td style="width: 30%; font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;O.R #: <span style="font-weight: bold;">{{$or}}</span></td>
            <td style="width: 40%; font-size: 12px; text-align: center;"></td>
            <td style="width: 30%; text-align: center; font-size: 12px;">Registrar</td>
        </tr>
        <tr>
            <td style="width: 30%; font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date Issued: @if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span> @endif</td>
            <td style="width: 40%; font-size: 12px; text-align: center;"></td>
            <td style="width: 30%; text-align: center;"></td>
        </tr>
    </table>
    
  </footer>
      
  <table style="width: 100%; margin: 0px; font-size: 12px; table-layout: fixed !important; margin-top: 180px;">
  <tr>
      <td colspan="8" style="width: 100%;">Revised Transcript</td>
  </tr>
  <tr>
      <td style="width: 18%;">Name:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{$studentinfo->middlename}}</td>
      <td style="width: 15%;">Address:</td>
      <td style="width: 35%;border-bottom: 1px solid black;" colspan="3">{{$address}}</td>
  </tr>
  <tr>
      <td style="width: 18%;">Date of Birth:</td>
      <td style="width: 17%;border-bottom: 1px solid black;">{{$studentinfo->dob}}</td>
      <td style="width: 5%;">Sex</td>
      <td style="width: 10%;border-bottom: 1px solid black;">{{$studentinfo->gender}}</td>
      <td style="">Place of Birth:</td>
      <td style="border-bottom: 1px solid black;" colspan="3"></td>
  </tr>
  
  <tr>
      <td>Parent or Guardian:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->parentguardian}}</td>
      <td style="">Address:</td>
      <td style="border-bottom: 1px solid black;" colspan="3">{{$details->address}}</td>
  </tr>
  <tr>
      <td>Elementary Course:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->elemcourse}}</td>
      <td style="">Date Complete:</td>
      <td style="border-bottom: 1px solid black;" colspan="3">{{$details->elemdatecomp}}</td>
  </tr>
  <tr>
      <td>Secondary Course:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->secondcourse}}</td>
      <td style="">Date Complete:</td>
      <td style="border-bottom: 1px solid black;" colspan="3">{{$details->seconddatecomp}}</td>
  </tr>
  <tr>
      <td>Admission Date:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->admissiondate}}</td>
      <td style="">Degree:</td>
      <td style="border-bottom: 1px solid black;" colspan="3">{{$details->degree}}</td>
  </tr>
  <tr>
      <td>Basis of Admission:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->basisofadmission}}</td>
      <td style="width: 15%;">Major:</td>
      <td style="width: 14%;border-bottom: 1px solid black;">{{$details->major}}</td>
      <td style="width: 7%;">Minor:</td>
      <td style="width: 14%;border-bottom: 1px solid black;"></td>
  </tr>
  <tr>
      <td style="width: 18%;border-bottom: 1px solid black;">Special Order:</td>
      <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->specialorder}}</td>
      <td style="border-bottom: 1px solid black;">Graduation Date:</td>
      <td style="border-bottom: 1px solid black;">{{$details->graduationdate}}</td>
      <td style="border-bottom: 1px solid black;" colspan="2">NSTP Serial No.:</td>
  </tr>
</table>
<div style="width: 100%;"></div>
@php
    $initialschool = null;
    $initialcourse = null;
@endphp
  <main>
    {{-- <p> --}}
        <table style="width: 100%; margin: 0px; table-layout: fixed;" class="font-two">
            {{-- <thead> --}}
                <tr style="font-weight: bold; font-size: 14px; font-weight: bold;font-size: 12px;">
                    <th style="border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">COURSE NUMBER</th>
                    <th style="border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">DESCRIPTIVE TITLE OF THE COURSE</th>
                    <th style="text-align: center; border-bottom: 1px solid black;border-top: 1px solid black;">GRADE</th>
                    <th style="border-bottom: 1px solid black;border-top: 1px solid black;">CREDITS</th>
                </tr>
            {{-- </thead> --}}
            @if(count($records)>0)
                @foreach($records as $key => $record)
                    @if($initialschool == strtolower($record->schoolname))
                    @else
                    @php
                        $initialschool = strtolower($record->schoolname);
                        if($initialcourse != strtolower($record->coursename))
                        {
                            $initialcourse = $record->coursename;
                        }
                    @endphp
                    <tr>
                        <td colspan="6" style="text-align: center"><u style="text-transform: uppercase; font-weight: bold; font-size: 12px;">{{$record->schoolname}}</u><br/><span style="font-weight: bold; text-transform: uppercase; font-size: 12px;">{{$initialcourse}}</span></td>
                    </tr>
                    @endif
                    <tr style="font-size: 12px;">
                        <td style="width: 15%; font-weight: bold;"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif</u></td>
                        <td style="width: 5%; "></td>
                        <td style="width: 10%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                        <td style="width: 50%; "></td>
                        <td style="width: 10%; "></td>
                        <td style="width: 10%; "></td>
                    </tr>
                    @if(count($record->subjdata)>0)
                        @foreach($record->subjdata as $subj)
                            <tr style="font-size: 12px;">
                                <td style="">{{$subj->subjcode}}</td>
                                <td style="">{{$subj->subjunit}}</td>
                                <td style="">&nbsp;</td>
                                <td style="">{{$subj->subjdesc}}</td>
                                <td style=" text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                            </tr>
                        @endforeach
                    @endif
                    @php    
                        if($key<=2)
                        {
                            $record->display = 1;
                        }
                        if($key == 2)
                        {                                
                            break;
                        }
                    @endphp
                @endforeach
            @endif
            {{-- <tr>
                <td colspan="6">
                    <div style="width: 100%; font-size: 12px; text-align: center;">***** Continued on next page *****</div></td>
            </tr> --}}
        </table>
        @if(collect($records)->where('display','0')->count()>0)
        <div style="width: 100%; font-size: 12px; text-align: center;">***** Continued on next page *****</div>
            @php
                $records = collect($records)->where('display','0')->values();
            @endphp
            <table style="width: 100%; margin: 0px; margin-top: 180px; page-break-before: always;" class="font-two">
                @if(count($records)>0)
                    @foreach($records as $key => $record)
                        @if($initialschool == strtolower($record->schoolname))
                        @else
                        @php
                            $initialschool = strtolower($record->schoolname);
                            if($initialcourse != strtolower($record->coursename))
                            {
                                $initialcourse = $record->coursename;
                            }
                        @endphp
                        <tr>
                            <td colspan="6" style="text-align: center; width: 100%;"><u style="text-transform: uppercase; font-weight: bold; font-size: 13px;">{{$record->schoolname}}</u><br/><span style="font-weight: bold; text-transform: uppercase; font-size: 13px;">{{$initialcourse}}</span></td>
                        </tr>
                        @endif
                        <tr style="font-size: 12px;">
                            <td style="width: 14%; font-weight: bold;"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer  @endif</u></td>
                            <td style="width: 5%;"></td>
                            <td style="width: 11%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                            <td style="width: 45%;"></td>
                            <td style="width: 12%;"></td>
                            <td style="width: 13%;"></td>
                        </tr>
                        @if(count($record->subjdata)>0)
                            @foreach($record->subjdata as $subj)
                                <tr style="font-size: 12px;">
                                    <td style="width: 14%;">{{$subj->subjcode}}</td>
                                    <td style="width: 5%;">{{$subj->subjunit}}</td>
                                    <td style="width: 11%;">&nbsp;</td>
                                    <td style="width: 45%;">{{$subj->subjdesc}}</td>
                                    <td style="width: 12%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <td style="width: 13%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                </tr>
                            @endforeach
                        @endif
                        @php    
                            if($key<=3)
                            {
                                $record->display = 1;
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
            @if(collect($records)->where('display','0')->count()>0)
            <div style="width: 100%; font-size: 12px; text-align: center;">***** Continued on next page *****</div>
                @php
                    $records = collect($records)->where('display','0')->values();
                @endphp
                <table style="width: 100%; margin: 0px; margin-top: 180px; page-break-before: always;" class="font-two">
                    @if(count($records)>0)
                        @foreach($records as $key => $record)
                            <tr>
                                <td colspan="6" style="text-align: center; width: 100%;"><u style="text-transform: uppercase; font-weight: bold; font-size: 13px;">{{$record->schoolname}}</u></td>
                            </tr>
                            <tr style="font-size: 12px;">
                                <td style="width: 14%; font-weight: bold;"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester  @else Summer @endif</u></td>
                                <td style="width: 5%;"></td>
                                <td style="width: 11%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                                <td style="width: 45%;"></td>
                                <td style="width: 12%;"></td>
                                <td style="width: 13%;"></td>
                            </tr>
                            @if(count($record->subjdata)>0)
                                @foreach($record->subjdata as $subj)
                                    <tr style="font-size: 12px;">
                                        <td style="width: 14%;">{{$subj->subjcode}}</td>
                                        <td style="width: 5%;">{{$subj->subjunit}}</td>
                                        <td style="width: 11%;">&nbsp;</td>
                                        <td style="width: 45%;">{{$subj->subjdesc}}</td>
                                        <td style="width: 12%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td style="width: 13%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            @php    
                                if($key<=3)
                                {
                                    $record->display = 1;
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
  </main>
</body>
</html>