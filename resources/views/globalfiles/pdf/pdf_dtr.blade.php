<style>

    *         { font-family: Arial, Helvetica, sans-serif; font-size: 11px; }

    #dtrtable { width: 100%; border: 1px solid black; border-collapse: collapse; table-layout: fixed; text-align: center; }

    #dtrtable th, td { border: 1px solid black; }

    #dtrtable td { border: 1px solid black; font-size: 10px;}
    @page { margin-top: 20px; margin-bottom: 20px;}
</style>

<div style="width: 100%; text-align: center; margin-bottom: 5px;">
    <strong style="font-size: 10px; ">DAILY TIME RECORD</strong>
</div>
{{-- <div style="width: 100%; border-bottom: 1px solid black; text-align: center;margin-bottom: 2px;">
    
</div> --}}

<table style="width: 100%;border-collapse: collapse; font-size: 10px !important;">
    <tr>
        <td style="border: none; width: 10px;">Name:</td>
        <td style="border: none; border-bottom: 1px solid black; text-transform: uppercase;">{{$myid->firstname}} {{$myid->middlename[0].'.'}} {{$myid->lastname}} {{$myid->suffix}}</td>
    </tr>
    <tr>
        <td style="border: none; width: 10px;">Designation:</td>
        <td style="border: none; border-bottom: 1px solid black;">{{strtoupper($myid->utype)}}</td>
    </tr>
    <tr>
        <td style="border: none; width: 10px;">Department:</td>
        <td style="border: none; border-bottom: 1px solid black;">{{strtoupper($myid->department)}}</td>
    </tr>
    <tr>
        <td style="border: none; width: 10px;">Date Period:</td>
        <td style="border: none; border-bottom: 1px solid black;">{{$dateperiod}}</td>
    </tr>
</table>
<br>
<table id="dtrtable">
    <thead>
        <tr>
            <th rowspan="2" style="width: 10%;"></th>
            <th colspan="2">AM</th>
            <th colspan="2">PM</th>
            {{-- <th style="font-size: 9px;">Undertime</th> --}}
        </tr>
        <tr>
            <th style="font-size: 9px !important;">Arrival</th>
            <th style="font-size: 9px !important;">Departure</th>
            <th style="font-size: 9px !important;">Arrival</th>
            <th style="font-size: 9px !important;">Departure</th>
            {{-- <th style="font-size: 9px !important;">Total no. of mins.</th> --}}
        </tr>
    </thead>
    <tbody>
        @foreach($employeeattendance as $employeeatt)
            <tr>
                <td>
                    {{$employeeatt->dayint}} 
                </td>
                <td>
                    {{$employeeatt->amin}}
                </td>
                <td>
                    {{$employeeatt->amout}}
                </td>
                <td>
                    {{$employeeatt->pmin}}
                </td>
                <td>
                    {{$employeeatt->pmout}}
                </td>
                {{-- <td>
                    @if($employeeatt->undertime > 0)
                        {{$employeeatt->undertime}}
                    @endif
                </td> --}}
            </tr>
        @endforeach
    </tbody>
</table>
<br>
<div style="width: 100%; text-indent: 50px; text-align: justify; font-size: 10px !important;">
    I CERTIFY on my honor that the above is true and correct report of number of hours of work performed, record of which was made daily at the time of arrival and at departure from office.
</div>
<br>
<div style="width: 100%; border-bottom: 1px solid black;">&nbsp;</div>
<div style="width: 100%; text-align: center;">Verified to Prescribed Office Hours</div>
<br>
<br>
<div style="width: 100%; text-align: center; border-bottom: 1px solid black;"></div>
<div style="width: 100%; text-align: center;">District Supervisor</div>

