

<div class="card">
    <div class="card-header">
        <div class="row mb-2">
            <div class="col-md-12 text-left">
                <button type="button" class="btn btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                @if(count($students) == 0)
            
                @else
                <table class="table" id="results-table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Grade Level</th>
                            <th>Section</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $key => $student)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$student->sid}}</td>
                                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</td>
                                <td>{{strtoupper($student->gender)}}</td>
                                <td>{{$student->levelname}}</td>
                                <td>{{$student->sectionname}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>