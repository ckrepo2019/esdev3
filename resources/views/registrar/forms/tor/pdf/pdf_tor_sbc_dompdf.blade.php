
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
    @page { margin: 110px 70px 10px 70px; size: 8.5in 13in}
    header { position: fixed; top: -60px; left: 0px; right: 0px; height: 200px; }
    footer { position: fixed; bottom: -100px; left: -90px; right: 0px; height: 150px; }
    /* p { page-break-after: always;} */
    p:last-child { page-break-after: never; }
    
    #watermark1 {
        opacity: 0.7;
                position: absolute;
                /* bottom:   0px; */
                left:     -70px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.5cm;
                height:   28cm;

                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
    #watermark2 {
        opacity: 0.7;
                position: fixed;
                bottom:   -140px;
                left:     -70px;
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                width:    21.5cm;
                height:   40cm;

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }



    .studinfotable td{

        padding: 2px !important;
    }
    .stretched-text1 {
  letter-spacing: 2px;
  display: inline-block;
  /* font-size: 32px; */
  transform: scaleY(0.5);
        -webkit-transform: scale(1, 1.8);
        -moz-transform: scale(1, 1.8);
        -o-transform: scale(1, 1.8);
        transform-origin: 0 18%;
  /* margin-bottom: -50%; */
}
    .stretched-text2 {
  letter-spacing: 2px;
  display: inline-block;
  /* font-size: 32px; */
  transform: scaleY(0.5);
        -webkit-transform: scale(1.05, 1.9);
        -moz-transform: scale(1.05, 1.9);
        -o-transform: scale(1.05, 1.9);
        transform-origin: 0 18%;
  /* margin-bottom: -50%; */
}
  </style>
