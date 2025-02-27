<table class="table table-striped mb-0" >
      <thead>
            <th width="5%"></th>
            <th width="75%">College Description</th>
            <th width="20%">Abrv.</th>
           
      </thead>
      <tbody>
            @foreach ($colleges as $college)
                  <tr>
                        <td>
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item" href="#" id="editcollege" data-id="{{$college->collegeDesc}}" data-value="{{$college->collegeDesc}}" ><i class="fas fa-edit"></i> Edit College</a>
                                          <a class="dropdown-item" href="#" id="removecollege" data-id="{{$college->collegeDesc}}" ><i class="fas fa-trash-alt pr-2" ></i>Remove College</a>
                                          <a class="dropdown-item" href="#" id="viewcourses" data-id="{{$college->collegeDesc}}"><i class="fas fa-eye pr-2"></i>View Course</a>
                                    </div>
                              </div>
                              
                        </td>
                        <td class="align-middle">{{$college->collegeDesc}}</td>
                        <td class="align-middle">{{$college->collegeabrv}}</td>
                       
                  </tr>
            @endforeach
      </tbody>
</table>