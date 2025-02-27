
<style>
    html{ font-family: Arial, Helvetica, sans-serif; }

    .left{ float: left; width : 45%; }

    .right{ float: right; width : 45%; }

    #logoTable{ border-collapse: collapse; width:100%; font-size: 12px; }
    
    #studentInfo{ border-spacing: 0;  width:100%; font-size: 12px; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }

    #studentInfo td{ /* border:1px solid black; */ padding:3px; border-spacing: 0; /* margin: 0px 20px 0px 0px; */ /* font-family: Arial, Helvetica, sans-serif; */ }
    #report{
        /* border: 1px solid #ddd; */
        border-collapse: collapse;
        width:100%;
        
    }
    #values{
        /* border: 1px solid #ddd; */
        /* border-collapse: collapse; */
        border-spacing: 0;
        width:100%;
        font-family: Arial, Helvetica, sans-serif;
    }
    #behavior{
        width:45%;
    }
    #remarks{
        /* border: 1px solid #ddd; */
        font-size: 12px;
        border-collapse: collapse;
        width:100%;
        font-family: Arial, Helvetica, sans-serif;
    }
    #remarksCells {
        border-bottom: 1px solid black;
    }
    #report td, #report th{
        border: 1px solid #111;
        border-collapse: collapse;
        padding: 4px;
        font-size: 12px;
    }
    
    .cellBottom{
        border-bottom: 1px solid #111 !important;
    }.cellRight{
        border-right: 1px solid #111 !important;
    }
    #values td, #values th{
        border: 1px solid #111;
        border-bottom: hidden;
        border-right: hidden;
        border-collapse: collapse;
        padding: 4px;
        font-size: 12px;
    }
    #reportHeader{
        margin:0px 5px 10px 5px;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    #thquarter{
        width:20%;
        padding:10px;
    }
    #thsignature{
        width:30%;
        padding:10px;
    }
    #signature{
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    div#letter{
        font-size: 11px;
    }
    #firstParagraph{
        text-indent: 10%;
    }
    #transfer{
        /* border: 1px solid #ddd; */
        border-collapse: collapse;
        width:100%;
        margin: 0px 20px 0px 0px;
        /* font-family: Arial, Helvetica, sans-serif; */
    }
    .page_break { 
        page-break-before: always; 
    }
</style>
<div class="left" >
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
                <td>No. of days absent</td>
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
                    {{-- <br>
                    Sangay ng Lanao del Norte --}}
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
    {{-- {{$getSubjectName[0]}} --}}
    <p id="reportHeader" ><strong><center>REPORT ON LEARNING PROGRESS AND ACHIEVEMENT<br>Track: <u></u></center></strong></p>
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
            @foreach ($generateGrade as $grade)
            <tr>
                <td>{{$grade->assignsubject}}</td>
                <td>{{$grade->firstsem[0]['quarter1']}}</td>
                <td>{{$grade->firstsem[0]['quarter2']}}</td>
                <td>{{$grade->finalRating}}</td>
                <td>{{$grade->remarks}}</td>
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
    <br>
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
            @foreach ($generateGrade as $grade)
            <tr>
                <td>{{$grade->assignsubject}}</td>
                <td>{{$grade->secondsem[0]['quarter3']}}</td>
                <td>{{$grade->secondsem[0]['quarter4']}}</td>
                <td>{{$grade->finalRating}}</td>
                <td>{{$grade->remarks}}</td>
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
                        @if(isset($values[0]['quarter1'][0]->makaTao))
                        {{($values[0]['quarter1'][0]->makaTao)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter2'][0]->makaTao))
                        {{($values[0]['quarter2'][0]->makaTao)}}
                        @endif
                    @endif
                </td>
                <td>
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter3'][0]->makaTao))
                        {{($values[0]['quarter3'][0]->makaTao)}}
                        @endif
                    @endif
                </td>
                <td class="cellRight">
                    @if(count($values)==0)

                    @elseif(count($values)>0)
                        @if(isset($values[0]['quarter4'][0]->makaTao))
                        {{($values[0]['quarter4'][0]->makaTao)}}
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
                <td id="behavior" class="cellBottom">Demonstrates appropriate behavior in carying out activities in the school, community and country</td>
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
                <td>Not Obseved</td>
            </tr>
        </table>
</div>

<script>
        var $ = jQuery;
        $(document).ready(function(){

        });
</script>