<table class="table">
      <thead>
            <tr>
                  <th width="5%"></th>
                  <th width="65%" class="align-middle">Decription</th>
                  <th width="10%" class="text-center">Required</th>
                  <th width="10%" class="text-center">AP</th>
                  <th width="10%" class="text-center align-middle">Status</th>
            </tr>
      </thead>
      <tbody>
            @foreach($requirementlist as $items)
                  <tr>
                        <td>
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item updaterequirement" href="#" data-id="{{$items->id}}">Edit Requirements</a>
                                    <a class="dropdown-item removerequirement" href="#" data-id="{{$items->id}}">Remove Requirements</a>
                                    <a class="dropdown-item setActiveState" href="#" data-id="{{$items->id}}">Set as active</a>
                                    <a class="dropdown-item removeActiveState" href="#" data-id="{{$items->id}}">Set as inactive</a>
                                    </div>
                              </div>
                        </td>
                        <td>{{$items->description}}</td>
                        <td class="text-center">
                              @if($items->isRequired == 1)
                                    YES
                              @else
                                    NO
                              @endif
                          
                        </td>
                        <td class="text-center">
                              @if($items->acadprogcode == null)
                                    ALL
                              @else
                                    {{$items->acadprogcode}}
                              @endif
                        </td>
                        
                        <td class="text-center">
                              @if($items->isActive == 1)
                                    Active
                              @elseif($items->isActive == 0)
                                    Inactive
                              @endif
                        </td>
                  </tr>
            @endforeach
      </tbody>
</table>