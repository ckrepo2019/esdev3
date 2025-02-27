
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
            <img src="{{base_path()}}/public/{{$getSchoolInfo[0]->picurl}}" alt="school" width="70px">
        </td>
        <th colspan="7" style="padding-left:13%">
            <center>
                <h1>Class Record</h1>
                <em><sup>(Pursuant to Deped Order 8 series of 2015)</sup></em>
            </center>
        </th>
        <th rowspan="2" style="text-align:right;width:20px">
            {{-- <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px"> --}}
            <img src="{{base_path()}}/public/assets/images/department_of_Education.png" alt="school" width="70px">
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
        <td colspan="3"><input type="text" value="{{$getSchoolInfo[0]->schoolname}}" style="width: 100%;"/></td>
        <td style="text-align:right">SCHOOL ID</td>
        <td><input type="text" value="{{$getSchoolInfo[0]->schoolid}}"/></td>
        <td style="text-align:right">SCHOOL YEAR</td>
        <td><input type="text" value="{{$getSchoolYear[0]->sydesc}}"/></td>
    </tr>
</table>
<div >
    <table id="grades" class="page_break_table">
        <thead>
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
            <tr style="font-weight: bold;">
                <td width="2px"></td>
                <td width="200px">LEARNERS' NAMES</td>
                <td colspan="13">WRITTEN WORKS ({{$gradesetup->writtenworks}} %)</td>
                <td colspan="13">PERFORMANCE TASKS ({{$gradesetup->performancetask}} %)</td>
                <td colspan="3">QUARTERLY ASSESSMENT ({{$gradesetup->qassesment}} %)</td>
                <td rowspan="3">Initial<br>Grade</td>
                <td class="rightBorder" rowspan="3">Quarterly<br>Grade</td>
            </tr>
            <tr style="font-weight: bold;">
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
            @if(count($hps)>0)
            <tr style="font-weight: bold;">
                    <td width="2px"></td>
                    <td>HIGHEST POSSIBLE SCORE</td>
                    <td>{{$hps[0]->wwhr0}}</td>
                    <td>{{$hps[0]->wwhr1}}</td>
                    <td>{{$hps[0]->wwhr2}}</td>
                    <td>{{$hps[0]->wwhr3}}</td>
                    <td>{{$hps[0]->wwhr4}}</td>
                    <td>{{$hps[0]->wwhr5}}</td>
                    <td>{{$hps[0]->wwhr6}}</td>
                    <td>{{$hps[0]->wwhr7}}</td>
                    <td>{{$hps[0]->wwhr8}}</td>
                    <td>{{$hps[0]->wwhr9}}</td>
                    <td>
                        {{$hps[0]->wwhr0+$hps[0]->wwhr1+$hps[0]->wwhr2+$hps[0]->wwhr3+$hps[0]->wwhr4+$hps[0]->wwhr5+$hps[0]->wwhr6+$hps[0]->wwhr7+$hps[0]->wwhr8+$hps[0]->wwhr9}}
                    </td>
                    <td>100.00</td>
                    <td>{{$gradesetup->writtenworks}} %</td>
                    <td>{{$hps[0]->pthr0}}</td>
                    <td>{{$hps[0]->pthr1}}</td>
                    <td>{{$hps[0]->pthr2}}</td>
                    <td>{{$hps[0]->pthr3}}</td>
                    <td>{{$hps[0]->pthr4}}</td>
                    <td>{{$hps[0]->pthr5}}</td>
                    <td>{{$hps[0]->pthr6}}</td>
                    <td>{{$hps[0]->pthr7}}</td>
                    <td>{{$hps[0]->pthr8}}</td>
                    <td>{{$hps[0]->pthr9}}</td>
                    <td>
                        {{$hps[0]->pthr0+$hps[0]->pthr1+$hps[0]->pthr2+$hps[0]->pthr3+$hps[0]->pthr4+$hps[0]->pthr5+$hps[0]->pthr6+$hps[0]->pthr7+$hps[0]->pthr8+$hps[0]->pthr9}}
                    </td>
                    <td>100.00</td>
                    <td>{{$gradesetup->performancetask}}</td>
                    <td>{{$hps[0]->qahr1}}</td>
                    <td>100.00</td>
                    <td>{{$gradesetup->qassesment}} %</td>
                </tr>
            @endif
        </thead>
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
        @if(count($grades)>0)
            @foreach ($grades as $detail)
                @if(strtolower($detail->gender)=="male")
                    <tr>
                        <td width="2px">{{$mcount}}</td>
                        <td style="text-align: left;">{{$detail->studname}}</td>
                        <td>{{$detail->ww0}}</td>
                        <td>{{$detail->ww1}}</td>
                        <td>{{$detail->ww2}}</td>
                        <td>{{$detail->ww3}}</td>
                        <td>{{$detail->ww4}}</td>
                        <td>{{$detail->ww5}}</td>
                        <td>{{$detail->ww6}}</td>
                        <td>{{$detail->ww7}}</td>
                        <td>{{$detail->ww8}}</td>
                        <td>{{$detail->ww9}}</td>
                        <td>{{$detail->wwtotal}}</td>
                        <td>{{$detail->wwps}}</td>
                        <td>{{$detail->wwws}}</td>
                        <td>{{$detail->pt0}}</td>
                        <td>{{$detail->pt1}}</td>
                        <td>{{$detail->pt2}}</td>
                        <td>{{$detail->pt3}}</td>
                        <td>{{$detail->pt4}}</td>
                        <td>{{$detail->pt5}}</td>
                        <td>{{$detail->pt6}}</td>
                        <td>{{$detail->pt7}}</td>
                        <td>{{$detail->pt8}}</td>
                        <td>{{$detail->pt9}}</td>
                        <td>{{$detail->pttotal}}</td>
                        <td>{{$detail->ptps}}</td>
                        <td>{{$detail->ptws}}</td>
                        <td>{{$detail->qa1}}</td>
                        <td>{{$detail->qaps}}</td>
                        <td>{{$detail->qaws}}</td>
                        <td>{{number_format($detail->ig,2)}}</td>
                        <td class="rightBorder">{{number_format($detail->qg)}}</td>
                        
                        @php
                            $mcount += 1;
                        @endphp
                    </tr>
                @endif
            @endforeach
        @endif
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
        @if(count($grades)>0)
            @foreach ($grades as $detail)
                @if(strtolower($detail->gender)=="female")
                    <tr>
                        <td width="2px">{{$fcount}}</td>
                        <td style="text-align: left;">{{$detail->studname}}</td>
                        <td>{{$detail->ww0}}</td>
                        <td>{{$detail->ww1}}</td>
                        <td>{{$detail->ww2}}</td>
                        <td>{{$detail->ww3}}</td>
                        <td>{{$detail->ww4}}</td>
                        <td>{{$detail->ww5}}</td>
                        <td>{{$detail->ww6}}</td>
                        <td>{{$detail->ww7}}</td>
                        <td>{{$detail->ww8}}</td>
                        <td>{{$detail->ww9}}</td>
                        <td>{{$detail->wwtotal}}</td>
                        <td>{{$detail->wwps}}</td>
                        <td>{{$detail->wwws}}</td>
                        <td>{{$detail->pt0}}</td>
                        <td>{{$detail->pt1}}</td>
                        <td>{{$detail->pt2}}</td>
                        <td>{{$detail->pt3}}</td>
                        <td>{{$detail->pt4}}</td>
                        <td>{{$detail->pt5}}</td>
                        <td>{{$detail->pt6}}</td>
                        <td>{{$detail->pt7}}</td>
                        <td>{{$detail->pt8}}</td>
                        <td>{{$detail->pt9}}</td>
                        <td>{{$detail->pttotal}}</td>
                        <td>{{$detail->ptps}}</td>
                        <td>{{$detail->ptws}}</td>
                        <td>{{$detail->qa1}}</td>
                        <td>{{$detail->qaps}}</td>
                        <td>{{$detail->qaws}}</td>
                        <td>{{number_format($detail->ig,2)}}</td>
                        {{-- <td class="rightBorder">{{$detail->qg}}</td> --}}
                        <td class="rightBorder">{{number_format($detail->qg)}}</td>
                        @php
                            $fcount += 1;
                        @endphp
                    </tr>
                @endif
            @endforeach
        @endif
    </table>
    </div>
