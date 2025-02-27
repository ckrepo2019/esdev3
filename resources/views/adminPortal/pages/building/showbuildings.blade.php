
@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
  <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('modalSection')
  <div class="modal fade" id="building" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header bg-info">
            <h5 class="modal-title">BUILDING FORM</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <form id="roomform" method="GET" action="/admin/add/building">
            <div class="modal-body">
                <div class="message"></div>
                <div class="form-group">
                    <label><b>Building Description</b></label>
                    <input value="{{@old('buildingDesc')}}"  id="buildingDesc"  name="buildingDesc" class="form-control @error('buildingDesc') is-invalid @enderror" placeholder="Building Description" >
                    @if($errors->has('buildingDesc'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('buildingDesc') }}</strong>
                      </span>
                    @endif
                </div>
                <div class="form-group">
                  <label>Building Capacity</label>
                  <input value="{{@old('buildingCapacity')}}" id="buildingCapacity" placeholder="Building Capacity" name="buildingCapacity" class="form-control @error('buildingCapacity') is-invalid @enderror" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
                  @if($errors->has('buildingCapacity'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('buildingCapacity') }}</strong>
                    </span>
                  @endif
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button onClick="this.form.submit(); this.disabled=true;" type="submit" class="btn btn-info savebutton">SAVE</button>
            </div>
        <form>
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
          <li class="breadcrumb-item active">Buildings</li>
      </ol>
      </div>
  </div>
  </div>
</section>

<section class="content p-0">
    <div class="container-fluid">
        <div class="card adminrooms mb-0">
          <div class="card-header bg-info">
          <span class="text-white" style="font-size: 16px"><b><i class="nav-icon fa fa-door-open"></i> BUILDINGS</b></span>
            <div class="input-group input-group-sm float-right w-25 search">
              <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
              <div class="input-group-append">
                  <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
              </div>
            </div>
            <button class="btn btn-sm btn-primary float-right mr-2 mb-2" data-toggle="modal"  data-target="#building" title="Contacts" data-widget="chat-pane-toggle"><b>ADD BULDING</b></button>
          </div>
          <div class="card-body table-responsive p-0 " id="dataholder" style="min-height:539px">
            @include('adminPortal.pages.building.buildingtable')
          </div>
          <div class="card-footer">
            <div class="" id="data-container">
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
                  @if ($errors->any())
                        $('#building').modal('show')
                  @endif

            })
            $(document).ready(function(){


              $('.buildingname').each(function(){

                var buildingname = $(this).attr('data-string').toLowerCase().replace(/\s+/g, '-')

                $(this).attr('href','/admin/view/building/info/'+buildingname)
              })

                  pagination('{{$data[0]->count}}',false);

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
                              url:'/admin/search/building',
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
                        url:'/admin/search/building',
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

