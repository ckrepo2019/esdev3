

@extends('hr.layouts.app')
@section('content')
@php
$refid = DB::table('usertype')
    ->where('id', Session::get('currentPortal'))
    ->first()->refid;
@endphp
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-4">
          <!-- <h1>Employees</h1> -->
          <h4 >Employees </h4>
        </div>
        <div class="col-sm-5">
          <input type="text" class="form-control form-control" id="input-filter" placeholder="Search employee..."/>
            {{-- <select class="form-control select2" style="width: 100%;">
            <option selected="selected">Alabama</option>
            <option>Alaska</option>
            <option>California</option>
            <option>Delaware</option>
            <option>Tennessee</option>
            <option>Texas</option>
            <option>Washington</option>
            </select> --}}
        </div>
        <div class="col-sm-3">
            @if($refid != 26)
            <a href="/hr/employees/addnewemployee/index" class="btn btn-primary btn-block" style="color: white;"><i class="fa fa-plus"></i> &nbsp;Add Employee</a>
            @endif
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content-body">    
    <div class="card card-nav-header" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
      <div class="card-header d-flex p-0" style="font-size: 14px;">
        {{-- <h3 class="card-title p-3">Tabs</h3> --}}
        <ul class="nav nav-pills ml-auto p-2" style="margin-left: 0px !important;">
        <li class="nav-item"><a class="nav-link active" href="#tab_1-active-employees" data-toggle="tab" style="border-radius: 25px !important;">Active Employees &nbsp;&nbsp;<span class="badge badge-warning">{{collect($employees)->where('isactive','1')->count()}}</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#tab_2-inactive-employees" data-toggle="tab" style="border-radius: 25px !important;">Inactive &nbsp;&nbsp;<span class="badge badge-warning">{{collect($employees)->where('isactive','0')->count()}}</span></a></li>
        </ul>
        <ul class="nav nav-pills ml-auto p-2">
        <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=pdf" style="border-radius: 25px !important;" target="_blank">Export to PDF</a></li>
        <li class="nav-item"><a class="nav-link" href="/hr/employees/index?action=export&exporttype=excel" style="border-radius: 25px !important;" target="_blank">Export to Excel</a></li>
        </ul>
      </div>
    </div>
    <div class="tab-content tab-header-content">
      <div class="tab-pane active" id="tab_1-active-employees">
        <div class="row d-flex align-items-stretch">
          @foreach(collect($employees)->where('isactive','1')->values() as $employee)
          <div class="col-sm-4 eachemployee mb-2"data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->utype}}<">
            <div class="card h-100 " style=" border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
              <div class="card-body p-0">
                <div class="row ml-0 mr-2 h-100">
                  <div class="col-md-4 p-2" style="background-color: #ababab;  border-top-left-radius: 5px;  border-bottom-left-radius: 5px; height: 100%;">
                    <div class="row">
                      <div class="col-md-12 text-center mb-1">
                        @php
                          $number = rand(1,3);
                          if(strtoupper($employee->gender) == 'FEMALE'){
                              $avatar = 'avatar/T(F) '.$number.'.png';
                          }
                          else{
                              $avatar = 'avatar/T(M) '.$number.'.png';
                          }
                        @endphp
                        <img src="{{asset($employee->picurl.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss'))}}" 
                        onerror="this.onerror = null, this.src='{{asset($avatar)}}'" style="width: 60px; border: 2px solid white; border-radius: 10px;">
                      </div>
                      <div class="col-md-12 text-center">
                        <a type="button"
                        {{-- href="/hr/employees/profile/index?employeeid={{$employee->id}}" --}}
                        class="btn btn-block btn-sm text-center btn-default p-1 text-white btn-view-profile" data-id="{{$employee->id}}" style="font-size: 11px; background-color: #343a40;border: 2px solid white; border-radius: 10px;">View Profile</a>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-md-12">
                        <span style="font-size: 14px; font-weight: bold;">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}} {{ucwords(strtolower($employee->suffix))}}</span>
                      </div>
                      {{-- <div class="col-md-12">
                        <span class="badge badge-success">Status</span>
                        <span class="badge badge-primary">Status</span>
                      </div> --}}
                      <div class="col-md-12">
                        <span style="font-size: 14px; font-weight: bold;" class="text-muted">{{$employee->utype}}</span>
                      </div>
                      <div class="col-md-12">
                        <small class="text-muted"><i class="fa fa-phone"></i> &nbsp;{{$employee->contactnum}}</small>
                      </div>
                      <div class="col-md-12">
                        <small class="text-muted"><i class="fa fa-address-book"></i> &nbsp;{{$employee->primaryaddress}}</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          {{-- <div class="col-4"></div>
          <div class="col-4"></div> --}}
        </div>
      </div>
      <div class="tab-pane" id="tab_2-inactive-employees">
        <div class="row d-flex align-items-stretch">
          @foreach(collect($employees)->where('isactive','0')->values() as $employee)
          <div class="col-sm-4 eachemployee mb-2"data-string="{{$employee->lastname}}, {{$employee->firstname}} {{$employee->suffix}} {{$employee->utype}}<">
            <div class="card h-100 " style=" border-radius: 5px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;border: 1px solid #044254;">
              <div class="card-body p-0">
                <div class="row ml-0 mr-2 h-100">
                  <div class="col-md-4 p-2" style="background-color: #044254;  border-top-left-radius: 5px;  border-bottom-left-radius: 5px; height: 100%;">
                    <div class="row">
                      <div class="col-md-12 text-center mb-1">
                        <img src="{{asset('assets/images/avatars/unknown.png')}}" onerror="this.onerror = null, this.src='{{asset('assets/images/avatars/unknown.png')}}'" style="width: 60px; border: 2px solid white; border-radius: 10px;">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-md-12">
                        <span style="font-size: 14px; font-weight: bold;">{{ucwords(strtolower($employee->lastname))}}, {{ucwords(strtolower($employee->firstname))}} {{ucwords(strtolower($employee->suffix))}}</span>
                      </div>
                      {{-- <div class="col-md-12">
                        <span class="badge badge-success">Status</span>
                        <span class="badge badge-primary">Status</span>
                      </div> --}}
                      <div class="col-md-12">
                        <span style="font-size: 14px; font-weight: bold;">{{$employee->utype}}</span>
                      </div>
                      <div class="col-md-12">
                        <small class="text-muted"><i class="fa fa-phone"></i> &nbsp;{{$employee->contactnum}}</small>
                      </div>
                      <div class="col-md-12">
                        <small class="text-muted"><i class="fa fa-address-book"></i> &nbsp;Employee address</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          {{-- <div class="col-4"></div>
          <div class="col-4"></div> --}}
        </div>
      </div>
    </div>
    <div id="container-profile"></div>
  </section>
  
