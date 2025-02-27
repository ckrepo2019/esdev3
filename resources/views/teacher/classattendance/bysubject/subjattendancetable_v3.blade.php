
@if(count($students) == 0)
   <style>
       .alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert {
    position: relative;
    padding: .75rem 1.25rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
}

   </style>
<div class="alert alert-danger" role="alert">
    No students enrolled!
  </div>
   @else
   <style>
    
    input[type=radio]                   { visibility: hidden; position: relative;width: 20px; height: 20px; }

    input[type=radio].present:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].late:before       { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0;padding: 0; }

    input[type=radio].halfday:before    { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].absent:before     { content: "";visibility: visible;position: absolute;border: 1px solid black;border-radius: 50%;top: 0;right: 0;bottom: 0;left: 0; }

    input[type=radio].present:checked:before    { font-family: "Font Awesome 5 Free";content: "\f00c";color: green;font-size: 20px;border: 1px solid white; }

    input[type=radio].late:checked:before       { background-color: gold; }

    input[type=radio].halfday:checked:before    { background-color: #6c757d; }

    input[type=radio].absent:checked:before     { font-family: "Font Awesome 5 Pro", "Font Awesome 5 Free";content: "\f00d";color: red;font-size: 20px;border: 1px solid white; }

    td{ text-transform: uppercase !important; }

    .tableFixHead       { overflow-y: auto; height: 500px; }

    .tableFixHead table { border-collapse: collapse; width: 100%; }

    .tableFixHead th,
    .tableFixHead td    { /* padding: 8px 16px; */ }

    .tableFixHead th    { position: sticky; top: 0; background: #ffc107; z-index: 999;}
   </style>
   <div class="card">
      <div class="card-header">
          <div class="row">
              <div class="col-md-6 text-left">
                  <button type="button" class="btn btn-default" id="btn-exportexcel"><i class="fa fa-file-excel"></i> EXCEL</button>
                  {{-- <button type="button" class="btn btn-default" id="btn-exportpdf"><i class="fa fa-file-pdf"></i> PDF</button> --}}
              </div>
              <div class="col-md-6 text-right">
                  <button type="button" class="btn btn-default" id="btn-reload"><i class="fa fa-sync"></i> Reload</button>
                  <button type="button" class="btn btn-primary" id="btn-save"><i class="fa fa-share"></i> Save Changes</button>
              </div>
          </div>
          <div class="row">
              <div class="col-md-12">
                    <label><em>Note: Please click the empty boxes to select status.</em></label><br/>
                    <label><em><small>Click multiple times on the selected empty box to see changes.</small></em></label>
                </div>
          </div>
        {{-- <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-primary">Apple</button>
            <button type="button" class="btn btn-primary">Samsung</button>
            <button type="button" class="btn btn-primary">Sony</button>
          </div> --}}
      </div>
      <div class="card-body  table-responsive p-0" style="height: 700px;">
        <table class="table table-head-fixed text-nowrap table-bordered">
          <thead>
              <tr>
                  <th>Student Name</th>
                  @if(count($dates)>0)
                      @foreach($dates as $date)
                      <th class="text-center eachdate" data-date="{{$date->date}}">
                          {{$date->datestr}}<br/>{{$date->day}}<br/>
                          {{-- <button type="button" class="btn btn-sm btn-default btn-hide">&nbsp;<i class="fa fa-eye"></i>&nbsp;</button> --}}
                          <button type="button" class="btn btn-sm btn-default btn-column-null" data-date="{{$date->date}}">&nbsp;<i class="fa fa-trash"></i>&nbsp;</button>
                          <button type="button" class="btn btn-sm btn-default btn-column-present" data-date="{{$date->date}}">P</button>
                          <button type="button" class="btn btn-sm btn-default btn-column-late" data-date="{{$date->date}}">L</button>
                          <button type="button" class="btn btn-sm btn-default btn-column-absent" data-date="{{$date->date}}">A</button>
                      </th>
                          {{-- <th class="text-center">{{$date->datestr}}<br/>{{$date->day}}</th> --}}
                      @endforeach
                  @endif
              </tr>
          </thead>
          <tbody>
              @if(count($students)>0)
                <tr class="bg-info">
                    <td class="bg-info">MALE</td>
                    <td colspan="{{count($dates)}}"></td>
                </tr>
                  @foreach($students as $student)
                    @if(strtolower($student->gender) == 'male')
                    <tr class="eachstud" data-id="{{$student->id}}">
                          <td>
                              <div class="row mb-1">
                                  <div class="col-md-12">
                                      <span class="badge badge-info">{{$student->sid}}</span>
                                      <span class="badge badge-info">{{$student->description}}</span>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-12">
                                      {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-12">
                                      <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                      <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                      <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                      <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                                  </div>
                              </div>
                          </td>
                        {{-- <tr>
                            <td>
                                <span class="badge badge-info">{{$student->sid}}</span>
                                <span class="badge badge-info">{{$student->description}}</span><br/>
                                {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                            </td> --}}
                            @if(count($student->attendance)>0)
                                @foreach($student->attendance as $att)
                                <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                    data-studid="{{$student->id}}"
                                    data-status="{{$att->status}}"
                                    data-tdate="{{$att->tdate}}"
                                    data-newstatus="{{$att->status}}"
                                    clicked="0" >
                                {{$att->status}} 
                                </td>
                                @endforeach
                            @endif
                        </tr>
                    @endif
                  @endforeach
                  <tr class="bg-pink">
                      <td class="bg-pink">FEMALE</td>
                      <td colspan="{{count($dates)}}"></td>
                  </tr>
                    @foreach($students as $student)
                      @if(strtolower($student->gender) == 'female')
                      <tr class="eachstud" data-id="{{$student->id}}">
                            <td>
                                <div class="row mb-1">
                                    <div class="col-md-12">
                                        <span class="badge  bg-pink">{{$student->sid}}</span>
                                        <span class="badge  bg-pink">{{$student->description}}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-sm btn-default btn-row-null m-0 pt-1 pb-1 pr-1 pl-1" data-id="{{$student->id}}"><i class="fa fa-trash"></i></button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-present m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">P</button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-late m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">L</button>
                                        <button type="button" class="btn btn-sm btn-default btn-row-absent m-0 pt-0 pb-0 pr-1 pl-1" data-id="{{$student->id}}">A</button>
                                    </div>
                                </div>
                            </td>
                              @if(count($student->attendance)>0)
                                  @foreach($student->attendance as $att)
                                  <td data-class="attstatus"
                                  class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                  data-studid="{{$student->id}}"
                                  data-status="{{$att->status}}"
                                  data-tdate="{{$att->tdate}}"
                                  data-newstatus="{{$att->status}}"
                                  clicked="0">
                                  {{$att->status}} 
                                  </td>
                                  @endforeach
                              @endif
                          </tr>
                      @endif
                    @endforeach
              @endif
          </tbody>
        </table>
    </div>
  </div>
   @endif