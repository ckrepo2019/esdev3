<table class="table" id="setupTable">
      <thead>
            <tr>
                  <td width="50%">Description</td>
                  <td width="20%">Percentage</td>
                  <td width="20%">Columns</td>
                  <td width="10%"></td>
            </tr>
      </thead>
      <tbody>
            @foreach ($gradesetup as $item)
                  <tr data-id="{{$item->id}}">
                        <td>{{$item->setupDesc}}</td>
                        <td>{{$item->percentage}}</td>
                        <td>{{$item->items}}</td>
                        <td {{$item->id}}>
                              {{-- <button class="btn btn-primary btn-sm"> <i class="nav-icon fa fa-edit"></i></button> --}}
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item editSetup" href="#" data-id="{{$item->id}}"><i class="fas fa-edit"></i> Edit Setup</a>
                                          <a class="dropdown-item removeSetup" href="#" data-id="{{$item->id}}"><i class="fas fa-trash-alt pr-2"></i>Remove Setup</a>
                                    </div>
                              </div>
                        </td>
                  </tr>
            @endforeach
      </tbody>
      <tfoot>
            <tr>
                  <td colspan="4">
                        @if(count($gradesetup) == 0)
                              <a href="#" id="duplicate_from_current_subject"><i class="nav-icon fa fa-plus"></i> Duplicate subject setup</a>
                              <br>
                              <br>
                        @endif
                        
                        <a href="#" id="showCreateSetupModal"><i class="nav-icon fa fa-plus"></i> Create Grade Setup</a>
                  </td>
            </tr>
      </tfoot>

</table>