
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

@section('modalSection')
      <div class="modal fade" id="modal_add_question_modal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog">
                  <div class="modal-content">
                        <div class="modal-header bg-info">
                              <h5 class="modal-title">Room Form</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                       
                              <input class="form-control" hidden name="insert" value="insert">
                              <div class="modal-body">
                                    <div class="form-group">
                                          <label for="">Evaluation Question</label>
                                          <input name="evalquestion" class="form-control">
                                    </div>
                                    <div class="form-group">
                                          <label for="">Evaluation Max Rating</label>
                                          <input class="form-control" name="maxrating">
                                    </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                    <button  type="button" class="btn btn-primary" id="save_button">SAVE</button>
                              </div>
                    
                  </div>
            </div>
      </div>
@endsection

@section('content')
      <section class="content-header">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-sm-6">
                  
                  </div>
                  <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                              <li class="breadcrumb-item"><a href="/home">Home</a></li>
                              <li class="breadcrumb-item active">Room</li>
                        </ol>
                  </div>
            </div>
      </div>
      </section>
    
    <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-info ">
                                    <span class="text-white h4"><b>Teacher Evaluation Questions</b></span>
                                    <button class="btn btn-sm btn-primary float-right mr-2 mb-2" id="add_modal_button" ><b>ADD QUESTION</b></button>
                              </div>
                              <div class="card-body table-responsive p-0" 
                              id="teacher_evaulation_question_table">
                              
                              
                              </div>
                        
                        </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerjavascript')
      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  load_teacher_evaluation_question_table()
                  var selected_question = null;
                  var proccess = null;

                  function updateButton(){

                        $('#save_button').removeClass('btn-primary')     
                        $('#save_button').addClass('btn-success')     
                        $('#save_button').text('UPDATE')
                        proccess = 'update'; 
                        $('input[name="evalquestion"]').val('') 
                        $('input[name="maxrating"]').val('') 

                  }
                  function createButton(){

                        $('#save_button').removeClass('btn-success')     
                        $('#save_button').addClass('btn-primary')    
                        $('#save_button').text('SAVE') 
                        proccess = 'insert'; 
                        $('input[name="evalquestion"]').val('') 
                        $('input[name="maxrating"]').val('') 

                  }

                  function load_teacher_evaluation_question_table(){

                        $.ajax({
                              type:'GET',
                              url:'/teacherevalquestions?table=table',
                              success:function(data) {
                                    $('#teacher_evaulation_question_table').empty()
                                    $('#teacher_evaulation_question_table').append(data)
                              }
                        })   

                  }

                  $(document).on('click','#add_modal_button',function(){
                        createButton()
                        $('#modal_add_question_modal').modal('show')
                  })

                  
                  $(document).on('click','.edit',function(){

                        updateButton()

                        $('#modal_add_question_modal').modal('show')

                        $.ajax({
                              type:'GET',
                              url:'/teacherevalquestions?info=info&id='+$(this).attr('data-value'),
                              success:function(data) {

                                    selected_question = data[0].id
                                    $('input[name="evalquestion"]').val(data[0].question)
                                    $('input[name="maxrating"]').val(data[0].maxrating)

                                
                              }
                        })  

                  })

                  $(document).on('click','#save_button',function(){

                        $.ajax({
                              type:'GET',
                              url:'/teacherevalquestions?'+proccess+'='+proccess+'&question='+$('input[name="evalquestion"]').val()+'&maxrate='+$('input[name="maxrating"]').val()+'&question_id='+selected_question,
                              success:function(data) {

                                    if(proccess == 'insert'){

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Created Successfully!'
                                          })



                                    }else if(proccess == 'update'){

                                          Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })

                                    }
                                    load_teacher_evaluation_question_table()
                              }
                        })   

                  })
                  
                  $(document).on('click','.delete',function(){

                        selected_question = $(this).attr('data-value')
                        proccess = 'delete'   

                        $.ajax({
                              type:'GET',
                              url:'/teacherevalquestions?'+proccess+'='+proccess+'&question_id='+selected_question,
                              success:function(data) {

                                    if(proccess == 'delete'){
                                          Toast.fire({
                                                type: 'success',
                                                title: 'Deleted Successfully!'
                                          })
                                    }

                                    load_teacher_evaluation_question_table()

                              }
                        })   
                         
                  })

            })
      </script>
    
@endsection

