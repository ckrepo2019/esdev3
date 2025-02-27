
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
            .tableFixHead thead th {
                  position: sticky;
                  top: 0;
                  background-color: #fff;
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            
            }
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }

            .grade_td{
                  cursor: pointer;
                  vertical-align: middle !important;
            }
      </style>
@endsection

@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc','desc')->get(); 
      $semester = DB::table('semester')->get(); 
      $schoolinfo = DB::table('schoolinfo')->first()->abbreviation;
      $dean = DB::table('college_colleges')
                  ->join('teacher',function($join){
                        $join->on('college_colleges.dean','=','teacher.id');
                        $join->where('teacher.deleted',0);
                  })
                  ->where('college_colleges.deleted',0)
                  ->select(
                        'teacher.id',
                        DB::raw("CONCAT(teacher.lastname,', ',teacher.firstname) as text")
                  )
                  ->distinct()
                  ->get();

      $gradesetup = DB::table('semester_setup')
                        ->where('deleted',0)
                        ->first();

@endphp

<div class="modal fade" id="modal_1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Student List</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
                        <table class="table table-sm" id="datatable_2"  width="100%">
                              <thead>
                                    <tr>
                                          <th width="60%">Student</th>
                                          <th width="15%">Grade Level</th>
                                          <th width="15%">Course</th>
                                          <th width="10%">Gender</th>
                                    </tr>
                              </thead>
                        </table>
                  </div>
            </div>
      </div>
</div>   

 


<div class="modal fade" id="modal_2" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-xl">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grades</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row" id="grades_setup_holder">
                              <div class="col-md-3" >
                                    <label for="" class="mb-0">Term</label>
                                    <div id="setup_term_holder"></div>
                              </div>
                              <div class="col-md-4">
                                    <label for=""  class="mb-0">Final Grade Computation</label>
                                    <div id="setup_fgc_holder"></div>
                              </div>
                              <div class="col-md-3" >
                                    <label for="" class="mb-0">Grading Scale</label>
                                    <div id="setup_gs_holder"></div>
                              </div>
                              <div class="col-md-2" >
                                    <label for="" class="mb-0">Decimal Places</label>
                                    <div id="setup_dp_holder"></div>
                              </div>
                        </div>
                        {{-- <div class="row mt-2" id="input_period_holder">
                        </div> --}}
                        <div class="row">
                              <div class="col-md-8">
                                    <p  class="mb-2"><i>Note: Press  <b class="text-danger">I</b> mark to student as Incomplete. Press <b class="text-danger">D</b> to mark student as Dropped.</i></p>
                              </div>
                              <div class="col-md-4 text-right">
                                    <button class="btn btn-primary btn-sm" id="print_grades_to_modal" style="font-size:.7rem !important">Print</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm table-striped mb-0"  style="font-size:.9rem">
                                          @if(strtoupper($schoolinfo) == 'SPCT' || strtoupper($schoolinfo) == 'GBBC')
                                                <tr>
                                                      <th id="subject" width="70%"></th>
                                                      <th id="section" width="30%" hidden></th>
                                                </tr>
                                          @else
                                                <tr>
                                                      <th id="section" width="30%"></th>
                                                      <th id="subject" width="70%"></th>
                                                </tr>
                                          @endif
                                    </table>
                              </div>
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 420px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed table-hover"  style="font-size:.8rem" width="100%">
                                          <thead>
                                                <tr id="deadline_holder">

                                                </tr>
                                                <tr>
                                                      <th width="3%" class="text-center">#</th>
                                                      <th width="30%">Student</th>
                                                      <th width="17%">Course</th>
                                                      <th width="8%" class="text-center term_holder" data-term="1">Prelim</th>
                                                      <th width="8%" class="text-center term_holder" data-term="2">Midterm</th>
                                                      <th width="8%" class="text-center term_holder" data-term="3">PreFi</th>
                                                      <th width="8%" class="text-center term_holder" data-term="4">Final Term</th>
                                                      <th width="8%" class="text-center term_holder" data-term="5">Final Grade</th>
                                                      <th width="10%" class="text-center term_holder" data-term="6">Remarks</th>
                                                </tr>
                                          </thead>
                                          <tbody id="student_list_grades">
            
                                          </tbody>
                                    </table>
                              </div>

                              <div class="col-md-12 mt-2">
                                    <button id="save_grades" class="btn btn-info btn-sm" disabled hidden>Save Grades</button>
                                    <button id="grade_submit" class="btn btn-primary btn-sm">Submit Grades</button>
                              </div>
                        </div>
                        <div class="row">
                        </div>
                  </div>
                  <div class="modal-footer pt-1 pb-1"  style="font-size:.7rem">
                        <i id="message_holder"></i>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="modal_3" style="display: none;" aria-hidden="true">
      <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title">Grade Submission</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body " style="font-size:.9rem">
                        <div class="row">
                              <div class="col-md-6 form-group mb-0">
                                    <select name="quarter_select" id="quarter_select" class="form-control form-control-sm">
                                          <option value="">Select Term</option>
                                          <option value="1" >Prelim</option>
                                          <option value="2" >Midterm</option>
                                          <option value="3" >PreFinal</option>
                                          <option value="4" >Final</option>
                                    </select>
                                    <small class="text-danger"><i>Select a term to view and submit grades.</i></small>
                              </div>
                              <div class="col-md-6">
                                    <button class="btn btn-primary float-right btn-sm" id="submit_selected_grade">Submit</button>
                              </div>
                        </div>
                        <div class="row">
                              <div class="col-md-12 table-responsive tableFixHead" style="height: 422px;">
                                    <table class="table table-sm table-striped table-bordered mb-0 table-head-fixed"  style="font-size:.9rem" width="100%">
                                          <thead>
                                                <tr>
                                                      <th width="5%"><input type="checkbox" disabled checked="checked" class="select_all"> </th>
                                                      <th width="20%">SID</th>
                                                      <th width="60%">Student</th>
                                                      <th width="15%" class="text-centerv">Grade</th>
                                                </tr>
                                          </thead>
                                          <tbody id="datatable_4">

                                          </tbody>
                                    </table>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="dean_holder_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body" style="font-size:.9rem">
                       <div class="row">
                              <div class="col-md-12 form-group">
                                    <label for="">Dean</label>
                                    <select class="form-control select2" id="printable_dean">

                                    </select>
                              </div>
                       </div>
                       <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="print_grades">Print</button>
                              </div>
                       </div>
                  </div>
            </div>
      </div>
</div>  

