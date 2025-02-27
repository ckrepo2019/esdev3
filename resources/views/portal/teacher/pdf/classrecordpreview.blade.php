
<style>
        #header{ /* border: 1px solid #ddd; */ font-size: 11px; border-spacing: 0; width:100%; font-family: Arial, Helvetica, sans-serif; }

        #header td,  #header th{ /* border: 1px solid black; */ }

        #grades{ /* border: 1px solid #ddd; */ border-bottom:1px solid black;  font-size: 13px; border-collapse: collapse;width:100%; font-family: Arial, Helvetica, sans-serif; margin: 5px 25px 0px 0px; }

        #grades td,  #grades th{ border: 1px solid black;  }
    #grades td:nth-child(2){
        text-transform: uppercase;
    }
        #grades td{ text-align: center; font-size: 11px; }

        .page_break { page-break-before: always; }

        .page_break_table { page-break-inside: auto; }

        input[type=text]{ padding: 3px; font-size: 11px; text-align: center; border: 1px solid black; }

        .left{ float: left; width : 45%; /* height : 100px; */ /* border: solid 1px black; */ /* display : inline-block; */ }

        h1,sup{ padding:0px; /* border: 1px solid black; */ margin:0px; }

        /* .rightBorder{ border-right: 1px solid black !important; }

        .topBorder{  border-top: 1px solid black !important; } */
    </style>
    <table id="header">
        <tr>
            <td rowspan="3" style="width:5%">
                <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
            </td>
            <th colspan="7" style="padding-left:13%">
                <center>
                    <h1>Class Record</h1>
                    <em><sup>(Pursuant to Deped Order 8 series of 2015)</sup></em>
                </center>
            </th>
            <th rowspan="2" style="text-align:right;width:20px">
                <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px">
            </th>
        </tr>
        <tr>
            <td style="text-align:right">REGION</td>
            <td width="15px"><input type="text" value="{{$getSchoolInfo[0]->region}}"/></td>
            <td width="10px">DIVISION</td>
            <td><input type="text" value="{{$getSchoolInfo[0]->division}}"/></td>
            <td width="80px" style="text-align:right">DISTRICT</td>
            <td><input type="text" value="{{$getSchoolInfo[0]->district}}"/></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align:right">SCHOOL NAME</td>
            <td colspan="3"><input type="text" value="{{$getSchoolInfo[0]->schoolname}}"/></td>
            <td style="text-align:right">SCHOOL ID</td>
            <td><input type="text" value="{{$getSchoolInfo[0]->schoolid}}"/></td>
            <td style="text-align:right">SCHOOL YEAR</td>
            <td><input type="text" value="{{$getSchoolYear[0]->sydesc}}"/></td>
        </tr>
    </table>
    <div >
        <table id="grades" class="page_break_table">
            <tr>
                <th colspan="2" class="topBorder">
                    @if ($getQuarter == 1)
                        FIRST
                    @elseif ($getQuarter == 2)
                        SECOND
                    @elseif ($getQuarter == 3)
                        THIRD
                    @elseif ($getQuarter == 4)
                        FOURTH
                    @endif
                    QUARTER
                </th>
                <th colspan="13" class="topBorder">GRADE & SECTION: {{$getLevelAndSection[0]->levelname}} - {{$getLevelAndSection[0]->sectionname}}</th>
                <th colspan="13" class="topBorder">TEACHER: {{$getTeacherName[0]->lastname}}, {{$getTeacherName[0]->firstname}} {{$getTeacherName[0]->middlename[0]}}. {{$getTeacherName[0]->suffix}}</th>
                <th colspan="5" class="rightBorder topBorder">SUBJECT: {{$getSubject[0]->subjdesc}}</th>
            </tr>
            <tr>
                <td width="2px"></td>
                <td width="100px">LEARNERS' NAMES</td>
                <td colspan="13">WRITTEN WORKS</td>
                <td colspan="13">PERFORMANCE TASKS</td>
                <td colspan="3">QUARTERLY ASSESSMENT</td>
                <td rowspan="2">Initial<br>Grade</td>
                <td class="rightBorder" rowspan="2">Quarterly<br>Grade</td>
            </tr>
            <tr>
                <td width="2px"></td>
                <td></td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>TOTAL</td>
                <td>PS</td>
                <td>WS</td>
                <td>1</td>
                <td>2</td>
                <td>3</td>
                <td>4</td>
                <td>5</td>
                <td>6</td>
                <td>7</td>
                <td>8</td>
                <td>9</td>
                <td>10</td>
                <td>TOTAL</td>
                <td>PS</td>
                <td>WS</td>
                <td>1</td>
                <td>PS</td>
                <td>WS</td>
            </tr>
            <tr>
                <th width="5px"></th>
                <th>MALE</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="rightBorder"></th>
            </tr>
            @php
                $fcount = 1;
                $mcount = 1;                    
            @endphp
            @foreach ($grades as $detail)
                @if(($detail->gender)=="MALE")
                    <tr>
                        <td width="2px">{{$mcount}}</td>
                        <td>{{$detail->studname}}</td>
                        <td>{{$detail->ww1}}</td>
                        <td>{{$detail->ww2}}</td>
                        <td>{{$detail->ww3}}</td>
                        <td>{{$detail->ww4}}</td>
                        <td>{{$detail->ww5}}</td>
                        <td>{{$detail->ww6}}</td>
                        <td>{{$detail->ww7}}</td>
                        <td>{{$detail->ww8}}</td>
                        <td>{{$detail->ww9}}</td>
                        <td>{{$detail->ww0}}</td>
                        <td>{{$detail->wwtotal}}</td>
                        <td>{{$detail->wwps}}</td>
                        <td>{{$detail->wwws}}</td>
                        <td>{{$detail->pt1}}</td>
                        <td>{{$detail->pt2}}</td>
                        <td>{{$detail->pt3}}</td>
                        <td>{{$detail->pt4}}</td>
                        <td>{{$detail->pt5}}</td>
                        <td>{{$detail->pt6}}</td>
                        <td>{{$detail->pt7}}</td>
                        <td>{{$detail->pt8}}</td>
                        <td>{{$detail->pt9}}</td>
                        <td>{{$detail->pt0}}</td>
                        <td>{{$detail->pttotal}}</td>
                        <td>{{$detail->ptps}}</td>
                        <td>{{$detail->ptws}}</td>
                        <td>{{$detail->qa1}}</td>
                        <td>{{$detail->qaps}}</td>
                        <td>{{$detail->qaws}}</td>
                        <td>{{$detail->ig}}</td>
                        <td class="rightBorder">{{$detail->qg}}</td>
                        
                        @php
                            $mcount += 1;
                        @endphp
                    </tr>
                @endif
            @endforeach
            <tr>
                <th width="5px"></th>
                <th>FEMALE</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th class="rightBorder"></th>
            </tr>
            @foreach ($grades as $detail)
            @if(($detail->gender)=="FEMALE")
            <tr>
                <td width="2px">{{$fcount}}</td>
                <td>{{$detail->studname}}</td>
                <td>{{$detail->ww1}}</td>
                <td>{{$detail->ww2}}</td>
                <td>{{$detail->ww3}}</td>
                <td>{{$detail->ww4}}</td>
                <td>{{$detail->ww5}}</td>
                <td>{{$detail->ww6}}</td>
                <td>{{$detail->ww7}}</td>
                <td>{{$detail->ww8}}</td>
                <td>{{$detail->ww9}}</td>
                <td>{{$detail->ww0}}</td>
                <td>{{$detail->wwtotal}}</td>
                <td>{{$detail->wwps}}</td>
                <td>{{$detail->wwws}}</td>
                <td>{{$detail->pt1}}</td>
                <td>{{$detail->pt2}}</td>
                <td>{{$detail->pt3}}</td>
                <td>{{$detail->pt4}}</td>
                <td>{{$detail->pt5}}</td>
                <td>{{$detail->pt6}}</td>
                <td>{{$detail->pt7}}</td>
                <td>{{$detail->pt8}}</td>
                <td>{{$detail->pt9}}</td>
                <td>{{$detail->pt0}}</td>
                <td>{{$detail->pttotal}}</td>
                <td>{{$detail->ptps}}</td>
                <td>{{$detail->ptws}}</td>
                <td>{{$detail->qa1}}</td>
                <td>{{$detail->qaps}}</td>
                <td>{{$detail->qaws}}</td>
                <td>{{$detail->ig}}</td>
                <td class="rightBorder">{{$detail->qg}}</td>
                @php
                    $fcount += 1;
                @endphp
            </tr>
            @endif
            @endforeach
        </table>
        </div>
    