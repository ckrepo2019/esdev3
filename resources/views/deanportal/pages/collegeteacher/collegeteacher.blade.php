
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')
<link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('content')
      
      <section class="content">
            <div class="card">
                  <div class="card-header">
                        TEACHER
                        <div class="input-group input-group-sm float-right w-25 search">
                              <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
                        </div>
                  </div>
                  <div class="card-body p-0" id="teacher_table_holder">
                        
                  </div>
                  <div class="card-footer">
                        <div class="" id="data-container">
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
                        url:'/collegeteachers?take='+take+'&skip='+skip+'&table=table'+'&search='+search,
                        success:function(data) {
                              console.log(data);
                              $('#teacher_table_holder').empty();
                              $('#teacher_table_holder').append(data);
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

