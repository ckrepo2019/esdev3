@extends('studentPortal.layouts.app2')

@section('pagespecificscripts')
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Teacher Evaluation</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">Teacher Evaluation</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content pt-0">
      <div class="container-fluid">
          <div class="row">
			<div class="col-md-12">
                  <div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col-md-12">
									<p class="mb-0 col-md-12">Teacher evaluation is only applicable for IBED Students.</p>
								</div>
							</div>
						</div>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header bg-primary">
                              <i class="fas fa-edit"></i> Subjects
                      </div>
                      <div class="card-body" id="subject_table_holder">
                          <p class="p-0">No records available.</p>
                      </div>
                  </div>
              </div>
              <div class="col-md-12">
                  <div class="card">
                        <div class="card-header bg-primary">
                              <i class="fas fa-edit"></i> EVALUATION
                        </div>
                        <div class="card-body" id="evaluation_table_holder">
						    <p class="p-0">No records available.</p>  
                        </div>
                        
                  </div>
              </div>
              
          </div>
      </div>
</section>

<script>

      $(document).ready(function(){

            var selectedSubject;
            var selectTeacher;
            var canSubmit = 0;
            var teachername = '';
            var subjcdesc = '';

            $(document).on('click','.evalbutton',function(){

                  $('.subj_tr').removeClass('bg-info')

                  selectTeacher = $(this).attr('data-tid')
                  selectedSubject =  $(this).attr('data-subj')

                  teachername = $(this).attr('data-teacher-name');
                  subjcdesc = $(this).attr('data-subjdesc');



                  canSubmit = $(this).attr('data-status')
                  $('#submit_eval').removeAttr('disabled','disabled')
                  load_questions()

                  $('.subj_tr[data-tid="'+selectTeacher+'"][data-subj="'+selectedSubject+'"]').addClass('bg-info')



            })

            function load_questions(){

                  $.ajax({
                        type:'GET',
                        url:'/student/teacherevaluation?questions=questions&teacher='+selectTeacher+'&subject='+selectedSubject,
                        success:function(data) {

                              $('#evaluation_table_holder').empty();
                              $('#evaluation_table_holder').append(data);

                              if(canSubmit == 0){

                                    $('#submit_eval').removeAttr('hidden')

                              }
                              else if(canSubmit == 1){

                                    $('#submit_eval').attr('hidden','hidden')

                              }

                              $('#selected_teacher_box').text(teachername)
                              $('#selected_subject_box').text(subjcdesc)

                        }
                  })   


            }


            $(document).on('click','#submit_eval',function(){

                  var contains_zero = false

                  $('.rating').each(function(index,value){

                        if($(this).val() == 0 && $(this).attr('data-id') != 'comment'){

                              contains_zero = true;

                        }
                  })

                  if(contains_zero){

                        Swal.fire({
                              type: 'warning',
                              title: 'Please complete evaluation!',
                              text: 'Some questions contains "0" values!',
                        })

                  }

                  var valid_comment = true;

                  var string_count = $('.rating[data-id="comment"]').val().length

                  if(string_count > 250){

                        valid_comment = false;
                        Swal.fire({
                              type: 'warning',
                              title: 'Oops...',
                              html:
                                    'Comments and suggestions should not <br>' +
                                    'exceed 250 characters!',
                              footer: 'Character Count: '+string_count
                        })

                        return false;
                  }

                  if(!contains_zero){

                        Swal.fire({
                              title: 'Are you sure?',
                              text: "You won't be able to revert this!",
                              type: 'warning',
                              showCancelButton: true,
                              confirmButtonColor: '#3085d6',
                              cancelButtonColor: '#d33',
                              confirmButtonText: 'Yes, submit it!'
                        }).then((result) => {
                        
                              if (result.value) {


                                    var len = $('.rating').length;
                                    $(this).attr('disabled','disabled')
                                    var proccessCount = 0;

                                    $('.rating').each(function(index,value){

                                          $.ajax({
                                                type:'GET',
                                                url:'/student/teacherevaluation',
                                                data:{
                                                      submit:'submit',
                                                      gsid:$(this).attr('data-id'),
                                                      question:$(this).attr('data-question'),
                                                      rating:$(this).val(),
                                                      headerid:$('#evaluation_table').attr('data-head')
                                                },
                                                success:function(data) {

                                                      proccessCount += 1

                                                      console.log(proccessCount +'-'+len)

                                                      if (proccessCount == len){

                                                            canSubmit = 1;
                                                            load_questions()
                                                            load_subjects()

                                                            Swal.fire({
                                                                  type: 'success',
                                                                  title: 'Successful!',
                                                                  text: 'Teacher Evaluation is submitted successfully!',
                                                            })

                                                      }

                                                }
                                          })  
                                    

                                    })

                              }
                        })

                  }
                 

            })

            load_subjects()

            function load_subjects(){

                  $.ajax({
                        type:'GET',
                        url:'/student/teacherevaluation?subjects=subjects&table=table',
                        success:function(data) {
                              $('#subject_table_holder').empty();
                              $('#subject_table_holder').append(data);
                        }
                  })   
                  
            }
            
          

          


      })


</script>
    
@endsection
