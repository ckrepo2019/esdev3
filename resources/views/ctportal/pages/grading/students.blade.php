
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')
      <style>
            .gradetable thead th:last-child  { 
                  position: sticky; 
                  right: 0; 
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            }

            .gradetable tbody th:last-child  { 
                  position: sticky; 
                  right: 0; 
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
                  min-width: 58px;
            }

            .gradetable tbody th:first-child  {  
                  position: sticky; 
                  left: 0; 
                  background-color: #fff; 
                  min-width: 179px !important;
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
            }

            .gradetable thead th:first-child  { 
                  position: sticky; left: 0; 
                  width: 185px !important;
                  background-color: #fff; 
                  outline: 2px solid #dee2e6;
                  outline-offset: -1px;
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
                  top: 96px !important;
                  background-color: #fff;
                  outline: 2px solid #dee2e6 ;
                  outline-offset: -1px;
                  z-index: 1;
            }
           
            .header_one {
                  position: sticky;
                  top: 68px !important;
                  background-color: #fff;
                  outline: 2px solid #dee2e6 ;
                  outline-offset: -1px;
                  z-index: 1;
            }
            .header_two {
                  position: sticky;
                  top: 42px !important;
                  background-color: #fff;
                  outline: 2px solid #dee2e6 ;
                  outline-offset: -1px;
                  z-index: 1;
            }
            .header_three {
                  position: sticky;
                  top: 0px !important;
                  background-color: #fff;
                  outline: 2px solid #dee2e6 ;
                  outline-offset: -1px;
                  z-index: 5;
            }

            
            
      </style>
@endsection



