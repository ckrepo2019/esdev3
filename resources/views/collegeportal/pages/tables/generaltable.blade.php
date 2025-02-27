<table class="table">
      <thead>
            <tr>
                  @foreach($tableHeaders as $tableheader)
                        <td width="{{$tableheader->width}}">{{$tableheader->title}}</td>
                  @endforeach
            </tr>
      </thead>
      <tbody>
            @foreach($tableData as $item)
                  <tr>
                        @foreach($tableHeaders as $tableheader)
                              @php
                                    $value = $tableheader->field
                              @endphp
                              @if($value != null)
                                    <td  class="align-middle" width="{{$item->$value}}">{{$item->$value}}</td>
                              @endif
                        @endforeach

                        @foreach($tableActions as $tableAction)

                              <td  class="align-middle">
                                    @if($tableAction->type == 'link')
                                          <a href="{{$tableAction->href}}{{sprintf("%06d",$item->id)}}" class="btn-sm btn {{$tableAction->color}}">{{$tableAction->text}}</a>
                                    @endif
                              </td>
                        @endforeach
                  <tr>
            @endforeach
      </tbody>
</table>