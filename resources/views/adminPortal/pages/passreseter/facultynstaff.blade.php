
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

@endsection


@section('modalSection')

  <div class="modal fade" id="passModal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-sm">
      <form id="checkPassForm" method="POST" action="/matchPassword">
        <div class="modal-content">
                <div class="modal-body">
                    <div class="message"></div>
                    <div class="form-group">
                        <label>Enter Password</label>
                        <input type="password"  id="password"  name="password" class="form-control">
                        <span class="invalid-feedback" role="alert">
                            <strong>Password does not match</strong>
                        </span>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="submit" class="btn btn-primary">RESET</button>
                </div>
          </div>
      </form>
    </div>
  </div>

@endsection

@section('content')
      <section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Room</li>
                        </ol>
                  </div>
            </div>
      </div>
      </section>
    
    <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-info ">
                                    <span class="text-white h4"><b>Faculty and Staff Password Resetter</b></span>
                                    <input type="text" id="search" name="search" class="form-control form-control-sm float-right w-25" placeholder="Search" >
                              </div>
                              <div class="card-body table-responsive p-0" 
                              id="parent_student_users_table">
                              </div>
                              <div class="card-footer">
                                    <div class="" id="data-container">
                                    </div>
                              </div> 
                        
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

      <script src="{{asset('js/pagination.js')}}"></script> 
      <script>
            $(document).ready(function(){

                  var selected = null;

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  $('#checkPassForm').submit(function(e){
                        $.ajax({
                              type:'GET',
                              url:'/matchPassword?pass='+btoa($('#password').val()),
                              success:function(data) {
                                    if(data == 1){
                                    $('#passModal').modal('hide');
                                    $('#password').removeClass('is-invalid')
                                    
                                          $.ajax({
                                          type:'GET',
                                          url:'/users?reset=reset&id='+selected.attr('data-id'),
                                          success:function(data) {
                                                Swal.fire({
                                                      type: 'success',
                                                      title: 'Updated successfully!',
                                                      showConfirmButton: false,
                                                      timer: 1500
                                                })

                                                if(data == 1){
                                                      $('#checkPassForm')[0].reset()
                                                      selected.removeAttr('data-id')
                                                      selected.removeAttr('data-name')
                                                      selected.removeClass('btn-danger')
                                                      selected.addClass('btn-success')
                                                      selected.text('DEFAULT')
                                                }
                                          }
                                          })
                                    }
                                    else{
                                    $('#password').addClass('is-invalid')
                                    }
                              }
                        })
                        e.preventDefault()
                  })


                  $(document).on('click','#resetPass',function(){
                        
                        selected = $(this)
                        Swal.fire({
                              title: 'Are you sure you want to reset password?',
                              type: 'info',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Reset'
                        })
                        .then((result) => {
                              if (result.value) {

                              $('#passModal').modal();

                              }
                        })
                  })

                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){
                        $.ajax({
                              type:'GET',
                              url:'/passwordresseter?take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&facultynstaff=facultynstaff',
                              success:function(data) {
                                    $('#parent_student_users_table').empty();
                                    $('#parent_student_users_table').append(data);
                                    pagination($('#searchCount').val(),false)
                              
                              }
                        })

                  }

                  var pageNum = 1;

                  function pagination(itemCount,pagetype){

                        var result = [];

                        for (var i = 0; i < itemCount; i++) {
                              result.push(i);
                        }

                        $('#data-container').pagination({
                              dataSource: result,
                              hideWhenLessThanOnePage: true,
                              pageNumber: pageNum,
                              pageRange: 1,
                              callback: function(data, pagination) {

                                          if(pagetype){

                                                processpaginate(pagination.pageNumber,10,$('#search').val(),false)

                                          }

                                          pageNum = pagination.pageNumber
                                          pagetype=true
                                    }
                              })
                  }

                  $(document).on('keyup','#search',function() {
                        pageNum = 1
                        processpaginate(0,10,$('#search').val(),null)
                        
                  });

            })
      </script>
    
@endsection

