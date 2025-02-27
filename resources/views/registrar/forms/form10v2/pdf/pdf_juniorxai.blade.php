<style>
    * { font-family: Arial, Helvetica, sans-serif; }
    @page { margin: 20px;  size: 8.5in 13in ;}

    #table1 td{
        padding: 0px;
    }
    table {
        border-collapse: collapse;
    }
    #table2{
        margin-top: 2px;
        font-size: 11px;
    }

    input[type="checkbox"] {
    /* position: relative; */
    top: 2px;
    box-sizing: content-box;
    width: 14px;
    height: 14px;
    margin: 0 5px 0 0;
    cursor: pointer;
    -webkit-appearance: none;
    border-radius: 2px;
    background-color: #fff;
    border: 1px solid #b7b7b7;
    }

    input[type="checkbox"]:before {
    content: '';
    display: block;
    }

    input[type="checkbox"]:checked:before {
    width: 4px;
    height: 9px;
    margin: 0px 4px;
    border-bottom: 2px solid ;
    border-right: 2px solid ;
    transform: rotate(45deg);
    }
    .text-center{
        text-align: center;
    }
    td, th{
        padding: 1px;
    }
</style>

<table style="width: 100%; table-layout: fixed; text-align: center;">
    <tr>
        <td style="width: 10%; font-size: 12px; border: 1px solid black; padding: 5px 0px;">FORM 137-A</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td style="width: 13%;"></td>
        <td style="width: 15%;" rowspan="4"><img src="{{base_path()}}/public/excelformats/xai/archdiocesan.png" alt="school" width="90px"></td>
        <td><img src="{{base_path()}}/public/excelformats/xai/header_schoolname.jpg" alt="school" width="340px"></td>
        <td style="width: 15%;" rowspan="4"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="90px"></td>
        <td style="width: 13%;" rowspan="4"></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 15px; font-weight: bold;"><img src="{{base_path()}}/public/excelformats/xai/header_1.png" alt="school" width="240px"></td>
    </tr>
    <tr>
        <td></td>
        <td style="font-size: 13px;"><img src="{{base_path()}}/public/excelformats/xai/header_2.jpg" alt="school" width="220px"></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
    </tr>
