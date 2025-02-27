

@extends('hr.layouts.app')
{{-- @section('headerjavascript')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
@endsection --}}
@section('content')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<style>
  
        
  .select2-container .select2-selection--single {
            height: 40px !important;
        }
        .alert-primary {
    color: #004085;
    background-color: #cce5ff;
    border-color: #b8daff;
}
.alert-success {
    color: #155724;
    background-color: #d4edda;
    border-color: #c3e6cb;
}
.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
}
.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeeba;
}
.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">
          <h1>Career Growth</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Career Growth</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  @php
  $refid = DB::table('usertype')
      ->where('id', Session::get('currentPortal'))
      ->first()->refid;
  @endphp
  
      {{-- <div class="row">
        
        <div class="col-md-4">
          <div class="alert alert-primary" role="alert">
              <small>No. of Promoted Male Employees this year</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-danger" role="alert">
            <small>No. of Promoted Male Employees this year</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="alert alert-success" role="alert">
            <small>No. of Promoted Employees this year</small>
          </div>
        </div>
    </div> --}}
  <div class="card" style="border: none !important; box-shadow: 0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
    <div class="card-header">
      <div class="row">
        {{-- <div class="col-md-5 p-0">
          <h1 style="font-size: 80px;">{{date('Y')}}</h1>
        </div> --}}
        <div class="col-md-5">
          <label>Select Employee</label>
          <select class="select2 form-control form-control-sm" id="select-empid">
            @foreach($employees as $employee)
            <option value="{{$employee->id}}" >{{strtoupper($employee->lastname)}}, {{strtoupper($employee->firstname)}} {{strtoupper($employee->middlename)}}</option>
            @endforeach
          </select>
        </div>
        {{-- <div class="col-md-7">
            <div class="row">
              <div class="col-md-4">
                <label style="visibility: hidden;">Select Employee</label>
                <div class="alert alert-primary" role="alert">
                    <small>No. of Promoted Male Employees this year</small>
                </div>
              </div>
              <div class="col-md-4">
                <label style="visibility: hidden;">Select Employee</label>
                <div class="alert alert-danger" role="alert">
                </div>
              </div>
              <div class="col-md-4">
                <label style="visibility: hidden;">Select Employee</label>
                <div class="alert alert-success" role="alert">
                </div>
              </div>
            </div> 
        </div>--}}
        {{-- <div class="alert alert-warning" role="alert">
          This is a warning alert—check it out!
        </div>
        <div class="alert alert-info" role="alert">
          This is a info alert—check it out!
        </div> --}}
      </div>
    </div>
  </div>
  <div id="container-results">

  </div>
@endsection
@section('footerjavascript')
<script>
    
    $(document).ready(function(){
      $('.select2').select2()
      function getresults()
      {        
        Swal.fire({
            title: 'Loading...',
            allowOutsideClick: false,
            closeOnClickOutside: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            }
        })  
        var employeeid = $('#select-empid').val();
        $.ajax({
            url: '/hr/employees/cgrowth',
            type: 'GET',
            data:{
                action: 'getresults',
                employeeid: employeeid
            },
            success:function(data){
                $('#container-results').empty()
                $('#container-results').append(data)
                $(".swal2-container").remove();
                $('body').removeClass('swal2-shown')
                $('body').removeClass('swal2-height-auto')
                $('.select2').select2()
                
            }
        })
      }
      getresults()
      $('#select-empid').on('change', function(){
        getresults()
      })
      $(document).on('click','#btn-promote', function(){
        var currenttypeid = $(this).attr('data-currentusertypeid');
        var usertypeid = $('#select-usertype').val();
        var employeeid = $('#select-empid').val();
        Swal.fire({
            title: 'Saving changes...',
            allowOutsideClick: false,
            closeOnClickOutside: false,
            onBeforeOpen: () => {
                Swal.showLoading()
            }
        })  
        $.ajax({
            url: '/hr/employees/cgrowth',
            type: 'GET',
            data:{
                action: 'promote',
                currenttypeid: currenttypeid,
                usertypeid: usertypeid,
                employeeid: employeeid
            },
            success:function(data){
                $('#container-results').empty()
                $('#container-results').append(data)
                $(".swal2-container").remove();
                $('body').removeClass('swal2-shown')
                $('body').removeClass('swal2-height-auto')
                $('.select2').select2()
                
            }
        })
      })
        // $(".filter").on("keyup", function() {
        //     var input = $(this).val().toUpperCase();
        //     var visibleCards = 0;
        //     var hiddenCards = 0;

        //     $(".container").append($("<div class='card-group card-group-filter'></div>"));


        //     $(".eachemployee").each(function() {
        //         if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

        //         $(".card-group.card-group-filter:first-of-type").append($(this));
        //         $(this).hide();
        //         hiddenCards++;

        //         } else {

        //         $(".card-group.card-group-filter:last-of-type").prepend($(this));
        //         $(this).show();
        //         visibleCards++;

        //         if (((visibleCards % 4) == 0)) {
        //             $(".container").append($("<div class='card-group card-group-filter'></div>"));
        //         }
        //         }
        //     });
        // });
    })

</script>

@endsection
