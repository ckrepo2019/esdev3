
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

      <div class="modal fade" id="instructorgrades" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content ">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">TEACHER SUBJECTS</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body " style="height: 500px" id="teachersubjecttable">
                             
                        </div>
                  </div>
            </div>
      </div>

      
      <div class="modal fade" id="studentgrades" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                  <div class="modal-content ">
                        <div class="modal-header bg-success">
                              <h5 class="modal-title">STUDENT GRADES</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                              </button>
                        </div>
                        <div class="modal-body table-responsive" style="height: 500px" id="studentgradestable">
                             
                        </div>
                        <div class="modal-footer">
                              <button class="btn btn-primary">
                                    POST GRADE
                              </button>
                        </div>
                  </div>
            </div>
      </div>

      <section class="content">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card">
                              <div class="card-header">
                                    Courses
                              </div>
                              <div class="card-body">
                                    <table class="table">
                                          <thead>
                                                <tr>
                                                      <th width="5%"></th>
                                                      <th width="50%" class="align-middle">Teacher</th>
                                                      <th width="10%" style="font-size:10px" class="text-center align-middle">Subject <br>Count</th>
                                                      <th width="10%" style="font-size:10px" class="text-center">Sumitted Midterm Grades</th>
                                                      <th width="10%" style="font-size:10px" class="text-center">Sumitted Finalterm Grades</th>
                                                </tr>
                                                
                                          </thead>
                                          <tbody>
                                                @foreach ($teachers as $item)
                                                      <tr class="teacherinfo" data-id="{{$item->id}}">
                                                            <td>
                                                                  <div class="dropdown">
                                                                        <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                              <i class="fas fa-ellipsis-v"></i>
                                                                        </button>
                                                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                        <a class="dropdown-item viewsubjects" 
                                                                           href="#" 
                                                                           data-id="{{$item->id}}"
                                                                        ><i class="fas fa-eye pr-2"></i> View Subjects</a>
                                                                        </div>
                                                                  </div>
                                                            </td>
                                                            <td>{{$item->lastname.', '.$item->firstname}}</td>
                                                            <td class="subjcount text-center" data-id="{{$item->id}}"></td>
                                                            <td class="midtermsubmitted text-center" data-id="{{$item->id}}"></td>
                                                            <td class="finaltermsubmitted text-center" data-id="{{$item->id}}"></td>
                                                      </tr>
                                                @endforeach
                                          </tbody>
                                    </table>
                              </div>
                        </div>
                  </div>
                  
            </div>
      </section>
@endsection

@section('footerjavascript')


      <script>
            $(document).ready(function(){

                  $(document).on('click','.viewsubjects',function(){
                        $('#instructorgrades').modal('show')

                              var teacherid = $(this).attr('data-id')

                              $.ajax( {
                                    url: '/submittedgrades?teacherid='+teacherid+'&table=table&teachersubj=teachersubj',
                                    type: 'GET',
                                    success:function(data) {
                                    
                                         $('#teachersubjecttable').empty()
                                         $('#teachersubjecttable').append(data)
                                    
                                    }
                              });

                  })

                  $(document).on('click','.viewmidtermgrades',function(){

                        var selectedsubjid = $(this).attr('data-id')
                        var selectedSection = $(this).attr('data-section')
                        $('#studentgrades').modal('show')

                        $.ajax({
                              type:'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/studentgradesdetail?&table=table&term=mid&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                              success:function(data) {
                                    $('#studentgradestable').empty()
                                    $('#studentgradestable').append(data)
                             
                              }

                        })

                  })
                  
                  $(document).on('click','.viewfinalgrades',function(){

                        var selectedsubjid = $(this).attr('data-id')
                        var selectedSection = $(this).attr('data-section')
                        $('#studentgrades').modal('show')

                        $.ajax({
                              type:'GET',
                              data: {'_token': '{{ csrf_token() }}'},
                              url:'/studentgradesdetail?&table=table&term=final&sectionid='+selectedSection+'&subjid='+selectedsubjid,
                              success:function(data) {
                                    $('#studentgradestable').empty()
                                    $('#studentgradestable').append(data)
                        
                              }

                        })

                  })

                  



                  $('.teacherinfo').each(function(){

                        var teacherid = $(this).attr('data-id')

                        $.ajax( {
                              url: '/submittedgrades?teacherid='+$(this).attr('data-id')+'&countsubjects=countsubjects',
                              type: 'GET',
                              success:function(data) {
                              
                                    $('td[class="subjcount text-center"][data-id="'+teacherid+'"]').text(data[0].subjectcount)
                              
                                    $('td[class="midtermsubmitted text-center"][data-id="'+teacherid+'"]').text(data[0].submittedmidtermgrades)

                                    $('td[class="finaltermsubmitted text-center"][data-id="'+teacherid+'"]').text(data[0].submittedfinaltermgrades)
                              
                              }
                        });
                  })

                
            })
      </script>

@endsection

