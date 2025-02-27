
<style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }

</style>

<table style="width: 100%;">
    <tr>
        <td width="20%"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
        <td style="width: 60%; text-align: center;">
                <strong>{{DB::table('schoolinfo')->first()->schoolname}}</strong>
                <br>
                <span style="font-size: 12px;">{{ucwords(strtolower(DB::table('schoolinfo')->first()->address))}}</span>
        </td>
        <td width="20%"></td>
    </tr>
</table>
<br/>
<div style="font-size: 15px; text-align: center;">ENROLLMENT SUMMARY - {{strtoupper($semester)}} {{$sydesc}}</div>
<br/>
<div class="row">
    <div class="col-md-12">
        @php
            $gtotal = 0;
        @endphp
        <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
            <thead>
                <tr>
                    <th style="width: 5%; border-bottom: 1px solid black;"></th>
                    <th style=" border-bottom: 1px solid black;">Student ID</th>
                    <th style="width: 50%; border-bottom: 1px solid black; text-align: left;">Student Name</th>
                    <th style=" border-bottom: 1px solid black; text-align: left;">Gender</th>
                    <th style=" border-bottom: 1px solid black; text-align: right;">Units Enrolled</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $key => $eachcourse)
                    <tr>
                        <th colspan="5" style="text-align: left;">{{$key}} ({{$eachcourse[0]->courseDesc}})</th>
                    </tr>
                    @php
                        $gtotal+=count($eachcourse);
                        $totaleachcourse = $eachcourse;
                        $levels = collect($eachcourse)->sortBy('sortid')->groupBy('levelname');
                    @endphp
                    @foreach($levels as $levelkey => $eachlevel)
                        <tr>
                            <td></td>
                            <th colspan="4" style="text-align: left;">{{$levelkey}}</td>
                        </tr>
                        @foreach(collect($eachlevel)->sortBy('lastname') as $eachstudent)
                            <tr>
                                <td></td>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{$eachstudent->sid}}</td>
                                <td>{{$eachstudent->lastname}},{{$eachstudent->firstname}} {{$eachstudent->middlename}}</td>
                                <td>{{ucwords($eachstudent->gender)}}</td>
                                <td style="text-align: right;">{{number_format($eachstudent->totalunits)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"></td>
                            <td style="border-top: 1px solid black;">
                            Year Level Count: <strong>{{count($eachlevel)}}</strong></td>
                            <td colspan="2"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td style="border-top: 1px solid black;">Course Total: <strong>{{count($totaleachcourse)}}</strong></td>
                        <td colspan="3"></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" style="border-top: 1px solid black; border-bottom: 1px solid black;">GRAND TOTAL : <strong>{{$gtotal}}</strong></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>