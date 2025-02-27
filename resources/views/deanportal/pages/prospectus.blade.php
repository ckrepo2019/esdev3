
@extends('deanportal.layouts.app2')

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
      </style>
@endsection

@section('content')

      <div class="modal fade" id="curriculumModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                  <div class="modal-header bg-primary">
                              <button class="btn btn-default " data-toggle="modal"  
                              data-target="#curriculumModalForm" 
                              data-widget="chat-pane-toggle"
                              id="createSubject">Create Curriculum</button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">×</span>
                        </button>
                  </div>
                  <div class="modal-body p-0" style="min-height: 400px !important" id="curriculumTable">
                  
                  </div>
            </div>
            </div>
      </div>

      <div class="modal fade" id="curriculumModalForm" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content">
                  <div class="modal-header bg-primary">
                        Curriculum Form
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">×</span>
                  </div>
                  <div class="modal-body">
                        <div class="form-group">
                              <label for="">Curriculum Description</label>
                              <input class="form-control" id="curDesc" name="curDesc">
                        </div>
                  </div>
                  <div class="card-footer">
                        <button class="btn btn-primary" id="savecur">Create</button>
                  </div>
            </div>
            </div>
      </div>

     

      <div class="modal fade" id="nocurrsubj" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h5 class="mb-0">List of subjects with no assigned curriculum</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="text-white">×</span>
                        </div>
                        <div class="modal-body" id="nocurrsubjtableholder">
                        </div>
                        <div class="card-footer">
                             
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="subjectsTable" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header bg-primary">
                              <button class="btn btn-default " data-toggle="modal"  
                              data-target="#subjectModal" 
                              data-widget="chat-pane-toggle"
                              id="createSubject">CREATE SUBJECT</button>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">×</span>
                        </button>
                  </div>
                  <div class="modal-body p-0" style="min-height: 400px !important" id="subjectTableHolder">
                      
                  </div>
                </div>
            </div>
      </div>
      <div class="modal fade" id="select_curriculum_to_add_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="text-white">×</span>
                        </div>
                        <div class="modal-body">
                              <div class="form-group">
                                    <select id="select_curriculum_to_add" class="form-control form-control-sm float-right">
                                          <option value="">SELECT CURRICULUM</option>
                                          @foreach (DB::table('college_curriculum')->where('courseID',$courseInfo->id)->where('deleted',0)->get() as $item)
                                                <option value="{{$item->id}}" @if($item->isactive == 1) selected @endif>{{$item->curriculumname}}</option>
                                          @endforeach
                                    </select>
                              </div>
                        </div>
                        <div class="card-footer">
                              <button class="btn btn-primary" id="add_subject_to_existing_curriculum_savebutton">Save</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="subjectmasterlist" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h5 class="mb-0"></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="text-white">×</span>
                        </div>
                        <div class="modal-body" >
                              <div class="form-group">
                                    <label for="">Subject list</label>
                                    <input type="text" class="form-control" id="searchsubject">
                              </div>
                              <div id="subjectmasterlistholder" class="table-responsive" style="height: 400px">

                              </div>
                           
                        </div>
                      
                  </div>
            </div>
      </div>

      <div class="modal fade" id="prerequisitesubjectlist" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h5 class="mb-0"></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true" class="text-white">×</span>
                        </div>
                        <div class="modal-body" >
                              <div class="form-group">
                                    <label for="">Subject list</label>
                                    <input type="text" class="form-control" id="searchsubject">
                              </div>
                              <div id="prerequisitlistholder" class="table-responsive" style="height: 400px">

                              </div>
                           
                        </div>
                      
                  </div>
            </div>
      </div>
      @foreach($inputArray  as $item)
             @php
                  $inputs = $item[0];
                  $modalInfo = $item[1];
            @endphp
            @include('collegeportal.pages.forms.generalform')
      @endforeach

      <section class="content-header">
            <div class="container-fluid">
            <div class="row">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active"><a href="/dean/courses">Courses</a></li>
            </ol>
            </div>
            </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="row">
                  <div class="col-md-9">
                        <div class="row">
                              <div class="col-md-12">
                                    <div class="card">
                                          <div class="card-header bg-primary">
                                                <div class="row">
                                                      <div class="col-md-4">
                                                            PROSPECTUS
                                                      </div>
                                                      <div class="col-md-3">
                                                            {{-- <button class="btn-block btn btn-sm btn-light float-right" 
                                                            id="addsubject"><b>ADD SUBJECT</b></button> --}}
                                                      </div>
                                                      <div class="col-md-5">
                                                            <select id="curriculum" class="form-control form-control-sm float-right">
                                                                  <option value="">SELECT CURRICULUM</option>
                                                                  @foreach (DB::table('college_curriculum')->where('courseID',$courseInfo->id)->where('deleted',0)->get() as $item)
                                                                        <option value="{{$item->id}}">{{$item->curriculumname}}</option>
                                                                  @endforeach
                                                            </select>
                                                      </div>
                                                   
                                                </div>
                                                
                                               
                                                
                                          </div>
                                          <div class="card-body p-0 table-responsive" style="max-height: 643px" id="prospectustable">
                                                
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class=" card">
                              <div class="card-header bg-primary">
                                    ABOUT COURSE
                              </div>
                              <div class="card-footer">
                                    <label><i class="fa fa-signature mr-1"></i>Course Description</label>
                                    <p class="text-success  pl-4">{{$courseInfo->courseDesc}}</p>
                                    <hr>
                                    <label><i class="fa fa-file-signature mr-1"></i>Course Abbreviation</label>
                                    <p class="text-success  pl-4">{{$courseInfo->courseabrv}}</p>
                                    <hr>
                                    <label><i class="fa fa-object-group mr-1"></i>Section Count</label>
                                    <p class="text-success  pl-4"></p>
                                    <hr>
                                    <label><i class="fa fa-users mr-1"></i>Student Count</label>
                                    <p class="text-success  pl-4"></p>
                                    <hr>
                                    <button class="btn btn-block btn-primary btn-sm" id="viwcurriculum">View Curriculum</button>
                                    <hr>
                                    <button class="btn btn-block btn-primary btn-sm" data-value="1" id="updatepros">View options</button>

                                    {{-- <div id="vncbuttonholder">
                                          <button class="btn btn-block btn-primary" id="viewnocurriculum">View subjects with no curriculum</button>
                                    </div> --}}
                                    
                                    {{-- <button 
                                    class="btn btn-primary btn-block" 
                                    data-toggle="modal"  
                                    id="togglecoursesubjects"
                                    data-target="#subjectsTable"
                                    data-widget="chat-pane-toggle"
                                    
                                    >COURSE SUBJECTS</button> --}}
                              </div>
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')

