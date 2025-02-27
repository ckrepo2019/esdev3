<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-striped">
      <thead>
            <tr>
                  <th>Name</th>
                  <th>Position</th>
            </tr>
      </thead>
      <tbody id="">
            @foreach ($data[0]->data as $item)
                  <tr>
                  <td>{{$item->lastname}}, {{$item->firstname}}</td>
                  <td>{{$item->utype}}</td>
                  </tr>
            @endforeach
      </tbody>
</table>

        
