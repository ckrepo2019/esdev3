@php
        $students = collect($students)->where('crashedout','0')->values();
@endphp

<style>
    table td, table th {
        padding: 0px !important;
    }
    thead{
        background-color: #eee !important;
    }
    
    .table                      {font-size:90%; text-transform: uppercase; }
    
#thetable thead th:first-child  { 
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable tbody td:first-child  {  
    position: sticky; 
    left: 0; 
    background-color: #fff; 
    background-color: #fff; 
    outline: 2px solid #dee2e6;
    outline-offset: -1px;
}

#thetable thead th:first-child  { 
        position: sticky; left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
}

.dataTables_filter, .dataTables_info { display: none; }
</style>
<div class="card-body">
    <div class="row">
        @if($locksf2 == 0)
        <div class="col-md-6">
            <label><em>Note: Please click the empty boxes to select status.</em></label><br/>
            <label><em><small>Click multiple times on the selected empty box to see changes.</small></em></label>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-primary save-button" id="btn-save" ><i class="fa fa-share"></i> Save Changes</button>
          {{-- <div class="btn-group btn-group-sm">
              <button type="button" class="btn btn-primary">Apple</button>
              <button type="button" class="btn btn-primary">Samsung</button>
              <button type="button" class="btn btn-primary">Sony</button>
            </div> --}}
        </div>
        @endif
        <div class="col-md-6">
            <input type="text" class="form-control" id="myInputTextField" placeholder="Search student...">
        </div>
        
        @if($locksf2 == 1)
        <div class="col-md-6">
            <h6><i class="fa fa-lock"></i>&nbsp;Locked: You can't make changes on this Attendance.</h6>
        </div>
        @endif
    </div>
    
    @if($locksf2 == 0)
    <table class="table-head-fixed text-nowrap table-bordered" id="thetable" style="font-size: 12px;">
      <thead>
          <tr>
              <th style="width: 100px !important; text-align: center;">Student Name</th>
              @if(count($dates)>0)
                  @foreach($dates as $date)
                      <th class="text-center eachdate" data-date="{{$date->date}}" style="width: 100px !important;">
                          {{$date->datestr}}<br/>
                          {{$date->day}}<br/>
                          <select class="form-control form-control-sm select-column-att" data-date="{{$date->date}}">
                              <option value="reset"></option>
                              <option value="reset">Reset</option>
                              <option value="present">Present</option>
                              <option value="presentam">AM Present</option>
                              <option value="presentpm">PM Present</option>
                              <option value="absent">Absent</option>
                              <option value="absentam">AM Absent</option>
                              <option value="absentpm">PM Absent</option>
                              <option value="late">Late</option>
                              <option value="lateam">AM Late</option>
                              <option value="latepm">PM Late</option>
                              <option value="cc">CC</option>
                              <option value="ccam">AM CC</option>
                              <option value="ccpm">PM CC</option>
                          </select>
                      </th>
                  @endforeach
              @endif
          </tr>
      </thead>
      <tbody>
          @if(count($students)>0)
          <tr>
              <td class="text-center"><h4>MALE</h4></td>
              @if(count($dates)>0)
                  @foreach($dates as $date)
                  <td></td>
                  @endforeach
              @endif
          </tr>
              @foreach($students as $student)
                @if(strtolower($student->gender) == 'male')
                    <tr class="eachstud" data-id="{{$student->id}}">
                          <td style="vertical-align: middle;">
                              <div class="row">
                                  <div class="col-md-4 align-self-center">
                                      <select class="form-control form-control-sm select-row-att" data-id="{{$student->id}}">
                                          <option value="reset"></option>
                                          <option value="reset">Reset</option>
                                          <option value="present">Present</option>
                                          <option value="presentam">AM Present</option>
                                          <option value="presentpm">PM Present</option>
                                          <option value="absent">Absent</option>
                                          <option value="absentam">AM Absent</option>
                                          <option value="absentpm">PM Absent</option>
                                          <option value="late">Late</option>
                                          <option value="lateam">AM Late</option>
                                          <option value="latepm">PM Late</option>
                                          <option value="cc">CC</option>
                                          <option value="ccam">AM CC</option>
                                          <option value="ccpm">PM CC</option>
                                      </select>
                                  </div>
                                  <div class="col-md-8" style="font-size: 14px;">
                                      <span class="badge badge-info">{{$student->sid}}</span>
                                      <span class="badge badge-info">{{$student->description}}</span><br/>
                                      @if($student->crashedout == 0)
                                      <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                      @else
                                      <del>{{$student->lastname}}, {{$student->firstname}}
                                      </del>
                                      @endif
                                  </div>
                              </div>
                          </td>
                        @if(count($student->attendance)>0)
                            @foreach($student->attendance as $att)
                            <td data-class="attstatus" class="text-center @if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                                data-studid="{{$student->id}}"
                                data-tdate="{{$att->tdate}}"
                                 @if($att->status == null)
                                  data-status="PRESENT" 
                                  data-newstatus="PRESENT"
                                  style="background-color: #8fdba3" 
                                  clicked="1"
                                 @else 
                                      data-status="{{$att->status}}"
                                      data-newstatus="{{$att->status}}"
                                      clicked="0"
                                  @endif
                                  >
                                 @if($att->status == null)
                                 Present<br/> <small>(Default)</small>
                                 @elseif($att->status == 'AM PRESENT')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-success">PRESENT</span>
                                 @elseif($att->status == 'PM PRESENT')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-success">PRESENT</span>
                                 @elseif($att->status == 'AM ABSENT')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-danger">ABSENT</span>
                                 @elseif($att->status == 'PM ABSENT')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-danger">ABSENT</span>
                                 @elseif($att->status == 'AM LATE')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-warning">LATE</span>
                                 @elseif($att->status == 'PM LATE')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-warning">LATE</span>
                                 @elseif($att->status == 'AM CC')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-secondary">CC</span>
                                 @elseif($att->status == 'PM CC')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-secondary">CC</span>
                                 @else
                                 {{$att->status}} 
                                 @endif 
								 <br>
								 @if($att->ttime != null)
								  <span class="badge timebadge" style="font-size:.7rem !important" data-studid="{{$student->id}}"
										data-tdate="{{$att->tdate}}">{{$att->ttime}}</span>
								 @endif
                            </td>
                            @endforeach
                        @endif
                    </tr>
                @endif
              @endforeach
              <tr>
              <td class="text-center" style="vertical-align: middle;"><h4>FEMALE</h4></td>
                  @if(count($dates)>0)
                      @foreach($dates as $date)
                      <td></td>
                      @endforeach
                  @endif
              </tr>
                @foreach($students as $student)
                  @if(strtolower($student->gender) == 'female')
                      <tr class="eachstud" data-id="{{$student->id}}">
                          <td>
                              <div class="row">
                                  <div class="col-md-4 align-self-center">
                                      <select class="form-control form-control-sm select-row-att" data-id="{{$student->id}}">
                                          <option value="reset"></option>
                                          <option value="reset">Reset</option>
                                          <option value="present">Present</option>
                                          <option value="presentam">AM Present</option>
                                          <option value="presentpm">PM Present</option>
                                          <option value="absent">Absent</option>
                                          <option value="absentam">AM Absent</option>
                                          <option value="absentpm">PM Absent</option>
                                          <option value="late">Late</option>
                                          <option value="lateam">AM Late</option>
                                          <option value="latepm">PM Late</option>
                                          <option value="cc">CC</option>
                                          <option value="ccam">AM CC</option>
                                          <option value="ccpm">PM CC</option>
                                      </select>
                                  </div>
                                  <div class="col-md-8" style="font-size: 14px;">
                                      <span class="badge badge-warning">{{$student->sid}}</span>
                                      <span class="badge badge-warning">{{$student->description}}</span><br/>
                                      @if($student->crashedout == 0)
                                      <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                      @else
                                      <del>{{$student->lastname}}, {{$student->firstname}}
                                      </del>
                                      @endif
                                  </div>
                              </div>
                          </td>
                          @if(count($student->attendance)>0)
                              @foreach($student->attendance as $att)
                              <td data-class="attstatus"
                              class=" text-center @if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
                              data-studid="{{$student->id}}"
                              data-tdate="{{$att->tdate}}"
                              @if($att->status == null)
                               data-status="PRESENT" 
                               data-newstatus="PRESENT"
                               style="background-color: #8fdba3" 
                               clicked="1"
                              @else 
                                   data-status="{{$att->status}}"
                                   data-newstatus="{{$att->status}}"
                                   clicked="0"
                               @endif
                               >
                               @if($att->status == null)
                               Present<br/> <small>(Default)</small>
                               @elseif($att->status == 'AM PRESENT')
                               <span class="badge badge-success">AM</span> <span class="badge badge-success">PRESENT</span>
                               @elseif($att->status == 'PM PRESENT')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-success">PRESENT</span>
                               @elseif($att->status == 'AM ABSENT')
                               <span class="badge badge-success">AM</span> <span class="badge badge-danger">ABSENT</span>
                               @elseif($att->status == 'PM ABSENT')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-danger">ABSENT</span>
                               @elseif($att->status == 'AM LATE')
                               <span class="badge badge-success">AM</span> <span class="badge badge-warning">LATE</span>
                               @elseif($att->status == 'PM LATE')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-warning">LATE</span>
                               @elseif($att->status == 'AM CC')
                               <span class="badge badge-success">AM</span> <span class="badge badge-secondary">CC</span>
                               @elseif($att->status == 'PM CC')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-secondary">CC</span>
                               @else
                               {{$att->status}} 
                               @endif
                              <br>
								 @if($att->ttime != null)
								  <span class="badge timebadge" style="font-size:.7rem !important" data-studid="{{$student->id}}"
										data-tdate="{{$att->tdate}}">{{$att->ttime}}</span>
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
    @else
    <table class="table-head-fixed text-nowrap table-bordered" id="thetable" style="font-size: 12px;">
      <thead>
          <tr>
              <th style="width: 100px !important; text-align: center;">Student Name</th>
              @if(count($dates)>0)
                  @foreach($dates as $date)
                      <th class="text-center" data-date="{{$date->date}}" style="width: 100px !important;">
                          {{$date->datestr}}<br/>
                          {{$date->day}}
                      </th>
                  @endforeach
              @endif
          </tr>
      </thead>
      <tbody>
          @if(count($students)>0)
          <tr>
            <td class="text-center" style="vertical-align: middle;"><h4>MALE</h4></td>
              @if(count($dates)>0)
                  @foreach($dates as $date)
                  <td></td>
                  @endforeach
              @endif
          </tr>
              @foreach($students as $student)
                @if(strtolower($student->gender) == 'male')
                    <tr data-id="{{$student->id}}">
                          <td>
                              <div class="row">
                                <div class="col-md-12" style="font-size: 14px;">
                                      <span class="badge badge-info">{{$student->sid}}</span>
                                      <span class="badge badge-info">{{$student->description}}</span><br/>
                                      @if($student->crashedout == 0)
                                      <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                      @else
                                      <del>{{$student->lastname}}, {{$student->firstname}}
                                      </del>
                                      @endif
                                  </div>
                              </div>
                          </td>
                        @if(count($student->attendance)>0)
                            @foreach($student->attendance as $att)
                            <td class="text-center @if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif"
                                data-studid="{{$student->id}}"
                                data-tdate="{{$att->tdate}}"
                                 @if($att->status == null)
                                  data-status="PRESENT" 
                                  data-newstatus="PRESENT"
                                  style="background-color: #8fdba3" 
                                  clicked="1"
                                 @else 
                                      data-status="{{$att->status}}"
                                      data-newstatus="{{$att->status}}"
                                      clicked="0"
                                  @endif
                                  >
                                 @if($att->status == null)
                                 Present<br/> <small>(Default)</small>
                                 @elseif($att->status == 'AM PRESENT')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-success">PRESENT</span>
                                 @elseif($att->status == 'PM PRESENT')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-success">PRESENT</span>
                                 @elseif($att->status == 'AM ABSENT')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-danger">ABSENT</span>
                                 @elseif($att->status == 'PM ABSENT')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-danger">ABSENT</span>
                                 @elseif($att->status == 'AM LATE')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-warning">LATE</span>
                                 @elseif($att->status == 'PM LATE')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-warning">LATE</span>
                                 @elseif($att->status == 'AM CC')
                                 <span class="badge badge-success">AM</span> <span class="badge badge-secondary">CC</span>
                                 @elseif($att->status == 'PM CC')
                                 <span class="badge badge-secondary">PM</span> <span class="badge badge-secondary">CC</span>
                                 @else
                                 {{$att->status}} 
                                 @endif
								 <br>
								 @if($att->ttime != null)
								  <span class="badge timebadge" style="font-size:.7rem !important" data-studid="{{$student->id}}"
										data-tdate="{{$att->tdate}}">{{$att->ttime}}</span>
								 @endif
                            </td>
                            @endforeach
                        @endif
                    </tr>
                @endif
              @endforeach
              <tr>
				<td class="text-center" style="vertical-align: middle;"><h4>FEMALE</h4></td>
                  @if(count($dates)>0)
                      @foreach($dates as $date)
                      <td></td>
                      @endforeach
                  @endif
              </tr>
                @foreach($students as $student)
                  @if(strtolower($student->gender) == 'female')
                      <tr data-id="{{$student->id}}">
                          <td>
                              <div class="row">
                                <div class="col-md-12" style="font-size: 14px;">
                                      <span class="badge badge-warning">{{$student->sid}}</span>
                                      <span class="badge badge-warning">{{$student->description}}</span><br/>
                                      @if($student->crashedout == 0)
                                      <strong>{{$student->lastname}}</strong>, {{ucwords(strtolower($student->firstname))}}
                                      @else
                                      <del>{{$student->lastname}}, {{$student->firstname}}
                                      </del>
                                      @endif
                                  </div>
                              </div>
                          </td>
                          @if(count($student->attendance)>0)
                              @foreach($student->attendance as $att)
                              <td                                 class=" text-center @if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif"
                              data-studid="{{$student->id}}"
                              data-tdate="{{$att->tdate}}"
                              @if($att->status == null)
                               data-status="PRESENT" 
                               data-newstatus="PRESENT"
                               style="background-color: #8fdba3" 
                               clicked="1"
                              @else 
                                   data-status="{{$att->status}}"
                                   data-newstatus="{{$att->status}}"
                                   clicked="0"
                               @endif
                               >
                               @if($att->status == null)
                               Present<br/> <small>(Default)</small>
                               @elseif($att->status == 'AM PRESENT')
                               <span class="badge badge-success">AM</span> <span class="badge badge-success">PRESENT</span>
                               @elseif($att->status == 'PM PRESENT')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-success">PRESENT</span>
                               @elseif($att->status == 'AM ABSENT')
                               <span class="badge badge-success">AM</span> <span class="badge badge-danger">ABSENT</span>
                               @elseif($att->status == 'PM ABSENT')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-danger">ABSENT</span>
                               @elseif($att->status == 'AM LATE')
                               <span class="badge badge-success">AM</span> <span class="badge badge-warning">LATE</span>
                               @elseif($att->status == 'PM LATE')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-warning">LATE</span>
                               @elseif($att->status == 'AM CC')
                               <span class="badge badge-success">AM</span> <span class="badge badge-secondary">CC</span>
                               @elseif($att->status == 'PM CC')
                               <span class="badge badge-secondary">PM</span> <span class="badge badge-secondary">CC</span>
                               @else
                               {{$att->status}} 
                               @endif
							   <br>
								 @if($att->ttime != null)
								 <span class="badge timebadge" style="font-size:.7rem !important" data-studid="{{$student->id}}"
										data-tdate="{{$att->tdate}}">{{$att->ttime}}</span>
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
    @endif
</div>

<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
    var oTable = $('#thetable').DataTable({
  "columnDefs": [
    { "width": "300px", "targets": 0 }
  ],
fixedColumns: true,
    scrollY:        500,
    scrollX:        true,
    scrollCollapse: true,
    paging:         false,
    fixedColumns:   true,
    "aaSorting": []
    })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
    $('#myInputTextField').keyup(function(){
        oTable.search($(this).val()).draw() ;
    })
    $('th').unbind('click.DT');
</script>