<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Student Grades</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Student Grades</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-6">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="info-box shadow-lg">
                                          <div class="info-box-content">
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            <label for="">School Year</label>
                                                            <select class="form-control form-control-sm select2" id="filter_sy">
                                                                  @foreach ($sy as $item)
                                                                        @if($item->isactive == 1)
                                                                              <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                                        @else
                                                                              <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                                        @endif
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                      <div class="col-md-4" >
                                                            <label for="">Semester</label>
                                                            <select class="form-control form-control-sm  select2" id="filter_semester">
                                                                  <option value="">Select semester</option>
                                                                  @foreach ($semester as $item)
                                                                        <option {{$item->isactive == 1 ? 'selected' : ''}} value="{{$item->id}}">{{$item->semester}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                </div>
                                                {{-- <div class="row">
                                                      <div class="col-md-4">
                                                            <button class="btn btn-primary btn-block btn-sm" id="filter_button_1"><i class="fas fa-filter"></i> Filter</button>
                                                      </div>
                                                </div> --}}
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped" id="datatable_1" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="12%">Section</th>
                                                                  <th width="43%">Subject</th>
                                                                  <th width="40%" class="text-center"></th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>      
                  </div>
            </div>
            {{-- <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-header">
                                    <h3 class="card-title">Grade Status</h3>
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem">
                                                <table class="table table-sm table-striped" id="datatable_3" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Section</th>
                                                                  <th width="35%">Subject</th>
                                                                  <th width="10%" class="text-center">Prelim</th>
                                                                  <th width="10%" class="text-center" {{strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : ''}}>Midterm</th>
                                                                  <th width="10%" class="text-center"  {{strtoupper($schoolinfo) == 'SPCT' ? 'hidden' : ''}}>PreFinal</th>
                                                                  <th width="10%" class="text-center">Final</th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>      
                  </div>
            </div> --}}
      </div>
  </section>
  
     
@endsection

@section('footerscript')

      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/moment/moment.min.js') }}"></script>
      {{-- <script>
            $(document).ready(function () {

                  $(document).on('click','.submit_grades',function(){
                  
                  })
            })
      </script> --}}

      <script>
            $('#filter_sy').select2()
            $('#filter_semester').select2()
      </script>

      <script>
            $(document).ready(function () {

                  var school = @json($schoolinfo);

                  

                  var isSaved = false;
                  var isvalidHPS = true;
                  var hps = []
                  var currentIndex 
                  var can_edit = true
                  
                  $(document).on('click','.input_grades',function(){

                        var term = $(this).attr('data-term')
                        var checkDateSetup = []

                        if(term == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prelim')
                        }else if(term == 2){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'midterm')
                        }else if(term == 3){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prefi')
                        }else if(term == 4){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'finalterm')
                        }
                        
                        if(checkDateSetup.length > 0){
                              if(!checkDateSetup[0].isended){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Deadline Ended.'
                                    })
                                    return false
                              }
                        }

                        // if(inputperiod.length == 0){
                        //       Toast.fire({
                        //             type: 'warning',
                        //             title: 'Grade input is not yet open.'
                        //       })
                        //       return false
                        // }

                        if(school == 'spct'.toUpperCase() && $(this).attr('data-term') == 'finalgrade'){
                              return false;
                        }

                        if(currentIndex != undefined){
                              if(isvalidHPS){
                                    if(can_edit){
                                          string = $(this).text();
                                          currentIndex = this;
                                          $('#start').length > 0 ? dotheneedful(this) : false
                                          $('td').removeAttr('style');
                                          $('#start').removeAttr('id')
                                          $(this).attr('id','start')
                                          $(currentIndex).removeClass('bg-danger')
                                          $(currentIndex).removeClass('bg-warning')
                                          var start = document.getElementById('start');
                                                            start.focus();
                                                            start.style.backgroundColor = 'green';
                                                            start.style.color = 'white';
                                    }
                              }
                        }
                        else{
                              if(can_edit){
                                    string = $(this).text();
                                    currentIndex = this;
                                    $('#start').length > 0 ? dotheneedful(this) : false
                                    $('td').removeAttr('style');
                                    $('#start').removeAttr('id')
                                    $(this).attr('id','start')
                                    $(currentIndex).removeClass('bg-danger')
                                    $(currentIndex).removeClass('bg-warning')
                                    var start = document.getElementById('start');
                                                      start.focus();
                                                      start.style.backgroundColor = 'green';
                                                      start.style.color = 'white';

                              }
                        }
                  })


                  function dotheneedful(sibling) {

                        var term = $(sibling).attr('data-term')

                        if(term == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prelim')
                        }else if(term == 2){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'midterm')
                        }else if(term == 3){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prefi')
                        }else if(term == 4){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'finalterm')
                        }
                        
                        if(checkDateSetup.length > 0){
                              if(!checkDateSetup[0].isended){
                                    Toast.fire({
                                          type: 'warning',
                                          title: 'Deadline Ended.'
                                    })
                                    return false
                              }
                        }

                        if (sibling != null) {
                              currentIndex = sibling
                              $(sibling).removeClass('bg-danger')
                              $(sibling).removeClass('bg-warning')

                              if($(start).text() == 'DROPPED'){
                                    $(start).addClass('bg-danger')
                              }else if($(start).text() == 'INC' || $(start).attr('data-status') == 3){
                                    $(start).addClass('bg-warning')
                              }
                             
                              start.style.backgroundColor = '';
                              start.style.color = '';
                              sibling.focus();
                              sibling.style.backgroundColor = 'green';
                              sibling.style.color = 'white';
                              start = sibling;

                             
                             
                              $('#message').empty();
                              string = $(currentIndex)[0].innerText
                        }
                  }

                  document.onkeydown = checkKey;

                  function checkKey(e) {

                        e = e || window.event;
                        if (e.keyCode == '38' && currentIndex != undefined)  {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.previousElementSibling;
                              if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              else{
                                    $('#curText').text(string)
                                    var sibling = nextrow.cells[idx];
                                    if(sibling == undefined){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '40' && currentIndex != undefined) {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;
                              if(nextrow == null || !$(nextrow.cells[idx]).hasClass('input_grades')){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(nextrow.cells[idx]).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              else{
                                    $('#curText').text(string)
                                    var sibling = nextrow.cells[idx];
                                    if(sibling == undefined){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '37' && currentIndex != undefined) {
                              var sibling = start.previousElementSibling;
                              if(sibling == null || !$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              else if($(sibling)[0].nodeName != "TD" ){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        } else if (e.keyCode == '39' && currentIndex != undefined) {
                              var sibling = start.nextElementSibling;
                              if(sibling == null || !$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              else if($(sibling)[0].nodeName != "TD" ){
                                    return false;
                              }
                              if(school == 'spct'.toUpperCase() && $(sibling).attr('data-term') == 'finalgrade'){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        }
                        else if (e.keyCode == '73' && currentIndex != undefined) {
                              $(currentIndex).text("INC")
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')
                        }
                        else if (e.keyCode == '68' && currentIndex != undefined) {
                              $(currentIndex).text("DROPPED")
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')
                        }
                        else if( e.key == "Backspace" && currentIndex != undefined){

                              // if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                              //       $(currentIndex).text('')
                              //       $('#curText').text("")
                              //       string = ''
                              //       $(currentIndex).addClass('updated')
                              //       $('#grade_submit').attr('disabled','disabled')
                              //       $('#save_grades').removeAttr('disabled')
                              //       return false
                              // }

                              if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                                    string = ''
                              }else{
                                    string = currentIndex.innerText
                                    string = string.slice(0 , -1);
                              }
                              
                              

                              if(string.length == 0){
                                    string = '';
                                    currentIndex.innerText = string
                              }else{
                                    currentIndex.innerText = parseInt(string)
                                    inputIndex = currentIndex
                              }

                              

                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')

                              $(currentIndex).text(string)
                              $('#curText').text(string)

                              var temp_studid = $(currentIndex).attr('data-studid')
                              var prelim =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="1"]').text());
                              var midterm =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="2"]').text());
                              var prefi = parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="3"]').text());
                              var final =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="4"]').text());

                              if(gradesetup.f_frontend != '' || gradesetup.f_frontend != null){

                                    var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)
                                    if(!isNaN(fg)){
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').text(fg)
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').addClass('updated')
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').addClass('updated')

                                          if(gradesetup.isPointScaled == 0){
                                                if(fg >= gradesetup.passingRate){
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('PASSED')
                                                }else{
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('FAILED')
                                                }
                                          }else{
                                                if(fg <= gradesetup.passingRate){
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('PASSED')
                                                }else{
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('FAILED')
                                                }
                                          }
                                          
                                    }else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').addClass('updated')
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').addClass('updated')

                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').text(null)
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').text(null)
                                    }
                              }

                        }
                        else if ( ( ( e.key >= 0 && e.key <= 9 ) || e.key == '.' ) && currentIndex != undefined) {


                              

                              //check ForPoint
                              if(e.key == '.'){
                                    if(gradesetup.decimalPoint == 0){
                                          return false
                                    }
                                    var checkForPoint = string.includes('.')
                                    if(checkForPoint){
                                          return false
                                    }
                              }

                              var check_string = string + e.key;
                              var decimalcount = count_decimal(check_string)

                              

                              if(decimalcount <= gradesetup.decimalPoint){
                                    string += e.key;
                              }else{
                                    string = string;
                              }
                              


                              
                              if(gradesetup.isPointScaled == 0){
                                    if(check_string > 100){
                                          string = 100 
                                    }
                              }else{
                                    if(check_string > 5){
                                          return false
                                    }
                              }

                              
                              if(currentIndex.innerText == 'INC' || currentIndex.innerText == 'DROPPED'){
                                    string = ''
                              }
                              
                              $(currentIndex).addClass('updated')
                              $('#save_grades').removeAttr('disabled')
                              $('#grade_submit').attr('disabled','disabled')

                              $(currentIndex).text(string)
                              $('#curText').text(string)

                              var temp_studid = $(currentIndex).attr('data-studid')
                              var prelim =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="1"]').text());
                              var midterm =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="2"]').text());
                              var prefi = parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="3"]').text());
                              var final =  parseFloat($('.grade_td[data-studid="'+temp_studid+'"][data-term="4"]').text());

                              if(gradesetup.f_frontend != '' || gradesetup.f_frontend != null){

                                    var fg = eval(gradesetup.f_frontend).toFixed(gradesetup.decimalPoint)

                                    if(!isNaN(fg)){
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').text(fg)
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').addClass('updated')
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').addClass('updated')

                                          if(gradesetup.isPointScaled == 0){
                                                if(fg >= gradesetup.passingRate){
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('PASSED')
                                                }else{
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('FAILED')
                                                }
                                          }else{
                                                if(fg <= gradesetup.passingRate){
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('PASSED')
                                                }else{
                                                      $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('FAILED')
                                                }
                                          }
                                          
                                          
                                    }
                                    else{
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').text('')
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').text('')
                                          $('th[data-studid="'+temp_studid+'"][data-term="5"]').text(null)
                                          $('th[data-studid="'+temp_studid+'"][data-term="6"]').text(null)
                                    }
                              }
                             
                        }
                      
                  }

            })

            function count_decimal(num) {
                  const converted = num.toString();
                  if (converted.includes('.')) {
                  return converted.split('.')[1].length;
                  };
                  return 0;
            }


      </script>

      <script>

            const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 2000,
            })

            var gradesetup = [];

            getgradesetup()
            function getgradesetup(){
                  $.ajax({
                        type:'GET',
                        url:'/semester-setup/getactive-setup',
                        async: false,  
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                        },
                        success:function(data) {
                              gradesetup = data
                              if(gradesetup.length == 0){
                                    $('#grades_setup_holder').attr('hidden','hidden')
                                    $('#grades_setup_holder')[0].innerHTML = '<div class="col-md-12"><p class="mb-0 text-danger">* No available grade setup.</p></div>'
                              }else{

                                    $('#grades_setup_holder').removeAttr('hidden','hidden')
                                    gradesetup = gradesetup[0]
                                    
                                    var termtext = ''
                                    if(gradesetup.prelim == 1){
                                          termtext += '<span class="badge badge-primary ml-1">Prelim</span>'
                                    }
                                    if(gradesetup.midterm == 1){
                                          termtext += '<span class="badge badge-primary ml-1">Midterm</span>'
                                    }
                                    if(gradesetup.prefi == 1){
                                          termtext += '<span class="badge badge-primary ml-1">Prefi</span>'
                                    }
                                    if(gradesetup.final == 1){
                                          termtext += '<span class="badge badge-primary ml-1">Final</span>'
                                    }
                                    $('#setup_term_holder')[0].innerHTML = termtext
                                    $('#setup_fgc_holder').text(gradesetup.f_frontend)
                                    $('#setup_dp_holder').text(gradesetup.decimalPoint)


                                    if(gradesetup.isPointScaled == 1){
                                          $('#setup_gs_holder').text('Decimal Point Scale ( 1 - 5 )')
                                    }else{
                                          $('#setup_gs_holder').text('Numerical Point Scale ( 60 - 100 )')
                                    }

                                    // $('#grades_setup_holder').remove()
                              }
                        }
                  })
            }

            var inputperiod = []
            getinputperiod()
            function getinputperiod(){
                  $.ajax({
                        type:'GET',
                        url:'/college/inputperiods/get/active',
                        async: false,  
                        data:{
                              syid:$('#filter_sy').val(),
                              semid:$('#filter_semester').val(),
                        },
                        success:function(data) {
                              inputperiod = data

                              $.each(inputperiod,function(a,b){
                                    var pastDate = moment(b.dateend);
                                    var dDiff = moment().isBefore(pastDate);
                                    b.isended = dDiff
                              })


                               // if(inputperiod.length == 0){
                              //       $('#input_period_holder').attr('hidden','hidden')
                              //       // $('#input_period_holder').empty()
                              //       $('#input_period_holder')[0].innerHTML = '<div class="col-md-12"><p class="mb-0 text-danger">* No available input Period.</p></div>'
                              // }else{
                              //       $('#input_period_holder').removeAttr('hidden')
                              //       $('#input_period_holder')[0].innerHTML = '<div class="col-md-12"><label>Input Period: </label>'+inputperiod[0].startformat2 + ' - ' + inputperiod[0].endformat2+'</div>'
                              // }
                        }
                  })
            }

           

            $(document).ready(function (){

                 

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });

                  $(document).on('click','#grade_submit',function() {
                        $('#quarter_select').val("")
                        $('.grade_submission_student').empty()
                        $('.select').attr('disabled','disabled')
                        $('.select').removeAttr('data-id')
                        $('.select_all').attr('disabled','disabled')
                        $('.select').prop('checked',true)
                        $('.select_all').prop('checked',true)
                        $('#submit_selected_grade').attr('disabled','disabled')
                        // $('#submit_selected_grade').removeAttr('class')
                        // $('#submit_selected_grade').addClass('btn btn-primary float-right btn-sm')
                        // $('#submit_selected_grade').text('Submit Grades')
                        // $('#submit_selected_grade').attr('data-id',1)
                        $('#modal_3').modal()
                  })

                  $(document).on('click','.select_all',function() {
                        if($(this).prop('checked') == true){
                              $('.select').prop('checked',true)
                        }else{
                              $('.select').each(function(){
                                    if($(this).attr('disabled') == undefined){
                                          $(this).prop('checked',false)
                                    }
                              })
                        }
                  })

                  $(document).on('change','#quarter_select',function() {
                        var term = $(this).val()
                        if(term == ""){
                              $('.select_all').attr('disabled','disabled')
                              $('.select').attr('disabled','disabled')
                              $('.grade_submission_student').text()
                              $('#submit_selected_grade').attr('disabled','disabled')
                              $('.select').removeAttr('data-id')
                              $('.grade_submission_student').empty()
                              return false
                        }
                        $('#submit_selected_grade').removeAttr('disabled')
                        $('.select_all').removeAttr('disabled')
                        $('.select').removeAttr('disabled')
                        $('.grade_td[data-term="'+term+'"]').each(function(a,b){
                              if($(this).attr('data-status') == 1 || $(this).attr('data-status') == 7 || $(this).attr('data-status') == 8 || $(this).attr('data-status') == 9 || $(this).attr('data-status') == 2 || $(this).attr('data-status') == 4){
                                    $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('disabled','disabled')
                              }
                              $('.grade_submission_student[data-studid="'+$(this).attr('data-studid')+'"]').text($(this).text())
                              $('.select[data-studid="'+$(this).attr('data-studid')+'"]').attr('data-id',$(this).attr('data-id'))
                        })
                  })

                  $(document).on('click','#submit_selected_grade',function() {
                              submit_grade()
                  })

                  function get_term(term){
                        if(term == 1){ return "prelemgrade" }
                        else if(term == 2){ return "midtermgrade" }
                        else if(term == 3){ return "prefigrade" }
                        else if(term == 4){ return "finalgrade" }
                  }

                  function submit_grade(){

                        var selected = []
                        var term = $('#quarter_select').val()
                        var dterm = term
                        term = get_term(term)

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined && $(this).attr('data-id') != undefined){
                                    selected.push($(this).attr('data-id'))
                              }
                        })

                        if(selected.length == 0){
                              Toast.fire({
                                    type: 'info',
                                    title: 'No student selected'
                              })
                              return false
                        }

                        Swal.fire({
                              html:
                                    '<h4>Are you sure you want <br>' +
                                    'to submit grades?</h4>',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit Grades!'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          url: '/college/teacher/student/grades/submit',
                                          data:{
                                                syid:$('#filter_sy').val(),
                                                semid:$('#filter_semester').val(),
                                                term:term,
                                                selected:selected,
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Submitted Successfully!'
                                                      })
                                                      $.each(selected,function(a,b){

                                                            $('.input_grades[data-id="'+b+'"][data-term="'+dterm+'"]').removeClass('bg-warning')
                                                            $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+dterm+'"]').attr('data-status',1)
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+dterm+'"]').addClass('bg-success')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+dterm+'"]').removeClass('input_grades')
                                                            var temp_id = all_grades.findIndex(x=>x.id == b)
                                                            if(dterm == '1'){
                                                                  all_grades[temp_id].prelemstatus = 1
                                                            }else if(dterm == '2'){
                                                                  all_grades[temp_id].midtermstatus = 1
                                                            }else if(dterm == '3'){
                                                                  all_grades[temp_id].prefistatus = 1
                                                            }else if(dterm == '4'){
                                                                  all_grades[temp_id].finalstatus = 1
                                                            }
                                                            plot_subject_grades(all_grades)
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          },error:function(){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    })
                              }
                        })

                  }

                  function inc_grade(){

                        var selected = []
                        var students = []
                        var term = $('#quarter_select').val()
                        term = get_term(term)

                        $('.select').each(function(){
                              if($(this).prop('checked') == true && $(this).attr('disabled') == undefined){
                                    selected.push($(this).attr('data-id'))
                                    students.push($(this).attr('data-id'))
                              }
                        })

                        Swal.fire({
                              html:
                                    '<h4>Are you sure you want <br>' +
                                    'to mark student as INC?</h4>',
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Submit Grades!'
                        }).then((result) => {
                              if (result.value) {
                                    $.ajax({
                                          type:'POST',
                                          url: '/college/teacher/student/grades/inc',
                                          data:{
                                                syid:$('#filter_sy').val(),
                                                semid:$('#filter_semester').val(),
                                                term:term,
                                                selected:selected,
                                          },
                                          success:function(data) {
                                                if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: 'Submitted Successfully!'
                                                      })
                                                      $.each(selected,function(a,b){
                                                            $('.select[data-id="'+b+'"]').attr('disabled','disabled')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').attr('data-status',1)
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').addClass('bg-success')
                                                            $('.input_grades[data-id="'+b+'"][data-term="'+term+'"]').removeClass('input_grades')
                                                            var temp_id = all_grades.findIndex(x=>x.id == b)

                                                            if(term == 'prelemgrade'){
                                                                  all_grades[temp_id].prelemstatus = 1
                                                            }else if(term == 'midtermgrade'){
                                                                  all_grades[temp_id].midtermstatus = 1
                                                            }else if(term == 'prefigrade'){
                                                                  all_grades[temp_id].prefistatus = 1
                                                            }else if(term == 'finalgrade'){
                                                                  all_grades[temp_id].finalstatus = 1
                                                            }
                                                            plot_subject_grades(all_grades)
                                                            
                                                      })
                                                }else{
                                                      Toast.fire({
                                                            type: 'error',
                                                            title: 'Something went wrong!'
                                                      })
                                                }
                                          },error:function(){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Something went wrong!'
                                                })
                                          }
                                    })
                              }
                        })

                  }

                  $(document).on('click','#save_grades',function() {

                        $('#save_grades').text('Saving Grades...')
                        $('#save_grades').removeClass('btn-primary')
                        $('#save_grades').addClass('btn-secondary')
                        $('#save_grades').attr('disabled','disabled')

                        if( $('.updated[data-term="1"]').length == 0){
                              save_midterm()
                        }

                        $('.updated[data-term="1"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"prelemgrade",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="1"]').length == 0){
                                                save_midterm()
                                         }
                                    }
                              })
                        })


                  })

                  function save_midterm(){
                        if( $('.updated[data-term="2"]').length == 0){
                              save_prefi()
                        }
                        $('.updated[data-term="2"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"midtermgrade",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="2"]').length == 0){
                                                save_prefi()
                                         }
                                    }
                              })
                        })

                  }

                  function save_prefi(){
                        if( $('.updated[data-term="3"]').length == 0){
                              save_final()
                        }
                        $('.updated[data-term="3"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"prefigrade",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="3"]').length == 0){
                                                save_final()
                                         }
                                    }
                              })
                        })

                  }

                  function save_final(){
                        if( $('.updated[data-term="4"]').length == 0){
                              save_fg()
                        }
                        $('.updated[data-term="4"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"finalgrade",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                          $(td).removeClass('updated')
                                          if($('.updated[data-term="4"]').length == 0){
                                                      save_final()
                                          }
                                    }
                              })
                        })
                  }

                  function save_fg(){
                        if( $('.updated[data-term="5"]').length == 0){
                              save_fgremarks()
                        }
                        $('.updated[data-term="5"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"fg",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                          $(td).removeClass('updated')
                                          if($('.updated[data-term="5"]').length == 0){
                                                      save_final()
                                          }
                                    }
                              })
                        })
                  }

                  function save_fgremarks(){
                        if( $('.updated[data-term="6"]').length == 0){
                              Toast.fire({
                                    type: 'success',
                                    title: 'Saved Successfully!'
                              })
                              $('#save_grades').attr('disabled','disabled')
                              $('#save_grades').removeClass('btn-secondary')
                              $('#save_grades').addClass('btn-primary')
                              $('#save_grades').text('Save Grades')
                              $('#grade_submit').removeAttr('disabled')

                              var temp_students = all_subject.filter(x=>x.schedid == schedid)
                              get_grades(schedid,false,temp_students[0].students)
                             
                        }
                        $('.updated[data-term="6"]').each(function(a,b){
                              var studid = $(this).attr('data-studid')
                              var term = $(this).attr('data-term')
                              var courseid = $(this).attr('data-course')
                              var sectionid = $(this).attr('data-section')
                              var pid = $(this).attr('data-pid')
                              var termgrade = $(this).text()
                              var td = $(this)
                              $.ajax({
                                    type:'POST',
                                    url: '/college/teacher/student/grades/save',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          term:"fgremarks",
                                          sectionid:sectionid,
                                          termgrade:termgrade,
                                          studid:studid,
                                          courseid:courseid,
                                          pid:pid,
                                    },
                                    success:function(data) {
                                         $(td).removeClass('updated')
                                         if($('.updated[data-term="6"]').length == 0){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Saved Successfully!'
                                                })
                                                $('#save_grades').attr('disabled','disabled')
                                                $('#save_grades').removeClass('btn-secondary')
                                                $('#save_grades').addClass('btn-primary')
                                                $('#save_grades').text('Save Grades')
                                                $('#grade_submit').removeAttr('disabled')
                                                var temp_students = all_subject.filter(x=>x.schedid == schedid)
                                                get_grades(schedid,false,temp_students[0].students)
                                                get_grades(schedid,false,temp_students[0].students)
                                         }
                                    }
                              })
                        })
                  }




                  var school = @json($schoolinfo);

                  // const Toast = Swal.mixin({
                  //       toast: true,
                  //       position: 'top-end',
                  //       showConfirmButton: false,
                  //       timer: 2000,
                  // })

                  $.ajaxSetup({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                  });

                  var all_subject = []
                  get_subjects()

                  var schedid = null;
                  $(document).on('click','.submit_grade',function(){
                        var temp_button = $(this)
                        temp_button.attr('disabled','disabled')
                        var term = $(this).attr('data-term')
                        $.ajax({
                              type:'POST',
                              url: '/college/teacher/student/grades/submit',
                              data:{
                                    schedid:schedid,
                                    term:term,
                              },
                              success:function(data) {
                                    if(data[0].status == 1){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Submitted Successfully!'
                                          })
                                          temp_button.removeAttr('disabled')
                                    }else{
                                          temp_button.removeAttr('disabled')
                                          Toast.fire({
                                                type: 'danger',
                                                title: 'Something went wrong!'
                                          })
                                    }
                              },
                              error:function(){
                                    temp_button.removeAttr('disabled')
                                    Toast.fire({
                                          type: 'danger',
                                          title: 'Something went wrong!'
                                    })
                              }
                        })
                  })


                  $(document).on('change','#filter_sy , #filter_semester',function (){
                        all_gradestatus = []
                        // datatable_3()
                        all_subject = []
                        getinputperiod()
                        getgradesetup()
                        datatable_1()
                        get_subjects()
                  })

                  $(document).on('change','#term',function (){
                        // datatable_3()
                        datatable_1()
                  })

                  function get_subjects() {
                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/subject',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    teacherid:73
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          Toast.fire({
                                                type: 'warning',
                                                title: 'No records Found!'
                                          })
                                    }else{
                                          all_subject = data
                                          // get_enrolled()
                                          // grade_status()
                                          datatable_1()
                                    }
                              }
                        })
                  }

                  function get_enrolled(){
                        $.each(all_subject,function(a,b){
                              $.ajax({
                                    type:'GET',
                                    url: '/college/teacher/student/grades/students',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_semester').val(),
                                          schedid:b.schedid,
                                          subjid:b.subjectID
                                    },
                                    success:function(data) {
                                          datatable_1()
                                    }
                              })
                        })
                  }


                  var all_gradestatus = []
                  function grade_status(){
                        // $.ajax({
                        //       type:'GET',
                        //       url: '/college/teacher/student/grades/status/get',
                        //       data:{
                        //             syid:$('#filter_sy').val(),
                        //             semid:$('#filter_semester').val(),
                        //       },
                        //       success:function(data) {
                        //             all_gradestatus = data
                        //             datatable_3()
                        //       }
                        // })


                  }

                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })


                  $(document).on('click','.view_grades',function(){

                        if(gradesetup.length == 0){
                              $('.term_holder[data-term=1]').remove()
                              $('.term_holder[data-term=2]').remove()
                              $('.term_holder[data-term=3]').remove()
                              $('.term_holder[data-term=4]').remove()
                        }

                        $('#message_holder').text('')
                        $('#save_grades').attr('hidden','hidden')
                        $('#modal_2').modal()
                        temp_id = $(this).attr('data-id')
                        schedid = temp_id

                        $('.with_submission_info').remove()
                        $('.submit_grade').attr('hidden','hidden')
                       
                        var students = all_subject.filter(x=>x.schedid == temp_id)

                        $('#section')[0].innerHTML = '<a class="mb-0">'+students[0].sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+students[0].levelname.replace('COLLEGE','')+' - '+students[0].courseabrv+'</p>'

                        $('#subject')[0].innerHTML = '<a class="mb-0">'+students[0].subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+students[0].subjCode+'</p>'

                        $('#student_list_grades').empty()
                        var female = 0;
                        var male = 0;
                        var count = 1;
                        var pid = students[0].pid
                        var sectionid = students[0].sectionID


                        if(students[0].students.length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'No student Found!'
                              })
                              return false
                        }else{
                              $('#save_grades').removeAttr('hidden')
                        }

                        $('#datatable_4').empty()

                        $('.student_count').text(students[0].students.length)
                        var colspan = 9;
                        
                        var disprelim = 0
                        var dismidterm = 0
                        var disprefi = 0
                        var disfinal = 0


                        if(gradesetup != null){
                              disprelim = gradesetup.prelim
                              dismidterm = gradesetup.midterm
                              disprefi = gradesetup.prefi
                              disfinal = gradesetup.final
                        }

                        if(disprelim == 0){
                              $('#quarter_select option[value="1"]').attr('hidden','hidden')
                              $('.term_holder[data-term=1]').remove()
                              colspan -= 1
                        }
                        
                        if(dismidterm == 0){
                              $('#quarter_select option[value="2"]').attr('hidden','hidden')
                              $('.term_holder[data-term=2]').remove()
                              colspan -= 1
                        }
                        
                        if(disprefi == 0){
                              $('#quarter_select option[value="3"]').attr('hidden','hidden')
                              $('.term_holder[data-term=3]').remove()
                              colspan -= 1
                        }
                        
                        if(disfinal == 0){
                              $('#quarter_select option[value="4"]').attr('hidden','hidden')
                              $('.term_holder[data-term=4]').remove()
                              colspan -= 1
                        }

                        $('#quarter_select').select2()

                        $('#deadline_holder').empty()
                        var deadlinetext = '<td colspan="3" class="text-right align-middle p-0"><b>Date Deadline</b></td>'
                        if(disprelim == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prelim')
                              if(checkDateSetup.length > 0){
                                    var bg = 'bg-success'
                                    if(!checkDateSetup[0].isended){
                                          var bg = 'bg-danger'
                                    }
                                    deadlinetext += '<td class=" p-0 text-center '+bg+'">'+checkDateSetup[0].endformat2+'</td>'
                              }else{
                                    deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
                              }
                        }
                        if(dismidterm == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'midterm')
                              if(checkDateSetup.length > 0){
                                    var bg = 'bg-success'
                                    if(!checkDateSetup[0].isended){
                                          var bg = 'bg-danger'
                                    }
                                    deadlinetext += '<td class=" p-0 text-center '+bg+'">'+checkDateSetup[0].endformat2+'</td>'
                              }else{
                                    deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
                              }
                        }
                        if(disprefi == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'prefi')
                              if(checkDateSetup.length > 0){
                                    var bg = 'bg-success'
                                    if(!checkDateSetup[0].isended){
                                          var bg = 'bg-danger'
                                    }
                                    deadlinetext += '<td class=" p-0 text-center '+bg+'">'+checkDateSetup[0].endformat2+'</td>'
                              }else{
                                    deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
                              }
                        }
                        if(disfinal == 1){
                              checkDateSetup = inputperiod.filter(x=>x.term == 'finalterm')
							  
							 
                              if(checkDateSetup.length > 0){
                                    var bg = 'bg-success'
                                    if(!checkDateSetup[0].isended){
                                          var bg = 'bg-danger'
                                    }
									
                                    deadlinetext += '<td class=" p-0 text-center '+bg+'">'+checkDateSetup[0].endformat2+'</td>'
                              }else{
                                    deadlinetext += '<td class=" p-0 text-center align-middle">Not Set</td>'
                              }
							  
							  console.log(deadlinetext)
                              
                        }
                        deadlinetext += '<td class=" p-0"></td><td class=" p-0"></td>'

                        $('#deadline_holder').append(deadlinetext)

                        $.each(students[0].students,function (a,b) {

                                    

                                    if(male == 0 && b.gender == 'MALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">MALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">MALE</th></tr>')
                                          male = 1
                                          count = 0
                                    }else if(female == 0 && b.gender == 'FEMALE'){
                                          $('#student_list_grades').append('<tr class="bg-secondary"><th colspan="'+colspan+'">FEMALE</th></tr>')
                                          $('#datatable_4').append('<tr class="bg-secondary"><th colspan="4">FEMALE</th></tr>')
                                          female = 1
                                          count = 0
                                    }

                                    count += 1

                                    var text = '<tr><td class="text-center">'+count+'</td><td>'+b.student+'</td><td>'+b.courseabrv+'</td>'

                                    if(disprelim == 1){
                                          text += '<td  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td term_holder" data-term="1" ></td>'
                                    }

                                    if(dismidterm == 1){
                                          text += '<td  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td term_holder" data-term="2"></td>'
                                    }

                                    if(disprefi == 1){
                                          text += '<td  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td term_holder" data-term="3"></td>'
                                    }

                                    if(disfinal == 1){
                                          text += '<td  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class="grade_td term_holder" data-term="4" ></td>'
                                    }

                                    text += '<th  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class=" term_holder text-center" data-term="5"></th>'

                                    text += '<th  data-studid="'+b.studid+'" data-course="'+b.courseid+'" data-pid="'+pid+'" data-section="'+sectionid+'" class=" term_holder text-center" data-term="6"></th>'

                                    text += '</tr>'


                                    $('#student_list_grades').append(text)
                                    $('#datatable_4').append('<tr><td><input disabled checked="checked" type="checkbox" class="select" data-studid="'+b.studid+'" data-id="'+b.id+'"></td><td>'+b.sid+'</td><td>'+b.student+'</td><td data-studid="'+b.studid+'" class="grade_submission_student text-center"></td></tr>')

                                    

                           
                        })

                        $('.grade_td').addClass('text-center')
                        $('.grade_td').addClass('align-middle')
                        get_grades(temp_id,true, students[0].students);

                  })


                  var all_grades = []
                  var dean = @json($dean)

                  $('#printable_dean').select2({
                        'data':dean,
                        'placeholder':'Select Dean'
                  })

                  
                  $(document).on('click','#print_grades_to_modal',function(){
                        $('#dean_holder_modal').modal()
                  })


                  $(document).on('click','#print_grades',function(){
                        print_grades()
                  })

                  function print_grades() {
                       
                       var pid = []
                       var sectionid = []
                       var students = all_subject.filter(x=>x.schedid == schedid)[0].students
                       var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                       var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]

                       $.each(temp_pid,function(a,b){
                             pid.push(b.pid)
                       })
                       $.each(temp_sectionid,function(a,b){
                             sectionid.push(b.sectionid)
                       })

                       var temp_subjid = temp_pid[0].pid

                       var syid = $('#filter_sy').val()
                       var semid = $('#filter_semester').val()
                       var pid = pid
                       var sectionid = sectionid
                       var dean = $('#printable_dean').val()

                       window.open('/college/teacher/student/grades/print?&syid='+syid+'&semid='+semid+'&pid='+pid+'&sectionid='+sectionid+'&schedid='+schedid+'&subjid='+temp_subjid+'&dean='+dean, '_blank');

                 }

                  function get_grades(schedid, prompt = true, students) {

                        // var sched = all_subject.filter(x=>x.schedid == schedid)
                        // var pid = sched[0].pid
                        // var sectionid = sched[0].sectionID

                        // if(school == 'gbbc'.toUpperCase()){
                              var pid = []
                              var sectionid = []
                              var temp_pid = [...new Map(students.map(item => [item['pid'], item])).values()]
                              var temp_sectionid = [...new Map(students.map(item => [item['sectionid'], item])).values()]
                              $.each(temp_pid,function(a,b){
                                    pid.push(b.pid)
                              })
                              $.each(temp_sectionid,function(a,b){
                                    sectionid.push(b.sectionid)
                              })
                        // }

                        $('.p_count').text(0)
                        $('.f_count').text(0)
                        $('.ng_count').text(0)

                        $('.drop_count').text(0)
                        $('.inc_count').text(0)
                        $('.pen_count').text(0)
                        $('.sub_count').text(0)
                        $('.app_count').text(0)

                        $.ajax({
                              type:'GET',
                              url: '/college/teacher/student/grades/get',
                              data:{

                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_semester').val(),
                                    pid:pid,
                                    sectionid:sectionid
                              },
                              success:function(data) {

                                    $('.grade_td').addClass('input_grades')
                                    all_grades = data

                                    if(data.length == 0){
                                          // Toast.fire({
                                          //       type: 'warning',
                                          //       title: 'No grades found!'
                                          // })
                                          // $('#message_holder').text('No grades found. Please input student grades.')
                                    }else{

                                          $('.drop_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 9).length)
                                          $('.drop_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 9).length)
                                          $('.drop_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 9).length)
                                          $('.drop_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 9).length)

                                          $('.inc_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 8).length)
                                          $('.inc_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 8).length)
                                          $('.inc_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 8).length)
                                          $('.inc_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 8).length)

                                          $('.pen_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 3).length)
                                          $('.pen_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 3).length)
                                          $('.pen_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 3).length)
                                          $('.pen_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 3).length)

                                          $('.sub_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 1).length)
                                          $('.sub_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 1).length)
                                          $('.sub_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 1).length)
                                          $('.sub_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 1).length)

                                          $('.app_count[data-stat="1"]').text(data.filter(x=>x.prelemstatus == 2 || x.prelemstatus == 7).length)
                                          $('.app_count[data-stat="2"]').text(data.filter(x=>x.midtermstatus == 2  || x.midtermstatus == 7).length)
                                          $('.app_count[data-stat="3"]').text(data.filter(x=>x.prefistatus == 2  || x.prefistatus == 7).length)
                                          $('.app_count[data-stat="4"]').text(data.filter(x=>x.finalstatus == 2  || x.finalstatus == 7).length)


                                          $('.p_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade >= 75).length)
                                          $('.p_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade >= 75).length)
                                          $('.p_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade >= 75).length)
                                          $('.p_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade >= 75).length)

                                          $('.f_count[data-stat="1"]').text(data.filter(x=>x.prelemgrade != null && x.prelemgrade < 75).length)
                                          $('.f_count[data-stat="2"]').text(data.filter(x=>x.midtermgrade != null && x.midtermgrade < 75).length)
                                          $('.f_count[data-stat="3"]').text(data.filter(x=>x.prefigrade != null && x.prefigrade < 75).length)
                                          $('.f_count[data-stat="4"]').text(data.filter(x=>x.finalgrade != null && x.finalgrade < 75).length)

                                          if(school == 'spct'.toUpperCase()){
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))
                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))
                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="2"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }
                                          else{
                                                $('.ng_count[data-stat="1"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="1"]').text()) + parseInt($('.f_count[data-stat="1"]').text()) )) 
                                                $('.ng_count[data-stat="2"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="2"]').text()) + parseInt($('.f_count[data-stat="2"]').text()) ))
                                                $('.ng_count[data-stat="3"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="3"]').text()) + parseInt($('.f_count[data-stat="3"]').text()) ))
                                                $('.ng_count[data-stat="4"]').text(parseInt($('.student_count[data-stat="1"]').text()) - ( parseInt($('.p_count[data-stat="4"]').text()) + parseInt($('.f_count[data-stat="4"]').text()) ))
                                          }
                                          
                                          plot_subject_grades(data)
                                          if(prompt){
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Grades found!'
                                                })
                                                $('#message_holder').text('Grades found.')
                                          }

                                         
                                    }

                              },
                              error:function(){
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Something went wrong!'
                                    })
                                    $('#message_holder').text('Unable to load grades.')
                              }
                        })
                  }

                  function plot_subject_grades(data){
                        $.each(data,function(a,b){
                                                
                              var q1status = 'input_grades'
                              var q2status = 'input_grades'
                              var q3status = 'input_grades'
                              var q4status = 'input_grades'

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').text(b.prelemgrade != null ? b.prelemgrade : '')
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').text(b.midtermgrade != null ? b.midtermgrade : '')
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').text(b.prefigrade != null ? b.prefigrade : '')
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').text(b.finalgrade != null ? b.finalgrade : '')
                              

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').attr('data-id',b.id)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').attr('data-id',b.id)

                              $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').attr('data-status',b.prelemstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').attr('data-status',b.midtermstatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').attr('data-status',b.prefistatus)
                              $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').attr('data-status',b.finalstatus)

                              if(b.prelemstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-success')
                              }else if(b.prelemstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-primary')
                              }else if(b.prelemstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').text('DROPPED')
                              }else if(b.prelemstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').text('INC')
                              }else if(b.prelemstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-warning')
                              }else if(b.prelemstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-info')
                              }else if(b.prelemstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="1"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="1"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.midtermstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-success')
                              }else if(b.midtermstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-primary')
                              }else if(b.midtermstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').text('DROPPED')
                              }else if(b.midtermstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').text('INC')
                              }else if(b.midtermstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-info')
                              }else if(b.midtermstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-warning')
                              }else if(b.midtermstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="2"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="2"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.prefistatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-success')
                              }else if(b.prefistatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-primary')
                              }else if(b.prefistatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-info')
                              }else if(b.prefistatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').text('DROPPED')
                              }else if(b.prefistatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').text('INC')
                              }else if(b.prefistatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-warning')
                              }else if(b.prefistatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="3"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="3"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              if(b.finalstatus == 1){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-success')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-success')
                                    if (b.fgremarks == 'FAILED') {
                                          $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-danger')
                                    } else {
                                          $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-success')
                                    }
                              }else if(b.finalstatus == 7){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-primary')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-primary')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-primary')
                              }else if(b.finalstatus == 9){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-danger')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-danger')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-danger')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').text('DROPPED')
                                   
                              }else if(b.finalstatus == 8){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-warning')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-warning')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-warning')
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').text('INC')
                              }else if(b.finalstatus == 4){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-info')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-info')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-info')
                              }else if(b.finalstatus == 3){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-warning')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-warning')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-warning')
                              }else if(b.finalstatus == 2){
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').addClass('bg-secondary')
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').addClass('bg-secondary')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').addClass('bg-secondary')
                              }else{
                                    $('.input_grades[data-studid="'+b.studid+'"][data-term="4"]').removeAttr('class')
                                    $('td[data-studid="'+b.studid+'"][data-term="4"]').addClass('grade_td text-center align-middle input_grades')
                              }

                              $('th[data-studid="'+b.studid+'"][data-term="5"]').text(b.fg != null ? b.fg : '')
                              $('th[data-studid="'+b.studid+'"][data-term="6"]').text(b.fgremarks != null ? b.fgremarks : '')
                              $('.select[data-studid="'+b.studid+'"]').attr('data-id',b.id)


                              if(b.finalstatus == 9){
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').text('DROPPED')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').text('DROPPED')
                              }else if(b.finalstatus == 8){
                                    $('th[data-studid="'+b.studid+'"][data-term="5"]').text('INC')
                                    $('th[data-studid="'+b.studid+'"][data-term="6"]').text('INC')
                              }
                                   
                        })

                  }

                  $(document).on('click','.view_students',function(){
                        $('#modal_1').modal()
                        temp_id = $(this).attr('data-id')
                        var students = all_subject.filter(x=>x.schedid == temp_id)
                        datatable_2(students[0].students)
                  })


                  function datatable_2(students){

                        $("#datatable_2").DataTable({
                              destroy: true,
                              data:students,
                              lengthChange: false,
                              autoWidth: false,
                              columns: [
                                    { "data": "search"},
                                    { "data": "levelname"},
                                    { "data": "courseabrv"},
                                    { "data": "gender"},
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.student
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML =  rowData.levelname.replace('COLLEGE','')
                                                $(td).addClass('align-middle')
                                          }
                                    },
                              ]
                        })

                  }



                  function datatable_1(){

                        // var all_data = all_subject
                        // if($('#term').val() != ""){
                        //       if($('#term').val() == "Whole Sem"){
                        //             all_data = all_subject.filter(x=>x.schedotherclass == null)
                        //       }else{
                        //             all_data = all_subject.filter(x=>x.schedotherclass == $('#term').val())
                        //       }
                        // }
                        if(school == 'sait'.toUpperCase()){
                              var temp_subjects = all_subject
                        }else{
                              var temp_subjects = all_subject
                        }
                   

                        $("#datatable_1").DataTable({
                              destroy: true,
                              data:temp_subjects,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": "subjDesc" },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                // if(school == 'spct'.toUpperCase() || school == 'gbbc'.toUpperCase()){
                                                //       var text = rowData.subjCode
                                                // }else{
                                                //       if(rowData.levelname == undefined){
                                                //             rowData.levelname = 'COLLEGE';
                                                //       }
                                                var text = ''

                                                $.each(rowData.sections,function(a,b){
                                                      text += '<span class=" badge badge-primary  mt-1" style="font-size:.65rem !important; white-space:normal" >'+b.schedgroupdesc+'</span> <br>'
                                                })
                                                      // var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">';
                                                // }
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                
                                                var schedotherclass = ''
                                                if(school == 'spct'.toUpperCase() || school == 'gbbc'.toUpperCase()){
                                                      var text = rowData.subjDesc
                                                }else{
                                                      var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode;
                                                }

                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')

                                               
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var buttons = '<button class="btn btn-sm btn-primary mr-1 view_students" data-id="'+rowData.schedid+'"><i class="fas fa-user-circle"></i> Students <i>('+rowData.students.length+')</i></button>'

                                                buttons += '<button class="btn btn-sm btn-secondary mr-1 view_grades" data-id="'+rowData.schedid+'"><i class="fas fa-chart-bar"></i> Grades</button>'
                                                $(td)[0].innerHTML = buttons
                                                $(td).addClass('text-right')
                                                $(td).addClass('align-middle')
                                                
                                          }
                                    }

                              ]
                        })
                  }

                  function datatable_3(){

                        var all_data = all_gradestatus
                        if($('#term').val() != ""){
                              if($('#term').val() == "Whole Sem"){
                                    all_data = all_gradestatus.filter(x=>x.schedotherclass == null)
                              }else{
                                    all_data = all_gradestatus.filter(x=>x.schedotherclass == $('#term').val())
                              }
                        }

                        $("#datatable_3").DataTable({
                              destroy: true,
                              data:all_data,
                              lengthChange: false,
                              scrollX: true,
                              autoWidth: false,
                              columns: [
                                    { "data": "sectionDesc"},
                                    { "data": "subjDesc" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null }
                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                var text = '<a class="mb-0">'+rowData.sectionDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.levelname.replace('COLLEGE','')+' - '+rowData.courseabrv+'</p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                // var schedotherclass = rowData.schedotherclass != null ? rowData.schedotherclass : 'Whole Semester'
                                                var schedotherclass = ''

                                                var text = '<a class="mb-0">'+rowData.subjDesc+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.subjCode+' - <i class="mb-0 text-danger" style="font-size:.7rem">'+schedotherclass+'</i></p>';
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].prelimstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].prelimstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].prelimdate+'</p>';
                                                }
                                             
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                if(school == 'spct'.toUpperCase()){
                                                      $(td).attr('hidden','hidden')
                                                }
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].midtermstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].midtermstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].midtermdate+'</p>';
                                                }
                                                
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                                if(school == 'spct'.toUpperCase()){
                                                      $(td).attr('hidden','hidden')
                                                }
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].prefistatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].prefistatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].prefidate+'</p>';
                                                }
                                               
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': false, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                if(rowData.gradestatus.length == 0){
                                                      var text = '<a class="mb-0">Not Submitted</a>';
                                                }else{
                                                      var status = ''
                                                      if(rowData.gradestatus[0].finalstatus == null){
                                                            status = 'Not Submitted'
                                                      }else if(rowData.gradestatus[0].finalstatus == 1){
                                                            status = 'Submitted'
                                                      }
                                                      var text = '<a class="mb-0">'+status+'</a><p class="text-muted mb-0" style="font-size:.7rem">'+rowData.gradestatus[0].finaldate+'</p>';
                                                }
                                                $(td)[0].innerHTML =  text
                                                $(td).addClass('align-middle')
                                                $(td).addClass('text-center')
                                          }
                                    }

                              ]
                        })
                  }
            })
      </script>
@endsection