</head>
<body>
    @php
        $registrar = collect($signatories)->where('title','Registrar')->first()->name ?? $registrar;
        $preparedby = collect($signatories)->where('description','Prepared by')->first()->name ?? $preparedby;
        $checkedby = collect($signatories)->where('description','Checked by')->first()->name ?? $checkedby;
    @endphp
    <div id="watermark1" style="padding-top: 100px;">
            <img src="{{base_path()}}/public/assets/images/sbc/SBC_bold.png" height="100%" width="100%" />
    </div>
    {{-- <div id="watermark2">
            <img src="{{base_path()}}/public/assets/images/sbc/SBC.png" height="100%" width="100%" />
    </div> --}}
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
        
         $istransferredin = DB::table('college_enrolledstud')->where('id', $studentinfo->id)->where('studstatus',4)->where('deleted','0')->count();

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
            $accredited = 0;
            if($lastcourseid > 1)
            {
                $accredited = DB::table('college_courses')
                    ->where('id', $lastcourseid)
                    ->first()->accredited ?? 0;
            }
    @endphp
  <header>
      
      
    <table style="width: 100%;">
        <tr>
            <td style="width: 21%; text-align: right; vertical-align: top;"><img src="{{base_path()}}/public/assets/images/sbc/logo.jpg" alt="school" style="width:110px;"/></td>
            <td style="width: 58%; text-align:center; vertical-align: top;">
                <span style="font-size:17px; font-weight:bold">SOUTHERN BAPTIST COLLGE</span><br>
                <span style="font-size:15px; font-weight:bold">M'lang, Cotabato</span><br>
                <span style="font-size:13px">Tel.No.(064) 572-4020</span><br>
                <br>
                <span style="padding-top: 10px;">OFFICE OF THE REGISTRAR</span><br>
                <span style="font-size:13px">COLLEGE CODE: 12066</span>
            </td>
            <td style="width: 21%; text-align: center; vertical-align: top; border: 1px solid black; border-bottom: hidden;" @if($accredited == 1)rowspan="3" @endif>
                @if($getphoto)
                    @if($studentinfo->picurl != null || $getphoto->picurl != null)
                        @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                        <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style="width: 140px; height: 140px; margin: 0px; position: absolute;" />
                        @else
                            @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                <img src="{{base_path()}}/public/{{$studentinfo->picurl}}" style="width: 140px; height: 140px; margin: 0px; position: absolute;" /> 
                            @else
                            @endif
                        @endif
                    @endif
                @else                    
                    @if($studentinfo->picurl != null )
                        @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                            <img src="{{base_path()}}/public/{{$studentinfo->picurl}}" style="width: 140px; height: 140px; margin: 0px; position: absolute;" /> 
                        @else
                            @php
                            
                                if(strtoupper($studentinfo->gender) == 'FEMALE'){
                                    $avatar = 'avatar/S(F) 1.png';
                                }
                                else{
                                    $avatar = 'avatar/S(M) 1.png';
                                }
                            @endphp
                            
                        @endif
                    @endif
                @endif
            </td>
        </tr>
        @if($accredited == 1)
        <tr>
            <td></td>
            <td  style="text-align: center; padding: 0px; vertical-align:top;">
                <span style="font-size:10px; color: #207ACC;  font-family: Arial, Helvetica, sans-serif; font-weight: bold;">Accredited Level II: Association of Christian School, Colleges & Universities-
                </span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td  style="text-align: center; padding: 0px; vertical-align:top;">
                <span style="font-size:10px; color: #207ACC;  font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                      Accrediting Council, Inc (ACSCU-ACI)
                </span>
            </td>
            {{-- <td style="font-size: 11px; vertical-align:top; text-align: center; border: 1px solid black; border-top: hidden;">{{$studentinfo->lastname}} {{$studentinfo->suffix}}, {{$studentinfo->firstname}} {{$studentinfo->middlename}}</td> --}}
        </tr>
        @else
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style=" border: 1px solid black; border-top: hidden;">&nbsp;</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td style=" border: 1px solid black; border-top: hidden;">&nbsp;</td>
        </tr>
        @endif
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            {{-- @if($accredited == 0)? --}}
            <td style="font-size: 11px; vertical-align:bottom; text-align: center; border: 1px solid black; border-top: hidden;">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{$studentinfo->middlename}}</td>
            {{-- @else
            <td>&nbsp;</td>
            @endif --}}
        </tr>
    </table>
    <hr style="border: .2px solid #000;border-radius:1px ; margin-bottom: 1px;">
    <hr style="border: 2px solid #000; margin-top: 2px">
    <table style="width: 100%;">
        <tr>
            <td style="text-align: center; font-size:18px">OFFICIAL TRANSCRIPT OF RECORDS</td>
        </tr>
    </table>
