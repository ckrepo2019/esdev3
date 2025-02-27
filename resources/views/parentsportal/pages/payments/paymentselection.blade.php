@if(count($assessment) > 0)
      <table class="table table-hover table-head-fixed" id="tuitiontable" style="min-height:100px">
            <thead>
            <tr>
                  <th width="5%"></th>
                  <th width="60%">DESCRIPTION</th>
                  <th width="35%" class="text-right">AMOUNT</th>
                  
            </tr>
            </thead>
            <tbody id="tuitionbody" class="bg-info">
            @foreach ($assessment as $key=>$item)
                  @if($item->balance != '0.00')
                        <tr 
                              sortid="{{$key}}"
                              style="cursor:pointer" 
                              itemtype="tuition" class="items" 
                              pointers="2 @if($item->duedate != null){{$item->classid}} {{$item->id}} {{\Carbon\Carbon::create($item->duedate)->isoFormat('M')}}@else{{$item->classid}} {{$item->id}} 0 @endif"
                              qt = 1
                        >
                              <td>
                                    <div class="icheck-success d-inline selectPayable">
                                          <input type="checkbox" id="ass{{$key}}">
                                          <label for="ass{{$key}}">
                                          </label>
                                    </div>
                              </td>
                              <td>
                                    @if($item->duedate != null)
                                          {{strtoupper(\Carbon\Carbon::create($item->duedate)->isoFormat('MMMM'))}}
                                    @endif
                                    PAYABLES
                              </td>
                              <td class="text-right">{{number_format($item->balance, 2)}} </td>
                        </tr>
                  @endif
            @endforeach
            </tbody>
      </table>
      <table class="table">
            <tfoot class="bg-danger" >
                  <tr>
                        <td style="font-size:13px">TOTAL AMOUNT OF SELECTED ITEM</td>
                        <td class="text-right" id="totalass" style="font-size:21px">0.00</td>
                  </tr>
            </tfoot>
      </table>
@else
      <table class="table">
            <tr>
                  <td class="text-center">NO PAYMENT AVAILABLE</td>
            </tr>
      </table>
@endif