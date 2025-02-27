<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th width="50%">STUDENT NAME</th>
                  <th width="20%">SID</th>
                  <th width="30%"></td>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  <tr>
                        <td>{{Str::limit($item->firstname.', '.$item->lastname,25,'...')}}</td>
                        <td>{{$item->sid}}</td>
                        <td class="align-middle p-1">
                              @if($item->studstatus == 0)
                                    <button class="btn btn-danger btn-block" disabled>NOT ENROLLED</button>
                              @else
                                    <button class="btn btn-success btn-block unenroll" 
                                                data-value="{{$item->id}}"
                                                data-fname="{{$item->firstname}}"
                                                data-lname="{{$item->lastname}}"
                                                data-sid="{{$item->sid}}"
                                          >UNENROLL STUDENT</button>
                              @endif
                        </td>
                      
                  </tr>
            @endforeach
            
      </tbody>
</table>