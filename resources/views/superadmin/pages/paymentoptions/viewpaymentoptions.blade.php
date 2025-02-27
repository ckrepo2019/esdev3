
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }
      </style>

@endsection


@section('modalSection')
  <div class="modal fade" id="addpaymentoptions" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-primary">
            <h5 class="modal-title">Payment Options Form</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="paymentOptionsModal"
                  method="POST" 
                  {{-- action="/superadmin/create/paymentoptions" --}}
                  enctype="multipart/form-data"
                  >
            @csrf
            <input hidden id="poid" name="poid">
            <div class="modal-body">
                  <div class="form-group">
                        <label for="">Payment Type</label>
                        <select name="paymenttype" id="paymenttype" class="form-control">
                              <option value="">SELECT PAYMENT TYPE </option>
                              @foreach(DB::table('paymenttype')->where('isonline','1')->get() as $item)
                                    <option value="{{$item->id}}">{{$item->description}}</option>
                              @endforeach
                        </select>
                        <span class="invalid-feedback" role="alert">
                              <strong id="paymenttypeError"></strong>
                        </span>
                  </div>
                  <div class="form-group">
                        <label for="">Bank Name</label>
                        <input class="form-control" placeholder="Bank Name" id="paymentDesc" name="paymentDesc" disabled>
                  </div>
                  <div class="form-group">
                        <label for="">Account Name</label>
                        <input class="form-control" placeholder="Account Name" disabled id="accName" name="accName">
                  </div>
                  <div class="form-group">
                        <label for="">Account Number</label>
                        <input class="form-control" placeholder="Account Number" disabled id="accNum" name="accNum">
                  </div>
                  <div class="form-group">
                        <label for="">Mobile Number</label>
                        <input class="form-control" placeholder="Mobile Number" disabled name="mobileNum" id="mobileNum">
                  </div>
                  <div class="form-group">
                        <label for="">Payment Option Logo</label>
                        <input type="file" class="form-control" id="paymentLogo" name="paymentLogo">

                        <span class="invalid-feedback" role="alert">
                              <strong id="paymentLogoError"></strong>
                        </span>
                  </div>
                  <script>
                        $(document).ready(function(){
                              $(document).on('change','#paymenttype',function(){

                                    if($(this).val() == 3){

                                          $('#paymentDesc').removeAttr('disabled')
                                          $('#accName').removeAttr('disabled')
                                          $('#accNum').removeAttr('disabled')

                                          $('#mobileNum').attr('disabled','disabled')
                                          $('#mobileNum').val('')

                                    }
                                   
                                    else if($(this).val() == 4){

                                          $('#mobileNum').removeAttr('disabled')
                                          $('#paymentDesc').attr('disabled','disabled')
                                          $('#accNum').attr('disabled','disabled')
                                          $('#accName').removeAttr('disabled')

                                          $('#accNum').val('')
                                          $('#accName').val('')
                                          $('#paymentDesc').val('')
                                    }
                                    else{

                                          $('#paymentDesc').attr('disabled','disabled')
                                          $('#accName').removeAttr('disabled')
                                          $('#accNum').attr('disabled','disabled')
                                          $('#mobileNum').removeAttr('disabled')

                                          $('#paymentDesc').val('')
                                          $('#mobileNum').val('')

                                    }
                                    
                              })
                        })
                  </script>
               
            </div>
            <div class="modal-footer justify-content-between">
                <button  type="submit" class="btn btn-primary savebutton">SAVE</button>
            </div>
      </form>
        </div>
    </div>
  </div>
@endsection

@section('content')
{{-- 
@php
      $onlinepayments = $data[0]->data;
@endphp --}}

<section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">School Info</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
        <div class="container-fluid">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-primary">
                                    <h5 class="card-title">PAYMENT OPTIONS</h5>
                                    <button class="btn btn-sm btn-default float-right" data-toggle="modal"  data-target="#addpaymentoptions" title="Contacts" data-widget="chat-pane-toggle"><b>ADD PAYMENT OPTIONS</b></button>
                              </div>
                              <div class="card-body table-responsive p-0 " id="dataholder" style="min-height:539px">
                                  @include('superadmin.pages.paymentoptions.paymentoptionstable')
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container">
                                    </div>
                              </div> 
                        </div>   
                  </div>
            </div>
        </div>
</section>
@endsection

