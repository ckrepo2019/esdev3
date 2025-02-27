
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <style>
            .select2-selection{
                height: calc(2.25rem + 2px) !important;
            }
      </style>
   

@endsection


@section('content')

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

      <section class="content-header">
            <div class="container-fluid">
                  <div class="row">
                        <div class="col-sm-6">
                        
                        </div>
                        <div class="col-sm-6">
                              <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                    <li class="breadcrumb-item active">Section Detail</li>
                              </ol>
                        </div>
                  </div>
            </div>
      </section>
      <section class="content pt-0">
            <div class="row">
                  <div class="col-12">
                        <div class="card">
                              <div class="card-header bg-primary p-1">
                              </div>
                              <div class="card-body">
                                    <div class="row">
                                          <div class="col-md-3 form-group">
                                                <label for="">Section Name Status</label>
                                                <select name="" id="section_name" class="form-control select2">
                                                      <option value="">All</option>
                                                      <option value="1">With Section Name</option>
                                                      <option value="2">Without Section Name</option>
                                                </select>
                                          </div>
                                          <div class="col-md-3 form-group">
                                                <label for="">Class Advisory Status</label>
                                                <select name="" id="class_advisory" class="form-control select2">
                                                      <option value="">All</option>
                                                      <option value="1">With Class Advisory</option>
                                                      <option value="2">Without Class Advisory</option>
                                                </select>
                                          </div>
                                    </div>
                                    <div class="row">
                                          <div class="col-md-2">
                                                <button class="btn btn-primary btn-block" id="filter"> <i class="fas fa-filter"></i> FILTER</button>
                                          </div>
                                          <div class="col-md-7">
                                          </div>
                                         
                                    </div>
                                    <hr>
                                    <div class="row mt-3">
                                          <div class="col-md-12">
                                                <table class="table" id="section_detail_list">
                                                      <thead>
                                                            <tr>
                                                                  <th width="20%">Section Name</th>
                                                                  <th width="60%">Advicer</th>
                                                                  <th width="20%">School Year</th>
                                                            </tr>
                                                      </thead>
                                                      <tbody>
      
                                                      </tbody>
                                                </table>    
                                          </div>
                                    </div>
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

                  var sections = @json($section_detail);

                  loaddatatable(sections)
                  
                  function loaddatatable(data){
  
                        $("#section_detail_list").DataTable({
                        destroy: true,
                        data:data,
                              "columns": [
                                    { "data": null },
                                    { "data": null },
                                    { "data": 'sydesc' }

                              ],
                              columnDefs: [
                                    {
                                          'targets': 0,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {

                                                $(td).addClass('section_holder')
                                                $(td).attr('data-id',rowData.id)

                                                if(rowData.sectname == null){

                                                      $(td)[0].innerHTML = '<button class="btn btn-danger btn-sm fix_setionname" data-id="'+rowData.id+'">FIX SECTION NAME</button>'

                                                }else{

                                                            $(td).text(rowData.sectname)

                                                }
                                          }
                                    },
                                    {
                                          'targets': 1,
                                          'orderable': true, 
                                          'createdCell':  function (td, cellData, rowData, row, col) {


                                                if(rowData.teacherid == null){

                                                      $(td)[0].innerHTML = '<span class="text-danger">NO ADVICER ASSIGNED</span>'

                                                }
                                                else{

                                                      $(td).text(rowData.lastname +', '+rowData.firstname)

                                                }


                                          }
                                    }
                              ]
                        })

                  }

                  $(document).on('click','#filter',function(){

                        var section_status = $('#section_name').val()
                        var class_advisory = $('#class_advisory').val()

                        console.log(section_status)

                        // console.log(sections)
                        // console.log(class_advisory)

                        var filteredsection = sections.filter(function(x){

                              var validsectname;
                              var validadviser

                              if(x.sectname == null && section_status == 2){
                                    validsectname = true;
                              }
                              else if(x.sectname != null && section_status == 1){
                                    validsectname = true;
                              }
                              else if(section_status == ''){
                                    validsectname = true;
                              }
                              else{
                                    validsectname = false
                              }

                              if(x.teacherid == null && class_advisory == 2){
                                    validadviser = true;
                              }
                              else if(x.teacherid != null && class_advisory == 1){
                                    validadviser = true;
                              }
                              else if(class_advisory == ''){
                                    validadviser = true;
                              }else{
                                    validadviser = false;
                              }
                              
                              if(validsectname && validadviser){
                                    return x;
                              }

                              

                              
                        })

                        loaddatatable(filteredsection)
                        
                  })


                  $(document).on('click','.fix_setionname',function(){

                        var detail_id = $(this).attr('data-id')

                        $.ajax({
                              type:'GET',
                              url:'/fixer/sectiondetail?fix=fix',
                              data:{
                                    detail_id: detail_id,
                              },
                              success:function(data){


                                    if(data[0].status == 1){

                                          $('.section_holder[data-id="'+detail_id+'"]').empty()
                                          $('.section_holder[data-id="'+detail_id+'"]').text(data[0].data)

                                          var detial_count =  parseInt($('#section_detail_holder')[0].innerHTML) - 1

                                          var arrayID = sections.findIndex(x => x.id == detail_id)

                                          sections[arrayID].sectname = data[0].data;

                                          $('#section_detail_holder').text(detial_count)

                                          Swal.fire({
                                                type: 'success',
                                                text: 'Section name was added successfully!'
                                          });

                                    }else{

                                          Swal.fire({
                                                type: 'error',
                                                text: 'Something went wrong!'
                                          });
                                    }
                               

                              }

                        })
                  })

               

                  
            })
      
      </script>

@endsection
