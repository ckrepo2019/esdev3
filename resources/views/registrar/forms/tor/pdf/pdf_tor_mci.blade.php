
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
              
            font-family: Arial, Helvetica, sans-serif;
          }
          table{
              border-collapse: collapse;
          }
        @page { margin: 30px 50px; size: 8.5in 14in}
        header { position: fixed; top: 0px; left: 0px; right: 0px; height: 320px;}
        footer { position: fixed; bottom: 0; left: 0px; right: 0px; height: 200px; }
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
        @endphp
        <header>
            <table style="width: 100%; table-layout: fixed; font-size: 12px; text-align: center;">
                <tr>
                    <td><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="100px" /></td>
                </tr>
                <tr>
                    <td><img src="{{base_path()}}/public/assets/images/mci/coe_header.jpg" alt="school" width="50%"/></td>
                </tr>
                <tr>
                    <td>{{$schoolinfo->address}}</td>
                </tr>
                <tr>
                    <td style="color: blue; font-size: 11px;"><u>WEBSITE: www.mariancollege.edu.ph</u></td>
                </tr>
            </table>
            <table style="width: 100%; table-layout: fixed; font-size: 12px; text-align: center;">
                <tr>
                    <td></td>
                    <td style="color: blue; width: 50%; font-size: 11px;"><u>EMAIL ADDRESS: administrator@mariancollege.edu.ph</u></td>
                    <td rowspan="9" style="border: 1px solid black; text-align: center;  vertical-align: middle; padding: 0px; line-height: 5px;">
                        
                    {{-- <td rowspan="9" style="border: 1px solid black; text-align: center;  vertical-align: middle; padding: 0px; line-height: 5px;">@if($getphoto)<img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" alt="school" style="width: 100%; margin: 0px; position: absolute;" />@else PHOTO @endif --}}
                        @if($getphoto)
                            @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                            <img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style="width: 100%; margin: 0px; position: absolute;" draggable="false" id="image-view"/>
                            @else
                                @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                    {{-- <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;"  draggable="false"  id="image-view"/>  --}}
                                    <img src="{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;"  draggable="false"  id="image-view"/> 
                                @else
                                PHOTO
                                @endif
                            @endif
                        @else
                            
                            @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                {{-- <img src="/{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;" draggable="false"   id="image-view"/>  --}}
                                <img src="{{$studentinfo->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')}}" style="width: 100%; margin: 0px; position: absolute;" draggable="false"   id="image-view"/> 
                            @else
                                PHOTO
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <th style="font-size: 17px;">OFFICE OF THE REGISTRAR</th>
                </tr>
                <tr>
                    <td></td>
                    <th style="font-size: 15px;">COLLEGIATE DEPARTMENT</th>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <th style="font-size: 19px;">OFFICIAL TRANSCRIPT OF RECORD</th>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
            </table>
            {{-- <table style="width: 100%; table-layout: fixed; font-size: 12px;">
                <tr>
                    <td style="text-align: right;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="100px" /></td>
                    <td style="width: 55%; text-align: center; font-weight: bold;">
                        <img src="{{base_path()}}/public/assets/images/mci/coe_header.jpg" alt="school" width="80%"/><br/>
                        {{$schoolinfo->address}}<br/>
                        <span style="font-size: 17px;">OFFICE OF THE REGISTRAR</span><br/>
                        <span>COLLEGIATE DEPARTMENT</span><br/><br/>
                        <span style="font-size: 20px;">OFFICIAL TRANSCRIPT OF RECORD</span><br/>&nbsp;
                    </td>
                    <th style="border: 1px solid black; text-align: center;  vertical-align: middle;"> @if($getphoto)<img src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" alt="school" style="height: 1.5in; width: 1.3in; margin: 0px;" />@else PHOTO @endif</td>
                </tr>
            </table> --}}
            <table style="width: 100%; font-size: 10.5px;">
                <tr>
                    <td style="width: 15%;">NAME</td>
                    <td style="width: 35%;">: {{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->suffix}} {{$studentinfo->middlename}}</td>
                    <td style="width: 5%;">AGE</td>
                    <td style="width: 5%;">: {{$studentinfo->age}}</td>
                    <td style="width: 5%;">SEX</td>
                    <td>: {{$studentinfo->gender}}</td>
                    <td style="width: 12%;">CIVIL STATUS</td>
                    <td>:{{$details->civilstatus}}</td>
                </tr>
                <tr>
                    <td>DATE OF BIRTH</td>
                    <td>: {{$studentinfo->dob != null ? date('M d, Y', strtotime($studentinfo->dob)) : ''}}</td>
                    <td colspan="3">PLACE OF BIRTH</td>
                    <td colspan="3">: {{$details->pob}}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">PARENT/GUARDIAN</td>
                    <td style="vertical-align: top;">:&nbsp; {{$guardianname}}</td>
                    <td colspan="3" style="vertical-align: top;">ADDRESS</td>
                    <td colspan="3">:&nbsp; {{$address}}</td>
                </tr>
            </table>
            
            <div style="width: 100%; text-align: center; font-weight: bold; font-size: 14px; margin-top: 5px;">RECORDS OF PRELIMINARY GRADUATION</div>
            
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 30%;">PRIMARY GRADES COMPLETED</td>
                    <td style="width: 50%;">:&nbsp; {{$details->primaryschoolname}}</td>
                    <td style="width: 3%;">SY</td>
                    <td>:&nbsp; {{$details->primaryschoolyear}}</td>
                </tr>
                <tr>
                    <td>INTERMEDIATE GRADES COMPLETED</td>
                    <td>:&nbsp; {{$details->intermediatecourse}}</td>
                    <td>SY</td>
                    <td>:&nbsp; {{$details->intermediatesy}}</td>
                </tr>
                <tr>
                    <td>SECONDARY COURSE COMPLETED</td>
                    <td>:&nbsp;{{$details->secondcourse}} </td>
                    <td>SY</td>
                    <td>:&nbsp; {{$details->secondsy}}</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px;">
                <tr>
                    <td style="width: 15%;">TITLE OF DEGREE</td>
                    <td style="width: 40%;">:&nbsp; {{$details->degree}}</td>
                    <td style="width: 20%;">DATE OF GRADUATION</td>
                    <td>:&nbsp; {{$details->graduationdate}}</td>
                </tr>
                <tr>
                    <td>MAJOR</td>
                    <td>:&nbsp; {{$details->major}}</td>
                    <td>MINOR</td>
                    <td>:&nbsp; {{$details->minor ?? ''}}</td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom: 1px solid black;"></td>
                </tr>
            </table>
        </header>
      <footer style=" font-family: Arial, Helvetica, sans-serif !important; font-size: 11px;">
        <div style="width: 100%; font-weight: bold;">OFFICIAL MARKS:</div>
            <table style="width: 100%; font-size: 10.5px; table-layout: fixed;" border="1">
                <tr>
                    <th style="vertical-align: top; padding: 3px;">
                        1.00 - 96-<br/>
                        1.25 - 94-<br/>
                        1.50 - 92<br/>
                        1.75 - 89
                    </th>
                    <th style="vertical-align: top; padding: 3px;">
                        2.00 - 87-88<br/>
                        2.25 - 84-86<br/>
                        2.50 - 82-83<br/>
                        2.75 - 79-81
                    </th>
                    <th style="vertical-align: top; text-align: left; padding-left: 40px; padding: 3px;">
                        3.00 - 75-78<br/>
                        5.00 - BELOW 75<br/>
                        INC - INCOMPLETE<br/>
                        DR - DROPPED
                    </th>
                    <th style="width: 28%; vertical-align: top; text-align: left; padding: 3px;">
                        NC - NO CREDIT<br/>
                        FA - FAILUREDUE TO ATTENDANCE
                    </th>
                </tr>
            </table>
            <br/>
            <div style="width: 100%; font-size: 10.5px;">REMARKS: {{$details->remarks}}</div>
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; font-size: 11px; text-align: center; table-layout: fixed;">
                <tr>
                    <th style="border-bottom: 1px solid black; text-align: center;">{{$assistantreg}}</th>
                    <td></td>
                    <th style="border-bottom: 1px solid black; text-align: center;">{{$registrar}}</td>
                </tr>
                <tr>
                    <td>PREPARED BY</td>
                    <td>{{date('l, F d, Y')}}</td>
                    <td>COLLEGE REGISTRAR</td>
                </tr>
                <tr>
                    <th colspan="3">(NOT VALID WITHOUT COLLEGE SEAL)</td>
                </tr>
            </table>
        {{-- <table style="width: 100%; border: 1px solid grey; margin-bottom: 2px;">
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
        </table> --}}
      </footer>
          
    @php
        $initialschool = null;
        $initialcourse = null;

        $firstcountrows = 0;
        $firstrowsperpage = 23;
        $countrows = 0;
        $rowsperpage = 24;
        $thirdcountrows = 0;
        $thirdrowsperpage = 24;
        $fourthcountrows = 0;
        $fourthrowsperpage = 24;
    @endphp

    <main style="">
        <table style="width: 100%; font-size: 11px; margin-top: 558px; text-align: center;">
            <thead>
                <tr>
                    <td style="width: 15%; border-bottom: 1px solid black;">COURSE NO.</td>
                    <td style="width: 50%; border-bottom: 1px solid black;">DESCRIPTIVE TITLE</td>
                    <td style=" border-bottom: 1px solid black;">FINAL</td>
                    <td style=" border-bottom: 1px solid black;">R-EXAM</td>
                    <td style=" border-bottom: 1px solid black;">CREDIT</td>
                </tr>
            </thead>
            @if(count($records)>0)
                @foreach($records as $key => $record)
                    @php
                    $subjnum = count($record->subjdata);
                    @endphp
                    @if($initialschool!=$record->schoolname)
                    @php
                        $initialschool = $record->schoolname;
                        if($initialcourse != strtolower($record->coursename))
                        {
                            $initialcourse = $record->coursename;
                        }
                    @endphp
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="text-align: left; font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;"><i>{{$initialschool}} - {{$initialcourse}}</i></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                    @endif
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                    @if(count($record->subjdata)>0)
                        @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                            @php
                            
                            $subj->display = 0;
                            @endphp
                            <tr style="font-size: 11px;">
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left;">{{$subj->subjcode}}</td>
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;">{{$subj->subjdesc}}</td>
                                <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjgrade}}</td>
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                                <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjcredit > 0 ? $subj->subjcredit : ''}}</td>
                            </tr>
                            @php
                            $subj->display = 1;
                            if($firstcountrows == $firstrowsperpage)
                            {
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
                        if($firstcountrows == $firstrowsperpage)
                        {
                            break;
                        }
                        $displayedtexts = 0;
                    @endphp
                @endforeach
                @if($firstcountrows < $firstrowsperpage)
                    @for($x = $firstcountrows; $x < $firstrowsperpage; $x++)
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;"></td>
                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;"></td>
                        <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                    @endfor
                @endif
                <tr style="font-size: 11px;">
                    <td style="border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px; border-bottom: 1px solid black;"></td>
                    <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold; border-bottom: 1px solid black;"></td>
                    <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                </tr>
            @endif
        </table>
        @if(collect($records)->where('display','0')->count()>0)
        <div style="width: 100%; page-break-before: always; margin-bottom: 420px; ">&nbsp;</div>
        <br/>&nbsp;
        <br/>&nbsp;
        <br/>&nbsp;
        <table style="width: 100%; font-size: 11px; margin-top: 49px; text-align: center;">
            <thead>
                <tr>
                    <td style="width: 15%; border-bottom: 1px solid black;">COURSE NO.</td>
                    <td style="width: 50%; border-bottom: 1px solid black;">DESCRIPTIVE TITLE</td>
                    <td style=" border-bottom: 1px solid black;">FINAL</td>
                    <td style=" border-bottom: 1px solid black;">R-EXAM</td>
                    <td style=" border-bottom: 1px solid black;">CREDIT</td>
                </tr>
            </thead>
            @php
                $records = collect($records)->where('display','0')->values();
            @endphp
                @if(count($records)>0)
                    @foreach($records as $key => $record)
                    @php
                    // $countrows = 0;
                    $subjnum = collect($record->subjdata)->where('display','0')->count();
                    $record->subjdata = collect($record->subjdata)->where('display','0')->values();
                    $break = 0;
                    @endphp
                    @if($initialschool!=$record->schoolname)
                    @php
                        $initialschool = $record->schoolname;
                        if($initialcourse != strtolower($record->coursename))
                        {
                            $initialcourse = $record->coursename;
                        }
                    @endphp
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="text-align: left; font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;"><i>{{$initialschool}} - {{$initialcourse}}</i></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                    @endif
                        @if(count($record->subjdata)>0)
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        </tr>
                            @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                                @php                                
                                $subj->display = 0;
                                $countrows+=1;
                                if(($key+1) == $subjnum)
                                {
                                    $record->display = 1;
                                }else{
                                    $record->display = 0;
                                }
                                @endphp
                            <tr style="font-size: 11px;">
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left;">{{$subj->subjcode}}</td>
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;">{{$subj->subjdesc}}</td>
                                <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjgrade}}</td>
                                <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                                <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjcredit > 0 ? $subj->subjcredit : ''}}</td>
                            </tr>
                                @php
                                $subj->display = 1;
                                if($countrows == $rowsperpage)
                                {
                                    $break+=1;
                                }
                            if($break>0)
                            {
                                break;
                            }
                            @endphp
                            @endforeach
                        @endif
                        @php
                        if($countrows == $rowsperpage)
                        {
                            break;
                        }
                        @endphp
                    @endforeach
                    @if($countrows < $rowsperpage)
                        @for($x = $countrows; $x < $rowsperpage; $x++)
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;"></td>
                            <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;"></td>
                            <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;"></td>
                        </tr>
                        @endfor
                    @endif
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;">&nbsp;</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px; border-bottom: 1px solid black;"></td>
                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold; border-bottom: 1px solid black;"></td>
                        <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                    </tr>
                @endif
            </table>
        @endif
        {{-- <span style="font-size: 10px;">
            {{collect(collect($records)->where('sydesc','2021-2022')->where('semid','1')->first()->subjdata)->where('display','0')->count()}}
        </span> --}}
        @if(collect($records)->where('display','0')->count()>0)
        <div style="width: 100%; page-break-before: always; margin-bottom: 420px; ">&nbsp;</div>
        <br/>&nbsp;
        <br/>&nbsp;
        <br/>&nbsp;
        <table style="width: 100%; font-size: 11px; margin-top: 49px; text-align: center;">
            <thead>
                <tr>
                    <td style="width: 15%; border-bottom: 1px solid black;">COURSE NO.</td>
                    <td style="width: 50%; border-bottom: 1px solid black;">DESCRIPTIVE TITLE</td>
                    <td style=" border-bottom: 1px solid black;">FINAL</td>
                    <td style=" border-bottom: 1px solid black;">R-EXAM</td>
                    <td style=" border-bottom: 1px solid black;">CREDIT</td>
                </tr>
            </thead>
            @php
                $records = collect($records)->where('display','0')->values();
            @endphp
                @if(count($records)>0)
                @foreach($records as $key => $record)
                @php
                // $countrows = 0;
                $subjnum = collect($record->subjdata)->where('display','0')->count();
                $record->subjdata = collect($record->subjdata)->where('display','0')->values();
                $break = 0;
                @endphp
                @if($initialschool!=$record->schoolname)
                @php
                    $initialschool = $record->schoolname;
                    if($initialcourse != strtolower($record->coursename))
                    {
                        $initialcourse = $record->coursename;
                    }
                @endphp
                <tr style="font-size: 11px;">
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="text-align: left; font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;"><i>{{$initialschool}} - {{$initialcourse}}</i></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                </tr>
                @endif
                    @if(count($record->subjdata)>0)
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                        @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                            @php                                
                            $subj->display = 0;
                            $thirdcountrows+=1;
                            if(($key+1) == $subjnum)
                            {
                                $record->display = 1;
                            }else{
                                $record->display = 0;
                            }
                            @endphp
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left;">{{$subj->subjcode}}</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;">{{$subj->subjdesc}}</td>
                            <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjgrade}}</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                            <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjcredit > 0 ? $subj->subjcredit : ''}}</td>
                        </tr>
                            @php
                            $subj->display = 1;
                            if($thirdcountrows == $thirdrowsperpage)
                            {
                                $break+=1;
                            }
                        if($break>0)
                        {
                            break;
                        }
                        @endphp
                        @endforeach
                    @endif
                    @php
                    if($thirdcountrows == $thirdrowsperpage)
                    {
                        break;
                    }
                    @endphp
                @endforeach
                    @if($thirdcountrows < $thirdrowsperpage)
                        @for($x = $thirdcountrows; $x < $thirdrowsperpage; $x++)
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;"></td>
                            <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;"></td>
                            <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;"></td>
                        </tr>
                        @endfor
                    @endif
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;">&nbsp;</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px; border-bottom: 1px solid black;"></td>
                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold; border-bottom: 1px solid black;"></td>
                        <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                    </tr>
                @endif
            </table>
        @endif
        @if(collect($records)->where('display','0')->count()>0)
        <div style="width: 100%; page-break-before: always; margin-bottom: 420px; ">&nbsp;</div>
        <br/>&nbsp;
        <br/>&nbsp;
        <br/>&nbsp;
        <table style="width: 100%; font-size: 11px; margin-top: 49px; text-align: center;">
            <thead>
                <tr>
                    <td style="width: 15%; border-bottom: 1px solid black;">COURSE NO.</td>
                    <td style="width: 50%; border-bottom: 1px solid black;">DESCRIPTIVE TITLE</td>
                    <td style=" border-bottom: 1px solid black;">FINAL</td>
                    <td style=" border-bottom: 1px solid black;">R-EXAM</td>
                    <td style=" border-bottom: 1px solid black;">CREDIT</td>
                </tr>
            </thead>
            @php
                $records = collect($records)->where('display','0')->values();
            @endphp
                @if(count($records)>0)
                @foreach($records as $key => $record)
                @php
                // $countrows = 0;
                $subjnum = collect($record->subjdata)->where('display','0')->count();
                $record->subjdata = collect($record->subjdata)->where('display','0')->values();
                $break = 0;
                @endphp
                @if($initialschool!=$record->schoolname)
                @php
                    $initialschool = $record->schoolname;
                    if($initialcourse != strtolower($record->coursename))
                    {
                        $initialcourse = $record->coursename;
                    }
                @endphp
                <tr style="font-size: 11px;">
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="text-align: left; font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;"><i>{{$initialschool}} - {{$initialcourse}}</i></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                </tr>
                @endif
                    @if(count($record->subjdata)>0)
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="font-weight: bold; padding-left: 10px; border-right: 1px solid black; border-left: 1px solid black;">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black;"></td>
                    </tr>
                        @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                            @php                                
                            $subj->display = 0;
                            $fourthcountrows+=1;
                            if(($key+1) == $subjnum)
                            {
                                $record->display = 1;
                            }else{
                                $record->display = 0;
                            }
                            @endphp
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left;">{{$subj->subjcode}}</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;">{{$subj->subjdesc}}</td>
                            <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjgrade}}</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                            <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;">{{$subj->subjcredit > 0 ? $subj->subjcredit : ''}}</td>
                        </tr>
                            @php
                            $subj->display = 1;
                            if($fourthcountrows == $fourthrowsperpage)
                            {
                                $break+=1;
                            }
                        if($break>0)
                        {
                            break;
                        }
                        @endphp
                        @endforeach
                    @endif
                    @php
                    if($fourthcountrows == $fourthrowsperpage)
                    {
                        break;
                    }
                    @endphp
                @endforeach
                    @if($fourthcountrows < $fourthrowsperpage)
                        @for($x = $fourthcountrows; $x < $fourthrowsperpage; $x++)
                        <tr style="font-size: 11px;">
                            <td style="border-right: 1px solid black; border-left: 1px solid black;">&nbsp;</td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px;"></td>
                            <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black;"></td>
                            <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold;"></td>
                            <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black;"></td>
                        </tr>
                        @endfor
                    @endif
                    <tr style="font-size: 11px;">
                        <td style="border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;">&nbsp;</td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: left; padding-left: 8px; border-bottom: 1px solid black;"></td>
                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                        <td style="border-right: 1px solid black; border-left: 1px solid black; text-align: center; font-weight: bold; border-bottom: 1px solid black;"></td>
                        <td style="text-align: center;border-right: 1px solid black; border-left: 1px solid black; border-bottom: 1px solid black;"></td>
                    </tr>
                @endif
            </table>
        @endif
        </main>
    </body>
    </html>