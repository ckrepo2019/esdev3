<head>
    <title>Student Masterlist</title>
</head>

<style>
    html{
        /*text-transform: uppercase;*/
        
    font-family: Arial, Helvetica, sans-serif;
    }
.logo{
    width: 100%;
    table-layout: fixed;
}
.header{
    width: 100%;
}
.studentsMale th, .studentsMale td, .studentsFemale th, .studentsFemale td{
    border: 1px solid black;
}
.logo td ,
.header td {
    /* border: 1px solid black; */
}
.studentsMale{
    font-size: 11px;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    border-spacing: 0;
}
.studentsFemale{
    font-size: 11px;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    border-spacing: 0;
}
.studentsFemale td, .studentsMale td{
    border-top: hidden;
}
.studentsFemale th, .studentsMale th{
    text-align: center;
}
.total{
    text-align: left;
    font-size: 11px;
    width: 20%;
    table-layout: fixed;
    font-family: Arial, Helvetica, sans-serif;
    border-spacing: 0;
}
.total td{
    border: 1px solid black;
    text-align: center;
}
.clear:after {
    clear: both;
    content: "";
    display: table;
    border: 1px solid black;
}
table {
    border-collapse: collapse;
}
@media print {
   button.download {
      display:none;
   }
}  footer {
                position: fixed; 
                bottom: -50px; 
                left: 0px; 
                right: 0px;
                height: 100px; 

                /** Extra personal styles **/
                color: black;
                text-align: left;
                line-height: 20px;
            }
            @page{
                margin: 20px 30px
            }
</style>
@php

$signatories = DB::table('signatory')
        ->where('form','report_masterlist')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->where('acadprogid',$acadprogid)
        ->get();

if(count($signatories) == 0)
{
    $signatories = DB::table('signatory')
        ->where('form','report_masterlist')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->where('acadprogid',0)
        ->get();

    if(count($signatories)>0)
    {
        if(collect($signatories)->where('levelid', $levelid)->count() == 0)
        {
            $signatories = collect($signatories)->where('levelid',0)->values();
        }else{
            $signatories = collect($signatories)->where('levelid', $levelid)->values();
        }
    }

    
}else{
    if(collect($signatories)->where('levelid', $levelid)->count() == 0)
    {
        $signatories = collect($signatories)->where('levelid',0)->values();
    }else{
        $signatories = collect($signatories)->where('levelid', $levelid)->values();
    }
}
@endphp

<table class="logo">
    <tr>
        @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
        <td width="25%" style="text-align: right;"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td>
            <center>
                {{-- <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
                <br> --}}
                <strong>{{$schoolinfo[0]->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
                {{-- <br>
                <br> --}}
                
            </center>
        </td>
        <td width="25%"></td>
        @else
        <td width="15%"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td>
            {{-- <center> --}}
                {{-- <span style="font-size: 11px;">{{$schoolinfo[0]->division}}</span>
                <br> --}}
                <strong>{{$schoolinfo[0]->schoolname}}</strong>
                <br>
                <span style="font-size: 11px;">{{$schoolinfo[0]->address}}</span>
                {{-- <br>
                <br> --}}
                
            {{-- </center> --}}
        </td>
        <td width="15%"></td>
        @endif
    </tr>
</table>
<table class="header">
    <tr>
        <td width="15%"></td>
        <td style="width: 25%;">
            <span style="font-size: 11px;"><strong>School Year: </strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$schoolyear[0]->sydesc}}</u></span>
        </td>
    </tr>
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Grade Level & Section:</strong></span>
        </td>
    @if($sectionid > 0)
        <td>
            <span style="font-size: 11px;"><u>{{$data[0]->gradelevelname}} - {{$data[0]->sectionname}}</u></span>
        </td>
    @else
        <td>
            <span style="font-size: 11px;"><u>{{$data[0]->gradelevelname}} - ALL SECTIONS</u></span>
        </td>
    @endif
    </tr>
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) != 'hccsi')
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Adviser:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$teacher}}</u></span>
        </td>
    </tr>
    @endif
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Room:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u>{{$roomname}}</u></span>
        </td>
    </tr>
    {{-- @if($academicprogram == 'seniorhighschool' || $academicprogram == 'senior high school')
    <tr>
        <td width="15%"></td>
        <td>
            <span style="font-size: 11px;"><strong>Track & Strand:</strong></span>
        </td>
        <td>
            <span style="font-size: 11px;"><u style=" text-align: justify !important;">{{$data[0]->trackname}} - {{$data[0]->strandname}} ({{$data[0]->strandcode}})</u></span>
        </td>
    </tr>
    @endif --}}
