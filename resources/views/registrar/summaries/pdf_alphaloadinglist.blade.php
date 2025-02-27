<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>Document</title>
<style>
 
    html{        
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
    }
    .logo{
        width: 100%;
        table-layout: fixed;
    }
    .header{
        width: 100%;
    }
    table tr {
        page-break-inside: auto !important;
    }
    @page{
        size: 13in 8.5in;
    }
    table{
        border-collapse: collapse;
    }
</style>
</head>
<body>
    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_text(40, 580, "Page {PAGE_NUM} of {PAGE_COUNT}", '', 9, array(0,0,0));

        }
    </script>
    <div style="width: 100%; text-align: center; font-weight: bold;">
        {{DB::table('schoolinfo')->first()->schoolname}}
    </div>
    <div style="width: 100%; text-align: center;">
        {{DB::table('schoolinfo')->first()->address}}
    </div>
    <div style="width: 100%; text-align: right;">
        {{date('m/d/Y')}}
    </div>
    <div style="width: 100%; text-align: center;">
       {{$semester}} S.Y. {{$sydesc}}
    </div>
    <table style="width: 100%;" border="1">
        <thead>
            <tr>
                <th style="width: 3%;">#</th>
                <th style="width: 8%;">Subject</th>
                <th style="width: 25%;">Description</th>
                <th style="width: 10%;">Course</th>
                <th style="width: 10%;">Section</th>
                <th>TimeBegin</th>
                <th>TimeEnd</th>
                <th style="width: 5%;">Days</th>
                <th>Room</th>
                <th style="width: 3%;">Units</th>
                <th style="width: 3%;">Enrolled</th>
                <th style="width: 15%;">Instructor</th>
            </tr>
        </thead>
        @if(count($schedules)>0)
            @foreach($schedules as $schedulekey=>$schedule)
                @php
                $exclude = array('and','in','of','the','on','at','or','for','sa');
                $subjdesc = strtolower($schedule->subjectname);
                $words = explode(' ', $subjdesc);
                foreach($words as $key => $word) {
                    if(in_array($word, $exclude)) {
                        continue;
                    }
                    $words[$key] = ucfirst($word);
                }
                $subjectname = implode(' ', $words);
                @endphp
                <tr>
                    <td style="text-align: center;">{{$schedulekey+1}}</td>
                    <td>{{$schedule->subjcode}}</td>
                    <td>{{$subjectname}}</td>
                    <td>{{$schedule->courseabrv ?? $schedule->coursename ?? null}}</td>
                    <td>{{$schedule->sectionname ?? ''}}</td>
                    <td style="text-align: center;">{{date('h:i A ', strtotime($schedule->stime))}}</td>
                    <td style="text-align: center;">{{date('h:i A ', strtotime($schedule->etime))}}</td>
                    <td>{{$schedule->description}}</td>
                    <td>{{$schedule->roomname ?? ''}}</td>
                    <td style="text-align: center;">{{$schedule->units}}</td>
                    <td style="text-align: center;">{{$schedule->numstudents}}</td>
                    <td>{{strtoupper($schedule->teachername ?? $schedule->lastname.', '.$schedule->firstname)}}</td>
                </tr>
            @endforeach
        @endif
    </table>
</body>
</html>