</table>
<br/>
<div style="width: 100%; text-align: center;"><img src="{{base_path()}}/public/excelformats/xai/header_3.jpg" alt="school" width="530px"></div>
<br/>
<br/>
<table style="width: 100%; table-layout: fixed;" border="1">
    <tr>
        <th colspan="14" style="font-weight: bold; text-align: center; font-size: 18px; background-color: #ccd9ff;">PERSONAL PROFILE</th>
    </tr>
    <tr style="font-size: 11px;">
        <td style="width: 15%;">NAME:</td>
        <td colspan="4" style="font-weight: bold;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{$studinfo->middlename}} </td>
        <td>LRN:</td>
        <td colspan="3" style="text-align: center; background-color: #ffccb3;">{{$studinfo->lrn}}</td>
        <td colspan="2" style="width: 10%;">Date of Birth:</td>
        <td colspan="3" style="text-align: center;">{{date('m/d/Y', strtotime($studinfo->dob))}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2" style="width: 20%;">Place of Birth/ Province:</td>
        <td colspan="3" style="text-align: center; ">{{$studinfo->province}}</td>
        <td>Town:</td>
        <td colspan="3" style="text-align: center; ">{{$studinfo->city}}</td>
        <td colspan="2">Barangay:</td>
        <td colspan="3" style="text-align: center; ">{{$studinfo->barangay}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2" style="width: 20%;">Parent/ Guardian:</td>
        <td colspan="7" style="text-align: center; ">@if($studinfo->ismothernum == 1){{$studinfo->mothername}}@elseif($studinfo->isfathernum == 1){{$studinfo->fathername}}@elseif($studinfo->isguardiannum == 1){{$studinfo->guardianname}}@endif</td>
        <td colspan="2">Occupation:</td>
        <td colspan="3">@if($studinfo->ismothernum == 1){{$studinfo->moccupation}}@elseif($studinfo->isfathernum == 1){{$studinfo->foccupation}}@endif</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="4">Address of Parent/ Guardian:</td>
        <td colspan="10">{{$eligibility->guardianaddress}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="4">Elementary Graduated:</td>
        <td colspan="5" style="text-align: center; ">{{$eligibility->schoolname}}</td>
        <td colspan="2">School Year:</td>
        <td colspan="3" style="text-align: center; ">{{$eligibility->sygraduated}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="6">Total number of years in school to complete Elementary Education:</td>
        <td colspan="3" style="text-align: center; ">{{$eligibility->totalnoofyears}}</td>
        <td colspan="2">Gen. Ave.:</td>
        <td colspan="3" style="text-align: center; ">{{$eligibility->genave}}</td>
    </tr>
    <tr>
        <th colspan="14" style="font-weight: bold; text-align: center; font-size: 18px; background-color: #ccd9ff;">EDUCATIONAL PROFILE</th>
    </tr>
    @php
        $grades_first = collect($records)->first()[0]->grades;
        $attendance_first = collect($records)->first()[0]->attendance;
        $info_first   = collect($records)->first()[0];

        if(count($attendance_first)>0)
        {
            foreach($attendance_first as $eachatt)
            {
                $eachatt->monthdesc  = strtolower($eachatt->monthdesc);

                if($eachatt->monthdesc == 'may' || $eachatt->monthdesc == 'june' || $eachatt->monthdesc == 'july')
                {
                    $eachatt->days = 0;
                    $eachatt->present = 0;
                }
            }
        }
    @endphp
    <tr style="font-size: 11px;">
        <td>Classified as:</td>
        <td colspan="3" style="text-align: center;">{{$info_first->levelname}} - {{$info_first->sectionname}}</td>
        <td colspan="5" style="text-align: center;">{{$info_first->schoolname}}</td>
        <td colspan="2">School Year:</td>
        <td colspan="3" style="text-align: center;">{{$info_first->sydesc}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td>Curriculum</td>
        <td rowspan="2" colspan="4" style="width: 25%; text-align: center;">SUBJECT</td>
        <td colspan="4" style="text-align: center;">Class Standing</td>
        <td colspan="2" style="text-align: center;">Final</td>
        <td colspan="2" style="text-align: center;">Action</td>
        <td style="width: 10%;">Credits</td>
    </tr>
    <tr style="font-size: 11px;">
        <td>Year</td>
        <td style="text-align: center;">1</td>
        <td style="text-align: center;">2</td>
        <td style="text-align: center;">3</td>
        <td style="text-align: center;">4</td>
        <td colspan="2" style="text-align: center;">Grade</td>
        <td colspan="2" style="text-align: center;">Taken</td>
        <td>Earned</td>
    </tr>
    @if(count($grades_first) == 0)
        @for($x = 0; $x <= 8; $x++)
            <tr style="font-size: 11px;">
                <td style="text-align: center;">GRADE 7</td>
                <td colspan="4"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
        @endfor
    @else
        @foreach($grades_first as $firstgrade)
            @php
                $indent = 0;
                if(isset($firstgrade->mapeh))
                {
                    $indent = $firstgrade->mapeh;
                }
                if(isset($firstgrade->inTLE))
                {
                    if($indent == 0)
                    {
                        $indent = $firstgrade->inTLE;
                    }
                }
                if(isset($firstgrade->inMAPEH))
                {
                    if($indent == 0)
                    {
                        $indent = $firstgrade->inMAPEH;
                    }
                }
                $firstgrade->credits = null;
                $firstgrade->indent = $indent;
            @endphp
            <tr style="font-size: 11px;">
                <td style="text-align: center;">GRADE 7</td>
                <td colspan="4">&nbsp;@if($indent > 0)&nbsp;&nbsp;&nbsp;@endif{{$firstgrade->subjdesc}}</td>
                <td style="text-align: center;">{{$firstgrade->q1}}</td>
                <td style="text-align: center;">{{$firstgrade->q2}}</td>
                <td style="text-align: center;">{{$firstgrade->q3}}</td>
                <td style="text-align: center;">{{$firstgrade->q4}}</td>
                <td colspan="2" style="text-align: center;">{{$firstgrade->finalrating}}</td>
                <td colspan="2" style="text-align: center;">{{$firstgrade->remarks}}</td>
                <td style="text-align: center;">{{$firstgrade->credits}}</td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td></td>
        <td colspan="4" style="font-size: 15px; font-weight: bold;">AVERAGING</td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_first)->where('inSF9')->avg('q1')) > 0)
                {{number_format(collect($grades_first)->where('inSF9')->avg('q1'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_first)->where('inSF9')->avg('q2')) > 0)
                {{number_format(collect($grades_first)->where('inSF9')->avg('q2'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_first)->where('inSF9')->avg('q3')) > 0)
                {{number_format(collect($grades_first)->where('inSF9')->avg('q3'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_first)->where('inSF9')->avg('q4')) > 0)
                {{number_format(collect($grades_first)->where('inSF9')->avg('q4'))}}
            @endif
        </td>
        <td colspan="2" style="text-align: center; font-size: 11px;"></td>
        <td colspan="2" style="text-align: center; font-size: 11px;"></td>
        <td style="text-align: center; font-size: 11px;"></td>
    </tr>
    <tr style="font-size: 11px; text-align: center;">
        <td></td>
        <td style="width: 15%;"></td>
        <td>June</td>
        <td>July</td>
        <td>Aug.</td>
        <td>Sept.</td>
        <td>Oct.</td>
        <td>Nov.</td>
        <td>Dec.</td>
        <td>Jan.</td>
        <td>Feb.</td>
        <td>Mar.</td>
        <td>Apr.</td>
        <td>Total</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Days of school</td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','june')->count()>0)
                {{collect($attendance_first)->where('monthdesc','june')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','july')->count()>0)
                {{collect($attendance_first)->where('monthdesc','july')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','august')->count()>0)
                {{collect($attendance_first)->where('monthdesc','august')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','september')->count()>0)
                {{collect($attendance_first)->where('monthdesc','september')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','october')->count()>0)
                {{collect($attendance_first)->where('monthdesc','october')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','november')->count()>0)
                {{collect($attendance_first)->where('monthdesc','november')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','december')->count()>0)
                {{collect($attendance_first)->where('monthdesc','december')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','january')->count()>0)
                {{collect($attendance_first)->where('monthdesc','january')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','february')->count()>0)
                {{collect($attendance_first)->where('monthdesc','february')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','march')->count()>0)
                {{collect($attendance_first)->where('monthdesc','march')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','april')->count()>0)
                {{collect($attendance_first)->where('monthdesc','april')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            {{collect($attendance_first)->sum('days')}}
        </td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Days present</td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','june')->count()>0)
                {{collect($attendance_first)->where('monthdesc','june')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','july')->count()>0)
                {{collect($attendance_first)->where('monthdesc','july')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','august')->count()>0)
                {{collect($attendance_first)->where('monthdesc','august')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','september')->count()>0)
                {{collect($attendance_first)->where('monthdesc','september')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','october')->count()>0)
                {{collect($attendance_first)->where('monthdesc','october')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','november')->count()>0)
                {{collect($attendance_first)->where('monthdesc','november')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','december')->count()>0)
                {{collect($attendance_first)->where('monthdesc','december')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','january')->count()>0)
                {{collect($attendance_first)->where('monthdesc','january')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','february')->count()>0)
                {{collect($attendance_first)->where('monthdesc','february')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','march')->count()>0)
                {{collect($attendance_first)->where('monthdesc','march')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_first)->where('monthdesc','april')->count()>0)
                {{collect($attendance_first)->where('monthdesc','april')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            {{collect($attendance_first)->sum('present')}}
        </td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Class Adviser:</td>
        <td colspan="12" style="text-align: center; font-weight: bold;">&nbsp;{{$info_first->teachername}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="3">Total number of years in school:</td>
        <td colspan="3" style="text-align: center;">7</td>
        <td colspan="4" style="text-align: center;">Locked of units:</td>
        <td colspan="4"></td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="4">To be classified as:</td>
        <td colspan="2" style="text-align: center;">GRADE 8</td>
        <td colspan="6" style="text-align: center;">Has advance units in:</td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <th colspan="14" style="font-weight: bold; text-align: center; font-size: 18px; background-color: #ffff00;">&nbsp;</th>
    </tr>
    @php
        $grades_second = collect($records)->first()[1]->grades;
        $attendance_second = collect($records)->first()[1]->attendance;
        $info_second   = collect($records)->first()[1];

        if(count($attendance_second)>0)
        {
            foreach($attendance_second as $eachatt)
            {
                $eachatt->monthdesc  = strtolower($eachatt->monthdesc);

                if($eachatt->monthdesc == 'may' || $eachatt->monthdesc == 'june' || $eachatt->monthdesc == 'july' || $eachatt->monthdesc == 'april')
                {
                    $eachatt->days = 0;
                    $eachatt->present = 0;
                }
            }
        }
    @endphp
    <tr style="font-size: 11px;">
        <td>Classified as:</td>
        <td colspan="3" style="text-align: center;">{{$info_second->levelname}} - {{$info_second->sectionname}}</td>
        <td colspan="5" style="text-align: center;">{{$info_second->schoolname}}</td>
        <td colspan="2">School Year:</td>
        <td colspan="3" style="text-align: center;">{{$info_second->sydesc}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td>Curriculum</td>
        <td rowspan="2" colspan="4" style="width: 25%; text-align: center;">SUBJECT</td>
        <td colspan="4" style="text-align: center;">Class Standing</td>
        <td colspan="2" style="text-align: center;">Final</td>
        <td colspan="2" style="text-align: center;">Action</td>
        <td>Credits</td>
    </tr>
    <tr style="font-size: 11px;">
        <td>Year</td>
        <td style="text-align: center;">1</td>
        <td style="text-align: center;">2</td>
        <td style="text-align: center;">3</td>
        <td style="text-align: center;">4</td>
        <td colspan="2" style="text-align: center;">Grade</td>
        <td colspan="2" style="text-align: center;">Taken</td>
        <td>Earned</td>
    </tr>
    @if(count($grades_second) == 0)
        @for($x = 0; $x <= 8; $x++)
            <tr style="font-size: 11px;">
                <td style="text-align: center;">GRADE 8</td>
                <td colspan="4"></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td colspan="2"></td>
                <td colspan="2"></td>
                <td></td>
            </tr>
        @endfor
    @else
        @foreach($grades_second as $secondgrade)
            @php
                $indent = 0;
                if(isset($secondgrade->mapeh))
                {
                    $indent = $secondgrade->mapeh;
                }
                if(isset($secondgrade->inTLE))
                {
                    if($indent == 0)
                    {
                        $indent = $secondgrade->inTLE;
                    }
                }
                if(isset($secondgrade->inMAPEH))
                {
                    if($indent == 0)
                    {
                        $indent = $secondgrade->inMAPEH;
                    }
                }
                $secondgrade->credits = null;
                $secondgrade->indent = $indent;
            @endphp
            <tr style="font-size: 11px;">
                <td style="text-align: center;">GRADE 8</td>
                <td colspan="4">&nbsp;@if($indent > 0)&nbsp;&nbsp;&nbsp;@endif{{$secondgrade->subjdesc}}</td>
                <td style="text-align: center;">{{$secondgrade->q1}}</td>
                <td style="text-align: center;">{{$secondgrade->q2}}</td>
                <td style="text-align: center;">{{$secondgrade->q3}}</td>
                <td style="text-align: center;">{{$secondgrade->q4}}</td>
                <td colspan="2" style="text-align: center;">{{$secondgrade->finalrating}}</td>
                <td colspan="2" style="text-align: center;">{{$secondgrade->remarks}}</td>
                <td style="text-align: center;">{{$secondgrade->credits}}</td>
            </tr>
        @endforeach
    @endif
    <tr>
        <td></td>
        <td colspan="4" style="font-size: 15px; font-weight: bold;">AVERAGING</td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_second)->where('inSF9')->avg('q1')) > 0)
                {{number_format(collect($grades_second)->where('inSF9')->avg('q1'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_second)->where('inSF9')->avg('q2')) > 0)
                {{number_format(collect($grades_second)->where('inSF9')->avg('q2'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_second)->where('inSF9')->avg('q3')) > 0)
                {{number_format(collect($grades_second)->where('inSF9')->avg('q3'))}}
            @endif
        </td>
        <td style="text-align: center; font-size: 11px;">
            @if(number_format(collect($grades_second)->where('inSF9')->avg('q4')) > 0)
                {{number_format(collect($grades_second)->where('inSF9')->avg('q4'))}}
            @endif
        </td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td></td>
    </tr>
    <tr style="font-size: 11px; text-align: center;">
        <td></td>
        <td style="width: 15%;"></td>
        <td>June</td>
        <td>July</td>
        <td>Aug.</td>
        <td>Sept.</td>
        <td>Oct.</td>
        <td>Nov.</td>
        <td>Dec.</td>
        <td>Jan.</td>
        <td>Feb.</td>
        <td>Mar.</td>
        <td colspan="2">Total</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Days of school</td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','june')->count()>0)
                {{collect($attendance_second)->where('monthdesc','june')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','july')->count()>0)
                {{collect($attendance_second)->where('monthdesc','july')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','august')->count()>0)
                {{collect($attendance_second)->where('monthdesc','august')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','september')->count()>0)
                {{collect($attendance_second)->where('monthdesc','september')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','october')->count()>0)
                {{collect($attendance_second)->where('monthdesc','october')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','november')->count()>0)
                {{collect($attendance_second)->where('monthdesc','november')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','december')->count()>0)
                {{collect($attendance_second)->where('monthdesc','december')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','january')->count()>0)
                {{collect($attendance_second)->where('monthdesc','january')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','february')->count()>0)
                {{collect($attendance_second)->where('monthdesc','february')->first()->days}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','march')->count()>0)
                {{collect($attendance_second)->where('monthdesc','march')->first()->days}}
            @endif
        </td>
        <td colspan="2" style="text-align: center;">
            {{collect($attendance_second)->sum('days')}}
        </td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Days present</td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','june')->count()>0)
                {{collect($attendance_second)->where('monthdesc','june')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','july')->count()>0)
                {{collect($attendance_second)->where('monthdesc','july')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','august')->count()>0)
                {{collect($attendance_second)->where('monthdesc','august')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','september')->count()>0)
                {{collect($attendance_second)->where('monthdesc','september')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','october')->count()>0)
                {{collect($attendance_second)->where('monthdesc','october')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','november')->count()>0)
                {{collect($attendance_second)->where('monthdesc','november')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','december')->count()>0)
                {{collect($attendance_second)->where('monthdesc','december')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','january')->count()>0)
                {{collect($attendance_second)->where('monthdesc','january')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','february')->count()>0)
                {{collect($attendance_second)->where('monthdesc','february')->first()->present}}
            @endif
        </td>
        <td style="text-align: center;">
            @if(collect($attendance_second)->where('monthdesc','march')->count()>0)
                {{collect($attendance_second)->where('monthdesc','march')->first()->present}}
            @endif
        </td>
        <td colspan="2" style="text-align: center;">
            {{collect($attendance_second)->sum('present')}}
        </td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="2">Class Adviser:</td>
        <td colspan="12" style="text-align: center; font-weight: bold;">&nbsp; {{$info_second->teachername}}</td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="3">Total number of years in school:</td>
        <td colspan="3" style="text-align: center;">8</td>
        <td colspan="4" style="text-align: center;">Locked of units:</td>
        <td colspan="4"></td>
    </tr>
    <tr style="font-size: 11px;">
        <td colspan="4">To be classified as:</td>
        <td colspan="2" style="text-align: center;">GRADE 9 </td>
        <td colspan="6" style="text-align: center;">Has advance units in:</td>
        <td colspan="2"></td>
    </tr>
</table>
<div style="page-break-before: always;">&nbsp;</div>
<table style="width: 100%;" border="1">        
    @foreach(collect($records[1])->values() as $key => $eachrecord)
        @php
            $grades = $eachrecord->grades;
            $attendance = $eachrecord->attendance;
            $info   = $eachrecord;

            if(count($attendance)>0)
            {
                foreach($attendance as $eachatt)
                {
                    $eachatt->monthdesc  = strtolower($eachatt->monthdesc);

                    if($eachatt->monthdesc == 'may' || $eachatt->monthdesc == 'june' || $eachatt->monthdesc == 'july')
                    {
                        $eachatt->days = 0;
                        $eachatt->present = 0;
                    }
                }
            }
        @endphp
        <tr style="font-size: 11px;">
            <td style="width: 15%;">Classified as:</td>
            <td colspan="3" style="text-align: center;">{{$info->levelname}} - {{$info->sectionname}}</td>
            <td colspan="5" style="text-align: center;">{{$info->schoolname}}</td>
            <td colspan="2">School Year:</td>
            <td colspan="3" style="text-align: center;">{{$info->sydesc}}</td>
        </tr>
        <tr style="font-size: 11px;">
            <td>Curriculum</td>
            <td rowspan="2" colspan="4" style="width: 25%; text-align: center;">SUBJECT</td>
            <td colspan="4" style="text-align: center;">Class Standing</td>
            <td colspan="2" style="text-align: center;">Final</td>
            <td colspan="2" style="text-align: center;">Action</td>
            <td style="width: 10%;">Credits</td>
        </tr>
        <tr style="font-size: 11px;">
            <td>Year</td>
            <td style="text-align: center;">1</td>
            <td style="text-align: center;">2</td>
            <td style="text-align: center;">3</td>
            <td style="text-align: center;">4</td>
            <td colspan="2" style="text-align: center;">Grade</td>
            <td colspan="2" style="text-align: center;">Taken</td>
            <td>Earned</td>
        </tr>
        @if(count($grades) == 0)
            @for($x = 0; $x <= 8; $x++)
                <tr style="font-size: 11px;">
                    <td style="text-align: center;">@if($key == 0)GRADE 9 @else GRADE 10 @endif</td>
                    <td colspan="4"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="2"></td>
                    <td colspan="2"></td>
                    <td></td>
                </tr>
            @endfor
        @else
            @foreach($grades as $firstgrade)
                @php
                    $indent = 0;
                    if(isset($firstgrade->mapeh))
                    {
                        $indent = $firstgrade->mapeh;
                    }
                    if(isset($firstgrade->inTLE))
                    {
                        if($indent == 0)
                        {
                            $indent = $firstgrade->inTLE;
                        }
                    }
                    if(isset($firstgrade->inMAPEH))
                    {
                        if($indent == 0)
                        {
                            $indent = $firstgrade->inMAPEH;
                        }
                    }
                    $firstgrade->credits = null;
                    $firstgrade->indent = $indent;
                @endphp
                <tr style="font-size: 11px;">
                    <td style="text-align: center;">@if($key == 0)GRADE 9 @else GRADE 10 @endif</td>
                    <td colspan="4">&nbsp;@if($indent > 0)&nbsp;&nbsp;&nbsp;@endif{{$firstgrade->subjdesc}}</td>
                    <td style="text-align: center;">{{$firstgrade->q1}}</td>
                    <td style="text-align: center;">{{$firstgrade->q2}}</td>
                    <td style="text-align: center;">{{$firstgrade->q3}}</td>
                    <td style="text-align: center;">{{$firstgrade->q4}}</td>
                    <td colspan="2" style="text-align: center;">{{$firstgrade->finalrating}}</td>
                    <td colspan="2" style="text-align: center;">{{$firstgrade->remarks}}</td>
                    <td style="text-align: center;">{{$firstgrade->credits}}</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td></td>
            <td colspan="4" style="font-size: 15px; font-weight: bold;">AVERAGING</td>
            <td style="text-align: center; font-size: 11px;">
                @if(number_format(collect($grades)->where('inSF9')->avg('q1')) > 0)
                    {{number_format(collect($grades)->where('inSF9')->avg('q1'))}}
                @endif
            </td>
            <td style="text-align: center; font-size: 11px;">
                @if(number_format(collect($grades)->where('inSF9')->avg('q2')) > 0)
                    {{number_format(collect($grades)->where('inSF9')->avg('q2'))}}
                @endif
            </td>
            <td style="text-align: center; font-size: 11px;">
                @if(number_format(collect($grades)->where('inSF9')->avg('q3')) > 0)
                    {{number_format(collect($grades)->where('inSF9')->avg('q3'))}}
                @endif
            </td>
            <td style="text-align: center; font-size: 11px;">
                @if(number_format(collect($grades)->where('inSF9')->avg('q4')) > 0)
                    {{number_format(collect($grades)->where('inSF9')->avg('q4'))}}
                @endif
            </td>
            <td colspan="2" style="text-align: center; font-size: 11px;"></td>
            <td colspan="2" style="text-align: center; font-size: 11px;"></td>
            <td style="text-align: center; font-size: 11px;"></td>
        </tr>
        <tr style="font-size: 11px; text-align: center;">
            <td></td>
            <td style="width: 15%;"></td>
            <td>June</td>
            <td>July</td>
            <td>Aug.</td>
            <td>Sept.</td>
            <td>Oct.</td>
            <td>Nov.</td>
            <td>Dec.</td>
            <td>Jan.</td>
            <td>Feb.</td>
            <td>Mar.</td>
            <td>Apr.</td>
            <td>Total</td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="2">Days of school</td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','june')->count()>0)
                    {{collect($attendance)->where('monthdesc','june')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','july')->count()>0)
                    {{collect($attendance)->where('monthdesc','july')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','august')->count()>0)
                    {{collect($attendance)->where('monthdesc','august')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','september')->count()>0)
                    {{collect($attendance)->where('monthdesc','september')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','october')->count()>0)
                    {{collect($attendance)->where('monthdesc','october')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','november')->count()>0)
                    {{collect($attendance)->where('monthdesc','november')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','december')->count()>0)
                    {{collect($attendance)->where('monthdesc','december')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','january')->count()>0)
                    {{collect($attendance)->where('monthdesc','january')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','february')->count()>0)
                    {{collect($attendance)->where('monthdesc','february')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','march')->count()>0)
                    {{collect($attendance)->where('monthdesc','march')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','april')->count()>0)
                    {{collect($attendance)->where('monthdesc','april')->first()->days}}
                @endif
            </td>
            <td style="text-align: center;">
                {{collect($attendance)->sum('days')}}
            </td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="2">Days present</td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','june')->count()>0)
                    {{collect($attendance)->where('monthdesc','june')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','july')->count()>0)
                    {{collect($attendance)->where('monthdesc','july')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','august')->count()>0)
                    {{collect($attendance)->where('monthdesc','august')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','september')->count()>0)
                    {{collect($attendance)->where('monthdesc','september')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','october')->count()>0)
                    {{collect($attendance)->where('monthdesc','october')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','november')->count()>0)
                    {{collect($attendance)->where('monthdesc','november')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','december')->count()>0)
                    {{collect($attendance)->where('monthdesc','december')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','january')->count()>0)
                    {{collect($attendance)->where('monthdesc','january')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','february')->count()>0)
                    {{collect($attendance)->where('monthdesc','february')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','march')->count()>0)
                    {{collect($attendance)->where('monthdesc','march')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                @if(collect($attendance)->where('monthdesc','april')->count()>0)
                    {{collect($attendance)->where('monthdesc','april')->first()->present}}
                @endif
            </td>
            <td style="text-align: center;">
                {{collect($attendance)->sum('present')}}
            </td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="2">Class Adviser:</td>
            <td colspan="12" style="text-align: center; font-weight: bold;">&nbsp;{{$info->teachername}}</td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="3">Total number of years in school:</td>
            <td colspan="3" style="text-align: center;">@if($key == 0)9 @else 10 @endif</td>
            <td colspan="4" style="text-align: center;">Locked of units:</td>
            <td colspan="4"></td>
        </tr>
        <tr style="font-size: 11px;">
            <td colspan="4">To be classified as:</td>
            <td colspan="2" style="text-align: center;">@if($key == 0)GRADE 10 @else GRADE 11 @endif</td>
            <td colspan="6" style="text-align: center;">Has advance units in:</td>
            <td colspan="2"></td>
        </tr>
        @if($key == 0)
        <tr>
            <th colspan="14" style="font-weight: bold; text-align: center; font-size: 18px; background-color: #ffff00;">&nbsp;</th>
        </tr>
        @endif
    @endforeach
</table>
<div style="width: 100%; text-align: center; color: blue; font-size: 13px; font-weight: bold;">STANDARD INTELLIGENCE TEST TAKEN</div>
<table style="width: 100%; table-layout: fixed; font-size: 12px;" border="1">
    <tr>
        <th rowspan="2" style="width: 40%;">NAME AND FORM OF TESTS</th>
        <th>Date</th>
        <th>Score</th>
        <th>Percentile</th>
    </tr>
    <tr>
        <th>Taken</th>
        <th>Received</th>
        <th>Rank</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<div style="width: 100%; text-align: center; color: blue; font-size: 13px; font-weight: bold;">SUMMARY OF CREDITS ACHIEVED TOWARDS GRADUATION/ COMPLETION</div>
<table style="width: 100%; table-layout: fixed; font-size: 12px;" border="1">
    <tr>
        <th colspan="3">1ST CURR. YEAR</th>
        <th colspan="3">2ND  CURR. YEAR</th>
        <th colspan="3">3RD   CURR. YEAR</th>
        <th colspan="3">4TH   CURR. YEAR</th>
    </tr>
    <tr>
        <th style="width: 12%;">Subjects</th>
        <th>Year</th>
        <th>CE</th>
        <th style="width: 12%;">Subjects</th>
        <th>Year</th>
        <th>CE</th>
        <th style="width: 12%;">Subjects</th>
        <th>Year</th>
        <th>CE</th>
        <th style="width: 12%;">Subjects</th>
        <th>Year</th>
        <th>CE</th>
    </tr>
    <tr>
        <td>Filipino</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>English</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Math</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Science & Tech.</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>Aral. Pan.</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>EsP.</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>TLE</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>MAPEH</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>CL</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</table>
<br/>
<table style="width: 100%; color: blue; font-size: 12px;">
    <tr>
        <td style="width: 40%; text-align: right;">REMARKS:</td>
        <td style="width: 30%; border-bottom: 1px solid blue;">&nbsp;{{$footer->purpose}}</td>
        <td></td>
    </tr>
</table>
<table style="width: 100%; font-size: 12px; text-align: center;">
    <tr>
        <td>I  HEREBY CERTIFY that this is the true records of <span style="font-weight: bold;">{{$studinfo->lastname}}, {{$studinfo->firstname}} {{$studinfo->suffix}} {{$studinfo->middlename}} </span> and  she has no </td>
    </tr>
    <tr>
        <td>accountabilities to any of the properties of the school. </td>
    </tr>
</table>
<br/>
<table style="width: 100%; table-layout: fixed; font-size: 13px;">
    <tr>
        <td style="width: 25%; text-align: center; font-style: italic; font-weight: bold;">Not Valid</td>
        <td style="width: 40%;"></td>
        <td></td>
    </tr>
    <tr>
        <td style=" width: 25%; text-align: center; font-style: italic; font-weight: bold;">Without Seal</td>
        <td></td>
        <td style="border-bottom: 1px solid black; text-align: center;">{{$footer->recordsincharge}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="text-align: center;"><small>School Registrar/Secretary</small></td>
    </tr>
</table>