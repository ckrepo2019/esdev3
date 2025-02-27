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
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
                margin: 10px 15px;
        @else
                margin: 0.5in 0.5in;
        @endif
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
        @endif
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
            // }
        @endphp
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc')
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
            <tr>
                <td rowspan="2" style="text-align: right; vertical-align: top; padding-top: 30px !important;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                <td style="wudth: 60%; text-align: center; padding-top: 10px !important;"><img src="{{base_path()}}/public/assets/images/apmc/header_schoolname.jpg" alt="school" width="450px"></td>
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
        <br/>
        <div style="width: 100%; text-align: center; font-size: 25px; font-family: Times, serif;">CERTIFICATE OF ENROLLMENT
        </div>
        <br/>
        <br/>
        <div style="padding: 0px 60px; width: 100%;">
            <p>To Whom It May Concern:</p>
        </div>
        <br/>
        <div style="margin-left: 60px; width: 30%; float: left; line-height: 30px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that
        </div>
        <div style="margin-right: 60px; width: 57%; float: right; border-bottom: 1px solid black; text-align: center; line-height: 27px;">
            {{strtoupper($studentinfo->firstname)}} {{strtoupper($studentinfo->middlename)}} {{strtoupper($studentinfo->lastname)}}
        </div>
        <p style="margin: 0px 60px; text-align: justify; line-height: 25px;">
            is officially enrolled as <u>{{$studentinfo->levelname}} - {{$studentinfo->sectionname}} - {{$studentinfo->strandcode}}</u> Course in the in {{$schoolinfo->schoolname}}@if(strtolower($schoolinfo->abbreviation) == 'apmc') (Cebuano Barracks Institute), {{ucfirst(strtolower($schoolinfo->division))}}, Zamboanga del Sur @endif during this<u>&nbsp;&nbsp;{{$semesterinfo->semester}}&nbsp;&nbsp;</u>, School Year <u>&nbsp;&nbsp;{{$syinfo->sydesc}}&nbsp;&nbsp;</u></u>.
        </p>
        <br/>
        <br/>
        <p style="margin: 0px 60px; text-align: justify; line-height: 25px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certifies further that the undersigned knows him/her to be Good Moral Character, and as far as knowledgeable information is concerned, he/she has never been charged nor convicted of any crime involving moral turpitude.
        </p>
        <br/>
        <br/>
        <p style="margin: 0px 60px; text-align: justify; line-height: 25px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification issued to <u>{{strtoupper($studentinfo->firstname)}} {{strtoupper($studentinfo->middlename)}} {{strtoupper($studentinfo->lastname)}}</u> for All legal purposes that may serve him/her best.
        </p>
        <br/>
        <br/>
        <p style="margin: 0px 60px; text-align: justify; line-height: 25px;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this <u>&nbsp;&nbsp;&nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp;</u> day of <u>&nbsp;&nbsp;&nbsp;&nbsp;{{date('F', strtotime($givendate))}}&nbsp;&nbsp;&nbsp;&nbsp;</u>, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->address))}}.
        </p>
        <br>
        <br>
        <br>
        <sub style="width: 35%; float: right; font-size: 18px; font-weight: bold; text-align: center; padding:  0px 50px 0px 0px;">{{$schoolregistrar}}</sub>
        <br/>
        <br/>
        <sup style="width: 35%; float: right; text-align: center; padding:  0px 50px 0px 0px;">School Registrar</sup>
        <br/>
        <br/>
        <br/>
        <br/>
        <div style="margin: 0px 60px; width: 100%;">
            NOT VALID WITHOUT SCHOOL SEAL.
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
                    <td colspan="3" style=" border-bottom: 2px solid  rgb(5, 144, 199);"></td>
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
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}}</strong> is a bonafide {{$studentinfo->levelname}} student of the Senior High School under the <strong>{{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) Strand</strong> of this institution for the school year {{$syinfo->sydesc}}.
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
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>&nbsp;&nbsp;{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename[0]}}. &nbsp;&nbsp;</strong> is currently enrolled as <strong>{{$studentinfo->levelname}} - {{$studentinfo->strandcode}}&nbsp;&nbsp;</strong> student with <strong>LRN {{$studentinfo->lrn}}</strong> in this institution Stella Matutina Academy of Bukidnon, Inc. for this school year &nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
            <br/>
            <p style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve him/her best.</p>
            <br/>
            <p style="text-align: justify;">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this &nbsp;&nbsp;{{date('jS', strtotime($givendate))}}&nbsp;&nbsp; day of &nbsp;&nbsp;{{date('M', strtotime($givendate))}}&nbsp;&nbsp;, {{date('Y', strtotime($givendate))}} at Stella Matutina Academy of Bukidnon, Inc., Kibawe, Bukidnon.
            </p>
            <br>
            <br>
            <br>
            <div style="width: 100%; text-align: center; padding-left: 45%;">
                <sub style="width: 35%; font-size: 18px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub>
            </div>
            <br/>
            <div style="width: 100%; text-align: center; padding-left: 45%;">
                <sup style="width: 35%; text-align: center; padding: 0px;">Registrar's Incharge</sup>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="margin: 0px; width: 30%; text-align: center;">
                NOT VALID WITHOUT<br/>SCHOOL SEAL
            </div>
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
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                <tr>
                    <td colspan="3" style="border-top: 10px solid green;">&nbsp;</td>
                </tr>
                @else
                <tr>
                    <td colspan="3" style="border-bottom: 10px solid green;">&nbsp;</td>
                </tr>
                @endif
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
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div>
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
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}}</u>&nbsp;&nbsp; is a bonafide &nbsp;&nbsp;<u style="font-weight: bold;">{{$studentinfo->levelname}}</u>&nbsp;&nbsp; {{$pupil}} of  &nbsp;&nbsp;{{$schoolinfo->schoolname}}&nbsp;&nbsp; with LRN {{$studentinfo->lrn}} this school year&nbsp;&nbsp;{{$syinfo->sydesc}}.</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above mention for whatever legal purpose it may serve  @if(strtolower($studentinfo->gender) == 'male')him @else her @endif best.</p>
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