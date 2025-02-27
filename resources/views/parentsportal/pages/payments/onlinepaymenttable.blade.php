<table class="table table-head-fixed" style="min-width:800px">
      <thead>
            {{-- <tr>
                  <th colspan="1" class="text-center" style="border-right: 1px solid #dee2e6;">Transaction Info</th>
                  <th colspan="3" class="text-center" style="border-right: 1px solid #dee2e6;">Upload Information</th>
                  <th>
                  </th>
            </tr> --}}

            <tr>
                  <th width="15%" style="border-right: 1px solid #dee2e6;" class="text-center">Status / OR#</th>
                  {{-- <th width="18%" style="border-right: 1px solid #dee2e6;">OR#</th> --}}
                  <th width="20%">Amount</th>
                  <th width="31%">Type / Ref. Num.</th>
                  {{-- <th width="26%" ></th> --}}
                  <th width="20%" style="border-right: 1px solid #dee2e6;">Date Uploaded</th>
                  <th width="13%" ></th>
            </tr>
      </thead>
      <tbody>
            @foreach ($onlinepayments as $item)
                  <tr>
                        <td class="align-middle" style="border-right: 1px solid #dee2e6;">

                              <p class="d-flex flex-column text-center mb-0">
                                    @if($item->isapproved == 0)
                                          <span class="badge badge-danger">On process</span> 
                                    @elseif($item->isapproved == 1)
                                          <span class="badge badge-success">Approved</span> 
                                    @elseif($item->isapproved == 3)
                                          <span class="badge badge-danger">Canceled</span> 
                                    @elseif($item->isapproved == 2)
                                          {{-- <span class="badge badge-danger">Not approved</span>{{$item->remarks}} --}}
                                    @elseif($item->isapproved == 5)
                                          <span class="badge badge-success">Paid</span> 
                                    @endif
                                    <span>{{$item->ornum}}</span>
                              </p>
                        </td>
                        {{-- <td style="border-right: 1px solid #dee2e6;">
                              {{$item->ornum}}
                        </td> --}}
                       
                        <td class="align-middle">
                           
                              &#8369; {{number_format($item->amount,2)}}
                        </td>
                        <td class="align-middle">
                              <p class="d-flex flex-column mb-0">
                                    <span class="text-primary">{{$item->description}}</span>
                                    <span> {{$item->refNum}}</span>
                              </p>
                        </td>
                        <td class="align-middle" style="border-right: 1px solid #dee2e6;">
                              {{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}
                        </td>
                        <td class="align-middle text-center">
                              <button class="btn btn-success btn-sm view-image" data-src="{{asset($item->picUrl)}}">View Image</button>
                        </td>
                  </tr>
            @endforeach
      </tbody>
     


</table>