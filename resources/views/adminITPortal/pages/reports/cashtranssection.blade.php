<div class="col-md-12">
      <div class="card">
            <div class="card-header bg-primary">
                  <h3 class="card-title">BY GRADELEVEL</h3>
            </div>
            <div class="card-body p-0 table-responsive" style="height: 588px">
                  <table class="table" >
                        <thead>
                              <tr>
                                    <th width="20%">OR #</th>
                                    <th width="25%" class="text-right">Total Amount</th>
                                    <th width="25%" class="text-right">Amount Paid</th>
                                    <th width="30%" class="text-right">Transaction Date</th>
                              </tr>
                        </thead>
                        <tbody>
                              @foreach ($cashtrans as $item)
                                    <tr class="@if($item['totalamount'] != $item['amountpaid']) bg-danger @endif">
                                          <td># {{$item['ornum']}}</td>
                                          <td class="text-right">&#8369; {{number_format($item['totalamount'],2)}}</td>
                                          <td class="text-right">&#8369; {{number_format($item['amountpaid'],2)}}</td>
                                          <td class="text-right">{{\Carbon\Carbon::create($item['transdate'])->isoFormat('MMMM DD, YYYY')}}</td>
                                    </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
            <div class="card-footer pt-0 pb-0">
                  <table class="table pb-0">
                        <tbody>
                              <tr>
                                    <th width="20%" style="border-top:0">TOTAL</th>
                                    <th width="25%" class="text-right" style="border-top:0">&#8369; {{number_format($amountpaid[0]['totalAmount'],2)}}</th>
                                    <th width="25%" class="text-right" style="border-top:0"> &#8369; {{number_format($amountpaid[0]['totalAmountPaid'],2)}}</th>
                                    <th width="40%" class="text-right" style="border-top:0"></th>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>
</div>

<input hidden id="pagecount" name="pagecount" value="{{$count[0]['count']}}">