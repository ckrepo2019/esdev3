<input type="hidden" value="{{$data[0]->count}}" id="searchCount">


{{-- @php
      $onlinepayments = $data[0]->data;
@endphp --}}

<table class="table table-hover" >
      <thead>
            <tr>
                  <th width="2%" class="p-0"></th>
                  <th width="15%" class="text-center">Image</th>
                  <th width="10%" class="text-center align-middle">Type</th>
                  <th width="10%" class="text-center align-middle">Bank Name</th>
                  <th width="20%">Account Name</th>
                  <th width="20%" class="text-center align-middle">Account #</th>
                  <th width="20%" class="text-center align-middle">Mobile #</th>
                  <th width="3%"></th>
            </tr>
      </thead>
      <tbody>
            @php
                  $paymentType = DB::table('paymenttype')->where('isonline','1')->get();
            @endphp     

            @foreach($data[0]->data as $item)
                  <tr>
                        @if($item->isActive == 1)
                              <td class="bg-success p-0"></td>
                        @else
                              <td class="bg-danger p-0"></td>
                        @endif

                        <td class="p-1 text-center align-middle"><img id="img{{$item->id}}" src="{{asset($item->picurl)}}?hello{{\Carbon\Carbon::now()->isoFormat('HH:mm:ss')}}" width="60"></td>
                        <td class="text-center align-middle">
                              {{collect($paymentType)->where('id',$item->paymenttype)->pluck('description')[0]}}
                        </td>
                        <td width="20%" class="text-center align-middle">{{$item->optionDescription}}</td>
                        <td>{{$item->accountName}}</td>
                        <td class="text-center align-middle">{{$item->accountNum}}</td>
                        <td class="text-center align-middle">{{$item->mobileNum}}</td>
                        <td class="p-0 align-middle text-center">
                              <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                          <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#" id="updatePaymentOptions" data-id="{{$item->id}}">Edit</a>
                                    <a class="dropdown-item" href="#" id="removePaymentOption" data-id="{{$item->id}}">Remove</a>
                                    <a class="dropdown-item" href="#" id="setActiveState" data-id="{{$item->id}}">Set as active</a>
                                    <a class="dropdown-item" href="#" id="removeActiveState" data-id="{{$item->id}}">Set as inactive</a>
                                    </div>
                              </div>
                        </td>
                  </tr>
            @endforeach
      </tbody>
</table>

<script>
      $(document).ready(function(){
            

            // function reloadIt()
            //       {

            //             $('img').each(function(){
            //                   $(this).attr('src',$(this)[0].dataset.src)
            //             })

            //             setTimeout(reloadIt(), 5000);
            //       }

            //       // reloadIt()
            // window.onload = reloadIt();

            $(document).on('click','#updatePaymentOptions',function(){
                  
                  $('#poid').val($(this).attr('data-id'))

                        @foreach($data[0]->data as $item)

                              if('{{$item->id}}' == $(this).attr('data-id')){

                                    $('#paymenttype').val('{{$item->paymenttype}}').change()
                                    $('#paymentDesc').val('{{$item->optionDescription}}')
                                    $('#accName').val('{{$item->accountName}}')
                                    $('#accNum').val('{{$item->accountNum}}')
                                    $('#mobileNum').val('{{$item->mobileNum}}')
                              }

                        @endforeach

                  $('#addpaymentoptions').modal();
                  
            })
      })
</script>