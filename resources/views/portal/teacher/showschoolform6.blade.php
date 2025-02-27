@extends('teacher.layouts.app')

@section('content')
<style>
    .summaryTable th, .summaryTable td{
        font-size: 11px;
        border:1px solid black !important;
        text-align: center;
        /* table-layout: fixed; */
        padding: 3px;
    }
    #header, #header th, #header td{
        font-size: 12px;
        border: none !important;
        /* border:1px solid black !important; */
        padding:2px;
        text-align: right;
    }
    input[type=text]{
        text-align: center;
        width:100%;
    }
    .leftAlign{
        text-align: left !important;
    }
    #female{
        width: 5%;
    }
    .guidelines{
        font-size: 11px;
    }
</style>
<style type="text/css" media="print">
    @page { size: landscape; }
  </style>
@php
$grade_1 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 1')   
            ->where('deleted',0)
            ->get(); 
$grade_2 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 2')   
            ->where('deleted',0)
            ->get();
$grade_3 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 3')   
            ->where('deleted',0)
            ->get();
$grade_4 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 4')   
            ->where('deleted',0)
            ->get();
$grade_5 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 5')   
            ->where('deleted',0)
            ->get();
$grade_6 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 6')   
            ->where('deleted',0)
            ->get();
$grade_7 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 7')   
            ->where('deleted',0)
            ->get();
$grade_8 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 8')   
            ->where('deleted',0)
            ->get();
$grade_9 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 9')   
            ->where('deleted',0)
            ->get();
$grade_10 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 10')   
            ->where('deleted',0)
            ->get();
$grade_11 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 11')   
            ->where('deleted',0)
            ->get();
$grade_12 = DB::table('gradelevel')
            ->select('id')
            ->where('levelname','GRADE 12')   
            ->where('deleted',0)
            ->get();  
