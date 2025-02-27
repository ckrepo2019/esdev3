
@extends('deanportal.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">

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

      @include('collegeportal.pages.forms.generalform')
      <section class="content">
            <div class="card" >
                  <div class="card-header bg-primary p-1">
                        
                  </div>
                  <div class="card-body">
                        <div class="row">
                              <div class="col-md-4">
                                    <h4>COLLEGE SUBJECTS</h4>
                              </div>
                              <div class="col-md-8">
                                    <button class="btn btn-sm btn-primary float-right" data-toggle="modal"  data-target="#{{$modalInfo->modalName}}" title="Contacts" data-widget="chat-pane-toggle" id="newsubject"><b>CREATE SUBJECT</b></button>
                              </div>
                        </div>
                        <hr>
                        <div class="row">
                              <div class="col-md-12">
                                    <table class="table" id="subjects_holder">
                                          <thead   >
                                                <tr>
                                                      <th width="20%">Code</th>
                                                      <th width="55%">Description</th>
                                                      <th width="10%">SPEC</th>
                                                      <th width="5%">Lec</th>
                                                      <th width="5%">Lab</th>
                                                      <th width="5%">Total</th>
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

@section('footerjavascript')

      <script src="{{asset('js/pagination.js')}}"></script> 
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>


      <script>
            $(document).ready(function(){


                  const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

                  $(document).on('input','#subjDesc',function(){
                        var temp_description = $(this).val()
                        var checkifExit = college_subjects.filter(x=>x.subjDesc == temp_description)
                        if(checkifExit.length > 0){
                              var html = '<dl><dt>Subject Already Exist!</dt>'
                              $.each(checkifExit,function(a,b){
                                    html +=  '<dd class="pl-3 mb-0">'+b.subjCode+' - ' +b.subjDesc+'</dd>'
                              })
                              html += '</dl>'

                              $('#subjDescError')[0].innerHTML = html
                              $('#subjDesc').addClass('is-invalid')
                        }
                        else{
                              $('#subjDesc').removeClass('is-invalid')
                        }
                  })

                  $(document).on('input','#subjCode',function(){
                        var temp_code = $(this).val()
                        var checkifExit = college_subjects.filter(x=>x.subjCode == temp_code)
                        if(checkifExit.length > 0){
                              var html = '<dl><dt>Subject Already Exist!</dt>'
                              $.each(checkifExit,function(a,b){
                                    html +=  '<dd class="pl-3 mb-0">'+b.subjCode+' - ' +b.subjDesc+'</dd>'
                              })
                              html += '</dl>'

                              $('#subjCodeError')[0].innerHTML = html
                              $('#subjCode').addClass('is-invalid')
                        }
                        else{
                              $('#subjCode').removeClass('is-invalid')
                        }
                  })
                      
                  $('.savebutton').removeAttr('onclick')
                  $('.savebutton').attr('type','button')
                  $('.savebutton').attr('data-id','0')

                  
                  // var college_subjects = @json($subjects);

                  


                  function loaddatatable(data){
  
                        $("#subjects_holder").DataTable({
                              destroy: true,
                              data:data,
                              columns: [
                                          { "data": "subjCode" },
                                          { "data": "subjDesc" },
                                          { "data": "subjClass"},
                                          { "data": "lecunits"},
                                          { "data": "labunits"},
                                          { "data": null}
                                    ],

                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).text(rowData.subjCode)

                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td)[0].innerHTML = '<a href="#" class="editSubject" data-id="'+rowData.id+'">'+rowData.subjDesc+'</a>'

                                          }
                                    },
                                    {
                                          'targets': 2,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                               if(rowData.subjClass == 1){
                                                      $(td)[0].innerHTML = '<span class="d-block badge badge-success">MAJOR</span>'
                                               }
                                               else if(rowData.subjClass == 2){
                                                      $(td)[0].innerHTML = '<span class="d-block badge badge-success">MINOR</span>'
                                               }else{
                                                      $(td)[0].innerHTML = '<span class="d-block badge badge-danger">NS</span>'
                                               }
                                          }
                                    },
                                    {
                                          'targets': 3,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 4,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                          }
                                    },
                                    {
                                          'targets': 5,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {
                                                $(td).addClass('text-center')
                                                var total = parseInt( rowData.lecunits ) + parseInt(rowData.labunits)
                                                $(td).text(total)
                                          }
                                    }
                              ]
                             
                        });

                  }

                  var select_subject

                  $(document).on('click','.editSubject',function(){

                        $('#subjectModal').modal('show')

                        var temp_subjid = $(this).attr('data-id')
                        select_subject = temp_subjid
                        
                        $('.savebutton')[0].innerText = 'UPDATE'
                        var temp_college_subjects =  college_subjects.filter(x => x.id == temp_subjid)

                        $('.savebutton').attr('data-id',1)
                        $('#subjDesc').val(temp_college_subjects[0].subjDesc)
                        $('#subjCode').val(temp_college_subjects[0].subjCode)
                        $('#lecunits').val(temp_college_subjects[0].lecunits)
                        $('#labunits').val(temp_college_subjects[0].labunits)
                        $('#subjclass').val(temp_college_subjects[0].subjClass).change()


                  })


                  $('#subjectModal').on('hidden.bs.modal', function () {
                        $('.savebutton').attr('data-id',0)
                        $('#subjectModalForm')[0].reset()
                  });

                  load_collegesubjects()
                  

                  function load_collegesubjects(){

                        $.ajax( {
                              url: '/dean/collegesubjects/list',
                              type: 'GET',
                              success:function(data) {
                                    college_subjects = data
                                    loaddatatable(data)
                              }
                        });

                  }

                  $(document).on('click','.savebutton',function(){

                        if($(this).attr('data-id') == 0){

                              $.ajax( {
                                    url: '/collegesubjects?create=create&subjDesc='+$('#subjDesc').val()+'&subjCode='+$('#subjCode').val()+'&lecunits='+$('#lecunits').val()+'&labunits='+$('#labunits').val()+'&subjclass='+$('#subjclass').val(),
                                    type: 'GET',
                                    success:function(data) {
                                          
                                          if(data[0].status == 0){

                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Error!'
                                                })

                                                $.each(data[0].errors,function(a,b){
                                                      $('#'+a).addClass('is-invalid')
                                                      $('#'+a+'Error strong').text(b)
                                                })

                                          }else{

                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Created Successfully!'
                                                })

                                                $('#subjectModalForm')[0].reset()
                                                $('#subjectModal').modal('hide')


                                                $('.is-invalid').removeClass('is-invalid')
                                                load_collegesubjects()
                                          }
                                    
                                    
                                    }
                              });
                        }
                        else{

                              $.ajax( {
                                    url: '/collegesubjects?update=update&subjid='+select_subject+'&subjDesc='+$('#subjDesc').val()+'&subjCode='+$('#subjCode').val()+'&lecunits='+$('#lecunits').val()+'&labunits='+$('#labunits').val()+'&subjclass='+$('#subjclass').val(),
                                    type: 'GET',
                                    success:function(data) {
                                          
                                          if(data[0].status == 0){
                                                Toast.fire({
                                                      type: 'error',
                                                      title: 'Error!'
                                                })
                                                $.each(data[0].errors,function(a,b){
                                                      $('#'+a).addClass('is-invalid')
                                                      $('#'+a+'Error strong').text(b)
                                                })
                                          }else{
                                                var subjid = college_subjects.findIndex(x => x.id == select_subject)
                                                college_subjects[subjid].subjDesc = $('#subjDesc').val()
                                                college_subjects[subjid].subjCode = $('#subjCode').val()
                                                college_subjects[subjid].lecunits = $('#lecunits').val()
                                                college_subjects[subjid].labunits = $('#labunits').val()
                                                college_subjects[subjid].subjClass = $('#subjclass').val()
                                                var temp_search = $('input[type="search"]').val()
                                                loaddatatable(college_subjects)
                                                $('input[type="search"]').val(temp_search).change()
                                                Toast.fire({
                                                      type: 'success',
                                                      title: 'Updated Successfully!'
                                                })

                                                $('#subjectModalForm')[0].reset()
                                                $('#subjectModal').modal('hide')

                                                $('.is-invalid').removeClass('is-invalid')

                                          }
                                    
                                    
                                    }
                              });
                        }

                  })

                  $(document).on('click','#newsubject',function(){
                        $('.savebutton')[0].innerText = 'CREATE'
                        $('#subjDesc').removeClass('is-invalid')
                        $('#subjCode').removeClass('is-invalid')
                  })

            })
                  
      </script>
@endsection

