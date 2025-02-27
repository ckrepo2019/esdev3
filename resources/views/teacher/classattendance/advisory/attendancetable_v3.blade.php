@php
    if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'hccsi')
    {
        $students = collect($students)->where('crashedout','0')->values();
    }   
@endphp

<div class="col-md-12">
    <div class="card">
        <div class="card-header">
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
                @if($locksf2 == 1)
                <div class="col-md-6">
                    <h6><i class="fa fa-lock"></i>&nbsp;Locked: You can't make changes</h6>
                </div>
                @endif
            </div>
        </div>
        <div class="card-body  table-responsive p-0" style="height: 700px;">
        @if($locksf2 == 1)
        <table class="table table-head-fixed text-nowrap table-bordered">
          <thead>
              <tr>
                  <th>Student Name</th>
                  @if(count($dates)>0)
                      @foreach($dates as $date)
                          <th class="text-center eachdate" data-date="{{$date->date}}">
                              {{$date->datestr}}<br/>{{$date->day}}
                          </th>
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
                        <tr>
                              <td>
                                  <div class="row mb-1">
                                      <div class="col-md-12">
                                          <span class="badge badge-info">{{$student->sid}}</span>
                                          <span class="badge badge-info">{{$student->description}}</span>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12">
                                          @if($student->crashedout == 0)
                                          {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                          @else
                                          <del>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</del>
                                          @endif
                                      </div>
                                  </div>
                              </td>
                            @if(count($student->attendance)>0)
                                @foreach($student->attendance as $att)
                                <td>
                                     @if($att->status == null)
                                     Present<br/> <small>(Default)</small>
                                     @else
                                     {{$att->status}} 
                                     @endif
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
                          <tr>
                              <td>
                                  <div class="row mb-1">
                                      <div class="col-md-12">
                                          <span class="badge badge-info">{{$student->sid}}</span>
                                          <span class="badge badge-info">{{$student->description}}</span>
                                      </div>
                                  </div>
                                  <div class="row">
                                      <div class="col-md-12">
                                          @if($student->crashedout == 0)
                                          {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                          @else
                                          <del>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</del>
                                          @endif
                                      </div>
                                  </div>
                              </td>
                              @if(count($student->attendance)>0)
                                  @foreach($student->attendance as $att)
                                  <td >
                                  @if($att->status == null)
                                  Present<br/> <small>(Default)</small>
                                  @else
                                  {{$att->status}} 
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
                                            @if($student->crashedout == 0)
                                            {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                            @else
                                            <del>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</del>
                                            @endif
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
                                  <td data-class="attstatus" class="@if(strtolower($att->status) == 'present') bg-success @elseif(strtolower($att->status) == 'absent') bg-danger @elseif(strtolower($att->status) == 'late') bg-warning @elseif(strtolower($att->status) == 'cc') bg-secondary @endif eachstuddate"
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
                                       @else
                                       {{$att->status}} 
                                       @endif
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
                                            <span class="badge badge-info">{{$student->sid}}</span>
                                            <span class="badge badge-info">{{$student->description}}</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($student->crashedout == 0)
                                            {{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}
                                            @else
                                            <del>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}} {{$student->suffix}}</del>
                                            @endif
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
                                    @else
                                    {{$att->status}} 
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
    </div>
</div>