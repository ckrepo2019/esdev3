<input type="hidden" value="{{$data[0]->count}}" id="searchCount">



  <table class="table table-hover" style="min-width:500px; table-layout:fixed;">
    <thead>
      <tr>
        <th width="2%" class="p-0"></th>
        <th width="16%" class="appadd">DATE</th>
        <th width="8%" class="appadd">DAY</th>
        <th width="20%" class="appadd">DESCRIPTION</th>
        <th width="15%" class="appadd">S.Y.</th>
        <th width="23%">TYPE</th>
        <th width="8%"></th>
        <th width="8%"></th>
      </tr>
    </thead>
    <tbody >
      @if($data[0]->count>0)
        @foreach ($data[0]->data as $key=>$item)
          <tr>
            @if($item->noclass == 1)
              <td class="text-center p-0 align-middle bg-danger"> </td>
            @else
              <td class="text-center p-0 align-middle bg-success"> </td>
            @endif
                
              <td class="align-middle">{{strtoupper(\Carbon\Carbon::create($item->datefrom)->isoFormat('MMM DD'))}}
                @if(strtoupper(\Carbon\Carbon::create($item->datefrom)->isoFormat('MMM DD'))!=strtoupper(\Carbon\Carbon::create($item->dateto)->isoFormat('MMM DD')))
                @if(\Carbon\Carbon::create($item->datefrom)->isoFormat('MMM')!= \Carbon\Carbon::create($item->dateto)->isoFormat('MMM'))
                  - <br>{{strtoupper(\Carbon\Carbon::create($item->dateto)->isoFormat('MMM DD'))}}
                  @else
                  - {{strtoupper(\Carbon\Carbon::create($item->dateto)->isoFormat('DD'))}}
                  @endif
                
                @endif
              </td>
              <td class="align-middle">
                {{strtoupper(\Carbon\Carbon::create($item->dateto)->isoFormat('ddd'))}}
              </td>
              <td class="align-middle appadd">
                @if($item->annual == 1)
                  <span class="text-primary ">{{$item->description}}</span>
                @else
                  <span class=" ">{{$item->description}}</span>
                @endif
              
              </td>
              <td class="align-middle appadd">{{$item->sydesc}}</td>
              <td class="align-middle appadd">{{$item->typedesc}}</td>
              <td class="align-middle p-0 text-right"> 
                <button type="button" class="btn btn-sm btn-outline-primary ed" id="{{$item->id}}" data-toggle="modal" data-target="#modal-primary" module_processs="update" ><i class="far fa-edit"></i></button>
              </td>
                <td class="align-middle p-0 pl-1">
                <a href="/adminremoveholiday/{{$item->id}}" type="button" class="btn btn-sm btn-outline-danger text-center" module_processs="delete" ><i class="fa fa-trash"></i></a>
              </td>
          </tr>   
        @endforeach
      @else
        <tr>
          <td colspan="7" class="text-center">No results found</td>
        </tr>
      @endif
    </tbody>
  </table>
