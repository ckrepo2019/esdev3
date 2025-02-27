

<style>
    * {
        
        font-family: Arial, Helvetica, sans-serif;
    }
    td{
        padding: 2px;
    }
</style>
<table style="width: 100%; font-size: 12px; border-collapse: collapse;" border="1">
    <thead>
        <tr>
            <th colspan="4" style="text-align: left;">
                Section: {{$sectioninfo->sectionname}}
            </th>
        </tr>
    </thead>
    @foreach($students as $key => $student)
        <tr>
            <td style="width: 5%; text-align: center;">{{$key+1}}</td>
            <td style="width: 45%;">{{$student->lastname}}, {{$student->firstname}}</td>
            <td style="width: 35%;">{{$student->teamsemail}}</td>
            <td style="width: 15%;">{{$student->teamspassword}}</td>
        </tr>
    @endforeach
</table>