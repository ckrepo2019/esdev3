

@php
    
      if(auth()->user()->type == 14){

            $extendsetup = 'deanportal.layouts.app2';
            
      }
      elseif(auth()->user()->type == 16){

            $extendsetup = 'chairpersonportal.layouts.app2';

      }

@endphp

@extends($extendsetup)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
            .dropdown-toggle::after {
                  display: none;
                  margin-left: .255em;
                  vertical-align: .255em;
                  content: "";
                  border-top: .3em solid;
                  border-right: .3em solid transparent;
                  border-bottom: 0;
                  border-left: .3em solid transparent;
            }

            #prospectus_guide_table_holder table{
                  border-bottom: 0px !important;
            }

            .select2-container .select2-selection--single {
            height: 40px;
      }     
      </style>
      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
@endsection

@section('content')

      <div class="modal fade" id="courseModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title">PLEASE SPECIFY STUDENT COURSE</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <form id="updateStudentCourse" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                              <label>SELECT COURSE</label>
                              <select id="course" name="course" class="select2 form-control" required>
                                    @foreach (DB::table('college_courses')->where('deleted','0')->get() as $item)
                                          <option value="{{$item->id}}">{{$item->courseDesc}}</option>
                                    @endforeach
                              </select>
                        </div>
                        <div class="modal-footer justify-content-between">
                              <button type="submit" class="btn btn-primary">SUBMIT</button>
                        </div>
                  </form>
            </div>
            </div>
      </div>

      <div class="modal fade" id="torModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title">STUDENT TOR</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
            
                  <div class="modal-body p-0 table-responsive" id="tor_table_holder" style="height: 500px">
                  
                  </div>
                  <div class="modal-footer justify-content-between">
                        {{-- <button type="button" class="btn btn-primary" id="gen_tor">VIEW PROSPECTUS</button> --}}
                  </div>
            
            </div>
            </div>
      </div>

      <div class="modal fade" id="prospectusModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                  <h5 class="modal-title">PROSPECTUS GUIDE</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
            
                  <div class="modal-body table-responsive p-0" id="prospectus_guide_table_holder" style="height: 500px">
                  
                  </div>
                  <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" id="select_curriculum">CREATE STUDENT TRANSCRIPT</button>
                  </div>
            
            </div>
            </div>
      </div>
     
      <div class="modal fade" id="curriculumModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">PLEASE SPECIFY CURRICULUM</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <div class="modal-body">
                        <div class="from-group">
                              <label for="">CURRICULUM</label>
                              <select class="form-control select2" name="currid" id="currid"></select>
                        </div>
                  </div>
                  <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="submit_student_tor">CREATE STUDENT TRANSCRIPT</button>
                  </div>
            
            </div>
            </div>
      </div>

      <div class="modal fade" id="gradesModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header">
                        <h5 class="modal-title">UPDATE STUDENT GRADE</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                  </div>
                  <form id="gradeForm">
                        <div class="modal-body">
                              <div class="form-group">
                                    <label for="">SUBJECT</label>
                                    <input class="form-control" name="subject" id="subject" readonly>
                              </div>
                              <div class="form-group">
                                    <label for="">Midterm Grade</label>
                                    <input class="form-control" type="text" name="midtermgrade" id="midtermgrade"   data-type="currency" placeholder="00.00">
                              </div>
                              <div class="form-group">
                                    <label for="">Final Grade</label>
                                    <input class="form-control" type="text" name="finalgrade" id="finalgrade"   data-type="currency" placeholder="00.00">
                              </div>
                              <div class="form-group">
                                    <label for="">REMARKS</label>
                                    <select class="form-control" id="remarks" name="remarks">
                                          <option value="0">SELECT REMARKS</option>
                                          <option value="1">PASSED</option>
                                          <option value="2">FAILED</option>
                                    </select>
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button type="button" class="btn btn-primary" id="submit_student_grade">SUBMIT GRADE</button>
                        </div>
                  </form>
            
            </div>
            </div>
      </div>

      <section class="content">
            <div class="card">
                  <div class="card-header bg-primary">
                        <span class="text-white" style="font-size: 20px"><b><i class="nav-icon fa fa-door-open"></i> 
                              @if($location == 'masterlist')
                                    COLLEGE STUDENT MASTERLIST
                              @elseif($location == 'enrolled')
                                    ENROLLED COLLEGE STUDENT'S
                              @elseif($location == 'pre-enrolled')
                                    PRE-ENROLLED COLLEGE STUDENT'S
                              @endif
                        </b>
                        </span>
                      
                        <div class="input-group input-group-sm float-right w-25 search">
                          <input type="text" id="search" name="search" class="form-control float-right" placeholder="Search" >
                          <div class="input-group-append">
                              <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                          </div>
                        </div>
                        @if($location == 'masterlist')
                              <select name="pre-enrolled" id="pre-enrolled" class="float-right form-control form-control-sm col-md-2 mr-2">
                                    <option value="" selected>Student Status</option>
                                    <option value="1">Enrolled</option>
                                    <option value="2">Pre-enrolled</option>
                              </select>
                        @endif
                  </div>
                  <div class="card-body p-0" id="college_student_table_holder">
                        @include('chairpersonportal.pages.scheduling.collegestudenttable')
                  </div>
                  <div class="card-footer">
                        <div class="" id="data-container">
                        </div>
                  </div> 
            </div>
      </section>