</table>
<br>
<span style="font-size: 12px;"><center><strong>List of Students</strong></center></span>
@if($esc == 1)
<span style="font-size: 12px;"><center><strong>(ESC Grantees)</strong></center></span>
@endif
<br>
@if($acadprogid == 5 || $acadprogid == 6)
    @php
        $strands = collect($data)->groupBy('strandcode')->all();
    @endphp
    @foreach($strands as $eachkey => $eachstrand)
        <div style="font-size: 15px; background-color: #ddd;"><center><strong>{{$eachkey}}</strong></center></div>
        @if(collect($eachstrand)->where('gender','male')->count() == 0 || collect($eachstrand)->where('gender','female')->count() == 0)
            @php
                $width = '100%';   
            @endphp
        @elseif(collect($eachstrand)->where('gender','male')->count() != 0 && collect($eachstrand)->where('gender','female')->count() != 0)
            @php
                $width = '50%';   
            @endphp
        @endif
        @php
            $male = 0;
            $female = 0;
            $maxnum = max(array(collect($eachstrand)->where('gender','male')->count(),collect($eachstrand)->where('gender','female')->count()));

            $collectionmale = collect($eachstrand)->where('gender','male')->values();
            $collectionfemale = collect($eachstrand)->where('gender','female')->values();
        @endphp
        <table style="width:100%; font-size: 10.5px; table-layout: fixed;" border="1">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>MALE</th>
                    <th width="5%">No.</th>
                    <th>FEMALE</th>
                </tr>
            </thead>
            @for($x = 0; $x < $maxnum; $x++)
                <tr>
                    <td style="text-align: center;">@if(isset($collectionmale[$x])){{($x+1)}}@endif</td>
                    <td style="padding-left: 10px;">
                        @if(isset($collectionmale[$x]))
                            @if($format == 'lastname_first')
                                {{ucwords(mb_strtolower($collectionmale[$x]->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($collectionmale[$x]->student_firstname))}} {{isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0].'.')) : ''}} {{$collectionmale[$x]->student_suffix}}
                            @else
                            {{ucwords(strtolower($collectionmale[$x]->student_firstname))}} {{isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($collectionmale[$x]->student_lastname,'UTF-8'))}} {{$collectionmale[$x]->student_suffix}}
                            @endif
                        @endif
                    </td>
                    <td style="text-align: center;">@if(isset($collectionfemale[$x])){{($x+1)}}@endif</td>
                    <td style="padding-left: 10px;">
                        @if(isset($collectionfemale[$x]))
                            @if($format == 'lastname_first')
                                {{ucwords(mb_strtolower($collectionfemale[$x]->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($collectionfemale[$x]->student_firstname))}} {{isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0].'.')) : ''}} {{$collectionfemale[$x]->student_suffix}}
                            @else
                            {{ucwords(strtolower($collectionfemale[$x]->student_firstname))}} {{isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($collectionfemale[$x]->student_lastname,'UTF-8'))}} {{$collectionfemale[$x]->student_suffix}}
                            @endif
                        @endif
                    </td>
                </tr>
            @endfor
            
        </table>
        {{-- @if(collect($eachstrand)->where('gender','male')->count() != 0)
            <table class="studentsMale" style="width:{{$width}}; @if(collect($eachstrand)->where('gender','female')->count()>0) float: left; @endif">
                <tr>
                    <th width="10%">No.</th>
                    <th>MALE</th>
                </tr>
                @foreach (collect($eachstrand)->where('gender','male')->values() as $student)
                    @php
                        $male+=1;
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{$male}}</td>
                        <td><span style="padding-left: 10px;">
                            
                            @if($format == 'lastname_first')
                            {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(strtolower($student->student_suffix))}}
                            @else
                            {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}} {{ucwords(strtolower($student->student_suffix))}}
                            @endif
                        </span></td>
                    </tr>
                @endforeach
            </table>
        @endif

        @if(collect($eachstrand)->where('gender','female')->count() != 0)
        <table class="studentsFemale" style="width:{{$width}}; @if(collect($eachstrand)->where('gender','male')->count()>0) float: right; @endif">
            <tr>
                <th width="10%">No.</th>
                <th>FEMALE</th>
            </tr>
            @foreach (collect($eachstrand)->where('gender','female')->values() as $student)
                @php
                    $female+=1;    
                @endphp
                <tr>
                    <td style="text-align: center;">{{$female}}</td>
                    <td><span style="padding-left: 10px;">
                        @if($format == 'lastname_first')
                        {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(strtolower($student->student_suffix))}}
                        @else
                        {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}} {{ucwords(strtolower($student->student_suffix))}}
                        @endif
                    </span></td>
                </tr>
            @endforeach
        </table>
        @endif --}}
        <div style="clear: both;"></div>
        <br>
        {{-- <table class="total">
            <tr>
                <td style="text-align: left;">
                    <strong>&nbsp;&nbsp;Male</strong>
                </td>
                <td>
                    <strong>{{$male}}</strong>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <strong>&nbsp;&nbsp;Female</strong>
                </td>
                <td>
                    <strong>{{$female}}</strong>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <strong>&nbsp;&nbsp;Total</strong>
                </td>
                <td>
                    <strong>{{$male + $female}}</strong>
                </td>
            </tr>
        </table>
        <br/> --}}
        {{-- <br/> --}}
    @endforeach
    
    <table class="total">
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Male</strong>
            </td>
            <td>
                <strong>{{collect($data)->where('gender','male')->count()}}</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Female</strong>
            </td>
            <td>
                <strong>{{collect($data)->where('gender','female')->count()}}</strong>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">
                <strong>&nbsp;&nbsp;Total</strong>
            </td>
            <td>
                <strong>{{collect($data)->count()}}</strong>
            </td>
        </tr>
    </table>
