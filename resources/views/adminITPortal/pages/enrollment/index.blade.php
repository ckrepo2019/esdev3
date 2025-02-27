@extends('adminITPortal.layouts.app')


@section('pagespecificscripts')

@endsection

<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@section('content')
<style>
    .shadow{
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
}
.shadow-lg{
    box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
}
.card{
    border: none !important;
}
</style>
@php

  //   $graphdetails1 = array();
  // $graphdetails2 = array();
  // if(count($schoolyears)>0)
  // {
  //   foreach($schoolyears as $key => $sy)
  //   {
  //     $sy->key = $key+1;
  //     $enrollees = 0;

  //     $enroll1 = DB::table('enrolledstud')
  //       ->where('syid', $sy->id)
  //       ->whereIn('studstatus',[1,2,4])
  //       ->where('deleted','0')
  //       ->count();

  //     $enrollees+=$enroll1;

  //     $enroll2 = DB::table('sh_enrolledstud')
  //       ->select('studid')
  //       ->where('syid', $sy->id)
  //       ->whereIn('studstatus',[1,2,4])
  //       ->where('deleted','0')
  //       ->distinct('studid')
  //       ->count();

  //     $enrollees+=$enroll2;

  //     $sy->enrollees = $enrollees;
  //     array_push($graphdetails1,[$sy->key,$sy->sydesc]);
  //     array_push($graphdetails2,[$sy->key,$enrollees]);
  //   }
  // }
@endphp
  <section class="content-header">
    <div class="container-fluid">
          <div class="row">
                <div class="col-sm-6">
                      <h1 class="m-0 text-dark">{{Session::get('schoolinfo')->schoolname}}</h1>
                </div>
                <div class="col-sm-6">
                </div>
          </div>
    </div>
  </section>
  <section class="content pt-0">

    
    <div class="card shadow">
      <div class="card-header">
        <div class="row">
          <div class="col-md-4">
            <label>Select School Year</label>
            <select class="form-control" id="select-syid">
              @foreach($schoolyears as $sy)
                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label>Select Semester</label>
            <select class="form-control" id="select-semid">
              @foreach($semesters as $semester)
                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
              @endforeach
            </select>
          </div>
          {{-- <div class="col-md-4">
            <label>Select Grade Level</label>
            <select class="form-control" id="select-levelid">
              @foreach($gradelevels as $gradelevel)
                <option value="{{$gradelevel->id}}">{{$gradelevel->levelname}}</option>
              @endforeach
            </select>
          </div> --}}
        </div>
      </div>
      {{-- <div class="card-body"></div> --}}
    </div>
    <div id="results-enrollment"></div>
    <div id="results-teachingloads"></div>
  </section>
@endsection


@section('footerjavascript')


<script src="{{asset('plugins/flot/jquery.flot.js')}}"></script>

<script src="{{asset('plugins/flot/jquery.flot.resize.js')}}"></script>

<script src="{{asset('plugins/flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
{{-- <script src="{{asset('plugins/jszip/jszip.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{asset('plugins/pdfmake/vfs_fonts.js')}}"></script> --}}
<script src="{{asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
  <script>
    $(document).ready(function(){
      
      $('#results-enrollment').hide()
      function getenrolled()
      {
        $.ajax({
            url: '{{Session::get('schoolinfo')->eslink}}/academic/index',
            type:'GET',
            data: {
                action        :  'getenrollmentresults',
                syid        :  $('#select-syid').val(),
                semid        :  $('#select-semid').val()
            },
            success:function(data) {
              $('#results-enrollment').empty()
              $('#results-enrollment').append(data)
              $('#results-enrollment').show()
                
            }
        })
      }
          getenrolled()
        $('#select-syid').on('change', function(){
          getenrolled()
        })
        $('#select-semid').on('change', function(){
          getenrolled()
        })
    })

  </script>

@endsection