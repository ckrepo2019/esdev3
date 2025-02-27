<div class="row">
      <div class="col-md-12">
            <h5>Grading System : {{$grading_system[0]->description}}</h5> 
      </div>
</div>
<hr>
<div class="row">
      <div class="col-md-4 form-group">
            <label for="">Sections</label>
            <select name="" id="section_list" class="select2 form-control">
                  <option value="">All</option>
                  @foreach ($sections  as $item)
                        <option value="{{$item->id}}">{{$item->sectionname}}</option>
                  @endforeach
            </select>
      </div>
      <div class="col-md-4 form-group">
            <label for="">Select Student</label>
            <select name="" id="student_test" class="select2 form-control">
                  <option value="">Select Student</option>
                  @foreach ($students  as $item)
                        <option value="{{$item->id}}">{{$item->lastname.', '.$item->firstname}}</option>
                  @endforeach
            </select>
      </div>
</div>
<div class="row">
      <div class="col-md-4">
            <button class="btn btn-primary btn-block" id="eval_student_grade"><i class="fas fa-sync-alt"></i> EVALUATE STUDENT GRADE</button>
      </div>
      <div class="col-md-4" hidden id="generate_grade_holder">
            <button class="btn btn-secondary btn-block" id="generate_student_grade_detail"><i class="fas fa-sync-alt"></i> GENERATE STUDENT GRADE DETAIL</button>
      </div>
</div>
<div class="row mt-5">
      <div class="col-md-12 table-responsive" style="height: 450px" id="pstable_holder">

      </div>
</div>
<div class="row mt-5">
      <div class="col-md-12">
            <button class="btn btn-primary" id="save_grades" hidden>
                  <i class="fas fa-save"></i> SAVE GRADES
            </button>
      </div>
</div>


<script>

      $(document).ready(function(){

            $('.select2').select2()

            var t_s_gsid = '{{$grading_system[0]->id}}'
            var firstIndex = 0;
            var lastIndex = 0;
            var checkedGrades =  01;
            var saveCount = 0;
            var unSavedCount = 0;
            var proccessCount =  0;
            var checkedCount = 0;
            var url = '/gradestudent/preschool'

            $(document).on('change','input[data-type="checkbox"]',function(){

                  $(this).addClass('checked_grade')

                  if($(this).prop('checked') == true){
                        $(this).attr('value',1)
                  }
                  else{
                        $(this).attr('value',0)
                  }

            })

            $(document).on('change','select[data-type="select"]',function(){

                  $(this).addClass('checked_grade')

            })

            function loadGradesDetail(){

                  $.ajax({
                        type:'GET',
                        url:url,
                        data:{
                              evaluate:'evaluate',
                              studid: selectedStudent,
                              // acadprog:acadprog,
                              gsid:t_s_gsid
                        },
                        success:function(data) {
                              
                              if(data == 0){

                                    Swal.fire({
                                          type: 'error',
                                          title: 'No grades detail!',
                                          text:'Student grades detail is not yet generated',
                                    });

                                    $('#generate_grade_holder').removeAttr('hidden')
                                    $('#pstable_holder').empty()
                                    $('#save_grades').attr('hidden','hidden')

                              }
                              else{

                                    $('#pstable_holder').empty()
                                    $('#pstable_holder').append(data)
                                    $('#save_grades').removeAttr('hidden')

                              }

                        }
                  })

            }

            function submitGrades(){

                  var counter = 0;

                  $('.checked_grade').slice(firstIndex,lastIndex).each(function(){

                        var value = $(this).val();
                        var gradeid = $(this).attr('data-id')
                        var gardequarter = $(this).attr('data-quarter')
                        var selectInfo = $(this)

                        $.ajax({
                              type:'GET',
                              url:url,
                              data:{
                                    submit:'submit',
                                    studid: selectedStudent,
                                    gradeid: gradeid,
                                    value:value,
                                    gardequarter: gardequarter,
                                    // acadprog: acadprog
                              },
                              success:function(data) {

                                    if(data == 1){

                                          saveCount += 1
                                          $('#save_count').text(saveCount)
                                    
                                    }
                                    else if(data == 0){

                                          unSavedCount += 1
                                          $('#not_saved_count').text(unSavedCount)

                                    }

                                    counter += 1;
                                    proccessCount += 1;

                                    if(counter == 9 && checkedGrades != 0){

                                          firstIndex  += 10;
                                          lastIndex += 10;
                                          checkedGrades -= 1
                                          submitGrades()
                                    }

                                    if(  checkedCount  == proccessCount){

                                          $('#proccess_count_modal .modal-title').text('Complete')
                                          $('#proccess_done').removeAttr('hidden')
                                          $('.checked_grade').removeClass('checked_grade')
                                          $('#save_grades').removeAttr('disabled')
                                          loadGradesDetail()

                                    }

                                    $('#proccess_count').text(proccessCount+' / '+checkedCount)

                              }
                        })
                  })
            }

            $(document).on('click','#eval_student_grade',function(){

                  $('#generate_grade_holder').attr('hidden','hidden')

                  selectedStudent = $('#student_test').val()

                  if(selectedStudent == ''){

                        Swal.fire({
                              type: 'error',
                              title: 'No Student Selected!',
                              text:'Please select student',
                        });

                  }
                  else{

                        loadGradesDetail()
                  
                  }
            })


            $(document).on('click','#generate_student_grade_detail',function(){

                  if(selectedStudent == ''){
                        Swal.fire({
                              type: 'error',
                              title: 'No Student Selected!',
                              text:'Please select student',
s                        });
                  }
                  else{
                        $.ajax({
                              type:'GET',
                              url:url,
                              data:{
                                    generate:'generate',
                                    studid: selectedStudent,
                                    gsid:t_s_gsid
                              },
                              success:function(data) {
                                    Swal.fire({
                                          type: 'success',
                                          title: 'Generated Successfully!',
                                          text: data + ' detail(s) generated',
                                    });
                                    $('#generate_grade_holder').attr('hidden','hidden')
                                    loadGradesDetail()
                              }
                        })
                  }
            })

            $(document).on('click','#save_grades',function(){

                  firstIndex = 0;
                  lastIndex = 10;
                  checkedGrades =  parseInt( $('.checked_grade').length / 10 )  + 1;
                  saveCount = 0;
                  unSavedCount = 0;
                  proccessCount =  0;
                  checkedCount = $('.checked_grade').length;

                  if(checkedCount == 0){

                        Swal.fire({
                              type: 'info',
                              title: 'No changes made!',
                        });
                  }else{
                        
                        $('#proccess_count_modal .modal-title').text('Processing ...')
                        $('#proccess_done').attr('hidden','hidden')
                        $('#proccess_count_modal').modal()
                        $('#save_count').text(saveCount)
                        $('#not_saved_count').text(unSavedCount)
                        $('#proccess_count').text(proccessCount)
                        $('#save_grades').attr('disabled','disabled')
                        submitGrades()

                  }
                  
            })


      })
</script>
