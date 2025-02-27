
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
@endsection

{{-- @section('modalSection')
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
@endsection --}}

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
                                    <span class="text-white h4"><b>Teacher Evaluation Setup</b></span>
                                    {{-- <button class="btn btn-sm btn-primary float-right mr-2 mb-2" id="add_modal_button" ><b>ADD QUESTION</b></button> --}}
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="form-group col-md-12">
                                                <label for="">Instruction</label>
                                                <textarea name="instruction" id="instruction" cols="30" rows="10" class="form-control">{{$setup->instruction}}</textarea>
                                          </div>
                                          <div class="form-group col-md-3">
                                                <div class="icheck-success d-inline">
                                                      <input type="checkbox" value="1" id="q1" {{$setup->q1 == 1 ?'checked':''}}>
                                                      <label for="q1">Quarter 1</label>
                                                </div>
                                          </div>
                                          <div class="form-group col-md-3">
                                                <div class="icheck-success d-inline">
                                                      <input type="checkbox" value="1" id="q2" {{$setup->q2 == 1 ?'checked':''}}>
                                                      <label for="q2">Quarter 2</label>
                                                </div>
                                          </div>
                                          <div class="form-group col-md-3">
                                                <div class="icheck-success d-inline">
                                                      <input type="checkbox" value="1" id="q3" {{$setup->q3 == 1 ?'checked':''}}>
                                                      <label for="q3">Quarter 2</label>
                                                </div>
                                          </div>
                                          <div class="form-group col-md-3">
                                                <div class="icheck-success d-inline">
                                                      <input type="checkbox" value="1" id="q4" {{$setup->q4 == 1 ?'checked':''}}>
                                                      <label for="q4">Quarter 4</label>
                                                </div>
                                          </div>
                                    </div>
                              </div>
                              <div class="card-footer">
                                    <button class="btn btn-primary" id="update_teacher_evaluation_setup">UPDATE TEACHER EVALUATION SETUP</button>
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

                  $(document).on('click','#update_teacher_evaluation_setup',function(){

                        var q1;
                        var q2;
                        var q3;
                        var q4;

                        if($('#q1').prop('checked')){
                              q1 = 1
                        }
                        if($('#q2').prop('checked')){
                              q2 = 1
                        }
                        if($('#q3').prop('checked')){
                              q3 = 1
                        }
                        if($('#q4').prop('checked')){
                              q4 = 1
                        }

                        $.ajax({
                              type:'GET',
                              url:'/teacherevalsetup?update=update&instruction='+$('#instruction').val()+'&q1='+q1+'&q2='+q2+'&q3='+q3+'&q4='+q4,
                              success:function(data) {
                                    
                                    Toast.fire({
                                                type: 'success',
                                                title: 'Updated Successfully!'
                                          })

                              }
                        })
                  })
                  
            })

           
     </script>
    
@endsection

