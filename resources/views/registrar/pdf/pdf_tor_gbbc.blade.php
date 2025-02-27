<html>
    <head>
        <style>
            body{
                margin: 20px 20px 500px 20px;
            }
            /* @page{
                margin: 20px 20px 500px 20px;
            } */
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
            /* header {
                position: fixed;
                top: -60px;
                left: 0px;
                right: 0px;
                height: 50px;
                color: white;
                text-align: center;
                line-height: 35px;
            }

            footer {
                position: fixed; 
                bottom: -60px; 
                left: 0px; 
                right: 0px;
                height: 50px; 

                color: white;
                text-align: center;
                line-height: 35px;
            } */
        </style>
    </head>
    <body>
        @php
            $url = asset($studentinfo->picurl);
            $parts = parse_url($url);
            $fullurl = $parts['scheme'] . '://' . $parts['host'].'/';

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
        
        <table style="width: 100%;">
            <thead>
                {{-- <tr nobr="true">
                    <th style="width: 20%; text-align: right;">
                        <img src="{{base_path()}}/public/{{$schoolinfo->picurl}}" alt="school" width="80px"/>
                    </th>
                    <th style="width: 80%;" colspan="2">
                        <img src="{{base_path()}}/public/assets/images/gbbc/tor-header.png" alt="school" width="500px"/>
                    </th>
                </tr> --}}
                <tr nobr="true">
                    <th rowspan="2" style="width: 20%; "></th>
                    <th style="width: 60%; height: 60px; text-align: center; font-weight: bolder; color: #2d7691; font-size: 17px; line-height: 40px;" class="font-one">Office of the Registrar</th>
                    <th style="width: 20%; height: 120px; vertical-align: top; padding: 0px;" rowspan="2">
                        {{-- <div style="border: 1px solid black; height: 120px;">&nbsp;</div> --}}
                        {{-- <img src="{{$fullurl}}{{$studentinfo->picurl}}" alt="school" width="500px"/> --}}
                    </th>
                </tr>
                <tr nobr="true">
                    <th style="width: 60%;text-align: center; font-weight: bolder; color: #2d6c91; font-size: 23px; height: 60px;" class="font-one">Offical Transcript of Record</th>
                </tr>
            </thead>
            <tr>
                <td style="vertical-align: top; padding: 0px; font-size: 11px; width: 100%;"  colspan="3"><table style="width: 100%; margin: 0px;">
                        <tr>
                            <td colspan="4" style="width: 100%;">Revised Transcript</td>
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
                            <td style="width: 15%;">Place of Birth:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Parent or Guardian:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->parentguardian}}</td>
                            <td style="width: 15%;">Address:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3">{{$details->address}}</td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Elementary Course:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->elemcourse}}</td>
                            <td style="width: 15%;">Date Complete:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3">{{$details->elemdatecomp}}</td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Secondary Course:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->secondcourse}}</td>
                            <td style="width: 15%;">Date Complete:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3">{{$details->seconddatecomp}}</td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Admission Date:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->admissiondate}}</td>
                            <td style="width: 15%;">Degree:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;" colspan="3">{{$details->degree}}</td>
                        </tr>
                        <tr>
                            <td style="width: 18%;">Basis of Admission:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->basisofadmission}}</td>
                            <td style="width: 15%;">Major:</td>
                            <td style="width: 14%;border-bottom: 1px solid black;">{{$details->major}}</td>
                            <td style="width: 7%;">Minor:</td>
                            <td style="width: 14%;border-bottom: 1px solid black;"></td>
                        </tr>
                        <tr>
                            <td style="width: 18%;border-bottom: 1px solid black;">Special Order:</td>
                            <td style="width: 32%;border-bottom: 1px solid black;" colspan="3">{{$details->specialorder}}</td>
                            <td style="width: 15%;border-bottom: 1px solid black;">Graduation Date:</td>
                            <td style="width: 35%;border-bottom: 1px solid black;">{{$details->graduationdate}} &nbsp;&nbsp;&nbsp;NSTP Serial No.:</td>
                        </tr>
                    </table>
                    <div style="line-height: 5px;"></div>
                    <table style="width: 100%; margin: 0px; max-height: 500px;" class="font-two">
                        <thead>
                            <tr style="font-weight: bold; font-size: 14px; font-weight: bold;">
                                <th style="width: 30%; border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">COURSE NUMBER</th>
                                <th style="width: 45%; border-bottom: 1px solid black;border-top: 1px solid black;" colspan="2">DESCRIPTIVE TITLE OF THE COURSE</th>
                                <th style="width: 12%; text-align: center; border-bottom: 1px solid black;border-top: 1px solid black;">GRADE</th>
                                <th style="width: 13%; border-bottom: 1px solid black;border-top: 1px solid black;">CREDITS</th>
                            </tr>
                        </thead>
                        @if(count($records)>0)
                            @foreach($records as $record)
                                <tr>
                                    <td colspan="6" style="text-align: center; width: 100%;"><u style="text-transform: uppercase; font-weight: bold; font-size: 13px;">{{$record->schoolname}}</u></td>
                                </tr>
                                <tr style="font-size: 12px;">
                                    <td style="width: 14%; font-weight: bold;"><u>@if($record->semid == 1)First Semester @elseif($record->semid == 2)Second Semester @endif</u></td>
                                    <td style="width: 5%;"></td>
                                    <td style="width: 11%; text-align: right;"><u>{{$record->sydesc}}</u>&nbsp;&nbsp;</td>
                                    <td style="width: 45%;"></td>
                                    <td style="width: 12%;"></td>
                                    <td style="width: 13%;"></td>
                                </tr>
                                @if(count($record->subjdata)>0)
                                    @foreach($record->subjdata as $subj)
                                        <tr style="font-size: 11px;">
                                            <td style="width: 14%;">{{$subj->subjcode}}</td>
                                            <td style="width: 5%;">{{$subj->subjunit}}</td>
                                            <td style="width: 11%;">&nbsp;</td>
                                            <td style="width: 45%;">{{$subj->subjdesc}}</td>
                                            <td style="width: 12%; text-align: right; font-weight: bold;">{{$subj->subjgrade}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                            <td style="width: 13%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$subj->subjcredit}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>