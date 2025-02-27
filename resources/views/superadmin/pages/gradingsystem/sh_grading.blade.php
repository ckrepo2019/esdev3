<div class="row">
      <div class="col-md-12">
            <h5>Grading System : <span id="gs_desc"></span></h5> 
      </div>
</div>

<hr>
<div class="row">
      <div class="col-md-4 form-group">
            <label for="">Section</label>
            <select name="" id="t_s_section" class="form-control select2">
                  <option value="">Select a section</option>
                  @foreach ($sections as $item)
                        <option value="{{$item->id}}">{{$item->sectionname}}</option>
                  @endforeach
            </select>
      </div>

      <div class="col-md-4 form-group">
            <label for="">Subject</label>
            <select name="" id="t_s_subject" class="form-control select2">
                  <option value="">Select a subject</option>
            </select>
      </div>
      <div class="col-md-4 form-group">
            <label for="">Quarter</label>
            <select name="" id="t_s_quarter" class="form-control select2">
                  <option value="">Select Quarter</option>
                  @if($semester == 1)
                        <option value="1">1st Quarter</option>
                        <option value="2">2nd Quarter</option>
                  @endif
                  @if($semester == 2)
                        <option value="3">3rd Quarter</option>
                        <option value="4">4th Quarter</option>
                  @endif
            </select>
      </div>
</div>



<div class="row">
      <div class="col-md-4">
            <button class="btn btn-primary btn-block" id="gs_e_section_g"><i class="fas fa-sync-alt"></i> EVALUATE SECTION GRADE</button>
      </div>
      <div class="col-md-4" id="g_gsd_h" hidden>
            <button class="btn btn-secondary btn-block" id="g_gsd_b"><i class="fas fa-sync-alt"></i> GENERATE GRADE DETAILS ( <span id="no_gs_count"></span> )</button>
      </div>
</div>
<div class="row mt-5">
      <div class="col-md-12" id="pstable_holder">

      </div>
</div>
<div class="row mt-5">
      <div class="col-md-12">
            <button class="btn btn-primary" id="save_grades_hs" hidden>
                  <i class="fas fa-save"></i> SAVE GRADES
            </button>
      </div>
