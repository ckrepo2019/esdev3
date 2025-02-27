@extends('clinic.layouts.app')
@section('content')

<style>

    .shadow {
          box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
          border: 0;
    }

</style>
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
                    <div class="card shadow">
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
                                <div class="col-md-6 pt-2">
                                    <span class="text-danger"><i> Click the cards to edit or delete medicine.</i></span>
                                   
                                </div>
                                <div class="col-md-6 text-right pt-2">
                                    <span class="badge badge-success">Best</span>
                                    <span class="badge badge-warning">Expiring this week</span>
                                    <span class="badge badge-danger"> Expired</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="container-meds">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="modal-addmedicine">
      <div class="modal-dialog">
        <div class="modal-content">
          
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important">Medicine Information</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
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
              <hr>
              <div class="row">
                <div class="col-md-6">
                    <button type="button" class="btn btn-primary medinfo-form-button" >Add</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-danger float-right" id="btn-deletemed">Delete</button>
                </div>
            </div>
          </div>
        </div>
      </div>
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

                $('.medinfo-form-button').removeClass('btn-success')
                $('.medinfo-form-button').addClass('btn-primary')
                $('.medinfo-form-button').text('Add')
                $('.medinfo-form-button').attr('id','btn-submit-addmedicine')
                $('#btn-deletemed').attr('hidden','hidden')
                $('#btn-deletemed').removeAttr('data-id')
                $('#btn-deletemed').removeAttr('data-id')

                $('#input-add-brandname').val('')
                $('#input-add-genericname').val('')
                $('#input-add-dosage').val('')
                $('#input-add-quantity').val('')
                $('#input-add-expirydate').val('')
                $('#input-add-description').val('')
                
                $('#modal-addmedicine').modal('show')
            })

            $(document).on('click', '#btn-submit-addmedicine', function(){

                console.log('sdfsdf')
                
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
            $(document).on('click', '#btn-deletemed', function(){
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
                                    $('#modal-addmedicine').modal('hide')
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
                $('#modal-addmedicine').modal('show')

                $('.medinfo-form-button').removeClass('btn-primary')
                $('.medinfo-form-button').addClass('btn-success')
                $('.medinfo-form-button').addClass('btn-success')
                $('.medinfo-form-button').text('Update')
                $('#btn-deletemed').removeAttr('hidden')
                $('#btn-deletemed').attr('data-id',id)

                $('.medinfo-form-button').attr('id','btn-submit-editmedicine')

                $('#btn-submit-editmedicine').attr('data-id',id);
                $.ajax({
                    url: '/clinic/inventory/getmedinfo',
                    type: 'GET',
                    // dataType: 'json',
                    data:{
                        id   : id
                    }, success:function(data){
                
                        $('#input-add-brandname').val(data.brandname)
                        $('#input-add-genericname').val(data.genericname)
                        $('#input-add-dosage').val(data.dosage)
                        $('#input-add-quantity').val(data.quantity)
                        $('#input-add-expirydate').val(data.expirydate)
                        $('#input-add-description').val(data.description)
                    }
                })      
            })

            $(document).on('click','#btn-submit-editmedicine', function(){

                console.log('sdfsdf')
                var id = $(this).attr('data-id');
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
                                $('#modal-addmedicine').modal('hide')
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
