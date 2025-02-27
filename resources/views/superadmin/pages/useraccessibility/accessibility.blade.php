
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('modalSection')
<div class="modal fade" id="viewaccessibility" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title">USER ACCESSIBILITY</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
              </button>
          </div>
         
              <input hidden id="poid" name="poid">
              <div class="modal-body user_accessibility_table" >
              </div>
              <div class="modal-footer">
                  <button  type="submit" class="btn btn-primary savebutton">CREATE ACCESSIBLITY</button>
              </div>
  
          </div>
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
            <div class="container-fluid">
            <div class="row">
            <div class="col-12">
                  <div class="card adminrooms">
                  <div class="card-header bg-primary">
                        <h5 class="card-title">USER ACCESSIBILITY</h5>
                        <button class="btn btn-sm btn-default float-right user_accessibility_view" data-toggle="modal"  data-target="#viewaccessibility" data-widget="chat-pane-toggle"><b>VIEW ACCESSIBILITY</b></button>
                  </div>
                  <div class="card-body usertype_table_holder p-0">
                        
                  </div>
                  <div class="card-footer">
                  <div class="mt-3" id="data-container">
                  </div>
                  </div>
            </div>
            </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

      <script>
            $(document).ready(function(){
            
                  loadusertype()

                  function loadusertype(){

                        $.ajax({
                              type:'GET',
                              url:'/usertypes/?table=table',
                              success:function(data) {
                                    $('.usertype_table_holder').empty()
                                    $('.usertype_table_holder').append(data)

                              }
                        })


                  }

                  $(document).on('click','.user_accessibility_view',function(){
                  
                        $.ajax({
                              type:'GET',
                              url:'/accessibility/?table=table',
                              success:function(data) {
                                    $('.user_accessibility_tabler').empty()
                                    $('.user_accessibility_table').append(data)

                              }
                        })
                  })

                  

            })

      </script>
    
@endsection

