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
                    <h3><i class="fas fa-exclamation-triangle text-warning"></i> This room has been deleted</h3>
                    <p><a href="/admingetrooms"> click here </a> to view all room</a>.</p>
                </div>
            </div>
        </section>
@endsection

@else
    @section('modalSection')
        <div class="modal fade" id="modal-primary" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">Room Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="roomform" method="GET" action="/adminupdateroom">
                    <input name="si" type="hidden" id="si" value="{{$roomsdetail[0]->id}}">
                    <div class="modal-body">
                        <div class="message"></div>
                        <div class="form-group">
                            <label>Room Name</label>
                            <input value="{{$roomsdetail[0]->roomname}}"  id="roomName"  name="roomName" class="form-control @error('roomName') is-invalid @enderror" placeholder="Room Name" onkeyup="this.value = this.value.toUpperCase();">
                            @if($errors->has('roomName'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('roomName') }}</strong>
                              </span>
                            @endif
                        </div>
                        <div class="form-group">
                          <label>Room Capacity</label>
                          <input value="{{$roomsdetail[0]->capacity}}" id="roomCapacity" placeholder="Room Capacity" name="roomCapacity" class="form-control @error('roomCapacity') is-invalid @enderror" min="1" oninput="this.value=this.value.replace(/[^0-9]/g,'');" >
                          @if($errors->has('roomCapacity'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('roomCapacity') }}</strong>
                            </span>
                          @endif
                        </div>
                        <div class="form-group">
                            <label>Building</label>
                            <select name="building" id="building" class="form-control select2  @error('building') is-invalid @enderror">
                                <option selected value="">SELECT BUILDING</option>
                                @foreach (DB::table('building')->where('deleted','0')->get() as $item)
                                    @if($errors->any())
                                      @if($item->id == old('building'))
                                          <option selected value="{{$item->id}}">{{ $item->description}}</option>
                                      @else
                                          <option value="{{$item->id}}">{{ $item->description}}</option>
                                      @endif
                                    @else
                                        @if($item->id == $roomsdetail[0]->buildingid)
                                            <option selected value="{{$item->id}}">{{ $item->description}}</option>
                                        @else
                                            <option value="{{$item->id}}">{{ $item->description}}</option>
                                        @endif
                                    @endif
                                   
                                @endforeach
                            </select>
          
                            @if($errors->has('building'))
                              <span class="invalid-feedback" role="alert">
                                  <strong>{{ $errors->first('building') }}</strong>
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
                        <li class="breadcrumb-item"><a href="/admingetrooms">Rooms</a></li>
                        <li class="breadcrumb-item">{{$roomsdetail[0]->roomname}}</li>
                    </ol>
                </div>
            </div>
            </div>
        </section>
        <section class="content pt-0">
            <div class="container-fluid">
                <div class="row">
                <div class="col-md-9">
                    <div class="card" style="height: 300px">
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-edit" style="color:#ffc107"></i>
                                ROOM SCHEDULE
                            </h3>
                        </div>
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th class="align-middle">Section Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach($cdetails as $item)
                                <tr>
                                    <td><a href="#">{{$item->sectionname}}</a></td>
                                </tr>
                            @endforeach --}}
                            
                        </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-3">
                <div class="card h-100" >
                        <div class="card-header bg-info">
                            <h3 class="card-title">
                                <i class="fas fa-question" style="color:#ffc107"></i>
                                ABOUT ROOM
                            </h3>
                        </div>
                        <div class="card-body">
                            @foreach($roomsdetail as $item)
                                <label><i class="fa fa-door-open mr-2"></i> Room Name</label>
                                <p class="text-success">{{$item->roomname}}</p>
                                <hr>
                                <label><i class="fa fa-users mr-2"></i> Room Capacity</label>
                                <p class="text-success">{{$item->capacity}}</p>
                                <hr>
                                <label><i class="fa fa-users mr-2"></i>Building</label>
                                <p class="text-success">{{$item->description}}</p>
                            @endforeach

                            <hr>
                                <button type="button" class="btn btn-sm btn-outline-primary btn-block" data-toggle="modal" data-target="#modal-primary" ><i class="far fa-edit"></i> EDIT</button>
                                
                            @if(!$inUse)

                                <a href="#" type="button" class="btn btn-sm btn-outline-danger btn-block deleterooms" ><i class="fa fa-trash"></i> DELETE</a>
                           
                            @endif

                        
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </section>
        
    @endsection
    @section('footerjavascript')
        <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
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
        </script>
    @endsection
@endif