@endsection
@section('footerjavascript')
<script>
    
    $(document).ready(function(){
        $('.select2').select2({
          theme: 'bootstrap4'
        })
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
        $("#input-filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachemployee").each(function() {
                if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                $(".card-group.card-group-filter:first-of-type").append($(this));
                $(this).hide();
                hiddenCards++;

                } else {

                $(".card-group.card-group-filter:last-of-type").prepend($(this));
                $(this).show();
                visibleCards++;

                if (((visibleCards % 4) == 0)) {
                    $(".container").append($("<div class='card-group card-group-filter'></div>"));
                }
                }
            });

        });

        function getActiveEmployees()
        {

        }

        $('.btn-view-profile').on('click',function(){
              $('#container-profile').empty()
              $('#container-profile').hide()
            var empid = $(this).attr('data-id');
            $.ajax({
                    type:'GET',
                    url: '/hr/employees/getprofile',
                    data:{
                      empid: empid
                    },
                    success:function(data) {
                      $('.card-nav-header').hide()
                      $('.tab-header-content').hide()
                      $('#container-profile').append(data)
                      $('#container-profile').show()
                      
                    },
                    error:function(){
                      
                    }
            })
        })
        $(document).on('click','#btn-back', function(){
          $('#container-profile').hide()
          $('.card-nav-header').show()
          $('.tab-header-content').show()
        })
    })

</script>

@endsection
