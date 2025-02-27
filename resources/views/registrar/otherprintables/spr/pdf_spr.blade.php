
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

        <title>Document</title>

        <style>
            * { font-family: Arial, Helvetica, sans-serif; }
            table{
                border-collapse: collapse;
            }
            td, th{
                padding: 1px;
            }
            @page{
                margin: 40px 25px;
            }
        </style>
    </head>
    <body>
        <table style="width: 100%; table-layout: fixed; margin-bottom: 20px;">
            <tr>
                <td rowspan="3" style="text-align: right;">
                    <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="70px">
                </td>
                <td style="width: 45s%; font-weight: bold; text-align: center; font-size: 18px;">
                    {{DB::table('schoolinfo')->first()->schoolname}}
                </td>
                <td rowspan="3"></td>
            </tr>
            <tr>
                <td style="font-size: 13px; text-align: center;">{{DB::table('schoolinfo')->first()->address}}</td>
            </tr>
            <tr style="font-weight: bold; text-align: center; font-size: 17px;">
                <td>Student Permanent Record</td>
            </tr>
        </table>
        <div style="width: 100%; margin-top: 10px; font-size: 14px; padding-left: 20px; border-bottom: 1px dashed black; line-height: 10px; font-weight: bold;">
            {{$studentinfo->sid}} {{$studentinfo->lastname}}, {{$studentinfo->firstname}} {{$studentinfo->middlename}}<br/>&nbsp;
        </div>
        <table style="width: 100%; font-size: 12px; table-layout: fixed;">
            <thead>
                <tr>
                    <th colspan="2" style="width: 20%; border-bottom: 1px dashed black;">Subject Code</th>
                    <th style="width: 45%; border-bottom: 1px dashed black;">Description</th>
                    <th style="border-bottom: 1px dashed black;">Grade</th>
                    <th style="border-bottom: 1px dashed black;">Units</th>
                    <th style="width: 10%; border-bottom: 1px dashed black;">Remarks</th>
                    <td style="border-bottom: 1px dashed black;"></td>
                </tr>
            </thead>
            @foreach($records as $record)
                @if(count($record->subjects)>0)
                <tr>
                    <th colspan="2">{{DB::table('semester')->where('id', $record->semid)->first()->semester}} {{DB::table('sy')->where('id', $record->syid)->first()->sydesc}}</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @foreach($record->subjects as $eachsubject)
                    <tr>
                        <td>&nbsp;</td>
                        <td style="width: 15% !important; vertical-align: top;">{{$eachsubject->subjectcode}}</td>
                        <td style="vertical-align: top;">{{ucwords(strtolower($eachsubject->subjectname))}}</td>
                        <td style="text-align: center; vertical-align: top;">{{$eachsubject->eqgrade}}</td>
                        <td style="text-align: center; vertical-align: top;">{{$eachsubject->units}}</td>
                        <td style="text-align: center; vertical-align: top;">{{$eachsubject->remarks}}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">&nbsp;</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        </table>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 12px;">
            <tr>
                <td></td>
                <td style="width: 30%; font-weight: bold; text-align: center;">&nbsp;{{$registrar}}&nbsp;</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: center;">Registrar(Consultant)</td>
            </tr>
        </table>
        <br/>
        <br/>
        <table style="width: 100%; font-size: 10.5px;">
            <tr>
                <td style="width: 25%; vertical-align: top;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GRADING SYSTEM USED:</td>
                <td>1.0 - 100 1.1 - 99 1.2 - 98 1.3 - 97 1.4 - 96 1.5 - 95 1.6 - 94 1.7 - 93 1.8 - 92 1.9 - 91 2.0 - 90 2.1 - 89 2.2 - 88 2.3 - 87 2.4 - 86 2.5 - 85 2.6 - 84 2.7 - 83 2.8 - 82 2.9 - 81 3.0 - 80 3.1 - 79 3.2 - 78  3.3 - 77 3.4 - 76 3.5 - 75 5.0 - Failed 9.0 - Dropped</td>
            </tr>
        </table>
    </body>
</html>