<table class="table">
      <thead>
            <tr>
                  <th width="10%">
                        
                  </th>
                  <th width="60%"> 
                        Curriculum Description
                  </th>
                  <th width="300%">
                        Status
                  </th>
                  
            </tr>
      </thead>
      <tbody>
            @foreach ($curriculum as $item)
                  <tr>
                        <td>
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item editcurriculum" href="#"  data-id="{{$item->id}}"><i class="fas fa-edit"></i> Edit Curriculum</a>
                                          @if($item->isactive == 0)
                                                <a class="dropdown-item removecurriculum" href="#" data-id="{{$item->id}}"><i class="fas fa-trash-alt pr-2"></i>Remove Curriculum</a>
                                          @endif
                                          @if($item->isactive == 0)
                                                <a class="dropdown-item setasactive" href="#" data-id="{{$item->id}}" data-value="1"> <i class="fas fa-sliders-h pr-2"></i></i>Set as Active</a>
                                          @else
                                                <a class="dropdown-item setasactive" href="#"  data-id="{{$item->id}}" data-value="0"><i class="fas fa-sliders-h pr-2"></i></i>Set as Inactive</a>
                                          @endif
                                    </div>
                              </div>
                        </td>
                        <td>{{$item->curriculumname}}</td>
                        <td>
                              @if($item->isactive == 0)
                                    <span class="badge badge-danger">Inactive</span>
                              @else
                                    <span class="badge badge-success">Active</span>
                              @endif
                        </td>
                  </tr>
            @endforeach
      </tbody>

</table>