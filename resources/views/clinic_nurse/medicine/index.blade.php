@extends('clinic.layouts.app')
@section('content')
    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Drug  Inventory</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Drug  Inventory</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-addmedicine btn-primary" id="btn-addmedicine"><i class="fa fa-plus"></i> Drug</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <input type="text" class="form-control filter" placeholder="Search Med"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    <span class="badge badge-success">B E S T</span>
                                    <span class="badge badge-warning">Expiring this week</span>
                                    <span class="badge badge-danger"> E X P I R E D</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="container-meds">
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <label>Summary</label>
                        </div>
                        <div class="card-body">
                            
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-addmedicine">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">&nbsp;</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row form-container">
                  <div class="col-md-12 mb-2">
                      <label>Brand name</label>
                      <input type="text" class="form-control" id="input-add-brandname"/>
                  </div>
                  <div class="col-md-12 mb-2">
                      <label>Generic name</label>
                      <input type="text" class="form-control" id="input-add-genericname"/>
                  </div>
                  <div class="col-md-4">
                      <label>Dosage</label>
                      <input type="text" class="form-control" id="input-add-dosage"/>
                  </div>
                  <div class="col-md-3">
                      <label>Quantity</label>
                      <input type="number" class="form-control" id="input-add-quantity"/>
                  </div>
                  <div class="col-md-5">
                      <label>Expiry Date</label>
                      <input type="date" class="form-control" id="input-add-expirydate"/>
                  </div>
                  <div class="col-md-12 mt-2">
                      <label>Description</label>
                      <textarea class="form-control" id="input-add-description"></textarea>
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-addmedicine">Close</button>
            <button type="button" class="btn btn-primary" id="btn-submit-addmedicine">Add</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-editmedicine">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">&nbsp;</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row form-container">
                  <div class="col-md-12 mb-2">
                      <label>Brand name</label>
                      <input type="text" class="form-control" id="input-edit-brandname"/>
                  </div>
                  <div class="col-md-12 mb-2">
                      <label>Generic name</label>
                      <input type="text" class="form-control" id="input-edit-genericname"/>
                  </div>
                  <div class="col-md-4">
                      <label>Dosage</label>
                      <input type="text" class="form-control" id="input-edit-dosage"/>
                  </div>
                  <div class="col-md-3">
                      <label>Quantity</label>
                      <input type="number" class="form-control" id="input-edit-quantity"/>
                  </div>
                  <div class="col-md-5">
                      <label>Expiry Date</label>
                      <input type="date" class="form-control" id="input-edit-expirydate"/>
                  </div>
                  <div class="col-md-12 mt-2">
                      <label>Description</label>
                      <textarea class="form-control" id="input-edit-description"></textarea>
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-close-editmedicine">Close</button>
            <button type="button" class="btn btn-primary" id="btn-submit-editmedicine" data-id="">Save Changes</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    @endsection
    @section('footerjavascript')
    <script>
        var $ = jQuery;
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        $(document).ready(function(){

            function showMeds(){
                    $.ajax({
                        url: '/clinic/inventory/showmedicines',
                        type: 'GET',
                        success:function(data){
                            $('#container-meds').empty()
                            $('#container-meds').append(data)
                        }
                    })
            }
            showMeds();

            $(".filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;
    
                $(".container").append($("<div class='card-group card-group-filter'></div>"));
    
    
                $(".eachmed").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
    
                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;
    
                    } else {
    
                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;
    
                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });
    
            });

            $('#btn-addmedicine').on('click', function(){
                $('#modal-addmedicine').modal('show')
            })
            $('#btn-submit-addmedicine').on('click', function(){
                var brandname    = $('#input-add-brandname').val()
                var genericname  = $('#input-add-genericname').val()
                var dosage       = $('#input-add-dosage').val()
                var quantity     = $('#input-add-quantity').val()
                var expirydate   = $('#input-add-expirydate').val()
                var description  = $('#input-add-description').val()
                
                var checkvalidation = 0;

                if(brandname.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#input-add-brandname').css('border','1px solid red')
                }else{
                    $('#input-add-brandname').removeAttr('style')
                }
                if(genericname.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#input-add-genericname').css('border','1px solid red')
                }else{
                    $('#input-add-genericname').removeAttr('style')
                }
                if(quantity.replace(/^\s+|\s+$/g, "").length == 0 || quantity == 0)
                {
                    checkvalidation=1;
                    $('#input-add-quantity').css('border','1px solid red')
                }else{
                    $('#input-add-quantity').removeAttr('style')
                }

                if(checkvalidation == 0)
                {
                    $.ajax({
                        url: '/clinic/inventory/add',
                        type: 'GET',
                        dataType: 'json',
                        data:{
                            brandname   : brandname,
                            genericname : genericname,
                            dosage      : dosage,
                            quantity    : quantity,
                            expirydate  : expirydate,
                            description : description
                        }, success:function(data){
                            if(data == 0)
                            {
                                Toast.fire({
                                    type: 'warning',
                                    title: 'Med already exist!'
                                })
                            }else{
                                Toast.fire({
                                    type: 'success',
                                    title: 'Added successfully!'
                                })
                                $('#btn-close-addmedicine').click()
                                $('.form-container input,textarea').val("")
                                showMeds();
                            }
                        }
                    })
                }
                
            })
            $(document).on('click', '.btn-deletemed', function(){
                var id = $(this).attr('data-id');
                Swal.fire({
                title: 'Are you sure you want to delete selected drug?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete'
                })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/clinic/inventory/delete',
                            type: 'GET',
                            // dataType: 'json',
                            data:{
                                id   : id
                            }, success:function(data){
                                if(data == 1)
                                {
                                    Toast.fire({
                                        type: 'success',
                                        title: 'Deleted successfully!'
                                    })
                                    showMeds();
                                }else{
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Something went wrong!',
                                        text: data
                                    })
                                }
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-editmed', function(){
                var id = $(this).attr('data-id');
                $('#modal-editmedicine').modal('show')
                $('#btn-submit-editmedicine').attr('data-id',id);
                $.ajax({
                    url: '/clinic/inventory/getmedinfo',
                    type: 'GET',
                    // dataType: 'json',
                    data:{
                        id   : id
                    }, success:function(data){
                
                        $('#input-edit-brandname').val(data.brandname)
                        $('#input-edit-genericname').val(data.genericname)
                        $('#input-edit-dosage').val(data.dosage)
                        $('#input-edit-quantity').val(data.quantity)
                        $('#input-edit-expirydate').val(data.expirydate)
                        $('#input-edit-description').val(data.description)
                    }
                })      
            })
            $('#btn-submit-editmedicine').on('click', function(){
                var id = $(this).attr('data-id');
                var brandname    = $('#input-edit-brandname').val()
                var genericname  = $('#input-edit-genericname').val()
                var dosage       = $('#input-edit-dosage').val()
                var quantity     = $('#input-edit-quantity').val()
                var expirydate   = $('#input-edit-expirydate').val()
                var description  = $('#input-edit-description').val()
                
                var checkvalidation = 0;

                if(brandname.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#input-edit-brandname').css('border','1px solid red')
                }else{
                    $('#input-edit-brandname').removeAttr('style')
                }
                if(genericname.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    checkvalidation=1;
                    $('#input-edit-genericname').css('border','1px solid red')
                }else{
                    $('#input-edit-genericname').removeAttr('style')
                }
                if(quantity.replace(/^\s+|\s+$/g, "").length == 0 || quantity == 0)
                {
                    checkvalidation=1;
                    $('#input-edit-quantity').css('border','1px solid red')
                }else{
                    $('#input-edit-quantity').removeAttr('style')
                }

                if(checkvalidation == 0)
                {
                    $.ajax({
                        url: '/clinic/inventory/edit',
                        type: 'GET',
                        dataType: 'json',
                        data:{
                            id          : id,
                            brandname   : brandname,
                            genericname : genericname,
                            dosage      : dosage,
                            quantity    : quantity,
                            expirydate  : expirydate,
                            description : description
                        }, success:function(data){
                            if(data == 1)
                            {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Updated successfully!'
                                })
                                $('#btn-close-editmedicine').click()
                                $('.form-container input,textarea').val("")
                                showMeds();
                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: 'Something went wrong!'
                                })
                            }
                        }
                    })
                }
                
            })
        })
    </script>
    @endsection
