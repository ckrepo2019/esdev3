
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }
    @page{
        margin: 20px 35px;
    }
    table{
        border-collapse: collapse;
    }
</style>
@php
    function ucwordss($str, $exceptions) {
    $out = "";
    foreach (explode(" ", $str) as $word) {
    $out .= (!in_array($word, $exceptions)) ? strtoupper($word{0}) . substr($word, 1) . " " : $word . " ";
    }
    return rtrim($out);
    }

    foreach($colleges as $eachcollege)
    {
        $string = strtolower($eachcollege->collegeDesc);
        $ignore = array("in", "and", "the",'of');
        $eachcollege->collegename = ucwordss($string, $ignore);
    }
@endphp
<table style="width: 100%; table-layout: fixed;">
    <tr>
        <td></td>
        <td style="width: 40%;">
            <div style="width: 100%; font-size: 13px; text-align: center; font-weight: bold;">
            Davao Central College
            </div>
            <div style="width: 100%; font-size: 12px; text-align: center;">
            Toril, Davao City
            </div>
            <div style="width: 100%; font-size: 12px; text-align: center;">
            Office of the Registrar
            </div>
            <br/>
            <div style="width: 100%; font-size: 12px; text-align: center;">
            SUMMARY OF ENROLMENT
            </div>
            <div style="width: 100%; font-size: 13px; text-align: center;">
                {{$semester}}, School Year {{$sydesc}}
            </div>
        </td>
        <td style="text-align: right; vertical-align: top; font-size: 11px; padding-right: 40px;">
            {{date('m/d/Y')}}<br/>
            Page: 1
        </td>
    </tr>
</table>
<br/>
<br/>
@if($acadprogid == 6)
<table style="width: 100%; font-size: 9.5px;">
    <tr>
        <th rowspan="2" style="width: 30%;"></th>
        <th colspan="3" style="border: 1px solid black;">First</th>
        <th colspan="3" style="border: 1px solid black;">Second</th>
        <th colspan="3" style="border: 1px solid black;">Third</th>
        <th colspan="3" style="border: 1px solid black;">Fourth</th>
        <th rowspan="2" style="border: 1px solid black;">Total</th>
    </tr>
    <tr>
        <th style="border: 1px solid black;">M</th>
        <th style="border: 1px solid black;">F</th>
        <th style="border: 1px solid black;">Total</th>
        <th style="border: 1px solid black;">M</th>
        <th style="border: 1px solid black;">F</th>
        <th style="border: 1px solid black;">Total</th>
        <th style="border: 1px solid black;">M</th>
        <th style="border: 1px solid black;">F</th>
        <th style="border: 1px solid black;">Total</th>
        <th style="border: 1px solid black;">M</th>
        <th style="border: 1px solid black;">F</th>
        <th style="border: 1px solid black;">Total</th>
    </tr>
    @foreach($courses as $coursekey=>$eachcourse)
        <tr>
            <td style="text-align: left; border-left: 1px solid black; @if($coursekey == 0) border-top: 1px solid black; @endif">{{$eachcourse->courseabrv}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','17')->where('gender','male')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','17')->where('gender','female')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','17')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','18')->where('gender','male')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','18')->where('gender','female')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','18')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','19')->where('gender','male')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','19')->where('gender','female')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','19')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','20')->where('gender','male')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','20')->where('gender','female')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->where('levelid','20')->count()}}</td>
            <td style="text-align: right; border-left: 1px solid black; border-right: 1px solid black;">{{collect($students)->where('courseid', $eachcourse->id)->count()}}</td>
        </tr>
    @endforeach
    <tr>
        <th style="border: 1px solid black;">Totals</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','17')->where('gender','male')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','17')->where('gender','female')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','17')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','18')->where('gender','male')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','18')->where('gender','female')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','18')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','19')->where('gender','male')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','19')->where('gender','female')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','19')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','20')->where('gender','male')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','20')->where('gender','female')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('levelid','20')->count()}}</th>
        <th style="border: 1px solid black;">{{collect($students)->where('acadprogid', 6)->count()}}</th>
    </tr>
</table>
<br/>
<br/>
<table style="width: 100%; font-size: 10px;">
    @foreach($colleges as $collegekey=>$eachcollege)
    <tr>
        <td style="width: 15%;"></td>
        <td style="width: 50%;">{{$eachcollege->collegename}}</td>
        <th style="width: 10%; text-align: right;">{{collect($students)->where('collegeid', $eachcollege->id)->count()}}</th>
        <td style="width: 25%;">&nbsp;</td>
    </tr>
    @endforeach
    <tr>
        <td></td>
        <td>Total</td>
        <th style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: right;">{{collect($students)->where('acadprogid', 6)->count()}}</th>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td style="border-bottom: 1px solid black; line-height: 5px;"></td>
        <td></td>
    </tr>
