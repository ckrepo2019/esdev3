
<html>
    <head>
        <style>
    html{
        /* text-transform: uppercase; */
        
    font-family: Arial, Helvetica, sans-serif;
    }
    table{
        border-collapse: collapse;
    }
    @page{
        margin: 45px 20px 70px 20px;
    }
</style>
</head>
<body>
<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_text(260, 790, "page {PAGE_NUM} of {PAGE_COUNT} pages", '', 9, array(0,0,0));

    }
</script> 
<div style="font-size: 12px; font-weight: bold; text-align: center;">{{DB::table('schoolinfo')->first()->schoolname}}</div>
<div style="font-size: 12px; text-align: center;">
    @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'dcc')
    Toril, Davao City
    @else
    {{DB::table('schoolinfo')->first()->address}}
    @endif
</div>
<div style="font-size: 12px; text-align: center;">
    {{$semester}} {{$sydesc}}
</div>
<div style="font-size: 12px; text-align: center;">
    <span style="font-weight: bold;">{{$subjects[0]->subjcode}}</span> - {{$subjects[0]->subjectname}}
</div>
<div style="font-size: 12px; text-align: center;">
    {{$reportname}}
</div>
<br/>
<table style="width: 100%; table-layout: fixed;">
    <thead style=" font-size: 11px;">
        <tr>
            <th style="width: 12%; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px; text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I.D. No.</th>
            <th style="width: 35%; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px; text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;Name</th>
            <th style="border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px;">Sex</th>
            <th style="border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px;">Year</th>
            <th style="width: 20%; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px; text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;Course</th>
            <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px;">Grade</th>
            <th style="width: 10%; border-top: 1px dashed black; border-bottom: 1px dashed black; padding: 7px 0px; text-align: left;padding-left: 10px;">Units</th>
        </tr>
    </thead>
    @if(count($subjects[0]->students)>0)
        @foreach($subjects[0]->students as $eachstudent)
            <tr style=" font-size: 11px;">
                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{$eachstudent->sid}}</td>
                <td>&nbsp;&nbsp;&nbsp;&nbsp;{{$eachstudent->lastname}}, {{$eachstudent->firstname}} {{ucwords(mb_strtolower($eachstudent->middlename))}} {{$eachstudent->suffix}}</td>
                <td style=" text-align: center;">{{$eachstudent->gender[0]}}</td>
                <td style=" text-align: center;">{{str_replace(' YEAR', '', $eachstudent->yearlevel)}}</td>
                <td style="padding-left: 14px;">{{$eachstudent->courseabrv}}</td>
                <td style=" text-align: center;">@if($reporttype == 'promotional'){{$eachstudent->eqgrade}}@endif</td>
                <td style="padding-left: 10px;">{{$eachstudent->units}}</td>
            </tr>
        @endforeach
    @endif
</table>
<br/>
<br/>
<br/>
<table style="width: 100%;">
    <tr>
        <td></td>
        <td style="width: 30%; text-align: center; font-weight: bold; font-size: 12px;">{{$signatory->name ?? null}}</td>
    </tr>
    <tr>
        <td></td>
        <td style=" text-align: center; font-size: 11px;">Registar Consultant</td>
    </tr>
</table>
</body>
</html>