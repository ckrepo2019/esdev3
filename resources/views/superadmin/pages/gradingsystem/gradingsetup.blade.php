
@extends('superadmin.layouts.app2')

@section('pagespecificscripts')


    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/pagination.css')}}">
    <style>
        .select2-selection{
            height: calc(2.25rem + 2px) !important;
        }
    </style>
    @php
        $acadmicprogm = DB::table('academicprogram')->select('id','progname')->get();
        $specification = DB::table('grading_system_tspecification')->select('id','description')->get();
    @endphp

@endsection


@section('modalSection')

   
    
    <div class="modal fade" id="grading_type_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <td with="5%"></td>
                                <td with="95%">Description</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (DB::table('grading_system_type')->get() as $item)
                                <tr>
                                    <td>{{$item->id}}</td>
                                    <td>{{$item->description}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="select_academicprogram" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Academic Program</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form id="t_s_a_f">
                        <div class="form-group">
                            <label for="">Academic Program</label>
                            <select name="" id="testing_select_acadprog" class="form-control">
                                <option value="">Select Academic Program</option>
                                @foreach($acadmicprogm as $item)
                                    <option value="{{$item->id}}">{{$item->progname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Grading System</label>
                            <select name="" id="t_s_gs" class="form-control">
                                <option value="">Select Grading System</option>
                                @foreach(DB::table('grading_system')->where('deleted',0)->get() as $item)
                                    <option value="{{$item->id}}" hidden data-acad="{{$item->acadprogid}}" data-action="test">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="select_acadprog_submit">SELECT</button>
                </div>
            </div>
        </div>
    </div>


    {{-- //testing --}}
    <div class="modal fade" id="grading_sys_test" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Grading System Test</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="height: 1065px">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Teacher</label>
                            <select name="t_id" id="t_id" class="form-control select2">
                                <option value="">Select a teacher</option>
                                @foreach(DB::table('teacher')->where('isactive',1)->where('deleted',0)->get() as $item)
                                    <option value="{{$item->id}}">{{$item->lastname.', '.$item->firstname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">Specification</label>
                            <select name="s_id" id="s_id" class="form-control select2">
                                <option value="">Select a specification</option>
                                @foreach(DB::table('grading_system_tspecification')->get() as $item)
                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                           <button class="btn btn-primary btn-block" id="p_t_g"><i class="fas fa-vial"></i> TEST</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row" >
                        <div class="col-md-12" id="test_blade_holder">

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

    <div class="modal fade" id="create_grading_system_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Create Grading System</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form id="create_grading_system_form" autocomplete="off">
                        <div class="form-group" >
                                <label for="">Grading Description</label>
                                <input type="text" class="form-control" id="grading_system_description">
                        </div>
                        <div class="form-group">
                            <label for="">Grading Type</label>
                            <select value="" class="form-control" id="grading_system_type">
                                <option value="">Select Grading Type</option>
                                @foreach (DB::table('grading_system_type')->select('id','description')->get() as $item)
                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Specification</label>
                            <select name="" id="gs_specification" class="form-control">
                                <option value="">Select Specification</option>
                                @foreach($specification as $item)
                                    <option value="{{$item->id}}">{{$item->description}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Academic Program</label>
                            <select name="" id="grading_system_acadprog" class="form-control">
                                <option value="">Select Academic Program</option>
                                @foreach($acadmicprogm as $item)
                                    <option value="{{$item->id}}">{{$item->progname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" id="track_holder" hidden>
                            <label for="">Track</label>
                            <select name="" id="grading_track" class="form-control">
                                <option value="">Select Track</option>
                                <option value="1">Acadmic</option>
                                <option value="2">TVL</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Active</label>
                            <select name="" id="grading_system_isactive" class="form-control">
                                <option value="0">Inactive</option>
                                <option value="1">Active</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" id="create_grading_system_submit">CREATE GRADING SYSTEM</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="grading_system_detail_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Grading System Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="height: 572px;">
                    <div class="row">
                        <div class="col-md-6"><h5 id="grade_system_description"></h5></div>
                        <div class="col-md-6 text-right"><h5 id="grade_system_type">Percentage</h5></div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-block" id="create_grading_system_detail_button"><i class="fas fa-plus"></i> Create Detail</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-secondary btn-block" id="create_gs_ratingvalue_button"><i class="fas fa-eye"></i> Rating Value</button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary btn-secondary btn-block" id="subject_assignment_button"><i class="fas fa-eye"></i> Subject Assignment</button>
                        </div>
                    </div>
                    <div class="mt-2 table-responsive" id="grading_system_detail_table_holder" style="height: 314px;">
                    </div>
                </div>
             
            </div>
        </div>
    </div>
    <div class="modal fade" id="subject_assignment_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Grading System Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body"  id="subject_assignment_holder">
                   
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="gs_ratingvalue_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Rating Value</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="height: 572px;">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary" id="c_gsd_rv_b"><i class="fas fa-plus"></i> Create Rating Value</button>
                        </div>
                    </div>
                    <div class="row mt-3" id="c_gsd_rv_tb">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="c_gsd_rv_m" style="display: none;" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title">Rating Value</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="height: 500px;">
                    <form id="gsd_rv_form" autocomplete="off">
                            <div class="form-group" >
                                <label for="">Rating Description</label>
                                <input type="text" class="form-control" id="gsd_rv_d">
                            </div>
                            <div class="form-group" >
                                <label for="">Value</label>
                                <input type="text" class="form-control" id="gsd_rv_v">
                            </div>
                            <div class="form-group" >
                                <label for="">Sort</label>
                                <input type="text" class="form-control" id="gsd_rv_s">
                            </div>
                           
                    </form>
                    <button class="btn btn-primary" id="c_gsd_rv_s"><i class="fas fa-save"></i> SAVE</button>
                </div>
                
            </div>
        </div>
    </div>
   
    <div class="modal fade" id="create_grading_system_detail_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
              <div class="modal-content">
                    <div class="modal-header bg-primary">
                    <h4 class="modal-title">Create Grading System Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body" style="height: 500px;">
                        <form id="create_grading_system_detail_form" autocomplete="off">
                                <div class="form-group" >
                                      <label for="">Grading Description</label>
                                      <input type="text" class="form-control" id="grading_system_detail_description">
                                </div>
                                <div class="form-group" >
                                      <label for="" id="detail_value_label">Value</label>
                                      <input type="text" class="form-control" id="grading_system_detail_value">
                                </div>
                                <div class="form-group" >
                                    <label for="">Sort</label>
                                    <input type="text" class="form-control" id="grading_system_detail_sort" onkeyup="this.value = this.value.toUpperCase();">
                                </div>
                                <div class="form-group" hidden id="gsd_rv_i_h">
                                    <label for="">Columns</label>
                                    <input type="text" class="form-control" id="gsd_rv_i">
                                </div>
                                <div class="form-group" hidden id="gsd_rv_g_h">
                                    <label for="">Group</label>
                                    <input type="text" class="form-control" id="gsd_rv_g">
                                </div>
                                <div class="form-group" hidden id="gsd_rv_sf9_h">
                                    <label for="">SF9 value</label>
                                    <select name="" id="gsd_rv_sf9" class="form-control">
                                        {{-- <option value="0">SF9 Value</option> --}}
                                        <option value="1">WW</option>
                                        <option value="2">PT</option>
                                        <option value="3">QA</option>
                                    </select>
                                </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="create_grading_system_detail_submit">CREATE GRADING SYSTEM DETAIL</button>
                    </div>
              </div>
        </div>
    </div>  

    <div class="modal fade" id="version_transfer_modal" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-xl">
              <div class="modal-content">
                    <div class="modal-header bg-primary">
                    <h4 class="modal-title">Version Transfer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body" >
                        <div class="row">
                            <div class="col-md-12 table-responsive" style="height: 429px;" id="available_grades">

                            </div>
                        </div>
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
                    <div class="card-header bg-primary ">
                            <h5 class="card-title"></h4>Grading System</h5>
                    </div>
                    <div class="card-body table-responsive" ">
                        <div class="row">
                            <div class="col-md-3">
                                <button class="btn btn-primary btn-block" id="create_grading_system_button"><i class="fas fa-plus"></i> CREATE GRADING SYSTEM</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-secondary btn-block" id="grading_type_button"><i class="far fa-eye"></i> VIEW TYPE</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-secondary btn-block" id="test_grading_button"><i class="fas fa-vial"></i> TEST GRADING</button>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-success btn-block" id="version_transfer_button"><i class="fas fa-exchange-alt"></i> VERSION TRANSFER</button>
                            </div>
                            <script>
                                $(document).ready(function(){

                                    function getavailablegrades(){

                                        $.ajax({
                                            type:'GET',
                                            url:'/getv1grades',
                                            success:function(data) {
                                                $('#available_grades').empty();
                                                $('#available_grades').append(data);
                                            
                                            }
                                        })

                                    }

                                    $(document).on('click','#version_transfer_button',function(){

                                        $('#version_transfer_modal').modal()
                                        getavailablegrades()
                                       
                                    })

                                    $(document).on('click','.transfer',function(){

                                        $.ajax({
                                            type:'GET',
                                            url:'/transferv1grades',
                                            data:{
                                                gid:$(this).attr('data-id')
                                            },
                                            success:function(data) {

                                                if(data[0].status == 0){
                                                    Swal.fire({
                                                        type: 'info',
                                                        text: data[0].data
                                                    });
                                                }
                                                else{

                                                    Swal.fire({
                                                        type: 'info',
                                                        text: 'Success'
                                                    });
                                                    getavailablegrades()

                                                }
                                                
                                              
                                            
                                            }
                                        })

                                    })

                                })
                            </script>

                        </div>
                        <hr>
                        <div class="row">
                            <h4 class="col-md-12">Filter</h4>
                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group">
                                    <label for="">Type</label>
                                    <select value="" class="form-control" id="type_filter">
                                        <option value="">All</option>
                                        @foreach (DB::table('grading_system_type')->select('id','description')->get() as $item)
                                            <option value="{{$item->id}}">{{$item->description}}</option>
                                        @endforeach
                                    </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="">Academic Program</label>
                                <select name="" id="acadprog_filter" class="form-control">
                                    <option value="">All</option>
                                    @foreach($acadmicprogm as $item)
                                        <option value="{{$item->id}}">{{$item->progname}}</option>
                                    @endforeach
                                </select>
                            </div>
                          
                            <div class="col-md-3 form-group">
                                <label for="">Active Status</label>
                                <select name="" id="active_filter" class="form-control">
                                    <option value="">All</option>
                                    <option value="1">Inactive</option>
                                    <option value="2">Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-primary" id="gs_filter"><i class="fas fa-sync-alt"></i> FILTER</button>
                                <script>
                                     $(document).ready(function(){

                                        
                                    })
                                </script>
                               
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12 table-responsive" id="grading_system_table_holder">
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-12" id="data-container">
                            </div>
                        </div>
                        <hr>
                      </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                          <div class="card-header p-1 bg-secondary">
                          </div>
                          <div class="card-body">
                                <h4>Notes:</h4>
                                <hr>
                                <ol style="list-style: circle;">
                                    <li>Kindergarten Rating: Please specify rating value.</li>
                                    <li>Kindergarten Rating: 0 value is the header</li>
                                    <li>Kindergarten Checklist: 1 value is the header</li>
                                    <li>Add grading system detail sf9 value</li>
                                    <li>Grading System Assignment by subject</li>
                                    <li>Filter grading system</li>
                                </ol>
                                <h4>For update:</h4>
                                <hr>
                                <ul>
                                    <li>Transfer V1 grades to V2 grades</li>
                                    <li>Clear grading setup</li>
                                    <li>Clear inputed grades</li>
                                    <li>Finalize grading system for senior high track </li>
                                    <li>Restrict deletion of grading system if  already used</li>
                                </ul>
                          </div>
                    </div>
                </div>
        </div>
    </section>

    
  
@endsection

@section('footerjavascript')

    <script src="{{asset('js/pagination.js')}}"></script> 
    <script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
 
    <script>
        $(document).ready(function(){


            const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                  });

            var selectedGS;
            var gsdetailaction;
            var gsaction;
            var gsid;

           

            $(document).on('click','#grading_type_button',function(){

                $('#grading_type_modal').modal();

            })

            //gsdetail
            $(document).on('click','#create_grading_system_detail_button',function(){

                gsid = null
                $('#create_grading_system_detail_modal .modal-title').text('Create Grading System Detail')
                $('#create_grading_system_detail_submit').text('Create Grading System Detail')
                $('#create_grading_system_detail_modal').modal();
                $('#create_grading_system_detail_form')[0].reset()
                gsdetailaction = 'create';

            })

            $(document).on('click','.update_gs_detail',function(){
                
                gsdetailaction = 'update';
                gsid = $(this).attr('data-id')
                $('#create_grading_system_detail_modal').modal();
                $('#grading_system_detail_description').val($(this).attr('data-description'))
                $('#grading_system_detail_value').val($(this).attr('data-value'))
                $('#grading_system_detail_sort').val($(this).attr('data-sort'))
                $('#gsd_rv_i').val($(this).attr('data-items'))
                $('#gsd_rv_g').val($(this).attr('data-group'))
                $('#gsd_rv_sf9').val($(this).attr('data-sf9'))
                $('#create_grading_system_detail_modal .modal-title').text('Update Grading System Detail')
                $('#create_grading_system_detail_submit').text('Update Grading System')
                

            })

            $(document).on('click','.delete_gs_detail',function(){

                gsdetailaction = 'delete'
                gsid = $(this).attr('data-id')
                $('#create_grading_system_detail_modal').modal();
                $('#grading_system_detail_description').val($(this).attr('data-description'))
                $('#grading_system_detail_value').val($(this).attr('data-value'))
                $('#grading_system_detail_sort').val($(this).attr('data-sort'))
                $('#gsd_rv_i').val($(this).attr('data-items'))
                $('#gsd_rv_g').val($(this).attr('data-group'))
                $('#gsd_rv_sf9').val($(this).attr('data-sf9'))
                $('#create_grading_system_detail_modal .modal-title').text('Delete Grading System Detail')
                $('#create_grading_system_detail_submit').text('Delete Grading System')

            })

            $(document).on('click','.grading_system_detail_view_button',function(){

                selectedGS = $(this).attr('data-id')
                var type = $(this).attr('data-type')

                $('#grade_system_description').text($(this).attr('data-description'))
                $('#grade_system_type').text($(this).attr('data-stringtype'))

                $('#gsd_rv_i_h').attr('hidden','hidden')
                $('#gsd_rv_g_h').attr('hidden','hidden')
                $('#gsd_rv_sf9_h').attr('hidden','hidden')

                if(type == 1){

                    $('#detail_value_label').text('Percentage')
                    $('#gsd_rv_i_h').removeAttr('hidden')
                    $('#gsd_rv_sf9_h').removeAttr('hidden')

                }
                else if(type == 2){

                    $('#detail_value_label').text('Checklist')
                    $('#gsd_rv_g_h').removeAttr('hidden')

                }
                else if(type == 3){

                    $('#detail_value_label').text('Max Rating')
                    $('#gsd_rv_g_h').removeAttr('hidden')
                }

                loadGSdetail()

                $('#grading_system_detail_modal').modal();

            })

            function loadGSdetail(){

                $.ajax({
                        type:'GET',
                        url:'/gradingsystem',
                        data:{
                            gradesysdetail:'gradesysdetail',
                            gradesysid: selectedGS,
                         
                        },
                        success:function(data) {

                            $('#grading_system_detail_table_holder').empty();
                            $('#grading_system_detail_table_holder').append(data);
                          
                        
                        }
                })

            }

            function filter(){

                $('.gs_tr').attr('hidden','hidden')

                $('.gs_tr').each(function(){
                    
                    $filterCount = 0;
                    $validCount = 0 ;

                    if($('#acadprog_filter').val() != null && $('#acadprog_filter').val() != ''){

                        $filterCount += 1;

                        if($('#acadprog_filter').val() == $(this).attr('data-acadprog')){
                        
                            $validCount += 1 ;

                        }

                    }

                    if($('#active_filter').val() != null && $('#active_filter').val() != ''){

                        $filterCount += 1;

                        if($('#active_filter').val() == $(this).attr('data-isactive')){
                    
                            $validCount += 1 ;

                        }

                    }

                    if($('#type_filter').val() != null && $('#type_filter').val() != ''){

                        $filterCount += 1;

                        if($('#type_filter').val() == $(this).attr('data-type')){

                            $validCount += 1 ;

                        }

                    }

                    if($filterCount == $validCount){

                        $(this).removeAttr('hidden')

                    }

                })

            }

            $(document).on('click','#gs_filter',function(){

                filter()

            })

            

            $(document).on('click','#create_grading_system_detail_submit',function(){

                $('#grade_system_description').text($(this).attr('data-description'))
               
                $.ajax({
                        type:'GET',
                        url:'/gradingsystem',
                        data:{
                            action:gsdetailaction,
                            detail:'detail',
                            id:gsid,
                            description:$('#grading_system_detail_description').val(),
                            sort:$('#grading_system_detail_sort').val(),
                            value:$('#grading_system_detail_value').val(),
                            group:$('#gsd_rv_g').val(),
                            items:$('#gsd_rv_i').val(),
                            sf9val:$('#gsd_rv_sf9').val(),
                            headerid:selectedGS
                        },
                        success:function(data) {

                            if(data == 1){

                                if(gsdetailaction == 'create')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Created Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                else if(gsdetailaction == 'update')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Updated Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                else if(gsdetailaction == 'delete')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Deleted Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }

                                $('#create_grading_system_detail_modal').modal('hide')
                                $('#create_grading_system_detail_form')[0].reset()
                                loadGSdetail()

                            }else if(data == 0){

                                Swal.fire({
                                    type: 'error',
                                    title: 'Somethin went wrong!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                            }

                        
                        }
                })

            })


            //gsdetail

            $(document).on('click','#create_grading_system_button',function(){

                selectedGS = null
                gsaction = 'create'
                $('#create_grading_system_modal').modal();
                $('#create_grading_system_submit').text('CREATE GRADING SYSTEM')
                $('#create_grading_system_modal .modal-title').text('CREATE GRADING SYSTEM')
                $('#track_holder').attr('hidden','hidden')
                $('#create_grading_system_form')[0].reset()

            })

            $(document).on('click','.update_gs',function(){

                gsaction = 'update'
                selectedGS = $(this).attr('data-id')
              
                $('#create_grading_system_modal').modal()
                $('#grading_system_description').val($(this).attr('data-description'))
                $('#grading_system_type').val($(this).attr('data-type')).change()
                $('#grading_system_acadprog').val($(this).attr('data-acadprog')).change()
                $('#grading_system_isactive').val($(this).attr('data-isactive')).change()
                $('#gs_specification').val($(this).attr('data-specification')).change()
                $('#grading_track').val($(this).attr('data-trackid')).change()

                $('#create_grading_system_modal .modal-title').text('UPDATE GRADING SYSTEM')
                $('#create_grading_system_submit').text('UPDATE GRADING SYSTEM')

            })


            $(document).on('click','.delete_gs',function(){

                gsaction = 'delete'
                selectedGS = $(this).attr('data-id')
                $('#create_grading_system_modal').modal()
                $('#grading_system_description').val($(this).attr('data-description'))
                $('#grading_system_type').val($(this).attr('data-type')).change()
                $('#grading_system_acadprog').val($(this).attr('data-acadprog')).change()
                $('#grading_system_isactive').val($(this).attr('data-isactive')).change()
                $('#gs_specification').val($(this).attr('data-specification')).change()
                $('#grading_track').val($(this).attr('data-trackid')).change()

                $('#create_grading_system_submit').text('DELETE GRADING SYSTEM')
                $('#create_grading_system_modal .modal-title').text('DELETE GRADING SYSTEM')

            })  


            
            $(document).on('click','#create_grading_system_submit',function(){

                $.ajax({
                    type:'GET',
                    url:'/gradingsystem',
                    data:{
                        action: gsaction,
                        id: selectedGS,
                        description:$('#grading_system_description').val(),
                        type:$('#grading_system_type').val(),
                        isactive:$('#grading_system_isactive').val(),
                        acadprog:$('#grading_system_acadprog').val(),
                        specification:$('#gs_specification').val(),
                        trackid:$('#grading_track').val()
                    },
                    success:function(data) {
                       
                       if(data == 1){

                            if(gsaction == 'create')
                            {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Created Successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                            else if(gsaction == 'update')
                            {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Updated Successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                            else if(gsaction == 'delete')
                            {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Deleted Successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }

                            $('#create_grading_system_modal').modal('hide');

                            $('#create_grading_system_form')[0].reset()
                            processpaginate(0,10,null,true)
                           
                         
                       }else if(data == 0){

                          

                            Swal.fire({
                                type: 'error',
                                title: 'Somethin went wrong!',
                                showConfirmButton: false,
                                timer: 1500
                            });

                       }
                    
                    }
                })

                

            })


            //gsdrating value

            var gsd_rv_action;
            var s_gsd_rv;

            function load_gsd_rv_table(){

                $.ajax({
                        type:'GET',
                        url:'/gradingsystem',
                        data:{
                            ratingvaluetable:'ratingvaluetable',
                            gradesysid: selectedGS,
                        
                        },
                        success:function(data) {

                            $('#c_gsd_rv_tb').empty();
                            $('#c_gsd_rv_tb').append(data);
                        
                        
                        }
                })

            }


            $(document).on('click','#create_gs_ratingvalue_button',function(){

                $('#gs_ratingvalue_modal').modal()
                load_gsd_rv_table()


            })

            $(document).on('click','#c_gsd_rv_b',function(){

                $('#c_gsd_rv_m').modal()
                gsd_rv_action = 'create'
                $('#c_gsd_rv_s')[0].innerHTML = '<i class="fas fa-save"></i></i> CREATE'
                $('#gsd_rv_form')[0].reset()
             
            })

            $(document).on('click','.u_gsd_rv_b',function(){

                $('#c_gsd_rv_m').modal()
                gsd_rv_action = 'update'
                s_gsd_rv = $(this).attr('data-id')
                $('#gsd_rv_d').val($(this).attr('data-description'))
                $('#gsd_rv_v').val($(this).attr('data-value'))
                $('#gsd_rv_s').val($(this).attr('data-sort'))
                $('#c_gsd_rv_s')[0].innerHTML = '<i class="fas fa-edit"></i></i> UPDATE'


            })

            $(document).on('click','.d_gsd_rv_b',function(){

                $('#c_gsd_rv_m').modal()
                gsd_rv_action = 'delete'
                s_gsd_rv = $(this).attr('data-id')
                $('#gsd_rv_d').val($(this).attr('data-description'))
                $('#gsd_rv_v').val($(this).attr('data-value'))
                $('#gsd_rv_s').val($(this).attr('data-sort'))
                $('#c_gsd_rv_s')[0].innerHTML = '<i class="fas fa-trash-alt"></i></i> DELETE'

            })


            $(document).on('click','#c_gsd_rv_s',function(){

                $.ajax({
                        type:'GET',
                        url:'/gradingsystem',
                        data:{
                            action:gsd_rv_action,
                            ratingvalue:'ratingvalue',
                            id:gsid,
                            description:$('#gsd_rv_d').val(),
                            value:$('#gsd_rv_v').val(),
                            sort:$('#gsd_rv_s').val(),
                            headerid:selectedGS,
                            id:s_gsd_rv
                        },
                        success:function(data) {

                            if(data == 1){

                                if(gsd_rv_action == 'create')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Created Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                else if(gsd_rv_action == 'update')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Updated Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }
                                else if(gsd_rv_action == 'delete')
                                {
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Deleted Successfully!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }

                                $('#c_gsd_rv_m').modal('hide')
                                $('#gsd_rv_form')[0].reset()
                                load_gsd_rv_table()


                            }else if(data == 0){

                                Swal.fire({
                                    type: 'error',
                                    title: 'Somethin went wrong!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                            }

                        
                        }
                })

            })

            $(document).on('click','#subject_assignment_button',function(){

                $('#subject_assignment_modal').modal();

                $.ajax({
                    type:'GET',
                    url:'/grading/subject/assignment',
                    data:{
                        gsid:selectedGS,
                        
                    },
                    success:function(data) {

                        $('#subject_assignment_holder').empty()
                        $('#subject_assignment_holder').append(data)
                        
                    
                    }
                })

            })

            

            processpaginate(0,10,null,true)

                function processpaginate(skip = null,take = null ,search = null, firstload = true){

                    $.ajax({
                            type:'GET',
                            url:'/gradingsystem?take='+take+'&skip='+skip+'&table=table'+'&search='+search+'&gradingsystemlist=gradingsystemlist',
                            success:function(data) {
                                $('#grading_system_table_holder').empty();
                                $('#grading_system_table_holder').append(data);
                                filter()
                                // pagination($('#searchCount').val(),false)
                            
                            }
                    })

                }

                // var pageNum = 1;

                // function pagination(itemCount,pagetype){

                //     var result = [];

                //     for (var i = 0; i < itemCount; i++) {
                //             result.push(i);
                //     }

                //     $('#data-container').pagination({
                //             dataSource: result,
                //             hideWhenLessThanOnePage: true,
                //             pageNumber: pageNum,
                //             pageRange: 1,
                //             callback: function(data, pagination) {

                //                         if(pagetype){

                //                             processpaginate(pagination.pageNumber,10,$('#search').val(),false)

                //                         }

                //                         pageNum = pagination.pageNumber
                //                         pagetype=true
                //                 }
                //             })
                // }

        })
    </script>

    <script>

        $(document).ready(function(){

            var selectedStudent
            var acadprog
            var t_s_gsid

            $('.select2').select2()

            $(document).on('click','#test_grading_button',function(){

                $('#select_academicprogram').modal()

            })

            $(document).on('change','#testing_select_acadprog',function(){

                $('option[data-action="test"]').attr('hidden','hidden')
                $('option[data-action="test"]').removeAttr('selected')
                $('#t_s_gs').val('')

                if($(this).val() == ''){

                    $('#t_s_gs').val('')
                    
                }
                else{

                    $('option[data-action="test"][data-acad="'+$(this).val()+'"]').removeAttr('hidden')

                }

            })

            $(document).on('click','#select_acadprog_submit',function(){

                acadprog = $('#testing_select_acadprog').val()
                $('#t_id').val('').change()
                t_s_gsid = $('#t_s_gs').val()
                $('#grading_sys_test').modal()
                $('#test_blade_holder').empty()
             
            })

            $(document).on('click','#p_t_g',function(){


                var url;
                var valid = true;

                if(acadprog == 2){

                    url = '/gradestudent/preschool';

                }else{

                    if($('#t_id').val() == ''){

                        valid = false
                        Swal.fire({
                            type: 'info',
                            title: 'Please select a teacher!',
                        });

                    }

                    if($('#s_id').val() == ''){

                        Swal.fire({
                            type: 'info',
                            title: 'Please select a specification!',
                        });
                    }

                   


                }

                if($('#s_id').val() == 2){

                    if(acadprog == 3){

                        url = '/reportcard/coreval/gradeschool';

                    }else if(acadprog == 4){

                        url = '/reportcard/coreval/highschool';

                    }else if(acadprog == 5){

                        url = '/reportcard/coreval/seniorhigh';

                    }

                }
                if($('#s_id').val() == 1){

                    if(acadprog == 3){

                        url = '/reportcard/grades/gradeschool';

                    }else if(acadprog == 4){

                        url = '/reportcard/grades/highschool';

                    }else if(acadprog == 5){

                        url = '/reportcard/grades/seniorhigh';

                    }

                }


               

                if(valid){
                    $.ajax({
                        type:'GET',
                        url: url,
                        data:{
                            grade:'grade',
                            acadprog: acadprog,
                            gsid:t_s_gsid,
                            teacherid:$('#t_id').val()
                        },
                        success:function(data) {
                            if(data[0].status == 0){
                                Swal.fire({
                                    type: 'info',
                                    text: data[0].data
                                });
                            }
                            else{
                                $('#test_blade_holder').empty()
                                $('#test_blade_holder').append(data)
                                $('#t_s_a_f')[0].reset()

                            }
                        }
                    })
                }

               

            })

            
           
        

            // $(document).on('click','#eval_student_grade',function(){

            //     $('#generate_grade_holder').attr('hidden','hidden')

            //     selectedStudent = $('#student_test').val()

            //     if(selectedStudent == ''){

            //         Swal.fire({
            //             type: 'error',
            //             title: 'No Student Selected!',
            //             text:'Please select student',
            //             showConfirmButton: false,
            //             timer: 1500
            //         });

            //     }
            //     else{

            //         loadGradesDetail()
                    
            //     }
            // })

            // $(document).on('click','#generate_student_grade_detail',function(){

            //     if(selectedStudent == ''){

            //         Swal.fire({
            //             type: 'error',
            //             title: 'No Student Selected!',
            //             text:'Please select student',
            //             showConfirmButton: false,
            //             timer: 1500
            //         });

            //     }
            //     else{

            //         $.ajax({
            //             type:'GET',
            //             url:'/testgrading',
            //             data:{
            //                 generate:'generate',
            //                 studid: selectedStudent,
            //                 acadprog: acadprog,
            //                 gsid:t_s_gsid
            //             },
            //             success:function(data) {

            //                 Swal.fire({
            //                         type: 'success',
            //                         title: 'Generated Successfully!',
            //                         text: data + ' detail generated',
            //                         showConfirmButton: false,
            //                         timer: 2000
            //                     });
                            
            //                 $('#generate_grade_holder').attr('hidden','hidden')
            //                 loadGradesDetail()

            //             }
            //         })
            //     }

            // })

            // $(document).on('change','select[data-type="select"]',function(){

            //     $(this).addClass('checked_grade')

            // })

     

        // $(document).on('click','#save_grades',function(){

        //     var firstIndex = 0;
        //     var lastIndex = 10;
        //     var checkedGrades =  parseInt( $('.checked_grade').length / 10 )  + 1;
        //     var saveCount = 0;
        //     var unSavedCount = 0;
        //     var proccessCount =  0;
        //     var checkedCount = $('.checked_grade').length;

        //     $('#proccess_count_modal .modal-title').text('Processing ...')
        //     $('#proccess_done').attr('hidden','hidden')
        //     $('#proccess_count_modal').modal()
            
        //     $('#save_count').text(saveCount)
        //     $('#not_saved_count').text(unSavedCount)
        //     $('#proccess_count').text(proccessCount)

        //     if(checkedCount == 0){

        //         $('#proccess_count_modal .modal-title').text('Complete')
        //         $('#proccess_done').removeAttr('hidden')

        //     }

        //     submitGrades()

        //     function submitGrades(){

        //         var counter = 0;

        //         $('.checked_grade').slice(firstIndex,lastIndex).each(function(){

        //             var value = $(this).val();
        //             var gradeid = $(this).attr('data-id')
        //             var gardequarter = $(this).attr('data-quarter')
        //             var selectInfo = $(this)

        //             $.ajax({
        //                     type:'GET',
        //                     url:'/testgrading',
        //                     data:{
        //                         submit:'submit',
        //                         studid: selectedStudent,
        //                         gradeid: gradeid,
        //                         value:value,
        //                         gardequarter: gardequarter,
        //                         acadprog: acadprog
        //                     },
        //                     success:function(data) {

        //                         if(data == 1){

        //                             saveCount += 1
        //                             $('#save_count').text(saveCount)
                                   
        //                         }
        //                         else if(data == 0){

        //                             unSavedCount += 1
        //                             $('#not_saved_count').text(unSavedCount)

        //                         }

                              
        //                         counter += 1;
        //                         proccessCount += 1;

        //                         if(counter == 9 && checkedGrades != 0){

        //                             firstIndex  += 10;
        //                             lastIndex += 10;
        //                             checkedGrades -= 1
        //                             submitGrades()

        //                         }

        //                         if(  checkedCount  == proccessCount){

        //                             $('#proccess_count_modal .modal-title').text('Complete')
        //                             $('#proccess_done').removeAttr('hidden')
        //                             $('.checked_grade').removeClass('checked_grade')
        //                             loadGradesDetail()

        //                         }

        //                         $('#proccess_count').text(proccessCount+' / '+checkedCount)

        //                     }
        //             })

        //         })

        //     }

        // })
        })

    </script>

    <script>

        $(document).ready(function(){
            
            $(document).on('change','#grading_system_acadprog',function(){
                if($(this).val() == 5){
                    $('#track_holder').removeAttr('hidden')
                }
                else{
                    $('#track_holder').attr('hidden','hidden')
                    $('#grading_track').val('')
                }

            })


        })
    </script>


    
@endsection

