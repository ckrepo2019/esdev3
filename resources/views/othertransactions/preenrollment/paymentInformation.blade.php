 
<div class="row">
      <div class="col-md-4">Payment Date:</div>
      <div class="col-md-6">{{\Carbon\Carbon::create($onlinepayments[0]->TransDate)->isoFormat('MMMM DD, YYYY hh:ss a')}}</div>
</div>
<div class="row">
      <div class="col-md-4">Date Uploaded:</div>
      <div class="col-md-6">{{\Carbon\Carbon::create($onlinepayments[0]->paymentDate)->isoFormat('MMMM DD, YYYY hh:ss a')}}</div>
</div>
<br>
<div class="row">
      <table class="table">
            
            <thead>
                  {{-- <tr class="bg-success">
                        <th class="text-center" colspan="2">
                              <h5 class="mb-0">Selected Items</h5>
                        </th>
                  </tr> --}}
                  <tr class="bg-secondary">
                        <th width="80%">Description</th>
                        <th width="20%">Amount</th>
                  </tr>
            </thead>
            <tbody>
                  @php
                        $selectTotalAmount = 0 ;
                  @endphp

                  @foreach ($onlinepayments as $item)

                        <tr>
                              <td>{{$item->description}}</td>
                              <td class="text-right">{{number_format($item->descriptAmount,2)}}</td>
                        </tr>

                        @php
                              $selectTotalAmount += $item->descriptAmount;
                        @endphp
                  @endforeach
            </tbody>
            <tfoot>
                  <tr class="bg-warning">
                        <th width="80%">Total amount of selected items:</th>
                        <th width="20%" class="text-right">{{number_format($selectTotalAmount,2)}}</th>
                  </tr>
                  <tr class="bg-primary">
                        <th width="80%">Total Paid Amount:</th>
                        <th width="20%" class="text-right">{{number_format($onlinepayments[0]->amount,2)}}</th>
                  </tr>
            </tfoot>
      
      </table>

      {{-- <table class="table table-hover table-striped">
      
            <tbody>
                  <tr>
                        <th width="80%">Total Paid Amount:</th>
                        <th width="20%" class="text-right">{{$onlinepayments[0]->amount}}</th>
                  </tr>
            </tbody>
      
      </table> --}}
      <div class="form-group">
            <label for="">Uploaded Image</label>
            <img src="{{asset($onlinepayments[0]->picUrl)}}" alt="" class="w-100">
      </div>
</div>


<script>
      $(document).ready(function(){

            @if($onlinepayments[0]->isapproved == 5)
                  $('#paymentInfoRibbon').text('Paid')
            @elseif($onlinepayments[0]->isapproved == 0)
                  $('#paymentInfoRibbon').text('On Process')
            @elseif($onlinepayments[0]->isapproved == 3)
                  $('#paymentInfoRibbon').text('Canceled')
            @elseif($onlinepayments[0]->isapproved == 1)
                  $('#paymentInfoRibbon').text('Approved')
            @elseif($onlinepayments[0]->isapproved == 2)
                  $('#paymentInfoRibbon').text('Not Approved')
            @endif
            
      })

</script>
