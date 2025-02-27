

@extends('layouts.app')

@section('headerscript')

    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
  
@endsection

@section('content')
<section class="content ">
      
      <div class="row">
            <div class="col-md-12">
                  <div class="card">
                        <div class="card-header">
                              
                              <div class="card-title pt-2 col-md-8">PRE REGISTERED STUDENTS</div>
                              <div class="input-group float-right search col-md-4">
                                    <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
                              </div>
                          
                        </div>
                        <div class="card-body" id="dataholder">
                              @include('prereginquirytable')
                        </div>
                        <div class="card-footer">
                              <div class="" id="data-container">
                            </div>
                  </div>
            </div>
      </div>
      
</section>

<script src="{{asset('js/pagination.js')}}"></script> 
<script>
      $(document).ready(function(){
     

     pagination('{{$count}}',false);

     function pagination(itemCount,pagetype){


       var result = [];
       for (var i = 0; i < itemCount; i++) {
         result.push(i);
       }
       
       var pageNum = 1;

       $('#data-container').pagination({
         dataSource: result,
         hideWhenLessThanOnePage: true,
         pageNumber: pageNum,
         pageRange: 1,
         callback: function(data, pagination) {
           if(pagetype){
             $.ajax({
               type:'GET',
               url:'/searchprereg',
               data:{
                 data:$("#search").val(),
                 pagenum:pagination.pageNumber},
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
         url:'/searchprereg',
         data:{data:$(this).val()},
         success:function(data) {
           $('#dataholder').empty();
           $('#dataholder').append(data);
           pagination($('#searchCount').val())
         }
       })
     });

   
   })
</script>
@endsection


                        
            