@else
    
    @if($genderCount['maleCount'] == 0 || $genderCount['femaleCount'] == 0)
        @php
            $width = '100%';   
        @endphp
    @elseif($genderCount['maleCount'] != 0 && $genderCount['femaleCount'] != 0)
        @php
        $width = '50%';   
            // if($sectionid > 0)
            // {
            // $width = '50%';   
            // }else{
            // $width = '100%';   
            // }
        @endphp
    @endif
    @php
        $male = 0;
        $female = 0;
        $maxnum = max(array($genderCount['maleCount'],$genderCount['femaleCount']));

        $collectionmale = collect($data)->where('gender','male')->values();
        $collectionfemale = collect($data)->where('gender','female')->values();
    @endphp
    {{-- {{collect($collectionmale)}} --}}
        <table style="width:100%; font-size: 10.5px; table-layout: fixed;" border="1">
            <thead>
                <tr>
                    <th width="5%">No.</th>
                    <th>MALE</th>
                    <th width="5%">No.</th>
                    <th>FEMALE</th>
                </tr>
            </thead>
            @for($x = 0; $x < $maxnum; $x++)
                <tr>
                    <td style="text-align: center;">@if(isset($collectionmale[$x])){{($x+1)}}@endif</td>
                    <td style="padding-left: 10px;">
                        @if(isset($collectionmale[$x]))
                            @if($format == 'lastname_first')
                                {{ucwords(mb_strtolower($collectionmale[$x]->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($collectionmale[$x]->student_firstname))}} {{isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0].'.')) : ''}} {{$collectionmale[$x]->student_suffix}}
                            @else
                            {{ucwords(strtolower($collectionmale[$x]->student_firstname))}} {{isset($collectionmale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionmale[$x]->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($collectionmale[$x]->student_lastname,'UTF-8'))}} {{$collectionmale[$x]->student_suffix}}
                            @endif
                        @endif
                    </td>
                    <td style="text-align: center;">@if(isset($collectionfemale[$x])){{($x+1)}}@endif</td>
                    <td style="padding-left: 10px;">
                        @if(isset($collectionfemale[$x]))
                            @if($format == 'lastname_first')
                                {{ucwords(mb_strtolower($collectionfemale[$x]->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($collectionfemale[$x]->student_firstname))}} {{isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0].'.')) : ''}} {{$collectionfemale[$x]->student_suffix}}
                            @else
                            {{ucwords(strtolower($collectionfemale[$x]->student_firstname))}} {{isset($collectionfemale[$x]->student_middlename[0]) ? ucwords(strtolower($collectionfemale[$x]->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($collectionfemale[$x]->student_lastname,'UTF-8'))}} {{$collectionfemale[$x]->student_suffix}}
                            @endif
                        @endif
                    </td>
                </tr>
            @endfor
            
        </table>
    {{-- @if($genderCount['maleCount'] != 0)
        <table class="studentsMale" style="width:{{$width}}; page-break-inside: always; float: left; ">
            <tr>
                <th width="10%">No.</th>
                <th>MALE</th>
            </tr>
            @foreach ($data as $student)
                @if (strtoupper($student->student_gender)=="MALE")
                @php
                    $male+=1;
                @endphp
                    <tr>
                        <td style="text-align: center;">{{$male}}</td>
                        <td><span style="padding-left: 10px;">
                            @if($format == 'lastname_first')
                            {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(strtolower($student->student_suffix))}}
                            @else
                            {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}} {{ucwords(strtolower($student->student_suffix))}}
                            @endif</span></td>
                    </tr>
                @endif
            @endforeach
        </table>
    @endif

    @if($genderCount['femaleCount'] != 0)
    <table class="studentsFemale" style="width:{{$width}}; page-break-inside: avoid;  float: right; ">
        <tr>
            <th width="10%">No.</th>
            <th>FEMALE</th>
        </tr>
        @foreach ($data as $student)
            @if (strtoupper($student->student_gender)=="FEMALE")
            @php
                $female+=1;    
            @endphp
                <tr>
                    <td style="text-align: center;">{{$female}}</td>
                    <td><span style="padding-left: 10px;">
                        @if($format == 'lastname_first')
                        {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}}, {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(strtolower($student->student_suffix))}}
                        @else
                        {{ucwords(strtolower($student->student_firstname))}} {{isset($student->student_middlename[0]) ? ucwords(strtolower($student->student_middlename[0].'.')) : ''}} {{ucwords(mb_strtolower($student->student_lastname,'UTF-8'))}} {{ucwords(strtolower($student->student_suffix))}}
                        @endif</span>
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
    @endif --}}
    <div style="clear: both;"></div>
    <br>
    <table class="total">
        <tr>
            <td>
                <strong>Male = {{count($collectionmale)}}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Female = {{count($collectionfemale)}}</strong>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Total = {{count($collectionmale) + count($collectionfemale)}}</strong>
            </td>
        </tr>
    </table>
