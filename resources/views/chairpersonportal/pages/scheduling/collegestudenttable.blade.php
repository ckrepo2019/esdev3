<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-striped">
      <thead>
            <tr>
                  <th width="3%" class="pl-2 pr-2"></th>
                  <th width="27%">NAME</th>
                  <th width="15%">ID</th>
                  <th width="15%">COURSE</th>
                  <th width="20%">GRADE LEVEL</th>
                  <th width="15%">SECTION</th>
                  <th width="5%">UNITS</th>
            
            </tr>
      </thead>
      <tbody>
            @if(count($data[0]->data) > 0)
                  @foreach($data[0]->data as $student)
                        <tr class="studtr bg-success-50 pt-1 pb-1" data-id="{{$student->id}}">
                              @if(auth()->user()->type == 16)
                                    <td class="pl-2 pr-2 align-middle">
                                          <div class="dropdown subjectoptions" >
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                      <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item view_student_TOR" href="#" 
                                                data-id="{{$student->id}}" 
                                                data-course ="{{$student->courseid}}" 
                                                data-course-string="{{$student->courseDesc}}"
                                                >View TOR</a>
                                                </div>
                                          </div>
                                    </td>
                              @elseif(auth()->user()->type == 14)
                                    @if(($student->studstatus == 0 && $student->sectionname == null) || $student->canchangecourse)
                                          <td class="pl-2 pr-2 align-middle">
                                                <div class="dropdown subjectoptions" >
                                                      <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                      </button>
                                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                      <a  class="dropdown-item change_student_course" href="#" data-toggle="modal"  data-target="#courseModal" class="studentinfo" data-id="{{$student->id}}">Change Course</a>
                                                      </div>
                                                </div>
                                          </td>
                                    @else
                                          <td></td>
                                    @endif
                              @endif
                         
                              <td data-id="{{Str::slug($student->firstname.' '.$student->lastname,'-')}}">
                                    @if(auth()->user()->type == 16)
                                          @if($student->courseabrv != null)
                                                <a href="/chairperson/scheduling/show/{{sprintf("%06d", $student->id)}}/{{Str::slug($student->firstname.' '.$student->lastname,'-')}}"><b>{{$student->lastname}}, {{$student->firstname}}</b></a>
                                          @else
                                                <a href="#" data-toggle="modal"  data-target="#courseModal" class="studentinfo" data-id="{{$student->id}}"><b>{{$student->lastname}}, {{$student->firstname}}</b></a>
                                          @endif
                                    @elseif(auth()->user()->type == 14)
                                          <b>{{$student->lastname}}, {{$student->firstname}}</b>
                                    @endif
                                    <br>
                                    @if($student->studstatus == 1 && $student->preEnrolled == 0)
                                          <span class="badge badge-success">Enrolled</span>
                                    @elseif($student->studstatus == 0 && $student->preEnrolled == 1)
                                          <span class="badge badge-danger">Pre-enrolled</span>
                                    @endif
                                   
                              </td>
                              <td>{{$student->sid}}</a></td>
                              <td>{{$student->courseabrv}}</a></td>
                              <td>{{$student->levelname}}</a></td>
                              <td>{{$student->sectionname}}</td>
                              <td class="text-center">{{$student->units}}</td>
                              
                           
                        </tr>
                  @endforeach
            @else
                  <tr>
                        <td colspan="6" class="text-center">NO REGISTERED STUDENTS</td>
                  </tr>
            @endif
      </tbody>
</table>