</header>
  <footer>
    <table style="width: 100%; table-layout: fixed; font-size: 12px; font-family: Arial, Helvetica, sans-serif !important;">
        <tr>
            <td style="width: 40%;"></td>
            <td style="width: 10%;">Prepared by:</td>
            <td style="border-bottom: 1px solid black; text-align: center;">{{$preparedby}}</td>
            <td style="width: 9%;">Checked by:</td>
            <td style="border-bottom: 1px solid black; text-align: center;">{{$checkedby}}</td>
        </tr>
    </table>
        
  </footer>
  <table class="studinfotable" style="width: 100%; font-size: 11px; margin-top: 170px; font-family: Arial, Helvetica, sans-serif !important;">
    <tr>
        <td style="width: 16%;">Name:</td>
        <td colspan="2" style="border-bottom: 1px solid black; text-transform: uppercase;">{{$studentinfo->lastname}}</td>
        <td style="border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">{{$studentinfo->firstname}}</td>
        <td colspan="2" style="border-bottom: 1px solid black; text-transform: uppercase; text-align: center;">{{$studentinfo->middlename}}</td>
        <td colspan="2" style="">&nbsp;</td>
        <td colspan="3" style="border-bottom: 1px solid black; text-align: center;">@if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span> @endif</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td style="font-size: 10px !important; padding-top: 3px;"><sup>Last Name</sup></td>
        <td>&nbsp;</td>
        <td style="font-size: 10px !important; padding-top: 3px; text-align: center"><sup>First Name</sup></td>
        <td>&nbsp;</td>
        <td style="font-size: 10px !important; padding-top: 3px; text-align: right;"><sup>Middle Name</sup></td>
        <td colspan="2">&nbsp;</td>
        <td colspan="3" style="text-align: center;">Date</td>
    </tr>
    <tr>
        <td>Parent/Guardian:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->parentguardian}}</td>
        <td colspan="2" style="">&nbsp;&nbsp;&nbsp;&nbsp;Gender:</td>
        <td colspan="3" style="border-bottom: 1px solid black;">{{$studentinfo->gender}}</td>
    </tr>
    <tr>
        <td>Address:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$address}}</td>
        <td colspan="2" style="">&nbsp;&nbsp;&nbsp;&nbsp;Birth Date:</td>
        <td colspan="3" style="border-bottom: 1px solid black;">{{$studentinfo->dob}}</td>
    </tr>
    <tr>
        <td>Mailing Address:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->mailingaddress ?? ''}}</td>
        <td colspan="2" style="">&nbsp;&nbsp;&nbsp;&nbsp;Place of Birth:</td>
        <td colspan="3" style="border-bottom: 1px solid black;">&nbsp;{{$details->pob}}</td>
    </tr>
    <tr>
        <td colspan="2">Elementary Course Completed:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->elemcourse}}</td>
        <td colspan="3" style="">&nbsp;&nbsp;&nbsp;&nbsp;School Year:</td>
        <td style="border-bottom: 1px solid black;">{{$details->elemsy}}</td>
    </tr>
    <tr>
        <td colspan="2">Secondary Course Completed:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->secondcourse}}</td>
        <td colspan="3" style="">&nbsp;&nbsp;&nbsp;&nbsp;School Year:</td>
        <td style="border-bottom: 1px solid black;">{{$details->secondsy}}</td>
    </tr>
    <tr>
        @if($details->degree != null && $details->degree != "")
        <td colspan="2">Collegiate Course Completed:</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->degree != null ? $details->degree : ($coursename ?? '')}}</td>
        <td colspan="3" style="width: 15%;">&nbsp;&nbsp;&nbsp;&nbsp;School Year:</td>
        <td style="width: 10%; border-bottom: 1px solid black;">{{$details->thirdsy}}</td>
        @else
        <td colspan="2"></td>
        <td colspan="5"></td>
        <td colspan="3"></td>
        <td>&nbsp;</td>
        @endif
    </tr>
    <tr>
        <td colspan="2" style="width: 22%; ">Degree(s):</td>
        <td colspan="5" style="border-bottom: 1px solid black;">{{$details->degree != null ? $details->degree : ($coursename ?? '')}}</td>
        <td colspan="2" style="width: 5%;">&nbsp;&nbsp;&nbsp;&nbsp;Major:</td>
        <td colspan="2" style="border-bottom: 1px solid black;">{{$details->major != null ? $details->major : ($major ?? '')}}</td>
    </tr>
    <tr>
        <td colspan="11">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="11">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="11">&nbsp;</td>
    </tr>
</table>
{{-- <div style="width: 100%;"></div> --}}
@php
    $currentpage = 1;
    $totalpages = 1;
