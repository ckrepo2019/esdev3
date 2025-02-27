
@extends('scholarshipcoor.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

@endsection

@section('content')
      <div class="modal fade" id="student_info" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h5 class="modal-title">Student Information</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body" id="studentinfo_modal">

                        </div>
                  </div>
            </div>
      </div>

      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-6">
                        
                        </div>
                        <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">COR Printing</li>
                        </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-12">
                              <div class="card">
                                    <div class="card-header">
                                          <div class="d-flex justify-content-between">
                                            <h3 class="card-title">ENROLLED STUDENT</h3>
                                            <div class="card-tools">
                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                              
                                                  <div class="input-group-append">
                                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                                  </div>
                                                </div>
                                              </div>
                                          </div>
                                    </div>
                                    <div class="card-body p-0" id="enrolledstud_card_holder">
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

      <script src="{{asset('js/pagination.js')}}"></script> 

      <script>
            
            $(document).ready(function(){

                  $(document).on('click','.view_stud_info',function(){
                        
                        $('#student_info').modal();

                        $.ajax({
                              type:'GET',
                              url:'/collegeStudentMasterlist?info=info&studid='+$(this).attr('data-id'),
                              success:function(data) {
                                   $('#studentinfo_modal').empty()
                                   $('#studentinfo_modal').append(data)
                              }
                        })

                  })

                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/collegeStudentMasterlist?take='+take+'&skip='+skip+'&table=table'+'&search='+search,
                              success:function(data) {
                                    $('#enrolledstud_card_holder').empty();
                                    $('#enrolledstud_card_holder').append(data);
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

