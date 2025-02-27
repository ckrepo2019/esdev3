
@extends('teacher.layouts.app')

@section('headerjavascript')

      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
      </style>
    
@endsection

@section('content')

      <div class="modal fade" id="g_modal" style="display: none;" aria-hidden="true" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                  <div class="modal-content">
                        <div class="modal-header bg-primary">
                              <h4 class="modal-title">Student Report Card</h4>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                              <div class="row" >
                                  <div class="col-md-12" id="grading_holder">
                                  </div>
                              </div>
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
                        <div class="row">
                        <div class="col-md-6"><label>Success : </label></div>
                        <div class="col-md-6"><span id="save_count"></span></div>
                        </div>
                        <div class="row">
                        <div class="col-md-6"><label>Failed : </label></div>
                        <div class="col-md-6"><span id="not_saved_count"></span></div>
                        </div>
                  </div>
                  <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" data-dismiss="modal" id="proccess_done" hidden>Done</button>
                  </div>
            </div>
            </div>
      </div>


      <section class="content-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                    
                    </div>
                    <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active">Report Card</li>
                            </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content pt-0">
            <div class="row">
                  <div class="col-md-3">
                        <div class="card card-widget widget-user-2">
                              <div class="widget-user-header bg-primary">
                                <h5 class="mb-0">Pre-School</h5>
                              </div>
                              <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Advisory <span class="float-right badge bg-primary" style="font-size:15px">{{App\Models\Grading\CoreValue::advisory_count(null,2)}} </span>
                                    </a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Sections <span class="float-right badge bg-danger" style="font-size:15px">{{App\Models\Grading\CoreValue::advisory_count(null,2)}}</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                        </div>
                  </div>
                  <div class="col-9">
                        <div class="card">
                            <div class="card-header bg-primary ">
                                    <h3 class="card-title">Report Card</h3>
                            </div>
                            <div class="card-body ">
                                <table class="table">
                                        <tr>
                                              <th width="40%">Academic Program</th>
                                              <th width="20%"></th>
                                              <th width="20%"></th>
                                              <th width="20%"></th>
                                        </tr>
                                        <tr>
                                              <td>Pre-School</td>
                                              <td></td>
                                              <td></td>
                                              <td>
                                                    <button class="btn btn-primary col-md-12 grade_b btn-sm" data-acad="ps"><i class="fas fa-balance-scale"></i> GRADES</button>
                                              </td>
                                        </tr>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                              <div class="card">
                                    <div class="card-header bg-primary ">
                                          <h3 class="card-title">Grade Status</h3>
                                    </div>
                                    <div class="card-body p-0" id="grade_status_holder">
                                    
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </section>


@endsection

@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script>
            $(document).ready(function(){

                  const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                              });

                  var corvalurl;
                  var gradeurl;

                  loadGradeStatus()

                  function loadGradeStatus(){
                        var syid = $('#sy').val();
                        var sem = $('#semester').val();
                        $.ajax({
                              type:'GET',
                              url: '/reportcard/grade/status/preschool',
                              success:function(data) {
                                    $('#grade_status_holder').empty()
                                    $('#grade_status_holder').append(data)
                              }
                        })
                  }

                  $(document).on('click','.corevalue_b',function(e){
                     
                        if($(this).attr('data-acad') == 'gs'){

                              corvalurl = '/reportcard/coreval/gradeschool';

                        }
                        else if($(this).attr('data-acad') == 'hs'){

                              corvalurl = '/reportcard/coreval/highschool';

                        }
                        else if($(this).attr('data-acad') == 'sh'){

                              corvalurl = '/reportcard/coreval/seniorhigh';

                        }

                        $.ajax({
                              type:'GET',
                              url: corvalurl,
                              data:{
                                    grade:'grade',
                              },
                              success:function(data) {

                                    if(data[0].status == 0){
                                          Swal.fire({
                                                type: 'info',
                                                text: data[0].data
                                          });
                                    }
                                    else{
                                          $('#g_modal').modal()
                                          $('#grading_holder').empty()
                                          $('#grading_holder').append(data)
                                    }
                              }
                        })

                        e.stopPropagation();

                  })


                  $(document).on('click','.grade_b',function(){

                        if($(this).attr('data-acad') == 'ps'){

                              gradeurl = '/gradestudent/preschool';

                        }
                        else if($(this).attr('data-acad') == 'gs'){

                              gradeurl = '/reportcard/grades/gradeschool';


                        }
                        else if($(this).attr('data-acad') == 'hs'){

                              gradeurl = '/reportcard/grades/highschool';

                        }
                        else if($(this).attr('data-acad') == 'sh'){

                              gradeurl = '/reportcard/grades/seniorhigh';

                        }

                        $.ajax({
                              type:'GET',
                              url: gradeurl,
                              data:{
                                    grade:'grade',
                              },
                              success:function(data) {

                                    if(data[0].status == 0){
                                          Swal.fire({
                                                type: 'info',
                                                text: data[0].data
                                          });
                                    }
                                    else{

                                          $('#g_modal').modal()
                                          $('#grading_holder').empty()
                                          $('#grading_holder').append(data)
                                 

                                    }
                              }
                        })

                  })


                  // function loadGradeStatus(){

                  //       $.ajax({
                  //             type:'GET',
                  //             url: '/reportcard/grade/status',
                  //             success:function(data) {
                              
                  //                   $('#grade_status_holder').empty()
                  //                   $('#grade_status_holder').append(data)
                                    
                  //             }
                  //       })

                  // }
                  
                  // loadGradeStatus()

            })
           
      </script>
@endsection