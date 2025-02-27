@php
      if(auth()->user()->type == 7){
            $extend = 'studentPortal.layouts.app2';
      }else if(auth()->user()->type == 9){
            $extend = 'parentsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
    <style>
        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>

@endsection


@section('content')

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
          <div class="modal-content">
                <div class="card-header p-2 border-0">
                    <button type="button" class="close btn-sm" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                      </button>
                </div>
                <div class="modal-body pt-0">
                      <div class="row">
                          <div class="col-md-12" >
                            <i id="message_holder"></i>
                          </div>
                      </div>
                </div>
                {{-- <div class="modal-footer border-0">
                    <button class="btn btn-danger btn-sm" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                </div> --}}
          </div>
    </div>
</div>   

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Uploaded Payment</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Uploaded Payment</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body p-0" >
                        <table class="table table-sm" width="100%">
                            <thead>
                                <tr>
                                    <th colspan="3" style="font-size:.9rem">Transaction as of {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</th>
                                </tr>
                            </thead>
                            <tbody id="datatable_1">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>



<script>
    $(document).ready(function(){

        get_cashtrans()
        var cashier = []

        function get_cashtrans(){
            $.ajax({
                type:'GET',
                url: '/student/enrollment/record/online/payment',
                success:function(data) {
                    cashier = data
                    $.each(data,function(a,b){

                        var status = "";
                        var amount = parseFloat(b.amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, "$&,")
                        
                        if(b.isapproved == 0){
                            status = '<i >(On Process)</i>'
                        }else if(b.isapproved == 1){
                            status = '<i class="text-primary">(Approved)</i>'
                        }else if(b.isapproved == 3){
                            status = '<i  class="text-danger">(Canceled)</i>'
                        }else if(b.isapproved == 2){
                            status = '<i  class="text-danger view_message" data-id="'+b.id+'">(Not Approved) <i class="fas fa-question-circle"></i></i>'
                        }else if(b.isapproved == 5){
                            var ornum = ""
                            if(b.ornum != null){
                                ornum = b.ornum
                            }
                            // status = '<i class="text-success">(Processed: OR#: '+ornum+')</i>'
                            status = '<i class="text-success">(Processed)</i>'
                        }

                        $('#datatable_1').append('<tr><td width="30%"><a class="mb-0" style="font-size:.8rem">'+b.description+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.paymentDate+'</p></td><td width="30%"><a class="mb-0" style="font-size:.8rem">RN: '+b.refNum+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+b.TransDate+'</p></td><td width="40%" class="text-right"><a class="mb-0" style="font-size:.8rem"> &#8369; '+amount+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+status+'</p></td></tr>')
                    })
                }
            })
        }

        $(document).on('click','.view_message',function () {
            var temp_id = $(this).attr('data-id')
            var temp_message = cashier.filter(x=>x.id == temp_id)[0].remarks
            $('#modal_1').modal()
            $('#message_holder').text(temp_message)
        })

        


    })
</script>

@endsection