</table>
<br/>
<p style="font-size: 12px; margin-left: 3%; margin-right: 3%;">This is to certify that the names listed in this College Enrollment List for the {{$semester}}, School Year {{$sydesc}} from Page No. _____ to Page No. ______ are the true names of the students.</p>
<br/>
<table style="width: 100%; font-size: 12px; table-layout: fixed; margin-left: 3%;">
    <tr>
        <td style="width: 17%; padding-left: 18px;">Date Submitted:</td>
        <td style="width: 20%; border-bottom: 1px solid black;"></td>
        <td></td>
        <td></td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td style="text-align: center;">College Registrar</td>
    </tr>
</table>

@else
@php
    $gtotalmale = 0;
    $gtotalfemale = 0;
    $notshsstudents = collect($students)->where('acadprogid','<','5')->all();
    $notshsstudents = collect($notshsstudents)->groupBy('acadprogname');

    $shsstudents = collect($students)->where('acadprogid','5')->sortBy('sort1')->groupBy('sort1');
    $totalmaleshsstudents = 0;
    $totalfemaleshsstudents = 0;
    // $shsstudents = collect($students)->groupBy(['levelname','trackname']);
@endphp
<table style="width: 100%; font-size: 12px; margin: 0px 150px;">
    <thead>
        <tr>
            <th></th>
            <th>MALE</th>
            <th>FEMALE</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    @foreach($notshsstudents as $key => $eachstudent)
        @php
            $eachlevel = collect($eachstudent)->groupBy('levelname');
        @endphp
        @foreach($eachlevel as $keylevel => $each)
        <tr>
            <td>{{$keylevel}}</td>
            <td style="text-align: center;">{{collect($each)->where('gender','male')->count()}}</td>
            <td style="text-align: center;">{{collect($each)->where('gender','female')->count()}}</td>
            <td style="text-align: center;">{{collect($each)->count()}}</td>
        </tr>
        @endforeach
        <tr>
            <td></td>
            <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
            <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
            <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
        </tr>
        <tr>
            <td></td>
            <th style="text-align: center;">{{collect($eachstudent)->where('gender','male')->count()}}</th>
            <th style="text-align: center;">{{collect($eachstudent)->where('gender','female')->count()}}</th>
            <th style="text-align: center;">{{collect($eachstudent)->count()}}</th>
        </tr>        
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr> 
    @endforeach
    @foreach($shsstudents as $key => $eachstudent)
        <tr>
            <td>{{$key}}</td>
            <td style="text-align: center;">{{collect($eachstudent)->where('gender','male')->count()}}</td>
            <td style="text-align: center;">{{collect($eachstudent)->where('gender','female')->count()}}</td>
            <td style="text-align: center;">{{collect($eachstudent)->count()}}</td>
        </tr>
        @php
            $totalmaleshsstudents+=collect($eachstudent)->where('gender','male')->count();
            $totalfemaleshsstudents+=collect($eachstudent)->where('gender','female')->count();
        @endphp
    @endforeach
    @if($acadprogid == 'basiced' || $acadprogid == 'all' || $acadprogid == '5')
    <tr>
        <td></td>
        <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
        <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
        <td style="border-top: 1px solid black; border-bottom: 1px solid black; text-align: center;"></td>
    </tr>
    <tr>
        <td></td>
        <th style="text-align: center;">{{$totalmaleshsstudents}}</th>
        <th style="text-align: center;">{{$totalfemaleshsstudents}}</th>
        <th style="text-align: center;">{{$totalmaleshsstudents+$totalfemaleshsstudents}}</th>
    </tr>
    @endif
