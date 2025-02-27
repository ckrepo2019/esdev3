
<style>
    .header{
        width: 100%;
        table-layout: fixed;
        font-family: Arial, Helvetica, sans-serif;
        /* border: 1px solid black; */
    }
    .header td {
        font-size: 15px !important;
        /* border: 1px solid black; */
    }
    .studentstable{
        width: 100%;
        /* table-layout: fixed; */
        font-size: 12px;
        border: 1px solid black;
        border-collapse: collapse;
    }
    .studentstable td, .enrollees th{
        border: 1px solid black;
        padding: 5px;
    }
    .clear:after {
        clear: both;
        content: "";
        display: table;
        border: 1px solid black;
    }
    tbody td {
        font-size: 11px !important;
    }
</style>
<table class="header">
    <tr>
        <td width="15%" rowspan="2"><img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="70px"></td>
        <td><strong>{{$schoolinfo[0]->schoolname}}</strong> </td>
        <td style="text-align:right;"><strong>Loads</strong><br><small>S.Y {{$sy[0]->sydesc}}</small></td>
    </tr>
</table>
<br>
<strong>{{$myinfo->firstname}} {{$myinfo->middlename != null ? $myinfo->middlename[0].'.' : ''}} {{$myinfo->lastname}} {{$myinfo->suffix}}</strong>
<br>
<small>TEACHER</small>
<br>
@if(isset($mondayArray))
    <table class="studentstable" width="100%">
        <thead>
            <tr>
                <th colspan="4" style="border: 1px solid; background-color: gainsboro">MONDAY</th>
            </tr>
            <tr>
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @if(isset($mondayArray))
                @foreach($mondayArray as $monsched)
                    <tr>
                        <td>{{$monsched->levelname}} -
                            @if(isset($monsched->sectionname))
                            {{$monsched->sectionname}}
                            @endif
                        </td>
                        <td>{{$monsched->subjdesc}}</td>
                        <td>{{$monsched->stime}} - {{$monsched->etime}}</td>
                        <td>{{$monsched->roomname}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
@endif
@if(isset($tuesdayArray))
    <br>
    <table class="studentstable" width="100%">
        <thead>
            <tr>
                <th colspan="4" style="border: 1px solid;">TUESDAY</th>
            </tr>
            <tr style="background-color: gainsboro">
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @foreach($tuesdayArray as $tuesched)
                <tr>
                    {{-- <td>
                        {{$tuesched->description}}
                    </td> --}}
                    <td>{{$tuesched->levelname}} -
                        @if(isset($tuesched->sectionname))
                        {{$tuesched->sectionname}}
                        @endif
                    </td>
                    <td>{{$tuesched->subjdesc}}</td>
                    <td>{{$tuesched->stime}} - {{$tuesched->etime}}</td>
                    <td>{{$tuesched->roomname}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@if(isset($wednesdayArray))
    <br>
    <table class="studentstable" width="100%">
        <thead>
            <tr>
                <th colspan="4" style="border: 1px solid;">WEDNESDAY</th>
            </tr>
            <tr style="background-color: gainsboro">
                {{-- <th style="border: 1px solid"></th>  --}}
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @foreach($wednesdayArray as $wedsched)
                <tr>
                    {{-- <td>
                        {{$wedsched->description}}
                    </td> --}}
                    <td>{{$wedsched->levelname}} -
                        @if(isset($wedsched->sectionname))
                        {{$wedsched->sectionname}}
                        @endif
                    </td>
                    <td>{{$wedsched->subjdesc}}</td>
                    <td>{{$wedsched->stime}} - {{$wedsched->etime}}</td>
                    <td>{{$wedsched->roomname}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@if(isset($thursdayArray))
    <br>
    <table class="studentstable" width="100%">
        <thead>
            <tr>
                <th colspan="4" style="border: 1px solid;">THURSDAY</th>
            </tr>
            <tr style="background-color: gainsboro">
                {{-- <th style="border: 1px solid"></th>  --}}
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @foreach($thursdayArray as $thusched)
                <tr>
                    {{-- <td>
                        {{$thusched->description}}
                    </td> --}}
                    <td>{{$thusched->levelname}} -
                        @if(isset($thusched->sectionname))
                        {{$thusched->sectionname}}
                        @endif
                    </td>
                    <td>{{$thusched->subjdesc}}</td>
                    <td>{{$thusched->stime}} - {{$thusched->etime}}</td>
                    <td>{{$thusched->roomname}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@if(isset($fridayArray))
    <br>
    <table class="studentstable" width="100%">
        <thead>
            <tr>
                <th colspan="4" style="border: 1px solid;">FRIDAY</th>
            </tr>
            <tr style="background-color: gainsboro">
                {{-- <th style="border: 1px solid"></th>  --}}
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @foreach($fridayArray as $frisched)
                <tr>
                    {{-- <td>
                        {{$frisched->description}}
                    </td> --}}
                    <td>{{$frisched->levelname}} -
                        @if(isset($frisched->sectionname))
                        {{$frisched->sectionname}}
                        @endif
                    </td>
                    <td>{{$frisched->subjdesc}}</td>
                    <td>{{$frisched->stime}} - {{$frisched->etime}}</td>
                    <td>{{$frisched->roomname}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
@if(isset($saturdayArray))
    <br>
    <table class="studentstable"  width="100%">
        <thead>
            <tr style="boder:0">
                <th colspan="4">SATURDAY</th>
            </tr>
            <tr style="background-color: gainsboro">
                <th style="border: 1px solid" width="25%">Grade & Section</th> 
                <th style="border: 1px solid" width="30%">Subject</th> 
                <th style="border: 1px solid" width="20%">Time</th> 
                <th style="border: 1px solid" width="25%">Room</th> 
            </tr>
        </thead>
        <tbody style="text-transform: uppercase;">
            @foreach($saturdayArray as $satsched)
                <tr>
                    {{-- <td>
                        {{$frisched->description}}
                    </td> --}}
                    <td>{{$satsched->levelname}} -
                        @if(isset($satsched->sectionname))
                        {{$satsched->sectionname}}
                        @endif
                    </td>
                    <td>{{$satsched->subjdesc}}</td>
                    <td>{{$satsched->stime}} - {{$satsched->etime}}</td>
                    <td>{{$satsched->roomname}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif