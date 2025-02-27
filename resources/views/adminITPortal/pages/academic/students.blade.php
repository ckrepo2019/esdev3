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
$url = DB::table('schoollist')->where('id',Session::get('schoolid'))->first();
    $graphdetails1 = array();
  $graphdetails2 = array();
  if(count($schoolyears)>0)
  {
    foreach($schoolyears as $key => $sy)
    {
    try{
      $sy['key'] = $key+1;
      $enrollees = 0;

      $enroll1 = DB::table('enrolledstud')
        ->where('syid', $sy['id'])
        ->whereIn('studstatus',[1,2,4])
        ->where('deleted','0')
        ->count();

      $enrollees+=$enroll1;

      $enroll2 = DB::table('sh_enrolledstud')
        ->select('studid')
        ->where('syid', $sy['id'])
        ->whereIn('studstatus',[1,2,4])
        ->where('deleted','0')
        ->distinct('studid')
        ->count();

      $enrollees+=$enroll2;

      $sy['enrollees'] = $enrollees;
      array_push($graphdetails1,[$sy['key'],$sy['sydesc']]);
      array_push($graphdetails2,[$sy['key'],$enrollees]);
    }catch(\Throwable $error)
    {
      $sy->key = $key+1;
      $enrollees = 0;

      $enroll1 = DB::table('enrolledstud')
        ->where('syid', $sy->id)
        ->whereIn('studstatus',[1,2,4])
        ->where('deleted','0')
        ->count();

      $enrollees+=$enroll1;

      $enroll2 = DB::table('sh_enrolledstud')
        ->select('studid')
        ->where('syid', $sy->id)
        ->whereIn('studstatus',[1,2,4])
        ->where('deleted','0')
        ->distinct('studid')
        ->count();

      $enrollees+=$enroll2;

      $sy->enrollees = $enrollees;
      array_push($graphdetails1,[$sy->key,$sy->sydesc]);
      array_push($graphdetails2,[$sy->key,$enrollees]);
    }
    }
  }
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
              @foreach(collect($schoolyears) as $sy)
              
                                        <?php try { ?>
                <option value="{{$sy['id']}}" @if($sy['isactive'] == 1) selected @endif>{{$sy['sydesc']}}</option>
                
                                        <?php }catch(\Throwable $error) { ?>
                <option value="{{$sy->id}}" @if($sy->isactive == 1) selected @endif>{{$sy->sydesc}}</option>
                                        <?php }?>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label>Select Semester</label>
            <select class="form-control" id="select-semid">
              @foreach(collect($semesters) as $semester)
                                        <?php try { ?>
                <option value="{{$semester['id']}}" @if($semester['isactive'] == 1) selected @endif>{{$semester['semester']}}</option>
                
                                        <?php }catch(\Throwable $error) { ?>
                <option value="{{$semester->id}}" @if($semester->isactive == 1) selected @endif>{{$semester->semester}}</option>
                                        <?php }?>
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
    {{-- <div id="results-enrollment"></div> --}}
    <div id="results-statistics-sf5"></div>
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
      
      // function getenrolled()
      // {
      //   $.ajax({
      //       url: '{{$url->eslink}}/academic/students',
      //       type:'GET',
      //       data: {
      //           action        :  'getenrollmentresults',
      //           syid        :  $('#select-syid').val(),
      //           semid        :  $('#select-semid').val()
      //       },
      //       success:function(data) {
      //         $('#results-enrollment').empty()
      //         $('#results-enrollment').append(data)
                
      //       }
      //   })
      // }
      //   $.ajax({
      //       url: '{{$url->eslink}}/academic/index',
      //       type:'GET',
      //       data: {
      //           action        :  'getenrollmentresults',
      //           syid        :  $('#select-syid').val(),
      //           semid        :  $('#select-semid').val()
      //       },
      //       success:function(data) {
      //         $('#results-enrollment').empty()
      //         $('#results-enrollment').append(data)
      //         getenrolled()
                
      //       }
      //   })
      $('#results-statistics-sf5').hide()
      function getstats_sf5()
      {
        $.ajax({
            url: '{{$url->eslink}}/academic/students',
            type:'GET',
            data: {
                action        :  'getstatistics_sf5',
                syid        :  $('#select-syid').val(),
                levelid        :  $('#select-levelid').val(),
                semid        :  $('#select-semid').val()
            },
            success:function(data) {
              $('#results-statistics-sf5').empty()
              $('#results-statistics-sf5').append(data)
              $('#results-statistics-sf5').show()
                
            }
        })
      }
      getstats_sf5()
      function getstats_sf5withlevelid()
      {
        $.ajax({
            url: '{{$url->eslink}}/academic/students',
            type:'GET',
            data: {
                action        :  'getstatistics_sf5',
                syid        :  $('#select-syid').val(),
                levelid        :  $('#select-levelid').val(),
                semid        :  $('#select-semid').val()
            },
            success:function(data) {
              $('#results-statistics-sf5').empty()
              $('#results-statistics-sf5').append(data)
                
            }
        })
      }

      $('#select-syid').on('change', function(){
        getstats_sf5()
      })
      $('#select-semid').on('change', function(){
        getstats_sf5()
      })
      $(document).on('change','#select-levelid', function(){
        getstats_sf5withlevelid()
      })
    //   function getteachingloads()
    //   {
    //     $.ajax({
    //         url: '/academic/index',
    //         type:'GET',
    //         data: {
    //             action        :  'getteachingloadsresults',
    //             syid        :  $('#select-syid').val(),
    //             semid        :  $('#select-semid').val()
    //         },
    //         success:function(data) {
    //           $('#results-enrollment').empty()
    //           $('#results-enrollment').append(data)
                
    //         }
    //     })
    //   }
    //   getteachingloads()

      // function filterrecords(){
      //   Swal.fire({
      //       title: 'Fetching data...',
      //       onBeforeOpen: () => {
      //           Swal.showLoading()
      //       },
      //       allowOutsideClick: false
      //   })
      //   $.ajax({
      //       url: '/academic/index?action=filter',
      //       type:'GET',
      //       data: {
      //           syid        :  $('#select-syid').val(),
      //           semid        :  $('#select-semid').val()
      //       },
      //       success:function(data) {
      //           $('#filterresults').empty()
      //           $('#filterresults').append(data)

      //           $(".swal2-container").remove();
      //           $('body').removeClass('swal2-shown')
      //           $('body').removeClass('swal2-height-auto')

      //           gradecategory_basic()
      //           gradecategory_shs()
                
      //       }
      //   })
      // }
      // filterrecords();
      // $('#select-syid').on('change',function(){
      // filterrecords();
      // })
      // $('#select-semid').on('change',function(){
      // filterrecords();
      // })
      // $('#select-levelid').on('change',function(){
      // filterrecords();
      // })
      // function gradecategory_basic()
      // {
      //   var category = $('#select-basiced-category').val();
      //   $.ajax({
      //       url: '/academic/index?action=getcatrecords',
      //       type:'GET',
      //       dataType: 'json',
      //       data: {
      //           syid        :  $('#select-syid').val(),
      //           levelid        :  $('#select-basiced-levelid').val(),
      //           filtertype        :  'basiced',
      //           category        :  category
      //       },
      //       success:function(data) {
      //         if(data.length > 0)
      //         {
      //           if(category == 'B')
      //           {
      //             $('#container-catheader-basic').text(data[0].levelname+ ' - BEGINNING - (B: 74% and below)')
      //           }else if(category == 'D')
      //           {
      //             $('#container-catheader-basic').text(data[0].levelname+ ' - DEVELOPING - (D: 75%-79%)')
      //           }else if(category == 'AP')
      //           {
      //             $('#container-catheader-basic').text(data[0].levelname+ ' - APPROACHING PROFICIENCY - (AP: 80%-84%)')
      //           }else if(category == 'P')
      //           {
      //             $('#container-catheader-basic').text(data[0].levelname+ ' - PROFICIENT - (P: 85% -89%)')
      //           }else if(category == 'A')
      //           {
      //             $('#container-catheader-basic').text(data[0].levelname+ ' - ADVANCED - (A: 90% and above)')
      //           }
      //         }
      //         $('#container-catchart-basiced').empty()
      //         $('#container-catchart-basiced').append('<canvas id="catchartbasic" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>')
              
      //         var sections = [];
      //         var sectionmalecount = [];
      //         var sectionfemalecount = [];
      //         var sectiontotalcount = [];
      //         $.each(data, function(key, value){
      //           sections.push(value.sectionname)
      //           sectionmalecount.push(value.male)
      //           sectionfemalecount.push(value.female)
      //           sectiontotalcount.push(value.total)
      //         })
      //       var catchartdatabasic = {
      //           labels  : sections,
      //           datasets: [
      //             {
      //               label               : 'Female',
      //               backgroundColor     : 'rgba(60,141,188,0.9)',
      //               borderColor         : 'rgba(60,141,188,0.8)',
      //               pointRadius          : false,
      //               pointColor          : '#3b8bba',
      //               pointStrokeColor    : 'rgba(60,141,188,1)',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: 'rgba(60,141,188,1)',
      //               borderWidth: 1,
      //               data                : sectionfemalecount
      //             },
      //             {
      //               label               : 'Male',
      //               backgroundColor     : '#cce5ff',
      //               borderColor         : '#007bff',
      //               pointRadius         : false,
      //               pointColor          : 'rgba(210, 214, 222, 1)',
      //               pointStrokeColor    : '#cce5ff',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: '#cce5ff',
      //               borderWidth: 1,
      //               data                : sectionmalecount
      //             },
      //             {
      //               label               : 'Total',
      //               backgroundColor     : 'rgba(210, 214, 222, 1)',
      //               borderColor         : 'rgba(210, 214, 222, 1)',
      //               pointRadius         : false,
      //               pointColor          : 'rgba(210, 214, 222, 1)',
      //               pointStrokeColor    : '#c1c7d1',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: 'rgba(220,220,220,1)',
      //               borderWidth: 1,
      //               data                : sectiontotalcount
      //             },
      //           ]
      //         }

      //         //-------------
      //         //- BAR CHART -
      //         //-------------
      //         var catchartcanvasbasic = $('#catchartbasic').get(0).getContext('2d')
      //         var catchartsectionsbasic = $.extend(true, {}, catchartdatabasic)
      //         var temp0 = catchartdatabasic.datasets[0]
      //         var temp1 = catchartdatabasic.datasets[1]
      //         catchartsectionsbasic.datasets[0] = temp1
      //         catchartsectionsbasic.datasets[1] = temp0

      //         var catchartoptionsbasic = {
      //         responsive              : true,
      //         maintainAspectRatio     : false,
      //         datasetFill             : false
      //         }

      //         new Chart(catchartcanvasbasic, {
      //         type: 'bar',
      //         data: catchartdatabasic,
      //         options: catchartoptionsbasic
      //         })
      //         $(".swal2-container").remove();
      //         $('body').removeClass('swal2-shown')
      //         $('body').removeClass('swal2-height-auto')

                
      //       }
      //   })

      // }
      // $(document).on('change','#select-basiced-levelid', function(){
      //   Swal.fire({
      //       title: 'Fetching data...',
      //       onBeforeOpen: () => {
      //           Swal.showLoading()
      //       },
      //       allowOutsideClick: false
      //   })
      //   gradecategory_basic()
      // })
      // $(document).on('change','#select-basiced-category', function(){
      //   Swal.fire({
      //       title: 'Fetching data...',
      //       onBeforeOpen: () => {
      //           Swal.showLoading()
      //       },
      //       allowOutsideClick: false
      //   })
      //   gradecategory_basic()
      // })
      // function gradecategory_shs()
      // {
      //   $('#loading-shs').show()
      //   var category = $('#select-shs-category').val();
      //   $.ajax({
      //       url: '/academic/index?action=getcatrecords',
      //       type:'GET',
      //       dataType: 'json',
      //       data: {
      //           syid        :  $('#select-syid').val(),
      //           levelid        :  $('#select-shs-levelid').val(),
      //           filtertype        :  'shs',
      //           category        :  category
      //       },
      //       success:function(data) {

      //         $('#container-catchart-shs').empty()
      //         $('#container-catchart-shs').append('<canvas id="catchartshs" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>')
              
      //       $('#loading-shs').hide()
      //         var strands = [];
      //         var strandmalecount = [];
      //         var strandfemalecount = [];
      //         var strandtotalcount = [];
      //         $.each(data, function(key, value){
      //           strands.push(value.strandcode)
      //           strandmalecount.push(value.male)
      //           strandfemalecount.push(value.female)
      //           strandtotalcount.push(value.total)
      //         })
      //       var catchartdatashs = {
      //           labels  : strands,
      //           datasets: [
      //             {
      //               label               : 'Female',
      //               backgroundColor     : 'rgba(60,141,188,0.9)',
      //               borderColor         : 'rgba(60,141,188,0.8)',
      //               pointRadius          : false,
      //               pointColor          : '#3b8bba',
      //               pointStrokeColor    : 'rgba(60,141,188,1)',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: 'rgba(60,141,188,1)',
      //               borderWidth: 1,
      //               data                : strandfemalecount
      //             },
      //             {
      //               label               : 'Male',
      //               backgroundColor     : '#cce5ff',
      //               borderColor         : '#007bff',
      //               pointRadius         : false,
      //               pointColor          : 'rgba(210, 214, 222, 1)',
      //               pointStrokeColor    : '#cce5ff',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: '#cce5ff',
      //               borderWidth: 1,
      //               data                : strandmalecount
      //             },
      //             {
      //               label               : 'Total',
      //               backgroundColor     : 'rgba(210, 214, 222, 1)',
      //               borderColor         : 'rgba(210, 214, 222, 1)',
      //               pointRadius         : false,
      //               pointColor          : 'rgba(210, 214, 222, 1)',
      //               pointStrokeColor    : '#c1c7d1',
      //               pointHighlightFill  : '#fff',
      //               pointHighlightStroke: 'rgba(220,220,220,1)',
      //               borderWidth: 1,
      //               data                : strandtotalcount
      //             },
      //           ]
      //         }

      //         //-------------
      //         //- BAR CHART -
      //         //-------------
      //         var catchartcanvasshs = $('#catchartshs').get(0).getContext('2d')
      //         var catchartsectionsshs = $.extend(true, {}, catchartdatashs)
      //         var temp0 = catchartdatashs.datasets[0]
      //         var temp1 = catchartdatashs.datasets[1]
      //         catchartsectionsshs.datasets[0] = temp1
      //         catchartsectionsshs.datasets[1] = temp0

      //         var catchartoptionsshs = {
      //         responsive              : true,
      //         maintainAspectRatio     : false,
      //         datasetFill             : false
      //         }

      //         new Chart(catchartcanvasshs, {
      //         type: 'bar',
      //         data: catchartdatashs,
      //         options: catchartoptionsshs
      //         })
      //         $(".swal2-container").remove();
      //         $('body').removeClass('swal2-shown')
      //         $('body').removeClass('swal2-height-auto')

                
      //       }
      //   })

      // }
      // $(document).on('change','#select-shs-levelid', function(){
      //   Swal.fire({
      //       title: 'Fetching data...',
      //       onBeforeOpen: () => {
      //           Swal.showLoading()
      //       },
      //       allowOutsideClick: false
      //   })
      //   gradecategory_shs()
      // })
      // $(document).on('change','#select-shs-category', function(){
      //   Swal.fire({
      //       title: 'Fetching data...',
      //       onBeforeOpen: () => {
      //           Swal.showLoading()
      //       },
      //       allowOutsideClick: false
      //   })
      //   gradecategory_shs()
      // })
      // $(document).on('click','#btn-view-noofenrollees', function(){
        
      //   var syid        =  $('#select-syid').val()
      //   window.open("/academic/index?syid="+$('#select-syid').val()+"&action=export&exporttype=numberofenrollees",'_blank');
      // })
      // $(document).on('click','#btn-view-basiced-catstudents', function(){
        
      //   var syid        =  $('#select-syid').val();
      //   var semid        =  $('#select-semid').val();
      //   var levelid     =  $('#select-basiced-levelid').val();
      //   var category = $('#select-basiced-category').val();
      //   window.open("/academic/index?syid="+syid+"&semid="+semid+"&levelid="+levelid+"&action=getcatrecords&export=1&filtertype=basiced&category="+category,'_blank');
      // })
      // $(document).on('click','#btn-view-shs-catstudents', function(){
        
      //   var syid        =  $('#select-syid').val();
      //   var semid        =  $('#select-semid').val();
      //   var levelid     =  $('#select-shs-levelid').val();
      //   var category = $('#select-shs-category').val();
      //   window.open("/academic/index?syid="+syid+"&semid="+semid+"&levelid="+levelid+"&action=getcatrecords&export=1&filtertype=shs&category="+category,'_blank');
      // })
    })

  </script>

@endsection