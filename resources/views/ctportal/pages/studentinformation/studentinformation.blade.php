
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')

      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
        </style>
   
@endsection

@section('content')

      @php
            
            


      @endphp


      <section class="content">
            <div class="card">
                  <div class="card-header p-1 bg-primary"></div>
                  <div class="card-body" style="min-height:1000px">
                       <div class="row">
                             <div class="col-md-12">
                                   <h3>Filter</h3>
                             </div>
                             <div class="col-md-4 form-group">
                                    <label for="">Section</label>
                                    <select name="t_s_section" id="t_s_section" class="form-control select2">
                                          <option value="">All</option>
                                          @foreach (collect($sections)->unique() as $item)
                                                <option value="{{$item->sectionid}}">{{$item->sectionname}}</option>
                                          @endforeach
                                    </select>
                              </div>
                             <div class="col-md-4 form-group">
                                   <label for="">Subject</label>
                                   <select name="t_s_subject" id="t_s_subject" class="form-control select2">
                                         <option value="">All</option>
                                         @foreach (collect($subjects)->unique('subjCode') as $item)
                                                <option value="{{$item->subjectid}}">{{$item->subjCode}}</option>
                                          @endforeach
                                   </select>
                             </div>
                             
                              <div class="col-md-4 form-group">
                                    <label for="">Exam Permit</label>
                                    <select name="per" id="per" class="form-control select2">
                                          <option value="">All</option>
                                          <option value="0">With Balance</option>
                                          <option value="1">Permitted</option>
                                    </select>
                              </div>
                             
                       </div>
                       <div class="row">
                              <div class="col-md-2">
                                    <button class="btn btn-primary" id="filter" disabled><i class="fas fa-filter"></i> FILTER</button>
                              </div>
                       </div>
                       <hr>
                       <div class="row ">
                             <div class="col-md-12">
                                   <h3>Student Information</h3>
                             </div>
                             <div class="col-md-6">
                                    <table class="table" id="student_table_male">
                                          <thead>
                                                <tr class="bg-info">
                                                      <th colspan="4">MALE</th>
                                                </tr>
                                                <tr>
                                                      <th>SID</th>
                                                      <th>Lastname</th>
                                                      <th>Firstname</th>
                                                      <th>Exam Permit</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                
                                          </tbody>
                                    </table>
                             </div>
                             <div class="col-md-6">
                                    <table class="table" id="student_table_female">
                                          <thead>
                                                <tr class="bg-pink">
                                                      <th colspan="4">FEMALE</th>
                                                </tr>
                                                <tr>
                                                      <th>SID</th>
                                                      <th>Lastname</th>
                                                      <th>Firstname</th>
                                                      <th>Exam Permit</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                
                                          </tbody>
                                    </table>
                             </div>
                       </div>
                  </div>
            </div>
      </section>
@endsection

