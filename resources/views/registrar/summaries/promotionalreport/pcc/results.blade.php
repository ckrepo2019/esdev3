
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
@php
    $courses = collect($students)->groupBy('coursename');
@endphp
<div class="row">
    <div class="col-md-12 text-left">
        <button type="button" class="btn btn-default" id="btn-export-excel"><i class="fa fa-file-excel"></i> Export to Excel</button>
    </div>
    <div class="col-md-12">
        <table class="table-head-fixed table-bordered" id="studentstable" style="font-size: 12px;width: 100%;">
          <thead>
              <tr>
                  <th style="text-align: center;">Program Name</th>
                  <th>Program Major</th>
                  <th>Student No.</th>
                  <th>Year Level</th>
                  <th>Last Name</th>
                  <th>First Name</th>
                  <th>Middle Name</th>
                  <th>EXT. Name</th>
                  <th>Sex</th>
                  <th>Nationality</th>
                  <th>Course Code</th>
                  <th>Course Description</th>
                  <th>No. of Units</th>
                  <th>Grades</th>
                  <th>Remarks</th>
              </tr>
          </thead>
          <tbody>
            @if(count($courses)>0)
                @foreach($courses as $coursekey=>$course)
                    <tr>
                        <td><div style=" width: 300px;white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;">{{$coursekey}}</div></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @php
                        $students = collect($course)->sortBy('sortname')->sortBy('yearid')->values();
                    @endphp
                    @if(count($students)>0)
                        @foreach($students as $student)
                        <tr>
                            <td></td>
                            <td>{{$student->major}}</td>
                            <td>{{$student->sid}}</td>
                            <td class="text-center">{{$student->yearid}}</td>
                            <td>{{$student->lastname}}</td>
                            <td>{{$student->firstname}}</td>
                            <td class="text-center">{{$student->middlename}}</td>
                            <td>{{$student->suffix}}</td>
                            <td>{{$student->gender}}</td>
                            <td>{{$student->nationality}}</td>
                            @if(count($student->subjects)==0)
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            @else
                            <td>{{$student->subjects[0]->subjectcode}}</td>
                            <td>{{$student->subjects[0]->subjectname}}</td>
                            <td class="text-center">{{$student->subjects[0]->subjunit}}</td>
                            <td>{{$student->subjects[0]->subjgrade}}</td>
                            <td>@if($student->subjects[0]->subjgrade > 0 ){{$student->subjects[0]->subjgrade < 5.0 ? 'PASSED' : 'FAILED'}}@endif</td>
                            @endif
                        </tr>
                        @if(count($student->subjects)>1)
                            @foreach($student->subjects as $eachsubjkey=>$eachsubj)
                                @if($eachsubjkey>0)
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td></td>
                                    <td class="text-center"></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{$eachsubj->subjectcode}}</td>
                                    <td>{{$eachsubj->subjectname}}</td>
                                    <td class="text-center">{{$eachsubj->subjunit}}</td>
                                    <td>{{$eachsubj->subjgrade}}</td>
                                    <td>@if($eachsubj->subjgrade > 0 ){{$eachsubj->subjgrade < 5.0 ? 'PASSED' : 'FAILED'}}@endif</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                        @endforeach
                    @endif
                @endforeach
            @endif
          </tbody>
        </table>
    </div>
</div>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
      <script>
          var oTable = $('#studentstable').DataTable({
      "columnDefs": [
        { "width": "300", "targets": 0 }
      ],
            scrollY:        "500px",
            scrollX:        true,
            scrollCollapse: true,
            paging:         false,
            "ordering": false,
            fixedColumns:   {
                leftColumns: 1
            },
        "aaSorting": []
          })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
          $('#myInputTextField').keyup(function(){
              oTable.search($(this).val()).draw() ;
          })
          $('th').unbind('click.DT');
      </script>