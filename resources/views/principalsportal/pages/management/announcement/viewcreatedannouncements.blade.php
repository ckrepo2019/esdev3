@extends('principalsportal.layouts.app2')


@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <style>
        th, td{
            padding: .5em !important;
        }
        p{
          margin:0 !important;
        }
        b{
          font-weight:normal !important;
        }
    </style>
@endsection()


@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Announcement Logs</li>
      </ol>
      </div>
  </div>
  </div>
</section>

<section class="content-header">
    <div class="row">
        <div class="col-md-12">
            <div class="card principalannouncement">
                <div class="card-header bg-info">
                <span class="" style="font-size: 16px"><b><i class="nav-icon far fa-circle"></i> SENT ANNOUNCEMENT</b></span>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" >
                            <input type="text" id="search" name="announcement" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0 m-0" id="announcementholder">
                    @include('search.principal.announcement')
                </div>
                <div class="card-footer">
                    <div class="mt-3" id="data-container"></div>
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

          @foreach($data[0]->data as $key=>$item)
            $('.content-td')['{{$key}}'].innerHTML = ('{!! Str::limit($item->content, $limit = 20, $end = '...') !!}')
          @endforeach

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
                    url:'/principalsearchannouncement',
                    data:{
                      data:$("#search").val(),
                      pagenum:pagination.pageNumber},
                    success:function(data) {
                      $('#announcementholder').empty();
                      $('#announcementholder').append(data);
                    }
                  })
                }
                pagetype=true;
              },
                hideWhenLessThanOnePage: true,
                pageSize: 10,
            })
          }
          $("#search" ).keyup(function() {
            $.ajax({
              type:'GET',
              url:'/principalsearchannouncement',
              data:{data:$(this).val(),pagenum:'1'},
              success:function(data) {
                $('#announcementholder').empty();
                $('#announcementholder').append(data);
                pagination($('#searchCount').val())
              }
            })
          });
        })
    </script>
@endsection





