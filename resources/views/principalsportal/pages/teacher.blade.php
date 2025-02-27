@extends('principalsportal.layouts.app2')


@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row">
      <div class="col-sm-6">
      <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000"><i class="fa fa-chart-line nav-icon"></i> TEACHERS</h4>
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Teachers</li>
      </ol>
      </div>
  </div>
  </div>
</section>

<section class="content">
    <div class="card card-primary card-outline principalteacher">
      <div class="card-header bg-info">
          <h5 class="card-title">
              <i class="fas fa-user"></i>
              Teachers List 
          </h5>
          <div class="input-group input-group-sm w-25 search float-right" >
            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
            </div>
        </div>
      </div>
      <div class="card-body table-responsive pb-1" id="teacherholder">
        @include('search.principal.facultystaff')
      </div>
      <div class="card-footer">
          <div id="data-container"></div>
      </div>
    </div>
</section>

@endsection

@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script>

    <script>
        $(document).ready(function(){

          pagination('{{$data[0]->count}}',false);
          function pagination(itemCount,pagetype){
            var result = [];
            for (var i = 0; i < itemCount; i++) {
              result.push(i);
            }
            $('#data-container').pagination({
              dataSource: result,
              callback: function(data, pagination) {
                if(pagetype){
                  $.ajax({
                    type:'GET',
                    url:'/searchteacherajax',
                    data:{
                      data:$("#search").val(),
                      pagenum:pagination.pageNumber},
                    success:function(data) {
                      $('#teacherholder').empty();
                      $('#teacherholder').append(data);
                    }
                  })
                }
                pagetype=true;
              },
                hideWhenLessThanOnePage: true,
                pageSize: 6,
            })
          }
          $("#search" ).keyup(function() {
            $.ajax({
              type:'GET',
              url:'/searchteacherajax',
              data:{data:$(this).val(),pagenum:'1'},
              success:function(data) {
                $('#teacherholder').empty();
                $('#teacherholder').append(data);
                pagination($('#searchCount').val())
              }
            })
          });
        })
    </script>
@endsection
