@php
      if(auth()->user()->type == 17){
            $extend = 'superadmin.layouts.app2';
      }else if(auth()->user()->type == 3 || Session::get('currentPortal') == 3){
            $extend = 'registrar.layouts.app';
      }else if(auth()->user()->type == 2 || Session::get('currentPortal') == 2){
            $extend = 'principalsportal.layouts.app2';
      }
@endphp

@extends($extend)

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
                  border: 0 !important;
            }
            .no-border-col{
                  border-left: 0 !important;
                  border-right: 0 !important;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }
      </style>
@endsection


@section('content')

@php
      $sy = DB::table('sy')->orderBy('sydesc')->get(); 
      $semester = DB::table('semester')->orderBy('semester')->get(); 
      $gradelevel = DB::table('gradelevel')->where('deleted',0)->orderBy('sortid')->get(); 
@endphp



<div class="modal fade" id="upload_documents_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Documents Upload ( <span class="studentname"></span> )</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-0">
                        <div class="row">
                              <form id="uploadformat" 
                                                action="/sf9template/upload" 
                                                method="POST" 
                                                enctype="multipart/form-data"
                                                class="col-md-12 "
                                                >
                                                @csrf
                                          <table class="table table-sm">
                                                <thead>
                                                      <tr>
                                                            <th width="50%">Description</th>
                                                            <th width="50%"></th>
                                                      </tr>
                                                </thead>
                                                <tbody id="document_list">

                                                </tbody>
                                          </table>

                                          <button class="btn btn-primary btn-sm">Upload</button>
                              </form>
                        </div>
                        <div class="row">
                              <div class="col-md-12">
                                    
                              </div>
                        </div>
                       
                  </div>
            </div>
      </div>
</div>   


<div class="modal fade" id="uploaded_documents_modal" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg">
            <div class="modal-content">
                  <div class="modal-header pb-2 pt-2 border-0">
                        <h4 class="modal-title" style="font-size: 1.1rem !important">Uploaded Documents ( <span class="studentname"></span> )</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                  </div>
                  <div class="modal-body pt-1" id="uploaded_document_holder">
                       <div class="row">
                              <div class="col-md-12">
                                    <table class="table table-sm">
                                          <thead>
                                                <tr>
                                                      <th width="29%">Description</th>
                                                      <th width="53%">File Name</th>
                                                      <th width="6%"></th>
                                                      <th width="6%"></th>
                                                      <th width="6%"></th>
                                                </tr>
                                          </thead>
                                          <tbody id="uploaded_document_list">

                                          </tbody>
                                    </table>
                              </div>
                       </div>
                        <div class="row">
                              <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm" id="download_all"><i class="fas fa-download"></i> Download All</button>      
                              </div>      
                        </div>                       
                  </div>
            </div>
      </div>
</div>   

<section class="content-header">
      <div class="container-fluid">
            <div class="row mb-2">
                  <div class="col-sm-6">
                        <h1>Student Requirements </h1>
                  </div>
                  <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active">Student Requirements</li>
                  </ol>
                  </div>
            </div>
      </div>
</section>
    
