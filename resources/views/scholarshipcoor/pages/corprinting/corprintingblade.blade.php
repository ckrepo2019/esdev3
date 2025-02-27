
@extends('scholarshipcoor.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">

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
                                      
                                                <div class="input-group input-group-sm" style="width: 150px;">
                                                <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
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

                  processpaginate(0,10,null,true)

                  function processpaginate(skip = null,take = null ,search = null, firstload = true){

                        $.ajax({
                              type:'GET',
                              url:'/studentforprintingtable?take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&facultynstaff=facultynstaff',
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

