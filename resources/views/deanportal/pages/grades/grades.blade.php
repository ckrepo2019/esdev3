
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('content')
      <div class="modal fade" id="submittedgrades_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content ">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">Grade Submitted</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body table-responsive" style="height:  767px;">
                              <div class="row" id="submittedgrades_holder_modal">
                              
                              </div>
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
                                          <h3 class="card-title">SUBMITTED GRADES</h3>
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
                                    <div class="card-body p-0" id="submitted_grade_card_holder">

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

                        const Toast = Swal.mixin({
                                          toast: true,
                                          position: 'top-end',
                                          showConfirmButton: false,
                                          timer: 3000
                                    });

                        processpaginate(0,10,null,true)

                        function processpaginate(skip = null,take = null ,search = null, firstload = true){

                              $.ajax({
                                    type:'GET',
                                    url:'/dean/view/grades?take='+take+'&skip='+skip+'&table=table'+'&search='+search,
                                    success:function(data) {
                                          $('#submitted_grade_card_holder').empty();
                                          $('#submitted_grade_card_holder').append(data);
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

                        $(document).on('click','.view_grade',function(){

                              $('#submittedgrades_modal').modal()

                              $.ajax({
                                    type:'GET',
                                    url:'/dean/view/grades?update=update&logid='+$(this).attr('data-id'),
                                    success:function(data) {
                                      
                                          processpaginate(pageNum,10,$('#search').val(),null)
                                    
                                    }
                              })


                              loadgrades($(this).attr('data-id'))

                             

                        })

                        function loadgrades(logid){

                              $.ajax({
                                    type:'GET',
                                    url:'/dean/view/grades?viewgrade=viewgrade&logid='+logid,
                                    success:function(data) {

                                          $('#submittedgrades_holder_modal').empty();
                                          $('#submittedgrades_holder_modal').append(data);
                                    
                                    }
                              })

                        }

                        $(document).on('click','.post_grade',function(){

                              $('#submittedgrades_modal').modal()

                              var selectedLogid = $(this).attr('data-logid')

                              $.ajax({
                                    type:'GET',
                                    url:'/dean/view/grades?postgrade=postgrade&gradeid='+$(this).attr('data-id')+'&term='+$(this).attr('data-term'),
                                    success:function(data) {
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Posted successfully!'
                                          })
                                          loadgrades(selectedLogid)
                                    
                                    }
                              })

                        })
                  }) 
            </script>
@endsection