@endphp
<form action="/schoolForm_6/preview" method="GET" target="_blank">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-10">
                <h4>School Form 6 (SF6) Summarized Report on Promotion and Level of Proficiency</h4>
                <em>This replaces Form 20</em>
            </div>
            <div class="col-sm-2">
                <ol class="breadcrumb float-sm-right">
                    <button type="submit" class="btn btn-success btn-sm text-white"  target="_blank">
                        <i class="fa fa-upload"></i>
                        Print
                    </button>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <table id="header" class="table table-bordered">
                        <thead>
                            <tr>
                                <th rowspan="2" style="padding:10px;">
                                    <center><img src="{{asset('assets/images/department_of_Education.png')}}" alt="school" width="80px"></center>
                                </th>
                                <th width="10%">School ID</th>
                                <th><input type="text" value="{{$school[0]->schoolid}}" readonly/></th>
                                <th style="padding:0px 2px 0px 20px;">Region</th>
                                <th><input type="text" value="{{$school[0]->region}}" readonly/></th>
                                <th>Division</th>
                                <th colspan="2"><input type="text" value="{{$school[0]->division}}" readonly/></th>
                                <th></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th>School Name</th>
                                <th colspan="3"><input type="text" value="{{$school[0]->schoolname}}" readonly/></th>
                                <th>District</th>
                                <th><input type="text" value="{{$school[0]->district}}" readonly/></th>
                                <th style="padding:0px 2px 0px 40px;">School Year</th>
                                <th><input type="text" value="{{$sy}}" readonly/></th>
                            </tr>
                        </thead>
                    </table>
                    <br>
                    <table class="summaryTable" width="100%" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th rowspan="2" style="width:10%">SUMMARY TABLE</th>
                                <th colspan="3">GRADE 1/GRADE 7</th>
                                <th colspan="3">GRADE 2/GRADE 8</th>
                                <th colspan="3">GRADE 3/GRADE 9</th>
                                <th colspan="3">GRADE 4/GRADE 10</th>
                                <th colspan="3">GRADE 5/GRADE 11</th>
                                <th colspan="3">GRADE 6/GRADE 12</th>
                                <th colspan="3">TOTAL</th>
                            </tr>
                            <tr>
                                <th id="male" >MALE</th>
                                <th id="female" >FEMALE</th>
                                <th id="total" >TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th class="leftAlign">PROMOTED</th>
                                <td>
                                    @php
                                        $promotedMale1 = 0;   
                                        $promotedMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[2]=="PROMOTED")
                                                    @php
                                                    $promotedMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="PROMOTED")
                                                    @php
                                                    $promotedMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale1 != 0 || $promotedMale7 != 0)
                                        {{$promotedMale1}} / {{$promotedMale7}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale1 = 0;   
                                        $promotedFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale1 != 0 || $promotedFemale7 != 0)
                                        {{$promotedFemale1}} / {{$promotedFemale7}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale1 + $promotedFemale1) != 0 || ($promotedMale7 + $promotedFemale7) != 0)
                                        {{$promotedMale1 + $promotedFemale1}} / {{$promotedMale7 + $promotedFemale7}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMale2 = 0;   
                                        $promotedMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale2 != 0 || $promotedMale8 != 0)
                                        {{$promotedMale2}} / {{$promotedMale8}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale2 = 0;   
                                        $promotedFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale2 != 0 || $promotedFemale8 != 0)
                                        {{$promotedFemale2}} / {{$promotedFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale2 + $promotedFemale2) != 0 || ($promotedMale8 + $promotedFemale8) != 0)
                                        {{$promotedMale2 + $promotedFemale2}} / {{$promotedMale8 + $promotedFemale8}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMale3 = 0;   
                                        $promotedMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale3 != 0 || $promotedMale9 != 0)
                                        {{$promotedMale3}} / {{$promotedMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale3 = 0;   
                                        $promotedFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale3 != 0 || $promotedFemale9 != 0)
                                        {{$promotedFemale3}} / {{$promotedFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale3 + $promotedFemale3) != 0 || ($promotedMale9 + $promotedFemale9) != 0)
                                        {{$promotedMale3 + $promotedFemale3}} / {{$promotedMale9 + $promotedFemale9}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMale4 = 0;   
                                        $promotedMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale4 != 0 || $promotedMale10 != 0)
                                        {{$promotedMale4}} / {{$promotedMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale4 = 0;   
                                        $promotedFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale4 != 0 || $promotedFemale10 != 0)
                                        {{$promotedFemale4}} / {{$promotedFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale4 + $promotedFemale4) != 0 || ($promotedMale10 + $promotedFemale10) != 0)
                                        {{$promotedMale4 + $promotedFemale4}} / {{$promotedMale10 + $promotedFemale10}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMale5 = 0;   
                                        $promotedMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale5 != 0 || $promotedMale11 != 0)
                                        {{$promotedMale5}} / {{$promotedMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale5 = 0;   
                                        $promotedFemale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale5 != 0 || $promotedFemale11 != 0)
                                        {{$promotedFemale5}} / {{$promotedFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale5 + $promotedFemale5) != 0 || ($promotedMale11 + $promotedFemale11) != 0)
                                        {{$promotedMale5 + $promotedFemale5}} / {{$promotedMale11 + $promotedFemale11}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMale6 = 0;   
                                        $promotedMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedMale6 != 0 || $promotedMale12 != 0)
                                        {{$promotedMale6}} / {{$promotedMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedFemale6 = 0;   
                                        $promotedFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="PROMOTED")

                                                    @php
                                                    $promotedFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($promotedFemale6 != 0 || $promotedFemale12 != 0)
                                        {{$promotedFemale6}} / {{$promotedFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($promotedMale6 + $promotedFemale6) != 0 || ($promotedMale12 + $promotedFemale12) != 0)
                                        {{$promotedMale6 + $promotedFemale6}} / {{$promotedMale12 + $promotedFemale12}}
                                    @else

                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMaleTotal = $promotedMale1 + $promotedMale2 + $promotedMale3 + $promotedMale4 + $promotedMale5 + $promotedMale6 + $promotedMale7 + $promotedMale8 + $promotedMale9 + $promotedMale10 + $promotedMale11 + $promotedMale12;
                                    @endphp
                                    {{$promotedMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $promotedFemaleTotal = $promotedFemale1 + $promotedFemale2 + $promotedFemale3 + $promotedFemale4 + $promotedFemale5 + $promotedFemale6 + $promotedFemale7 + $promotedFemale8 + $promotedFemale9 + $promotedFemale10 + $promotedFemale11 + $promotedFemale12;
                                    @endphp
                                    {{$promotedFemaleTotal}}
                                </td>
                                <td>
                                    {{$promotedMaleTotal + $promotedFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th class="leftAlign">IRREGULAR (Grade 7 onwards only)</th>
                                <td>
                                    @php
                                        $promotedMaleSenior7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior7+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior7 != 0)
                                                        {{$promotedMaleSenior7}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php 
                                        $promotedFemaleSenior7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                        $promotedFemaleSenior7+=1
                                                    @endphp
                                                    @if ($promotedFemale7 != 0)
                                                        {{$promotedFemale7}}
                                                    @else
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior7 + $promotedFemaleSenior7) != 0)
                                        {{$promotedMaleSenior7 + $promotedFemaleSenior7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMaleSenior8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior8+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior8 != 0)
                                                        {{$promotedMaleSenior8}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php  
                                        $promotedFemaleSenior8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedFemaleSenior8+=1
                                                    @endphp
                                                    @if ($promotedFemaleSenior8 != 0)
                                                        {{$promotedFemaleSenior8}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior8 + $promotedFemaleSenior8) != 0)
                                        {{$promotedMaleSenior8 + $promotedFemaleSenior8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php  
                                        $promotedMaleSenior9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior9+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior9 != 0)
                                                        {{$promotedMaleSenior9}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $promotedFemaleSenior9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedFemaleSenior9+=1
                                                    @endphp
                                                    @if ($promotedFemaleSenior9 != 0)
                                                        {{$promotedFemaleSenior9}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior9 + $promotedFemaleSenior9) != 0)
                                        {{$promotedMaleSenior9 + $promotedFemaleSenior9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php  
                                        $promotedMaleSenior10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior10+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior10 != 0)
                                                        {{$promotedMaleSenior10}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php  
                                        $promotedFemaleSenior10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="IRREGULAR")

                                                    @php
                                                    $promotedFemaleSenior10+=1
                                                    @endphp
                                                    @if ($promotedFemaleSenior10 != 0)
                                                        {{$promotedFemaleSenior10}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior10 + $promotedFemaleSenior10) != 0)
                                        {{$promotedMaleSenior10 + $promotedFemaleSenior10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMaleSenior11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior11+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior11 != 0)
                                                        {{$promotedMaleSenior11}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php  
                                        $promotedFemaleSenior11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedFemaleSenior11+=1
                                                    @endphp
                                                    @if ($promotedFemaleSenior11 != 0)
                                                        {{$promotedFemaleSenior11}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior11 + $promotedFemaleSenior11) != 0)
                                        {{$promotedMaleSenior11 + $promotedFemaleSenior11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php 
                                        $promotedMaleSenior12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedMaleSenior12+=1
                                                    @endphp
                                                    @if ($promotedMaleSenior12 != 0)
                                                        {{$promotedMaleSenior12}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $promotedFemaleSenior12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="IRREGULAR")
                                                    @php
                                                    $promotedFemaleSenior12+=1
                                                    @endphp
                                                    @if ($promotedFemaleSenior12 != 0)
                                                        {{$promotedFemaleSenior12}}
                                                    @else
                                                    @endif
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @if (($promotedMaleSenior12 + $promotedFemaleSenior12) != 0)
                                        {{$promotedMaleSenior12 + $promotedFemaleSenior12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $promotedMaleSeniorTotal = $promotedMaleSenior7 + $promotedMaleSenior8 + $promotedMaleSenior9 + $promotedMaleSenior10 + $promotedMaleSenior11 + $promotedMaleSenior12;
                                    @endphp
                                    {{$promotedMaleSeniorTotal}}
                                </td>
                                <td>
                                    @php
                                        $promotedFemaleSeniorTotal = $promotedFemaleSenior7 + $promotedFemaleSenior8 + $promotedFemaleSenior9 + $promotedFemaleSenior10 + $promotedFemaleSenior11 + $promotedFemaleSenior12;
                                    @endphp
                                    {{$promotedFemaleSeniorTotal}}
                                </td>
                                <td>
                                    {{$promotedMaleSeniorTotal + $promotedFemaleSeniorTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th class="leftAlign">RETAINED</th>
                                <td>
                                    @php
                                        $retainedMale1 = 0;   
                                        $retainedMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale1 != 0 || $retainedMale7 != 0)
                                        {{$retainedMale1}} / {{$retainedMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale1 = 0;   
                                        $retainedFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale1 != 0 || $retainedFemale7 != 0)
                                        {{$retainedFemale1}} / {{$retainedFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale1 + $retainedFemale1) != 0 || ($retainedMale7 + $retainedFemale7) != 0)
                                        {{$retainedMale1 + $retainedFemale1}} / {{$retainedMale7 + $retainedFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMale2 = 0;   
                                        $retainedMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale2 != 0 || $retainedMale8 != 0)
                                        {{$retainedMale2}} / {{$retainedMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale2 = 0;   
                                        $retainedFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale2 != 0 || $retainedFemale8 != 0)
                                        {{$retainedFemale2}} / {{$retainedFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale2 + $retainedFemale2) != 0 || ($retainedMale8 + $retainedFemale8) != 0)
                                        {{$retainedMale2 + $retainedFemale2}} / {{$retainedMale8 + $retainedFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMale3 = 0;   
                                        $retainedMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale3 != 0 || $retainedMale9 != 0)
                                        {{$retainedMale3}} / {{$retainedMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale3 = 0;   
                                        $retainedFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale3 != 0 || $retainedFemale9 != 0)
                                        {{$retainedFemale3}} / {{$retainedFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale3 + $retainedFemale3) != 0 || ($retainedMale9 + $retainedFemale9) != 0)
                                        {{$retainedMale3 + $retainedFemale3}} / {{$retainedMale9 + $retainedFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMale4 = 0;   
                                        $retainedMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale4 != 0 || $retainedMale10 != 0)
                                        {{$retainedMale4}} / {{$retainedMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale4 = 0;   
                                        $retainedFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale4 != 0 || $retainedFemale10 != 0)
                                        {{$retainedFemale4}} / {{$retainedFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale4 + $retainedFemale4) != 0 || ($retainedMale10 + $retainedFemale10) != 0)
                                        {{$retainedMale4 + $retainedFemale4}} / {{$retainedMale10 + $retainedFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMale5 = 0;   
                                        $retainedMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale5 != 0 || $retainedMale11 != 0)
                                        {{$retainedMale5}} / {{$retainedMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale5 = 0;   
                                        $retainedFemale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale5 != 0 || $retainedFemale11 != 0)
                                        {{$retainedFemale5}} / {{$retainedFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale5 + $retainedFemale5) != 0 || ($retainedMale11 + $retainedFemale11) != 0)
                                        {{$retainedMale5 + $retainedFemale5}} / {{$retainedMale11 + $retainedFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMale6 = 0;   
                                        $retainedMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedMale6 != 0 || $retainedMale12 != 0)
                                        {{$retainedMale6}} / {{$retainedMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedFemale6 = 0;   
                                        $retainedFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[2]=="RETAINED")

                                                    @php
                                                    $retainedFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($retainedFemale6 != 0 || $retainedFemale12 != 0)
                                        {{$retainedFemale6}} / {{$retainedFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if (($retainedMale6 + $retainedFemale6) != 0 || ($retainedMale12 + $retainedFemale12) != 0)
                                        {{$retainedMale6 + $retainedFemale6}} / {{$retainedMale12 + $retainedFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $retainedMaleTotal = $retainedMale1 + $retainedMale2 + $retainedMale3 + $retainedMale4 + $retainedMale5 + $retainedMale6 + $retainedMale7 + $retainedMale8 + $retainedMale9 + $retainedMale10 + $retainedMale11 + $retainedMale12;
                                    @endphp
                                    {{$retainedMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $retainedFemaleTotal = $retainedFemale1 + $retainedFemale2 + $retainedFemale3 + $retainedFemale4 + $retainedFemale5 + $retainedFemale6 + $retainedFemale7 + $retainedFemale8 + $retainedFemale9 + $retainedFemale10 + $retainedFemale11 + $retainedFemale12;
                                    @endphp
                                    {{$retainedFemaleTotal}}
                                </td>
                                <td>
                                    {{$retainedMaleTotal + $retainedFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th>LEVEL OF POFICIENCY (K to 12 Only)</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                                <th>MALE</th>
                                <th>FEMALE</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <th class="leftAlign">BEGINNING (B: 74% and below)</th>
                                <td>
                                    @php
                                        $bMale1 = 0;   
                                        $bMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale1 != 0 || $bMale7 !=0)
                                        {{$bMale1}} / {{$bMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale1 = 0;   
                                        $bFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale1 != 0 || $bFemale7 !=0)
                                        {{$bFemale1}} / {{$bFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                @if(($bMale1 + $bFemale1) != 0 || ($bMale7 + $bFemale7) != 0)
                                        {{$bMale1 + $bFemale1}} / {{$bMale7 + $bFemale7}}
                                @else
                                @endif
                                </td>
                                <td>
                                    @php
                                        $bMale2 = 0;   
                                        $bMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale2 != 0 || $bMale8 !=0)
                                        {{$bMale2}} / {{$bMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale2 = 0;   
                                        $bFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale2 != 0 || $bFemale8 !=0)
                                        {{$bFemale2}} / {{$bFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($bMale2 + $bFemale2) != 0 || ($bMale8 + $bFemale8) != 0)
                                            {{$bMale2 + $bFemale2}} / {{$bMale8 + $bFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bMale3 = 0;   
                                        $bMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale3 != 0 || $bMale9 !=0)
                                        {{$bMale3}} / {{$bMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale3 = 0;   
                                        $bFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale3 != 0 || $bFemale9 !=0)
                                        {{$bFemale3}} / {{$bFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($bMale3 + $bFemale3) != 0 || ($bMale9 + $bFemale9) != 0)
                                            {{$bMale3 + $bFemale3}} / {{$bMale9 + $bFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bMale4 = 0;   
                                        $bMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale4 != 0 || $bMale10 !=0)
                                        {{$bMale4}} / {{$bMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale4 = 0;   
                                        $bFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale4 != 0 || $bFemale10 !=0)
                                        {{$bFemale4}} / {{$bFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($bMale4 + $bFemale4) != 0 || ($bMale10 + $bFemale10) != 0)
                                            {{$bMale4 + $bFemale4}} / {{$bMale10 + $bFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bMale5 = 0;   
                                        $bMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale5 != 0 || $bMale11 !=0)
                                        {{$bMale5}} / {{$bMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale5 = 0;   
                                        $bFemale11= 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale5 != 0 || $bFemale11 !=0)
                                        {{$bFemale5}} / {{$bFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($bMale5 + $bFemale5) != 0 || ($bMale11 + $bFemale11) != 0)
                                            {{$bMale5 + $bFemale5}} / {{$bMale11 + $bFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bMale6 = 0;   
                                        $bMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bMale6 != 0 || $bMale12 !=0)
                                        {{$bMale6}} / {{$bMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bFemale6 = 0;   
                                        $bFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final<=74)
                                                    @php
                                                    $bFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final<=74)

                                                    @php
                                                    $bFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($bFemale6 != 0 || $bFemale12 !=0)
                                        {{$bFemale6}} / {{$bFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($bMale6 + $bFemale6) != 0 || ($bMale12 + $bFemale12) != 0)
                                            {{$bMale6 + $bFemale6}} / {{$bMale12 + $bFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $bMaleTotal = $bMale1 + $bMale2 + $bMale3 + $bMale4 + $bMale5 + $bMale6 + $bMale7 + $bMale8 + $bMale9 + $bMale10 + $bMale11 + $bMale12;
                                    @endphp
                                    {{$bMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $bFemaleTotal = $bFemale1 + $bFemale2 + $bFemale3 + $bFemale4 + $bFemale5 + $bFemale6 + $bFemale7 + $bFemale8 + $bFemale9 + $bFemale10 + $bFemale11 + $bFemale12;
                                    @endphp
                                    {{$bFemaleTotal}}
                                </td>
                                <td>
                                    {{$bMaleTotal + $bFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th class="leftAlign">DEVELOPING (D: 75%-79%)</th>
                                <td>
                                    @php
                                        $dMale1 = 0;   
                                        $dMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale1 != 0 || $dMale7 !=0)
                                        {{$dMale1}} / {{$dMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale1 = 0;   
                                        $dFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale1 != 0 || $dFemale7 !=0)
                                        {{$dFemale1}} / {{$dFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale1 + $dFemale1) != 0 || ($dMale7 + $dFemale7) != 0)
                                            {{$dMale1 + $dFemale1}} / {{$dMale7 + $dFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMale2 = 0;   
                                        $dMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale2 != 0 || $dMale8 !=0)
                                        {{$dMale2}} / {{$dMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale2 = 0;   
                                        $dFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale2 != 0 || $dFemale8 !=0)
                                        {{$dFemale2}} / {{$dFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale2 + $dFemale2) != 0 || ($dMale8 + $dFemale8) != 0)
                                            {{$dMale2 + $dFemale2}} / {{$dMale8 + $dFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMale3 = 0;   
                                        $dMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale3 != 0 || $dMale9 !=0)
                                        {{$dMale3}} / {{$dMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale3 = 0;   
                                        $dFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale3 != 0 || $dFemale9 !=0)
                                        {{$dFemale3}} / {{$dFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale3 + $dFemale3) != 0 || ($dMale9 + $dFemale9) != 0)
                                            {{$dMale3 + $dFemale3}} / {{$dMale9 + $dFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMale4 = 0;   
                                        $dMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale4 != 0 || $dMale10 !=0)
                                        {{$dMale4}} / {{$dMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale4 = 0;   
                                        $dFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale4 != 0 || $dFemale10 !=0)
                                        {{$dFemale4}} / {{$dFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale4 + $dFemale4) != 0 || ($dMale10 + $dFemale10) != 0)
                                            {{$dMale4 + $dFemale4}} / {{$dMale10 + $dFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMale5 = 0;   
                                        $dMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale5 != 0 || $dMale11 !=0)
                                        {{$dMale5}} / {{$dMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale5 = 0;   
                                        $dFemale11= 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale5 != 0 || $dFemale11 !=0)
                                        {{$dFemale5}} / {{$dFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale5 + $dFemale5) != 0 || ($dMale11 + $dFemale11) != 0)
                                            {{$dMale5 + $dFemale5}} / {{$dMale11 + $dFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMale6 = 0;   
                                        $dMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dMale6 != 0 || $dMale12 !=0)
                                        {{$dMale6}} / {{$dMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dFemale6 = 0;   
                                        $dFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)
                                                    @php
                                                    $dFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=75 && $student[0]->Final<=79.99)

                                                    @php
                                                    $dFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($dFemale6 != 0 || $dFemale12 !=0)
                                        {{$dFemale6}} / {{$dFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($dMale6 + $dFemale6) != 0 || ($dMale12 + $dFemale12) != 0)
                                            {{$dMale6 + $dFemale6}} / {{$dMale12 + $dFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $dMaleTotal = $dMale1 + $dMale2 + $dMale3 + $dMale4 + $dMale5 + $dMale6 + $dMale7 + $dMale8 + $dMale9 + $dMale10 + $dMale11 + $dMale12;
                                    @endphp
                                    {{$dMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $dFemaleTotal = $dFemale1 + $dFemale2 + $dFemale3 + $dFemale4 + $dFemale5 + $dFemale6 + $dFemale7 + $dFemale8 + $dFemale9 + $dFemale10 + $dFemale11 + $dFemale12;
                                    @endphp
                                    {{$dFemaleTotal}}
                                </td>
                                <td>
                                    {{$dMaleTotal + $dFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th>APPROACHING PROFICIENCY (AP: 80%-84%)</th>
                                <td>
                                    @php
                                        $apMale1 = 0;   
                                        $apMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale1 != 0 || $apMale7 !=0)
                                        {{$apMale1}} / {{$apMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale1 = 0;   
                                        $apFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale1 != 0 || $apFemale7 !=0)
                                        {{$apFemale1}} / {{$apFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale1 + $apFemale1) != 0 || ($apMale7 + $apFemale7) != 0)
                                            {{$apMale1 + $apFemale1}} / {{$apMale7 + $apFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMale2 = 0;   
                                        $apMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale2 != 0 || $apMale8 !=0)
                                        {{$apMale2}} / {{$apMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale2 = 0;   
                                        $apFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale2 != 0 || $apFemale8 !=0)
                                        {{$apFemale2}} / {{$apFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale2 + $apFemale2) != 0 || ($apMale8 + $apFemale8) != 0)
                                            {{$apMale2 + $apFemale2}} / {{$apMale8 + $apFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMale3 = 0;   
                                        $apMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale3 != 0 || $apMale9 !=0)
                                        {{$apMale3}} / {{$apMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale3 = 0;   
                                        $apFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale3 != 0 || $apFemale9 !=0)
                                        {{$apFemale3}} / {{$apFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale3 + $apFemale3) != 0 || ($apMale9 + $apFemale9) != 0)
                                            {{$apMale3 + $apFemale3}} / {{$apMale9 + $apFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMale4 = 0;   
                                        $apMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale4 != 0 || $apMale10 !=0)
                                        {{$apMale4}} / {{$apMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale4 = 0;   
                                        $apFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale4 != 0 || $apFemale10 !=0)
                                        {{$apFemale4}} / {{$apFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale4 + $apFemale4) != 0 || ($apMale10 + $apFemale10) != 0)
                                            {{$apMale4 + $apFemale4}} / {{$apMale10 + $apFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMale5 = 0;   
                                        $apMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale5 != 0 || $apMale11 !=0)
                                        {{$apMale5}} / {{$apMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale5 = 0;   
                                        $apFemale11= 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale5 != 0 || $apFemale11 !=0)
                                        {{$apFemale5}} / {{$apFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale5 + $apFemale5) != 0 || ($apMale11 + $apFemale11) != 0)
                                            {{$apMale5 + $apFemale5}} / {{$apMale11 + $apFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMale6 = 0;   
                                        $apMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apMale6 != 0 || $apMale12 !=0)
                                        {{$apMale6}} / {{$apMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apFemale6 = 0;   
                                        $apFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)
                                                    @php
                                                    $apFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=80 && $student[0]->Final<=84.99)

                                                    @php
                                                    $apFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($apFemale6 != 0 || $apFemale12 !=0)
                                        {{$apFemale6}} / {{$apFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($apMale6 + $apFemale6) != 0 || ($apMale12 + $apFemale12) != 0)
                                            {{$apMale6 + $apFemale6}} / {{$apMale12 + $apFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $apMaleTotal = $apMale1 + $apMale2 + $apMale3 + $apMale4 + $apMale5 + $apMale6 + $apMale7 + $apMale8 + $apMale9 + $apMale10 + $apMale11 + $apMale12;
                                    @endphp
                                    {{$apMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $apFemaleTotal = $apFemale1 + $apFemale2 + $apFemale3 + $apFemale4 + $apFemale5 + $apFemale6 + $apFemale7 + $apFemale8 + $apFemale9 + $apFemale10 + $apFemale11 + $apFemale12;
                                    @endphp
                                    {{$apFemaleTotal}}
                                </td>
                                <td>
                                    {{$apMaleTotal + $apFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th class="leftAlign">PROFICIENT (P: 85%-89%)</th>
                                <td>
                                    @php
                                        $pMale1 = 0;   
                                        $pMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale1 != 0 || $pMale7 !=0)
                                        {{$pMale1}} / {{$pMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale1 = 0;   
                                        $pFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale1 != 0 || $pFemale7 !=0)
                                        {{$pFemale1}} / {{$pFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale1 + $pFemale1) != 0 || ($pMale7 + $pFemale7) != 0)
                                            {{$pMale1 + $pFemale1}} / {{$pMale7 + $pFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMale2 = 0;   
                                        $pMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale2 != 0 || $pMale8 !=0)
                                        {{$pMale2}} / {{$pMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale2 = 0;   
                                        $pFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale2 != 0 || $pFemale8 !=0)
                                        {{$pFemale2}} / {{$pFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale2 + $pFemale2) != 0 || ($pMale8 + $pFemale8) != 0)
                                            {{$pMale2 + $pFemale2}} / {{$pMale8 + $pFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMale3 = 0;   
                                        $pMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale3 != 0 || $pMale9 !=0)
                                        {{$pMale3}} / {{$pMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale3 = 0;   
                                        $pFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale3 != 0 || $pFemale9 !=0)
                                        {{$pFemale3}} / {{$pFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale3 + $pFemale3) != 0 || ($pMale9 + $pFemale9) != 0)
                                            {{$pMale3 + $pFemale3}} / {{$pMale9 + $pFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMale4 = 0;   
                                        $pMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale4 != 0 || $pMale10 !=0)
                                        {{$pMale4}} / {{$pMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale4 = 0;   
                                        $pFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale4 != 0 || $pFemale10 !=0)
                                        {{$pFemale4}} / {{$pFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale4 + $pFemale4) != 0 || ($pMale10 + $pFemale10) != 0)
                                            {{$pMale4 + $pFemale4}} / {{$pMale10 + $pFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMale5 = 0;   
                                        $pMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale5 != 0 || $pMale11 !=0)
                                        {{$pMale5}} / {{$pMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale5 = 0;   
                                        $pFemale11= 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale5 != 0 || $pFemale11 !=0)
                                        {{$pFemale5}} / {{$pFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale5 + $pFemale5) != 0 || ($pMale11 + $pFemale11) != 0)
                                            {{$pMale5 + $pFemale5}} / {{$pMale11 + $pFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMale6 = 0;   
                                        $pMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pMale6 != 0 || $pMale12 !=0)
                                        {{$pMale6}} / {{$pMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pFemale6 = 0;   
                                        $pFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)
                                                    @php
                                                    $pFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=85 && $student[0]->Final<=89.99)

                                                    @php
                                                    $pFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($pFemale6 != 0 || $pFemale12 !=0)
                                        {{$pFemale6}} / {{$pFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($pMale6 + $pFemale6) != 0 || ($pMale12 + $pFemale12) != 0)
                                            {{$pMale6 + $pFemale6}} / {{$pMale12 + $pFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pMaleTotal = $pMale1 + $pMale2 + $pMale3 + $pMale4 + $pMale5 + $pMale6 + $pMale7 + $pMale8 + $pMale9 + $pMale10 + $pMale11 + $pMale12;
                                    @endphp
                                    {{$pMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $pFemaleTotal = $pFemale1 + $pFemale2 + $pFemale3 + $pFemale4 + $pFemale5 + $pFemale6 + $pFemale7 + $pFemale8 + $pFemale9 + $pFemale10 + $pFemale11 + $pFemale12;
                                    @endphp
                                    {{$pFemaleTotal}}
                                </td>
                                <td>
                                    {{$pMaleTotal + $pFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th class="leftAlign">ADVANCED (A: 90% and above)</th>
                                <td>
                                    @php
                                        $aMale1 = 0;   
                                        $aMale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale1 != 0 || $aMale7 !=0)
                                        {{$aMale1}} / {{$aMale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale1 = 0;   
                                        $aFemale7 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_1[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale1+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_7[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale7+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale1 != 0 || $aFemale7 !=0)
                                        {{$aFemale1}} / {{$aFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale1 + $aFemale1) != 0 || ($aMale7 + $aFemale7) != 0)
                                            {{$aMale1 + $aFemale1}} / {{$aMale7 + $aFemale7}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMale2 = 0;   
                                        $aMale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale2 != 0 || $aMale8 !=0)
                                        {{$aMale2}} / {{$aMale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale2 = 0;   
                                        $aFemale8 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_2[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale2+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_8[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale8+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale2 != 0 || $aFemale8 !=0)
                                        {{$aFemale2}} / {{$aFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale2 + $aFemale2) != 0 || ($aMale8 + $aFemale8) != 0)
                                            {{$aMale2 + $aFemale2}} / {{$aMale8 + $aFemale8}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMale3 = 0;   
                                        $aMale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale3 != 0 || $aMale9 !=0)
                                        {{$aMale3}} / {{$aMale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale3 = 0;   
                                        $aFemale9 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_3[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale3+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_9[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale9+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale3 != 0 || $aFemale9 !=0)
                                        {{$aFemale3}} / {{$aFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale3 + $aFemale3) != 0 || ($aMale9 + $aFemale9) != 0)
                                            {{$aMale3 + $aFemale3}} / {{$aMale9 + $aFemale9}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMale4 = 0;   
                                        $aMale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale4 != 0 || $aMale10 !=0)
                                        {{$aMale4}} / {{$aMale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale4 = 0;   
                                        $aFemale10 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_4[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale4+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_10[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale10+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale4 != 0 || $aFemale10 !=0)
                                        {{$aFemale4}} / {{$aFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale4 + $aFemale4) != 0 || ($aMale10 + $aFemale10) != 0)
                                            {{$aMale4 + $aFemale4}} / {{$aMale10 + $aFemale10}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMale5 = 0;   
                                        $aMale11 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale5 != 0 || $aMale11 !=0)
                                        {{$aMale5}} / {{$aMale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale5 = 0;   
                                        $aFemale11= 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_5[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale5+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_11[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale11+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale5 != 0 || $aFemale11 !=0)
                                        {{$aFemale5}} / {{$aFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale5 + $aFemale5) != 0 || ($aMale11 + $aFemale11) != 0)
                                            {{$aMale5 + $aFemale5}} / {{$aMale11 + $aFemale11}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMale6 = 0;   
                                        $aMale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="MALE" || ($student[1]->gender)=="Male")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aMale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aMale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aMale6 != 0 || $aMale12 !=0)
                                        {{$aMale6}} / {{$aMale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aFemale6 = 0;   
                                        $aFemale12 = 0;   
                                    @endphp
                                    @foreach ($students as $student)
                                        @if(($student[1]->gender)=="FEMALE" || ($student[1]->gender)=="Female")
                                            @if(($student[1]->levelid)==$grade_6[0]->id)
                                                @if($student[0]->Final>=90)
                                                    @php
                                                    $aFemale6+=1
                                                    @endphp
                                                @endif
                                            @elseif(($student[1]->levelid)==$grade_12[0]->id)
                                                @if($student[0]->Final>=90)

                                                    @php
                                                    $aFemale12+=1
                                                    @endphp
                                                @endif
                                                @endif
                                        @endif
                                    @endforeach
                                    @if ($aFemale6 != 0 || $aFemale12 !=0)
                                        {{$aFemale6}} / {{$aFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @if(($aMale6 + $aFemale6) != 0 || ($aMale12 + $aFemale12) != 0)
                                            {{$aMale6 + $aFemale6}} / {{$aMale12 + $aFemale12}}
                                    @else
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $aMaleTotal = $aMale1 + $aMale2 + $aMale3 + $aMale4 + $aMale5 + $aMale6 + $aMale7 + $aMale8 + $aMale9 + $aMale10 + $aMale11 + $aMale12;
                                    @endphp
                                    {{$aMaleTotal}}
                                </td>
                                <td>
                                    @php
                                        $aFemaleTotal = $aFemale1 + $aFemale2 + $aFemale3 + $aFemale4 + $aFemale5 + $aFemale6 + $aFemale7 + $aFemale8 + $aFemale9 + $aFemale10 + $aFemale11 + $aFemale12;
                                    @endphp
                                    {{$aFemaleTotal}}
                                </td>
                                <td>
                                    {{$aMaleTotal + $aFemaleTotal}}
                                </td>
                            </tr>
                            <tr>
                                <th>TOTAL</th>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <br>                            
                    <table class="table" style="border: 1px solid black !important;table-layout:fixed;">
                        <tr>
                            <th style="border: hidden !important;width:15%;padding:5px"><small>Pepared and Submitted:</small></th>
                            <td style="border-top: hidden; border-bottom: 1px solid black;border-left:hidden; border-right: hidden; !important;padding:5px"><center><small>{{$principalname[0]->lastname}}, {{$principalname[0]->firstname}} {{$principalname[0]->middlename[0]}} {{$principalname[0]->suffix}}</small></center></td>
                            <td style="padding:5px;border:hidden;text-align:right"><small>Reviewed & Validated by:</small></td>
                            <td style="padding:0px;border-bottom: 1px solid black;"><small><input type="text" name="divisionRep" style="text-transform: uppercase" required/></small></td>
                            <td style="padding:5px;border:hidden;text-align:right;width:10%"><small>Noted by:</small></td>
                            <td style="padding:0px;border-right:hidden;border-bottom: 1px solid black;"><input type="text" name="divisionSup" style="text-transform: uppercase" required/></td>
                        </tr>
                        <tr>
                            <th style="border:hidden;padding:0px;"></th>
                            <td style="border-bottom:hidden;padding:0px;"><center><small>SCHOOL HEAD</small></center></td>
                            <td style="border:hidden;padding:0px;"></td>
                            <td style="border-bottom:hidden;padding:0px;"><center><small>DIVISION REPRESENTATIVE</small></center></td>
                            <td style="border-bottom:hidden;padding:0px;"></td>
                            <td style="border-bottom:hidden;border-right:hidden;padding:0px;"><center><small>SCHOOLS DIVISION SUPERINTENDENT</small></center></td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <div class="col-md-12 guidelines">
                    <p><strong>GUIDELINES:</strong></p>
                    <ol>
                        <li>After receiving and validating the report for Promotion submitted by the class adviser, the School Head shall compute the grade level total and school total.</li>
                        <li>This report together with the copy of Report for Promotion submitted by the class adviser shall be fowarded to the Division Office by the end of the school year.</li>
                        <li>The Report on Promotion per grade level is reflected in the End of School Year Report of GESP/GSSP.</li>
                        <li>Protocols of validation & submission is under the discretion of the Schools Division Superintendent.</li>
                    </ol>
                </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
@endsection