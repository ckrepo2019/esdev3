<div class="row">
      <div class="col-md-8 text-xl">
            {{DB::table('schoolinfo')->first()->schoolname}}
      </div>
      <div class="col-md-4">

      </div>
</div>
<div class="row">
      <div class="col-md-8 text-lg">
            {{DB::table('schoolinfo')->first()->address}}
      </div>
      <div class="col-md-4 text-right text-lg">
            Date: {{\Carbon\Carbon::create($receiptInfo[0]->transdate)->isoFormat('MM/DD/YYYY hh:mm A')}}
      </div>
</div>
<div class="row">
      <div class="col-md-8">
        
      </div>
      <div class="col-md-4 text-right text-lg text-danger">
            NO.   <b>{{$receiptInfo[0]->ornum}}</b>
      </div>
</div>
<div class="row">
      <div class="col-md-3 text-lg">
            ID No.:  <b>{{$receiptInfo[0]->sid}}</b>
      </div>
      <div class="col-md-5 text-lg">
            Name:  <b>{{strtoupper($receiptInfo[0]->studname)}}</b>
      </div>
      <div class="col-md-4 text-right text-lg">
            Grade and Section: {{$receiptInfo[0]->glevel}}
      </div>
</div>
<hr>
<div class="row">
      <div class="col-md-8">

            @php
                  $moneyformat = number_format(collect($receiptInfo)->sum('amount'),2);

                  $exploded = explode('.' ,$moneyformat);
                  $wholenum = str_replace(',','',$exploded[0]);
                  $centavo = 50;

                  // $wordString .=' and '. \Terbilang::make($exploded[1]).'centavos';

            @endphp
            <p class="text-lg">RECEIVED IN THE AMOUNT OF <b>
                  {{strtoupper(\Terbilang::make($wholenum))}} 
                  @if($centavo > 0)
                        AND {{strtoupper(\Terbilang::make($centavo))}} CENTAVOS
                  @endif
                  </b>
            </p>
            <p class="text-lg">AS PAYMENT FOR</p>

            <table class="table table-bordered text-lg">
                  <thead>
                        <tr>
                              <th class="text-center">
                                    PARTICULARTS
                              </th>
                              <th class="text-center">
                                    AMOUNT
                              </th>
                        </tr>
                  </thead>
                  <tbody>
                        @foreach ($receiptInfo as $item)
                              <tr>
                                    <td>{{$item->items}}</td>
                                    <td class="text-right">{{number_format($item->amount,2)}}</td>
                              </tr>
                        @endforeach
                  </tbody>
                  <tfoot>
                        <tr>
                              <td colspan="2" class="text-right text-bold"><span class="pr-4">TOTAL:</span> {{number_format(collect($receiptInfo)->sum('amount'),2)}}</td>   
                        </tr>
                  </tfoot>
            </table>
            <p class="text-lg">Issud By: <u><b>{{$receiptInfo[0]->name}}<b></u></p>

      </div>
</div>