@section('footerscript')

      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>

      <script>
            $(document).ready(function(){

                  var students;
                  
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

                  $(document).on('click','#filter',function(){

                        var section = $('#t_s_section').val()
                        var subject = $('#t_s_subject').val()
                        var permit = $('#per').val()

                        var newdataArray = []

                        $.each(students,function(a,b){

                              // console.log(b.subjectID + ' - '+ subject)

                              var valid = false;
                              var trueCount = 0;
                              var filterCount = 0;

                              if(section != null && section != ''){

                                    filterCount += 1
                                    if( b.sectionid == section ){
                                          valid = true
                                          trueCount += 1;
                                    }else{
                                          valid = false
                                    }
                                    
                              }

                              if(subject != null && subject != ''){

                                    filterCount += 1
                                    if( b.subjectID == subject ){
                                          valid = true
                                          trueCount += 1;
                                    }else{
                                          valid = false
                                    }

                              }


                              
                              if(permit != null && permit != ''){

                                    filterCount += 1
                                    if( b.permit == permit ){
                                          valid = true
                                          trueCount += 1;
                                    }else{
                                          valid = false
                                    }

                              }

                              if( trueCount == filterCount){

                                    newdataArray.push(b)

                              }

                        })

                        loaddatatable(newdataArray)

                  })


                  var data = [{
                        sid:'Processing ...',
                        lastname:'Processing ...',
                        firstname:'Processing ...',
                        permit:'Processing ...',
                  }]

                  var subjects = []

                  $('.select2').select2()

                  @foreach($subjects as $item)

                        subjects.push({
                              'subjcode':'{{$item->subjCode}}',
                              'subjid':'{{$item->subjectid}}',
                              'sectionid':'{{$item->sectionid}}'
                        })

                  @endforeach

                  $("#student_table_male").DataTable({
                        destroy: true,
                        data:data,
                        paging:   false,
                        ordering: false,
                        info:     false,
                        columns: [
                                    { "data": "sid" },
                                    { "data": "lastname" },
                                    { "data": "firstname" },
                                    { "data": "permit" },
                              ]
                  })
                  $("#student_table_female").DataTable({
                        destroy: true,
                        data:data,
                        paging:   false,
                        ordering: false,
                        info:     false,
                        columns: [
                                    { "data": "sid" },
                                    { "data": "lastname" },
                                    { "data": "firstname" },
                                    { "data": "permit" },
                              ]
                  })
                  
                  function loaddatatable(data){

                        newData = data.filter((v,i,a)=>a.findIndex(t=>(t.id === v.id))===i)
                        console.log(newData)
                        var maledata      = [];
                        var femaledata    = [];

                        $.each(newData,function(key, value){
                              if(value.gender == 'male')
                              {
                                    maledata.push(value)
                              }
                              else if(value.gender == 'female')
                              {
                                    femaledata.push(value)
                              }
                        })


                        $("#student_table_male").DataTable({
                                          destroy: true,
                                          data:maledata,
                                          columns: [
                                                      { "data": "sid" },
                                                      { "data": "lastname" },
                                                      { "data": "firstname" },
                                                      { "data": "permit" },
                                                ],
                                          columnDefs: [
                                                {
                                                      'targets': 3,
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            if(rowData.permit == 1){
                                                                  $(td)[0].innerHTML = '<a class="btn btn-sm btn-block btn-success text-white">Permitted</a>'
                                                            }else{
                                                                  $(td)[0].innerHTML = '<a class="btn btn-sm btn-block btn-danger text-white">With Balance</a>'
                                                            }
                                                            
                                                      }
                                                }
                                                
                                          ]
                                    })

                        $("#student_table_female").DataTable({
                                          destroy: true,
                                          data:femaledata,
                                          columns: [
                                                      { "data": "sid" },
                                                      { "data": "lastname" },
                                                      { "data": "firstname" },
                                                      { "data": "permit" },
                                                ],
                                          columnDefs: [
                                                {
                                                      'targets': 3,
                                                      'createdCell':  function (td, cellData, rowData, row, col) {
                                                            if(rowData.permit == 1){
                                                                  $(td)[0].innerHTML = '<a class="btn btn-sm btn-block btn-success text-white">Permitted</a>'
                                                            }else{
                                                                  $(td)[0].innerHTML = '<a class="btn btn-sm btn-block btn-danger text-white">With Balance</a>'
                                                            }
                                                            
                                                      }
                                                }
                                                
                                          ]
                                    })

                        
                  }



                  loadstudent()

                  function loadstudent(){

                        $.ajax({
                              type:'GET',
                              url: '/college/student/info?data=data',
                              success:function(data) {
                                    // console.log(data)
                                    students = data
                                    // loaddatatablemale(data)
                                    // loaddatatablefemale(data)
                                    loaddatatable(data)
                                    $('#filter').removeAttr('disabled')
                              
                              }
                        })

                  }
            
            })
      </script>

@endsection

