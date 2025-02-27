@extends('clinic.layouts.app')
@section('content')
<style>
    .select2-container .select2-selection--single {
            height: 40px;
        }
</style>
    @php
        use \Carbon\Carbon;
        $now = Carbon::now();
        $comparedDate = $now->toDateString();
    @endphp

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h3 class="m-0">Personnel</h3>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Personnel</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <section class="content">

      <div class="modal fade" id="availabilityModal" tabindex="-1" role="dialog" aria-labelledby="availabilityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="availabilityModalLabel">Weekly Availability</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              @foreach ($personnel as $person)
              <div class="card mb-3">
                  <div class="card-header">
                      <h3>{{ $person->name }}</h3> <!-- Display personnel name -->
                  </div>
                  <div class="card-body">
                    <h5>Weekly Schedule:</h5>
                    <ul class="list-group">
                        @forelse ($person->weeklySchedule as $schedule)
                            <li class="list-group-item list-group-item-success">
                                {{ \Carbon\Carbon::parse($schedule->scheddate)->format('F d, Y') }} - {{ $schedule->timefrom }}: {{ $schedule->description }}
                            </li>
                        @empty
                            <li>No schedule available for this week.</li>
                        @endforelse
                    </ul>
                </div>
                
              </div>
          @endforeach
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>

        <div class="row d-flex align-items-stretch">
            @if(count($personnel)>0)
                @foreach($personnel as $person)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch">
                  <div class="card bg-light">
                    <div class="card-header text-muted border-bottom-0">
                      {{$person->utype}}
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <h2 class="lead text-uppercase"><b>{{$person->name}}</b></h2>
                          {{-- <p class="text-muted text-sm"><b>About: </b> Web Designer / UX / Graphic Artist / Coffee Lover </p> --}}
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small"><span class="fa-li"><i class="fa fa-lg fa-building"></i></span> Address: {{ucwords($person->address)}}</li>
                            <li class="small"><span class="fa-li"><i class="fa fa-lg fa-phone"></i></span> Phone #: {{$person->contactnum}}</li>
                          </ul>
                        </div>
                        <div class="col-5 text-center">
                            @php
                                $number = rand(1,3);
                                    if(strtolower($person->gender) == 'female'){
                                        $avatar = 'avatar/T(F) '.$number.'.png';
                                    }
                                    elseif(strtolower($person->gender) == 'male'){
                                        $avatar = 'avatar/T(M) '.$number.'.png';
                                    }else{
                                        $avatar = 'assets/images/avatars/unknown.png';
                                    }
                            @endphp
                          <img src="{{asset($person->picurl)}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" alt="user-avatar" class="img-circle img-fluid">
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                                @if($person->utype =="DOCTOR")
                                <a href="/clinic/doctor/availablity/index" class="btn btn-sm bg-teal">
                                  <i class="fa fa-calendar" aria-hidden="true"></i>
                                </a>
                                @endif
                                <button type="button" class="btn btn-default p-1 btn_show_availability">
                                    @if($person->loggedIn == 1 && $person->loggedOut == 0)
                                    Status : <span class="badge badge-success">Active</span>
                                    @else
                                    Status : <span class="badge badge-danger">Away</span>
                                    @endif
                                </button>
                              {{-- <a href="#" class="btn btn-sm bg-teal">
                                <i class="fas fa-comments"></i>
                              </a>
                              <a href="#" class="btn btn-sm btn-primary">
                                <i class="fas fa-user"></i> View Profile
                              </a> --}}
                        </div>
                    </div>
                  </div>
                </div>
                @endforeach
            @endif
        </div>
    </section>
    {{-- <div class="modal fade" id="modal-addpersonnel">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Add Personnel</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="row">
                  <div class="col-md-12">
                      <label>Select User</label><br/>
                      <select class="form-control select2" style="width: 100%;">
                        <option selected="selected">Alabama</option>
                        <option>Alaska</option>
                        <option>California</option>
                        <option>Delaware</option>
                        <option>Tennessee</option>
                        <option>Texas</option>
                        <option>Washington</option>
                      </select>
                  </div>
                  <div class="col-md-12">
                      <label>Select Type</label><br/>
                      <select></select>
                  </div>
              </div>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal" id="btn-closeexperience">Close</button>
            <button type="button" class="btn btn-primary" id="btn-addoption">Add</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div> --}}
    @endsection
    @section('footerjavascript')
        <script>
            $(function () {
              //Initialize Select2 Elements
              $('.select2').select2()
            })
            $(document).ready(function(){
                $('#btn-addpersonnel').on('click', function(){
                    $('#modal-addpersonnel').modal('show')
                    $.ajax({
                        url: '/clinic/personnel/getemployees',
                        type: 'GET',
                        success: function(){
                            
                        }
                    })
                })
                $('#btn-addtype').on('click', function(){
                    $('#modal-addtype').modal('show')
                })

                $('.btn_show_availability').on('click', function(){
                 $('#availabilityModal').modal()
                })
            })
            
        </script>
      
    @endsection
