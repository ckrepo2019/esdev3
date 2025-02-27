<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th width="30%">USER NAME</th>
                  <th width="20%">USERNAME</th>
                  <th width="30%">USER TYPE</th>
                  <th width="20%"></td>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  <tr>
                        <td>{{Str::limit($item->name,25,'...')}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->utype}}</td>
                        @if($item->isDefault == 1)
                              <td class="p-0 align-middle"><button class="btn btn-success btn-sm btn w-50">DEFAULT</button></td>
                        @else
                              <td class="p-0 align-middle"><button class="btn btn-danger btn-sm w-50" id="resetPass" data-id="{{$item->id}}" data-name="{{$item->name}}">RESET</button></td>
                        @endif
                  </tr>
            @endforeach
            
      </tbody>
</table>