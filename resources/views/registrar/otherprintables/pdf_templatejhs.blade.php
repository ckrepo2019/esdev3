<html>
    <head>
        <title>Certificate of Enrollment</title>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
        <style>
            
            @page{
                margin: 0.4in 1in;
            }
            *{                
                font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            }
        </style>
        @else
        <style>
            
            @page{
                margin: 0.5in 0.5in;
            }
        </style>
        @endif
        <style>
            table {
                border-collapse: collapse;
            }
            td, th{
                padding: 0px;
            }
    #watermark1 {
                position: absolute;
                /* bottom:   0px; */
                /* left:     20px; */
                /** The width and height may change 
                    according to the dimensions of your letterhead
                **/
                /* width:    100%; */
                height:   19cm;
                opacity: 0.2;
                /** Your watermark should be behind every content**/
                z-index:  -2000;
            }
        </style>
        
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
        <style>
            #watermark {
                        position: fixed;
        
                        /** 
                            Set a position in the page for your image
                            This should center it vertically
                        **/
                        bottom:   18cm;
                        left:     2.7cm;
                        opacity: 0.2;
        
                        /** Change image dimensions**/
                        /* width:    8cm;
                        height:   8cm; */
        
                        /** Your watermark should be behind every content**/
                        z-index:  -1000;
                    }
        </style>
        @endif
    </head>
    <body>
        @php
            // $pupil = 'pupil';
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
            // {
            $pupil = 'student';
    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci' && $studentinfo->acadprogid == 3)
    {
    $pupil = 'pupil';
    }
            // }
        @endphp
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                <tr>
                    <td rowspan="2" style="text-align: right; vertical-align: top; padding-top: 30px !important;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                    <td style="wudth: 60%; text-align: center; padding-top: 10px !important;"><img src="{{base_path()}}/public/assets/images/apmc/header_schoolname.jpg" alt="school" width="55%"></td>
                    <td rowspan="2" style="vertical-align: top; padding-top: 10px !important; text-align: right;"><img src="{{base_path()}}/public/assets/images/apmc/header_triplelogos.jpg" alt="school" width="180px;"></td>
                </tr>
                <tr>
                    <td style="font-size: 13px; text-align: center; vertical-align: top;">
                        Tel. # (062) 925-6875 / Email: <u style="color: blue;">apmc2k7@yahoo.com</u> or <u style="color: blue;">apmcshine@gmail.com</u>
                        <br/>
                        <div style="margin-top: 5px; font-weight: bold;">Website: apmc-essentiel.ckgroup.ph</div>                    
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 3px solid black;"></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 1px solid black; line-height: 5px;">&nbsp;</td>
                </tr>
            </table>
            <br/>
            <div style="width: 100%; padding: 0 1in;">
                <div style="width: 100%; text-align: center; font-size: 25px; font-family: Times, serif;">CERTIFICATE OF ENROLLMENT
                </div>
                <br/>
                <br/>
                <div style="padding: 0px; width: 100%;">
                    <p>To Whom It May Concern:</p>
                </div>
                <br/>
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 30%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that</td>
                        <td style="border-bottom: 1px solid black; text-align: center; text-transform: uppercase;">{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</td>
                    </tr>
                </table>
                <p style="margin: 0px; text-align: justify; line-height: 25px;">
                    is officially enrolled as <u>{{$studentinfo->levelname}} - {{$studentinfo->sectionname}}</u> student in {{$schoolinfo->schoolname}}@if(strtolower($schoolinfo->abbreviation) == 'apmc') (Cebuano Barracks Institute), {{ucfirst(strtolower($schoolinfo->division))}}, Zamboanga del Sur @endif during the School Year <u>{{$syinfo->sydesc}}</u>.
                </p>
                <br/>
                <br/>
                <p style="margin: 0px; text-align: justify; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certifies further that the undersigned knows him/her to be Good Moral Character, and as far as knowledgeable information is concerned, he/she has never been charged nor convicted of any crime involving moral turpitude.
                </p>
                <br/>
                <br/>
                <p style="margin: 0px; text-align: justify; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification issued to <u>{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u> for All legal purposes that may serve him/her best.
                </p>
                <br/>
                <br/>
                <p style="margin: 0px; text-align: justify; line-height: 25px;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
                </p>
                <br>
                <br>
                <br>
                <div style="width: 100%; text-align: center; padding-left: 65%;">
                    <sub style="width: 35%; font-size: 18px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
                </div>
                <br/>
                <div style="width: 100%; text-align: center; padding-left: 65%;">
                    <sup style="width: 35%; text-align: center; padding: 0px;">School Registrar</sup>
                </div>
                <br/>
                <br/>
                <br/>
                <br/>
                <div style="margin: 0px; width: 100%; font-size: 12px;">
                    NOT VALID WITHOUT SCHOOL SEAL.
                </div>
            </div>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            <table style="width: 100%;" >
                <tr>
                    <td style="width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="73px"></td>
                    <td style="vertical-align: top;">
                        <div style="font-size: 15px; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="font-size: 11px; font-weight: bold;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
                        <div style="font-size: 11px; font-weight: bold;">Telephone Number : 828-1499 | Email add : <u style="color: rgb(5, 144, 199);">saitvalencia1960@gmail.com</u></div>
                        <div style="font-size: 11px; font-weight: bold;">Website : <u style="color: rgb(5, 144, 199);">https://sait.edu.ph</u></div>
                    </td>
                    <td style="width: 20%;">
                        <img src="{{base_path()}}/public/assets/images/sait/logo_certified.png" alt="school" width="120px;">
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style=" border-bottom: 4px solid  rgb(5, 144, 199); height: 5px;"></td>
                </tr>
                <tr>
                    <td colspan="3" style=" border-bottom: 2px solid  rgb(5, 144, 199); height: 4px;"></td>
                </tr>
            </table>
            <br/>
            {{-- @if($studentinfo->acadprogid == 4) --}}
                <div style="width: 100%; text-align: center;">
                    <img src="{{base_path()}}/public/assets/images/sait/office-of-the-hs-registrar.png" alt="school" width="50%;">
                </div>
            {{-- @endif --}}
            <br/>
            <br/>
            <div style="width: 100%; text-align: center;">
                CERTIFICATION
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-weight: bold; font-size: 18px;;">
                To Whom It May Concern:
            </div>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}}</strong> is a bonafide {{$studentinfo->levelname}} student of this institution for the school year {{$syinfo->sydesc}}.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the verbal request of the above-named student {{$purpose}}.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{$schoolinfo->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; text-align: center; padding-left: 50%;">
                <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;"><u>{{$schoolregistrar}}</u></sub>
            </div>
            <div style="width: 100%; text-align: center; padding-left: 50%; margin: 5px 0px;">
                <sup style="width: 40%; text-align: center; padding: 0px;">
                    {{$signatorylabel}}
                </sup>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-size: 13px;">
                NOT valid WITHOUT<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;school SEAL
            </div>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                <tr>
                    <td style="text-align: right; vertical-align: middle; width: 10%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="115px"></td>
                    <td style="text-align: right; vertical-align: top; padding: 0px;">
                        <div style="width: 100%; font-weight: bold; font-size: 28px !important; margin: 0px; color: #002060;"><img src="{{base_path()}}/public/assets/images/sma/coe_header_schoolname.jpg" alt="school" width="580px"></div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">(Formerly Stella Matutina Academy)</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">West Kibawe, Kibawe, Bukidnon, Philippines 8720</div>
                        <div style="width: 100%; font-size: 13px;"><span style="color: red;">Email add:</span> <span style="color: #3a63b6;">academymatutinastella@gmail.com,</span> <span style="color: red;">Cellphone No.</span> <span style="color: #3a63b6;">09364281685</span>  </div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Gov. Rec. No. 128, s 1969, DepEd Sch ID No. 404989 FAPE ID No.1001423</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Member: Catholic Education Association of the Philippines</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Associate Member: Bukidnon Association of Catholic Schools</div>
                    </td>
                    {{-- <td style="vertical-align: middle; text-align: left; width: 15%;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td> --}}
                </tr>
            </table>
            <div style="width: 100%; padding: 0 0.5in; text-align: justify; font-family: Arial, Helvetica, sans-serif; border-top: 1px solid black;">
                <br/>
                <div style="width: 100%; text-align: center; font-size: 30px; font-family: Times, serif; font-weight: bold;">C E R T I F I C A T I O N</div>
                <br/>
                <br/>
                <div style="width: 100%;">
                    <p>TO WHOM IT MAY CONCERN:</p>
                </div>
                <br/>
                <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>&nbsp;&nbsp;{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. </strong> is currently enrolled as <strong>{{$studentinfo->levelname}}</strong> student with <strong>LRN {{$studentinfo->lrn}}</strong> in this institution Stella Matutina Academy of Bukidnon, Inc. for this school year &nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
                <br/>
                <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve him/her best.</p>
                <br/>
                <p style="text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this &nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp; day of &nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;, {{date('Y', strtotime($givendate))}} at Stella Matutina Academy of Bukidnon, Inc., Kibawe, Bukidnon.
                </p>
                <br>
                <br>
                <br>
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
                </div>
                <br/>
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sup style="width: 40%; text-align: center; padding: 0px;">Registrar's Incharge</sup>
                </div>
                <br/>
                <br/>
                <br/>
                <br/>
                <div style="margin: 0px; width: 30%; text-align: center; font-size: 12px;">
                    NOT VALID WITHOUT<br/>SCHOOL SEAL.
                </div>
            </div>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lhs')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px; border-bottom: 3px solid #0070c0">
                <tr>
                    <td style="text-align: right; vertical-align: middle; width: 27%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px"><br/><br/></td>
                    <td style="text-align: left; vertical-align: top; padding: 5px 0px 0px 10px;">
                        <div style="width: 100%; font-weight: bold; font-size: 28px !important; margin: 0px; color: #002060;"><img src="{{base_path()}}/public/assets/images/lhs/coe_header.png" alt="school" width="400px"></div>
                    </td>
                    {{-- <td style="vertical-align: middle; text-align: left; width: 20%;"></td> --}}
                </tr>
            </table>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-weight: bold; font-size: 28px !important; margin: 0px; color: #002060; text-align: center;"><img src="{{base_path()}}/public/assets/images/lhs/coe_certification.png" alt="school" width="38%"></div>
            <br/>
            <br/>
            <div style="width: 100%; margin: 0px 50px; font-size: 18px; font-stretch: condensed">
                <p style="font-stretch: condensed;;
                ">TO WHOM IT MAY CONCERN:</p>
            </div>
            <div style="width: 100%; margin: 0px 20px 0px 50px; font-size: 18px; text-align: justify; line-height: 40px;">
                <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;THIS IS TO CERTIFY that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->suffix}}</strong> with <strong>Learner’s   Reference Number (LRN) {{$studentinfo->lrn}}</strong> is currently enrolled as a <strong>{{$studentinfo->levelname}}</strong> student of this institution in the school year {{$syinfo->sydesc}}.</p>
                <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is hereby granted upon request of the above-named person for whatever legal purpose this may serve hihe/sher best.</p>
                <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Done this {{date('jS', strtotime($givendate))}} day of {{date('M', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at Loyola High School, Don Carlos, Bukidnon.</p>
            </div>
            <br>
            <br/>
            <div style="width: 100%; text-align: center; padding-left: 50%; font-size: 15px;">
                <sub style="width: 40%; ftext-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
            </div>
            <br/>
            <div style="width: 100%; text-align: center; padding-left: 50%; font-size: 15px;">
                <sup style="width: 40%; text-align: center; padding: 0px;">Principal</sup>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="margin: 0px; width: 100%; font-size: 12px; margin: 0px 20px 0px 50px;">
                NOT VALID WITHOUT<br/>SCHOOL SEAL.
            </div>
            <div style="margin: 0px; width: 100%; font-size: 12px; margin: 0px 20px 0px 50px;">
                OR WITH ALTERATION OR ERASURE
            </div>
            
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'faa')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                <tr>
                    <td style="text-align: right; vertical-align: middle; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        <div style="width: 100%; font-weight: bold; font-size: 22px !important;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 12px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                        <div style="width: 100%; font-size: 12px !important;">Tel no. 227-1096/224-1835 (Fax no.)</div>
                    </td>
                    <td style="vertical-align: middle; text-align: left; width: 15%;">
                        {{-- <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"> --}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 2px solid black;">&nbsp;</td>
                </tr>
            </table>
            <br/>
            <div style="width: 100%; text-align: center; font-size: 25px; font-family: Arial, Helvetica, sans-serif;">CERTIFICATE OF ENROLLMENT
            </div>
            <br/>
            <br/>
            <div style="width: 100%; padding: 0 0.5in; text-align: justify; font-family: Arial, Helvetica, sans-serif;">
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. {{$studentinfo->lastname}}</u>, is officially enrolled as {{$studentinfo->levelname}} student in {{$schoolinfo->schoolname}}, at {{$schoolinfo->address}}, school year {{$syinfo->sydesc}} with I.D number {{$studentinfo->sid}}.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above-mentioned student for whatever legal purposes it may serve him/her best.</p>
                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Issued this &nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp; {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
                </p>
                <br>
                <br>
                <br>
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
                </div>
                <br/>
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sup style="width: 40%; text-align: center; padding: 0px;">Registrar</sup>
                </div>
                <br/>
                <br/>
                <br/>
                <br/>
                <div style="margin: 0px; width: 100%; font-size: 12px;">
                    NOT VALID WITHOUT<br/>SCHOOL SEAL.
                </div>
            </div>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs')
            <table style="width: 100%; border-collapse: collapse; font-family:Georgia, serif !important; margin: 0px 50px !important;">
                <tr>
                    <td style="text-align: center; vertical-align: middle; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="75px"></td>
                    <td style="wudth: 70%; text-align: center; vertical-align: bottom;">
                        <div style="width: 100%; font-weight: bold; font-size: 22px !important;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 21px !important;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
                    </td>
                    <td style="vertical-align: middle; text-align: left; width: 15%;">
                        {{-- <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"> --}}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 2px solid black;">&nbsp;</td>
                </tr>
                <tr>
                    <td></td>
                    <td style="font-size: 50px; text-align: center; font-weight: bold;"><br/><u>CERTIFICATION</u></td>
                    <td></td>
                </tr>
            </table>
            <br/>
            <br/>
            <div style="font-family:Georgia, serif !important; margin: 0px 50px !important;">
                <p>{{date('F d, Y', strtotime($givendate))}}</p>
                <br/>
                <p>TO WHOM IT MAY CONCERN:</p>
                <p style="text-align: justify;">This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif.</strong> is a {{strtolower($studentinfo->levelname)}} @if($studentinfo->levelid < 13) learner @else completer @endif of {{DB::table('schoolinfo')->first()->schoolname}} for school year {{$syinfo->sydesc}}.</p>
                @if($purpose != null)
                <p style="text-align: justify;">{{$purpose}}</p>
                @endif
                <p style="text-align: justify;">Issued this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}} {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.</p>

                <br/>
                <br/>
                <p>SIGNED:</p>
                <br/>
                <br/>
                <br/>
                <table style="width: 100%;">
                    <tr>
                        <th style="width: 40%; border-bottom: 1px solid black;">{{$schoolregistrar}}</th>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">JHS Principal</td>
                        <td></td>
                    </tr>
                </table>
                <br/>
                <br/>
                <br/>
                <br/>
                <p>NOT VALID WITHOUT<br/>SCHOOL SEAL.</p>
            </div>
        @else
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                <div id="watermark">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" width="500px" />
                </div>
                <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px; text-align: center;">
                <tr>
                    <td><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                </tr>
                <tr>
                    <td><img src="{{base_path()}}/public/assets/images/mci/coe_header.jpg" alt="school" width="350px;"></td>
                </tr>
                <tr>
                    <td>
                        Climaco Street, Poblacion, Ipil, Zamboanga Sibugay 7001<br/>
                        <sup style="font-size: 10px;">WEBSITE: www.mariancollege.edu.ph</sup><br/>
                        <sup style="font-size: 10px;">EMAIL ADDRESS: administrator@mariancollege.edu.ph</sup>
                    </td>
                </tr>
                </table>
                <br/>
                <br/>
                <br/>
            @else
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
            <div id="watermark1" style="padding-top: 170px; text-align: center;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" height="95%" width="95%" style="padding-left: 20px;" />
            </div>
            <table style="width: 100%; border-collapse: collapse;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'ndsc') font-family: Arial, Helvetica, sans-serif;@endif margin: 0px 10px;">
                <tr>
                    <td rowspan="2" style="text-align: right; vertical-align: top; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        <div style="width: 100%; font-weight: bold; font-size: 20px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Owned by the Archdiocese of Cotabato</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Administered by the Diocesan Clergy of Cotabato (DCC)</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Lebak, Sultan Kudarat</div>
                    </td>
                    <td rowspan="2" style="vertical-align: top; text-align: left; width: 15%;">
                        <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="100px">
                    </td>
                </tr>
                <tr>
                    <td style="font-size: 13px; text-align: center; vertical-align: top; padding-top: 10px;">                    
                        <img src="{{base_path()}}/public/assets/images/ndsc/qoute.jpg" alt="school" width="300px">
                        <div style="width: 100%; font-size: 17.7px; font-weight: bold;">(B.E.S.T)</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" >&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top: 10px solid green;">&nbsp;</td>
                </tr>
            </table>
            @else
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                <tr>
                    <td rowspan="2" style="text-align: right; vertical-align: middle; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%; font-size: 20px !important;">Department of Education</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->regiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->divisiontext}}</div>
                        {{-- <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div> --}}
                    </td>
                    <td rowspan="2" style="vertical-align: middle; text-align: left; width: 15%;"><img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="80px"></td>
                </tr>
                <tr>
                    <td style="font-size: 13px; text-align: center; vertical-align: top;">
                        <div style="width: 100%; font-weight: bold; font-size: 16px !important;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%;">{{DB::table('schoolinfo')->first()->address}}</div>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" >&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" >&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-bottom: 2px solid black;">&nbsp;</td>
                </tr>
            </table>
            @endif
            @endif
            <div style="width: 100%; padding: 0 0.5in; text-align: justify;">
                <div style="width: 100%; text-align: center; font-size: 30px; font-family: Times, serif; font-weight: bold;">C E R T I F I C A T I O N</div>
                <br/>
                <br/>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->firstname}} {{$studentinfo->middlename == null ? '' : $studentinfo->middlename[0].'.'}} {{$studentinfo->lastname}}</u>&nbsp;&nbsp; is a bonafide &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->levelname}}</u>&nbsp;&nbsp; {{$pupil}} of  &nbsp;&nbsp;{{$schoolinfo->schoolname}}&nbsp;&nbsp; with LRN {{$studentinfo->lrn}} this school year&nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
                <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve  @if(strtolower($studentinfo->gender) == 'male')him @else her @endif best.</p>
                <p>
                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this &nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp; day of &nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp; {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
                </p>
                <br>
                <br>
                <br>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'nsdphs')
                    <table style="width: 100%;">
                        <tr>
                            <th style="border-bottom: 1px solid black;">{{$signatoryinfo[0]->name ?? null}}</th>
                            <th style="width: 30%;">&nbsp;</th>
                            <th style="border-bottom: 1px solid black;">{{$signatoryinfo[1]->name ?? null}}</th>
                        </tr>
                        <tr>
                            <td style="text-align: center;">{{$signatoryinfo[0]->description ?? null}}</td>
                            <th></th>
                            <td style="text-align: center;">{{$signatoryinfo[1]->description ?? null}}</td>
                        </tr>
                    </table>
                @else
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
                </div>
                <br/>
                <div style="width: 100%; text-align: center; padding-left: 50%;">
                    <sup style="width: 40%; text-align: center; padding: 0px;">
                        {{$signatorylabel}}
                    </sup>
                </div>
                @endif
                <br/>
                <br/>
                <br/>
                <br/>
                <div style="margin: 0px; width: 100%; font-size: 12px;">
                    Not valid without<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Seal
                </div>
            </div>
        @endif
    </body>
</html>