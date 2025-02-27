<link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<style>
    
    /* input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio].present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio].halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio].late:checked:before       { background-color: gold; }

    input[type=radio].halfday:checked:before    { background-color: #6c757d; }

    input[type=radio].absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    td                  { text-transform: uppercase !important; } */

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background-color: #eee; z-index: 100;}
    thead{
        background-color: #eee !important;
    }
    
    .table                      {width:1500px; font-size:90%; text-transform: uppercase; }

/* .table thead th:first-child { position: sticky; left: 0; background-color: #fff;
    z-index: 9999999 } */
    
.table thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
}
/* .table thead th:last-child  { 
    position: sticky !important; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    z-index: 999 !important
} */
/* .table thead {
    
    z-index: 999
} */

/* .table tbody td:last-child  { 
    position: sticky; 
    right: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
    } */

.table tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    width: 150px !important;
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

.table thead th:first-child  { 
        position: sticky; left: 0; 
        width: 150px !important;
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

</style>
              <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        @if(count($dates)>0)
                            @foreach($dates as $dateval)
                                <th class="text-center">{{date('d',strtotime($dateval))}}</th>
                            @endforeach
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(count($students)>0)
                        <tr class="bg-info">
                            <td class="bg-info font-weight-bold">MALE</td>
                            @if(count($dates)>0)
                                <th colspan="{{count($dates)}}"></th>
                            @endif
                        </tr>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'male')
                                <tr>
                                    <td>
                                        {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                    </td>
                                    @if(count($student->attendance)>0)
                                        @foreach($student->attendance as $subjectattendance)
                                            <td class="text-center">
                                                @if(strtolower($subjectattendance->status) == 'present')
                                                    <span class="badge bg-success">PRESENT</span>
                                                @elseif(strtolower($subjectattendance->status) == 'late')
                                                    <span class="badge bg-warning">LATE</span>
                                                @elseif(strtolower($subjectattendance->status) == 'absent')
                                                    <span class="badge bg-danger"  >ABSENT</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                        <tr class="bg-pink">
                            <td class="bg-pink font-weight-bold">FEMALE</td>
                            @if(count($dates)>0)
                                <th colspan="{{count($dates)}}"></th>
                            @endif
                        </tr>
                        @foreach($students as $student)
                            @if(strtolower($student->gender) == 'female')
                                <tr>
                                    <td>
                                        {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                    </td>
                                    @if(count($student->attendance)>0)
                                        @foreach($student->attendance as $subjectattendance)
                                            <td class="text-center">
                                                @if(strtolower($subjectattendance->status) == 'present')
                                                    <span class="badge bg-success">PRESENT</span>
                                                @elseif(strtolower($subjectattendance->status) == 'late')
                                                    <span class="badge bg-warning">LATE</span>
                                                @elseif(strtolower($subjectattendance->status) == 'absent')
                                                    <span class="badge bg-danger"  >ABSENT</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
              </table>