<section class="content pt-0">
      <div class="container-fluid">
            <div class="row">
                  <div class="col-md-12">
                        <div class="card shadow">
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-12" style="font-size:.9rem !important">
                                                <table class="table table-sm" id="students_table" >
                                                      <thead>
                                                            <tr>
                                                                  <th width="10%">SID</th>
                                                                  <th width="8%">Status</th>
                                                                  <th width="35%">Student Name</th>
                                                                  <th width="10%">Grade Level</th>
                                                                  <th width="12%" class="text-center">Uploaded</th>
                                                                  <th width="15%" class="text-center">Monitoring</th>
                                                                  <th width="10%"></th>
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


      <script>

            

            var paramstudid = location.search.split('student=')[1]

            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                  })
            
            var all_students = [];
            var selectedstudent = []
            // $(document).on('click','.upload_requirement',function(){
                 
            // })

            $(document).on('click','#download_all',function(){
                  downloadall()
            })

            

            $(document).on('click','.upload_documents',function(){
                  var levelid = $(this).attr('data-levelid')
                  var tempid = $(this).attr('data-studid')
                  selectedstudent = all_students.filter(x=>x.id == tempid)
           
                  get_levelrequirements(levelid)
                  
                  $('.studentname').text(selectedstudent[0].sid +' - '+selectedstudent[0].studentname)
                  $('#upload_documents_modal').modal()
            })

            $(document).on('click','.view_uploaded',function(){
                  var levelid = $(this).attr('data-levelid')
                  var tempid = $(this).attr('data-studid')
                  selectedstudent = all_students.filter(x=>x.id == tempid)
                  var sid = selectedstudent[0].sid

                  uploadeddocs(tempid,levelid,sid)
                  
                  $('.studentname').text(selectedstudent[0].sid +' - '+selectedstudent[0].studentname)
                  $('#uploaded_documents_modal').modal()
            })

            $(document).on('click','.deleteuploadeddocs',function(){
                  var id = $(this).attr('data-id')
                  var studid = $(this).attr('data-studid')
                  var requirement = $(this).attr('data-requirement')
                  var levelid = $(this).attr('data-levelid')
            
                  Swal.fire({
                        text: 'Are you sure you want to delete document?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Delete'
                  }).then((result) => {
                        if (result.value) {
                              deleteuploadeddocs(id,studid,requirement,levelid)
                        }
                  })
                 
            })


            

            $('#uploadformat' ).submit( function( e ) {

                  var inputs = new FormData(this)
                  inputs.append('studid',selectedstudent[0].id)
                  inputs.append('sid',selectedstudent[0].sid)
                  inputs.append('levelid',selectedstudent[0].levelid)

                  $.ajax({
                        url: '/student/requirements/upload',
                        type: 'POST',
                        data: inputs,
                        processData: false,
                        contentType: false,
                        success:function(data) {
                              if(data[0].status == 1){
                                    get_students()
                              }
                              Toast.fire({
                                    type: data[0].icon,
                                    title: data[0].message
                              })
                        }
                  })

                  e.preventDefault();

            })

            function downloadall(){
                  var levelid = selectedstudent[0].levelid
                  var studid = selectedstudent[0].id
                  var sid = selectedstudent[0].sid
                  window.open("/student/requirements/downloadall?studid="+studid+"&levelid="+levelid+"&sid="+sid);
            }

            function get_levelrequirements(levelid){
                  $('#document_list').empty()
                  $.ajax({
                        type:'GET',
                        url: '/superadmin/setup/document/list',
                        data:{
                              levelid:levelid
                        },
                        success:function(data){
                              if(data.length > 0){
                                    $.each(data,function(a,b){
                                          if(b.isActive == 1){
                                                $('#document_list').append('<tr><td>'+b.description+'</td><td><input class="form-control form-control-sm" type="file" name="req'+b.id+'[]" multiple></td></tr>')
                                          }
                                    })
                              }else{
                                    $('#document_list').append('<tr><td colspan="2">No documents setup. <a href="/setup/document" target="blank_">Click here</a> to setup document list.</td></tr>')
                              }
                              
                              
                        }
                  })
            }

            function deleteuploadeddocs(id, studid, requirement, levelid){
                  $.ajax({
                        type:'GET',
                        url: '/student/requirements/deleteuploadeddocs',
                        data:{
                              studid:studid,
                              id:id,
                              requirement:requirement
                        },
                        success:function(data){

                              if(data[0].status == 1){
                                    uploadeddocs(studid,levelid)
                                    get_students()
                              }
                              Toast.fire({
                                    type: data[0].icon,
                                    title: data[0].message
                              })
                             
                        }
                  })
            }

            function uploadeddocs(studid,levelid,sid){

                  $('#document_list').empty()
                  $('#uploaded_document_list').empty()

                  $.ajax({
                        type:'GET',
                        url: '/student/requirements/uploadeddocs',
                        data:{
                              studid:studid,
                              levelid:levelid,
                              sid:sid
                        },
                        success:function(data){
                              if(data.length > 0){
                                    $.each(data,function(a,b){

                                          if(b.uploaded.length > 0){
                                                $.each(b.uploaded,function(c,d){
                                                      var viewmage = `<td class="text-center align-midle"><a href="`+d.picurl+`" target="_blank"><i class="fas fa-eye"></i></a></td>`
                                                      if(d.filetype.includes('image')){
                                                            icon = '<i class="fas fa-image"></i>'
                                                      }else if(d.filetype.includes('spreadsheet')){
                                                            viewmage = ''
                                                            icon = '<i class="far fa-file-excel"></i>'
                                                      }else if(d.filetype.includes('pdf')){
                                                            icon = '<i class="far fa-file-pdf"></i>'
                                                      }

                                                      

                                                      var temprow = `<tr>
                                                                        <td class="align-middle">`+d.description+`</td>
                                                                        <td> `+icon+' '+d.filename+`</td>     
                                                                        <td class="text-center align-midle"><a href="javascript:void(0)" class="deleteuploadeddocs pl-2" data-studid="`+studid+`"  data-id="`+d.id+`" data-levelid="`+levelid+`"  data-requirement="`+d.requirement+`"><i class="far fa-trash-alt text-danger" ></i></a></td><td class="text-center align-midle"><a href="`+d.picurl+`" download><i class="fas fa-download"></i></a></td>`+viewmage+`     
                                                                  </tr>`
                                                      $('#uploaded_document_list').append(temprow)
                                                })
                                          }else{
                                                $('#uploaded_document_list').append('<tr><td>'+b.description+'</td><td colspan="4">No Files Uploaded!</td></tr>')
                                          }

                                    })
                              }else{
                                    $('#uploaded_document_list').append('<tr><td colspan="3">No Files Uploaded!</td></tr>')
                              }
                              
                        }
                  })
                  
            }

            get_students()
            if(paramstudid != undefined){
                  $('input[type="search"]').val(paramstudid)
            }

            function get_students(){

                  $('#students_table').DataTable({
                        destroy: true,
                        autoWidth: false,
                        lengthChange: false,
                        stateSave: true,
                        serverSide: true,
                        processing: true,
                        ajax:{
                              url: '/student/requirements/students',
                              type: 'GET',
                              dataSrc: function ( json ) {
                                    // buildings_datatable = json.data
                                    all_students = json.data
                                    return json.data;
                              }
                        },
                        columns: [
                                    { "data": "sid" },
                                    { "data": null },
                                    { "data": "studentname" },
                                    { "data": "levelname" },
                                    { "data": null },
                                    { "data": null },
                                    { "data": null },
                              ],
                        columnDefs: [
                              {
                                    'targets': 0,
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 1,
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          if(rowData.studtype == 'new'){
                                                $(td).text('New')
                                          }else if(rowData.studtype == 'old'){
                                                $(td).text('Old')
                                          }else{
                                                $(td).text(null)
                                          }
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 2,
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 3,
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                    }
                              },
                              {
                                    'targets': 4,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td)[0].innerHTML = '<a data-studid="'+rowData.id+'" data-levelid="'+rowData.levelid+'" href="javascript:void(0)" class="view_uploaded">'+rowData.uploaded.length+' Documents</a>'
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                          // $(td).text(null)
                                    }
                              },
                              {
                                    'targets': 5,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          $(td).addClass('align-middle')
                                          $(td).text(null)
                                          $(td)[0].innerHTML = rowData.monitoring+' / '+rowData.docnum
                                          $(td).addClass('text-center')
                                    }
                              },
                              {
                                    'targets': 6,
                                    'orderable': false, 
                                    'createdCell':  function (td, cellData, rowData, row, col) {
                                          var button = '<button class="btn btn-primary btn-sm upload_documents" style="font-size:.8rem !important" data-studid="'+rowData.id+'" data-levelid="'+rowData.levelid+'">Upload</button>'
                                          $(td)[0].innerHTML = button
                                          $(td).addClass('align-middle')
                                          $(td).addClass('text-center')
                                          $(td)[0].inner
                                    }
                              },
                              
                        ]
                  });

            }

      </script>


@endsection


