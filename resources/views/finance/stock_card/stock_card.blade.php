@php

    
    $check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid','resourcepath')->first();

    if(Session::get('currentPortal') == 14){    
		$extend = 'deanportal.layouts.app2';
	}else if(Session::get('currentPortal') == 3){
        $extend = 'registrar.layouts.app';
    }else if(Session::get('currentPortal') == 8){
        $extend = 'admission.layouts.app2';
    }else if(Session::get('currentPortal') == 1){
        $extend = 'teacher.layouts.app';
    }else if(Session::get('currentPortal') == 2){
        $extend = 'principalsportal.layouts.app2';
    }else if(Session::get('currentPortal') == 4){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 15){
        $extend = 'finance.layouts.app';
    }else if(Session::get('currentPortal') == 18){
        $extend = 'ctportal.layouts.app2';
    }else if(Session::get('currentPortal') == 10){
        $extend = 'hr.layouts.app';
    }else if(Session::get('currentPortal') == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else if(auth()->user()->type == 16){
        $extend = 'chairpersonportal.layouts.app2';
    }else{
        if(isset($check_refid->refid)){
			
			if($check_refid->resourcepath == null){
                $extend = 'general.defaultportal.layouts.app';
			}else if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
            }else if($check_refid->refid == 22){
                $extend = 'principalcoor.layouts.app2';
            }else if($check_refid->refid == 29){
                $extend = 'idmanagement.layouts.app2';
            }else if($check_refid->refid ==  23){
				$extend = 'clinic.index';
			}elseif($check_refid->refid ==  24){
				$extend = 'clinic_nurse.index';
			}elseif($check_refid->refid ==  25){
				$extend = 'clinic_doctor.index';
			}elseif($check_refid->refid ==  33){
                $extend = 'inventory.layouts.app2';
            }elseif($check_refid->refid ==  19){
                $extend = 'finance.layouts.app';
                
            }else{
                $extend = 'general.defaultportal.layouts.app';
            }
        }else{
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp


@extends($extend)

@section('pagespecificscripts')


<style>
    /* .widget-user .widget-user-image > img {
        border: hidden;
    }
    .donutTeachers{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/corporate-grooming-20140726161024.jpg')}}") no-repeat  50% 80%;
        background-size: 30%;
    }
    .donutStudents{
        margin-top: 90px;
        margin: 0 auto;
        background: transparent url("{{asset('assets/images/student-cartoon-png-2.png')}}") no-repeat  50% 80%;
        background-size: 30%;
    } */
</style>

@endsection

@section('content')
<!-- DataTables -->

<br>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Stock Card</h1>
                <!-- <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                    <i class="fa fa-file-invoice nav-icon"></i> 
                    <b>STUDENT LEDGER</b></h4> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Online Payments</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row m-2">
    {{-- <div class="col-md-3">
        <div class="card">
            <div class="card-header"><i class="fa fa-filter"></i> Filter</div>
            <div class="card-body">
            </div>
        </div>
    </div> --}}
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row justify-content-end">
                    <div class="col-md-3">
                        <form action="/finance/stock_card/print" method="get" target="_blank">
                                <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-print"></i> Print</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="mt-2 table">
                    <table class="table table-striped font-size-sm" id="stockCardDatable">
                        <thead >
                            <tr>
                                <th width="5%"> </th>
                                <th width="20%" class="text-center">Item</th>
                                <th width="5%"> Stock In</th>
                                <th width="5%">Stock Out</th>
                                <th width="5%">Initial Onhand</th>
                                <th width="5%">Onhand</th>
                                <th width="20%">Department</th>
                                <th width="20%" class="text-center">Remarks</th>
                                <th width="20%" class="text-center">Transacted by</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footerjavascript')

<script>

        function getStockcard() {
            return $.ajax({
                type: 'GET',
                url: '/finance/inventory/getStockCard'
            })
        }

        function renderStockcard() {


            getStockcard().done(function (data) {

        
                var table = $('#stockCardDatable').DataTable({
                    pageLength: 5,
                    lengthMenu: [5, 10, 15, 20],
                    autoWidth: true,
                    responsive: true,
                    destroy: true,
                    stateSave: true,
                    data: data,
                    columns: [
                        { data: null, render: function(data, type, row, meta) { return meta.row + 1; } }, // Index column
                        { data: 'description', className: 'text-center'},
                        { data: 'stock_in' , className: 'text-center' },
                        { data: 'stock_out' , className: 'text-center' },
                        { data: 'initial_onhand' , className: 'text-center' },
                        { data: 'onhand' , className: 'text-center' },
                        { data: 'deparment_name' },
                        { data: 'remarks'},
                        { data: 'name', className: 'text-center' },
                    ],
                });

            });

        }

        $(document).ready(function(){


            renderStockcard();


        });

</script>
@endsection