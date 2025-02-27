
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
        footer { position: fixed; bottom: -30px; left: 0px; right: 0px; height: 100px; }
        /* p { page-break-after: always;} */
        p:last-child { page-break-after: never; }
        
        #watermark {
                    position: fixed;
    
                    /** 
                        Set a position in the page for your image
                        This should center it vertically
                    **/
                    bottom:   22cm;
                    left:     2.5cm;
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
        <script type="text/php">
            if ( isset($pdf) ) {
                $pdf->page_text(30, 970, "SHEET - {PAGE_NUM} of {PAGE_COUNT}", '', 12, array(0,0,0));
            }
        </script> 
        <div id="watermark">
            <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="600px" />
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
          
        <table style="width: 100%; table-layout: fixed;">
            <tr>
                <th rowspan="6" style="text-align: left; border-bottom: 4px solid green;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="100px"/></th>
                <th style="width: 65%; text-align: center; font-size: 20px; color: green;">{{DB::table('schoolinfo')->first()->schoolname}}</th>
                @if($studentinfo->studstatus == 1 || $studentinfo->studstatus == 2 || $studentinfo->studstatus == 4)
                <th rowspan="7"></th>
                @else
                <th rowspan="6"></th>
                @endif
            </tr>
            <tr style="text-align: center;">
                <td>Founded in 1965 by the Oblates of Mary Immaculate (OMI)</td>
            </tr>
            <tr style="text-align: center;">
                <td>Owned by the Archdiocese of Cotabato</td>
            </tr>
            <tr style="text-align: center;">
                <td>Administered  by the Diocesan Clergy of Cotabato (DCC)</td>
            </tr>
            <tr style="text-align: center;">
                <td>Lebak, Sultan Kudarat</td>
            </tr>
            <tr style="text-align: center;">
                <td style="border-bottom: 4px solid green;">Tele-Fax: (064) 205-3041</td>
                @if($studentinfo->studstatus == 1 || $studentinfo->studstatus == 2 || $studentinfo->studstatus == 4)
                @else
                <td style="border-bottom: 4px solid green;"></td>
                @endif
            </tr>
            <tr>
                <td></td>
                <th>OFFICE OF THE REGISTRAR</th>
                @if($studentinfo->studstatus == 1 || $studentinfo->studstatus == 2 || $studentinfo->studstatus == 4)
                @else
                <td></td>
                @endif
            </tr>
            <tr>
                <td></td>
                <th>OFFICIAL TRANSCRIPT OF RECORDS</th>
                <td style="font-size: 11px; font-weight: bold; text-align: center;">
                    @if($studentinfo->studstatus == 1 || $studentinfo->studstatus == 2 || $studentinfo->studstatus == 4)
                    {{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}.
                    @endif
                </td>
            </tr>
        </table>
        <table style="width: 100%; text-align: left !important;">
            <tr>
                <th style="width: 15%;">NAME:</th>
                <th style="border-bottom: 1px solid black;">{{$studentinfo->lastname}}</th>
                <th style="width: 5%;"></th>
                <th style="border-bottom: 1px solid black;">{{$studentinfo->firstname}}</th>
                <th style="width: 5%;"></th>
                <th style="border-bottom: 1px solid black;">{{$studentinfo->middlename}}</th>
            </tr>
        </table>
    </header>
      <footer>
        <table style="width: 100%; ">
            <thead>
                <tr style="font-size: 10px;">
                    <th style="width: 20%; text-align: center;" colspan="2">Not Valid Without<br/>The School Seal</th>
                    <th style="width: 85%; padding-left: 20%;" colspan="2"></th>
                </tr>
                <tr style="font-size: 14px;">
                    <td style="text-align: left;" colspan="4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Prepared & Checked by:</td>
                </tr>
            </thead>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr style="font-size: 14px;">
                <td style="width: 2%;">&nbsp;</td>
                <td style="width: 40%; border-bottom: 1px solid black;">{{$assistantreg}}</td>
                <td></td>
                <td style="width: 15%; border-bottom: 1px solid black;">{{$registrar}}</td>
            </tr>
            <tr style="font-size: 14px;">
                <td colspan="3"></td>
                <td style="text-align: center;">
                    Registrar
                </td>
            </tr>
        </table>
        
      </footer>
        <table style="width: 100%; margin-top: 135px; font-size: 13px;">
            <tr>
                <td style="width: 15%;">Date of Birth:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{date('F d, Y', strtotime($studentinfo->dob))}}</td>
                <td style="width: 15%;">&nbsp;&nbsp;Place of Birth:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;"></td>
            </tr>
            <tr>
                <td style="width: 15%;">Address:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->address}}</td>
                <td style="width: 15%;">&nbsp;&nbsp;Date Admitted:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->admissiondatestr}}</td>
            </tr>
            <tr>
                <td style="width: 15%;">College of:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->collegeof}}</td>
                <td style="width: 15%;">&nbsp;&nbsp;Entrance Data:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->entrancedata}}</td>
            </tr>
        </table>
        <table style="width: 100%; margin-top: 5px; font-size: 13px;">
            <tr>
                <td style="width: 40%;">Intermediate Grades Completed At/Year:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->intermediategrades}}</td>
            </tr>
            <tr>
                <td style="width: 40%;">Secondary Grades Completed At/Year:</td>
                <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->secondarygrades}}</td>
            </tr>
        </table>
        <br/>
      {{-- <table style="width: 100%; margin: 0px; font-size: 12px; table-layout: fixed !important; margin-top: 180px;">
      <tr>
          <td colspan="8" style="width: 100%;">Revised Transcript</td>
      </tr>
      <tr>
          <td style="width: 18%;">Name:</td>
          <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->suffix}}</td>
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
    </table> --}}
    <div style="width: 100%;"></div>
    @php
        $initialschool = null;
        $initialcourse = null;

        $firstcountrows = 0;
        $firstrowsperpage = 40;
        $countrows = 0;
        $rowsperpage = 60;
    @endphp
      <main>
        {{-- <p> --}}
            <table style="width: 100%; margin: 0px; table-layout: fixed; border-top: 2px solid black; border-bottom: 2px solid black;" class="font-two">
                {{-- <thead> --}}
                    <tr style="font-weight: bold; font-size: 14px; font-weight: bold;font-size: 13px;">
                        <th style="width: 20%; border-bottom: 2px solid black;border-top: 1px solid black;border-right: 1px solid black;" rowspan="2">Course Number</th>
                        <th style="width: 50%; border-bottom: 2px solid black;border-top: 1px solid black;" rowspan="2">DESCRIPTIVE TITLE </th>
                        <th style="width: 15%; text-align: center; border: 1px solid black;" colspan="2">GRADES</th>
                        <th style="border-bottom: 2px solid black;border-top: 1px solid black;" rowspan="2">CREDITS</th>
                    </tr>
                    <tr style="font-size: 13px;">
                        <th style=" border: 1px solid black; border-bottom: 2px;">Final</th>
                        <th style=" border: 1px solid black; border-bottom: 2px;">Re-Ex</th>
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
                            // $firstcountrows+=2;
                        @endphp
                        <tr style="font-size: 12px;">
                            <td colspan="5" style="text-align: left; font-weight: bold; padding-left: 10px;"><i>Records from {{$record->schoolname}} - {{$record->schooladdress}}</i></td>
                        </tr>
                        @endif
                        <tr style="font-size: 12px;">
                            <td style="font-weight: bold; padding-left: 10px;" colspan="2">@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                            {{-- <td style="width: 5%; "></td> --}}
                            {{-- <td style="width: 10%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td> --}}
                            <td style=""></td>
                            <td style=""></td>
                            <td style=""></td>
                        </tr>
                        @if(count($record->subjdata)>0)
                            @foreach(collect($record->subjdata)->values()->all() as $key=> $subj)
                                @php
                                
                                $subj->display = 0;
                                @endphp
                                <tr style="font-size: 12px;">
                                    <td style="border-right: 1px solid black;padding-left: 10px;">{{$subj->subjcode}}</td>
                                    {{-- <td style="">&nbsp;</td> --}}
                                    <td style="padding-left: 5px;border-right: 1px solid black;">{{$subj->subjdesc}}</td>
                                    <td style=" text-align: center; font-weight: bold;border-right: 1px solid black;">{{$subj->subjgrade}}</td>
                                    <td style="border-right: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                                    <td style="text-align: center;">{{$subj->subjcredit}}</td>
                                </tr>
                                @php
                                $subj->display = 1;
                                // if(collect($record->subjdata)->where('display','0')->count() == 0)
                                // {                           
                                //     $record->display = 1;
                                // } 
                                if($firstcountrows == $firstrowsperpage)
                                {
                                    // $subj->display = 1;
                                    // if(!isset($record->subjdata[$key+1]))
                                    // {
                                    //     $record->display = 1;
                                    // }
                                    break;
                                }else{

                                    $firstcountrows+=1;
                                    // $subj->display = 1;
                                }
                                @endphp
                            @endforeach
                        @endif
                        @php
                            $displayedtexts = 0;
                        @endphp
                        @if(count($record->texts)>0)
                            @foreach($record->texts as $eachtext)
                                @php
                                    $eachtext->display = 0;
                                @endphp
                                <tr style="font-size: 12px;">
                                    <td colspan="5" style="text-align: center;">{{$eachtext->description}}</td>
                                </tr>
                                @php
                                    if($firstcountrows == $firstrowsperpage)
                                    {
                                        // if(!isset($record->subjdata[$key+1]))
                                        // {
                                        //     $record->display = 1;
                                        // }
                                        break;
                                    }else{

                                        $firstcountrows+=1;
                                        $displayedtexts+=1;
                                        $eachtext->display = 1;
                                    }
                                @endphp
                            @endforeach
                        @endif
                        @php   
                            if(count($record->subjdata) == collect($record->subjdata)->where('display','1')->count() && count($record->texts) == $displayedtexts)
                            {
                                
                                    $record->display = 1;
                            }
                            // if($key<=4)
                            // {
                            //     $record->display = 1;
                            // }
                            // if($key == 4)
                            // {                                
                            //     break;
                            // }
                        @endphp
                    @endforeach
                @endif
                @if(collect($records)->where('display','0')->count()==0)
                <tr style="font-size: 12px;">
                    <td colspan="5" style="text-align: center; font-weight: bold;">***TRANSCRIPT CLOSED***</td>
                </tr>
                @endif
                @for($x = $firstcountrows; $x < $firstrowsperpage; $x++)
                <tr style="font-size: 12px;">
                    <td style="border-right: 1px solid black;padding-left: 10px;">&nbsp;</td>
                    {{-- <td style="">&nbsp;</td> --}}
                    <td style="padding-left: 5px;border-right: 1px solid black;">&nbsp;</td>
                    <td style=" text-align: center; font-weight: bold;border-right: 1px solid black;">&nbsp;</td>
                    <td style="border-right: 1px solid black;"></td>
                    <td style="text-align: center;">&nbsp;</td>
                </tr>
                @endfor
            </table>
            <table style="width: 100%;">
                <tr style="font-size: 14px;">
                    <th colspan="5">G R A D I N G  &nbsp;&nbsp; S Y S T E M</th>
                </tr>
                <tr style="font-size: 14px;">
                    <th colspan="5">UNDERGRADUATE</th>
                </tr>
                <tr style="font-size: 12px;">
                    <td style="width: 20%;"></td>
                    <td style="width: 10%;">95-100</td>
                    <td style="width: 30%;">Excellent</td>
                    <td style="width: 10%;">75-79</td>
                    <td>Below Average</td>
                </tr>
                <tr style="font-size: 12px;">
                    <td></td>
                    <td>90-94</td>
                    <td>Very Good</td>
                    <td>Below 75</td>
                    <td>Failed</td>
                </tr>
                <tr style="font-size: 12px;">
                    <td></td>
                    <td>85-89</td>
                    <td>Above Average</td>
                    <td>Inc.</td>
                    <td>Incomplete</td>
                </tr>
                <tr style="font-size: 12px;">
                    <td></td>
                    <td>80-84</td>
                    <td>Average</td>
                    <td>Drp</td>
                    <td>Dropped</td>
                </tr>
                <tr style="font-size: 12px; text-align: center;">
                    <td colspan="5">ONE UNIT OF CREDIT is one-hour lecture or recitation, each week for the period of a complete Semester.</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 12px; @if(collect($records)->where('display','0')->count()>0) page-break-after: always;@endif">
                <tr>
                    <td style="width: 12%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;REMARKS:</td>
                    <td style="border-bottom: 1px solid black; font-weight: bold;">&nbsp;</td>
                </tr>
            </table>
            @if(collect($records)->where('display','0')->count()>0)
                @php
                    $records = collect($records)->where('display','0')->values();
                @endphp
                <div style="height: 140px;">&nbsp;</div>
                <table style="width: 100%; border-bottom: 2px solid black;" class="font-two">
                    <tr style="font-weight: bold; font-size: 14px; font-weight: bold;font-size: 13px;">
                        <th style="width: 20%; border-bottom: 2px solid black;border-top: 1px solid black;border-right: 1px solid black;" rowspan="2">Course Number</th>
                        <th style="width: 50%; border-bottom: 2px solid black;border-top: 1px solid black;" rowspan="2">DESCRIPTIVE TITLE </th>
                        <th style="width: 15%; text-align: center; border: 1px solid black;" colspan="2">GRADES</th>
                        <th style="border-bottom: 2px solid black;border-top: 1px solid black;" rowspan="2">CREDITS</th>
                    </tr>
                    <tr style="font-size: 13px;">
                        <th style=" border: 1px solid black; border-bottom: 2px;">Final</th>
                        <th style=" border: 1px solid black; border-bottom: 2px;">Re-Ex</th>
                    </tr>
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
                            <tr style="font-size: 12px;">
                                <td colspan="5" style="text-align: left; font-weight: bold; padding-left: 10px;"><i>Records from {{$record->schoolname}} - {{$record->schooladdress}}</i></td>
                            </tr>
                            @endif
                            <tr style="font-size: 12px;">
                                <td style="font-weight: bold; padding-left: 10px;" colspan="2">Cont. @if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @else Summer @endif, SY {{$record->sydesc}}</td>
                                <td style=""></td>
                                <td style=""></td>
                                <td style=""></td>
                            </tr>
                            @if(count(collect($record->subjdata)->where('display','0'))>0)
                                @foreach(collect($record->subjdata)->where('display','0')->values()->all() as $key=> $subj)
                                    <tr style="font-size: 12px;">
                                        <td style="border-right: 1px solid black;padding-left: 10px;">{{$subj->subjcode}}</td>
                                        <td style="padding-left: 5px;border-right: 1px solid black;">{{$subj->subjdesc}}</td>
                                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black;">{{$subj->subjgrade}}</td>
                                        <td style="border-right: 1px solid black; text-align: center; font-weight: bold;">{{$subj->subjreex > 0 ? $subj->subjreex : null}}</td>
                                        <td style="text-align: center;">{{$subj->subjcredit}}</td>
                                    </tr>
                                    @php
                                        if($countrows == $rowsperpage)
                                        {
                                            break;
                                        }else{

                                            $countrows+=1;
                                            $subj->display = 1;
                                        }
                                    @endphp
                                @endforeach
                            @endif
                            @php
                                $seconddisplayedtexts = 0;
                            @endphp
                            @if(count($record->texts)>0)
                                @foreach($record->texts as $eachtext)
                                    @php
                                        $eachtext->display = 0;
                                    @endphp
                                    <tr style="font-size: 12px;">
                                        <td colspan="5" style="text-align: center;">{{$eachtext->description}}</td>
                                    </tr>
                                    @php
                                        if($countrows == $rowsperpage)
                                        {
                                            // if(!isset($record->subjdata[$key+1]))
                                            // {
                                            //     $record->display = 1;
                                            // }
                                            break;
                                        }else{
    
                                            $countrows+=1;
                                            $seconddisplayedtexts+=1;
                                            $eachtext->display = 1;
                                        }
                                    @endphp
                                @endforeach
                            @endif
                            @php   
                            if(count($record->subjdata) == collect($record->subjdata)->where('display','1')->count() && count($record->texts) == $displayedtexts)
                            {
                                
                                    $record->display = 1;
                            }
                            @endphp
                        @endforeach
                    @endif
                    @if(collect($records)->where('display','0')->count()==0)
                    <tr style="font-size: 12px;">
                        <td colspan="5" style="text-align: center; font-weight: bold;">***TRANSCRIPT CLOSED***</td>
                    </tr>
                    @endif
                    @for($x = $countrows; $x < $rowsperpage; $x++)
                    <tr style="font-size: 12px;">
                        <td style="border-right: 1px solid black;padding-left: 10px;">&nbsp;</td>
                        {{-- <td style="">&nbsp;</td> --}}
                        <td style="padding-left: 5px;border-right: 1px solid black;">&nbsp;</td>
                        <td style=" text-align: center; font-weight: bold;border-right: 1px solid black;">&nbsp;</td>
                        <td style="border-right: 1px solid black;"></td>
                        <td style="text-align: center;">&nbsp;</td>
                    </tr>
                    @endfor
                </table>
                @endif
      </main>
    </body>
    </html>