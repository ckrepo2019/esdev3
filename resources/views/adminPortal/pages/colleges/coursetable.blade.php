<table class="table table-striped">
      <thead>
            <th width="70%">Course Description</th>
            <th width="20%">Abrv.</th>
            <th width="10%"></th>
      </thead>
      <tbody>
            @foreach ($courses as $course)
                  <tr>
                        <td class="align-middle">{{$course->courseDesc}}</td>
                        <td class="align-middle">{{$course->courseabrv}}</td>
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
                  </tr>
            @endforeach
      </tbody>
</table>