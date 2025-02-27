
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }

</style>

@php

$signatories = DB::table('signatory')
        ->where('form','report_enrollmentsummary')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->where('acadprogid',$acadprogid)
        ->get();

if(count($signatories) == 0)
{
    $signatories = DB::table('signatory')
        ->where('form','report_enrollmentsummary')
        ->where('syid', $syid)
        ->where('deleted','0')
        ->where('acadprogid',0)
        ->get();

    // if(count($signatories)>0)
    // {
    //     if(collect($signatories)->where('levelid', $levelid)->count() == 0)
    //     {
    //         $signatories = collect($signatories)->where('levelid',0)->values();
    //     }else{
    //         $signatories = collect($signatories)->where('levelid', $levelid)->values();
    //     }
    // }

    
}
// else{
//     if(collect($signatories)->where('levelid', $levelid)->count() == 0)
//     {
//         $signatories = collect($signatories)->where('levelid',0)->values();
//     }else{
//         $signatories = collect($signatories)->where('levelid', $levelid)->values();
//     }
// }
@endphp
<table style="width: 100%;">
    <tr>
        <td width="20%"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
        <td style="width: 60%; text-align: center;">
                <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
                <br>
                <span style="font-size: 12px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</span>
        </td>
        <td width="20%" style="text-align: right;">@if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'pcc') <img src="{{base_path()}}/public/assets/images/uccp.png" alt="school" width="80px"> @endif</td>
    </tr>
</table>
<br/>
<div style="font-size: 15px; text-align: center;">ENROLLMENT SUMMARY - {{strtoupper($semester)}} {{$sydesc}}</div>
<br/>
<div class="row">
    <div class="col-md-12">
        @php
            $gtotalmale = 0;
            $gtotalfemale = 0;
        @endphp
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid black; text-align:left;">DEPARTMENT</th>
                    <th style="border-bottom: 1px solid black;">COURSE</th>
                    <th style="border-bottom: 1px solid black;">CLASSIFICATION</th>
                    <th style="border-bottom: 1px solid black;">MALE</th>
                    <th style="border-bottom: 1px solid black;">FEMALE</th>
                    <th style="border-bottom: 1px solid black;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">&nbsp;</td>
                </tr>
                @foreach($students as $key => $eachstudent)
                    <tr>
                        <th colspan="6" style="border-bottom: 1px solid black; text-align: left;">{{$key}} Department</th>
                    </tr>
                    @php
                        $totaleachstudent = $eachstudent;
                        // if($key != 'Basic Ed')
                        // {
                            // $totaleachcourse = collect($totaleachstudent)->sortByDesc('coursesourtid')->values()->all();
                        // }
                        
                        $totaleachstudent = collect($totaleachstudent)->sortBy('sortid')->values()->all();
                        if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sbc')
                        {
                        $eachstudent = collect($totaleachstudent)->groupBy('coursename');
                        }else{
                        $eachstudent = collect($totaleachstudent)->groupBy('completecourse');
                        }
                    @endphp
                    @foreach($eachstudent as $coursekey => $eachcourse)
                        @if($coursekey != '')
                        <tr>
                            <td></td>
                            <th colspan="5" style="border-bottom: 1px solid black; text-align: left;">{{$coursekey}}</th>
                        </tr>
                        @endif
                        @php
                        $totaleachcourse = collect($eachcourse)->sortBy('sortid')->values()->all();
                            $levels = collect($totaleachcourse)->groupBy('levelname');
                        @endphp
                        @foreach($levels as $levelkey => $eachlevel)
                            <tr>
                                <td>
                                    {{-- @if($levelkey == '1ST YEAR COLLEGE')
                                        {{$eachlevel}}
                                    @endif --}}
                                </td>
                                <td></td>
                                <td>{{$levelkey}}</td>
                                <td style="text-align: right;">{{collect($eachlevel)->where('gender','male')->count()}}</td>
                                <td style="text-align: right;">{{collect($eachlevel)->where('gender','female')->count()}}</td>
                                <td style="text-align: right;">{{collect($eachlevel)->count()}}</td>
                            </tr>
                        @endforeach
                        @if($key != 'Basic Ed')
                        <tr>
                            <td></td>
                            <th colspan="2" style="border-top: 1px solid black; text-align: left;">TOTAL {{$coursekey}}</th>
                            <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachcourse)->where('gender','male')->count()}}</td>
                            <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachcourse)->where('gender','female')->count()}}</th>
                            <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachcourse)->count()}}</td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                        @endif
                    @endforeach
                    @php
                        $gtotalmale += collect($totaleachstudent)->where('gender','male')->count();
                        $gtotalfemale += collect($totaleachstudent)->where('gender','female')->count();
                    @endphp
                    <tr>
                        <th colspan="2" style="border-top: 1px solid black; text-align: left;">TOTAL {{$key}} Department</th>
                        <th style="border-top: 1px solid black;"></th>
                        <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachstudent)->where('gender','male')->count()}}</th>
                        <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachstudent)->where('gender','female')->count()}}</th>
                        <th style="border-top: 1px solid black; text-align: right;">{{collect($totaleachstudent)->count()}}</th>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2" style="border-top: 1px solid black; text-align: left;">GRAND TOTAL :</th>
                    <td style="border-top: 1px solid black;"></td>
                    <th style="border-top: 1px solid black; text-align: right;">{{$gtotalmale}}</th>
                    <th style="border-top: 1px solid black; text-align: right;">{{$gtotalfemale}}</td>
                    <th style="border-top: 1px solid black; text-align: right;">{{$gtotalmale+$gtotalfemale}}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<br/>
<br/>
@if(count($signatories)>0)
    @foreach($signatories as $signatory)
    <div class="label" style="display:inline-block;
    background-color:White;
    width: auto; text-align:center; font-size: 15px;">
        <div class="label-text" style=" float:left; text-align: center; line-height: 30px; vertical-align: center; white-space: nowrap; overflow: hidden;">
            <span style="float: left;">&nbsp;{{$signatory->title}}</span>
            <br/>
            <br/>
            <span style="text-align:center;border-bottom: 1px solid black;">&nbsp;{{$signatory->name}}</span>
            <br/>
            <sup style="text-align:center">{{$signatory->description}}</sup> 
        </div>
    </div>
    @endforeach
@endif