@section('content')

      <div class="modal fade" id="gradeTable" style="display: none;" aria-hidden="true">
           
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                
                  <div class="modal-content">
                        
                        <div class="modal-header bg-primary">
                              <h5 class="modal-title"></h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body p-0 table-responsive" style="height: 500px;" id="studentlist">  
                              <div class="modal-body p-0" style="height: 400px">
                                    <div class="card mr-2 ml-2" style="top:30%"> 
                                          <div class="card-body bg-success text-center text-lg text-bold">
                                                <span class="text-xl text-bold">PLEASE SELECT TERM</p>
                                          </div>
                                    </div>                 
                              </div>
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary" id="updateGrade" hidden="hidden">UPDATE GRADES</button>
                        </div>
                       
                  </div>
                 
            </div>
          
      </div>

      <div class="modal fade" id="proccess_count_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h4 class="modal-title">Proccessing ...</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                           <div class="col-md-6"><label>Process : </label></div>
                           <div class="col-md-6"><span id="proccess_count"></span></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="proccess_done" hidden>Done</button>
                      </div>
                </div>
            </div>
        </div>

      <div class="modal fade" id="setupModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">GRADE SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <div class="modal-body p-0 table-responsive" style="height: 471px" id="setupTable">
                        
                  </div>
            </div>
            </div>
      </div>

      <div class="modal fade" id="termsetup" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content ">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">Grade Submission</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body table-responsive" style="height:  533px;">
                              <div class="row" id="studentfinalgrade">
                                  
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="modal fade" id="termModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">TERM</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <div class="modal-body">
                        <div class="form-group">
                              <label for="">SELECT TERM</label>
                              <select id="changeterm" class="form-control">
                              </select>  
                        </div>
                  </div>
                  <div class="modal-footer">
                        <button class="btn btn-primary" id="changeModalButton">SELECT</button>
                  </div>
            </div>
            </div>
      </div>

      <div class="modal fade" id="grade_term_setup_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-sm">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">GRADE TERM SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <div class="modal-body">
                        <div class="form-group">
                              <label for="">TERM SETUP</label>
                              <select id="term_setup" class="form-control">
                                    <option value="">SELECT TERM SETUP</option>
                                   @foreach (DB::table('college_gradestermsetup')->where('isactive',1)->where('deleted',0)->get() as $item)
                                       <option value="{{$item->id}}">{{$item->description}}</option>
                                   @endforeach
                              </select>   
                        </div>

                  </div>
                  <div class="modal-footer">
                        <button class="btn btn-primary" id="update_term_setup">SELECT</button>
                  </div>
            </div>
            </div>
      </div>

      <script>
            $(document).on('click','#changeTerm',function(){
                  $('#termModal').modal();    
            })
      </script>

      <div class="modal fade" id="createQuarterSetup" style="display: none;" aria-hidden="true">
            <div class="modal-dialog  modal-lg">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">CREATE SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <form id="createQuarterSetup"  enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body table-responsive" style="height: 471px">
                              <div class="form-group">
                                    <label for="">Setup Description</label>
                                    <input type="text" class="form-control" name="setupDesc">
                              </div>
                              <div class="form-group">
                                    <label for="">Percentage</label>
                                    <input class="form-control" name="percentage">
                              </div>
                              <div class="form-group">
                                    <label for="">Number of Columns</label>
                                    <input class="form-control" name="items">
                              </div>
                              <button class="btn btn-primary">CREATE</button>
                        </div>
                  </form>
            </div>
            </div>
      </div>

      <div class="modal fade" id="createSetupModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog  modal-lg">
            <div class="modal-content ">
            <div class="modal-header bg-success">
                  <h5 class="modal-title">CREATE SETUP</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                  </button>
            </div>
                  <form id="createSetup"  enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body table-responsive" style="height: 471px">
                              <div class="form-group">
                                    <label for="">Setup Description</label>
                                    <input type="text" class="form-control" name="setupDesc">
                              </div>
                              <div class="form-group">
                                    <label for="">Percentage</label>
                                    <input class="form-control" name="percentage">
                              </div>
                              <div class="form-group">
                                    <label for="">Number of Columns</label>
                                    <input class="form-control" name="items">
                              </div>
                              <button class="btn btn-primary">CREATE</button>
                        </div>
                  </form>
            </div>
            </div>
      </div>

      <section class="content">
            <div class="row">
                  @foreach ($schedule as $item)
                        <div class="col-md-4">
                              <div class="card card-widget widget-user-2">
                                    <div class="widget-user-header bg-primary">
                                          <h2 class="widget-user-username ml-0">{{$item[0]->sectionDesc}}</h2>
                                          <h5 class="widget-user-desc ml-0">{{$item[0]->courseDesc}}</h5>
                                          </div>
                                          <div class="card-footer p-0">
                                          <ul class="nav flex-column">
                                                @foreach($item as $scheditem)
                                                      <li class="nav-item">
                                                            <a href="#" data-section="{{$scheditem->sectionDesc}}" class="nav-link subjectVal" item-id="{{$scheditem->subjid}}" item-section="{{$scheditem->sectionID}}">
                                                                  {{Str::limit($scheditem->subjDesc, $limit = 20, $end = '...')}}
                                                            <span class="float-right badge bg-primary"></span>
                                                            </a>
                                                      </li>
                                                @endforeach
                                          </ul>
                                    </div>
                              </div>
                        </div>
                  @endforeach
            </div>      
      </section>

@endsection

