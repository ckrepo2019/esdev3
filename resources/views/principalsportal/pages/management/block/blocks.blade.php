@php
 $refid = DB::table('usertype')->where('id',auth()->user()->type)->where('deleted',0)->select('refid')->first();
@endphp


@if(auth()->user()->type == 2)

    @php
        $xtend = 'principalsportal.layouts.app2';
    @endphp

@else

   
    @if( $refid->refid == 20)
        @php
            $xtend = 'principalassistant.layouts.app2';
        @endphp
    @elseif( $refid->refid == 22)
        @php
            $xtend = 'principalcoor.layouts.app2';
        @endphp
    @endif

@endif

@extends($xtend)


@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('modalSection')
<div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <h4 class="modal-title">Section Form</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        </div>
        <form action="/prinicipalstoreblock" method="GET">
            <div class="modal-body">
                <div class="message">
                </div>
                <div class="form-group">
                    <label>Block Name</label>
                    <input name="bn" class="form-control @if (\Session::has('bn')) is-invalid @endif" id="bn" placeholder="Enter section name">
                    @if (\Session::has('bn'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{\Session::get('bn')->message}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Gradelevel</label>
                    <select class="form-control @if (\Session::has('gradelevel')) is-invalid @endif" name="gradelevel" id="gradelevel">
                        <option value="" selected disabled>Select Gradelevel</option>
                        @foreach (DB::table('gradelevel')->where('deleted',0)->where('acadprogid',5)->get() as $item)
                            <option value="{{$item->id}}" >{{$item->levelname}}</option>
                        @endforeach
                    </select>
                    @if (\Session::has('gradelevel'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{\Session::get('si')->message}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <label>Strand</label>
                    <select class="form-control @if (\Session::has('si')) is-invalid @endif" name="si" id="si">
                        <option value="" selected disabled>Select Strand</option>
                        @foreach (\App\Models\Principal\SPP_Strand::loadSHStrands() as $item)
                            <option value="{{$item->id}}" >{{$item->strandname}}</option>
                        @endforeach
                    </select>
                    @if (\Session::has('si'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{\Session::get('si')->message}}</strong>
                        </span>
                    @endif
                </div>
               
            </div>
            <div class="modal-footer justify-content-between">
                <button type="submit" class="btn btn-primary ss">Save</button>
            </div>
        </form>
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
            <li class="breadcrumb-item active">Blocks</li>
        </ol>
        </div>
    </div>
    </div>
</section>

<section class="content">
    <!-- Default box -->
    <div class="card card-primary card-outline principalblock">
        <div class="card-header border-0 bg-info">
            
            <div class="input-group input-group-sm float-right w-25" >
                <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(auth()->user()->type == 2 || (isset($refid->refid) && ($refid->refid == 20 || $refid->refid == 22) ))
                <button type="button" class="btn btn-primary btn-sm createsect float-right mb-2 mr-2" data-toggle="modal" data-target="#modal-primary">
                    Create Block
                </button>
                
            @endif
        </div>
        <div class="card-body p-0 pt-0" id="blockholder">
            @include('search.principal.block')
        </div>
        <div class="card-footer clearfix">
            <div class="mt-3" id="data-container"></div>
        </div>
    </div>
</section>
  


@endsection


@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script>

    <script>
        $(document).ready(function(){
            @if ($errors->any())
                $('#modal-primary').modal('show');
            @endif
        });
    </script>

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
                    url:'/prinicipalsearchblock',
                    data:{
                      data:$("#search").val(),
                      pagenum:pagination.pageNumber},
                    success:function(data) {
                      $('#blockholder').empty();
                      $('#blockholder').append(data);
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
              url:'/prinicipalsearchblock',
              data:{data:$(this).val(),pagenum:'1'},
              success:function(data) {
                $('#blockholder').empty();
                $('#blockholder').append(data);
                pagination($('#searchCount').val())
              }
            })
          });
        })
    </script>

@endsection