@endphp
  <main style=" font-family: Arial, Helvetica, sans-serif !important;">
    @if($istransferredin>0)
        <div style="width: 100%; font-size: 12px;">Attached herewith the certified true copy of Transcript of Records from</div>
    @endif
      <table style="width: 100%;" border="1">
        <thead>
            <tr style=" font-size: 12.5px;">
                <th style="width: 12%;">COURSE</th>
                <th style="width: 8%;">NO.</th>
                <th>D E S C R I P T I V E  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T I T L E</th>
                <th style="width: 9%;">GRADE</th>
                <th style="width: 9%;">CREDIT</th>
            </tr>
        </thead>
        <tr>
            <td colspan="5" style="vertical-align: top !important; height: 500px; padding: 0px; font-size: 11px;">
                <table style="width: 100%;" class="font-two">
                    @if(count($records)>0)
                    @php
                    $initialschool = null;
                    $initialcourse = null;
                
                    @endphp
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
                                <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2">
                                    &nbsp;
                                </td>
                                <td style="text-align: center; padding-left: 8px; font-weight: bold;">{{ucwords(strtolower($record->schoolname))}} - {{ucwords(strtolower($record->schooladdress))}}
                                </td>
                                <td style="width: 9%;"></td>
                                <td style="width: 9%;"></td>
                                {{-- <td colspan="5" style="text-align: center; padding-left: 8px;"><u style="text-transform: uppercase; font-weight: bold; font-size: 11px;">{{$record->schoolname}}</u>
                                </td> --}}
                            </tr>
                            @endif
                            <tr>
                                <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2">
                                    &nbsp;
                                </td>
                                <td style="text-align: center; padding-left: 8px; font-weight: bold;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif {{$record->sydesc}}
                                </td>
                                <td style="width: 9%;"></td>
                                <td style="width: 9%;"></td>
                            </tr>
                            {{-- <tr>
                                <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif</u></td>
                                <td style="text-align: left;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                                <td style="width: 9%;"></td>
                                <td style="width: 9%;"></td>
                            </tr> --}}
                            @if(count($record->subjdata)>0)
                                @foreach($record->subjdata as $subj)
                                    @php   
                                            $subj->display = 0;
                                    @endphp
                                    <tr>
                                        <td style="width: 12%; padding-left: 8px;">{{preg_replace("/[^a-zA-Z]+/", "", $subj->subjcode)}}</td>
                                        <td style="width: 8%; text-align: center;">{{filter_var($subj->subjcode, FILTER_SANITIZE_NUMBER_INT)}}</td>
                                        <td style=" padding-left: 30px;">{{$subj->subjdesc}}</td>
                                        <td style="width: 9%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <td style="width: 9%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                    </tr>
                                    @php   
                                            $subj->display = 1;
                                    @endphp
                                @endforeach
                            @endif
                            @php    
                                if($key<3)
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
                </table>
            </td>
        </tr>
    </table>
    @if(collect($records)->where('display','0')->count()>0)
        @php
            $totalpages+=1;
        @endphp
    @endif
    <table  style="width: 100%; margin: 0px;font-size: 11px; table-layout: fixed !important; text-align: center;">
        <tr>
            <td style="width: 20%;">Page {{$currentpage}} of {{$totalpages}}</td>
            <td style="width: 40%;" colspan="2">(Not Valid Without College Seal)</td>
            <td style="width: 20%;"></td>
            <td style="width: 20%;"></td>
        </tr>
        <tr>
            @if($currentpage == $totalpages)
            <td style="width: 20%;">School Seal</td>
            <td style="width: 70%;" colspan="2"></td>
            @else
            <td style="width: 20%;"></td>
            <td style="width: 70%;" colspan="2"></td>
            @endif
            <td style="width: 10%; font-size: 15px; padding-top: 10px;" colspan="2">
                <b>
                {{$registrar}}
                </b>
            </td>
        </tr>
        <tr>
            @if($currentpage == $totalpages)
            <td style="width: 20%; font-weight: bold;">Remarks:</td>
            <td style="width: 70%; border-bottom: 1px solid black; text-align: left;" colspan="2">{{$details->remarks}}</td>
            @else
            <td style="width: 20%;"></td>
            <td style="width: 70%;" colspan="2"></td>
            @endif
            <td style="width: 10%;" colspan="2"><b>Registrar</b></td>
        </tr>
        <tr>
            <td colspan="2" style="vertical-align: top;">
                    <span style="transform: rotate(-90); margin-left: 60px; margin-bottom: 560px; font-size: 11px; position: absolute;;">Space for Authentication</span>
            </td>
            <td colspan="3" style="vertical-align:top; padding-top: 8px;">
                @if($currentpage == $totalpages)
                <table style="width: 100%; border: 1px solid black; font-size: 11px; font-weight: bold; table-layout: fixed;">
                    <tr>
                        <td colspan="4">&nbsp;&nbsp;Grading System:</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;1.00 = 98-100</td>
                        <td>1.75 = 89-91</td>
                        <td>2.50 = 80-82</td>
                        <td>5.0 = Failure</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;1.25 = 95-97</td>
                        <td>2.00 = 86-88</td>
                        <td>2.75 = 77-79</td>
                        <td>NG = No Grade</td>
                    </tr>
                    <tr>
                        <td>&nbsp;&nbsp;1.50 = 92-94</td>
                        <td>2.25 = 83-85</td>
                        <td>3.00 = 75-76</td>
                        <td>DRP = Dropped</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>INC = Incomplete</td>
                    </tr>
                </table>
                @endif
            </td>
        </tr>
    </table>
    
    @if(collect($records)->where('display','0')->count()>0)
        @php
            $currentpage+=1;
        @endphp
        <div style="page-break-before: always">&nbsp;</div>
        <div id="watermark2" style="border: 1px solid black;">
                <img src="{{base_path()}}/public/assets/images/sbc/SBC_bold.png" height="100%" width="100%" />
        </div>
        <table style="width: 100%; margin-top: 120px font-size: 11px;;">
            <tr>
                <td style="width: 80%;"></td>
                <td style="border-bottom: 1px solid black;">@if($dateissued != null) <span style="font-weight: bold;">{{date('m/d/Y', strtotime($dateissued))}}</span> @endif</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Date</td>
            </tr>
        </table>
        <table style="width: 100%;" border="1">
          <thead>
              <tr style=" font-size: 12.5px;">
                  <th style="width: 12%;">COURSE</th>
                  <th style="width: 8%;">NO.</th>
                  <th>D E S C R I P T I V E  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;T I T L E</th>
                  <th style="width: 9%;">GRADE</th>
                  <th style="width: 9%;">CREDIT</th>
              </tr>
          </thead>
          <tr>
              <td colspan="5" style="vertical-align: top !important; height: 700px; padding: 0px; font-size: 11px;" >
                  <table style="width: 100%;" class="font-two">
                    @php
                    $initialschool = null;
                    $initialcourse = null;
                
                    @endphp
                          @foreach(collect($records)->where('display','0')->values() as $key => $record)
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
                                <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2">
                                    &nbsp;
                                </td>
                                <td style="text-align: center; padding-left: 8px; font-weight: bold;">{{ucwords(strtolower($record->schoolname))}} - {{ucwords(strtolower($record->schooladdress))}}
                                </td>
                                <td style="width: 9%;"></td>
                                <td style="width: 9%;"></td>
                                {{-- <td colspan="5" style="text-align: center; padding-left: 8px;"><u style="text-transform: uppercase; font-weight: bold; font-size: 11px;">{{$record->schoolname}}</u>
                                </td> --}}
                            </tr>
                              {{-- <tr>
                                  <td colspan="5" style="text-align: center; padding-left: 8px;"><u style="text-transform: uppercase; font-weight: bold; font-size: 11px;">{{$record->schoolname}}</u><br/><span style="font-weight: bold; text-transform: uppercase; font-size: 11px;">{{$initialcourse}}</span></td>
                              </tr> --}}
                              @endif
                              <tr>
                                  <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2">
                                      &nbsp;
                                  </td>
                                  <td style="text-align: center; padding-left: 8px; font-weight: bold;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif {{$record->sydesc}}
                                  </td>
                                  <td style="width: 9%;"></td>
                                  <td style="width: 9%;"></td>
                              </tr>
                              {{-- <tr>
                                  <td style="width: 20%; font-weight: bold; padding-left: 8px;" colspan="2"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif</u></td>
                                  <td style="text-align: left;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                                  <td style="width: 9%;"></td>
                                  <td style="width: 9%;"></td>
                              </tr> --}}
                              @if(count($record->subjdata)>0)
                                  @foreach($record->subjdata as $subj)
                                      @php   
                                              $subj->display = 0;
                                      @endphp
                                      <tr>
                                        <td style="width: 12%; padding-left: 8px;">{{preg_replace("/[^a-zA-Z]+/", "", $subj->subjcode)}}</td>
                                        <td style="width: 8%; text-align: center;">{{filter_var($subj->subjcode, FILTER_SANITIZE_NUMBER_INT)}}</td>
                                          <td style=" padding-left: 30px;">{{$subj->subjdesc}}</td>
                                          <td style="width: 9%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                          <td style="width: 9%; text-align: center;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                      </tr>
                                      @php   
                                              $subj->display = 1;
                                      @endphp
                                  @endforeach
                              @endif
                              @php    
                                  if($key<3)
                                  {
                                      $record->display = 1;
                                  }
                                  if($key == 2)
                                  {                                
                                      break;
                                  }
                              @endphp
                          @endforeach
                  </table>
              </td>
          </tr>
      </table>
      <table  style="width: 100%; margin: 0px;font-size: 11px; table-layout: fixed !important; text-align: center;">
          <tr>
              <td style="width: 20%;">Page {{$currentpage}} of {{$totalpages}}</td>
              <td style="width: 40%;" colspan="2">(Not Valid Without College Seal)</td>
              <td style="width: 20%;"></td>
              <td style="width: 20%;"></td>
          </tr>
          <tr>
              @if($currentpage == $totalpages)
              <td style="width: 20%;">School Seal</td>
              <td style="width: 70%;" colspan="2"></td>
              @else
              <td style="width: 20%;"></td>
              <td style="width: 70%;" colspan="2"></td>
              @endif
              <td style="width: 10%; font-size: 15px; padding-top: 10px;" colspan="2"><b>{{$registrar}}</b></td>
          </tr>
          <tr>
              @if($currentpage == $totalpages)
              <td style="width: 20%; font-weight: bold;">Remarks:</td>
              <td style="width: 70%; border-bottom: 1px solid black; text-align: left;" colspan="2">{{$details->remarks}}</td>
              @else
              <td style="width: 20%;"></td>
              <td style="width: 70%;" colspan="2"></td>
              @endif
              <td style="width: 10%;" colspan="2"><b>Registrar</b></td>
          </tr>
          <tr>
              <td colspan="2" style="vertical-align: top;">
                      <span style="transform: rotate(-90); margin-left: 85px; margin-bottom: 890px; font-size: 11px;">Space for Authentication</span>
              </td>
              <td colspan="3" style="vertical-align:top; padding-top: 8px;">                
                  @if($currentpage == $totalpages)
                  <table style="width: 100%; border: 1px solid black; font-size: 11px; font-weight: bold; table-layout: fixed;">
                      <tr>
                          <td colspan="4">&nbsp;&nbsp;Grading System:</td>
                      </tr>
                      <tr>
                          <td>&nbsp;&nbsp;1.00 = 98-100</td>
                          <td>1.75 = 89-91</td>
                          <td>2.50 = 80-82</td>
                          <td>5.0 = Failure</td>
                      </tr>
                      <tr>
                          <td>&nbsp;&nbsp;1.25 = 95-97</td>
                          <td>2.00 = 86-88</td>
                          <td>2.75 = 77-79</td>
                          <td>NG = No Grade</td>
                      </tr>
                      <tr>
                          <td>&nbsp;&nbsp;1.50 = 92-94</td>
                          <td>2.25 = 83-85</td>
                          <td>3.00 = 75-76</td>
                          <td>DRP = Dropped</td>
                      </tr>
                      <tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>INC = Incomplete</td>
                      </tr>
                  </table>
                  @endif
              </td>
          </tr>
      </table>
    @endif
    
     
  </main>
</body>
</html>