</table>
@php
    $schoolyears = DB::table('sy')->orderByDesc('sydesc')->where('sydesc','<', DB::table('sy')->where('id', $syid)->first()->sydesc)->select('id')->get();
    $currentyear = (array_search ($syid, collect($schoolyears)->pluck('id')->toArray()))-1;
    $lastyear = array_search ($currentyear, collect($schoolyears)->pluck('id')->toArray());
    $lastyear = DB::table('sy')->orderByDesc('sydesc')->where('sydesc','<', DB::table('sy')->where('id', $syid)->first()->sydesc)->get()[$lastyear];
    $currentyear = DB::table('sy')->where('id', $syid)->first();


    $totalcurrentyear = 0;

    $acadprogs = DB::table('academicprogram')
        ->get();

    if($acadprogid == 'basiced')
    {
        $acadprogs = collect($acadprogs)->where('id','<',6)->all();
    }
    elseif($acadprogid == 'all')
    {
        $acadprogs = collect($acadprogs)->where('id','<',6)->all();
    }else{
        $acadprogs = collect($acadprogs)->where('id',$acadprogid)->all();
    }

    foreach($acadprogs as $eachacadprog)
    {
        if($eachacadprog->id == 6)
        {
            $totalcount = DB::table('college_enrolledstud')
                ->select('college_enrolledstud.studid')
                ->where('syid', $lastyear->id)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->whereIn('studstatus',[1,2,4])
                ->distinct('college_enrolledstud.studid')
                ->count();
        }
        elseif($eachacadprog->id == 5)
        {
            $totalcount = DB::table('sh_enrolledstud')
                ->select('sh_enrolledstud.studid')
                ->where('syid', $lastyear->id)
                ->where('semid', $semid)
                ->where('deleted', 0)
                ->whereIn('studstatus',[1,2,4])
                ->distinct('sh_enrolledstud.studid')
                ->count();
        }else{
            $totalcount = DB::table('enrolledstud')
                ->select('enrolledstud.studid')
                ->join('gradelevel','enrolledstud.levelid','=','gradelevel.id')
                ->where('syid', $lastyear->id)
                ->where('acadprogid', $eachacadprog->id)
                ->where('enrolledstud.deleted', 0)
                ->whereIn('studstatus',[1,2,4])
                ->distinct('enrolledstud.studid')
                ->count();
        }
        $eachacadprog->totalcount = $totalcount;
    }
@endphp
<br/>
<table style="width: 100%; table-layout: fixed; margin: 0px 50px; font-size: 12px;" border="1">
    <tr>
        <th></th>
        <th>{{$currentyear->sydesc}}</th>
        <th>{{$lastyear->sydesc}}</th>
        <th style="width: 15%;">Difference</th>
    </tr>
    @foreach($notshsstudents as $key => $eachstudgroup)
        <tr>
            <td>{{$key}}</td>
            <td style="text-align: center;">{{count($eachstudgroup)}}</td>
            <td style="text-align: center;">{{collect($acadprogs)->where('progname', strtoupper($key))->first()->totalcount}}</td>
            <td style="text-align: center;">{{collect($acadprogs)->where('progname', strtoupper($key))->first()->totalcount - count($eachstudgroup)}}</td>
        </tr>
        @php
        $totalcurrentyear+=count($eachstudgroup);
        @endphp
    @endforeach
    @if($acadprogid == 'basiced' || $acadprogid == 'all' || $acadprogid == '5')
    
    <tr>
        <td>MWSP</td>
        <td style="text-align: center;">0</td>
        <td style="text-align: center;">0</td>
        <td style="text-align: center;">0</td>
    </tr>
    <tr>
        <td>Senior High School</td>
        <td style="text-align: center;">{{collect($students)->where('acadprogid','5')->count()}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->where('id', 5)->first()->totalcount}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->where('id', 5)->first()->totalcount - collect($students)->where('acadprogid','5')->count()}}</td>
    </tr>
    <tr>
        <th style="text-align: right;">Total</th>
        <td style="text-align: center;">{{$totalcurrentyear+collect($students)->where('acadprogid','5')->count()}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->sum('totalcount')}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->sum('totalcount')-($totalcurrentyear+collect($students)->where('acadprogid','5')->count())}}</td>
    </tr>
    @else
    <tr>
        <th style="text-align: right;">Total</th>
        <td style="text-align: center;">{{$totalcurrentyear}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->sum('totalcount')}}</td>
        <td style="text-align: center;">{{collect($acadprogs)->sum('totalcount')-($totalcurrentyear)}}</td>
    </tr>
    @endif
</table>
<br/>
<br/>
<table style="width: 100%; table-layout: fixed; margin: 0px 50px; font-size: 12px;">
    <tr>
        <td colspan="2">Prepared by:</td>
        <td colspan="2">Noted by:</td>
    </tr>
    <tr>
        <td colspan="4">&nbsp;</td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">&nbsp;&nbsp;</td>
        <td></td>
        <td style="border-bottom: 1px solid black; font-weight: bold; text-align: center;">&nbsp;{{auth()->user()->name}}&nbsp;</td>
        <td></td>
    </tr>
    <tr>
        <td style="text-align: center;">ENCODER</td>
        <td></td>
        <td style="text-align: center;">Registrar(Consultant)</td>
        <td></td>
    </tr>
</table>

@endif
