<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table">
      <thead>
            <tr>
                  <th width="40%">STUDENT NAME</th>
                  <th width="40%">SID</th>
                  <th width="20%">USERNAME</th>
            </tr>
      </thead>
      <tbody>
            @foreach ($data[0]->data as $item)
                  <tr>
                        <td>{{Str::limit($item->lastname.', '.$item->firstname,25,'...')}}</td>
                        <td>{{$item->sid}}</td>
                        <td><button class="btn btn-success btn-sm" 
                                    data-fname="{{$item->firstname}}"
                                    data-lname="{{$item->lastname}}"
                                    data-sid="{{$item->sid}}"
                                    data-id="{{$item->id}}"
                                    id="fix_credentials"
                              >FIX CREDENTIAL</button></td>
                  </tr>
            @endforeach
            
      </tbody>
</table>