@section('footerjavascript')

     
      <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

      <script src="{{asset('js/pagination.js')}}"></script> 
      <script>

            $(document).ready(function(){

                  const Toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000,
                              timerProgressBar: true,
                              onOpen: (toast) => {
                              toast.addEventListener('mouseenter', Swal.stopTimer)
                              toast.addEventListener('mouseleave', Swal.resumeTimer)
                              }
                        })

                  function readURL(input) {
                        if (input.files && input.files[0]) {
                              var reader = new FileReader();
                              reader.onload = function (e) {
                                    $('#img'+$('#poid').val()).attr('src', e.target.result);
                              }
                              reader.readAsDataURL(input.files[0]);
                        }
                  }

             

                  $('#paymentOptionsModal')
                        .submit( function( e ) {
                              

                        var inputs = new FormData(this)     

                        $('.savebutton').attr('disabled','disabled')

                        $.ajax( {

                              url: '/superadmin/create/paymentoptions',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    
                                    if(data[0].status == 0){

                                          $.each(data[0].errors,function(a,b){
                                                      $('#'+a).addClass('is-invalid')
                                                      $('#'+a+'Error').text(b)
                                          })

                                          Toast.fire({
                                                type: 'error',
                                                title: 'Inavlid Input!'
                                          })

                                          $('.savebutton').removeAttr('disabled')

                                     }else{

                                          $('input').each(function(){
                                                $(this).removeClass('is-invalid')
                                          })

                                          if($('#poid').val() != ''){
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Updated successfully!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })
                                          }
                                          else{
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Created successfully!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })
                                          }
                                          
                                         
                                          $('#addpaymentoptions').modal('hide')

                                          $.ajax({
                                                type:'GET',
                                                url:'/superadmin/filter/paymentoptions',
                                                data:{
                                                      data:$("#search").val(),
                                                      pagenum: $('.paginationjs-page.J-paginationjs-page.active').attr('data-num')
                                                },
                                                success:function(data) {

                                                      $('#dataholder').empty();
                                                      $('#dataholder').append(data);

                                                      readURL($('#paymentLogo')[0])

                                                      if($('#poid').val() != ''){
                                                            pagination($('#searchCount').val(),false,$('.paginationjs-page.J-paginationjs-page.active').attr('data-num'))
                                                      }
                                                      else{
                                                            pagination($('#searchCount').val(),false,1)
                                                      }
                                                     
                                                      

                                                     

                                                },
                                                complete:function(){

                                                
                                                      document.getElementById('paymentOptionsModal').reset();

                                                      $('.savebutton').removeAttr('disabled')

                                                      $('#paymentDesc').attr('disabled','disabled')
                                                      $('#accName').attr('disabled','disabled')
                                                      $('#accNum').attr('disabled','disabled')

                                                      $('input').each(function(){
                                                            $(this).removeClass('is-invalid')
                                                      })

                                               

                                                      
                                                }
                                          })

                                    }


                              }
                              
                        })
                        
                        e.preventDefault();
                  })

                  $(document).on('click','#removePaymentOption',function(){

                        Swal.fire({
                              title: 'Are you sure you want to remove payment option?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Remove'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/remove/paymentoptions/'+$(this).attr('data-id'),
                                          success:function(){
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Deleted successfully!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })

                                                var pagenum = $('.paginationjs-page.J-paginationjs-page.active').attr('data-num')
                                                filterFirstPage(pagenum)
                                          }
                                    })
                              }
                        })
                  })

                  $(document).on('click','#setActiveState',function(){

                        Swal.fire({
                              title: 'Are you sure you want to set payment option as active?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Activate'
                        })
                        .then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setactive/paymentoptions/'+$(this).attr('data-id'),
                                          success:function(){
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Changed to active!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })

                                                var pagenum = $('.paginationjs-page.J-paginationjs-page.active').attr('data-num')
                                                filterFirstPage(pagenum)
                                          }
                                    })
                              }
                        })

                        
                  })

                  $(document).on('click','#removeActiveState',function(){

                        Swal.fire({
                              title: 'Are you sure you want to set payment option as inactive?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Inactive'
                        })
                        .then((result) => {
                              if (result.value) {

                                    $.ajax({
                                          type:'GET',
                                          url: '/superadmin/setasinactive/paymentoptions/'+$(this).attr('data-id'),
                                          success:function(){
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Changed to inactive!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })

                                                var pagenum = $('.paginationjs-page.J-paginationjs-page.active').attr('data-num')
                                                filterFirstPage(pagenum)
                                          }
                                    })
                              
                              }
                        })
                        
                  })


                  function filterFirstPage(pagenum){

                        $.ajax({
                              type:'GET',
                              url:'/superadmin/filter/paymentoptions',
                              data:{
                                    data:$("#search").val(),
                                    pagenum:$pagenum
                              },
                              success:function(data) {
                                    $('#dataholder').empty();
                                    $('#dataholder').append(data);
                                    pagination($('#searchCount').val(),false,pagenum)
                              }
                        })

                  }

                  pagination('{{$data[0]->count}}',false,1);

                  function pagination(itemCount,pagetype,pagenum){

                        var result = [];
                        for (var i = 0; i < itemCount; i++) {
                        result.push(i);
                        }
                        
                        var pageNum = pagenum;

                        $('#data-container').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {
                                    if(pagetype){
                                          $.ajax({
                                                type:'GET',
                                                url:'/superadmin/filter/paymentoptions',
                                                data:{
                                                      data:$("#search").val(),
                                                      pagenum:pagination.pageNumber
                                                },
                                                success:function(data) {
                                                      $('#dataholder').empty();
                                                      $('#dataholder').append(data);
                                                }
                                          })
                                    }
                              pagetype=true
                              }
                        })
                  }

                  $(document).on('keyup','#search',function() {
                        $.ajax({
                              type:'GET',
                              url:'/superadmin/filter/paymentoptions',
                              data:{data:$(this).val()},
                              success:function(data) {
                                    $('#dataholder').empty();
                                    $('#dataholder').append(data);
                                    pagination($('#searchCount').val(),false,1)
                              }
                        })
                  });

            
            })
      </script>
    
    
    
@endsection

