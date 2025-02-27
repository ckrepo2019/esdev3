<html>
    <head>
        <title>Certificate of Enrollment  @if($tabletemplate == 'withunits') @endif</title>
        <style>
            @page{
                margin: 0.5in 0.5in 0.2in 0.5in;
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
            table {
                border-collapse: collapse;
            }
        </style>
    </head>
    <body>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
            <table style="width: 100%; table-layout: fixed;">
                <tr>
                    <th style="width: 20%;">
                        <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" style="width: 120px;"/>
                    </th>
                    <td style="text-align: center;">
                        <span style="font-size: 20px; font-weight: bold;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->schoolname))}}</span>
                        <br/>
                        <span style="font-size: 14px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</span>
                    </td>
                    <th style="width: 20%;"></th>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center; font-size: 20px; font-family: Arial, Helvetica, sans-serif;">
                        <u>C</u>&nbsp;&nbsp;&nbsp;<u>E</u>&nbsp;&nbsp;&nbsp;<u>R</u>&nbsp;&nbsp;&nbsp;<u>T</u>&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;<u>F</u>&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;<u>C</u>&nbsp;&nbsp;&nbsp;<u>A</u>&nbsp;&nbsp;&nbsp;<u>T</u>&nbsp;&nbsp;&nbsp;<u>I</u>&nbsp;&nbsp;&nbsp;<u>O</u>&nbsp;&nbsp;&nbsp;<u>N</u>
                    </td>
                </tr>
            </table>
            <br/>
            <table style="width: 100%; margin: 0px 20px;">
                <tr>
                    <td>TO WHOM IT MAY CONCERN:</td>
                </tr>
                <tr>
                    <td style="text-align: justify; padding-top: 5px; line-height: 25px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that @if(strtolower($studentinfo->gender) == 'female') MS. @else MR. @endif {{$studentinfo->firstname}} @if($studentinfo->middlename != null) {{$studentinfo->middlename[0]}}.@endif {{$studentinfo->lastname}} {{$studentinfo->suffix}}, a {{strtolower($studentinfo->yearlevel)}} student of the course {{$studentinfo->strandname}} under the {{$studentinfo->collegename}} of this Institution was officially enrolled during the {{$semesterinfo->semester}} of SY {{$syinfo->sydesc}} in the subjects listed below with their corresponding grade(s) and unit(s).</td>
                </tr>
            </table><br/>
            <table style="width: 100%; margin: 0px 20px 0px 50px; font-size: 13px; font-family: Arial, Helvetica, sans-serif; line-height: 20px;">
                <thead>
                    <tr>
                        <td style="text-align: left;">CODE</td>
                        <td style="text-align: left;">DESCRIPTIVE TITLE</th>
                        <td style="text-align: center;">GRADE</td>
                        <td style="text-align: center;">C.G.</td>
                        <td style="text-align: center;">UNITS</th>
                    </tr>
                    <tr>
                        <td colspan="5" style="text-align: left;"><u>College {{$semesterinfo->semester}} A.Y. {{$syinfo->sydesc}}</u></td>
                    </tr>
                </thead>
                @if(count($subjects) == 0)
                <tr>
                    <th>&nbsp;</th>
                    <td></td>
                    <td></td>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                @else
                    @foreach($subjects as $subj)
                        <tr>
                            <td class="p-0">{{$subj->subjcode}}</td>
                            <td class="p-0">{{$subj->subjdesc}}</td>
                            <td style="text-align: center;">{{$subj->subjgrade}}</td>
                            <td></td>
                            <td style="text-align: center;">{{$subj->subjunit}}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td></td>
                    <td>- - - - - - - - - - Nothing Follows - - - - - - - - - - </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td  style="text-align: right;">Units:</td>
                    <td  style="text-align: center; border-bottom: 1px solid black !important;">{{collect($subjects)->sum('subjunit')}}</td>
                </tr>
            </table>
            <br/>
            <p style="text-align: justify; margin: 0px 20px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon the request of @if(strtolower($studentinfo->gender) == 'female') Ms. @else Mr. @endif {{ucwords(strtolower($studentinfo->lastname))}} this {{date('jS', strtotime($givendate))}} day of {{date('F Y', strtotime($givendate))}} for {{$purpose}}
            <br/>
            <br/>
            <br/>
            <table style="width: 100%; table-layout: fixed; font-size: 15px; margin: 0px 20px;">
                <tr>
                    <td rowspan="2">(Not valid<br/>without the<br/>school seal.)</td>
                    <td rowspan="2"></td>
                    <td style="text-align: center; vertical-align: bottom;">{{$schoolregistrar}}</td>
                </tr>
                <tr>
                    <td style="text-align: center; vertical-align: top; font-size: 13px !important;">OIC-College Registrar</td>
                </tr>
            </table>
        @else
            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
            <div id="watermark1" style="padding-top: 170px; text-align: center;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" height="95%" width="95%" style="padding-left: 20px;" />
            </div>
            @endif
            <table style="width: 100%; border-collapse: collapse;@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'ndsc') font-family: Arial, Helvetica, sans-serif;@endif margin: 0px 10px;">
                <tr>
                    <td rowspan="2" style="text-align: right; vertical-align: top; width: 15%;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                    <td style="wudth: 70%; text-align: center;">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                        <div style="width: 100%; font-weight: bold; font-size: 20px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Founded in 1965 by the Oblates of Mary Immaculate (OMI)</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Owned by the Archdiocese of Cotabato</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Administered by the Diocesan Clergy of Cotabato (DCC)</div>
                        <div style="width: 100%; font-size: 17.7px !important;">Lebak, Sultan Kudarat</div>
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                        <div style="width: 100%; font-weight: bold; font-size: 25px;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->address}}</div>
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                        <div style="width: 100%;">Republic of the Philippines</div>
                        <div style="width: 100%; font-size: 20px !important;">Commission on Higher Education</div>
                        @else
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->regiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->divisiontext}}</div>
                        <div style="width: 100%; font-size: 15px !important;">{{DB::table('schoolinfo')->first()->districttext}}</div>
                        @endif
                    </td>
                    <td rowspan="2" style="vertical-align: top; text-align: left; width: 15%;">
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                        <img src="{{base_path()}}/public/assets/images/ndsc/logo_archdiocese.jpg" alt="school" width="100px">
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        
                        
                        @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci')
                        <img src="{{base_path()}}/public/assets/images/logo_ched.png" alt="school" width="100px">
                        @else
                        <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="100px">
                        @endif
                    </td>
                </tr>
                <tr>
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                    <td style="font-size: 13px; text-align: center; vertical-align: top; padding-top: 10px;">                    
                        <img src="{{base_path()}}/public/assets/images/ndsc/qoute.jpg" alt="school" width="300px">
                        <div style="width: 100%; font-size: 17.7px; font-weight: bold;">(B.E.S.T)</div>
                    </td>
                    @elseif(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                    <td style="font-size: 13px; text-align: center; vertical-align: top; padding-top: 10px;">         
                        <div style="width: 100%; font-size: 15px !important; font-weight: bold;">OFFICE OF THE COLLEGE REGISTRAR</div>
                    </td>
                    @else
                    <td style="font-size: 13px; text-align: center; vertical-align: top;">
                        <div style="width: 100%; font-weight: bold; font-size: 16px !important;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
                        <div style="width: 100%;">{{DB::table('schoolinfo')->first()->address}}</div>
                    </td>
                    @endif
                </tr>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'sic')
                <tr>
                    <td colspan="3" >&nbsp;</td>
                </tr>
                @endif
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
            <div style="width: 100%; padding: 0 0.5in; text-align: justify;">
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                <div style="width: 100%; text-align: center; font-size: 30px; font-family: Times, serif; font-weight: bold;"><img src="{{base_path()}}/public/assets/images/ndsc/headertext.png" alt="school" width="300px"></div>
                
                @else
                <div style="width: 100%; text-align: center; font-size: 30px; font-family: Times, serif; font-weight: bold;">C E R T I F I C A T I O N</div>
                @endif
                <p style="font-size: 17.7px !important;">TO WHOM IT MAY CONCERN:</p>
                <p style="font-size: 17.7px !important;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that <strong>{{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{isset($studentinfo->middlename[0]) ? $studentinfo->middlename[0].'. ' : ''}} {{$studentinfo->suffix}}</strong> is officially enrolled in {{$studentinfo->strandname}} ({{$studentinfo->strandcode}}) @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') - {{$studentinfo->yearlevel}}  @endif at {{DB::table('schoolinfo')->first()->schoolname}} for the @if($semesterinfo->id == 1)First Semester @elseif($semesterinfo->id == 2) Second Semester @endif, School Year {{$syinfo->sydesc}}.</p>
                @if($tabletemplate == 'withunits')
                <p style="font-size: 17.7px !important;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that he/she enrolled the following courses to wit:
                </p>
                <table style="width: 100%; font-size: 14px !important;" >
                    <thead>
                        <tr>
                            <th style="text-align: left;">
                                {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                                 --}}
                                Course Code 
                                {{-- @endif --}}
                            </th>
                            <th style="text-align: left;"><i>
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')Descriptive Title @else
                                {{-- if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                                Course Description 
                                {{-- @else Course  --}}
                                 @endif</i></th>
                            <th style="text-align: center;"><i>Units</i></th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    @if(count($subjects) == 0)
                    <tr>
                        <th>&nbsp;</th>
                        <td></td>
                        <td></td>
                        <th>&nbsp;</th>
                    </tr>
                    @else
                        @foreach($subjects as $subj)
                            <tr>
                                {{-- @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc'  || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc') --}}
                                <td>{{$subj->subjcode}}</td>
                                <td class="p-0">{{$subj->subjdesc}}</td>
                                {{-- @else
                                <td></td>
                                <td class="p-0">{{$subj->subjcode}}</td>
                                @endif --}}
                                <td class="p-0" style="text-align: center;">{{$subj->subjunit}}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <th>&nbsp;</th>
                        <th style="text-align: right;">Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th style="border-top: 1px solid black; text-align: center;">{{collect($subjects)->sum('subjunit')}}</th>
                        <th>&nbsp;</th>
                    </tr>
                </table>
                <p style="font-size: 17.7px !important;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic') This certification is issued upon the request of <u>{{$studentinfo->lastname}}</u> for whatever legal purposes it may serve him/her best.
                    @else  This certification is issued upon his request for whatever legal purposes it may serve him/her best.
                    @endif
                </p>
                @else
                <p style="font-size: 17.7px !important;">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify further that he/she enrolled the following courses and earned the corresponding ratings to wit:
                </p>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ndsc')
                <table style="width: 100%; font-size: 15px !important;">
                    <thead>
                        <tr>
                            <th style="text-align: left;"><i>Course Code</i></th>
                            <th class="text-center" style="width: 55%;"><i>Course Description</i></th>
                            <th class="text-center"><i>Grades</i></th>
                            <th class="text-center"><i>Units</i></th>
                        </tr>
                    </thead>
                    @if(count($subjects) == 0)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else
                        @foreach($subjects as $subj)
                            <tr style="font-size: 13px !important;">
                                <td>{{$subj->subjcode}}</td>
                                <td>{{$subj->subjdesc}}</td>
                                <td class="p-0" style="text-align: center;">{{$subj->subjgrade}}</td>
                                <td class="p-0" style="text-align: center;">{{$subj->subjunit}}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
                @else
                <table style="width: 100%; font-size: 15px !important;">
                    <thead>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                        <tr>
                            <th>&nbsp;</th>
                            <th style="text-align: center;"><i>Descriptive Title</i></th>
                            <th style="text-align: center;"><i>Ratings</i></th>
                            <th style="text-align: center;"><i>Units</i></th>
                            <th>&nbsp;</th>
                        </tr>
                        @else
                        <tr>
                            <th style="text-align: left;"><i>Course Code</i></th>
                            <th style="text-align: left;"><i>Course Description</i></th>
                            <th style="text-align: center;"><i>Ratings</i></th>
                            <th style="text-align: center;"><i>Units</i></th>
                            <th>&nbsp;</th>
                        </tr>
                        @endif
                    </thead>
                    @if(count($subjects) == 0)
                    <tr>
                        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                        <th>&nbsp;</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        <th>&nbsp;</th>
                        @else
                        <th>&nbsp;</th>
                        <td></td>
                        <td></td>
                        <td></td>
                        @endif
                    </tr>
                    @else
                        @foreach($subjects as $subj)
                            <tr>
                                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                                    <td class="p-0">{{$subj->subjcode}}</td>
                                    <td class="p-0" style="text-align: center;">{{$subj->subjgrade}}</td>
                                    <td class="p-0" style="text-align: center;">{{$subj->subjunit}}</td>
                                    <td></td>
                                @else
                                    <td class="p-0">{{$subj->subjcode}}</td>
                                    <td class="p-0">{{$subj->subjdesc}}</td>
                                    <td class="p-0" style="text-align: center;">{{$subj->subjgrade}}</td>
                                    <td class="p-0" style="text-align: center;">{{$subj->subjunit}}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endif
                </table>
                @endif
                <p style="">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This certification is issued upon his/her request for whatever legal purposes it may serve him/her best.
                </p>
                @endif
                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Given this {{date('jS', strtotime($givendate))}} day of {{date('F', strtotime($givendate))}}, {{date('Y', strtotime($givendate))}} at <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>, {{DB::table('schoolinfo')->first()->address}}, PHILIPPINES.
                </p>
                <br>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'nsdphs')
                <br>
                <br>
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
                {{-- <img src="{{asset('assets/images/ndsc/sign_padayao.png')}}" width="150px" style="float: right;"/> --}}
                <table style="width: 100%;">
                    @if(strpos(strtolower($schoolregistrar), 'padayao') !== false)
                    
                    <tr>
                        <th style="">&nbsp;</th>
                        <th style="width: 30%;">&nbsp;</th>
                        <th style="text-align: center; vertical-align: bottom;"><img src="{{asset('assets/images/ndsc/sign_padayao.png')}}" style=" width: 180px; position: relative; right: 100px; margin-bottom: -35px;"/></th>
                    </tr>
                    @else
                    <tr>
                        <th style="">&nbsp;</th>
                        <th style="width: 30%;">&nbsp;</th>
                        <th style="">&nbsp;</th>
                    </tr>
                    <tr>
                        <th style="">&nbsp;</th>
                        <th style="width: 30%;">&nbsp;</th>
                        <th style="">&nbsp;</th>
                    </tr>
                    @endif
                    <tr>
                        <th style="">&nbsp;</th>
                        <th style="width: 30%;">&nbsp;</th>
                        <th style=""> <sub style="width: 40%; font-size: 15px; font-weight: bold; text-align: center; padding: 0px;">{{$schoolregistrar}}</sub></th>
                    </tr>
                    <tr>
                        <th style="">&nbsp;</th>
                        <th style="width: 30%;">&nbsp;</th>
                        <th style="">&nbsp;</th>
                    </tr>
                    <tr>
                        <td style="text-align: center;"></td>
                        <th></th>
                        <td style="text-align: center;">
                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sic')
                            <sup style="width: 40%; text-align: center; padding: 0px;">OIC - Registrar</sup>
                            @else
                            <sup style="width: 40%; text-align: center; padding: 0px;">School Registrar</sup>
                            @endif
                        </td>
                    </tr>
                </table>
                @endif
                <br/>
                <br/>
                <div style="margin: 0px; width: 100%; font-size: 12px;">
                    NOT VALID WITHOUT<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SCHOOL SEAL.
                </div>
            </div>
        @endif
    </body>
</html>