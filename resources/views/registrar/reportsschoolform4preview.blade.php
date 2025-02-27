@extends('registrar.layouts.app')

@section('content')
    <style>
        .table th, .table td      { font-size: 12px; border:1px solid black !important; /* text-align: center; table-layout: fixed; */ padding: 3px; table-layout: fixed !important;}
        /* #header, #header th, #header td         { font-size: 12px; border: none !important; border:1px solid black !important; padding:2px; text-align: right; } */
        /* input[type=text]                        { text-align: center; width:100%; } */
        /* .leftAlign                              { text-align: left !important; } */
        /* #female                                 { width: 5%; } */
        /* .guidelines                             { font-size: 11px; } */
        .table-hover tbody tr:hover td, .table-hover tbody tr:hover th {
        background-color: gold;
        }
    </style>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item active">School Form 4</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
    <div class="row">
        <div class="col-12">
            <div class="card card-default color-palette-box">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fa fa-file"></i>
                        <strong>School Form 4 (SF4) Monthly Learner's Movement and Attendance</strong>
                    </h3>
                    <br>
                    <small><em>(This replaces Form 3 & STS Form 4-Absenteeism and Dropout Profile)</em></small>
                    
                    <button class="btn btn-sm btn-primary btnprint text-white float-right">
                            <i class="fa fa-upload"></i>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="main-card mb-3 card ">
                <div class="card-body">
                    <div class="row">
                        <form action="" method="get" name="formsubmit">
                            
                            
                            <input type="hidden" name="selectedschoolyear" value="{{$selectedschoolyear->id}}"/>
                            <input type="hidden" name="selectedmonth" value="{{$selectedmonth}}"/>
                        </form>
                        <label>School Year &nbsp;&nbsp;&nbsp;</label>
                        <select class="form-control form-control-sm col-md-3" name="selectedschoolyear">
                            @foreach($schoolyears as $schoolyear)
                                <option value="{{$schoolyear->id}}" {{$schoolyear->id == $selectedschoolyear->id  ? 'selected' : ''}}>{{$schoolyear->sydesc}}</option>
                            @endforeach
                        </select>&nbsp;&nbsp;&nbsp;
                        <label>Month &nbsp;&nbsp;&nbsp;</label>
                        <select class="form-control form-control-sm col-md-3" name="selectedmonth">
                            @foreach($months as $month)
                                <option value="{{$month->id}}" {{$month->id == $selectedmonth  ? 'selected' : ''}}>{{$month->month}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br/>
                    <div class="col-md-12" style="overflow-x: scroll">
                        <table class="mb-0 table-bordered table table-hover">
                            <thead>
                                <tr class="text-center ">
                                    <td style="width:50px !important" class="t-sm align-middle text-center" rowspan="3">GRADE / YEAR LEVEL</td> 
                                    <td style=" width:80px !important;" class="align-middle" rowspan="3">SECTION</td> 
                                    <td style="width:130px !important" class="t-md align-middle" rowspan="3" >NAME OF ADVISER</td> 
                                   
                                    <td style="width:120px !important; " class="t-md align-middle" rowspan="2" colspan="3">REGISTERED<br>LEARNERS<br>(As of End of the Month)</td>
                        
                                    <th style="width:200px !important; " colspan="6">ATTENDANCE</th>
                                    <th style="width:320px !important; " colspan="9">DROPPED OUT</th>
                                    <th style="width:315px !important; " colspan="9">TRANSFERRED OUT</th>
                                    <th style="width:315px !important; " colspan="9">TRANSFERRED IN</th>
                                </tr>
                                <tr class="text-center">
                                    <td class="align-middle"  colspan="3">Daily Average</td>
                                    <td class="align-middle"  colspan="3">Percentage for the Month</td>
                        
                                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                                    <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                                        </td>
                        
                                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                                    <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                                        </td>
                        
                                    <td class="align-middle"  colspan="3">(A) Cumulative as of Previous Month</td>
                                    <td class="align-middle"  colspan="3">(B) For the Month</td>
                                    <td class="align-middle"  colspan="3">(A+B) Cumulative as of End of the Month
                                        </td>
                        
                                </tr>
                                <tr class="text-center">
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                        
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                        
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                        
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                        
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                    <td>M</td>
                                    <td>F</td>
                                    <td>T</td>
                                </tr>
                            </thead>
                            @if(count($sections[0]->data) != 0)
                                <tbody>
                                    @if($sections[0]->count != 0)
                                        @foreach($sections[0]->data as $section)
                                            <tr class="text-center">
                                                <td class="text-center">{{$section->levelname}}</td>
                                                <td>{{$section->sectionname}}</td>
                                                <td  class="text-center">
                                                    @if($section->lastname != null)
                                                        {{$section->lastname}} , {{$section->firstname}}
                                                    @else
                                                        NO ADVISER
                                                    @endif
                                                </td>
                                                <td>{{$section->male}}</td>
                                                <td>{{$section->female}}</td>
                                                <td>{{$section->male + $section->female}}</td>
                        
                                                <td>{{$section->maleAtt}}</td>
                                                <td>{{$section->femaleAtt}}</td>
                                                <td>{{$section->maleAtt + $section->femaleAtt}}</td>
                        
                                                <td>
                                                    @if($section->male != 0)
                                                        {{round(($section->maleAtt / $section->male) * 100)}}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($section->female != 0)
                                                        {{round(($section->femaleAtt / $section->female) * 100)}}%
                                                    @else
                                                        0%
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($section->female != 0 || $section->male != 0)
                                                        {{
                                                        round((($section->femaleAtt + $section->maleAtt) / ($section->female +  $section->male)) * 100)
                                                        }}%
                                                    @else
                                                        0%
                                                    @endif
                        
                                                </td>
                        
                                                <td>{{$section->prevdropoutmale}}</td>
                                                <td>{{$section->prevdropoutfemale}}</td>
                                                <td>{{$section->prevdropoutmale + $section->prevdropoutfemale}}</td>
                                                <td>{{$section->dropoutmale}}</td>
                                                <td>{{$section->dropoutfemale}}</td>
                                                <td>{{$section->dropoutmale + $section->dropoutfemale}}</td>
                                                
                                                <td>{{$section->dropoutmale + $section->prevdropoutmale}}</td>
                                                <td>{{$section->dropoutfemale + $section->prevdropoutfemale}}</td>
                                                <td>{{$section->prevdropoutmale + $section->prevdropoutfemale + $section->dropoutmale + $section->dropoutfemale}}</td>
                        
                                                <td>{{$section->prevtransoutmale}}</td>
                                                <td>{{$section->prevtransoutfemale}}</td>
                                                <td>{{$section->prevtransoutmale + $section->prevtransoutfemale}}</td>
                                                <td>{{$section->transoutmale}}</td>
                                                <td>{{$section->transoutfemale}}</td>
                                                <td>{{$section->transoutmale + $section->transoutfemale}}</td>
                        
                                                <td>{{$section->transoutmale + $section->prevtransoutmale}}</td>
                                                <td>{{$section->transoutfemale + $section->prevtransoutfemale}}</td>
                                                <td>{{$section->prevtransoutmale + $section->prevtransoutfemale + $section->transoutmale + $section->transoutfemale}}</td>
                        
                                                <td>{{$section->prevtransinmale}}</td>
                                                <td>{{$section->prevtransinfemale}}</td>
                                                <td>{{$section->prevtransinmale + $section->prevtransinfemale}}</td>
                                                <td>{{$section->transinmale}}</td>
                                                <td>{{$section->transinfemale}}</td>
                                                <td>{{$section->transinmale + $section->transinfemale}}</td>
                        
                                                <td>{{$section->transinmale + $section->prevtransinmale}}</td>
                                                <td>{{$section->transinfemale + $section->prevtransinfemale}}</td>
                                                <td>{{$section->transinmale + $section->transinfemale + $section->prevtransinmale + $section->prevtransinfemale}}</td>
                        
                                            </tr>
                                        @endforeach
                                    @endif
                                <tr>
                                    <td colspan="3">ELEMENTARY/SECONDARY:</td>
                                    <td  colspan="36"></td>
                                </tr>
                        
                                @for($x=0;$x<=8;$x++)
                                    <tr class="text-center">
                                        @if($x>0 && $x<7)
                                            <td colspan="3">
                                                GRADE{{$x}} / GRADE {{$x+6}}
                                            </td>
                                        @elseif($x==7)
                                            <td colspan="3">TOTAL FOR  NON-GRADED
                                                </td>
                                        @elseif($x==8)
                                            <td colspan="3">TOTAL</td>
                                        @else
                                            <td colspan="3">KINDER</td>
                                        @endif
                        
                        
                        
                                        <!-- Registered Male -->
                        
                                        <td class="align-middle" style="font-size: 12px;">
                        
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male')}} /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')}}
                                            @elseif($x==8)
                                                {{collect($sections[0]->data)->sum('male')}}
                                            @endif
                        
                                        </td>
                        
                                        <!-- Registered Female -->
                        
                                        <td class="align-middle" style="font-size: 12px;">
                        
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}}
                                            @elseif($x==8)
                                                {{collect($sections[0]->data)->sum('female')}}
                                            @else
                                                
                                            @endif
                        
                        
                                        </td>
                        
                                        <!-- Registered Total -->
                        
                                        <td class="align-middle" style="font-size: 12px;">
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+3)->sum('female')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')}}
                                            @endif
                                        </td>
                        
                                        <!-- Daily Attendance Male -->
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('maleAtt')}}
                                            @endif
                                        </td>
                        
                                        <!-- Daily Attendance Female -->
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt')}} 
                        
                                            @elseif($x==8)
                                                {{collect($sections[0]->data)->sum('femaleAtt')}}
                                            @endif
                                        </td>
                        
                                         <!-- Daily Attendance Female -->
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') + collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')}} 
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt')}}
                        
                                            @endif
                        
                                        </td>
                        
                                        <!-- Percentage Daily Attendance Male -->
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') != 0)
                                                    {{round(collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('male') * 100)}}%
                                                @else
                                                    0%
                                                @endif
                                                /
                                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') != 0)
                                                    {{round(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') * 100)}}%
                                                @else
                                                    0%
                                                @endif
                        
                                            @elseif($x==8)
                                                @if($section->male != 0)
                                                    {{  (collect($sections[0]->data)->sum('maleAtt') / collect($sections[0]->data)->sum('male'))  * 100 }}%
                                                @else
                                                    0%
                                                @endif
                                            @endif  
                                        </td>
                        
                                        <!-- Percentage Daily Attendance Female -->
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                @if(collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') != 0)
                                                    {{collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+3)->sum('female') * 100}}%
                                                @else
                                                    0%
                                                @endif
                                                /
                                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') != 0)
                                                    {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') / collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') * 100}}%
                                                @else
                                                    0%
                                                @endif
                                            @elseif($x==8)
                                                @if($section->female != 0)
                                                    {{  (collect($sections[0]->data)->sum('femaleAtt') / collect($sections[0]->data)->sum('female'))  * 100 }}%
                                                @else
                                                    0%
                                                @endif
                                            @endif
                                        </td>
                        
                                        <!-- Percentage Daily Attendance Total -->
                                        <td>
                                            {{-- {{collect($sections[0]->data)->sum('female') +  collect($sections[0]->data)->sum('male')}} --}}
                                          
                                            @if($x>0 && $x<7)
                                            
                                                @if( collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+3)->sum('male') > 0)
                                                    {{
                                                        (
                                                            (
                                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('femaleAtt') +
                                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('maleAtt')
                                                            )   /
                                                            (
                                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('female') +
                                                                collect($sections[0]->data)->where('sortid',$x+3)->sum('male')
                                                            )
                        
                                                        )   * 100
                                                    }}%
                                                @else
                                                    0%
                                                @endif
                                                /
                                                @if(collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male') > 0)
                                                    {{
                                                        (
                                                            (
                                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('femaleAtt') +
                                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('maleAtt')
                                                            )   /
                                                            (
                                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('female') +
                                                                collect($sections[0]->data)->where('sortid',$x+6+3)->sum('male')
                                                            )
                        
                                                        )   * 100
                                                    }}%
                                                @else
                                                    0%
                                                @endif
                        
                        
                                            @elseif($x==8)
                                                @if( (collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male')) > 0)
                                                    {{  
                                                        (
                                                            (   collect($sections[0]->data)->sum('femaleAtt') + collect($sections[0]->data)->sum('maleAtt') ) /
                                                            (   collect($sections[0]->data)->sum('female') + collect($sections[0]->data)->sum('male') ) 
                                                        ) * 100 
                                                    }}%
                                                @else
                                                    0
                                                @endif
                                            @endif
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevdropoutmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevdropoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevdropoutfemale') + collect($sections[0]->data)->sum('prevdropoutmale')}}
                                                
                                            @endif
                        
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('dropoutmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('dropoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('dropoutfemale') + collect($sections[0]->data)->sum('dropoutmale')}}
                                                
                                            @endif
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevdropoutmale') +
                                                    collect($sections[0]->data)->sum('dropoutmale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') }} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevdropoutfemale') +  collect($sections[0]->data)->sum('dropoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevdropoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('dropoutmale')}} 
                                                /
                                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevdropoutmale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('dropoutmale') }} 
                        
                                            @elseif($x==8)
                        
                                                {{  collect($sections[0]->data)->sum('prevdropoutfemale') + 
                                                collect($sections[0]->data)->sum('prevdropoutmale') + 
                                                collect($sections[0]->data)->sum('dropoutfemale') +
                                                collect($sections[0]->data)->sum('dropoutmale')
                                                }} 
                                                
                                            @endif
                                        </td>
                        
                                        {{-- transout --}}
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransoutmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('prevtransoutmale')}}
                                                
                                            @endif
                        
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transoutmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transoutfemale') + collect($sections[0]->data)->sum('transoutmale')}}
                                                
                                            @endif
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransoutmale') +
                                                    collect($sections[0]->data)->sum('transoutmale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') }} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransoutfemale') + collect($sections[0]->data)->sum('transoutfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransoutmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transoutmale')}} 
                                                /
                                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransoutmale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transoutmale') }} 
                        
                                            @elseif($x==8)
                        
                                                {{  collect($sections[0]->data)->sum('prevtransoutfemale') + 
                                                    collect($sections[0]->data)->sum('prevtransoutmale') + 
                                                    collect($sections[0]->data)->sum('transoutfemale') +
                                                    collect($sections[0]->data)->sum('transoutmale')
                                                }}                        
                                            @endif
                                        </td>
                        
                                        {{-- transin --}}
                        
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransinmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransinfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('prevtransinmale')}}
                                                
                                            @endif
                        
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transinmale')}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transinfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale') }} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('transinfemale') + collect($sections[0]->data)->sum('transinmale')}}
                                                
                                            @endif
                                        </td>
                        
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale')}} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransinmale') +
                                                    collect($sections[0]->data)->sum('transinmale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale')}} 
                                                /
                                                {{collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') }} 
                        
                                            @elseif($x==8)
                        
                                                {{collect($sections[0]->data)->sum('prevtransinfemale') + collect($sections[0]->data)->sum('transinfemale')}}
                        
                                            @endif
                                        </td>
                                        <td>
                                            @if($x>0 && $x<7)
                                                {{  collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('prevtransinmale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+3)->sum('transinmale')}} 
                                                /
                                                {{  collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinfemale') + 
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('prevtransinmale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinfemale') +
                                                    collect($sections[0]->data)->where('sortid',$x+6+3)->sum('transinmale') }} 
                        
                                            @elseif($x==8)
                        
                                            {{  collect($sections[0]->data)->sum('prevtransinfemale') + 
                                                collect($sections[0]->data)->sum('prevtransinmale') + 
                                                collect($sections[0]->data)->sum('transinfemale') +
                                                collect($sections[0]->data)->sum('transinmale')
                                            }} 
                                                
                                            @endif
                                        </td>
                        
                        
                                        {{-- <td>9999 / 9999</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> --}}
                        
                        
                        
                        
                        
                                        {{-- <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td> --}}
                                         
                                    </tr>
                        
                        
                                @endfor
                        
                            </tbody>
                           @endif
                        </table>
                    </div>
                    <br/>
                <table class="table border-0">
                    <thead>
                        <tr>
                            <td width="70%" class="border-0">GUIDELINES</td>
                            <td width="30%" class="border-0"><strong>Prepared and Submitted by:</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-0">1. This form shall be accomplished every end of the month using the summary box of SF2 submitted by the teachers/advisers to update figures for the month. </td>
                            <td class="border-0"></td>
                        </tr>
                        <tr>
                            <td class="border-0">2. Furnish the Division Office with a copy a week after June 30, October 30 & March 31</td>
                            <td class="border-0 text-center">__________________________________________</td>
                        </tr>
                        <tr>
                            <td class="text-center border-0" ></td>
                            <td class="text-center border-0" >(Signature of School Head over Printed Name)</td>
                        </tr>
                    </tbody>
                </table>
                    {{-- <table id="header" class="table table-bordered">
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
                    </table> --}}
                    {{-- <br> --}}                          
                        {{-- <table class="table" style="border: 1px solid black !important;table-layout:fixed;">
                            <tr>
                                <th style="border: hidden !important;width:15%;padding:5px"><small>Prepared and Submitted:</small></th>
                                <td style="border-top: hidden; border-bottom: 1px solid black;border-left:hidden; border-right: hidden; !important;padding:5px"><center>
                                    <small>
                                        <input type="text" class="form-control form-control-sm text-uppercase" name="schoolhead" value="{{$school[0]->authorized}}"/>
                                    </small>
                                </center></td>
                                <td style="padding:5px;border:hidden;text-align:right"><small>Reviewed & Validated by:</small></td>
                                <td style="padding:5px;border-bottom: 1px solid black;">
                                    <small>
                                        <input type="text" class="form-control form-control-sm text-uppercase" name="divrep"/>
                                    </small>
                                </td>
                                <td style="padding:5px;border:hidden;text-align:right"><small>Noted by:</small></td>
                                <td style="padding:5px;border-right:hidden;border-bottom: 1px solid black;width:20%">
                                    
                                    <input type="text" class="form-control form-control-sm text-uppercase" name="divsup"/>
                                </td>
                            </tr>
                            <tr>
                                <th style="border:hidden;padding:0px;"></th>
                                <td style="border-bottom:hidden;padding:0px;"><center><small>SCHOOL HEAD</small></center></td>
                                <td style="border:hidden;padding:0px;"></td>
                                <td style="border-bottom:hidden;padding:0px;"><center><small>DIVISION REPRESENTATIVE</small></center></td>
                                <td style="border-bottom:hidden;padding:0px;"></td>
                                <td style="border-bottom:hidden;border-right:hidden;padding:0px;"><center><small>SCHOOLS DIVISION SUPERINTENDENT</small></center></td>
                            </tr>
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
                        </div> --}}
                    {{-- @endif --}}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
    <script>
        $(document).on('change','select[name="selectedschoolyear"]', function (){
            $('input[name="selectedschoolyear"]').val($(this).val());
            $('form[name="formsubmit"]').attr('action','/reports_schoolform4/changeschoolyear');
            $('form[name="formsubmit"]').attr('target','');
            $('form[name="formsubmit"]').submit();
        });
        $(document).on('change','select[name="selectedmonth"]', function (){
            $('input[name="selectedmonth"]').val($(this).val());
            $('form[name="formsubmit"]').attr('action','/reports_schoolform4/changemonth');
            $('form[name="formsubmit"]').attr('target','');
            $('form[name="formsubmit"]').submit();
        });
        $(document).on('click','.btnprint', function (){
            $('form[name="formsubmit"]').attr('action','/reports_schoolform4/print');
            $('form[name="formsubmit"]').attr('target','_blank');
            $('form[name="formsubmit"]').submit();


        });
        $(document).ready(function(){
            $('body').addClass('sidebar-collapse');
        })
    </script>
@endsection