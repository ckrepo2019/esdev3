<input type="hidden" value="{{$data[0]->count}}" id="searchCount">

<table class="table table-hover" >
  <thead>
    <tr>
      <th class="align-middle" width="30%">Room Name</th>
      <th class="align-middle" width="30%">Building</th>
      <th class="align-middle" width="70%">Capacity</th>
    </tr>
  </thead>
  <tbody >
    @foreach ($data[0]->data as $key=>$item)
      <tr>
          <td><a href="/roomsinfo/{{$item->id}}">{{$item->roomname}}</a></td>
          <td>{{$item->description}}</td>
          <td>{{$item->capacity}}</td>
          <!-- <td class="align-middle p-0 text-right"> 
            <button type="button" class="btn btn-sm btn-primary ee" id="{{$item->id}}" data-toggle="modal" data-target="#modal-primary"><i class="far fa-edit"></i> EDIT</button>
          </td>
          <td class="align-middle p-0 pl-1">
            <a href="/adminremoveroom/{{$item->id}}" type="button" class="btn btn-sm btn-danger text-center" ><i class="fa fa-trash"></i> DELETE</a>
          </td> -->
      </tr>   
    @endforeach
  </tbody>
</table>