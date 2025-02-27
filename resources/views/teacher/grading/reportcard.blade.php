
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
                              <h4 class="modal-title" id="modal_reportcard_title">Student Report Card</h4>
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
                        <label for="">SCHOOL YEAR</label>
                        <select name="sy" id="sy" class="form-control select2">
                            @foreach(DB::table('sy')->select('id','sydesc','isactive')->get() as $item)
                                @if($item->isactive == 1)
                                    <option value="{{$item->id}}" selected="selected">{{$item->sydesc}}</option>
                                @else
                                    <option value="{{$item->id}}">{{$item->sydesc}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="">SEMESTER</label>
                        <select name="semester" id="semester" class="form-control select2">
                            @foreach(DB::table('semester')->select('id','semester','isactive')->get() as $item)
                                @if($item->isactive == 1)
                                    <option value="{{$item->id}}" selected="selected">{{$item->semester}}</option>
                                @else
                                    <option value="{{$item->id}}">{{$item->semester}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="row mt-2">
                  <div class="col-md-2">
                        <button class="btn btn-primary btn-block" id="filter_all">
                              FILTER
                       </button>
                  </div>
            </div>
            <hr>
            <div class="row mt-2">
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
                  <div class="col-md-3">
                        <div class="card card-widget widget-user-2">
                              <div class="widget-user-header bg-primary">
                                <h5 class="mb-0">Grade School</h5>
                              </div>
                              <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Advisory <span class="float-right badge bg-primary" style="font-size:15px">{{App\Models\Grading\CoreValue::advisory_count(null,3)}} </span>
                                    </a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Sections <span class="float-right badge bg-danger" style="font-size:15px">{{App\Models\Grading\GradeSchool::section_count()}}</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                        </div>
                     
                  </div>
                  <div class="col-md-3">
                        <div class="card card-widget widget-user-2">
                              <div class="widget-user-header bg-primary">
                                <h5 class="mb-0">High School</h5>
                              </div>
                              <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Advisory <span class="float-right badge bg-primary" style="font-size:15px">{{App\Models\Grading\CoreValue::advisory_count(null,4)}} </span>
                                    </a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Sections <span class="float-right badge bg-danger" style="font-size:15px">{{App\Models\Grading\HighSchool::section_count()}}</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                        </div>
                  </div>
                  <div class="col-md-3">
                        <div class="card card-widget widget-user-2">
                              <div class="widget-user-header bg-primary">
                                <h5 class="mb-0">Senior High</h5>
                              </div>
                              <div class="card-footer p-0">
                                <ul class="nav flex-column">
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Advisory <span class="float-right badge bg-primary" style="font-size:15px">{{App\Models\Grading\CoreValue::advisory_count(null,5)}} </span>
                                    </a>
                                  </li>
                                  <li class="nav-item">
                                    <a class="nav-link">
                                      Sections <span class="float-right badge bg-danger" style="font-size:15px">{{App\Models\Grading\SeniorHigh::section_count()}}</span>
                                    </a>
                                  </li>
                                </ul>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary ">
                                <h3 class="card-title">Report Card</h3>
                        </div>
                        <div class="card-body ">
                            <table class="table">
                                    <tr>
                                          <th width="40%">Academic Program</th>
                                          <th width="15%"></th>
                                          <th width="15%"></th>
                                          <th width="15%"></th>
                                    </tr>
                                    <tr>
                                          <td>Pre-School</td>
                                          <td>
                                                <button class="btn btn-primary col-md-12 grade_b btn-sm" data-acad="ps"><i class="fas fa-balance-scale"></i> GRADES</button>
                                          </td>
                                          <td></td>
                                          <td> 
                                                {{-- <button class="btn btn-secondary col-md-12 btn-sm"><i class="fas fa-h-square"></i>HOMEROOM</button> --}}
                                          </td>
                                    </tr>
                                    <tr>
                                          <td>Grade School</td>
                                          <td>
                                                <button class="btn btn-primary col-md-12 grade_b btn-sm" data-acad="gs"><i class="fas fa-balance-scale"></i> GRADES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-danger col-md-12 corevalue_b btn-sm" data-acad="gs"><i class="fas fa-heart"></i> CORE VALUES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-secondary col-md-12 btn-sm home_room" data-acad="gs"><i class="fas fa-h-square"></i> HOMEROOM</button>
                                          </td>
                                    </tr>
                                    <tr>
                                          <td>High School</td>
                                          <td>
                                                <button class="btn btn-primary col-md-12 grade_b btn-sm" data-acad="hs"><i class="fas fa-balance-scale"></i> GRADES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-danger col-md-12 corevalue_b btn-sm" data-acad="hs"><i class="fas fa-heart"></i> CORE VALUES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-secondary col-md-12 btn-sm home_room" data-acad="hs"><i class="fas fa-h-square"></i> HOMEROOM</button>
                                          </td>
                                    </tr>
                                     <tr>
                                          <td>Senior High</td>
                                          <td>
                                                <button class="btn btn-primary col-md-12 grade_b btn-sm" data-acad="sh" ><i class="fas fa-balance-scale"></i> GRADES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-danger col-md-12 corevalue_b btn-sm" data-acad="sh"><i class="fas fa-heart"></i> CORE VALUES</button>
                                          </td>
                                          <td> 
                                                <button class="btn btn-secondary col-md-12 btn-sm home_room" data-acad="sh"><i class="fas fa-h-square"></i> HOMEROOM</button>
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
            {{-- <div class="row">
                  <div class="col-md-12">
                      <div class="card">
                            <div class="card-header p-1 bg-secondary">
                            </div>
                            <div class="card-body">
                                  <h4>Notes:</h4>
                                  <hr>
                                  <ol style="list-style: circle;">
                                      <li><b>+</b> Genererate grade status for before inputing grades</li>
                                      <li><b>+</b> Grade Proccess Notification</li>
                                      <li><b>+</b> Grade Proccess logs</li>
                                      <li><b>+</b> View grade logs</li>
                                      <li><b>+</b> Resubmit grades pending grades</li>
                                      <li><b>+</b> Restrict Higher than hps</li>
                                      <li><b>+</b> Grades color code</li>
                                      <li><b>+</b> Sort Student</li>
                                      <li><b>+</b> Restrict Teacher from inputing grades higher than the highest possible score</li>
                                      <li><b>+</b> Auto calculate grade</li>
                                      <li><b>+</b> Auto calculate grade when hps is chang</li>
                                  </ol>
                                  <h4>For update:</h4>
                                  <hr>
                                  <ul>
                                          <li><b>+</b> Restrict Teacher from inputing the highest possible score if student grade is less than</li>
                                          <li><b>+</b> Student Attendance</li>
                                          <li><b>+</b> View grade notification</li>
                                          <li><b>+</b> Printable SF9</li>
                                         
                                  </ul>
                            </div>
                      </div>
                  </div>
            </div> --}}
      </section>


@endsection

@section('footerjavascript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script>
            $(document).ready(function(){

                  $('.select2').select2()

                  const Toast = Swal.mixin({
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                              });

                  var corvalurl;
                  var gradeurl;

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

                        var syid = $('#sy').val();
                        var semid = $('#semester').val();

                        $('#modal_reportcard_title').text('CORE VALUES')

                        $.ajax({
                              type:'GET',
                              url: corvalurl,
                              data:{
                                    grade:'grade',
                                    syid:syid,
                                    semid:semid
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

                        $('#modal_reportcard_title').text('E-CLASS RECORD')
                        var syid = $('#sy').val();
                        var semid = $('#semester').val();

                        $.ajax({
                              type:'GET',
                              url: gradeurl,
                              data:{
                                    grade:'grade',
                                    syid:syid,
                                    semid:semid
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

                  $('#filter_all').unbind().click(function(){
                        loadGradeStatus()
                  })


                  function loadGradeStatus(){

                        var syid = $('#sy').val();
                        var sem = $('#semester').val();

                        $.ajax({
                              type:'GET',
                              url: '/reportcard/grade/status',
                              data:{
                                    semid:sem,
                                    syid:syid
                              },
                              success:function(data) {
                              
                                    $('#grade_status_holder').empty()
                                    $('#grade_status_holder').append(data)
                                    
                              }
                        })

                  }

                 
                  $(document).on('click','.home_room',function(e){
                        var acad_desc = ''
                        if($(this).attr('data-acad') == 'gs'){
                              corvalurl = '/reportcard/homeroom/gradeschool';
                              acad_desc = 'gs'
                        }
                        else if($(this).attr('data-acad') == 'hs'){
                              corvalurl = '/reportcard/homeroom/highschool';
                              acad_desc = 'hs'
                        }
                        else if($(this).attr('data-acad') == 'sh'){
                              corvalurl = '/reportcard/homeroom/seniorhigh';
                              acad_desc = 'sh'
                        }

                        var syid = $('#sy').val();
                        var semid = $('#semester').val();
                        $('#modal_reportcard_title').text('HOMEROOM GUIDANCE LEARNER’S DEVELOPMENT ASSESSMENT')

                        $.ajax({
                              type:'GET',
                              url: corvalurl,
                              data:{
                                    grade:'grade',
                                    syid:syid,
                                    semid:semid
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
                                          if(acad_desc == 'gs'){
                                                $('#sem_holder').attr('hidden','hidden')
                                          }
                                          else if(acad_desc == 'hs'){
                                                $('#sem_holder').attr('hidden','hidden')
                                          }
                                          else if(acad_desc == 'sh'){
                                                $('#sem_holder').removeAttr('hidden')
                                          }
                                    }
                              }
                        })


                  })

                  // function home_room()P}
                  
                  // loadGradeStatus()

            })
           
      </script>
@endsection