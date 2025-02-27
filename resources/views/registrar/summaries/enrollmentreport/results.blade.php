
<link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
<style>
    
    th, td { white-space: nowrap; }
    div.dataTables_wrapper {
        /* width: 800px; */
        margin: 0 auto;
    }
#studentstable th{
    border: 1px solid #ddd !important;
}
#studentstable td{
    border: 1px solid #ddd !important;
}
.dataTables_filter, .dataTables_info { display: none; }
.dataTables_wrapper{
    margin: 0px !important;
    width: 100% !important;
}
</style>
    <div class="card">
    <div class="card-header">
        {{-- <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
            </div>
            <div class="col-md-6 text-right">
                @if($levelid > 0)
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                @endif
            </div>
        </div> --}}
        <div class="row">
            
            <div class="col-md-3">
                <label>Registrar</label>
                <input type="text" class="form-control" id="input-registrar" value="{{collect($signatories)->where('title','Registrar')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-3">
                <label>President</label>
                <input type="text" class="form-control" id="input-president" value="{{collect($signatories)->where('title','President')->first()->name ?? null}}"/>
            </div>
            <div class="col-md-6 text-right">
                <label>&nbsp;</label><br/>
                <button type="button" class="btn btn-sm btn-warning">{{count($students)}} Student(s)</button>
                @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ccsa')
                <button type="button" class="btn btn-sm btn-default" id="btn-export-excel"><i class="fa fa-file-pdf"></i> Export to Excel</button>
                @else
                <button type="button" class="btn btn-sm btn-default" id="btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                @endif
            </div>

        </div>
    </div>
    <div class="card-body" style="overflow-y: auto;">
        <div class="row">
            <div class="col-md-6">
                <input type="text" class="form-control form-control-sm" id="myInputTextField" placeholder="Search student..."/>
            </div>
        </div>
        <table class="table table-bordered table-head-fixed" id="studentstable" style="font-size: 12px;">
        {{-- <table class="table table-bordered" style="font-size: 12px;"> --}}
            <thead>
                <tr>
                    {{-- <th style="width: 10%:">Student ID</th/>
                    <th style="width: 10%:">LRN</th> --}}
                    <th style="width: 150px !important;">Student Name</th>
                    <th>S<br/>E<br/>X</th>
                    <th>COURSE</th>
                    <th>Y<br/>E<br/>A<br/>R</th>
                    <th>BIRTHDATE</th>
                    @for($x = 0; $x < $maxsubjects; $x++)
                    <th>Subject-{{$x+1}}</th>
                    <th>U<br/>N<br/>I<br/>T<br/>S</th>
                    @endfor
                    <th>Total<br/>Units</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        {{-- <td>{{$student->sid}}</td>
                        <td>{{$student->lrn}}</td> --}}
                        <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                        <td>{{$student->gender[0]}}</td>
                        <td>{{$student->courseabrv}}</td>
                        <td>{{$student->yearid}}</td>
                        <td>@if($student->dob != null){{date('m/d/Y', strtotime($student->dob))}}@endif</td>
                        @for($x = 0; $x < $maxsubjects; $x++)
                            <td>@if(isset($student->subjects))@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjcode ?? $student->subjects[$x]->subjectcode}} @endif @endif</td>
                            <td>@if(isset($student->subjects))@if(isset($student->subjects[$x])) {{$student->subjects[$x]->subjunit}} @endif @endif</td>
                        @endfor
                        <td>@if(isset($student->subjects)){{collect($student->subjects)->sum('subjunit')}}@endif</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script>    
    var oTable = $('#studentstable').DataTable({
"columnDefs": [
  { "width": "200", "targets": 0 }
],
      scrollY:        "500px",
      scrollX:        true,
      scrollCollapse: true,
      paging:         false,
      "ordering": false,
      fixedColumns:   {
          leftColumns: 1,
          rightColumns: 1
      },
  "aaSorting": []
    })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
    $('#myInputTextField').keyup(function(){
        oTable.search($(this).val()).draw() ;
    })
    $('th').unbind('click.DT');
    // $('.table').DataTable({
    //     "paging": true,
    //     "lengthChange": false,
    //     "searching": true,
    //     "ordering": false,
    //     "info": false,
    //     "autoWidth": false,
    //     "responsive": false,
    // });
</script>