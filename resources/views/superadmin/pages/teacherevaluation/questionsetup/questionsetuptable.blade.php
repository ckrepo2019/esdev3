<table class="table">
      <thead>
            <tr>
                  <th width="75%">Question</th>
                  <th width="15%" class="text-center">Max Rate</th>
                  <th width="5%" class="text-center"></th>
                  <th width="5%" class="text-center"></th>
            </tr>
            
      </thead>
      <tbody>
            @foreach ($evaluationQuestion as $item)
                  <tr>
                        <td>{{$item->question}}</td>
                        <td class="text-center">{{$item->maxrating}}</td>
                        <td class="text-center">  
                              <button type="button" class="btn btn-sm btn-outline-primary text-center edit" data-value="{{$item->id}}"><i class="fa fa-edit"></i></button>
                        </td>
                        <td class="text-center">  
                              <button type="button" class="btn btn-sm btn-outline-danger text-center delete" data-value="{{$item->id}}"><i class="fa fa-trash-alt"></i></button>
                        </td>
                  </tr>
            @endforeach
          
      </tbody>
</table>