@endif
<br/>
@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
<table style="width: 100%; font-size: 12px; text-transform: unset; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="text-align: right;">Certified and verified under oath to be true and correct:</td>
        <td></td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td style="font-weight: bold;">&nbsp;{{$teacher ?? null}} &nbsp;</td>
        <td style="font-weight: bold;">CHRISTINE J. CASILAGAN&nbsp;</td>
    </tr>
    <tr>
        <td>
            @if($sectionid > 0)Adviser @endif</td>
        <td>School Registrar</td>
    </tr>
</table>
@else
@if($academicprogram != 'seniorhighschool')
    @if($teacher!=null)
        <div class="label" style="display:inline-block;
        background-color:White;
        width: auto; text-align:center; font-size: 12px;">
            <div class="label-text" style=" float:left; text-align: center; line-height: 30px; vertical-align: center; white-space: nowrap; overflow: hidden;">
                <span style="text-align:center;border-bottom: 1px solid black;">&nbsp;{{$teacher}}</span>
                {{-- <div style="line-height: 5px;">&nbsp;</div> --}}
                <br/>
                <sup style="text-align:center">Class Adviser</sup> 
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
        <br/>
    @endif
@endif
@if(count($signatories)>0)
<table style="width: 100%; table-layout: fixed; font-size: 12px;">
    <tr>
        @foreach($signatories as $signatory)
        <td style="">{{$signatory->title}}</td>
        <td></td>
        @endforeach
    </tr>
    <tr>
        @foreach($signatories as $signatory)
        <td style="">&nbsp;</td>
        <td></td>
        @endforeach
    </tr>
    <tr>
        @foreach($signatories as $signatory)
        <td style="border-bottom: 1px solid black; text-align: center;">{{$signatory->name}}</td>
        <td></td>
        @endforeach
    </tr>
    <tr>
        @foreach($signatories as $signatory)
        <td style="text-align: center;">{{$signatory->description}}</td>
        <td></td>
        @endforeach
    </tr>
</table>
    {{-- @foreach($signatories as $signatory)
    <div class="label" style="display:inline-block;
    background-color:White;
    width: auto; text-align:center; font-size: 12px;">
        <div class="label-text" style=" float:left; text-align: center; line-height: 30px; vertical-align: center; white-space: nowrap; overflow: hidden;">
            <span style="float: left;">&nbsp;{{$signatory->title}}</span>
            <br/>
            <br/>
            <span style="text-align:center;border-bottom: 1px solid black;">&nbsp;{{$signatory->name}}</span>
            <br/>
            <sup style="text-align:center">{{$signatory->description}}</sup> 
        </div>
    </div>
    @endforeach --}}
@endif
@endif
{{-- <p class="total">Male = <u>{{$countMale}}</u><br>Female = <u>{{$countFemale}}</u><br>Total = <u>{{$countMale + $countFemale}}</u></p> --}}
