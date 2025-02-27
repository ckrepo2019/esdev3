@extends('adminPortal.layouts.app2')

@section('pagespecificscripts')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection


@if($deleted)
    @section('content')

        <section class="content-header">

        </section>
        <section class="content">
            <div class="error-page">
                <div class="error-content pt-4">
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> This building has been deleted</h3>
                    <p><a href="/admin/get/buildings"> click here </a> to view all building</a>.</p>
                </div>
            </div>
        </section>
    @endsection

@else
     
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
                <form id="buildingform" method="POST" action="/admin/update/building" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                       
                        <input value="{{$building->id}}" hidden name="data-id" id="data-id">
                        <div class="form-group">
                            <label><b>Building Description</b></label>
                            <input 
                                @if(@old('buildingCapacity') != null)
                                    value="{{@old('buildingDesc')}}"
                                @else
                                    value="{{$building->description}}"
                                @endif 
                        
                                id="buildingDesc"  
                                name="buildingDesc" 
                                class="form-control @error('buildingDesc') is-invalid @enderror"
                                placeholder="Building Description" 
                                onkeyup="this.value = this.value.toUpperCase();">
                            @if($errors->has('buildingDesc'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('buildingDesc') }}</strong>`
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                        <label>Building Capacity</label>
                        <input  @if(@old('buildingCapacity') != null)
                                    value="{{@old('buildingCapacity')}}"
                                @else
                                    value="{{$building->capacity}}"
                                @endif 
                                      
                                id="buildingCapacity" 
                                placeholder="Building Capacity" 
                                name="buildingCapacity" 
                                class="form-control @error('buildingCapacity') is-invalid @enderror" 
                                min="1" 
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
                        @if($errors->has('buildingCapacity'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('buildingCapacity') }}</strong>
                            </span>
                        @endif
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button class="btn btn-info savebutton">SAVE</button>
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
                        <li class="breadcrumb-item"><a href="/admin/get/buildings">Buildings</a></li>
                        <li class="breadcrumb-item"></li>
                    </ol>
                </div>
            </div>
            </div>
        </section>
        <section class="content pt-0">
            <div class="container-fluid">
                <div class="row">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-edit" style="color:#ffc107"></i>
                                ROOMS
                            </h3>
                        </div>
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle">Room Name</th>
                                <th class="align-middle">Room Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                              @foreach ($buildingUsage as $item)
                                    <tr>
                                    <td>{{$item->roomname}}</td>
                                    <td>{{$item->capacity}}</td>
                                    </tr>
                              @endforeach
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                <div class="card h-100" >
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-question" style="color:#ffc107"></i>
                                ABOUT BUILDING
                            </h3>
                        </div>
                        <div class="card-body">
                       
                              <label><i class="fa fa-door-open mr-2"></i>Building Description</label>
                              <p class="text-success">{{$building->description}}</p>
                              <hr>
                              <label><i class="fa fa-users mr-2"></i>Buidling Capacity</label>
                              <p class="text-success">{{$building->capacity}}</p>
                              <hr>
                              <label><i class="fa fa-users mr-2"></i>Buidling Usage</label>
                              <p class="text-success">{{collect($buildingUsage)->sum('capacity')}}</p>
                              {{--<hr>
                              <label><i class="fa fa-users mr-2"></i>Building</label>
                              <p class="text-success">{{$item->description}}</p>
                             --}}
                              <hr>
                              <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#building" ><i class="far fa-edit"></i> EDIT</button>
                              @if(collect($buildingUsage)->sum('capacity') == 0)

                                    <a href="/admin/remove/building/{{$building->id}}" type="button" class="btn btn-sm btn-outline-danger btn-block deleterooms" ><i class="fa fa-trash"></i> DELETE</a>
                              
                              @endif

                        
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
                
                var a = $('#data-id').val()

                $('#buildingform').submit(function( e ) {
          
                    $('#data-id').val(a)

                })

            })
        </script>
        {{-- <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
        <script>
            $(document).ready(function(){

                $(function () {
                    $('#building').select2({
                        theme: 'bootstrap4'
                    })
                    
                })

                @if ($errors->any())
                    $('#modal-primary').modal('show')
                @endif

                $(document).on('click','.deleterooms',function(){
                        Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete room!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type:'GET',
                                url:'/adminremoveroom/{{$roomsdetail[0]->id}}',
                                success:function(data) { 
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: 'Room has been deleted!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(()=>{
                                        location.reload(); 
                                    })
                                }
                            });
                        }
                    })
                });

            })
        </script> --}}
    @endsection
@endif
