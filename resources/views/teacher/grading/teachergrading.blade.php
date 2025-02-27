@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">

<style>
       
        .table                      {width:1500px; font-size:90%; text-transform: uppercase; }

        /* .table thead th:first-child { position: sticky; left: 0; background-color: #fff; } */
        .table thead th:last-child  { 
            position: sticky; 
            right: 0; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
        }

        .table tbody th:last-child  { 
            position: sticky; 
            right: 0; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
            }

        .table tbody th:first-child  {  
            position: sticky; 
            left: 0; 
            background-color: #fff; 
            width: 150px !important;
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
        }

        .table thead th:first-child  { 
                position: sticky; left: 0; 
                width: 150px !important;
                background-color: #fff; 
                outline: 2px solid #dee2e6;
                outline-offset: -1px;
        }

  
       
        td{
            text-align: center;
            cursor: pointer;
            vertical-align: middle !important;
        }
        .toast-top-right {
            top: 20%;
            margin-right: 21px;
        }

        .tableFixHead {
            overflow: auto;
            height: 100px;
        }

        .tableFixHead thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
           
        }

        .isHPS {

            position: sticky;
            top: 55px !important;
            background-color: #fff;
            outline: 2px solid #dee2e6 ;
            outline-offset: -1px;
           
        }
       
    </style>

    <style>
        .select2-container .select2-selection--single {
           
            height: 40px !important;
            
        }
        .loader {
            margin: auto;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            border-bottom: 16px solid blue;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
            border: 0 !important;
        }
    </style>

    <div class="app-page-title">
        <div class="page-title-wrapper">
            <div class="page-title-heading ">
                <div>
                    <input id="syid" name="syid" value="{{$schoolyearid}}" hidden>
                    <input id="gradelevelid" name="gradelevelid" value="{{$gradeLevelid}}" hidden>
                    <input id="sectionid" name="sectionid" value="{{$sectionid}}" hidden>
                    <input id="subjectid" name="subjectid" value="{{$subjectid}}" hidden>
                    <div class="page-title-subheading">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <nav class="" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/grades/index">Sections</a></li>
                <li class="breadcrumb-item"><a href="/grades/getsubjects?selectedschoolyear={{$schoolyearid}}&selectedsemester={{$activeSem}}&selectedlevelid={{$gradeLevelid}}&selectedsectionid={{$sectionid}}">Subjects</a></li>
                <li class="active breadcrumb-item" aria-current="page">Grades</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6">
            <div class="btn-actions-pane-lefy">
                <div role="group" class="btn-group-sm btn-group float-right">
                    {{-- 11132021 - grades --}}
                    @if($gradeLevelid == 14 || $gradeLevelid == 15)
                        @if($activeSem == 1)
                            <button name="quarter" value="1" class="btn btn-success">1st Quarter <br> <span class="badge data_stat" value="1">{{collect($grade_status)->where('quarter',1)->first()->status}}</span></button>
                            <button name="quarter" value="2" class="btn btn-success">2nd Quarter  <br> <span class="badge data_stat" value="2">{{collect($grade_status)->where('quarter',2)->first()->status}}</span></button>
                        @elseif($activeSem == 2)
                            <button name="quarter" value="3" class="btn btn-success">3rd Quarter  <br> <span class="badge data_stat" value="3">{{collect($grade_status)->where('quarter',3)->first()->status}}</span></button>
                            <button name="quarter" value="4" class="btn btn-success">4th Quarter  <br> <span class="badge data_stat" value="4">{{collect($grade_status)->where('quarter',4)->first()->status}}</span></button>
                        @endif
                    @else
                        @if(count($gradessetup) == 0)
                            <button name="quarter" value="1" class="btn btn-success" id="clickme">1st Quarter  <br> <span class="badge data_stat" value="1">{{collect($grade_status)->where('quarter',1)->first()->status}}</span></button>
                            <button name="quarter" value="2" class="btn btn-success">2nd Quarter  <br> <span class="badge data_stat" value="2">{{collect($grade_status)->where('quarter',2)->first()->status}}</span></button>
                            <button name="quarter" value="3" class="btn btn-success">3rd Quarter  <br> <span class="badge data_stat" value="3">{{collect($grade_status)->where('quarter',3)->first()->status}}</span></button>
                            <button name="quarter" value="4" class="btn btn-success">4th Quarter  <br> <span class="badge data_stat" value="4">{{collect($grade_status)->where('quarter',4)->first()->status}}</span></button>
                        @else
                            @if($gradessetup[0]->first == 1)
                                <button name="quarter" value="1" class="btn btn-success" id="clickme">1st Quarter  <br> <span class="badge data_stat" value="1">{{collect($grade_status)->where('quarter',1)->first()->status}}</span></button>
                            @endif
                            @if($gradessetup[0]->second == 1)
                                <button name="quarter" value="2" class="btn btn-success">2nd Quarter  <br> <span class="badge data_stat" value="2">{{collect($grade_status)->where('quarter',2)->first()->status}}</span></button>
                            @endif
                            @if($gradessetup[0]->third == 1)
                                <button name="quarter" value="3" class="btn btn-success">3rd Quarter  <br> <span class="badge data_stat" value="3">{{collect($grade_status)->where('quarter',3)->first()->status}}</span></button>
                            @endif
                            @if($gradessetup[0]->fourth == 1)
                                <button name="quarter" value="4" class="btn btn-success">4th Quarter  <br> <span class="badge data_stat" value="4">{{collect($grade_status)->where('quarter',4)->first()->status}}</span></button>
                            @endif
                        @endif
                    @endif
                     {{-- 11132021 - grades --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row"  style=" font-size:11px !important">
                        <div class="col-md-3">
                            <strong><i class="fas fa-book mr-1"></i> Section / Grade Level</strong>
                            <p class="text-muted mb-0" id="label_gradelevel">
                                    {{$moreinfo[0]->sectionname}}
                            </p>
                            <p class="text-danger mb-0" >
                                <i id="label_section">  {{$moreinfo[0]->levelname}} </i>
                            </p>
                        </div>
                        <div class="col-md-5">
                                <strong><i class="fas fa-book mr-1"></i> Subject</strong>
                                <p class="text-muted mb-0" id="label_subject">
                                    {{$moreinfo[0]->subjdesc}}
                                </p>
                                <p class="text-danger mb-0" >
                                    <i id="label_subjectcode">  {{$moreinfo[0]->subjcode}} </i>
                                </p>
                        </div>
                        <div class="col-md-4">
                            <strong><i class="fas fa-book mr-1"></i> Grade Status</strong>
                            <p class="text-muted mb-0" id="label_status">
                                --
                            </p>
                            <p class="text-danger mb-0" >
                                <i id="label_datesubmitted"> -- </i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($gradeLevelid == 14 || $gradeLevelid == 15)
        <div class="row mb-2">
            <div class="col-md-4">
                <label for="">Strand</label>
                <select name="" id="filter_strand"  class="form-control form-control-sm">
                    @foreach ($subjstrand as $item)
                        <option value="{{$item->strandid}}">{{$item->strandcode}}</option>
                    @endforeach
                </select>
                <p class="text-danger mb-0" style="font-size:.8rem !important"><i>The strands listed are from the subject plot.</i></p>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="main-card mb-3 card shadow">
                <div class="card-body">
                    <div class="ribbon-wrapper ribbon-lg" id="gradeRibbon" style="z-index: 101" hidden>
                            <div class="ribbon bg-danger" id="gradeRibbonMessage" >
                            </div>
                    </div>
                    <div id="filterPanel">
                        <div id="tableContainer"">

                        </div>
                      
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12" id="option_holder" hidden>
            <div class="card shadow">
                <div class="card-body p-3" >
                    <div class="row">
                        <div class="col-md-6">
                            <button class="btn btn-success btn-sm" id="updateGrade" disabled> UPDATE GRADES</button>
                            <p class="mb-0" id="failed_update"></p>
                        </div>
                        <div class="col-md-6 text-right ">
                                <button class="btn btn-success  btn-sm" id="btnSubmit" disabled> SUBMIT GRADES</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  
    <script>
        $(document).ready(function(){

            var temp_quarter = @json($quarter);

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            var curQuarter = 0;
            var selectedQuarter = 0;

            if(temp_quarter != null){
                curQuarter  = temp_quarter
                selectedQuarter =  $('button[name=quarter][value='+curQuarter+']');
                loadGrades()
            }

            $(document).on('click','.exportrecord',function(){
           
                var sectionid= $('#sectionid').val();
                var subjectid= $('#subjectid').val();
                var exporttype= $(this).attr('exporttype');
                var syid= $('#syid').val();
                var semid = @json($activeSem)
                
                quarter = curQuarter

                if(quarter == 0){

                    Swal.fire({
                        title: 'Please select a quarter?',
                        type: 'info',
                    })

                }
                else{
                    
                    window.open('/classrecord/pdf/'+sectionid+'/'+subjectid+'/'+quarter+'?exporttype='+exporttype+'&syid='+syid+'&semid='+semid, '_blank');
                    
                }

            })

            var selectedQuarter
            var isquarter = true
            var can_edit

            $('button[name=quarter]').on('click', function() {
                selectedQuarter = $(this)
                curQuarter = $(this).val()
                $('button[name=quarter]').each(function(){
                    $(this).css('background-color','#28a745')
                })
                selectedQuarter.css('background-color','#1e7e34')
                $('#btnSubmit').attr('disabled','disabled')
                $('#updateGrade').attr('disabled','disabled')
                $('.exportrecord').attr('disabled','disabled')
                $('#gradeRibbon').attr('hidden','hidden');
                $('#gradeRibbonMessage').text();
                $('#tableContainer').empty();

                
                loadGrades()
            
            })

            $(document).on('change','#filter_strand', function() {
                loadGrades()
            })



            function loadGrades() {

                var syid= $('#syid').val();
                var gradelevelid= $('#gradelevelid').val();
                var sectionid= $('#sectionid').val();
                var subjectid= $('#subjectid').val();
                var quarter = curQuarter;
                var strandid = $('#filter_strand').val();

                var semid = 1
                if(gradelevelid == 14 || gradelevelid == 15){
                    semid = '{{$activeSem}}'
                }

                $.ajax({
                        url: '/getgrades/'+subjectid,
                        type:"GET",
                        data:{
                            strandid:strandid,
                            syid: syid,
                            gradelevelid:gradelevelid,
                            sectionid: sectionid,
                            subjectid :subjectid,
                            quarter :quarter,
                            semid:semid
                        },
                        success:function(data) {
                            $('#tableContainer').empty();
                            if(data == 'NYP'){
                                Toast.fire({
                                        type: 'warning',
                                        title: 'Quarter '+( parseInt(curQuarter)-1 )+' grades are not yet approved.'
                                })
                            }
                            else if(data == 'NGS'){
                                Toast.fire({
                                        type: 'warning',
                                        title: 'Grade setup is not configured.'
                                })
                            }
                            else if(data == 'NSE'){
                                Toast.fire({
                                        type: 'warning',
                                        title: 'No student enrolled for this subject.'
                                })
                            }
                            else{

                                $('#updateGrade').removeAttr('disabled')
                                $('.exportrecord').removeAttr('disabled')
                              
                                $('#btnSubmit').removeAttr('disabled')
                                $('#tableContainer').append(data)
                                
                               
                            }
                        }
                })
            }

            $(document).on('click','#btnSubmit', function() {
                Swal.fire({
                title: 'Are you sure you want to submit final grades?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Submit'
                })
                .then((result) => {
                    if (result.value) {

                        $('#btnSubmit').attr('disabled','disabled');

                                var sy = $('#syid').val();
                                var gradeLevel = $('#gradelevelid').val();
                                var section = $('#sectionid').val();
                                var quarters = curQuarter
                                var subjects = $('#subjectid').val();

                                var semid = 1
                                if(gradeLevel == 14 || gradeLevel == 15){
                                    semid = '{{$activeSem}}'
                                }


                                var excluded = []

                                $('.exclude').each(function(){
                                        if($(this).prop('checked') == false){
                                            excluded.push($(this).attr('data-studid'))
                                        }
                                })
                        
                                $.ajax({
                                    url: '/gradesSubmit/'+quarters,
                                    type:"GET",
                                    data:{
                                            syid: sy,
                                            gradelevelid: gradeLevel,
                                            section: section,
                                            quarter : quarters,
                                            subjectid: subjects,
                                            dataHolder: 'submit',
                                            semid:semid,
                                            excluded:excluded

                                    },
                                    success:function(data) {
                                        
                                        $('#updateGrade').attr('disabled','disabled');
                                        $('#btnSubmit').attr('disabled','disabled');
                                        $('th[contenteditable="true"]').attr('contenteditable','false')
                                        $('td[contenteditable="true"]').attr('contenteditable','false')
                                        $('#start').removeAttr('style')
                                        $('#start').removeAttr('id')
                                        
                                        update_sidenav()

                                        loadGrades()

                                        Toast.fire({
                                            type: 'success',
                                            title: 'Grades submitted successfully!'
                                        })
                                    }
                                });

                        }

                    });
                   
                })

                function update_sidenav(){
                    $.ajax({
                        url: '/teacher/get/pending',
                        type:"GET",
                        success:function(data) {
                                if(data[0].with_pending){
                                    $('.pending_status_holder').removeAttr('hidden')
                                    if(data[0].student_pending_count > 0 ){
                                            $('.student_pending').removeAttr('hidden')
                                            $('.student_pending').text(data[0].student_pending_count)
                                    }else{
                                            $('#student_pending').attr('hidden','hidden')
                                    }
                                    if(data[0].section_pending_count > 0 ){
                                            $('.section_pending').removeAttr('hidden')
                                            $('.section_pending').text(data[0].section_pending_count)
                                    }else{
                                            $('#section_pending').attr('hidden','hidden')
                                    }
                                }else{
                                    $('.student_pending').attr('hidden','hidden')
                                    $('.section_pending').attr('hidden','hidden')
                                    $('.pending_status_holder').attr('hidden','hidden')
                                }
                        }
                    });
                }
            
            })

    </script>

    <script>
        $(document).ready(function(){

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

            $('button[name=quarter]').on('click', function() {
                curQuarter = $(this).val()
            })

            var hps_check;

            function get_grade_header(){
                
                var selectedQuarter = $(this)
                var syid= $('#syid').val();
                var gradelevelid= $('#gradelevelid').val();
                var sectionid= $('#sectionid').val();
                var subjectid= $('#subjectid').val();
                var quarter = curQuarter;
                var semid = 1
                if(gradelevelid == 14 || gradelevelid == 15){
                    semid = '{{$activeSem}}'
                }

                $.ajax({
                    url: '/get/grade/header',
                    type:"GET",
                    data:{
                            syid: syid,
                            gradelevelid:gradelevelid,
                            sectionid: sectionid,
                            subjectid :subjectid,
                            quarter :quarter,
                            semid:semid
                    },
                    success:function(data) {
                        hps_check = data
                        updateAllGradesDetail()
                    }
                })
            }

            function updateAllGradesDetail(){

                  
                  $('#failed_update').empty()
                  $('#failed_update').attr('hidden','hidden')

                  var inputedData = [];
                  var inputedDataHPS = [];
                  var p_length = 0;
                  var edited_count = 0;
                  failed_count = 0;

                  $('.gradedetail').each(function(a,b){
                        if($('.edited[data-id="'+$(b).attr('data-value')+'"]').length > 0 ){
                              edited_count += 1
                        }
                  })

                  if(edited_count == 0){
                        Toast.fire({
                              type: 'info',
                              title: 'No available update!'
                        })
                        return false
                  }


                  $('.hps').each(function(a,b){
                        if($('.edited[data-id="hps"]').length > 0 ){
                              var student = [];
                              $('.edited[data-id="hps"]').each(function(c,d){
                                    var field = $(d).attr('data-field')
                                    var grade = $(d).text()
                                    var id = hps_check[0].id

                                    var data = {
                                          field:field,
                                          grade:grade,
                                          id:id,
                                          syid:hps_check[0].syid,
                                          subjid:hps_check[0].subjid,
                                          sectionid:hps_check[0].sectionid,
                                    }
                                    student.push(data)
                              })
                              var url = '/gradesheader/update'
                              var student = {
                                    'data':student
                              }

                              $.ajax({
                                    type:'GET',
                                    url:url,
                                    data:student,
                                    success:function(data){
                                          if(data[0].status == 1){
                                                $('.edited[data-id="hps"]').removeAttr('edited')
                                          }
                                    },
                                    error:function(){
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              })
                        }
                        
                  })

                  $('.gradedetail').each(function(a,b){
                        var student = [];
                        if($('.edited[data-id="'+$(b).attr('data-value')+'"]').length > 0 ){
                              $('.edited[data-id="'+$(b).attr('data-value')+'"]').each(function(c,d){
                                    var temp_cell = b
                                    var field = $(d).attr('data-field')
                                    var studid = $(d).attr('data-studid')
                                    var grade = $(d).text()
                                    var id = $(d).hasClass('isHPS') ? hps_check[0].id : $(d).attr('data-id')
                                    var data = {
                                          field:field,
                                          studid:studid,
                                          grade:grade,
                                          id:id
                                    }
                                    student.push(data)
                              })
                              var url = '/gradesdetail/update'
                              var student = {
                                    'data':student
                              }
                              $.ajax({
                                    type:'GET',
                                    url:url,
                                    data:student,
                                    success:function(data){
                                          if(data[0].status == 1){
                                                p_length += 1
                                                $('.edited[data-id="'+$(b).attr('data-value')+'"]').removeClass('edited')
                                                if(p_length == edited_count){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Updated Successfully!'
                                                      })
                                                      $('#updateGrade').removeClass('btn-danger')
                                                      $('#updateGrade').addClass('btn-success')
                                                }
                                          }else{
                                                failed_count += 1;
                                                display_failed()
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    },
                                    error:function(){
                                          failed_count += 1;
                                          display_failed()
                                          Toast.fire({
                                                type: 'error',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              })
                        }
                  })

            }

            function display_failed(){
                  $('#failed_update').empty()
                  $('#failed_update').removeAttr('hidden')
                  $('#failed_update').text('Failed Updates: ' +failed_count)
            }

            function checkHPS(){
                var is_hps = $('td[current="current"]').hasClass('isHPS')
                validHPS = true;
                if(is_hps){
                  try {
                        var highest = 0;
                        $('.gradedetail').each(function(){
                              if( $(this)[0].cells[$('td[current="current"]')[0].cellIndex].innerText > highest ){
                                    highest = $(this)[0].cells[$('td[current="current"]')[0].cellIndex].innerText; 
                              }
                        })
                        if( parseInt($('td[current="current"]')[0].innerText) < highest){
                              validHPS  = false
                        }
                        if(!validHPS){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Highest Possible Score is lower than student highest score!'
                              })
                        }
                        return validHPS;
                  }
                  catch(err) {
                        validHPS = true;
                        return validHPS;
                  }
                }else{
                    return validHPS
                }
            }

            $(document).on('click','#updateGrade',function(){
                var valid = checkHPS()
                if(valid){
                    get_grade_header()
                    $('#btnSubmit').removeAttr('disabled')
                }
            })

        })
    </script>

    <script>
        $(document).ready(function(){
            var keysPressed = {};
            const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
            })
            document.addEventListener('keydown', (event) => {
                    keysPressed[event.key] = true;
                    if (keysPressed['p'] && event.key == 'v') {
                        Toast.fire({
                                    type: 'warning',
                                    title: 'Date Version: 08/16/2021 16:24'
                                })
                    }
            });
            document.addEventListener('keyup', (event) => {
                    delete keysPressed[event.key];
            });
        })
    </script>

@endsection

@section('footerjavascript')

    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.select2').select2()
        })
    </script>
    
    
    

@endsection