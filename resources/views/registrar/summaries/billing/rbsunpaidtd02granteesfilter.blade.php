
        @if(count($students)==0)
        <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                No applications approved by the finance!
            </div>
        </div>
        </div>
        
        @else
        <div class="card">
    <div class="card-body p-2" style="overflow: hidden;">
        <div class="row p-0">
            <div class="col-md-12 text-right mb-2">
                <button type="button" class="btn btn-default btn-export-pdf"><i class="fa fa-file-pdf"></i> Export to PDF</button>
                <button type="button" class="btn btn-default btn-export-excel"><i class="fa fa-file-excel"></i> Export to Excel</button>
                {{-- <button type="button" class="btn btn-default btn-export-excel"><i class="fa fa-file-excel"></i> Export to EXCEL</button> --}}
            </div>
            <div class="col-md-12 p-0">
                <table class="table table-hover m-0" style="font-size: 13px;" cellspacing="0" width="100%" id="table-results">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>SID</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>GWA Previous Sem</th>
                            <th># of units</th>
                            <th>Actual Tuition And<br/>Other School Fees</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            <tr>
                                {{-- <td></td> --}}
                                <td>
                                    <div class="form-group clearfix">
                                      <div class="icheck-primary d-inline">
                                        <input type="checkbox" id="checkboxPrimary{{$student->id}}"  class="check-approve" data-studid="{{$student->id}}" @if($student->tesstatus == 1) checked @endif>
                                        <label for="checkboxPrimary{{$student->id}}">
                                        </label>
                                      </div>
                                    </div>
                                </td>
                                <td>{{$student->sid}}</td>
                                <td>                                    
                                   {{ucwords(strtolower($student->lastname))}}, {{ucwords(strtolower($student->firstname))}} {{ucwords(strtolower($student->middlename))}} {{$student->suffix}}                                 
                                </td>
                                <td class="courseid" data-id="{{$student->courseid}}">{{$student->courseabrv}}</td>
                                <td class="text-center gwa" data-id="{{$student->gwa}}"></td>
                                <td class="text-center numofunits" data-id="{{$student->units}}">{{$student->units}}</td>
                                <td class="text-right actualfees" data-id="{{$student->overallfees}}">{{number_format($student->overallfees,2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif