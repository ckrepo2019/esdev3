<html>
    <head>
        <title>Transcript of Records</title>
        <style>
            html{
                /* margin: 20px; */
                /* size: 8.5in 14in; */
            }
            * {
                
            font-family: Arial, Helvetica, sans-serif !important;
            }
            @page { margin: 20px 20px; size: 8.5in 14in}
            header { position: fixed; top: 0px; left: 0px; right: 0px; height: 320px;}
            footer { position: fixed; bottom: 0; left: 0px; right: 0px; height: 300px; }
            table {
                border-collapse: collapse;
            }
            body{
                margin-top: 200px;
                margin-bottom: 250px;
            }
            .watermark {
                margin-right: 25px;
                float: right;
                position: fixed;
                font-size: 11px;
                font-family: Arial, Helvetica, sans-serif !important;
                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                top:   0cm;
                right:     1cm;
                opacity: 1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            .displayphoto {
                margin-right: 40px;
                float: right;
                position: fixed;
                font-size: 11px;
                font-family: Arial, Helvetica, sans-serif !important;
                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                top:   -0.2cm;
                right:     1cm;
                opacity: 1;

                /** Change image dimensions**/
                /* width:    8cm;
                height:   8cm; */

                /** Your watermark should be behind every content**/
                z-index:  -1000;
            }
            .remarks {
                position: fixed;
                font-size: 11px;
                font-family: Arial, Helvetica, sans-serif !important;
                /** 
                    Set a position in the page for your image
                    This should center it vertically
                **/
                bottom:   5.3cm;
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
            function capitalize($word)
            {
                return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
            }
            function lowercase_word($word)
            {
                return \App\Http\Controllers\SchoolFormsController::lowercase_word($word);
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
            $num_firstrow = 31;
            $num_row = 39;
        @endphp
        <header>
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <td style="vertical-align: top; text-align: center; padding-left: 30px;"><img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" width="115px" /></td>
                    <td style="width: 65%; text-align: center;">
                        <h2 style="margin-bottom: 1px;">{{$schoolinfo->schoolname}}</h2>
                        <div style="width: 100%; font-size: 13px;">Office of the Registrar</div>
                        <div style="width: 100%; font-size: 13px;">U. Akut-Tiano Brothers Sts., Cagayan de Oro City, Philippines </div>
                        <div style="width: 100%; font-size: 13px;">Tel. No. (088) 856-4239 Local 606 / (088) 856-4232 (Fax)</div>
                        {{-- <br/>&nbsp; --}}
                        <div style="width: 100%; line-height: 22px;">&nbsp;</div>
                    </td>
                    <td style="vertical-align: top;">
                        {{-- <div style="width: 75%; height: 115px;  border: 1px solid black;">
                            &nbsp;
                        </div> --}}
                    </td>
                </tr>
                <tr>
                    <th style="background-color: #9fd5a5; border: 1px solid grey; padding: 1px;" colspan="3">
                        <img src="{{base_path()}}/public/excelformats/pcc/tor_header1.png" width="53%" />
                    </th>
                </tr>
                <tr>
                    <td style="text-align: center; border-bottom: 3px solid #319f3e; padding-top: 3px;" colspan="3">
                        <img src="{{base_path()}}/public/excelformats/pcc/tor_header2.png" width="80%" />
                    </td>
                </tr>
            </table>
        </header>
        <footer>
            <table style="width: 100%; font-size: 13px;">
                <tr>
                    <td colspan="8">GRADING SYSTEM: Grade Equivalent in Percentage (%)</td>
                </tr>
                <tr>
                    <td style="width: 11.5%;"></td>
                    <td>1.0 - 95 - 100</td>
                    <td>1.4 - 91</td>
                    <td>1.8 - 87</td>
                    <td>2.2 - 83</td>
                    <td>2.6 - 79</td>
                    <td>3.0 - 75</td>
                    <td style="width: 11.5%;"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>1.1 - 94</td>
                    <td>1.5 - 90</td>
                    <td>1.9 - 86</td>
                    <td>2.3 - 82</td>
                    <td>2.7 - 78</td>
                    <td>5.0 - Failure</td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>1.2 - 93</td>
                    <td>1.6 - 89</td>
                    <td>2.0 - 85</td>
                    <td>2.4 - 81</td>
                    <td>2.8 - 77</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td>1.3 - 92</td>
                    <td>1.7 - 88</td>
                    <td>2.1 - 84</td>
                    <td>2.5 - 80</td>
                    <td>2.9 - 79</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="8" style="line-height: 8px;">&nbsp;</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">&nbsp;</td>
                    <td colspan="7">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8" style="line-height: 8px;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="8" style="text-align: center; font-weight: bold;">CERTIFICATION</td>
                </tr>
                {{-- <tr>
                    <td colspan="8" style="line-height: px;">&nbsp;</td>
                </tr> --}}
                <tr>
                    <td colspan="8">
                        <p style="text-indent: 50px; margin: 4px 0px 0px 0px;">I hereby certify that the foregoing records of <u></u> has been verified and that the true copies of the official transcript of records substantiating the same are kept in the files of our school.</p>
                    </td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11px; margin-top: 5px;">
                <tr>
                    <td style="width: 11.5%; text-align: center">NOT VALID </td>
                    <td style="width: 5%;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="width: 11.5%;"></td>
                </tr>
                <tr>
                    <td style="text-align: center;">WITHOUT SEAL</td>
                    <td></td>
                    <td colspan="2" style=" font-size: 13px;">Prepared by:</td>
                    <td colspan="2" style=" font-size: 13px;">Checked & Verified by:</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="8" style="line-height: 8px;">&nbsp;</td>
                </tr>
                <tr style=" font-size: 13px; text-align: center;">
                    <td></td>
                    <td></td>
                    <td colspan="2" style="border-bottom: 1px solid black; text-align: center;">&nbsp;{{$assistantreg}}&nbsp;</td>
                    <td></td>
                    <td colspan="2" style="border-bottom: 1px solid black; text-align: center;">&nbsp;{{$registrar}}&nbsp;</td>
                    <td></td>
                </tr>
                <tr style=" font-size: 13px; text-align: center;">
                    <td></td>
                    <td></td>
                    <td colspan="2"><em>Registrar's Clerk</em></td>
                    <td></td>
                    <td colspan="2"><em>Registrar</em></td>
                    <td></td>
                </tr>
            </table>
        </footer>
        <main>
                @if($getphoto)
                    @if($studentinfo->picurl != null || $getphoto->picurl != null)
                        @if (file_exists(base_path().'/public/'.$getphoto->picurl))
                        <img class="displayphoto" src="{{URL::asset($getphoto->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}"  style=" width: 100px; height: 120px;" />
                        @else
                            @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                                <img class="displayphoto" src="{{base_path()}}/public/{{$studentinfo->picurl}}" style=" width: 100px; height: 120px;" /> 
                            @endif
                        @endif
                    @endif
                @else                    
                    @if($studentinfo->picurl != null )
                        @if (file_exists(base_path().'/public/'.$studentinfo->picurl))
                            <img  class="displayphoto" src="{{base_path()}}/public/{{$studentinfo->picurl}}" style=" width: 100px; height: 120px;" /> 
                        @else
                            <div class="displayphoto" style="border: 1px solid black; width: 100px; height: 120px;">
                                &nbsp;
                            </div>
                        @endif
                    @endif
                @endif
            <table style="width: 100%; font-size: 11.5px; margin-top: 7px;">
                <tr>
                    <td style="width: 5%; font-weight: bold;">Name</td>
                    <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->lastname}}</td>
                    <td style="width: 1%; font-weight: bold;">,</td>
                    <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->firstname}}</td>
                    <td style="width: 5%;border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->middlename != null ? $studentinfo->middlename[0] : ''}}</td>
                    <td style="width: 1%;">&nbsp;</td>
                    <td style="width: 16%; font-weight: bold;">Date of Admission:</td>
                    <td style="border-bottom: 1px solid black;width: 35%;">{{$details->admissiondatestr}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align: center;"><sup><small>(Last)</small></sup></td>
                    <td></td>
                    <td style="text-align: center;"><sup><small>(First)</small></sup></td>
                    <td style="text-align: center;"><sup><small>(Middle)</small></sup></td>
                    <td></td>
                    <td style="font-weight: bold;">Admission Credential:</td>
                    <td style="border-bottom: 1px solid black; font-weight: bold;">{{$details->entrancedata ?? ''}}</td>
                </tr>
            </table>
            <table style="width: 100%; font-size: 11.5px; table-layout: fixed;">
                <tr>
                    @if(strlen($address) > 32)
                    @php
                    $num_firstrow-=1;
                    @endphp
                    <td style="vertical-align: top;" colspan="2">
                        <div style=" font-weight: bold; width: 32%;position: relative; display: inline-block;">Date of Birth:</div>
                        <div style=" text-align: center; width: 67%; position: relative; display: inline-block; border-bottom: 1px solid black;">{{$studentinfo->dob != null ? date('m/d/Y', strtotime($studentinfo->dob)) : ''}}</div> </td>
                    <td style="width: 16%; vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 68%; position: relative; display: inline-block;">Sex</div><div style=" text-align: center; width: 30%;border-bottom: 1px solid black; position: relative; display: inline-block;">{{$studentinfo->gender[0] ?? ''}}</div></td>
                    <td style="width: 1%;"></td>
                    <td style="width: 51%;" colspan="2"><div style=" font-weight: bold; width: 22%; position: relative; display: inline-block; ">Home Address:</div> <u>{{$address}}</u></td>
                    @else
                    <td style="font-weight: bold;width: 10.5%;">Date of Birth:</td>
                    <td style="border-bottom: 1px solid black;">{{$studentinfo->dob != null ? date('m/d/Y', strtotime($studentinfo->dob)) : ''}}</td>
                    <td style="width: 11%; font-weight: bold;">Sex</td>
                    <td style="width: 5%; border-bottom: 1px solid black;">{{$studentinfo->gender[0] ?? ''}}</td>
                    <td style="width: 1%;"></td>
                    <td style="width: 12%; font-weight: bold;">Home Address:</td>
                    <td style="width: 39%;border-bottom: 1px solid black;">{{$address}}</td>
                    @endif
                </tr>
            </table>
            <table style="width: 100%; font-size: 11.5px; table-layout: fixed;">
                <tr>                    
                @if(strlen($studentinfo->pob) > 25 || (isset($details->cityaddress) && strlen($details->cityaddress) > 35))
                    @php
                    $num_firstrow-=1;
                    @endphp
                    <td style="vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 32%;position: relative; display: inline-block; vertical-align:">Place of Birth:</div>
                        @if(strlen($studentinfo->pob) > 25)
                        <u>{{$studentinfo->pob}}</u>
                        @else
                        <div style=" text-align: center; width: 68%; position: relative; display: inline-block; border-bottom: 1px solid black;">{{$studentinfo->pob}}</div>
                        @endif
                    </td>
                    <td style="width: 16%; vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 68%; position: relative; display: inline-block;">Civil Status</div><div style=" text-align: center; width: 30%;border-bottom: 1px solid black; position: relative; display: inline-block;">{{$details->civilstatus[0] ?? ''}}</div></td>
                    <td style="width: 1%;"></td>
                    <td style="width: 51%; vertical-align: top;" colspan="2">
                        <div style=" font-weight: bold; width: 22%; position: relative; display: inline-block;">City Address:</div>                            
                        @if(strlen($details->cityaddress) > 40)
                        <u style=" position: relative;">{{$details->cityaddress}}</u>
                        @else
                        <div style=" text-align: center; width: 77%; position: relative; display: inline-block; border-bottom: 1px solid black;">{{$details->cityaddress}}</div>
                        @endif
                    </td>
                @else
                    <td style="font-weight: bold;width: 10.5%;">Place of Birth:</td>
                    <td style="border-bottom: 1px solid black;">{{$studentinfo->pob}}</td>
                    <td style="width: 11%;font-weight: bold;">Civil Status</td>
                    <td style="width: 5%;border-bottom: 1px solid black;">{{$details->civilstatus[0] ?? ''}}</td>
                    <td style="width: 1%;"></td>
                    <td style="font-weight: bold; width: 12%;">City Address:</td>
                    <td style="width: 39%; border-bottom: 1px solid black;"><center>{{$details->cityaddress != null ? $details->cityaddress : '-do-'}}</center></td>
                @endif
            </tr>
            </table>
            <table style="width: 100%; font-size: 11.5px; table-layout: fixed;">
                <tr>       
                    @if(strlen($details->citizenship) > 18 || strlen($studentinfo->religionname) > 15 || strlen($details->degree) > 35)
                        @php
                        $num_firstrow-=1;
                        @endphp
                        <td style="vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 32%;position: relative; display: inline-block;">Citizenship:</div>
                            @if(strlen($details->citizenship) > 20)
                            <u style="word-wrap: break-word;">{{$details->citizenship}}</u>
                            @else
                            <div style=" text-align: center; width: 66%; position: relative; display: inline-block; border-bottom: 1px solid black;">&nbsp;{{$details->citizenship}}</div>
                            @endif
                        </td>
                        <td style="width: 22%; vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 28%; position: relative; display: inline-block;">Religion</div>
                            @if(strlen($studentinfo->religionname) > 20)
                            <u style="word-wrap: break-word;">{{$studentinfo->religionname}}</u>
                            @else
                            <div style=" text-align: center; width: 70%;border-bottom: 1px solid black; position: relative; display: inline-block;">&nbsp;{{$studentinfo->religionname}}</div>
                            @endif
                        </td>
                        <td style="width: 1%;"></td>
                        <td style="width: 51%; vertical-align: top;" colspan="2">
                            <div style=" font-weight: bold; width: 22%; position: relative; display: inline-block;">Degree:</div>                            
                            @if(strlen($details->degree) > 40)
                            <u style=" position: relative;">{{$details->degree}}</u>
                            @else
                            <div style=" text-align: center; width: 77%; position: relative; display: inline-block; border-bottom: 1px solid black;"><center>{{$details->degree != null ? $details->degree : '-'}}</center></div>
                            @endif
                        </td>
                    @else
                    <td style="width: 10.5%; font-weight: bold;">Citizenship:</td>
                    <td style="border-bottom: 1px solid black;">{{$details->citizenship}}</td>
                    <td style="width: 6%;font-weight: bold;">Religion</td>
                    <td style="width: 16%;border-bottom: 1px solid black;">{{$studentinfo->religionname}}</td>
                    <td style="width: 1%;"></td>
                    <td style="width: 12%; font-weight: bold;">Degree:</td>
                    <td style="width: 39%; border-bottom: 1px solid black;"><center>{{$details->degree != null ? $details->degree : '-'}}</center></td>
                    @endif
                </tr>
            </table>
            @php
            if($major != null)
            {
                $details->major = $major;
            }
            @endphp
            <table style="width: 100%; font-size: 11.5px; table-layout: fixed; margin: 0px;">
                <tr>
                    @if(strlen($studentinfo->fathername) > 18 || strlen($studentinfo->mothername) > 15 || strlen($details->major) > 15)
                    @php
                    $num_firstrow-=1;
                    @endphp
                    <td style="vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 20%;position: relative; display: inline-block;">Father</div>
                        @if(strlen($studentinfo->fathername) > 20)
                        <u style="word-wrap: break-word;">{{$studentinfo->fathername}}</u>
                        @else
                        <div style=" text-align: center; width: 78%; position: relative; display: inline-block; border-bottom: 1px solid black;">&nbsp;{{$studentinfo->fathername}}</div>
                        @endif
                    </td>
                    <td style="width: 22%; vertical-align: top;" colspan="2"><div style=" font-weight: bold; width: 28%; position: relative; display: inline-block;">Mother</div>
                        @if(strlen($studentinfo->mothername) > 20)
                        <u style="word-wrap: break-word;">{{$studentinfo->mothername}}</u>
                        @else
                        <div style=" text-align: center; width: 70%;border-bottom: 1px solid black; position: relative; display: inline-block;">&nbsp;{{$studentinfo->mothername}}</div>
                        @endif
                    </td>
                    <td style="width: 1%;"></td>
                    <td style="width: 24%; vertical-align: top;" colspan="2">
                        <div style=" font-weight: bold; width: 49%; position: relative; display: inline-block;">Major:</div>                            
                        @if(strlen($details->major) > 15)
                        <u style=" position: relative;">{{$details->major}}</u>
                        @else
                        <div style=" text-align: center; width: 48%; position: relative; display: inline-block; border-bottom: 1px solid black;"><center>{{$details->major != null ? $details->major : '-'}}</center></div>
                        @endif
                    </td>
                    @else
                    <td style="width: 6%; font-weight: bold;">Father</td>
                    <td style="border-bottom: 1px solid black;">{{$studentinfo->fathername}}</td>
                    <td style="width: 6%; font-weight: bold;">Mother</td>
                    <td style="width: 16%;border-bottom: 1px solid black;">{{$studentinfo->fathername}}</td>
                    <td style="width: 1%;"></td>
                    <td style="width: 12%; font-weight: bold;">Major:</td>
                    <td style="width: 12%; border-bottom: 1px solid black;"><center>{{$details->major}}</center></td>
                    @endif
                    <td style="width: 16%; font-weight: bold; text-align: center; vertical-align: bottom;">Date Conferred:</td>
                    <td style="width: 11%; border-bottom: 1px solid black; vertical-align: bottom;"><center>{{$details->graduationdate}}</center></td>
                </tr>
            </table>
            <h4 style="text-align: center; margin: 15px 0px;">RECORD OF PRELIMINARY EDUCATION</h4>
            <table style="width: 100%; font-size: 11.5px; table-layout: fixed; margin: 0px; line-height: 15px;">
                <tr>
                    <td style="width: 10.5%; font-weight: bold;">Primary:</td>
                    <td style="border-bottom: 1px solid black;">{{$details->primaryschoolname ?? ''}}</td>
                    <td style="width: 1%;"></td>
                    <td style="border-bottom: 1px solid black;">{{$details->primaryschooladdress ?? ''}}</td>
                    <td style="width: 3%; font-weight: bold;text-align: right;"> SY</td>
                    <td style="width: 11%; border-bottom: 1px solid black;">{{$details->primaryschoolyear ?? ''}}</td>
                </tr>
                <tr>
                    <td style=" font-weight: bold;">Intermediate:</td>
                    <td style="border-bottom: 1px solid black;">{{$details->intermediateschoolname ?? ''}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;">{{$details->intermediateschooladdress ?? ''}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;">{{$details->intermediatesy ?? ''}}</td>
                </tr>
                <tr>
                    <td style=" font-weight: bold;">High School:</td>
                    <td style="border-bottom: 1px solid black;">{{$details->juniorschoolname ?? ''}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;">{{$details->juniorschooladdress ?? ''}}</td>
                    <td></td>
                    <td style="border-bottom: 1px solid black;">{{$details->juniorschoolyear ?? ''}}</td>
                </tr>
            </table>
            <br/>
            @php
            
                $baseschool = null;
                $basecourse = null;
                $baseyearsem = null;
            @endphp
            <table style="width: 100%; table-layout: fixed; margin: 0px; font-size: 12px; border-bottom: 1px solid black;" class="table-grades">
                <thead>
                    <tr style="font-size: 13px !important;">
                        <td style="width: 23%; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; padding: 1.5px 1px 1.5px 1.5px !important; vertical-align: middle; text-align: center;">
                            <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">COURSE & NUMBER</div>
                        </td>
                        <td style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                            <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">DESCRIPTIVE TITLE</div>
                        </td>
                        <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                            <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">FINAL RATING</div>
                        </td>
                        <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; padding: 1.5px 1.5px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                            <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">CREDITS</div>
                        </td>
                    </tr>
                </thead>
                {{-- @if(count($records)>0)
                    @foreach($records as $eachrecord)
                        <tr></tr>
                    @endforeach
                @endif --}}
                
                @foreach($records as $key=>$record)
                    @php
                        $yearandsem = ($record->semid == 1 ? ' First Semester' : ($record->semid == 2 ? ' Second Semester' : 'Summer')).'SY: '.$record->sydesc.', '.(strtolower($schoolinfo->schoolname) == strtolower($record->schoolname) ? 'Cagayan de Oro City' : $record->schooladdress);
                        $num_firstrow -=2;
                    @endphp
                
                    <tr>
                        <th></th>
                        <th>{{$record->schoolname}}</th>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th></th>
                        <th>{{$yearandsem}}</th>
                        <td></td>
                        <td></td>
                    </tr>
                    @foreach($record->subjdata as $eachsubject)
                        <tr>
                            <td style="padding-left: 35px;">{{$eachsubject->subjcode}}</td>
                            <td style="padding-left: 10px;">{{lowercase_word($eachsubject->subjdesc)}}</td>
                            <td style="text-align: center;">{{$eachsubject->subjgrade}}</td>
                            <td style="text-align: center;">{{$eachsubject->subjcredit > 0 ? $eachsubject->subjcredit : ''}}</td>
                        </tr>
                        @php
                            $eachsubject->display = 1;
                            if($num_firstrow == 0)
                            {
                                break;
                            }else{
                                $num_firstrow -=1;
                            }
                        @endphp
                    @endforeach
                    {{-- @if($num_firstrow > 0)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $num_firstrow -=1;
                        @endphp
                    @endif --}}
                    @php
                        if(collect($record->subjdata)->where('display','1')->count() == count($record->subjdata))
                        {
                            $record->display = 1;
                        }
                        if($num_firstrow == 0)
                        {
                            break;
                        }
                    @endphp
                @endforeach
                @for($x = 0; $x < $num_firstrow; $x++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor
                <tr>
                    <td style="border-bottom: 1px solid black;">&nbsp;</td>
                    <td style="border-bottom: 1px solid black; text-align: center;">@if(collect($records)->where('display','0')->count() > 0)-more entries next page-@endif</td>
                    <td style="border-bottom: 1px solid black;"></td>
                    <td style="border-bottom: 1px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-top: 1px solid black; line-height: 0.5px;">&nbsp;</td>
                </tr>
            </table>
            @php
                $recordsleft = collect($records)->where('display','0')->values();
            @endphp
            <table style="width: 100%; font-size: 13px;" class="remarks">
                <tr>
                    <td style="font-weight: bold; width: 12%;">REMARKS:</td>
                    <td style="border-bottom: 1px solid black;">{{count($recordsleft) > 0 ? 'Please refer to page 2 for more entries…' : $details->remarks}}</td>
                </tr>
            </table>
            @if(count($recordsleft)>0)
                <table style="width: 100%; font-size: 11.5px; margin-top: 13px; page-break-before: always;">
                    <tr>
                        <td style="width: 5%; font-weight: bold;">Name:</td>
                        <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->lastname}}</td>
                        <td style="width: 1%; font-weight: bold;">,</td>
                        <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->firstname}}</td>
                        <td style="width: 14%; border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->middlename != null ? $studentinfo->middlename[0] : ''}}</td>
                        <td style="width: 14%;"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: center;"><small>(Last)</small></td>
                        <td></td>
                        <td style="text-align: center;"><small>(First)</small></td>
                        <td style="text-align: center;"><small>(Middle)</small></td>
                        <td></td>
                    </tr>
                </table>
                <div class="watermark">Page: 2</div>
                <br/>
                <table style="width: 100%; table-layout: fixed; margin: 0px; font-size: 12px; border-bottom: 1px solid black;" class="table-grades">
                    <thead>
                        <tr style="font-size: 13px !important;">
                            <td style="width: 23%; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; padding: 1.5px 1px 1.5px 1.5px !important; vertical-align: middle; text-align: center;">
                                <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">COURSE & NUMBER</div>
                            </td>
                            <td style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">DESCRIPTIVE TITLE</div>
                            </td>
                            <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">FINAL RATING</div>
                            </td>
                            <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; padding: 1.5px 1.5px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">CREDITS</div>
                            </td>
                        </tr>
                    </thead>
                    <tr>
                        <td><em>cont'd</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>      
                    <tr>
                        <td><em>&nbsp;</em></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>      
                    @php
                        $num_row -=1;
                    @endphp
                    @foreach($recordsleft as $key=>$record)
                        @php
                            $yearandsem = ($record->semid == 1 ? ' First Semester' : ($record->semid == 2 ? ' Second Semester' : 'Summer').'SY: '.$record->sydesc.', '.(strtolower($schoolinfo->schoolname) == strtolower($record->schoolname) ? 'Cagayan de Oro City' : $record->schooladdress));
                            $num_row -=2;
                        @endphp
                    
                        <tr>
                            <th></th>
                            <th>{{$record->schoolname}}</th>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <th></th>
                            <th>{{$yearandsem}}</th>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $subjects = collect($record->subjdata)->where('display','0')->values();
                        @endphp
                        @foreach($subjects as $eachsubject)
                            <tr>
                                <td style="padding-left: 35px;">{{$eachsubject->subjcode}}</td>
                                <td style="padding-left: 10px;">{{lowercase_word($eachsubject->subjdesc)}}</td>
                                <td style="text-align: center;">{{$eachsubject->subjgrade}}</td>
                                <td style="text-align: center;">{{$eachsubject->subjcredit > 0 ? $eachsubject->subjcredit : ''}}</td>
                            </tr>
                            @php
                                $eachsubject->display = 1;
                                if($num_row == 0)
                                {
                                    break;
                                }else{
                                    $num_row -=1;
                                }
                            @endphp
                        @endforeach
                        {{-- @if($num_row > 0)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @php
                            $num_row -=1;
                        @endphp
                        @endif --}}
                        @php
                            if(collect($subjects)->where('display','1')->count() == count($subjects))
                            {
                                $record->display = 1;
                            }
                            if($num_row == 0)
                            {
                                break;
                            }
                        @endphp
                    @endforeach
                    @for($x = 0; $x < $num_row; $x++)
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                    <tr>
                        <td style="border-bottom: 1px solid black;">&nbsp;</td>
                        <td style="border-bottom: 1px solid black; text-align: center;">@if(collect($recordsleft)->where('display','0')->count() > 0)-more entries next page-@endif</td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td style="border-bottom: 1px solid black;"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="border-top: 1px solid black; line-height: 0.5px;">&nbsp;</td>
                    </tr>
                </table>
                @php
                    $recordsleft = collect($records)->where('display','0')->values();
                @endphp
                <table style="width: 100%; font-size: 13px;" class="remarks">
                    <tr>
                        <td style="font-weight: bold; width: 12%;">REMARKS:</td>
                        <td style="border-bottom: 1px solid black;">{{count($recordsleft) > 0 ? 'Please refer to page 3 for more entries…' : $details->remarks}}</td>
                    </tr>
                </table>
                @if(count($recordsleft)>0)
                    @php
                        $num_rows = 39;
                    @endphp
                    <table style="width: 100%; font-size: 11.5px; margin-top: 13px; page-break-before: always;">
                        <tr>
                            <td style="width: 5%; font-weight: bold;">Name:</td>
                            <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->lastname}}</td>
                            <td style="width: 1%; font-weight: bold;">,</td>
                            <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->firstname}}</td>
                            <td style="width: 14%; border-bottom: 1px solid black; font-weight: bold; text-align: center;">{{$studentinfo->middlename != null ? $studentinfo->middlename[0] : ''}}</td>
                            <td style="width: 14%;"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: center;"><small>(Last)</small></td>
                            <td></td>
                            <td style="text-align: center;"><small>(First)</small></td>
                            <td style="text-align: center;"><small>(Middle)</small></td>
                            <td></td>
                        </tr>
                    </table>
                    <br/>
                    <table style="width: 100%; table-layout: fixed; margin: 0px; font-size: 12px; border-bottom: 1px solid black;" class="table-grades">
                        <thead>
                            <tr style="font-size: 13px !important;">
                                <td style="width: 23%; border-top: 1px solid black; border-bottom: 1px solid black; border-left: 1px solid black; padding: 1.5px 1px 1.5px 1.5px !important; vertical-align: middle; text-align: center;">
                                    <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">COURSE & NUMBER</div>
                                </td>
                                <td style="border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                    <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">DESCRIPTIVE TITLE</div>
                                </td>
                                <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; padding: 1.5px 1px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                    <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">FINAL RATING</div>
                                </td>
                                <td style="width: 14%; border-top: 1px solid black; border-bottom: 1px solid black; border-right: 1px solid black; padding: 1.5px 1.5px 1.5px 1px !important; vertical-align: middle; text-align: center;">
                                    <div style="width: 100%; height: 22px; border: 1px solid black; vertical-align: middle; padding-top: 8px;">CREDITS</div>
                                </td>
                            </tr>
                        </thead>
                        <tr>
                            <td><em>cont'd</em></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>      
                        <tr>
                            <td><em>&nbsp;</em></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>      
                        @php
                            $num_row -=1;
                        @endphp
                        @foreach($recordsleft as $key=>$record)
                            @php
                                $yearandsem = ($record->semid == 1 ? ' First Semester' : ($record->semid == 2 ? ' Second Semester' : 'Summer').'SY: '.$record->sydesc.', '.(strtolower($schoolinfo->schoolname) == strtolower($record->schoolname) ? 'Cagayan de Oro City' : $record->schooladdress));
                                $num_row -=2;
                            @endphp
                        
                            <tr>
                                <th></th>
                                <th>{{$record->schoolname}}</th>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <th>{{$yearandsem}}</th>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $subjects = collect($record->subjdata)->where('display','0')->values();
                            @endphp
                            @foreach($subjects as $eachsubject)
                                <tr>
                                    <td style="padding-left: 35px;">{{$eachsubject->subjcode}}</td>
                                    <td style="padding-left: 10px;">{{lowercase_word($eachsubject->subjdesc)}}</td>
                                    <td style="text-align: center;">{{$eachsubject->subjgrade}}</td>
                                    <td style="text-align: center;">{{$eachsubject->subjcredit > 0 ? $eachsubject->subjcredit : ''}}</td>
                                </tr>
                                @php
                                    $eachsubject->display = 1;
                                    if($num_row == 0)
                                    {
                                        break;
                                    }else{
                                        $num_row -=1;
                                    }
                                @endphp
                            @endforeach
                            {{-- @if($num_row > 0)
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            @php
                                $num_row -=1;
                            @endphp
                            @endif --}}
                            @php
                                if(collect($subjects)->where('display','1')->count() == count($subjects))
                                {
                                    $record->display = 1;
                                }
                                if($num_row == 0)
                                {
                                    break;
                                }
                            @endphp
                        @endforeach
                        @for($x = 0; $x < $num_row; $x++)
                            <tr>
                                <td>&nbsp;</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endfor
                        <tr>
                            <td style="border-bottom: 1px solid black;">&nbsp;</td>
                            <td style="border-bottom: 1px solid black; text-align: center;">@if(collect($recordsleft)->where('display','0')->count() > 0)-more entries next page-@endif</td>
                            <td style="border-bottom: 1px solid black;"></td>
                            <td style="border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border-top: 1px solid black; line-height: 0.5px;">&nbsp;</td>
                        </tr>
                    </table>
                    @php
                        $recordsleft = collect($records)->where('display','0')->values();
                    @endphp
                    <table style="width: 100%; font-size: 13px;" class="remarks">
                        <tr>
                            <td style="font-weight: bold; width: 12%;">REMARKS:</td>
                            <td style="border-bottom: 1px solid black;">{{count($recordsleft) > 0 ? 'Please refer to page 4 for more entries…' : $details->remarks}}</td>
                        </tr>
                    </table>
                @endif
            @endif
        </main>
    </body>
</html>