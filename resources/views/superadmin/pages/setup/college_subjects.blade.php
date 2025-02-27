
@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }
      else if(Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(Session::get('currentPortal') == 14){
            $extend = 'deanportal.layouts.app2';
      }
      else if(Session::get('currentPortal') == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }
      else if(auth()->user()->type == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 14){
            $extend = 'deanportal.layouts.app2';
      }
      else if(auth()->user()->type == 16){
            $extend = 'chairpersonportal.layouts.app2';
      }
@endphp


@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <style>
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection


@section('content')




<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>College Subjects</h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">College Subjects</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="info-box shadow-lg">
                              <div class="info-box-content">
                                    <div class="row">
                                          <div class="col-md-4">
                                               <h5><i class="fa fa-filter"></i> Filter</h5> 
                                          </div>
                                          <div class="col-md-8">
                                                
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2  form-group mb-0">
                                                <label for="">Subjects</label>
                                                <select class="form-control select2" id="filter_usage">
                                                      <option value="">All</option>
                                                      <option value="1">Used</option>
                                                      <option value="2">Not Used</option>
                                                </select>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow" style="">
                              <div class="card-body">
                                    <div class="row mt-2">
                                          <div class="col-md-12">
                                                <table class="table-hover table table-striped table-sm table-bordered" id="availsubj_datatable" width="100%" >
                                                      <thead>
                                                            <tr>
                                                                 
                                                                  <th width="14%" class="align-middle">Code</th>
                                                                  <th width="47%">Subject Description</th>
                                                                  <th width="18%">Subj. Group</th>
                                                                  <th width="5%" class="align-middle text-center p-0">Lect.</th>
                                                                  <th width="5%" class="align-middle text-center p-0">Lab.</th>
                                                                  <th width="4%" class="align-middle text-center p-0"></th>
                                                                  <th width="5%" class="align-middle text-center p-0"></th>
                                                            </tr>
                                                      </thead>
                                                </table>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>
</section>

@endsection

@section('footerjavascript')
      <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
      <script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
      <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
      <script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
      <script src="{{asset('js/setupjs/college-subjgroup.js') }}"></script>

      <script>
            const Toast = Swal.mixin({
                               toast: true,
                               position: 'top-end',
                               showConfirmButton: false,
                               timer: 2000,
                         })
         </script>

      <script>
            
            $('.select2').select2()

            $(document).on('change','#filter_usage',function(){
                  available_subject_datatable()
            })

            $(document).on('click','.delete_subject',function(){

                  var subjid = $(this).attr('data-id')


                  Swal.fire({
                        text: 'Are you sure you want to remove subject?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                  }).then((result) => {
                        if (result.value) {
                              $.ajax({
                                    type:'GET',
                                    url:'/setup/prospectus/subjets/remove',
                                    data:{
                                          subjid:subjid
                                    },
                                    success:function(data) {
                                          if(data[0].status == 1){
                                                      Toast.fire({
                                                            type: 'success',
                                                            title: data[0].message
                                                      })
                                                      available_subject_datatable()
                                          }else{
                                                Toast.fire({
                                                      type: 'error',
                                                      title: data[0].message
                                                })
                                          }
                                                
                                    }
                              })
                        }
                  })
            })

            available_subject_datatable()

            function available_subject_datatable(){

                  $("#availsubj_datatable").DataTable({
                        destroy: true,
                        bInfo: false,
                        autoWidth: false,
                        lengthChange: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: '/setup/prospectus/subjets/all',
                              type: 'GET',
                              data:{
                                    // courseid:selected_course,
                                    checkusage:true,
                                    filter_usage: $('#filter_usage').val(),
                                    curriculumid:$('#curriculum_filter').val(),
                              },
                              dataSrc: function ( json ) {
                                    all_students = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    // { "data": null },
                                    // { "data": null },
                                    { "data": "subjCode" },
                                    { "data": "subjDesc" },
                                    { "data": "description" },
                                    { "data": "lecunits" },
                                    { "data": "labunits" },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              // {
                              //       'targets': 0,
                              //       'orderable': false, 
                              //       'createdCell':  function (td, cellData, rowData, row, col) {
                                    
                              //             // var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                              //             // if(temp_subjinfo.length > 0){

                              //             //       temp_subjinfo = temp_subjinfo[0]
                              //             //       var temp_yearlevel = ''
                                                
                              //             //       if(temp_subjinfo.yearID == 17){
                              //             //             temp_yearlevel = '1ST YEAR';
                              //             //       }else if(temp_subjinfo.yearID == 18){
                              //             //             temp_yearlevel = '2ND YEAR';
                              //             //       }else if (temp_subjinfo.yearID == 19){
                              //             //             temp_yearlevel = '3RD YEAR';
                              //             //       }else if (temp_subjinfo.yearID == 20){
                              //             //             temp_yearlevel = '4TH YEAR';
                              //             //       }else if (temp_subjinfo.yearID == 21){
                              //             //             temp_yearlevel = '5TH YEAR';
                              //             //       }

                              //             //       $(td)[0].innerHTML = temp_yearlevel
                              //             // }else{
                              //                   $(td)[0].innerHTML = null
                              //             // }
                              //             $(td).addClass('text-center')
                              //             $(td).addClass('align-middle')
                              //       }
                              // },
                              // {
                              //       'targets': 1,
                              //       'orderable': false, 
                              //       'createdCell':  function (td, cellData, rowData, row, col) {
                              //             // var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                              //             // if(temp_subjinfo.length > 0){

                              //             //       temp_subjinfo = temp_subjinfo[0]

                              //             //       var temp_semester = ''
                                                
                              //             //       if(temp_subjinfo.semesterID == 1){
                              //             //             temp_semester = '1st Sem.';
                              //             //       }else if(temp_subjinfo.semesterID == 2){
                              //             //             temp_semester = '2nd Sem.';
                              //             //       }else if (temp_subjinfo.semesterID == 3){
                              //             //             temp_semester = 'Summer';
                              //             //       }

                              //             //       $(td)[0].innerHTML = temp_semester
                              //             // }else{
                              //                   $(td)[0].innerHTML = null
                              //             // }
                              //             $(td).addClass('text-center')
                              //             $(td).addClass('align-middle')
                              //       }
                              // },
                              // {
                              //       'targets': 2,
                              //       'orderable': true, 
                              //       'createdCell':  function (td, cellData, rowData, row, col) {

                              //             // var temp_subjinfo = subject_list.filter(x=>x.subjectID == rowData.id)
                              //             // if(temp_subjinfo.length > 0){
                                          
                              //             //       var text = rowData.subjDesc';
                              //             //       $(td)[0].innerHTML = text
                              //             // }else{
                              //             //       $(td)[0].innerHTML = '<span class="all_subj_info" id="'+rowData.id+'">'+rowData.subjDesc+'</span>'
                              //             // }

                              //             var text = rowData.subjDesc;
                              //             $(td)[0].innerHTML = text
                              //             $(td).addClass('align-middle')
                              //       }
                              // },
                              {
                                    'targets': 0,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.description == null){
                                                $(td)[0].innerHTML = '<a href="javascript:void(0)" class="assign_subjgroup text-danger" data-id="'+rowData.id+'">Not Assigned</a>'
                                          }else {
                                                $(td)[0].innerHTML = '<a href="javascript:void(0)" class="assign_subjgroup" data-id="'+rowData.id+'">'+rowData.description+'</a>'
                                          }
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                        
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          // if(subject_list.filter(x=>x.subjectID == rowData.id).length > 0){
                                          //       $(td)[0].innerHTML = ''
                                          // }else{
                                          //       var buttons = '<a href="javascript:void(0)" class="add_subject_to_prospectus" data-id="'+rowData.id+'"><i class="fas fa-plus text-primary"></i></a>';
                                          //       $(td)[0].innerHTML = buttons
                                          // }
                                          var usage_count = rowData.usageinfo.length

                                          $(td)[0].innerHTML = usage_count

                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {

                                          var buttons = '<a href="javascript:void(0)" class="delete_subject" data-id="'+rowData.id+'"><i class="far fa-trash-alt text-danger"></i></a>';
                                          $(td)[0].innerHTML =  buttons

                                          $(td).addClass('text-center')
                                          $(td).addClass('align-middle')
                                    }
                              },
                        ]
                        
                  });

                  var label_text = $($("#availsubj_datatable_wrapper")[0].children[0])[0].children[0]
                  $(label_text)[0].innerHTML = '<label for="" class="mb-0 pt-2">Available Subjects</label>'

                  }
      </script>
     
@endsection


