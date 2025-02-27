<html>
    <header>        
        @if( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak'  || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs')
            <style>
                @page{
                    margin: 0.5in;
                    size: 8.5in 11in;
                }
                td, th{
                    padding: 0px;
                }
                #container-content {
                    margin: 0px 0.5in;
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
        @elseif( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
        <style>
            @page{
                margin: 0.5in 0.7in;
                size: 8.5in 11in;
            }
            td, th{
                padding: 0px;
            }
            #container-content {
                margin: 0px 0.5in;
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
        @elseif( strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
        <style>
            @page{
                margin: 0.4in 1in;
            }
            *{                
                font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif; 
            }
            td, th{
                padding: 0px;
            }
            #container-content {
                margin: 0px 0.5in;
            }
#watermark1 {
            position: absolute;
            bottom:   100px;
            /* left:     20px; */
            /** The width and height may change 
                according to the dimensions of your letterhead
            **/
            /* width:    100%; */
            height:   17cm;
            opacity: 0.1;
            /** Your watermark should be behind every content**/
            z-index:  -2000;
        }
        </style>
    @else
            <style>
                @page{
                    margin: 0.5in 1in 20px 1in;
                    size: 8.5in 11in;
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
    </header>
    <body>
        @php
            // $pupil = 'pupil';
            // if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sihs')
            // {
            $pupil = 'student';
            // }
        @endphp
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
            <div id="watermark1" style="padding-top: 170px; text-align: center;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" height="95%" width="95%" style="padding-left: 20px;" />
            </div>
            <table style="width: 100%; border-collapse: collapse; margin: 0px 10px;">
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
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="border-top: 10px solid green;">&nbsp;</td>
                </tr>
            </table>
            <div style="width: 100%; text-align: justify;">
                <div style="width: 100%; text-align: center; font-size: 30px; font-family: Times, serif; font-weight: bold;"><img src="{{base_path()}}/public/assets/images/ndsc/headertext.png" alt="school" width="300px"></div>
                <br/>
                <br/>
                <br/>
                <p style="font-size: 17.7px !important;">TO WHOM IT MAY CONCERN:</p>
                <div>&nbsp;</div>
                @if($template == 'jhs' || $template == 'shs')
                    <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a @if($template == 'jhs'){{$studentinfo->levelname}}  @else {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) @endif student with Learner's Reference Number <strong>{{$studentinfo->lrn}}</strong> of {{DB::table('schoolinfo')->first()->schoolname}}, @if($template == 'jhs') Junior High School Department @else Senior High School Department @endif,  and School Year {{$syinfo->sydesc}}.</p>
                @else
                    <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a student of {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) at {{DB::table('schoolinfo')->first()->schoolname}}, for {{$semesterinfo->semester}}, School Year {{$syinfo->sydesc}}.</p>
                @endif
                    <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that @if(strtolower($studentinfo->gender) == 'male')he @else she @endif has a <strong>GOOD MORAL CHARACTER</strong> standing in our academic community.</p>
                    <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of <strong>@if(strtolower($studentinfo->gender) == 'male')Mr. @else Ms. @endif {{$studentinfo->lastname}}</strong> for whatever legal purpose(s) it may serve @if(strtolower($studentinfo->gender) == 'male')him @else her @endif best.</p>
                    <p style="font-size: 17.7px !important; text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{ucwords(strtolower($schoolinfo->schoolname))}}, {{ucwords(strtolower($schoolinfo->address))}}, Philippines.</p>
            </div>
            <br/>
            <br/>
            <br/>
            @if(count($signatories) == 0)
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; font-weight: bold;"></td>
                        <td style="width: 50%; font-weight: bold;"></td>
                    </tr>
                    <tr>
                        <td>Principal</td>
                        <td>Guidance Coordinator</td>
                    </tr>
                </table>
            @else            
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; font-weight: bold; text-align: center;">{{$signatories[0]->name}}</td>
                        <td style="width: 50%; font-weight: bold; text-align: center;">{{$signatories[1]->name}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">{{$signatories[0]->description}}</td>
                        <td style="text-align: center;">{{$signatories[1]->description}}</td>
                    </tr>
                </table>
            @endif
            <br/>
            <br/>
            
            <p>Not Valid Without<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;School Seal</p>
        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait')
            <div id="watermark1" style="padding-top: 170px; text-align: center;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" height="95%" width="95%" style="padding-left: 20px;" />
            </div>
            
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
            <br/>
            <br/>
            {{-- @if($studentinfo->acadprogid == 4) --}}
                <div style="width: 100%; text-align: center; color: rgb(5, 144, 199);">
                    CERTIFICATE OF GOOD MORAL CHARACTER
                </div>
            {{-- @endif --}}
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-weight: bold;">
                TO WHOM IT MAY CONCERN:
            </div>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that based on the records of this office, <strong>{{$studentinfo->firstname}} {{$studentinfo->middlename}} {{$studentinfo->lastname}} was</strong> a {{$studentinfo->levelname}} - {{$studentinfo->sectionname}}  @if($template == 'shs')Senior High School @elseif($template == 'jhs')Junior High School @else Grade School @endif Student of this institution for the school year {{$syinfo->sydesc}}.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;That as our learner within the period of @if(strtolower($studentinfo->gender) == 'male')his @else her @endif stay, @if(strtolower($studentinfo->gender) == 'male')he @else she @endif had never infracted nor violated any of the standing policies, rules and regulations of the school.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of the above named student for whatever legal purpose this may serve @if(strtolower($studentinfo->gender) == 'male')him @else her @endif best.
            </div>
            <br/>
            <div style="width: 100%; font-size: 14px; text-align: justify;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at {{$schoolinfo->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            
            @if(count($signatories) == 0)
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; font-weight: bold;"></td>
                        <td style="width: 50%; font-weight: bold;"></td>
                    </tr>
                    <tr>
                        <td>Principal</td>
                        <td>Guidance Coordinator</td>
                    </tr>
                </table>
            @else            
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; font-weight: bold; text-align: center;">{{$signatories[0]->name ?? ''}}</td>
                        <td style="width: 50%; font-weight: bold; text-align: center;">{{$signatories[1]->name ?? ''}}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">{{$signatories[0]->description ?? ''}}</td>
                        <td style="text-align: center;">{{$signatories[1]->description ?? ''}}</td>
                    </tr>
                </table>
            @endif
            {{-- <div style="width: 100%; text-align: center; padding-left: 50%;">
                <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;"><u>{{$schoolregistrar}}</u></sub>
            </div>
            <div style="width: 100%; text-align: center; padding-left: 50%; margin: 5px 0px;">
                <sup style="width: 40%; text-align: center; padding: 0px;">
                    {{$signatorylabel}}
                </sup>
            </div> --}}
            <br/>
            <br/>
            <br/>
            <br/>
            <div style="width: 100%; font-size: 13px;">
                NOT valid WITHOUT<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;school SEAL
            </div>
        @else
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px; border-bottom: 1px solid black;">
                <tr>
                    <td style="text-align: right; vertical-align: middle; width: 10%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="115px"></td>
                    <td style="text-align: right; vertical-align: top; padding: 0px; width: 90%;">
                        <div style="width: 100%; font-weight: bold; font-size: 28px !important; margin: 0px; color: #002060;">
                            {{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}
                        </div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">(Formerly Stella Matutina Academy)</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">West Kibawe, Kibawe, Bukidnon, Philippines 8720</div>
                        <div style="width: 100%; font-size: 13px;"><span style="color: red;">Email add:</span> <span style="color: #3a63b6;">academymatutinastella@gmail.com,</span> <span style="color: red;">Cellphone No.</span> <span style="color: #3a63b6;">09364281685</span>  </div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Gov. Rec. No. 128, s 1969, DepEd Sch ID No. 404989 FAPE ID No.1001423</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Member: Catholic Education Association of the Philippines</div>
                        <div style="width: 100%; font-size: 13px; color: #3a63b6;">Associate Member: Bukidnon Association of Catholic Schools</div>
                    </td>
                </tr>
            </table>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hc babak')
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px; border-bottom: 1px solid black;">
                <tr>
                    <td style="text-align: left; vertical-align: top; width: 23%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px"></td>
                    <td style="wudth: 70%; text-align: left; ">
                        <div style="width: 100%; font-size: 20px !important; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma')
                        <div style="width: 100%; font-size: 20px !important; font-weight: bold;">@if($template == 'jhs') HIGH SCHOOL @else @endif</div>
                        @endif
                        <div style="width: 100%; font-size: 15px !important; font-weight: bold;"><em>{{DB::table('schoolinfo')->first()->address}}</em></div>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        <div style="width: 100%; font-size: 15px !important;">064-572-6321; 09518263138; sbcmlanghs@gmail.com</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle; text-align: left; width: 7%;"></td>
                </tr>
            </table>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'xkhs')
                <table style="width: 100%; border-collapse: collapse; font-family:Georgia, serif !important; margin: 0px 50px !important;">
                    <tr>
                        <td style="text-align: center; vertical-align: middle; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="75px"></td>
                        <td style="wudth: 70%; text-align: center; vertical-align: bottom;">
                            <div style="width: 100%; font-weight: bold; font-size: 22px !important; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                            <div style="width: 100%; font-size: 21px !important; text-align: center;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</div>
                        </td>
                        <td style="vertical-align: middle; text-align: left; width: 15%;">
                            
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" style="border-bottom: 2px solid black;">&nbsp;</td>
                    </tr>
                    <tr>
                        @if($template == 'shs')
                        <td colspan="3" style="font-size: 30px; text-align: center; font-weight: bold;"><br/><u>CERTIFICATION OF GOOD MORAL</u></td>
                        @else
                        <td colspan="3" style="font-size: 30px; text-align: center; font-weight: bold;"><br/><u>CERTIFICATION OF GOOD MORAL CHARACTER</u></td>
                        @endif
                    </tr>
                </table>
                <br/>
                <br/>
                <div style="font-family:Georgia, serif !important; margin: 0px 50px !important;">
                    <p>{{date('F d, Y', strtotime($givendate))}}</p>
                    <br/>
                    <p>TO WHOM IT MAY CONCERN:</p>
                    <p style="text-align: justify;">This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif</strong> is a  @if($template == 'jhs'){{strtolower($studentinfo->levelname)}} @if($studentinfo->levelid < 13) learner @else completer @endif @else Senior High School graduate @endif of {{DB::table('schoolinfo')->first()->schoolname}} for school year {{$syinfo->sydesc}}.</p>
                    <p style="text-align: justify;">This further certifies that @if(strtolower($studentinfo->gender) == 'male')he @elseif(strtolower($studentinfo->gender) == 'female')she @else he/she @endif is of good moral character and is cleared from all responsibilities and liabilities of this institution.</p>
                    @if($purpose != null)
                    <p style="text-align: justify;">{{$purpose}}</p>
                    @endif
                    <p style="text-align: justify;">Issued this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}} {{date('Y', strtotime($givendate))}} at {{DB::table('schoolinfo')->first()->schoolname}}, {{ucwords(strtolower($schoolinfo->address))}}.</p>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%; font-weight: bold;">{{$signatories[0]->name ?? ''}}</td>
                            <td style="width: 50%; font-weight: bold;">{{$signatories[1]->name ?? ''}}</td>
                        </tr>
                        <tr>
                            <td>{{$signatories[0]->description ?? ''}}</td>
                            <td>{{$signatories[1]->description ?? ''}}</td>
                        </tr>
                    </table>
                    {{-- <table style="width: 100%;">
                        <tr>
                            <th style="width: 40%; border-bottom: 1px solid black;">{{$schoolregistrar}}</th>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="text-align: center;">JHS Principal</td>
                            <td></td>
                        </tr>
                    </table> --}}
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <p>NOT VALID WITHOUT<br/>SCHOOL SEAL.</p>
                </div>
            @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                    <tr>
                        <td style="text-align: left; vertical-align: top; width: 23%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="110px"></td>
                        <td style="wudth: 70%; text-align: left; vertical-align: top;">
                            <div style="width: 100%; font-size: 18px !important; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sihs')
                            <div style="width: 100%; font-size: 18px !important; font-weight: bold;">@if($template == 'jhs') HIGH SCHOOL @else @endif</div>
                            @endif
                            <div style="width: 100%; font-size: 13px !important; font-weight: bold;"><em>{{DB::table('schoolinfo')->first()->address}}</em></div>
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                            <div style="width: 100%; font-size: 15px !important;">064-572-6321; 09518263138; sbcmlanghs@gmail.com</div>
                            @endif
                        </td>
                        <td style="vertical-align: middle; text-align: left; width: 7%;"></td>
                    </tr>
                </table>
                <br/>
                <div style="width: 100%; margin-top: 5px; text-align: justify; font-family: Arial, Helvetica, sans-serif; " id="container-content">
                    <div style="width: 100%; text-align: center; font-size: 21.5px;font-weight: bold;">CERTIFICATE of GOOD MORAL</div>
                    <div style="text-align: center;">(<em>for completed or graduated students only</em>)</div>
                    <br/>
                    <p style="font-weight: bold; margin-bottom: 10px;">TO WHOM IT MAY CONCERN:</p>
                    <p style="text-align: justify; line-height: 38px;"><span style="font-weight: bold;">THIS IS TO CERTIFY</span> that <u>{{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}}</u>@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') has completed @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'msli')or graduated @endif in @if($template != 'college')Grade @endif @else is a @endif <u>{{$studentinfo->levelname}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') completer @else @if($template == 'jhs') Section <u>{{$studentinfo->sectionname}}</u> @elseif($template == 'shs') Strand <u>{{$studentinfo->strandname}}</u> @elseif($template == 'college') in  <u>{{$studentinfo->strandname}}</u> @endif @endif at <span style="font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</span> School Year  <u>{{$syinfo->sydesc}}</u> with Learner Reference Number <u>{{$studentinfo->lrn}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') @if($escid != null)  and ESC ID <u>{{$escid}}</u>@endif @endif.</p>
                    <p style="text-align: justify; line-height: 40px;">This is to certify that he/she has good character and has no derogatory record filed in the office. This certification is issued to the above-mentioned student for whatever legal purpose it may serve him/her best.</p>
                    <p style="text-align: justify; line-height: 40px;">Given this <u>{{date('jS', strtotime($givendate))}}</u> day of <u>{{date('F', strtotime($givendate))}}</u>, {{date('Y', strtotime($givendate))}} at @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') completer @else @if($template == 'jhs') Section <u>{{$studentinfo->sectionname}}</u> @else Strand <u>{{$studentinfo->strandname}}</u> @endif @endif at @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'msli') the High School's Principal's Office, @endif {{ucwords(strtolower($schoolinfo->schoolname))}}, {{ucwords(strtolower($schoolinfo->address))}} Philippines.</p>
                    <p style="margin-top: 40px;">Signed:</p>
                    <br/>
                    <br/>
                    @if(count($signatories) == 0)
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%; font-weight: bold;"></td>
                                <td style="width: 50%; font-weight: bold;"></td>
                            </tr>
                            <tr>
                                <td>Principal</td>
                                <td>Guidance Coordinator</td>
                            </tr>
                        </table>
                    @else
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%; font-weight: bold;">{{$signatories[0]->name ?? ''}}</td>
                                <td style="width: 50%; font-weight: bold;">{{$signatories[1]->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>{{$signatories[0]->description ?? ''}}</td>
                                <td>{{$signatories[1]->description ?? ''}}</td>
                            </tr>
                        </table>
                    @endif
                    <br/>
                    <br/>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 15%">Purpose</td>
                            <td style="width: 85%; border-bottom: 1px solid black;">{{$purpose}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 12px;"><em>Valid with school seal</em></td>
                        </tr>
                    </table>
                </div>
            @else
            <table style="width: 100%; border-collapse: collapse; font-family: Arial, Helvetica, sans-serif; margin: 0px 10px;">
                <tr>
                    <td style="text-align: left; vertical-align: top; width: 23%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                    <td style="wudth: 70%; text-align: left; vertical-align: top;">
                        <div style="width: 100%; font-size: 18px !important; font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma' && strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sihs')
                        <div style="width: 100%; font-size: 18px !important; font-weight: bold;">@if($template == 'jhs') HIGH SCHOOL @else @endif</div>
                        @endif
                        <div style="width: 100%; font-size: 13px !important; font-weight: bold;"><em>{{DB::table('schoolinfo')->first()->address}}</em></div>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        <div style="width: 100%; font-size: 15px !important;">064-572-6321; 09518263138; sbcmlanghs@gmail.com</div>
                        @endif
                    </td>
                    <td style="vertical-align: middle; text-align: left; width: 7%;"></td>
                </tr>
            </table>
            @endif
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'xkhs')
            {{-- <br/> --}}
                {{-- <br/> --}}
                <div style="width: 100%; margin-top: 5px; text-align: justify; font-family: Arial, Helvetica, sans-serif; " id="container-content">
                    <div style="width: 100%; text-align: center; font-size: 21.5px;font-weight: bold;">CERTIFICATE of GOOD MORAL</div>
                    <div style="text-align: center; font-size: 12px;">(<em>for completed or graduated students only</em>)</div>
                    <br/>
                    <p style="font-weight: bold; margin-bottom: 10px; font-size: 12px;">TO WHOM IT MAY CONCERN:</p>
                    <p style="text-align: justify; line-height: 20px; font-size: 12px;"><span style="font-weight: bold;">THIS IS TO CERTIFY</span> that <u>{{$studentinfo->firstname}} @if($studentinfo->middlename != null){{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}}</u>@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') has completed @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'msli')or graduated @endif in @if($template != 'college')Grade @endif @else is a @endif <u>{{$studentinfo->levelname}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') completer @else @if($template == 'jhs') Section <u>{{$studentinfo->sectionname}}</u> @elseif($template == 'shs') Strand <u>{{$studentinfo->strandname}}</u> @elseif($template == 'college') in  <u>{{$studentinfo->strandname}}</u> @endif @endif at <span style="font-weight: bold;">{{DB::table('schoolinfo')->first()->schoolname}}</span> School Year  <u>{{$syinfo->sydesc}}</u> with Learner Reference Number <u>{{$studentinfo->lrn}}</u> @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sma') @if($escid != null)  and ESC ID <u>{{$escid}}</u>@endif @endif.</p>
                    <p style="text-align: justify; line-height: 20px; font-size: 12px;">This is to certify that he/she has good character and has no derogatory record filed in the office. This certification is issued to the above-mentioned student for whatever legal purpose it may serve him/her best.</p>
                    <p style="text-align: justify; line-height: 20px; font-size: 12px;">Given this <u>{{date('jS', strtotime($givendate))}}</u> day of <u>{{date('F', strtotime($givendate))}}</u>, {{date('Y', strtotime($givendate))}} at @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sma') completer @else @if($template == 'jhs') Section <u>{{$studentinfo->sectionname}}</u> @else Strand <u>{{$studentinfo->strandname}}</u> @endif @endif at @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'msli') the High School's Principal's Office, @endif {{ucwords(strtolower($schoolinfo->schoolname))}}, {{ucwords(strtolower($schoolinfo->address))}} Philippines.</p>
                    <p style="margin-top: 40px; font-size: 12px;">Signed:</p>
                    <br/>
                    <br/>
                    @if(count($signatories) == 0)
                        <table style="width: 100%; font-size: 12px;">
                            <tr>
                                <td style="width: 50%; font-weight: bold;"></td>
                                <td style="width: 50%; font-weight: bold;"></td>
                            </tr>
                            <tr>
                                <td>Principal</td>
                                <td>Guidance Coordinator</td>
                            </tr>
                        </table>
                    @else
                        <table style="width: 100%; font-size: 12px;">
                            <tr>
                                <td style="width: 50%; font-weight: bold;">{{$signatories[0]->name ?? ''}}</td>
                                <td style="width: 50%; font-weight: bold;">{{$signatories[1]->name ?? ''}}</td>
                            </tr>
                            <tr>
                                <td>{{$signatories[0]->description ?? ''}}</td>
                                <td>{{$signatories[1]->description ?? ''}}</td>
                            </tr>
                        </table>
                    @endif
                    <br/>
                    <br/>
                    <table style="width: 100%; font-size: 12px;">
                        <tr>
                            <td style="width: 15%">Purpose</td>
                            <td style="width: 85%; border-bottom: 1px solid black;">{{$purpose}}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 12px;"><em>Valid with school seal</em></td>
                        </tr>
                    </table>
                </div>
            @endif
        @endif
    </body>
</html>