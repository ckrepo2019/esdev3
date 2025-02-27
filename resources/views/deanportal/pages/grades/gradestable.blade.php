@if($data[0]->count > 0)

      <input type="hidden" value="{{$data[0]->count}}" id="searchCount">


      <table class="table table-striped">
            <thead>
                  <tr>
                        <th  width="65%"></th>
                        <th  width="20%"></th>
                        <th  width="15%"></th>
                  
                        
                  </tr>
            </thead>
            <tbody>
                  @foreach ($data[0]->data as $item)
                        <tr>
                              <td class="align-middle">
                                    @if($item->status == 0)
                                          <span class="badge badge-success">New</span>
                                    @endif
                                    {{$item->message}}</td>
                              <td>
                                    {{\Carbon\Carbon::create($item->createddatetime)->isoFormat('MMM DD, YYYY hh:mm a')}}
                              </td>
                              <td><button class="btn btn-primary btn-sm view_grade" data-id="{{$item->id}}" >View Grades</button></td>
                        </tr>
                  @endforeach
            </tbody>

      </table>

@else

      <table class="table table-striped">
            <thead>
                  <tr>
                        <th class="text-center">No Results found</th>
                  </tr>
            </thead>
           
      </table>
@endif