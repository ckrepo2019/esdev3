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

@section('content')
	<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <!-- <h1>Finance</h1> -->
          
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Vendor</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content pt-0">
  	<div class="main-card card">
  		<div class="card-header bg-info">
        <div class="row">
          <div class="text-lg col-md-4">
            <!-- Fees and Collection     -->
            <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            <b>Supplier</b></h4>
          </div>
          <div class="col-md-4"></div>
          <div class="col-md-4">
                  
          </div>  
        </div>
        <div class="row">
          <div class="col-md-12">
            <button class="btn btn-warning float-right" id="btnvendor_create" data-toggle="tooltip" title="Create Adjustment"><i class="far fa-plus-square"></i> Create Supplier</button>
          </div>
        </div>
  		</div>
      
  		<div class="card-body table-responsive p-0 " style="height:380px">
        <table class="table table-hover table-sm text-sm">
          <thead class="bg-warning">
            <tr>
              <th>NAME</th>
              <th>ADDRESS</th>
              <th>CONTACT NO.</th>
              <th>EMAIL</th>
            </tr>  
          </thead> 
          <tbody id="vendor_list" style="cursor: pointer">
            
          </tbody>             
        </table>
  		</div>
  	</div>
  </section>
@endsection

@section('modal')
    <div class="modal fade" id="modal-vendor" aria-modal="true" style="display: none;">
        <div class="modal-dialog modal-lg mt-5">
            <div class="modal-content">
                <div id="modal-adj-header" class="modal-header bg-primary">
                    <h4 id="vendor_action" class="modal-title"></h4> 
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <input id="vendor_name" class="form-control text-xl" style="height: 2em" placeholder="Name">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label>Company Address</label>
                                </div>
                                <div class="col-md-9">
                                    <textarea id="vendor_address" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label>Contact No.</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="vendor_contactno" class="form-control">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-3">
                                    <label>Email Address</label>
                                </div>
                                <div class="col-md-9">
                                    <input id="vendor_email" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-2 bg-light">
                        <div class="col-md-3">
                            <button id="btnvendor_cancel" class="btn btn-outline-secondary btn-block">Cancel</button>
                        </div>
                        <div class="col-md-3 mt-1">
                            
                        </div>
                        <div class="col-md-3 mt-1">
                            <button id="btnvendor_delete" class="btn btn-outline-danger btn-block" style="display: none">Delete</button>
                        </div>
                        <div class="col-md-3 mt-1">
                            <button id="btnvendor_save" class="btn btn-outline-primary btn-block">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>


  
@endsection

@section('js')
  <script>
    
    $(document).ready(function(){

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        loadvendor();

        function loadvendor()
        {
            $.ajax({
                url:"{{route('vendor_load')}}",
                method:'GET',
                data:{
                },
                dataType:'json',
                success:function(data)
                {
                    $('#vendor_list').html(data.list);
                }
            });          
        }

        function vendor_clear()
        {
            $('#vendor_name').val('');
            $('#vendor_address').val('');
            $('#vendor_contactno').val('');
            $('#vendor_email').val('');  
            $('#btnvendor_delete').hide();
        }

        $(document).on('click', '#btnvendor_create', function(){
            vendor_clear();
            $('#modal-vendor').modal('show');
            $('#vendor_action').text('Create Supplier');
            $('#vendor_action').attr('data-info', 'create');
            setTimeout(function(){
                $('#vendor_name').focus();
            }, 300);
        });

        $(document).on('click', '#btnvendor_save', function(){
            var vendor = $('#vendor_name').val();
            var address = $('#vendor_address').val();
            var contactno = $('#vendor_contactno').val();
            var email = $('#vendor_email').val();
            var action = $('#vendor_action').attr('data-info');
            var vendorid = $('#btnvendor_save').attr('data-id');

            $.ajax({
                url: '{{route('vendor_update')}}',
                type: 'GET',
                dataType: '',
                data: {
                    vendor:vendor,
                    address:address,
                    contactno:contactno,
                    email:email,
                    action:action,
                    vendorid:vendorid
                },
                success:function(data)
                {
                    if(data == 1)
                    {
                        $('#modal-vendor').modal('hide');
                        vendor_clear();
                        loadvendor();
                    }
                    else
                    {
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        })

                        Toast.fire({
                            type: 'warning',
                            title: 'Supplier existed.'
                        })
                    }
                }
            });
        });

        $(document).on('click', '#btnvendor_cancel', function(){
            $('#modal-vendor').modal('hide');
        });

        $(document).on('click', '#vendor_list tr', function(){
            var vendorid = $(this).attr('data-id');

            $.ajax({
                url: '{{route('vendor_edit')}}',
                type: 'GET',
                dataType: 'json',
                data: {
                    vendorid:vendorid
                },
                success:function(data)
                {
                    $('#vendor_action').text('Edit Supplier')
                    $('#vendor_name').val(data.vendor_name);
                    $('#vendor_address').val(data.vendor_address);
                    $('#vendor_contactno').val(data.vendor_contactno);
                    $('#vendor_email').val(data.vendor_email);
                    $('#btnvendor_save').attr('data-id', vendorid);
                    $('#vendor_action').attr('data-info', 'edit');
                    $('#btnvendor_delete').show();
                    $('#modal-vendor').modal('show');
                }
            });
        });

        $(document).on('click', '#btnvendor_delete', function(){
            var vendorid = $('#btnvendor_save').attr('data-id');

            Swal.fire({
                title: 'Delete Supplier?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                console.log(result)
                if (result.value == true) {
                    $.ajax({
                          url: '{{route('vendor_delete')}}',
                          type: 'GET',
                          dataType: '',
                          data: {
                            vendorid:vendorid
                        },
                        success:function(data)
                        {
                            Swal.fire(
                                'Deleted!',
                                '',
                                'success'
                            );

                            $('#modal-vendor').modal('hide');

                            loadvendor();
                        }
                    });
                }
            })

        })

        

      

    });

  </script>
@endsection