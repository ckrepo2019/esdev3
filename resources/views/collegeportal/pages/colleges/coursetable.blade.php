<table class="table table-striped mb-0 table-head-fixed" >
      <thead>
            <th width="70%" class="align-middle">Course Description</th>
            <th width="20%" class="align-middle">Abrv.</th>

            @if($status == 0)
                  <th width="30%" class="align-middle"></th>
            @endif

            @if($withCurriculum)

                  <th width="30%" class="align-middle text-center" style="font-size:11px"># of Curriculum </th>

            @endif

            @if($withEnrolledStud)

                  <th width="30%" class="align-middle text-center" style="font-size:11px">Enrolled Student</th>

            @endif

      </thead>
      <tbody>
            @foreach ($courses as $course)
                  <tr>
                        <td class="align-middle">
                              @if($status == 1)
                                    <a href="#" class="courselink" data-id="{{$course->courseDesc}}" data-value="{{$course->id}}">{{$course->courseDesc}}<a>
                              @else
                                    {{$course->courseDesc}}
                              @endif
                        </td>
                        <td class="align-middle">{{$course->courseabrv}}</td>

                        @if($status == 0)
                              <td>
                                    
                                    <div class="dropdown">
                                          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                          </button>
                                          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item" href="#" id="editcourse" data-id="{{$course->id}}" data-value="{{$course->courseDesc}}" ><i class="fas fa-edit"></i> Edit Course</a>
                                                <a class="dropdown-item" href="#" id="removecourse" data-id="{{$course->courseDesc}}" ><i class="fas fa-trash-alt pr-2"></i>Remove Course</a>
                                          </div>
                                    </div>
                                    
                              </td>
                        @endif

                        @if($withCurriculum)
                              <td class="curr_holder text-center align-middle" data-value="{{$course->id}}"></td>
                        @endif

                        @if($withEnrolledStud)

                              <td class="text-center align-middle" data-enrolledstud="{{$course->id}}"></td>

                        @endif

                  </tr>
            @endforeach
      </tbody>
</table>