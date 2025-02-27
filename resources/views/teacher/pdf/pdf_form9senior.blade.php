<style>
    html                        { font-family: Arial, Helvetica, sans-serif;  }
    /* html                        { font-family: Arial, Helvetica, sans-serif; } */
    .left                       { float: left; width : 45%; /* height : 100px; */ /* border: solid 1px black; */ /* display : inline-block; */ }
    .right                      { float: right; width : 45%; /* border: solid 1px black; */ /* background-color: red; */ }
    #logoTable                  { border-collapse: collapse; width:100%; font-size: 11px; }
    
    #studentInfo                { border-spacing: 0; width:100%; font-size: 11px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
    #studentInfo td             { /* border:1px solid black; */ padding:3px; border-spacing: 0; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
    #report                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
    .report2                     { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; }
    #values                     { /* border: 1px solid #ddd; */ /* border-collapse: collapse; */ border-spacing: 0; width:100%; font-family: Arial, Helvetica, sans-serif; /* text-align: justify; */ }
    #behavior                   { width:45%; }
    #remarks                    { /* border: 1px solid #ddd; */ font-size: 11px; border-collapse: collapse; width:100%; font-family: Arial, Helvetica, sans-serif; }
    #remarksCells               { border-bottom: 1px solid black; }
    #report td, #report th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
    .report2 td, .report2 th      { border: 1px solid #111; border-collapse: collapse; padding: 4px; font-size: 10px; }
    
    .cellBottom                 { border-bottom: 1px solid #111 !important; }
    .cellRight                  { border-right: 1px solid #111 !important;  }
    #values td, #values th      { border: 1px solid #111; border-bottom: hidden; border-right: hidden; border-collapse: collapse; padding: 4px; font-size: 11px; }
    #reportHeader               { margin:0px 5px 20px 5px; font-family: Arial, Helvetica, sans-serif; }
    #thquarter                  { width:20%; padding:10px; }
    #thsignature                { width:30%; padding:10px; }
    p#signature                 { font-family: Arial, Helvetica, sans-serif; }
    div#letter                  { font-size: 11px; }
    #firstParagraph             { text-indent: 10%; }
    #transfer                   { /* border: 1px solid #ddd; */ border-collapse: collapse; width:100%; margin: 0px 20px 0px 0px; /* font-family: Arial, Helvetica, sans-serif; */ }
    .page_break                 { page-break-before: always; }

    #logoTable2                  { border-collapse: collapse; width:100%; font-size: 11px; table-layout: fixed;/* border: 1px solid #ddd; */}
    #logoTable2 td                 {/* border: 1px solid #ddd;*/}

</style>
<table id="logoTable2">
    <tr>
        <td style="width: 25%;">
            <div >
                <br>
                <center><img src="{{base_path()}}/public/assets/images/broken_shire_logo.png" alt="school" width="80px" style="margin: 2px;"></center>
                {{-- <img src="{{base_path()}}/public/assets/images/united_church_of_christ.png" alt="school" style="margin: 2px;" width="70px"> --}}
            </div>
        </td>
        <td style="width: 50%; line-height: 15px;">
            <center>
                REPUBLIC OF THE PHILIPPINES
                <br>
                DEPARTMENT OF EDUCATION - {{$getSchoolInfo[0]->region}}
                
                <br>
                {{$getSchoolInfo[0]->division}} - {{$getSchoolInfo[0]->district}}
                <br>
                {{$getSchoolInfo[0]->schoolname}}
                <br>
                
                <br>
                SCHOOL ID - {{$getSchoolInfo[0]->schoolid}}
            </center>
        </td>
        <td style="width: 25%;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="150px"></td>
    </tr>
</table>
<div style="width: 100%; background-color:#87b900; height: 15px;">
    <center>
        <small style="font-size: 11px;">S.Y. {{$getSyId[0]->sydesc}}</small>
    </center>
</div>
<div style="display: block; position: absolute; ">
<div style="float: left; width: 40%;">
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 20%;">NAME:</td>
            <td style="border-bottom: 1px solid black;text-transform: uppercase;">{{$arrayForm[0][0]->lastname}}, {{$arrayForm[0][0]->firstname}} {{$arrayForm[0][0]->middlename[0]}} {{$arrayForm[0][0]->suffix}}</td>
        </tr>
    </table>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 20%;">AGE: </td>
            <td style="border-bottom: 1px solid black;">{{$arrayForm[1]}}</td>
            <td style="text-align: right;">SEX: </td>
            <td style="border-bottom: 1px solid black;">{{$arrayForm[0][0]->gender}}</td>
        </tr>
    </table>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 20%;">TRACK:</td>
            <td style="border-bottom: 1px solid black;text-transform:uppercase;">{{$arrayForm[6]->trackname}}</td>
        </tr>
    </table>
</div>
<div style="float: right; width: 50%;">
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 55%; text-transform:none;">Learner's Reference Number (LRN):</td>
            <td style="border-bottom: 1px solid black;">{{$arrayForm[0][0]->lrn}}</td>
        </tr>
        <tr>
            <td>GRADE/SECTION:</td>
            <td style="border-bottom: 1px solid black;">{{$arrayForm[2][0]->levelname}} - {{$arrayForm[3][0]->sectionname}}</td>
        </tr>
        <tr>
            <td>STRAND:</td>
            <td style="border-bottom: 1px solid black;">{{$arrayForm[6]->strandname}}</td>
        </tr>
    </table>
</div>
</div>
<br>
<br>
<br>
&nbsp;
<table class="report2"  >
    <thead>
        <tr>
            <td colspan="5" style="background-color:#87b900;font-size: 12px;">
                <center><strong>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</strong></center>
            </td>
        </tr>
        <tr>
            <th colspan="2" style="width:70%"><center>FIRST SEMESTER</center></th>
            <th rowspan="2"  style="width: 10%;"><center>1</center></th>
            <th rowspan="2"  style="width: 10%;"><center>2</center></th>
            <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
        </tr>
        <tr>
            <th colspan="2"><center>Subjects</center></th>
        </tr>
    </thead>
</table>
<table class="report2"  >
    <tbody>
        <tr>
            <td colspan="5" style="background-color:#b8ff33;font-size: 11px;">
                CORE SUBJECTS
            </td>
        </tr>
        @if(isset($shs_grades))
            @foreach ($shs_grades[0]['firstsem'] as $firstCore)
                @if(strtoupper($firstCore[0]->description)=='CORE SUBJECT')
                    <tr>                                    
                        <td style="width: 15%;">{{$firstCore[0]->description}}</td>
                        <td style="width: 55%;">{{$firstCore[0]->subjdesc}}</td>
                        <td style="width: 10%;">
                            @if($firstCore[0]->quarter == 1)
                                <center>{{$firstCore[0]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;">
                            @if($firstCore[1]->quarter == 2)
                                <center>{{$firstCore[1]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;"></td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td colspan="5" style="background-color:#ffe699;font-size: 11px;">
                APPLIED SUBJECTS
            </td>
        </tr>
        @if(isset($shs_grades))
            @foreach ($shs_grades[0]['firstsem'] as $firstApplied)
                @if(strtoupper($firstApplied[0]->description)=='APPLIED SUBJECT')
                    <tr>                                    
                        <td style="width: 15%;">{{$firstApplied[0]->description}}</td>
                        <td style="width: 55%;">{{$firstApplied[0]->subjdesc}}</td>
                        <td style="width: 10%;">
                            @if($firstApplied[0]->quarter == 1)
                                <center>{{$firstApplied[0]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;">
                            @if($firstApplied[1]->quarter == 2)
                                <center>{{$firstApplied[1]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;"></td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td colspan="5" style="background-color:#b3f0ff;font-size: 11px;">
                SPECIALIZED SUBJECT/S
            </td>
        </tr>
        @if(isset($shs_grades))
            @foreach ($shs_grades[0]['firstsem'] as $firstspecialized)
                @if(strtoupper($firstspecialized[0]->description)=='SPECIALIZED SUBJECT')
                    <tr>                                    
                        <td style="width: 15%;">{{$firstspecialized[0]->description}}</td>
                        <td style="width: 55%;">{{$firstspecialized[0]->subjdesc}}</td>
                        <td style="width: 10%;">
                            @if($firstspecialized[0]->quarter == 1)
                                <center>{{$firstspecialized[0]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;">
                            @if($firstspecialized[1]->quarter == 2)
                                <center>{{$firstspecialized[1]->qg}}</center>
                            @endif
                        </td>
                        <td style="width: 10%;"></td>
                    </tr>
                @endif
            @endforeach
        @endif
        <tr>
            <td colspan="2"><center>General Average for the Semester</center></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>
<br>
<table class="report2"  >
    <thead>
        <tr>
            <th colspan="2" style="width:70%"><center>SECOND SEMESTER</center></th>
            <th rowspan="2"  style="width: 10%;"><center>3</center></th>
            <th rowspan="2"  style="width: 10%;"><center>4</center></th>
            <th rowspan="2" style="width: 10%;" ><center>FINAL</center></th>
        </tr>
        <tr>
            <th colspan="2"><center>Subjects</center></th>
        </tr>
    </thead>
</table>
<table class="report2"  >
    <tbody>
        <tr>
            <td colspan="5" style="background-color:#b8ff33;font-size: 11px;">
                CORE SUBJECTS
            </td>
        </tr>
        @if(isset($shs_grades))
            @if(isset($shs_grades[0]['secondsem']))
                @foreach ($shs_grades[0]['secondsem'] as $firstCore)
                    @if(strtoupper($firstCore[0]->description)=='CORE SUBJECT')
                        <tr>                                    
                            <td style="width: 15%;">{{$firstCore[0]->description}}</td>
                            <td style="width: 55%;">{{$firstCore[0]->subjdesc}}</td>
                            <td style="width: 10%;">
                                @if($firstCore[0]->quarter == 3)
                                    <center>{{$firstCore[0]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;">
                                @if($firstCore[1]->quarter == 4)
                                    <center>{{$firstCore[1]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endif
        <tr>
            <td colspan="5" style="background-color:#ffe699;font-size: 11px;">
                APPLIED SUBJECTS
            </td>
        </tr>
        @if(isset($shs_grades))
            @if(isset($shs_grades[0]['secondsem']))
                @foreach ($shs_grades[0]['secondsem'] as $firstApplied)
                    @if(strtoupper($firstApplied[0]->description)=='APPLIED SUBJECT')
                        <tr>                                    
                            <td style="width: 15%;">{{$firstApplied[0]->description}}</td>
                            <td style="width: 55%;">{{$firstApplied[0]->subjdesc}}</td>
                            <td style="width: 10%;">
                                @if($firstApplied[0]->quarter == 3)
                                    <center>{{$firstApplied[0]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;">
                                @if($firstApplied[1]->quarter == 4)
                                    <center>{{$firstApplied[1]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endif
        <tr>
            <td colspan="5" style="background-color:#b3f0ff;font-size: 11px;">
                SPECIALIZED SUBJECT/S
            </td>
        </tr>
        @if(isset($shs_grades))
            @if(isset($shs_grades[0]['secondsem']))
                @foreach ($shs_grades[0]['secondsem'] as $firstspecialized)
                    @if(strtoupper($firstspecialized[0]->description)=='SPECIALIZED SUBJECT')
                        <tr>                                    
                            <td style="width: 15%;">{{$firstspecialized[0]->description}}</td>
                            <td style="width: 55%;">{{$firstspecialized[0]->subjdesc}}</td>
                            <td style="width: 10%;">
                                @if($firstspecialized[0]->quarter == 3)
                                    <center>{{$firstspecialized[0]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;">
                                @if($firstspecialized[1]->quarter == 4)
                                    <center>{{$firstspecialized[1]->qg}}</center>
                                @endif
                            </td>
                            <td style="width: 10%;"></td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endif
        <tr>
            <td colspan="2"><center>General Average for the Semester</center></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>
    <table class="report2" style="width: 100%; font-size:12px; text-transform:none;margin-top:5px;table-layout: fixed">
        <tr>
            <th>Descriptors</th>
            <th>Grading Scale</th>
            <th>Remarks</th>
        </tr>
        <tr>
            <td>Outstanding</td>
            <td>90-100</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Very Satisfactory</td>
            <td>85-89</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Satisfactory</td>
            <td>80-84</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Fairly Satisfactory</td>
            <td>75-79</td>
            <td>PASSED</td>
        </tr>
        <tr>
            <td>Did Not Meet Expectations</td>
            <td>Below 75</td>
            <td>FAILED</td>
        </tr>
    </table>
    &nbsp;
    {{-- <p><strong><center</center></strong></p> --}}
    <table id="values" style="border-bottom: hidden !important;">
        <thead>
            <tr>
                <th colspan="6" class="cellRight"><center>REPORT ON LEARNER'S OBSERVED VALUES</center></th>
            </tr>
            <tr>
                <th rowspan="2" style="width:15%"><center>Core Values</center></th>
                <th rowspan="2"><center>Behavior Statements</center></th>
                <th colspan="4" class="cellRight"><center>Quarter</center></th>
            </tr>
            <tr>
                <th><center>1</center></th>
                <th><center>2</center></th>
                <th><center>3</center></th>
                <th class="cellRight"><center>4</center></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th rowspan="2">1. Maka-Diyos</th>
                <td id="behavior">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaDiyos_1))
                        {{($values[0]['quarter1'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaDiyos_1))
                        {{($values[0]['quarter2'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaDiyos_1))
                        {{($values[0]['quarter3'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaDiyos_1))
                        {{($values[0]['quarter4'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td id="behavior">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaDiyos_2))
                        {{($values[0]['quarter1'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaDiyos_2))
                        {{($values[0]['quarter2'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaDiyos_2))
                        {{($values[0]['quarter3'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td style="width: 5%;">
                <td class="cellRight" style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaDiyos_2))
                        {{($values[0]['quarter4'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th>2. Makatao</th>
                <td id="behavior">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaTao_1))
                        {{($values[0]['quarter1'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaTao_1))
                        {{($values[0]['quarter2'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaTao_1))
                        {{($values[0]['quarter3'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaTao_1))
                        {{($values[0]['quarter4'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th rowspan="2">3. Makakalikasan</th>
                <td id="behavior">Demonstrates contributions towards solidarity</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaKalikasan_1))
                        {{($values[0]['quarter1'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaKalikasan_1))
                        {{($values[0]['quarter2'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaKalikasan_1))
                        {{($values[0]['quarter3'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaKalikasan_1))
                        {{($values[0]['quarter4'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaKalikasan_2))
                        {{($values[0]['quarter1'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaKalikasan_2))
                        {{($values[0]['quarter2'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaKalikasan_2))
                        {{($values[0]['quarter3'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaKalikasan_2))
                        {{($values[0]['quarter4'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th class="cellBottom" rowspan="2">4. Makabansa</th>
                <td id="behavior">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaBansa_1))
                        {{($values[0]['quarter1'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaBansa_1))
                        {{($values[0]['quarter2'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaBansa_1))
                        {{($values[0]['quarter3'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaBansa_1))
                        {{($values[0]['quarter4'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td id="behavior" class="cellBottom">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaBansa_2))
                        {{($values[0]['quarter1'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaBansa_2))
                        {{($values[0]['quarter2'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaBansa_2))
                        {{($values[0]['quarter3'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaBansa_2))
                        {{($values[0]['quarter4'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
            </tr>
        </tbody>
    </table> 
    <table id="values" style="border-top: hidden;">
        <tr>
            <td colspan="4" style="background-color:#a3d762 " style="border-top: hidden;" class="cellRight"><center>Observed Values</center></td>
        </tr>
        <tr>
            <td class="cellBottom"><center>AO - Always Observed</center></td>
            <td class="cellBottom"><center>SO - Sometimes Observed</center></td>
            <td class="cellBottom"><center>RO - Rarely Observed</center></td>
            <td class="cellBottom cellRight"><center>NO - Not Observed</center></td>
        </tr>
    </table>
    <br>
    <center><span style="font-size: 11px;"><strong>Certificate of Transfer</strong></span></center>
    <br>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="width: 15%;">Admitted to Grade:</td>
            <td style="border-bottom: 1px solid black;"></td>
            <td style="width: 25%;">Eligibility for Admission to Grade:</td>
            <td style="border-bottom: 1px solid black;"></td>
        </tr>
    </table>
    <br>
    <table style="width: 100%; table-layout: fixed; font-size: 11px;">
        <tr>
            <td style="padding-left: 10%; padding-right: 10%;text-align:center; width:">
                <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                    <strong>{{$arrayForm[5][0]->firstname}} {{$arrayForm[5][0]->middlename[0].'.'}} {{$arrayForm[5][0]->lastname}} {{$arrayForm[5][0]->suffix}}</strong>
                </div>
                <br>
                <sup>Adviser</sup>
            </td>
            <td style="padding-left: 10%; padding-right: 10%;text-align:center;">
                <div style="width: 100%; border-bottom: 1px solid;text-transform: uppercase;">
                    <strong>{{$arrayForm[2][0]->firstname}} {{$arrayForm[2][0]->middlename[0].'.'}} {{$arrayForm[2][0]->lastname}} {{$arrayForm[2][0]->suffix}}</strong>
                </div>
                <br>
                <sup>Senior High School Principal</sup>
            </td>
        </tr>
    </table>
{{-- <div class="left" >
    <p id="reportHeader" ><strong><center>REPORT ON ATTENDANCE</center></strong></p>
    <table id="report"  >
        <thead>
            <tr>
                <th></th>
                @foreach($studattrep[0]->monthly as $month)
                <th>{{$month->month}}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>No. of School</td>
                @foreach($studattrep[0]->monthly as $month)
                <td>{{$month->numDays}}</td>
                @endforeach
                <td>{{$studattrep[0]->yearly->numDays}}</td>
            </tr>
            <tr>
                <td>No. of Days Present</td>
                @foreach($studattrep[0]->monthly as $month)
                <td>{{$month->present}}</td>
                @endforeach
                <td>{{$studattrep[0]->yearly->present}}</td>
            </tr>
            <tr>
                <td>No. of Days Absent</td>
                @foreach($studattrep[0]->monthly as $month)
                <td>{{$month->absent}}</td>
                @endforeach
                <td>{{$studattrep[0]->yearly->absent}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <br>
    <p id="signature" ><strong><center>Homeroom Remarks & Parent's Signature</center></strong></p>
    <br>
    <table id="remarks">
        <tr>
            <th id="thquarter">1<sup>st</sup> Quarter</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thsignature">Parent's Signature</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thquarter">2<sup>nd</sup> Quarter</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thsignature">Parent's Signature</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thquarter">3<sup>rd</sup> Quarter</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thsignature">Parent's Signature</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thquarter">4<sup>th</sup> Quarter</th>
            <th id="remarksCells"></th>
        </tr>
        <tr>
            <th id="thsignature">Parent's Signature</th>
            <th id="remarksCells"></th>
        </tr>
    </table>
</div>
<div class="right"> 
    <table id="logoTable">
        <tr>
            <th style="width:60%">
                <center>
                    REPUBLIKA NG PILIPINAS
                    <br>
                    <br>
                    KAGAWARAN NG EDUKASYON
                    <br>
                    {{$getSchoolInfo[0]->region}}
                    <br>
                    <br>
                </center>
            </th> 
        </tr>
        <tr>
            <th>
            <center>{{$getSchoolInfo[0]->schoolname}}
                <br><br>
                {{$getSchoolInfo[0]->division}}
                </center>
            </th>
        </tr>
        <tr>
            <th>
                <center><img src="{{base_path()}}/public/assets/images/harvard.png" alt="school" width="80px">
                </center>
            </th>
        </tr>
    </table>
    <br>
    <table id="studentInfo">
        <tr>
            <td >Surname:  <u style="text-transform:uppercase">{{$arrayForm[0][0]->lastname}}</u></td>
            <td>First Name:  <u style="text-transform:uppercase">{{$arrayForm[0][0]->firstname}}</u></td>
            <td>M.I:  <u style="text-transform:uppercase">
                @if($arrayForm[0][0]->middlename[0] != '')
                {{$arrayForm[0][0]->middlename[0].'.'}}
                @else
                &nbsp;&nbsp;
                @endif
            </u></td>
        </tr>
    </table>
    <table id="studentInfo">
        <tr>
            <td>LRN:  <u>{{$arrayForm[0][0]->lrn}}</u></td>
        </tr>
    </table>
    <table id="studentInfo">
        <tr>
            <td>Age:  <u>{{$arrayForm[1]}}</u></td>
            <td>Sex:  <u>{{$arrayForm[0][0]->gender}}</u></td>
        </tr>
    </table>
    <table id="studentInfo">
        <tr>
            <td>Year & Section: <u>{{$arrayForm[2][0]->levelname}} - {{$arrayForm[3][0]->sectionname}}</u></td>
            <td>School Year:  <u>{{$arrayForm[4]}}</u></td>
        </tr>
    </table>
    <br>
    <br>
    <div id="studentInfo">
        <strong>Dear Parents,</strong>
        <br>
        <br>
        
        <span>
            <p style="margin:0px">This report card shows the ability and progress your child has made in the different learning areas as his/her core values.</p>
            <br>
            <p style="margin:0px" >The School welcomes you should desire to know more about your child's progress.<p>
        </span>
        
        <br>
        <span style="float:left;width:45%;">
            <br>
            <p style="margin:0px;">
                <center><u>{{$getId[0]->firstname}} {{$getId[0]->middlename[0]}}. {{$getId[0]->lastname}} {{$getId[0]->suffix}}</u></center>
            </p>
            <p style="margin:0px;">
                <center>Class Adviser</center>
            </p>
        </span>
        <span style="float:right;width:45%; margin:0px 20px 0px 0px;">
            <br>
            <p style="margin:0px;b">
                    <center><u>{{$arrayForm[2][0]->firstname}} {{$arrayForm[2][0]->middlename[0]}}. {{$arrayForm[2][0]->lastname}}</u></center>
            </p>
            <p style="margin:0px;">
                <center>School Principal</center>
            </p>
        </span>
        <br>
    </div>
</div>
<div class="page_break"></div>
<div class="left" >
    <p id="reportHeader" ><strong><center>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT<br>Track: <u></u></center></strong></p>
    @if(isset($shs_grades[0]))
    <table id="report"  >
        <thead>
            <tr>
                <th colspan="5" style="border:hidden">
                    <u>First Semester</u>
                </th>
            </tr>
            <tr>
                <th rowspan="2">SUBJECTS</th>
                <th colspan="2"><center>QUARTER</center></th>
                <th rowspan="2"><center>SEMESTER<br>FINAL GRADE</center></th>
                <th rowspan="2"><center>REMARKS</center></th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shs_grades[0] as $grade)<td>{{$grade[0]->subjdesc}}</td>
                <tr>
                    <td>
                        @if($grade[0]->quarter == 1)
                            @php
                                $quarter1 = $grade[0]->qg;
                            @endphp
                            <center>{{$grade[0]->qg}}</center>
                        @endif
                    </td>
                    <td>
                        @if($grade[1]->quarter == 2)
                            @php
                                $quarter2 = $grade[1]->qg;
                            @endphp
                            <center>{{$grade[1]->qg}}</center>
                        @endif
                    </td>
                    <td>
                        @php
                            $fg = ($quarter1 + $quarter2) / 2;
                        @endphp
                        <center>{{$fg}}</center>
                    </td>
                    <td>
                        @if($fg<75)
                            <center>FAILED</center>
                        @else
                            <center>PASSED</center>
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr>
                <th>GENERAL AVERAGE</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif
    @if(isset($shs_grades[1]))
    <table id="report"  >
        <thead>
            <tr>
                <th colspan="5" style="border:hidden">
                    <u>Second Semester</u>
                </th>
            </tr>
            <tr>
                <th rowspan="2">SUBJECTS</th>
                <th colspan="2"><center>QUARTER</center></th>
                <th rowspan="2"><center>SEMESTER<br>FINAL GRADE</center></th>
                <th rowspan="2"><center>REMARKS</center></th>
            </tr>
            <tr>
                <th>3</th>
                <th>4</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shs_grades[1] as $grade)
            <tr>
                <td>{{$grade[0]->subjdesc}}</td>
                <td>
                    @if($grade[0]->quarter == 3)
                        <center>{{$grade[0]->qg}}</center>
                    @endif
                </td>
                <td>
                    @if($grade[1]->quarter == 4)
                        <center>{{$grade[1]->qg}}</center>
                    @endif
                </td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
            <tr>
                <th>GENERAL AVERAGE</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif
</div>
<div class="right" >
    <p id="reportHeader" ><strong><center>REPORT ON LEARNER'S OBSERVED VALUES</center></strong></p>
    <table id="values" >
        <thead>
            <tr>
                <th rowspan="2"><center>Core Values</center></th>
                <th rowspan="2"><center>Behavior Statements</center></th>
                <th colspan="4" class="cellRight"><center>Quarter</center></th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th class="cellRight">4</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th rowspan="2">1. Maka-Diyos</th>
                <td id="behavior">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaDiyos_1))
                        {{($values[0]['quarter1'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaDiyos_1))
                        {{($values[0]['quarter2'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaDiyos_1))
                        {{($values[0]['quarter3'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaDiyos_1))
                        {{($values[0]['quarter4'][0]->makaDiyos_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td id="behavior">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaDiyos_2))
                        {{($values[0]['quarter1'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaDiyos_2))
                        {{($values[0]['quarter2'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
                <td style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaDiyos_2))
                        {{($values[0]['quarter3'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td style="width: 5%;">
                <td class="cellRight" style="width: 5%;">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaDiyos_2))
                        {{($values[0]['quarter4'][0]->makaDiyos_2)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th>2. Makatao</th>
                <td id="behavior">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaTao_1))
                        {{($values[0]['quarter1'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaTao_1))
                        {{($values[0]['quarter2'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaTao_1))
                        {{($values[0]['quarter3'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaTao_1))
                        {{($values[0]['quarter4'][0]->makaTao_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th rowspan="2">3. Makakalikasan</th>
                <td id="behavior">Demonstrates contributions towards solidarity</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaKalikasan_1))
                        {{($values[0]['quarter1'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaKalikasan_1))
                        {{($values[0]['quarter2'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaKalikasan_1))
                        {{($values[0]['quarter3'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaKalikasan_1))
                        {{($values[0]['quarter4'][0]->makaKalikasan_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td>Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaKalikasan_2))
                        {{($values[0]['quarter1'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaKalikasan_2))
                        {{($values[0]['quarter2'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaKalikasan_2))
                        {{($values[0]['quarter3'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaKalikasan_2))
                        {{($values[0]['quarter4'][0]->makaKalikasan_2)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <th class="cellBottom" rowspan="2">4. Makabansa</th>
                <td id="behavior">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaBansa_1))
                        {{($values[0]['quarter1'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaBansa_1))
                        {{($values[0]['quarter2'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaBansa_1))
                        {{($values[0]['quarter3'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaBansa_1))
                        {{($values[0]['quarter4'][0]->makaBansa_1)}}
                        @endif
                    @endif
                </td>
            </tr>
            <tr>
                <td id="behavior" class="cellBottom">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter1'][0]->makaBansa_2))
                        {{($values[0]['quarter1'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaBansa_2))
                        {{($values[0]['quarter2'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaBansa_2))
                        {{($values[0]['quarter3'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
                <td class="cellBottom cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaBansa_2))
                        {{($values[0]['quarter4'][0]->makaBansa_2)}}
                        @endif
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <br>
    <table id="studentInfo">
        <tr>
            <th>Grading Scale</th>
            <th>Descriptors</th>
            <th>Remaks</th>
        </tr>
        <tr>
            <td>A — 90-100</td>
            <td>Outstanding</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>B — 85-89</td>
            <td>Satisfactory</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>C — 80-84</td>
            <td>Needs Improvement</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>D — 75-79</td>
            <td>Fairly Satisfactory</td>
            <td>Passed</td>
        </tr>
        <tr>
            <td>E — Below 75</td>
            <td>Did Not Meet Expectation</td>
            <td>Failed</td>
        </tr>
    </table>
    <br>
    <table id="studentInfo">
            <tr>
                <th>Marking</th>
                <th>Non-Numerical Rating</th>
            </tr>
            <tr>
                <td>AO</td>
                <td>Always Observed</td>
            </tr>
            <tr>
                <td>SO</td>
                <td>Sometimes Observed</td>
            </tr>
            <tr>
                <td>RO</td>
                <td>Rarely Observed</td>
            </tr>
            <tr>
                <td>NO</td>
                <td>Not Observed</td>
            </tr>
        </table>
</div>

<script>
        var $ = jQuery;
        $(document).ready(function(){

        });
</script> --}}