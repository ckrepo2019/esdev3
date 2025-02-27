
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
        .table-records td{
            padding: 0px;
        }
          *{
              
            font-family:  "Times New Roman", Georgia, serif;
          }
          table{
              border-collapse: collapse;
          }
        @page { margin: 20px; size: 8.5in 11in}
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 250px; }
        footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 180px; }
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
    </head>
    <body>
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_text(550, 765, "Page {PAGE_NUM} of {PAGE_COUNT}", '', 9, array(0,0,0));

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
        @endphp
        <header>
            <img src="{{base_path()}}/public/assets/images/hccsi/header_tor.png" alt="school" width="100%"/>
        </header>
      <footer style=" font-family: Arial, Helvetica, sans-serif !important;">
        <table style="width: 100%; border: 1px solid grey; margin-bottom: 2px;">
            <tr>
                <td rowspan="3" style="width: 15%; background-color: gray; color: white; text-align: center; font-size: 11px; font-weight: bold;">GRADING<br/>SYSTEM</td>
                <td style="width: 7%; font-size: 10px;">&nbsp;&nbsp;100-95</td>
                <td style="width: 15%;  font-size: 10px;">A+ Excellent</td>
                <td style="width: 7%; font-size: 10px;">84-80</td>
                <td style="width: 15%;  font-size: 10px;">B to B- Good</td>
                <td style="width: 7%; font-size: 10px;">Below 75</td>
                <td style=" font-size: 10px;">Failed</td>
            </tr>
            <tr style="font-size: 11px;">
                <td>&nbsp;&nbsp;94-90</td>
                <td>A+ Superior</td>
                <td>79-76</td>
                <td>C Fair</td>
                <td>W</td>
                <td>Withdrawn</td>
            </tr>
            <tr style="font-size: 11px;">
                <td>&nbsp;&nbsp;89-85</td>
                <td>B+ Very Good</td>
                <td>75</td>
                <td>C- Passed</td>
                <td>DRP</td>
                <td>Dropped</td>
            </tr>
        </table>
        <table style="width: 100%; border: 2px solid black;">
            <tr style="font-size: 11px;">
                <td rowspan="2" style="padding: 10px 20px 5px 20px; vertical-align: bottom;">NOT VALID WITHOUT<br/>OFFICIAL HCCSI SEAL</td>
                <td rowspan="2" style="width: 40%;"></td>
                <td style="width: 30%;"></td>
            </tr>
            <tr>
                <td style="text-align: center;  font-size: 12px; font-weight: bold;">&nbsp;{{$registrar}}&nbsp;</td>
            </tr>
            <tr style="font-size: 11px;">
                <td></td>
                <td></td>
                <td style="text-align: center; vertical-align: top; padding-bottom: 8px;">School Registrar</td>
            </tr>
        </table>
        {{-- @if(collect($records)->where('display','0')->count()>0) 
        <div style="width: 100%;">O.R. No.:          &nbsp;&nbsp;&nbsp;&nbsp;Date: </div>
        @endif --}}
      </footer>
          
    @php
        $initialschool = null;
        $initialcourse = null;

        $firstcountrows = 0;
        $firstrowsperpage = 23;
        $countrows = 0;
        $rowsperpage = 60;
    @endphp

    <main style="margin-top: 120px;">
        <div style="width: 100%; text-align: center; margin-top: 100px;">Office of the College Registrar</div>
        <div style="width: 100%; text-align: center; font-weight: bold;">Official Transcript of Records</div>
        <br/>
        <br/>
        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 23px;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{$studentinfo->middlename}}</div>
        <div style="width: 100%; text-align: center; font-weight: bold; font-size: 14px;">&nbsp;{{$address}}&nbsp;</div>
        <table style="width: 100%; margin-top: 10px; font-size: 11px; font-family: Arial, Helvetica, sans-serif !important; table-layout: fixed;">
            <tr>
                <td style="width: 40%; border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;" colspan="2">
                    Date of Birth: &nbsp;&nbsp;<strong style="margin: 0px;">{{$details->dob != null ? date('M d, Y', strtotime($details->dob)) : ''}}</strong>
                </td>
                <td style="width: 30%; border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Sex:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->gender}}</strong></td>
                <td rowspan="8" style="width: 20%; border: 1px solid gray; text-align: center; padding: 0px !important;">
                    @if($getphoto)<img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" alt="school" style="height: 1.5in; width: 1.3in; margin: 0px;" />@endif
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Place of Birth:&nbsp;&nbsp;<strong style="margin: 0px;">{{$studentinfo->pob != null ? $studentinfo->pob : $details->pob}}</strong></td>
                <td style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">ACR No. (If Alien):&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->acrno}}</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Citizenship:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->citizenship}}</strong></td>
                <td style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Civil Status:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->civilstatus}}</strong></td>
            </tr>
            <tr>
                <td style="width: 40%; border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Name of Father:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->fathername}}</strong></td>
                <td colspan="2" style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Name of Mother:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->mothername}}</strong></td>
                {{-- <td></td> --}}
            </tr>
            <tr>
                <td colspan="3" style="border: 1px solid gray; padding: 0px !important;">Parents' Address:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->parentaddress}}</strong></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Name of Guardian:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->parentguardian}}</strong></td>
                <td colspan="2" style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Guardian's Address:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->guardianaddress}}</strong></td>
                {{-- <td></td> --}}
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Elementary Course Completed at:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->elemcourse}}</strong></td>
                <td style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Year:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->elemsy}}</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Secondary Course Completed at:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->secondcourse}}</strong></td>
                <td style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray; padding: 0px !important;">Year:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->secondsy}}</strong></td>
            </tr>
            <tr>
                <td colspan="2" style="border-left: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Basis of Admission:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->basisofadmission}}</strong></td>
                <td colspan="2" style=" border-right: 1px solid gray; border-top: 1px solid gray; border-bottom: 1px solid gray;">Date of Admission:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->admissiondatestr}}</strong></td>
            </tr>
            <tr>
                <td colspan="4" style="border: 1px solid gray;">Degree/Course:&nbsp;&nbsp;<strong style="margin: 0px;">{{$details->degree}}</strong></td>
            </tr>
        </table>
    <table style="width: 100%; font-family: Arial, Helvetica, sans-serif !important; margin-top: 5px; table-layout: fixed;" class="table-records">
        <thead>
            <tr style="font-size: 12px;">
                <th style="width: 13%; border-top: 2px solid black; border-bottom: 2px solid black;">SUBJECT CODE</th>
                <th style="border-top: 2px solid black; border-bottom: 2px solid black; text-align: left;">SUBJECT DESCRIPTION</th>
                <th style="width: 15%; border-top: 2px solid rgb(3, 2, 2); border-bottom: 2px solid black;">FINAL RATING</th>
                <th style="width: 10%; border-top: 2px solid black; border-bottom: 2px solid black;">COMPLETION</th>
                <th style="width: 15%; border-top: 2px solid black; border-bottom: 2px solid black;">CREDITS</th>
            </tr>
        </thead>
        @if(count($records)>0)
            @foreach($records as $key => $record)
                @php
                    $subjnum = count($record->subjdata);
                    $break = 0;
                @endphp
                @if($initialschool == strtolower($record->schoolname))
                @else
                    @php
                        $initialschool = strtolower($record->schoolname);
                        if($initialcourse != strtolower($record->coursename))
                        {
                            $initialcourse = $record->coursename;
                        }
                        // $firstcountrows+=2;
                    @endphp
                @endif
                <tr style="font-size: 12px;">
                    <td colspan="5" style="background-color: #ddd; font-weight: bold;">AY {{$record->sydesc}} @if($record->semid == 1)1st Semester @elseif($record->semid == 2)2nd Semester @else Summer @endif - {{strtoupper($initialschool)}}</td>
                </tr>
                @if(count($record->subjdata)>0)
                    @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                        @php
                        
                        $subj->display = 0;
                        @endphp
                        <tr style="font-size: 12px;">
                            <td style="padding-left: 5px;">{{$subj->subjcode}}</td>
                            <td style="">{{$subj->subjdesc}}</td>
                            <td style=" text-align: center;">{{$subj->subjgrade}}</td>
                            <td style="text-align: center;"></td>
                            <td style="text-align: center;">{{$subj->subjunit}}</td>
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
                if(count($record->subjdata) == $subjnum)
                {
                    $record->display = 1;
                }
                if($break>0)
                {
                    break;
                }
                @endphp
            @endforeach
        @endif
        @if(collect($records)->where('display','0')->count()==0)
            <tr style="font-size: 12px;">
                <div id="watermark">O.R. No.: <span style="font-weight: bold;">{{$or}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: @if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span>@endif</div>
                <td colspan="5" style="text-align: center; font-weight: bold; background-color: #ddd;">*** &nbsp;&nbsp;TRANSCRIPT CLOSED&nbsp;&nbsp; ***</td>
            </tr>
        @else
            <tr style="font-size: 12px;">
                <td colspan="5" style="text-align: center; font-weight: bold; background-color: #ddd;">*** &nbsp;&nbsp;CONTINUED ON THE NEXT PAGE&nbsp;&nbsp; ***</td>
            </tr>
        @endif
    </table>
        @if(collect($records)->where('display','0')->count()>0)
            @php
                $records = collect($records)->where('display','0')->values();
            @endphp
            <div style="height: 137px;">&nbsp;</div>
            <table style="width: 100%;">
                <tr>
                    <td style="width: 70%; font-weight: bold;">&nbsp;&nbsp;{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{$studentinfo->middlename}}</td>
                    <td style="width: 30%; font-weight: bold; text-align: right;">Official Transcript of Records</td>
                </tr>
            </table>
            <table style="width: 100%; font-family: Arial, Helvetica, sans-serif !important; table-layout: fixed;" class="table-records">
                <thead>
                    <tr style="font-size: 12px;">
                        <th style="width: 13%; border-top: 2px solid black; border-bottom: 2px solid black;">SUBJECT CODE</th>
                        <th style="border-top: 2px solid black; border-bottom: 2px solid black; text-align: left;">SUBJECT DESCRIPTION</th>
                        <th style="width: 15%; border-top: 2px solid black; border-bottom: 2px solid black;">FINAL RATING</th>
                        <th style="width: 10%; border-top: 2px solid black; border-bottom: 2px solid black;">COMPLETION</th>
                        <th style="width: 15%; border-top: 2px solid black; border-bottom: 2px solid black;">CREDITS</th>
                    </tr>
                </thead>
                @if(count($records)>0)
                    @foreach($records as $key => $record)
                    @php
                    $subjnum = count($record->subjdata);
                    $record->subjdata = collect($record->subjdata)->where('display','0')->values();
                    $break = 0;
                    @endphp
                        @if($initialschool == strtolower($record->schoolname))
                        @else
                        @php
                            $initialschool = strtolower($record->schoolname);
                            if($initialcourse != strtolower($record->coursename))
                            {
                                $initialcourse = $record->coursename;
                            }
                            // $firstcountrows+=2;
                        @endphp
                        @endif
                        @if(count($record->subjdata)>0)
                        <tr style="font-size: 12px;">
                            <td colspan="5" style="background-color: #ddd; font-weight: bold;">AY {{$record->sydesc}} @if($record->semid == 1)1st Semester @elseif($record->semid == 2)2nd Semester @else Summer @endif - {{strtoupper($initialschool)}}</td>
                        </tr>
                            @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                                @php
                                
                                $subj->display = 0;
                                @endphp
                                <tr style="font-size: 12px;">
                                    <td style="padding-left: 5px;">{{$subj->subjcode}}</td>
                                    <td style="">{{$subj->subjdesc}}</td>
                                    <td style=" text-align: center;">{{$subj->subjgrade}}</td>
                                    <td style="text-align: center;"></td>
                                    <td style="text-align: center;">{{$subj->subjunit}}</td>
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
                        if(count($record->subjdata) == $subjnum)
                        {
                            $record->display = 1;
                        }
                        if($break>0)
                        {
                            break;
                        }
                        @endphp
                    @endforeach
                @endif
                @if(collect($records)->where('display','0')->count()==0)
                <tr style="font-size: 12px;">
                    <td colspan="5" style="text-align: center; font-weight: bold; background-color: #ddd;">*** &nbsp;&nbsp;TRANSCRIPT CLOSED&nbsp;&nbsp; ***</td>
                </tr>
                @else
                <tr style="font-size: 12px;">
                    <td colspan="5" style="text-align: center; font-weight: bold; background-color: #ddd;">*** &nbsp;&nbsp;CONTINUED ON THE NEXT PAGE&nbsp;&nbsp; ***</td>
                </tr>
                @endif
            </table>
            <div id="watermark">O.R. No.: <span style="font-weight: bold;">{{$or}}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date: @if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span>@endif</div>
            @endif
        </main>
    </body>
    </html>