<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

<script>
      $(document).ready(function(){
            @if ($errors->any())
                  @if($errors->has('modalName'))
                        @if($errors->first('modalName') == 'subjectModal')
                              $('#subjectsTable').modal('show')
                        @endif
                  @else
                        $('#subjectsTable').modal('show')
                  @endif
            @endif

          

                  
            $("select[name='prereq[]']").empty()

          


      })
</script>

<script>

       $(document).ready(function(){

            var selectedCur = 0;
            var prospectusurl = null;
            var selectedsem = null;
            var selectedYear = null;       


            

            $(document).on('click','#updatepros',function(){

                  if($(this).attr('data-value') == 1){

                        $('.multipleaddsubjects').removeAttr('hidden')
                        $('.subjectoptions').removeAttr('hidden')
                        $(this).attr('data-value',2)
                        $(this)[0].innerText = 'Hide options'

                  }
                  else if($(this).attr('data-value') == 2){

                        $('.multipleaddsubjects').attr('hidden','hidden')
                        $('.subjectoptions').attr('hidden','hidden')
                        $(this)[0].innerText = 'View options'
                        $(this).attr('data-value',1)
                  }
                

            })
         

            $(document).on('input','#searchsubject',function(){

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                  if( $('#prerequisitesubjectlist').is(':visible')){

                       var url = '/collegesubjects?table=table&subjid='+$(this).attr('data-id')+'&currid='+$('#curriculum').val()+'&yearid='+selectedYear+'&semID='+selectedsem+'&course='+course+'&search='+$(this).val()+'&status=3'

                  }
                  else{
       
                        var url = '/collegesubjects?table=table&subjid='+$(this).attr('data-id')+'&currid='+$('#curriculum').val()+'&yearid='+selectedYear+'&semID='+selectedsem+'&course='+course+'&search='+$(this).val()
                  }

                  $.ajax({
                        url: url,
                        type: 'GET',
                        success:function(data) {

                              if( $('#prerequisitesubjectlist').is(':visible')){

                                    $('#prerequisitlistholder').empty()
                                    $('#prerequisitlistholder').append(data)
                              }
                              else{
                                    console.log('b')
                                    $('#subjectmasterlistholder').empty()
                                    $('#subjectmasterlistholder').append(data)
                              }

                              
                        
                        }
                  });

            })


            $(document).on('click','.addsubjecttoprospectus',function(){

                  var selectedbutton = $(this)

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                  $.ajax({
                        url: '/collegesubjects?insertoprospectus=insertoprospectus&subjid='+$(this).attr('data-id')+'&currid='+$('#curriculum').val()+'&yearid='+selectedYear+'&semID='+selectedsem+'&course='+course,
                        type: 'GET',
                        success:function(data) {

                              selectedbutton.addClass('btn-danger')
                              selectedbutton.removeClass('btn-success')
                              selectedbutton[0].innerText = 'Remove'
                              selectedbutton.addClass('removesubjectfromprospectus')
                              selectedbutton.removeClass('addsubjecttoprospectus')
                              selectedbutton.attr('data-id',data)
                              loadProspectus()
                              $('.multipleaddsubjects').removeAttr('hidden')
                              $('.subjectoptions').removeAttr('hidden')
                        
                        }
                  });

            })

            $(document).on('click','.removesubjectfromprospectus',function(){

                  var selectedbutton = $(this)

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                  $.ajax( {
                        url: '/collegesubjects?removefromprospectus=removefromprospectus&prosid='+$(this).attr('data-id')+'&currid='+$('#curriculum').val()+'&yearid='+selectedYear+'&semID='+selectedsem+'&course='+course,
                        type: 'GET',
                        success:function(data) {
                              console.log(data)
                              selectedbutton.removeClass('btn-danger')
                              selectedbutton.addClass('btn-success')
                              selectedbutton.attr('data-id',data[0].subjectID)
                              selectedbutton[0].innerText = 'Add'
                              selectedbutton.removeClass('removesubjectfromprospectus')
                              selectedbutton.addClass('addsubjecttoprospectus')
                              loadProspectus()
                              $('.multipleaddsubjects').removeAttr('hidden')
                              $('.subjectoptions').removeAttr('hidden')
                        
                        }
                  });

            })

            $(document).on('click','.multipleaddsubjects',function(){

                  $('#subjectmasterlist').modal('show')         

                  $('#subjectmasterlist .modal-header h5')[0].innerText = 'Add subjects to '+$(this).attr('data-string')

                  selectedsem = $(this).attr('data-sem')
                  selectedYear = $(this).attr('data-year')

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');
                         
                  $.ajax( {
                        url: '/collegesubjects?table=table&course='+course+'&currid='+$('#curriculum').val(),
                        type: 'GET',
                        success:function(data) {

                              $('#subjectmasterlistholder').empty()
                              $('#subjectmasterlistholder').append(data)
                        
                        }
                  });

            })
            

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

            $(document).on('click','#addsubject',function(){

                  if($('#curriculum').val() == ''){

                        Toast.fire({
                              type: 'error',
                              title: 'Please select curriculum!'
                        })

                  }else{

                        $('#prospectusModal').modal('show')

                  }

            })

            $(document).on('click','#add_subject_to_existing_curriculum',function(){

                  if( $('.editSubject').length == 0){

                        Toast.fire({
                              type: 'error',
                              title: 'No available subject!'
                        })

                  }
                  else{

                        $('#select_curriculum_to_add_modal').modal('show')

                  }

            })


            $(document).on('click','#add_subject_to_existing_curriculum_savebutton',function(){

                  if($('#select_curriculum_to_add').val() == ''){

                        Toast.fire({
                              type: 'error',
                              title: 'No curriculum selected!'
                        })

                  }
                  else{

                        $('.editSubject').each(function(){

                              $.ajax( {
                                    url: '/prospectus?transfer=transfer&prosid='+$(this).attr('data-id')+'&currid='+$('#select_curriculum_to_add').val(),
                                    type: 'GET',
                                    success:function(data) {

                                          loadnocurrsubj()
                                    
                                    
                                    }
                              });

                        })

                  }

            })

            
            function loadnocurrsubj(){

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                        $.ajax( {
                              url: '/course/'+course+'/prospectus/table?',
                              type: 'GET',
                              success:function(data) {
                                    $('#nocurrsubjtableholder').empty()
                                    $('#nocurrsubjtableholder').append(data)


                                    if($('.editSubject').length == 0){

                                          $('#add_subject_to_existing_curriculum').remove()

                                    }
                                    else{

                                          $('#nocurrsubj .card-footer').append('<button class="btn btn-primary" id="add_subject_to_existing_curriculum">Add subjects to existing curriculum</button>')

                                    }
                              
                              }
                        });
                  }


            $(document).on('click','#viewnocurriculum',function(){

                  $('#nocurrsubj').modal('show')

                  loadnocurrsubj()

            })

            $(document).on('click','#savecur',function(){

                  if(selectedCur == 0){

                        $.ajax( {
                              url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&insert=insert&curDesc='+$('#curDesc').val(),
                              type: 'GET',
                              success:function(data){

                                    $('#curriculumModalForm').modal('hide')
                                    viewcur()
                                    loadCurSelect()
                              }
                        
                        });

                  }

                  else{

                        $.ajax( {
                              url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&update=update&curDesc='+$('#curDesc').val()+'&curid='+selectedCur,
                              type: 'GET',
                              success:function(data){

                                    selectedCur = 0
                                    viewcur()
                                    $('#curriculumModalForm').modal('hide')
                                    $('#savecur')[0].innerText = 'Create'
                                    loadCurSelect()

                              }
                        
                        });
                  }

                 

                 

            })

            function loadCurSelect(){

                  $.ajax( {
                        url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&info=info',
                        type: 'GET',
                        success:function(data){

                              $('#curriculum').empty()
                              $('#curriculum').append('<option value="">SELECT CURRICULUM</option>')

                              $.each(data,function(a,b){

                                    $('#curriculum').append('<option value="'+b.id+'">'+b.curriculumname+'</option>')

                              })

                        }
                  
                  });

            }

            function loadCurSelect(){

            $.ajax( {
                  url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&info=info',
                  type: 'GET',
                  success:function(data){

                        $('#curriculum').empty()
                        $('#curriculum').append('<option value="">SELECT CURRICULUM</option>')

                        $.each(data,function(a,b){

                              $('#curriculum').append('<option value="'+b.id+'">'+b.curriculumname+'</option>')

                        })

                  }

            });

            }

            $(document).on('click','.editcurriculum',function(){

                  selectedCur = $(this).attr('data-id')
               
                  $('#curriculumModalForm').modal('show')
                  $('#savecur')[0].innerText = 'Update'

                  $.ajax( {
                        url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&info=info&curid='+selectedCur,
                        type: 'GET',
                        success:function(data){
                              $('#curDesc').val(data[0].curriculumname);
                        }

                  });

            })

            $(document).on('click','.removecurriculum',function(){

                  $.ajax( {
                        url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&remove=remove&curid='+$(this).attr('data-id'),
                        type: 'GET',
                        success:function(data){

                              viewcur()

                        }

                  });

            })

            $(document).on('click','.setasactive',function(){
               
                  $.ajax( {
                        url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&setasactive=setasactive&activestatus='+$(this).attr('data-value')+'&curid='+$(this).attr('data-id'),
                        type: 'GET',
                        success:function(data){
                              viewcur()
                        }

                  });

            })

             $(document).on('click','#viwcurriculum',function(){

                  $('#curriculumModal').modal('show')
                  viewcur()
               
             })


            function viewcur(){

                  $.ajax( {
                        url: '/curriculum?courseid='+'{{$courseInfo->id}}'+'&table=table',
                        type: 'GET',
                        success:function(data){
                              $('#curriculumTable').empty()
                              $('#curriculumTable').append(data)
                        }
                       
                  });
            }

             $(document).on('click','.removeSubject',function(){


                  Swal.fire({
                        title: 'Are you sure?',
                        text: "You want to remove subject?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, remove it!'
                        }).then((result) => {

                              if (result.value) {

                                    $.ajax( {
                                          url: '/prospectus/college/delete/'+$(this).attr('data-id'),
                                          type: 'GET',
                                          success:function(){

                                                if($('#nocurrsubj').hasClass('show')){

                                                      loadnocurrsubj()

                                                }
                                                else{
                                                      loadProspectus()
                                                      Swal.fire({
                                                            type: 'error',
                                                            title: 'Deleted successfully!',
                                                            showConfirmButton: false,
                                                            timer: 1500
                                                      })
                                                }
                                          
                                          }
                                    
                                    });

                              }
                              
                        })

             })

             $(document).on('change','#curriculum',function(){

                  $('.multipleaddsubjects').attr('hidden','hidden')
                  $('.subjectoptions').attr('hidden','hidden')

                  $('#updatepros')[0].innerText = 'View options'
                  $('#updatepros').attr('data-value',1)

                   
                   if($(this).val() != ''){



                        loadProspectus()

                        $('#vncbuttonholder').empty();
                        $("select[name='prereq[]']").empty()
                       
                        var course = '{{$courseInfo->courseDesc}}'
                        course = course.toLowerCase().replace(/\s+/g, '-');

                        $.ajax( {
                              url: '/prospectus?info=info&currid='+$('#curriculum').val()+'&course='+course,
                              type: 'GET',
                              success:function(data) {

                                    $.each(data,function(a,b){
                                          $("select[name='prereq[]']").append('<option value="'+b.id+'">'+b.subjDesc+'</option>')
                                    })
                               
                                    $(function () {
                                          $('.select2').select2({
                                                theme: 'bootstrap4'
                                          })
                                    })

                                 
                              
                              }
                        });

                       

                   }
                   else{

                         $('#vncbuttonholder').append('<button class="btn btn-block btn-primary" id="viewnocurriculum">View subjects with no curriculum</button>')
                         $('#prospectustable').empty()

                        
                   }               

            })

            

            function loadProspectus(){


                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                  $.ajax( {
                        url: '/course/'+course+'/prospectus/table?curriculum='+$('#curriculum').val(),
                        type: 'GET',
                        success:function(data) {
                              
                              $('#prospectustable').empty();
                              $('#prospectustable').append(data);

                             

                              if($('#updatepros').attr('data-value') == 1){
                                    $('.multipleaddsubjects').attr('hidden','hidden')
                                    $('.subjectoptions').attr('hidden','hidden')
                              }
                              else if($('#updatepros').attr('data-value') == 2){
                                    $('.multipleaddsubjects').removeAttr('hidden')
                                    $('.subjectoptions').removeAttr('hidden')
                              }
                              
                        }
                  });

                  $('.savebutton').removeAttr('onclick')
                  $('.savebutton').removeAttr('type')

            }

      
            var type;
            var action;
            var selectsubj;

            $(document).on('click','.addprereq',function(a){

                  $('#prerequisitesubjectlist').modal('show');
                  selectsubj = $(this).attr('data-id')

                  $('#prerequisitesubjectlist h5')[0].innerText = 'Add prerequisite to '+$(this).attr('data-string')

                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');

                  $.ajax( {
                        url: '/collegesubjects?table=table&course='+course+'&currid='+$('#curriculum').val()+'&status=3&cursubjid='+selectsubj,
                        type: 'GET',
                        success:function(data) {

                              $('#prerequisitlistholder').empty()
                              $('#prerequisitlistholder').append(data)
                        
                        }
                  });
            })



            

            $(document).on('click','.addtoprereq',function(a){

                  selectedbutton = $(this)

                  $.ajax( {
                        url: '/collegesubjects?addtopreq=addtopreq&subjid='+$(this).attr('data-subjid')+'&cursubjid='+selectsubj,
                        type: 'GET',
                        success:function(data) {

                              selectedbutton.addClass('btn-danger')
                              selectedbutton.removeClass('btn-success')
                              selectedbutton[0].innerText = 'Remove'
                              selectedbutton.addClass('removetoprereq')
                              selectedbutton.removeClass('addtoprereq')
                              selectedbutton.attr('data-prereqid',data[0].id)
                              loadProspectus()
                              $('.multipleaddsubjects').removeAttr('hidden')
                              $('.subjectoptions').removeAttr('hidden')
                        
                        }
                  });
            })

            
            $(document).on('click','.removetoprereq',function(a){

                  selectedbutton = $(this)

                  $.ajax( {
                        url: '/collegesubjects?removetoprereq=removetoprereq&subjid='+$(this).attr('data-subjid')+'&cursubjid='+selectsubj+'&prereqid='+$(this).attr('data-prereqid'),
                        type: 'GET',
                        success:function(data) {

                              selectedbutton.removeClass('btn-danger')
                              selectedbutton.addClass('btn-success')
                              selectedbutton[0].innerText = 'Add'
                              selectedbutton.removeClass('removetoprereq')
                              selectedbutton.addClass('addtoprereq')
                              loadProspectus()
                              $('.multipleaddsubjects').removeAttr('hidden')
                              $('.subjectoptions').removeAttr('hidden')
                        
                        }
                  });
            })
            


            $(document).on('click','.editSubject',function(a){

                  prospectusurl = '/prospectus/college/update/'+$(this).attr('data-id');

                  $('#prospectusModal').modal();

                  $('.savebutton').text('UPDATE')
                  $('.savebutton').removeClass('btn-primary')
                  $('.savebutton').addClass('btn-success')
                  $('.savebutton').removeAttr('onclick')
                  $('.savebutton').removeAttr('type')
                  
                  var course = '{{$courseInfo->courseDesc}}'
                  course = course.toLowerCase().replace(/\s+/g, '-');
                  
                  $.ajax( {
                        url: '/course/'+course+'/prospectus/subject/'+$(this).attr('data-class')+'?curriculum='+$('#curriculum').val(),
                        type: 'GET',
                        success:function(data) {
                              $.each(data[0],function(a,b){
                                    $('.'+a).val(b).change()
                              })
                          
                        }
                  });

            })

            $('#prospectusModal').on('hidden.bs.modal', function () {

                  $('.is-invalid').removeClass('is-invalid')
                  $('#prospectusModalForm')[0].reset();
                  // originalButtonForm()

            })

            function originalButtonForm(){

                  $('.savebutton').text('CREATE')
                  $('.savebutton').removeClass('btn-success')
                  $('.savebutton').addClass('btn-primary')
                  $('.savebutton').attr('onclick','this.form.submit(); this.disabled=true; ')
                  $('.savebutton').attr('type','submit')

            }

            $( '#prospectusModalForm .savebutton' ).removeAttr('onclick')

            $( '#prospectusModalForm' )
                  .submit( function( e ) {

                        if(prospectusurl == null){

                              prospectusurl = '/dean/store/prospectus'

                        }
                    
                        var course = '{{$courseInfo->courseDesc}}'
                        course = course.toLowerCase().replace(/\s+/g, '-');

                        $.ajax( {
                              url: prospectusurl,
                              type: 'GET',
                              data: {
                                    'yearID':$('#yearID').val(),
                                    'semesterID':$('#semesterID').val(),
                                    'subjDesc':$('#subjDesc').val(),
                                    'subjCode':$('#subjCode').val(),
                                    'prereq':$('#prereq').val(),
                                    'subjClass':$('#subjClass').val(),
                                    'lecunits':$('#lecunits').val(),
                                    'labunits':$('#labunits').val(),
                                    'curriculumID':$('#curriculum').val(),
                                    'courseDesc':course,
                              },
                              success:function(data) {

                                    if(data[0].status == 0){

                                          $.each(data[0].errors,function(a,b){
                                                $('#'+a).addClass('is-invalid')
                                                $('#'+a+'Error').removeAttr('hidden')
                                                $('#'+a+'Error strong').text(b[0])
                                          })

                                    }
                                    else if(data[0].status == 1){

                                          $('#prospectusModal').modal('hide')

                                          loadProspectus()

                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated successfully!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })

                                    }

                                    else if(data[0].status == 2){

                                          $('#prospectusModal').modal('hide')

                                          loadProspectus()

                                          Swal.fire({
                                                type: 'success',
                                                title: 'Updated successfully!',
                                                showConfirmButton: false,
                                                timer: 1500
                                          })

                                    }


                                    prospectusurl = null
                                    
                              }
                        });

                        e.preventDefault();
                  })


       })
</script>

<script>
      $(function () {
            $('.select2').select2({
                  theme: 'bootstrap4'
            })
      })
</script>


    
@endsection



