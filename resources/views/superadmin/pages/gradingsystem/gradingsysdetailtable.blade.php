<table class="table table-striped">
      <thead>
            <tr>
                  <th width="5%">Sort</th>
                  @if($gs->type == 1)
                        <th width="10%">Items</th>
                  @elseif($gs->type ==  2 || $gs->type == 3)
                        <th width="10%">Group</th>
                  @endif
                  <th width="50%">Description</th>
                  <th width="10%" class="text-center">Value</th>
                  <th width="15%" class="text-center">SF9 Value</th>
                  <th width="5%"></th>
                  <th width="5%"></th>
            </tr>
      </thead>
      <tbody>
            @foreach ($sysDetail as $item)
                  <tr>
                        <td  class="align-middle text-center">{{$item->sort}}</td>
                        @if($gs->type == 1)
                              <td  class="align-middle text-center">{{$item->items}}</td>
                        @elseif($gs->type ==  2 || $gs->type == 3)
                              <td  class="align-middle text-center">{{$item->group}}</td>
                        @endif
                        <td class="align-middle">{{$item->description}}</td>
                        <td class="align-middle text-center">{{$item->value}}</td>
                        <td class="align-middle text-center">
                              @if($item->sf9val == 1)
                                    WW
                              @elseif($item->sf9val == 2)
                                    PT
                              @elseif($item->sf9val == 3)
                                    QA
                              @endif
                        </td>
                        <td class="align-middle">
                              <button class="btn btn-primary btn-block update_gs_detail btn-sm" 
                                    data-description="{{$item->description}}" 
                                    data-value="{{$item->value}}" 
                                    data-id="{{$item->id}}"  
                                    data-items="{{$item->items}}"  
                                    data-group="{{$item->group}}"  
                                    data-sf9="{{$item->sf9val}}"  
                                    data-sort="{{$item->sort}}">
                                    <i class="far fa-edit"></i></button>
                            
                        </td>
                        <td class="align-middle">
                              <button class="btn btn-danger btn-block delete_gs_detail btn-sm" 
                                          data-description="{{$item->description}}" 
                                          data-value="{{$item->value}}" 
                                          data-items="{{$item->items}}"  
                                          data-group="{{$item->group}}"  
                                          data-sf9="{{$item->sf9val}}"  
                                          data-id="{{$item->id}}"  
                                          data-sort="{{$item->sort}}">
                              <i class="fas fa-trash-alt"></i></i></button>
                        </td>
                  </tr>
            @endforeach
      </tbody>
</table>