@endsection

@section('footerjavascript')

      <script src="{{asset('js/pagination.js')}}"></script> 
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script>
          $(document).ready(function(){

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });


            $('.select2').select2()

            var preenrolled = null;
            var pageNum = 1;


            processpaginate(1,10,null,true)

            $(document).on('change','#pre-enrolled',function(){

                  preenrolled = $(this).val();
                  pageNum = 1
                  processpaginate(0,10,$('#search').val(),null)

            })

            function processpaginate(skip = null,take = null ,search = null, firstload = true){

                  url = '/collegstudents?take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&location='+'{{$location}}'+'&pre-enrolled='+preenrolled;

                  $.ajax({
                        type:'GET',
                        url: url,
                        success:function(data) {
                              $('#college_student_table_holder').empty();
                              $('#college_student_table_holder').append(data);
                              pagination($('#searchCount').val(),false)
                        
                        }
                  })

            }

     

            function pagination(itemCount,pagetype){

                  var result = [];

                  for (var i = 0; i < itemCount; i++) {
                        result.push(i);
                  }

                  $('#data-container').pagination({
                        dataSource: result,
                        hideWhenLessThanOnePage: true,
                        pageNumber: pageNum,
                        pageRange: 1,
                        callback: function(data, pagination) {

                                    if(pagetype){

                                          processpaginate(pagination.pageNumber,10,$('#search').val(),false)
                                          
                                    }

                                    pageNum = pagination.pageNumber
                                    pagetype=true
                              }
                        })
            }

            $(document).on('keyup','#search',function() {
                  pageNum = 1
                  processpaginate(0,10,$('#search').val(),null)
                  
            });


            function formatNumber(n) {
                  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
            }

            $("input[data-type='currency']").on({
                  keyup: function() {
                        formatCurrency($(this));
                  },
                  blur: function() { 
                        formatCurrency($(this), "blur");
                  }
            });

            function formatCurrency(input, blur) {
                  var input_val = input.val();
                  if (input_val === "") { return; }
                  var original_len = input_val.length;
                  var caret_pos = input.prop("selectionStart");
                  if (input_val.indexOf(".") >= 0) {
                  var decimal_pos = input_val.indexOf(".");
                  var left_side = input_val.substring(0, decimal_pos);
                  var right_side = input_val.substring(decimal_pos);
                  left_side = formatNumber(left_side);
                  right_side = formatNumber(right_side);
                  if (blur === "blur") {
                        right_side += "00";
                  }
                  right_side = right_side.substring(0, 2);
                  input_val =  left_side + "." + right_side;
                  } else {
                  input_val = formatNumber(input_val);
                  input_val = input_val;
                  if (blur === "blur") {
                        input_val += ".00";
                  }
                  }
                  input.val(input_val);
                  var updated_len = input_val.length;
                  caret_pos = updated_len - original_len + caret_pos;
                  input[0].setSelectionRange(caret_pos, caret_pos);
                  }

           

            var studentid = null;
            var studentCourse;
            var studentCourseString;

            $(document).on('click','.studentinfo',function(){

                  studentid = $(this).attr('data-id')
                  
               
            })

            $(document).on('click','.change_student_course',function(){

                  studentid = $(this).attr('data-id')


            })
            
             $(document).on('click','#gen_tor',function(){

                  $('#prospectusModal').modal('show')
                  loadcurriculum()

            })

            function loadcurriculum(){
                  
                  $.ajax({
                        url: '/curriculum?info=info&courseid='+studentCourse,
                        type: 'GET',
                        success:function(data) {
                              
                             $.each(data,function(a,b){

                                    if(b.isactive == 1){
                                         
                                          loadProspectus(b.id)
                                          $('#currid').append('<option style="color:green" value="'+b.id+'">'+b.curriculumname+'</option>')


                                    }
                                    else{
                                          $('#currid').append('<option style="color:red" value="'+b.id+'">'+b.curriculumname+'</option>')
                                    }

                                   
                             })
                              
                        }
                  });


                 
                  $('.savebutton').removeAttr('onclick')
                  $('.savebutton').removeAttr('type')

            }


            function loadProspectus(curriculum){

                  $.ajax({
                        url: '/course/'+studentCourseString+'/prospectus/table?curriculum='+curriculum,
                        type: 'GET',
                        success:function(data) {

                              $('#prospectus_guide_table_holder').empty()
                              $('#prospectus_guide_table_holder').append(data)
                       
                              $('#prospectusModal .multipleaddsubjects').remove()
                              $('#prospectusModal .subjectoptions').remove()
                        }
                  });

            }


            $(document).on('click','#select_curriculum',function(){

                  $('#curriculumModal').modal('show')

            })

            
            $(document).on('click','#submit_student_tor',function(){

                  $.ajax({
                        url: '/studenttor?cstor=cstor&student='+studentid+'&currid='+$('#currid').val(),
                        type: 'GET',
                        success:function(data) {

                              $('#curriculumModal').modal('hide')
                              $('#prospectusModal').modal('hide')

                              showStudentProspectus(studentid)
                        }
                  });

              

            })

           

            $(document).on('click','.view_student_TOR',function(){

                  $('#torModal').modal('show')

                  studentid = $(this).attr('data-id')
                  studentCourse = $(this).attr('data-course')
                  studentCourseString = $(this).attr('data-course-string')

                  showStudentProspectus($(this).attr('data-id'))

            })

            var selectStudentSubj;

            $(document).on('click','.updateGrade',function(){

                  selectStudentSubj = $(this).attr('data-id')

                  $('#gradesModal').modal('show')
                  // $('#gradesModal h5')[0].innerText = 'Update grade for '+$(this).attr('data-string')
                  $('#subject').val($(this).attr('data-string'))

            })


            $(document).on('click','#submit_student_grade',function(){

                  $.ajax({
                        url: '/studenttor?student='+studentid+'&updategrade=updategrade'+'&finalgrade='+$('#finalgrade').val()+'&midtermgrade='+$('#midtermgrade').val()+'&studprosid='+selectStudentSubj+'&remarks='+$('#remarks').val(),
                        type: 'GET',
                        processData: false,
                        contentType: false,
                        success:function(data) {
                              $('#gradeForm')[0].reset()
                              $('#gradesModal').modal('hide')
                              Toast.fire({
                                    type: 'success',
                                    title: 'Successful!'
                              })
                              showStudentProspectus(studentid)

                             
                              
                        },
                  });

            })



            

            function showStudentProspectus(student){

                  $.ajax({
                        url: '/studenttor?student='+student,
                        type: 'GET',
                        processData: false,
                        contentType: false,
                        success:function(data) {

                              $('#tor_table_holder').empty();
                              $('#tor_table_holder').append(data);

                              $('#tor_table_holder .multipleaddsubjects').remove()
                              $('#tor_table_holder .subjectoptions').remove()

                              $('#torModal .modal-footer').empty()

                              if( $('#notorid').attr('data-id') == 0){

                                    $('#torModal .modal-footer').empty()

                              }else{

                                    $('#torModal .modal-footer').append('<button type="button" class="btn btn-primary" id="gen_tor">VIEW PROSPECTUS</button>')

                              }
                             
                              
                        },
                  });

            }

            $( '#updateStudentCourse' )
                  .submit( function( e ) {

                        console.log($('#course').val())

                        $.ajax( {
                              url: '/chairperson/update/student/course/'+studentid+'/'+$('#course').val(),
                              type: 'GET',
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    $('#courseModal').modal('hide')

                                    Swal.fire({
                                          type: 'success',
                                          title: 'Course Update Successfully!',
                                          showConfirmButton: false,
                                          timer: 1500
                                    })

                                    processpaginate(0,10,$('#search').val(),null)
                                    
                              },
                        } );
                        e.preventDefault();
            } );
          })
    </script>
@endsection