@section('footerscript')

      <script>
            $(document).ready(function(){

                  var setupStatus = null;
                  var setupId = null;
                  var selectedsubjid;
                  var selectedSection;
                  var selectedsetup;
                  var selectedQuarter;
                  var sectionDesc;
                  var subjDesc;

                  @php
                        $teacherid = DB::table('teacher')->where('userid',auth()->user()->id)->first()->id;
                  @endphp

                  // $(document).on('click','.updateGradeDetail',function(b){

                  //       $.ajax({
                  //             type:'GET',
                  //             url:'/college/teacher/update/student/gradesdetail?student='+$(this).attr('data-value')+'&subject='+selectedsubjid+'&section='+selectedSection+'&term='+selectedQuarter,
                  //             success:function(data) {
                  //                   if(data == 1){
                  //                         $('#studentlist').empty()
                  //                         showGrades()
                  //                         Toast.fire({
                  //                               type: 'success',
                  //                               title: 'Updated successfully!'
                  //                         })
                  //                   }
                                  
                  //             }
                  //       })

                  // })

                  $(document).on('click','#updateGradeList',function(b){
                        
                        $('.updateGradeDetail').each(function(){

                              $.ajax({
                              type:'GET',
                                    url:'/college/teacher/update/student/gradesdetail?student='+$(this).attr('data-value')+'&subject='+selectedsubjid+'&section='+selectedSection+'&term='+selectedQuarter,
                                    success:function(data) {
                                          if(data == 1){
                                                $('#studentlist').empty()
                                                showGrades()
                                                // Toast.fire({
                                                //       type: 'success',
                                                //       title: 'Updated successfully!'
                                                // })
                                          }
                                    
                                    }
                              })

                        })


                  })

                  $('.updateGradeDetail').each(function(){

                     

                        // $.ajax({
                        // type:'GET',
                        //       url:'/college/teacher/update/student/gradesdetail?student='+$(this).attr('data-value')+'&subject='+selectedsubjid+'&section='+selectedSection+'&term='+selectedQuarter,
                        //       success:function(data) {
                        //             if(data == 1){
                        //                   // $('#studentlist').empty()
                        //                   showGrades()
                        //                   // Toast.fire({
                        //                   //       type: 'success',
                        //                   //       title: 'Updated successfully!'
                        //                   // })
                        //             }
                              
                        //       }
                        // })

                  })

                  $(document).on('click','#editSetup',function(){

                        $('#createSetupModal').modal();

                        setupStatus = 1;
                        setupId = $(this).attr('data-id');

                        showInputs()

                        $('#createSetupModal .modal-title').text('UPDATE SETUP')

                        modalHeader = $('#createSetupModal .modal-header')
                        modalHeader.removeClass()
                        modalHeader.attr('class','modal-header btn-info')

                        actionButton = $('#createSetupModal .btn');
                        actionButton.text('UPDATE SETUP')
                        actionButton.removeClass()
                        actionButton.attr('class','btn btn-info')
                        
                  })


                  $(document).on('click','#removeSetup',function(){

                        $('#createSetupModal').modal();

                        setupStatus = 2;
                        setupId = $(this).attr('data-id');

                        showInputs()
                        $('#createSetupModal .modal-title').text('REMOVE SETUP')

                        modalHeader = $('#createSetupModal .modal-header')
                        modalHeader.removeClass()
                        modalHeader.attr('class','modal-header btn-danger')

                        actionButton = $('#createSetupModal .btn');
                        actionButton.text('REMOVE SETUP')
                        actionButton.removeClass()
                        actionButton.attr('class','btn btn-danger')
                  
                  })

                  function showInputs(){
                       
                        $('input[name="setupDesc"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[0].innerText)

                        $('input[name="percentage"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[1].innerText)

                        $('input[name="items"]').val($('#setupTable tbody tr[data-id="'+setupId+'"]')[0].children[2].innerText)
                        
                  }

                  function originalSetup(){

                        $('#createSetupModal .modal-title').text('CREATE SETUP')

                        modalHeader = $('#createSetupModal .modal-header')
                        modalHeader.removeClass()
                        modalHeader.attr('class','modal-header btn-primary')

                        actionButton = $('#createSetupModal .btn');
                        actionButton.text('CREATE SETUP')
                        actionButton.removeClass()
                        actionButton.attr('class','btn btn-primary')

                        $('#createSetup')[0].reset();

                  }

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  function viewSetup(){

                        $.ajax({
                              type:'GET',
                              url:'/schedule?student=student&setuptable=setuptable&select=firstname,lastname,middlename&subject='+selectedsubjid+'&section='+selectedSection+'&teacher='+'{{$teacherid}}',
                              success:function(data) {
                                    $('#setupTable').append(data)
                              }
                        })
                  }


                  function checkGradesStatus(){

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/subjecttermsetup?check=check&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                              success:function(data) {

                                    if(data.length > 0){
                                       
                                          if(selectedQuarter == 1){

                                                

                                                if(data[0].prelimsubmit == 1){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('SUBMITTED')

                                                }

                                                else if(data[0].prelimsubmit == 2){
                                                      
                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('POSTED')

                                                }
                                                else{

                                                      $('#statusHolder').text('')
                                                      canUpdateGrade = true
                                                      // $('#updateGrade').removeAttr('hidden')

                                                }

                                          }
                                          else if(selectedQuarter == 2){

                                                if(data[0].midtermsubmit == 1){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('SUBMITTED')

                                                }

                                                else if(data[0].midtermsubmit == 2){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('POSTED')

                                                }
                                                else{

                                                      $('#statusHolder').text('')
                                                      canUpdateGrade = true
                                                      // $('#updateGrade').removeAttr('hidden')

                                                }


                                          }
                                          else if(selectedQuarter == 3){

                                                if(data[0].prefisubmit == 1){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('SUBMITTED')

                                                }

                                                else if(data[0].prefisubmit == 2){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('POSTED')

                                                }else{

                                                      $('#statusHolder').text('')
                                                canUpdateGrade = true
                                                // $('#updateGrade').removeAttr('hidden')

                                                }


                                          }
                                          else if(selectedQuarter == 4){

                                                if(data[0].finalsumbit == 1){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('SUBMITTED')

                                                }

                                                else if(data[0].finalsumbit == 2){

                                                      //$('#updateGrade').attr('hidden','hidden')
                                                      canUpdateGrade = false
                                                      $('#statusHolder').text('POSTED')

                                                }else{

                                                      $('#statusHolder').text('')
                                                      canUpdateGrade = true
                                                      // $('#updateGrade').removeAttr('hidden')

                                                }

                                          }
                                          else{

                                                $('#statusHolder').text('')
                                                canUpdateGrade = true
                                                // // $('#updateGrade').removeAttr('hidden')

                                          }
                                    }
                                    else{
                                          $('#statusHolder').text('')
                                          canUpdateGrade = true
                                          // $('#updateGrade').removeAttr('hidden')
                                    }
                              
                              }

                        })

                  }


                  function showGrades(){

                        $.ajax({
                              type:'GET',
                              url:'/schedule?student=student&teacher='+'{{$teacherid}}'+'&gradetable=gradetable&select=firstname,lastname,middlename,studinfo.id as studid&subject='+selectedsubjid+'&section='+selectedSection+'&term='+selectedQuarter,
                              success:function(data) {
                                    $('#studentlist').empty()
                                    $('#studentlist').append(data)
                                    $('#termTextHolder').text($('#changeterm option:selected')[0].innerText)
                                    $('#termTextHolder').attr('data-id',$('#changeterm').val())
                                    checkGradesStatus()
                                  
                              }
                        })

                  }
              

                  $(document).on('click','#showCreateSetupModal',function(){
                        setupId = null
                        setupStatus = null
                        $('#createSetupModal').modal();
                  })

                  $(document).on('click','#viewSetup',function(){
                        $('#setupTable').empty()
                        $('#setupModal').modal()
                        viewSetup()
                  })

                  function checksubjecttermsetup(){

                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/subjecttermsetup?check=check&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                              success:function(data) {

                                    if(data.length == 0){

                                          $('#grade_term_setup_modal').modal()

                                    }
                                    else{
                                          $('#changeterm').empty()

                                          if(data[0].withpre == 1){
                                                $('#changeterm').append('<option value="1">Prelim</option>')
                                          }
                                          if(data[0].withmid == 1){
                                                $('#changeterm').append('<option value="2">Midterm</option>')
                                          }
                                          if(data[0].withsemi == 1){
                                                $('#changeterm').append('<option value="3">Semifinal</option>')
                                          }
                                          if(data[0].withfinal == 1){
                                                $('#changeterm').append('<option value="4">Final</option>')
                                          }

                                          $('#grade_term_setup_modal').modal('hide')

                                          $('#gradeTable .modal-title').text(sectionDesc+' - ' +' '+subjDesc)

                                          $('#termModal').modal()
                                    }
                              }

                        })
                  }
                 
                  $(document).on('click','.subjectVal',function(){

                        selectedsubjid = $(this).attr('item-id');
                        selectedSection = $(this).attr('item-section');
                        sectionDesc = $(this).attr('data-section');
                        subjDesc = $(this)[0].innerText
                        checksubjecttermsetup()


                        
                       

                  })

             
                  
                  $('#createSetup').submit( function( e ) {

                        var inputs = new FormData(this)
                        inputs.append('subjID',selectedsubjid)
                        inputs.append('setupstatus',setupStatus)
                        inputs.append('setupId',setupId)
                        inputs.append('sectionid',selectedSection)
                        

                        $.ajax( {
                              url: '/college/teacher/createsetup',
                              type: 'POST',
                              data: inputs,
                              processData: false,
                              contentType: false,
                              success:function(data) {

                                    if(data == 0){

                                          viewSetup()
                                          showGrades()
                                          $('#setupTable').empty()
                                          $('#studentlist').empty()
                                          $('#createSetupModal').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Created successfully!'
                                          })
                                          originalSetup()

                                    }else if(data == 1){
                                          
                                          viewSetup()
                                          showGrades()
                                          $('#setupTable').empty()
                                          $('#studentlist').empty()
                                          $('#createSetupModal').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated successfully!'
                                          })
                                          originalSetup()

                                    }else if(data == 2){
                                                
                                          viewSetup()
                                          showGrades()

                                          $('#setupTable').empty()
                                          $('#studentlist').empty()
                                          $('#createSetupModal').modal('hide')
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted successfully!'
                                          })
                                          originalSetup()
                                    }

                              },
                        } );
                        e.preventDefault();

                  })


            var canUpdate = true
            var canUpdateGrade = true
            var quarterSetupvalue;

            

            $(document).on('click','.submitgrades',function(){

                  var thisValue = $(this)
                  $.ajax({
                        type:'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        url:'/subjecttermsetup?updatesubmission=updatesubmission&sectionid='+selectedSection+'&subjid='+selectedsubjid+'&term='+thisValue.attr('data-id'),
                        success:function(data) {

                              if(data == 1){
                                    
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Student grades is not available!'
                                    })
                              }
                              else{

                                    Toast.fire({
                                          type: 'success',
                                          title: 'Submitted successfully!'
                                    })

                                    viewsubmittermsetup()
                                    showGrades()

                              }

                            
                             
                        }

                  })

            })


            function checktermsetup(){

                  $.ajax({
                        type:'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        url:'/subjecttermsetup?check=check&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                        success:function(data) {

                              if(data.length > 0){
                                   
                                    if(data[0].fix == 1){
                                          canUpdate = false
                                          $('#update_term_setup').attr('hidden','hidden')
                                          $('#term_setup').attr('disabled','disabled')
                                    }  
                                    else{
                                          canUpdate = true
                                          $('#update_term_setup').removeAttr('hidden')
                                          $('#term_setup').removeAttr('disabled')
                                    }
                              
                                    quarterSetupvalue = data[0].quartersetupid

                              }
                             
                        }

                  })
            }

            function viewsubmittermsetup(){

                  $.ajax({
                        type:'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        url:'/quartersetup?teacher='+'{{DB::table('teacher')->where("userid",auth()->user()->id)->first()->id}}'+'&info=info',
                        success:function(data) {

                              $('#term_setup').empty()
                              
                              $('#term_setup').append('<option selected="selected">SELECT TERM SETUP</option>')

                              $.each(data,function(a,b){

                                    $('#term_setup').append('<option value="'+b.id+'">'+b.qsDesc+'</option>')

                              })

                              $('#term_setup').val(quarterSetupvalue).change()
                             
                        }
                  })

                  // checktermsetup()

                  $.ajax({
                        type:'POST',
                        data: {'_token': '{{ csrf_token() }}'},
                        url:'/subjecttermsetup?table=table&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                        success:function(data) {
                              
                              $('#studentfinalgrade').empty()
                              $('#studentfinalgrade').append(data)
                             
                        }
                  })

                  
            }
            $(document).on('click','#submit_term_grade',function(){

                  $('#termsetup').modal('show');

                  viewsubmittermsetup()

            })


            $(document).on('click','#update_term_setup',function(){

                  if(canUpdate){
                        $.ajax({
                              type:'POST',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/subjecttermsetup?create=create&sectionid='+selectedSection+'&subjid='+selectedsubjid+'&quartersetupid='+$('#term_setup').val(),
                              success:function(data) {

                                    checksubjecttermsetup()

                              }
                        })
                  }
                  
                  
            })


                  
                               
            $(document).on('click','#changeModalButton',function(){

                  selectedQuarter = $('#changeterm').val();
                  var thisValue = $(this)


                  
              

                  $.ajax({
                        type:'GET',
                        url:'/schedule?student=student&select=firstname,lastname,middlename,studinfo.id as studid&gradetable=gradetable&subject='+selectedsubjid+'&section='+selectedSection+'&term='+selectedQuarter,
                        success:function(data) {

                              if(data == 0){
                                    
                                    Toast.fire({
                                          type: 'error',
                                          title: 'Subject is not Configure or Incomplete!'
                                    })

                              }
                              else{

                                    $('#termModal').modal('hide')
                                    $('#gradeTable').modal()
                                    $('#studentlist').empty()
                                    showGrades()

                              }
                              
                        }
                  })

                  // showGrades()
                 

            })
                  
            

      })

      </script>

@endsection

