

@extends('layouts.app')

@section('headerscript')
   
@endsection

@section('content')
<div class="modal fade" id="updatemodal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-body p-0">
                 <img id="modalImage" src="" alt="" class="w-100">
              </div>
          </div>
      </div>
  </div>



<section class="content ">
      <div class="row justify-content-center">
            <div class="col-md-6">
                  <div class="card">
                        <div class="card-header card-title" style="background-color: #88b14b; color: #fff">
                             PRE-REGISTRATION INFORMATION
                        </div>
                     
                        <div class="card-body">
                              <div class="form-group">
                                    <label for="">FIRST NAME</label>
                                    <input class="form-control" readonly value="{{$codeinformation[0]->first_name}}">
                              </div>
                              <div class="form-group">
                                    <label for="">LAST NAME</label>
                                    <input class="form-control" readonly value="{{$codeinformation[0]->last_name}}">
                              </div>
                        </div>
                       
                  </div>
            </div>
            <div class="col-md-6">
                  <div class="card">
                        <div class="card-header card-title" style="background-color: #88b14b; color: #fff">
                             ONLINE PAYMENT INFORMATION
                        </div>
                        <div class="card-body">
                             <table class="table">
                                   <thead>      
                                          <tr>
                                                <td>PAYMENT RECIEPT</td>
                                                <td>AMOUNT</td>
                                                <td>STATUS</td>
                                                <td>DATE UPLOADED</td>
                                          </tr>
                                   </thead>
                                   <tbody>
                                          @foreach ($onlinepayment as $item)
                                                <tr>
                                                      <td><img src="{{asset($item->picUrl)}}" style="max-height:80px; cursor: pointer;" class="imagereceipt"></td>
                                                      <td class="align-middle">{{$item->amount}}</td>
                                                      <td class="align-middle">
                                                            @if($item->isapproved == 0)
                                                                  <span class="badge badge-danger">On process</span> 
                                                            @elseif($item->isapproved == 1)
                                                                  <span class="badge badge-success">Approved</span> 
                                                            @elseif($item->isapproved == 5)
                                                                  <span class="badge badge-success">Paid</span> 
                                                            @endif
                                                      </td>
                                                      <td class="align-middle">{{\Carbon\Carbon::create($item->paymentDate)->isoFormat('MMM DD, YYYY hh:mm A')}}</td>
                                                </tr>
                                          @endforeach
                                   </tbody>
                             </table>
                        </div>
                       
                  </div>
            </div>
      </div>
  </section>
  
  <script>
      $(document).ready(function(){
            $(document).on('click','.imagereceipt',function(){
                  console.log("sdfsdf");
                  $("#updatemodal").modal();
                  $('#modalImage').attr('src',$(this).attr('src'))
            })        

      })
  </script>
@endsection


                        
            

