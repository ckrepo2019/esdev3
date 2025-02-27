<div class="card">
    <div class="card-header">
        <div class="row mb-2">
            <div class="col-12 text-right">
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
            </div>
        </div>
        @php
            $array_levels = array_chunk(collect($gradelevels)->toArray(), 6);
        @endphp
        <table style="font-size: 12px; width: 100%;" border="1">
            <thead>
                <tr>
                    <th>Grade Level</th>
                    <th style="width: 3% !important;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                    <th>Grade Level</th>
                    <th style="width: 3%;">Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($array_levels as $gradelevel)
                    
                    <tr>
                        @foreach($gradelevel as $eachlevel)
                        <td class="p-0">{{$eachlevel->levelname}}</td>
                        <td class="p-0 text-center" style="vertical-align: middle;">{{collect($students)->where('levelid',$eachlevel->id)->count()}}</td>
                        @endforeach
                    </tr>
                @endforeach
                <tr>
                    <th colspan="11" class="text-right">TOTAL</th>
                    <th class="p-0 text-center" style="vertical-align: middle;">{{count($students)}}</th>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-body">
        <table id="table-students" class="table table-hover" style="font-size: 12px;">
            <thead>
                <tr>
                    <th>SID</th>
                    <th>LRN</th>
                    <th>Student</th>
                    <th>Level</th>
                    <th>Admission Status</th>
                    <th>Date Enrolled</th>
                    <th>Date Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{$student->sid}}</td>
                        <td>{{$student->lrn}}</td>
                        <td>{{$student->studentname}}</td>
                        <td>{{$student->levelname}}</td>
                        <td>{{$student->studentstatus}}</td>
                        <td>{{date('M d, Y', strtotime($student->dateenrolled))}}</td>
                        <td>{{date('M d, Y', strtotime($student->lastdate))}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    $("#table-students").DataTable({
        "ordering": false
    })
</script>