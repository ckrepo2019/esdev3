<table class="table">
      <thead>
            <tr>
                  <th>Description</th>
                  <th  class="text-right">Amount</th>
                  <th  class="text-right">Balance</th>
            </tr>
      </thead>
      <tbody id="payableInfoTable">
            @php
                  $totalbal = 0;
                  $totalamount = 0;
            @endphp
            @foreach ($billdet as $item)
                  @php
                        $totalbal += (float) $item->balance;
                        $totalamount +=  (float) $item->amount;
                  @endphp

                  <tr>
                        <td>{{$item->particulars}}</td>
                        <td class="text-right">{{number_format($item->amount, 2)}}</td>
                        <td class="text-right">{{number_format($item->balance, 2)}}</td>
                  </tr>
            @endforeach
      </tbody>
      <tfoot id="payableInfoFooter">
            <tr>
                  <th>Total</th>
                  <th class="text-right">{{number_format($totalamount, 2)}}</th>
                  <th class="text-right">{{number_format($totalbal, 2)}}</th>
            </tr>
      </tfoot>
</table>


