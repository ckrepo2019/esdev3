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


<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Payment Transaction</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Payment Transaction</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-body p-0">
                        <table class="table table-sm table-striped" >
                            <thead>
                                <tr>
                                    <th colspan="2" style="font-size:.9rem">Transaction as of {{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMMM DD, YYYY')}}</th>
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
                url: '/student/enrollment/record/cashtrans/transactions',
                success:function(data) {

                    if(data.length == 0){
                        $('#datatable_1').append('<tr><td colspan="2" style="font-size:.7rem">No Payment Found</td></tr>')
                        return false
                    }
                    cashier = data
                    $.each(data,function(a,b){

                        var transtype = ""

                        if(b.oid != null){
                            transtype = '<i class="text-success">(Online)</i>'
                        }

                        $('#datatable_1').append('<tr><td width="70%"><a class="mb-0" style="font-size:.8rem">'+b.paytype+' '+transtype+' - OR#: '+b.ornum+'</a><p class="text-muted mb-0" style="font-size:.8rem">'+b.transdate+'</p></td><td width="70%" class="text-right"><a class="mb-0" style="font-size:.8rem">&#8369; '+b.amountpaid+'</a><p class="text-muted mb-0" style="font-size:.8rem">&nbsp;</p></td></tr>')
                    })
                }
            })
        }


    })
</script>

@endsection
