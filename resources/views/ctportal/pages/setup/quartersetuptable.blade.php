<table class="table">
      <thead>
            <tr>
                  <th width="5%"></th>
                  <th width="50%">Description</th>
                  <th width="25%">Type</th>
                  <th width="5%">Semi</th>
                  <th width="5%">Mid</th>
                  <th width="5%">PreFi</th>
                  <th width="5%">Final</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($quartersetup as $item)
                  <tr>
                        <td>
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                          <a class="dropdown-item editSetup" href="#"  data-id="{{$item->id}}"><i class="fas fa-edit"></i>Edit Setup</a>
                                          <a class="dropdown-item removeSetup" href="#" data-id="{{$item->id}}"><i class="fas fa-trash-alt pr-2"></i>Remove Setup</a>
                                    </div>
                              </div>
                        </td>
                        <td>{{$item->qsDesc}}</td>
                        <td>
                              {{-- @if($item->type == 1)
                                    Averaging
                              @elseif($item->type == 2)
                                    Percentage
                              @endif --}}
                              {{$item->termsetupdesc}}
                        </td>
                        <td>{{$item->semi}}</td>
                        <td>{{$item->mid}}</td>
                        <td>{{$item->pre}}</td>
                        <td>{{$item->final}}</td>
                   
                  </tr>
            @endforeach
      </tbody>
      <tfoot>
            <tr>
                  <td colspan="7">
                        <a href="#" id="showCreateSetupModal"><i class="nav-icon fa fa-plus"></i> Create Quarter Setup</a>
                  </td>
            </tr>
      </tfoot>




</table>