<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-striped" style="min-width: 900px">
      <thead>
            <tr>
                  <th width="30%">Description</th>
                  <th width="15%">Specification</th>
                  <th width="15%">Type</th>
                  <th width="20%">Program</th>
                  <th width="5%"></th>
                  <th width="5%"></th>
                  <th width="5%"></th>
                  <th width="5%"></th>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  @php
                       if($item->isactive == 1){
                        
                              $active = 2;
                        }
                        elseif($item->isactive == 0){
                              $active = 1;
                        }  

                  @endphp

                  <tr   
                        class="gs_tr"
                        data-id="{{$item->id}}" 
                        data-description="{{$item->description}}" 
                        data-type="{{$item->gstype}}" 
                        data-stringtype="{{$item->type}}"
                        data-acadprog="{{$item->acadprogid}}"
                        data-isactive="{{$active}}"
                        data-specification="{{$item->spid}}"
                        data-trackid="{{$item->trackid}}"
                  >
                        <td class="align-middle">{{$item->description}}</td>
                        <td class="align-middle">{{$item->type}}</td>
                        <td>{{$item->specification}}</td>
                        <td>

                              @if($item->progname == null)
                                    No Specified
                              @else
                                    {{$item->progname}}
                              @endif
                        </td>
                        <td class="text-center">
                              @if($item->isactive == 1)
                                    <i class="fas fa-check text-success"></i>
                              @elseif($item->isactive == 0)
                                    <i class="fas fa-times text-danger"></i>
                              @endif

                        </td>
                        <td class="align-middle"><button 
                              
                              class="btn btn-sm btn-secondary grading_system_detail_view_button btn-block" 
                              data-id="{{$item->id}}" 
                              data-description="{{$item->description}}" 
                              data-type="{{$item->gstype}}" 
                              data-stringtype="{{$item->type}}"
                              data-acadprog="{{$item->acadprogid}}"
                              data-isactive="{{$item->isactive}}"
                              data-specification="{{$item->spid}}"
                              data-trackid="{{$item->trackid}}"
                              >
                              <i class="far fa-eye"></i></button></td>
                        
                        <td class="align-middle"><button class="btn btn-sm btn-primary update_gs btn-block" 
                              data-id="{{$item->id}}" 
                              data-description="{{$item->description}}" 
                              data-type="{{$item->gstype}}" 
                              data-stringtype="{{$item->type}}"
                              data-acadprog="{{$item->acadprogid}}"
                              data-isactive="{{$item->isactive}}"
                              data-specification="{{$item->spid}}"
                              data-trackid="{{$item->trackid}}"
                              >
                              <i class="far fa-edit"></i>
                        </button></td>
                        
                        <td class="align-middle"><button class="btn btn-sm btn-danger delete_gs btn-block" 
                              data-id="{{$item->id}}" 
                              data-description="{{$item->description}}" 
                              data-type="{{$item->gstype}}" 
                              data-stringtype="{{$item->type}}"
                              data-acadprog="{{$item->acadprogid}}"
                              data-isactive="{{$item->isactive}}"
                              >
                              <i class="fas fa-trash-alt"></i></button></td>
                        
                  </tr>
            @endforeach
      </tbody>
</table>