</div>
      <script>

            $(document).ready(function(){

                  var subjects = []

                  @foreach($subjects as $item)

                        subjects.push({
                              'subjcode':'{{$item->subjcode}}',
                              'subjid':'{{$item->subjid}}',
                              'sectionid':'{{$item->id}}'
                        })

                  @endforeach

                  $('.select2').select2()
                  
                  const Toast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 3000
                        });
                        
                  var selectedStudent
                  var t_s_gsid = null
                  var section
                  var selected_subject
                  var evalaction
                  var quarter
                  var firstIndex = 0;
                  var lastIndex = 10;
                  var itemCount = 0;
                  var itemCountTotal = 0;
                  var successCount = 0;
                  var failedCount = 0;
                  var proccessCount = 0;
                  var url = '/reportcard/grades/seniorhigh';

                  function loadGradesDetail(){

                        $.ajax({
                              type:'GET',
                              url:url,
                              data:{
                                    evaluate:'evaluate',
                                    studid: selectedStudent,
                                    section:section,
                                    subject:selected_subject,
                                    evalaction:evalaction,
                                    quarter:quarter,
                                    gsid:t_s_gsid

                              },
                              success:function(data) {

                                    $('#pstable_holder').empty()
                                    
                                    if(data[0].status == 0){
                                          Swal.fire({
                                                type: 'info',
                                                text:data[0].data,

                                          });
                                    }
                                    else if(data == 0){
                                          Swal.fire({
                                                type: 'error',
                                                title: 'No grades detail!',
                                                text:'Student grades detail is not yet generated',
                                                showConfirmButton: false,
                                                timer: 1500
                                          });
                                    }
                                    else{

                                          $('#pstable_holder').empty()
                                          $('#pstable_holder').append(data)
                                          $('#save_grades_hs').removeAttr('hidden')
                                          t_s_gsid = $('.gradestable').attr('data-id')
                                          $('#gs_desc').text($('.gradestable').attr('data-desc'))

                                    }

                              }
                        })

                  }

                  function generateGradeDetail(){
                  
                        $('.studtr[data-gs="0"]').slice(firstIndex,lastIndex).each(function(){

                              counter= 0
                              selectedStudent = $(this).attr('data-id')
                              var trgsid = $(this).attr('data-gsid')

                              $.ajax({
                                    type:'GET',
                                    url:url,
                                    data:{
                                          generate:'generate',
                                          studid: selectedStudent,
                                          section:section,
                                          subject:selected_subject,
                                          quarter:quarter,
                                          gsid:trgsid

                                    },
                                    success:function(data) {
                                          
                                          if(data > 0){
                                          
                                                successCount += 1
                                                $('#save_count').text(successCount)
                                          }
                                          else{

                                                failedCount += 1
                                                $('#not_saved_count').text(failedCount)
                                          }

                                          counter += 1;
                                          proccessCount += 1;

                                          if(counter == 9 && itemCount != 0){

                                                firstIndex  += 10;
                                                lastIndex += 10;
                                                itemCount -= 1
                                                generateGradeDetail()


                                          }

                                          if(  proccessCount  == itemCountTotal){

                                                $('#proccess_count_modal .modal-title').text('Complete')
                                                $('#proccess_done').removeAttr('hidden')
                                                $('.checked_grade').removeClass('checked_grade')
                                                loadGradesDetail()

                                          }

                                          $('#proccess_count').text(proccessCount + ' / ' + itemCountTotal)
                                    }
                              })

                        })
                  
                  }



                  // function 
                  function save_gs_student_grades(){

                        $('.ge').slice(firstIndex,lastIndex).each(function(){

                              counter= 0
                              var gradeid = $(this).attr('data-id')
                              var value = $(this).text()
                              var field = $(this).attr('data-field')
                              var studid = $(this).attr('data-studid')

                              $.ajax({
                                    type:'GET',
                                    url:url,
                                    data:{
                                                submit:'submit',
                                                value: selectedStudent,
                                                gradeid:gradeid,
                                                // acadprog:acadprog,
                                                value:value,
                                                studid:studid,
                                                field:field,
                                                gsid:t_s_gsid
                                    },
                                    success:function(data) {
                                          
                                          if(data > 0){
                                          
                                                successCount += 1
                                                $('#save_count').text(successCount)
                                          }
                                          else{

                                                failedCount += 1
                                                $('#not_saved_count').text(failedCount)
                                          }

                                          counter += 1;
                                          proccessCount += 1;

                                          if(counter == 9 && itemCount != 0){

                                                firstIndex  += 10;
                                                lastIndex += 10;
                                                itemCount -= 1
                                                save_gs_student_grades()

                                          }

                                          $('#proccess_count').text(proccessCount + ' / ' + itemCountTotal)

                                          if(  proccessCount  == itemCountTotal){

                                                $('#proccess_count_modal .modal-title').text('Complete')
                                                $('#proccess_done').removeAttr('hidden')
                                                $('.ge').removeClass('ge')
                                                itemCountTotal = 0
                                                $('#save_grades_hs').removeAttr('disabled')
                                                loadGradesDetail()

                                          }

                                          
                                    }
                              })

                        })
                  
                  }

                  $(document).on('change','#t_s_section',function(){

                        var sect = $(this).val()

                        $('#t_s_subject').empty()
                        $('#t_s_subject').append('<option value="">Select a subject</option')

                        $.each(subjects,function(a,b){

                              if(b.sectionid == sect){

                                    $('#t_s_subject').append('<option value="'+b.subjid+'">'+b.subjcode+'</option')


                              }
                        })

                  })

                  $('#g_gsd_b').unbind().click(function(){

                        itemCount = parseInt( $('.studtr[data-gs="0"]').length / 10 )  + 1;
                        itemCountTotal = $('.studtr[data-gs="0"]').length

                        firstIndex = 0;
                        lastIndex = 10;
                        proccessCount = 0
                        successCount = 0;
                        failedCount = 0
                        $('#proccess_done').attr('hidden','hidden')

                        $('#save_count').text(successCount)
                        $('#not_saved_count').text(failedCount)
                        
                        $('#proccess_count_modal').modal()
                        $('#proccess_count_modal .modal-title').text('Proccessing')

                        generateGradeDetail()

                  })


                  $('#gs_e_section_g').unbind().click(function(){
                        
                        var validfilter = true
                        selectedStudent = $('#student_test').val()

                        if($('#t_s_section').val() == ''){

                              Swal.fire({
                                    type: 'info',
                                    title: 'Please select section!',
                                    showConfirmButton: false,
                                    timer: 1500
                              });

                              validfilter = false

                        }
                        else if($('#t_s_subject').val() == ''){

                              Swal.fire({
                                    type: 'info',
                                    title: 'Please select subject!',
                                    showConfirmButton: false,
                                    timer: 1500
                              });

                              validfilter = false

                        }
                        else if($('#t_s_quarter').val() == ''){

                              Swal.fire({
                                    type: 'info',
                                    title: 'Please select quarter!',
                                    showConfirmButton: false,
                                    timer: 1500
                              });

                              validfilter = false

                        }

                        if(validfilter){

                              section = $('#t_s_section').val() 
                              selected_subject = $('#t_s_subject').val() 
                              quarter = $('#t_s_quarter').val() 
                              loadGradesDetail()

                        }


                  })

                  $('#save_grades_hs').unbind().click(function(){

                        itemCount = parseInt( $('.ge').length / 10 )  + 1;
                        itemCountTotal = $('.ge').length

                        if(itemCountTotal == 0){

                              Swal.fire({
                                    type: 'info',
                                    title: 'No modification made!',
                                    showConfirmButton: false,
                                    timer: 1500
                              });

                        }
                        else{


                              savehps()

                             
                              

                        }

                  })


                  function savehps(){

                        itemCount = parseInt( $('.ge[data-studid="0"]').length / 10 )  + 1;
                        itemCountTotal = $('.ge[data-studid="0"]').length
                        firstIndex = 0;
                        lastIndex = 10;
                        proccessCount = 0
                        proccessCount = 0
                        successCount = 0;
                        failedCount = 0

                        if(itemCountTotal == 0){

                              
                              itemCount = parseInt( $('.ge').length / 10 )  + 1;
                              itemCountTotal = $('.ge').length

                              firstIndex = 0;
                              lastIndex = 10;
                              proccessCount = 0
                              proccessCount = 0
                              successCount = 0;
                              failedCount = 0

                              $('#proccess_count').text(proccessCount + ' / ' + itemCountTotal)
                              $('#save_count').text(successCount)
                              $('#not_saved_count').text(failedCount)
                              $('#proccess_count_modal').modal()
                              $('#proccess_count').text(0)
                              $('#proccess_count_modal .modal-title').text('Proccessing')
                              $('#proccess_done').attr('hidden','hidden')
                              $('#save_grades_hs').attr('disabled','disabled')

                              save_gs_student_grades()

                        }else{


                              $('.ge[data-studid="0"]').each(function(){

                                    counter= 0
                                    var gradeid = $(this).attr('data-id')
                                    var value = $(this).text()
                                    var field = $(this).attr('data-field')
                                    var studid = $(this).attr('data-studid')

                                    $.ajax({
                                          type:'GET',
                                          url:url,
                                          data:{
                                                      submit:'submit',
                                                      value: selectedStudent,
                                                      gradeid:gradeid,
                                                      value:value,
                                                      studid:studid,
                                                      field:field,
                                                      gsid:t_s_gsid
                                          },
                                          success:function(data) {
                                                
                                                if(data > 0){
                                                
                                                      successCount += 1
                                                      $('#save_count').text(successCount)
                                                }
                                                else{

                                                      failedCount += 1
                                                      $('#not_saved_count').text(failedCount)
                                                }

                                                proccessCount += 1;

                                                if(  proccessCount  == itemCountTotal){

                                                      $('.ge[data-studid="0"]').removeClass('.ge')

                                                      itemCount = parseInt( $('.ge').length / 10 )  + 1;
                                                      itemCountTotal = $('.ge').length

                                                      firstIndex = 0;
                                                      lastIndex = 10;
                                                      proccessCount = 0
                                                      proccessCount = 0
                                                      successCount = 0;
                                                      failedCount = 0

                                                      $('#proccess_count').text(proccessCount + ' / ' + itemCountTotal)
                                                      $('#save_count').text(successCount)
                                                      $('#not_saved_count').text(failedCount)
                                                      $('#proccess_count_modal').modal()
                                                      $('#proccess_count').text(0)
                                                      $('#proccess_count_modal .modal-title').text('Proccessing')
                                                      $('#proccess_done').attr('hidden','hidden')
                                                      $('#save_grades_hs').attr('disabled','disabled')

                                                      save_gs_student_grades()


                                                }

                                                
                                          }
                                    })

                              })

                        }

                  }


            })
      </script>
