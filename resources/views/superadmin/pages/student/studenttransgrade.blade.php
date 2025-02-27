@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->orderBy('semester')->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Transferred In Grades</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Transferred In Grades</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
    
      <div class="container-fluid">
            <div class="row">
                  
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                          <div class="info-box-content">
                              <div class="row">
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">School Year</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sy">
                                                @foreach ($sy as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-2  form-group mb-0">
                                          <label for="">Semester</label>
                                          <select class="form-control select2 form-control-sm" id="filter_sem">
                                                @foreach ($semester as $item)
                                                      @if($item->isactive == 1)
                                                            <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                                      @else
                                                            <option value="{{$item->id}}">{{$item->semester}}</option>
                                                      @endif
                                                @endforeach
                                          </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                          <label for="">Student</label>
                                          <select name="studid" id="filter_studid" class="form-control select2"></select>
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
                                          <div class="col-md-12">
                                                <button class="btn btn-primary btn-sm view_sf9" style="font-size:.8rem" ><i class="fas fa-file-pdf"></i> SF9</button>
                                          </div>
                                    </div>
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table table-sm table-bordered">
                                                      <thead>
                                                            <tr>
                                                                  <th width="60%">Subject</th>
                                                                  <th width="10%" class="text-center q1">Q1</th>
                                                                  <th width="10%" class="text-center q2">Q2</th>
                                                                  <th width="10%" class="text-center q3">Q3</th>
                                                                  <th width="10%" class="text-center q4">Q4</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody id="subject_list">

                                                      </tbody>
                                                </table>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-12"><button class="btn btn-primary btn-sm" disabled id="save_grades">Save Grades</button></div>
                                    </div>
                              </div>
                        </div>
                       
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>


      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })

                  $('.select2').select2()

                  $(document).on('click','.view_sf9',function(){
                        var temp_id = $('#filter_studid').val()
                        window.open("/prinsf9print/"+temp_id+"?syid="+$('#filter_sy').val()+"&semid="+$('#filter_sem').val());
                  })

                  var all_student_transferedingrade = []
                  get_all_students()
                  $(document).on('click','#save_grades',function(){

                        if($('.updated').length == 0){
                              Toast.fire({
                                    type: 'warning',
                                    title: 'Nothing to save.'
                              })
                              return false
                        }

                        var temp_studid = $('#filter_studid').val()
                        var temp_students = all_students.filter(x=>x.studid == temp_studid)
                        $('.updated').each(function(a,b){
                              var subjid = $(this).attr('data-subj')
                              var quarter = $(this).attr('data-q')
                              var grade = $(this).text()
                              $.ajax({
                                    type:'GET',
                                    url:'/transferedin/grades/create',
                                    data:{
                                          syid:$('#filter_sy').val(),
                                          semid:$('#filter_sem').val(),
                                          studid:$('#filter_studid').val(),
                                          levelid:temp_students[0].levelid,
                                          sectionid:temp_students[0].sectionid,
                                          subjid:subjid,
                                          quarter:quarter,
                                          grade:grade,
                                    },
                                    success:function(data) {
                                          if(data[0].status == 0){
                                                Toast.fire({
                                                      type: 'warning',
                                                      title: data[0].message
                                                })
                                          }else{
                                                Toast.fire({
                                                      type: 'success',
                                                      title: data[0].message
                                                })
                                          }
                                    }
                              })
                        })
                  })

                  $(document).on('change','#filter_sy',function(){
                        $('#subject_list').empty()
                        $('#save_grades').attr('disabled','disabled')
                        get_all_students()
                  })

                  $(document).on('change','#filter_sem',function(){
                        if($('#filter_studid').val() == ""){
                              $('#subject_list').empty()
                              $('#save_grades').attr('disabled','disabled')
                              get_all_students()
                        }else{
                              var temp_studid = $('#filter_studid').val()
                              var temp_students = all_students.filter(x=>x.studid == temp_studid)
                              $('#subject_list').empty()
                              $('#save_grades').attr('disabled','disabled')
                              get_subjects(temp_students)
                        }
                        
                  })

                  $(document).on('change','#filter_studid',function(){
                        var temp_studid = $('#filter_studid').val()
                        var temp_students = all_students.filter(x=>x.studid == temp_studid)
                        $('#subject_list').empty()
                        $('#save_grades').attr('disabled','disabled')
                        get_subjects(temp_students)
                      
                  })

                  function get_subjects(studinfo){

                        $('.q1').removeAttr('hidden')
                        $('.q2').removeAttr('hidden')
                        $('.q3').removeAttr('hidden')
                        $('.q4').removeAttr('hidden')

                        var semid = 1

                        if(studinfo[0].levelid == 14 || studinfo[0].levelid == 15){
                              semid = $('#filter_sem').val()
                        }

                        $.ajax({
                              type:'GET',
                              url:'/transferedin/grades/subjects',
                              data:{
                                    levelid:studinfo[0].levelid,
                                    strandid:studinfo[0].strandid,
                                    syid:$('#filter_sy').val(),
                                    semid:semid,
                              },
                              success:function(data) {
                                    if(data.length == 0){

                                    }else{

                                          var q1hidden = ''
                                          var q2hidden = ''
                                          var q3hidden = ''
                                          var q4hidden = ''

                                          if(studinfo[0].levelid == 14 || studinfo[0].levelid == 15){
                                                if(semid){
                                                      q3hidden = 'hidden="hidden"' 
                                                      q4hidden = 'hidden="hidden"' 
                                                      $('.q3').attr('hidden','hidden')
                                                      $('.q4').attr('hidden','hidden')
                                                }else{
                                                      q1hidden = 'hidden="hidden"'
                                                      q2hidden = 'hidden="hidden"' 
                                                      $('.q1').attr('hidden','hidden')
                                                      $('.q2').attr('hidden','hidden')
                                                }
                                          }

                                          $.each(data,function(a,b){
                                                var pad = ''

                                                if(b.subjCom != null){
                                                      pad = 'pl-5'
                                                }
                                                var iscon = false

                                                if(studinfo[0].levelid == 14 || studinfo[0].levelid == 15){}
                                                else{
                                                      if(b.isCon == 1){
                                                            iscon = true;
                                                      }
                                                }

                                                if(!iscon){
                                                      $('#subject_list').append('<tr><td class="'+pad+'">'+b.subjdesc+'</td><td  class="text-center align-middle input_grades" data-q="1" '+q1hidden+' data-subj="'+b.subjid+'"></td><td class="text-center align-middle input_grades" data-q="2" '+q2hidden+' data-subj="'+b.subjid+'"></td><td  class="text-center align-middle input_grades" data-q="3" '+q3hidden+' data-subj="'+b.subjid+'"></td><td  class="text-center align-middle input_grades" data-q="4" '+q4hidden+' data-subj="'+b.subjid+'"></td></tr>')
                                                }else{
                                                      $('#subject_list').append('<tr><th class="'+pad+' bg-secondary">'+b.subjdesc+'</th><th  class="text-center align-middle bg-secondary"></th><th class="text-center align-middle bg-secondary"></th><th  class="text-center align-middle bg-secondary"></th><th  class="text-center align-middle bg-secondary"></th></tr>')
                                                }
                                               
                                          })

                                          load_student_transferedin_grades()
                                          $('#save_grades').removeAttr('disabled')
                                    }
                              }
                        })
                  }

                  // function get_all_gradelevel(){
                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/transferedin/grades/gradelevel',
                  //             success:function(data) {
                  //                   $("#levelid").empty()
                  //                   $("#levelid").append('<option value="">Select Grade Level</option>')
                  //                   $("#levelid").select2({
                  //                         data: data,
                  //                         allowClear: true,
                  //                         placeholder: "Select Grade Level",
                  //                   })
                  //             }
                  //       })
                  // }

                  var all_students = []
                  function get_all_students(){
                        $.ajax({
                              type:'GET',
                              url:'/transferedin/grades/students',
                              data:{
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val()
                              },
                              success:function(data) {
                                    if(data.length == 0){
                                          all_students = []
                                          Toast.fire({
                                                type: 'success',
                                                title: 'No student found!'
                                          })
                                    }else{
                                          all_students = data
                                          Toast.fire({
                                                type: 'success',
                                                title: data.length+' student(s) found!'
                                          })
                                         
                                    }
                                    $("#filter_studid").empty()
                                    $("#filter_studid").append('<option value="">Select Student</option>')
                                    $("#filter_studid").select2({
                                          data: data,
                                          allowClear: true,
                                          placeholder: "Select Student",
                                    })
                              }
                        })
                  }

                  function load_student_transferedin_grades(){
                        var temp_studid = $('#filter_studid').val()
                        var temp_student = all_students.filter(x=>x.id = $('#filter_studid').val())
                        $.ajax({
                              type:'GET',
                              url:'/transferedin/grades/list',
                              data:{
                                    studid:$('#filter_studid').val(),
                                    syid:$('#filter_sy').val(),
                                    semid:$('#filter_sem').val(),
                                    levelid:temp_student[0].levelid,
                              },
                              success:function(data) {
                                    $.each(data,function(a,b){
                                          $('.input_grades[data-q="'+b.quarter+'"][data-subj="'+b.subjid+'"]').text(b.qg)
                                    })
                              }
                        })
                  }

                  // function student_transferedin_datatable(){

                  //       $("#transferedingrade_datatable").DataTable({
                  //             destroy: true,
                  //             data:all_student_transferedingrade,
                  //             lengthChange : false,
                  //             columns: [
                  //                         { "data": "full_name" },
                  //                         { "data": "subjtext" },
                  //                         { "data": "levelname" },
                  //                         { "data": "sectionname" },
                  //                         { "data": "quarter" },
                  //                         { "data": "qg" },
                  //                         { "data": null },
                  //                         { "data": null },
                  //                   ],
                  //             columnDefs: [
                  //                   {
                  //                         'targets': 0,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 1,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 2,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 3,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 4,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('text-center')
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 5,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               $(td).addClass('text-center')
                  //                               $(td).addClass('align-middle')
                                                
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 6,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               var disabled = '';
                  //                               var buttons = '<a href="javascript:void(0)" '+disabled+' class="edit_transferedin_grades" data-id="'+rowData.id+'" data-studid="'+rowData.studid+'"><i class="far fa-edit text-primary"></i></a>';
                  //                               $(td)[0].innerHTML =  buttons
                  //                               $(td).addClass('text-center')
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //                   {
                  //                         'targets': 7,
                  //                         'orderable': false, 
                  //                         'createdCell':  function (td, cellData, rowData, row, col) {
                  //                               var disabled = '';
                  //                               var buttons = '<a href="javascript:void(0)" '+disabled+' class="delete_transferedin_grades" data-id="'+rowData.id+'" data-studid="'+rowData.studid+'"><i class="far fa-trash-alt text-danger"></i></a>';
                  //                               $(td)[0].innerHTML =  buttons
                  //                               $(td).addClass('text-center')
                  //                               $(td).addClass('align-middle')
                  //                         }
                  //                   },
                  //             ]
                              
                  //       });

                  //       }

              

            })
      </script>

      <script>
            $(document).ready(function () {

                  var isSaved = false;
                  var isvalidHPS = true;
                  var hps = []
                  var currentIndex 
                  var can_edit = true
                  
                  $(document).on('click','.input_grades',function(){
                        if(currentIndex != undefined){
                              if(isvalidHPS){
                                    if(can_edit){
                                          string = $(this).text();
                                          currentIndex = this;
                                          $('#start').length > 0 ? dotheneedful(this) : false
                                          $('td').removeAttr('style');
                                          $('#start').removeAttr('id')
                                          $(this).attr('id','start')
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
                                    var start = document.getElementById('start');
                                                      start.focus();
                                                      start.style.backgroundColor = 'green';
                                                      start.style.color = 'white';

                              }
                        }
                  })


                  function dotheneedful(sibling) {
                        if (sibling != null) {
                              currentIndex = sibling
                              start.focus();
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
                              $('#curText').text(string)
                              if (nextrow != null) {
                                    var sibling = nextrow.cells[idx];
                                    if(!$(sibling).hasClass('input_grades')){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                              

                        } else if (e.keyCode == '40' && currentIndex != undefined) {
                              var idx = start.cellIndex;
                              var nextrow = start.parentElement.nextElementSibling;
                              $('#curText').text(string)
                              var sibling = nextrow.cells[idx];
                              if (nextrow != null) {
                                    if(!$(sibling).hasClass('input_grades')){
                                          return false;
                                    }
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        } else if (e.keyCode == '37' && currentIndex != undefined) {
                              var sibling = start.previousElementSibling;
                              if(!$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }

                        } else if (e.keyCode == '39' && currentIndex != undefined) {
                              var sibling = start.nextElementSibling;
                              if(!$(sibling).hasClass('input_grades')){
                                    return false;
                              }
                              $('#curText').text(string)
                              if($(sibling)[0].cellIndex != 0){
                                    string = sibling.innerText;
                                    dotheneedful(sibling);
                              }
                        }
                        else if( e.key == "Backspace" && currentIndex != undefined){
                              string = currentIndex.innerText
                              string = string.slice(0 , -1);
                              if(string.length == 0){
                                    string = '';
                                    currentIndex.innerText = string
                              }else{
                                    currentIndex.innerText = parseInt(string)
                                    inputIndex = currentIndex
                              }
                              $(currentIndex).addClass('updated')
                              $('#save_button_1').removeAttr('disabled')
                              var temp_studid = $(currentIndex).attr('data-studid')
                              disabled_filter()
                              calcfg(temp_studid)
                        }
                        else if ( e.key >= 0 && e.key <= 9 && currentIndex != undefined) {
                        
                              if( $(currentIndex).text() == 0){
                                    string = ""
                              }

                              string += e.key;
                              if(string > 100){
                                    string = 100 
                              }
                              $(currentIndex).text(string)
                              $(currentIndex).addClass('updated')
                              $('#save_button_1').removeAttr('disabled')
                              $('#curText').text(string)
                              var temp_studid = $(currentIndex).attr('data-studid')
                              disabled_filter()
                              calcfg(temp_studid)
                        }
                  
                  }

                  function disabled_filter(){
                        $('#filter_subjects').attr('disabled','disabled')
                        // $('#filter_sy').attr('disabled','disabled')
                        $('#filter_gradelevel').attr('disabled','disabled')
                        $('#filter_section').attr('disabled','disabled')
                        $('#filter_button_1').attr('disabled','disabled')
                        $('.submit_grades').attr('disabled','disabled')
                  }

                  function calcfg(studid){

                        var with_fg = true
                        var temp_sum = parseInt(0)
                        var semid = $('#subject_sem').text()
                        var levelid = $('#subject_levelid').text()

                        if(levelid == 14 || levelid == 15){
                              if(semid == 1){
                                    for(var x=1;x<=2;x++){
                                          $('.studgrades[data-studid="'+studid+'"][data-quarter="'+x+'"]').each(function(a,b){
                                                if($(this).text() == ""){
                                                      with_fg = false
                                                }
                                                temp_sum += parseInt($(this).text())
                                          })
                                    }
                              }else{
                                    for(var x=3;x<=4;x++){
                                          $('.studgrades[data-studid="'+studid+'"][data-quarter="'+x+'"]').each(function(a,b){
                                                if($(this).text() == ""){
                                                      with_fg = false
                                                }
                                                temp_sum += parseInt($(this).text())
                                          })
                                    }
                              }
                              var fg = parseFloat(temp_sum/2).toFixed()
                        }else{

                              var is_sp =  $('.studgrades[data-studid="'+studid+'"][is_sp="true"]').length > 0 ? true : false;

                              if(!is_sp){
                                    $('.studgrades[data-studid="'+studid+'"]').each(function(a,b){
                                          if($(this).text() == ""){
                                                with_fg = false
                                          }
                                          temp_sum += parseInt($(this).text())
                                    })
                                    var fg = parseFloat(temp_sum/4).toFixed()
                              }else{
                                    with_fg = false
                              }

                        
                        }

                  

                  

                        if(with_fg){
                        
                              $('.fg[data-studid="'+studid+'"]').text(fg)
                              $('.actiontaken[data-studid="'+studid+'"]').text(fg >= 75 ? 'PASSED' : 'FAILED')
                              if(fg >= 75){
                                    $('.actiontaken[data-studid="'+studid+'"]').addClass('bg-success')
                                    $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-danger')
                              }else{
                                    $('.actiontaken[data-studid="'+studid+'"]').addClass('bg-danger')
                                    $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-success')
                              }
                        }else{
                              $('.fg[data-studid="'+studid+'"]').text('')
                              $('.actiontaken[data-studid="'+studid+'"]').text('')
                              $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-success')
                              $('.actiontaken[data-studid="'+studid+'"]').removeClass('bg-danger')
                        }

                  }


            })

      </script>


@endsection


