<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            </div>

            <div class="col-md-6 text-right">
                @if($levelid > 0)
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered" style="font-size: 15px;">
            <thead class="text-center">
                <tr>
                    <th style="width: 10%:">Student ID</th>
                    <th style="width: 10%:">LRN</th>
                    <th>Student Name</th>
                    <th>Grade Level / Year Level</th>
                </tr>
            </thead>
            @foreach($students as $student)
                <tr>
                    <td>{{$student->sid}}</td>
                    <td>{{$student->lrn}}</td>
                    <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                    <td>{{$student->levelname}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<script>
    
    $('.table').DataTable({
        "destroy": true,
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": false,
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });
</script>