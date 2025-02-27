<input 
      type="hidden" 
      value="{{$data[0]->count}}" 
      id="searchCount"
>

<table class="table table-hover" >
  <thead>
    <tr>
      <th class="align-middle" width="30%">Building Description</th>
      <th class="align-middle" width="30%">Capacity</th>
    </tr>
  </thead>
  <tbody >
    @foreach ($data[0]->data as $key=>$item)
      <tr>
            <td><a href="/admin/view/building/info/{{$item->id}}">{{$item->description}}</a></td>
            <td>{{$item->capacity}}</td>
      </tr>   
    @endforeach
  </tbody>
</table>