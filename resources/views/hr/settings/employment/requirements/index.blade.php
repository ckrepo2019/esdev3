

@extends('hr.layouts.app')
@section('content')
@php
    function capitalize($word)
    {
        return \App\Http\Controllers\SchoolFormsController::capitalize_word($word);
    }
@endphp
<style>
    #table-reqs td, #table-reqs th{
        padding: 2px;
    }
    .card{
        border: none !important;
    }
    #table-employees td { padding: 5px;}
    div.dataTables_wrapper {
        /* width: 800px; */
        /* margin: 0 auto; */
    }
    
    #table-employees thead th:first-child  { 
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
    }

    #table-employees tbody td:first-child  {  
        position: sticky; 
        left: 0; 
        background-color: #fff; 
        background-color: #fff; 
        outline: 2px solid #dee2e6;
        outline-offset: -1px;
    }

    #table-employees thead th:first-child  { 
            position: sticky; left: 0; 
            background-color: #fff; 
            outline: 2px solid #dee2e6;
            outline-offset: -1px;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Employment Requirements</h4>
                <!-- <h1>Attendance</h1> -->
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Employment Requirements</li>
                </ol>
            </div>
    </div>
    </div><!-- /.container-fluid -->
</section>
<div class="row">
    <div class="col-12">
    
    <div class="card" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
    <div class="card-header d-flex p-0">
    {{-- <h3 class="card-title p-3">Tabs</h3> --}}
    <ul class="nav nav-pills ml-auto p-2">
    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Employees</a></li>
    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Requirement</a></li>
    </ul>
    </div>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            <div class="card" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-body">
                    <table class="table-head-fixed text-nowrap table-bordered" id="table-employees" style="font-size: 13px; width: 100%;">   
                        <thead>
                            <tr>
                                <th style="width: 25%;">Employee</th>
                                @if(count($requirements)>0)
                                    @foreach($requirements as $requirement)   
                                        <th class="text-center bg-warning">{{$requirement->description}}</th>
                                    @endforeach
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $eachemployee)
                                <tr>
                                    <td>{{capitalize($eachemployee->sortname)}}</td>
                                    @if(count($requirements)>0)
                                        @foreach($requirements as $eachreqs)
                                            @php
                                                $reqinfo = collect($eachemployee->uploadedreqs)->where('credentialtypeid', $eachreqs->id)->first();
                                            @endphp
                                        <td class="text-right">
                                            @if($reqinfo)
                                            <span class="badge badge-success each-badge-uploadedview" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-uploadedid="{{$reqinfo->id}}" data-reqid="{{$eachreqs->id}}" data-reqname="{{$eachreqs->description}}" data-employeeid="{{$eachemployee->id}}" data-employeename="{{$eachemployee->sortname}}" data-source="{{$reqinfo->filepath}}" data-filename="{{$reqinfo->filename}}" data-ext="{{$reqinfo->extension}}"><i class="fa fa-check"></i></span>
                                            @else
                                            <span class="badge each-badge-upload text-muted" style="width: 100%; font-size: 12px; cursor: pointer; border: 1px solid #ddd;" data-reqid="{{$eachreqs->id}}" data-reqname="{{$eachreqs->description}}" data-employeeid="{{$eachemployee->id}}" data-employeename="{{$eachemployee->sortname}}"><i class="fa fa-upload text-muted"></i> Upload</span>
                                            @endif
                                        </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="tab-pane" id="tab_2">
            <div class="row mb-3">
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-outline-primary" id="btn-tomodal-requirement-add"><i class="fa fa-plus"></i> Add Requirement</button>
                </div>
            </div> 
            <div class="row" id="container-reqs">
            @if(count($requirements) > 0)
                    @foreach($requirements as $requirement)
                        <div class="col-4 mb-3" id="col-{{$requirement->id}}">
                            <div class="card h-100 shadow" style="background-color: white; border-radius: 0px 30px; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                {{-- <div class="card-header">
                                    <label>{{$type->typename}}</label>
                                </div> --}}
                                <div class="card-body">
                                    <label id="label-reqname-{{$requirement->id}}">{{$requirement->description}}</label><br/>
                                    {{-- <small>Rate (%) / <strong>No Work</strong>: <u id="rate-nowork-{{$requirement->id}}">{{$type->ratepercentagenowork}}</u>&nbsp;&nbsp;%</small><br/>
                                    <small>Rate (%) / <strong>Work</strong>: <u id="rate-work-{{$requirement->id}}">{{$type->ratepercentageworkon}}</u>&nbsp;&nbsp;%</small> --}}
                                </div>
                                <a href="#" class="small-box-footer bg-secondary pl-2 each-requirement" data-id="{{$requirement->id}}"  data-title="{{$requirement->description}}" style="vertical-align: middle; border-radius: 0px 30px;">
                                &nbsp;&nbsp;Edit info <i class="fas fa-edit fa-xs"></i>
                                 </a>
                            </div>
                        </div>
                    @endforeach
            @endif
                </div>
        </div>
        
        </div>
    </div>
    
    </div>
    
 <div class="modal fade" id="modal-add-requirement">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Requirement</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control" id="input-requirement-title-add"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-add-requirement-save">Save changes</button>
            </div>
        </div>
    
    </div>
    
</div>
 <div class="modal fade" id="modal-edit-requirement">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Requirement</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control" id="input-requirement-title-edit"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row m-0" style="width: 100%">
                    <div class="col-3 p-0">
                        <button type="button" class="btn btn-outline-danger" id="btn-modal-delete-requirement"><i class="fa fa-trash-alt"></i> <small>Delete</small></button>
                    </div>
                    <div class="col-9 p-0 text-right">
                        <button type="button" class="btn btn-primary" id="btn-modal-edit-requirement-save">Save changes</button></div>
                </div>
            </div>
        </div>
    
    </div>
    
</div>
<div class="modal fade" id="modal-upload-requirement">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title"><span id="span-text-requirement"></span></h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="/printable/certificationsupload" method="post" enctype="multipart/form-data" class="form-submit" action-type="upload">
               @csrf
                <div class="modal-body">
                        <h5><span id="span-text-employeename"></span></h5>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Upload a file</label>
                            <input type="file" id="input-file" name="file" accept=
                            "application/msword,.doc,.docx, application/vnd.ms-excel, application/pdf, image/*" class="form-control" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-modal-upload-requirement-save">Upload</button>
                </div>
            </form>
       </div>
   
   </div>
   
</div>
<div class="modal fade" id="modal-upload-requirement-view">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h4 class="modal-title"><span id="span-text-requirement-view"></span></h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <form action="/printable/certificationsupload" method="post" enctype="multipart/form-data" class="form-submit" action-type="reupload">
               @csrf
                <div class="modal-body">
                        <h5><span id="span-text-employeename"></span></h5>
                        <label>Uploaded file</label>
                        <div class="row p-2 m-1" style="border: 1px solid #ddd; border-radius: 10px;">
                            <div class="col-9" id="container-file-info"></div>
                            <div class="col-3 text-right"><i class="fa fa-check-circle text-success"></i></div>
                        </div>
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label>Replace file</label>
                            <input type="file" id="input-file-view" name="file" accept="application/msword,.doc,.docx, application/vnd.ms-excel, application/pdf, image/*" class="form-control" required/>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-danger" id="btn-modal-delete-uploaded"><i class="fa fa-trash-alt"></i></button>
                    <button type="submit" class="btn btn-primary" id="btn-modal-upload-requirement-save">Upload</button>
                </div>
            </form>
       </div>
   
   </div>
   
</div>
    
@endsection
@section('footerscripts')
    <script>
        $(document).ready(function(){
            var table_standard = $('#table-standard-allowances').DataTable({
                paging:         false,
                "info": false,
                "ordering": false,
                "aaSorting": []
            }) 
            
            var table_other = $('#table-other-allowances').DataTable({
                paging:         false,
                "info": false,
                "ordering": false,
                "aaSorting": []
            }) 
            var oTable = $('#table-employees').DataTable({
                "columnDefs": [
                    { "width": "300px", "targets": 0 }
                ],
                fixedColumns: true,
                scrollY:        500,
                scrollX:        true,
                scrollCollapse: true,
                paging:         false,
                fixedColumns:   true,
                "info": false,
                "ordering": false,
                "aaSorting": []
            })   //using Capital D, which is mandatory to retrieve "api" datatables' object, latest jquery Datatable
        
            // $('#table-employees_filter').closest('.row').find('div').first().append('<em>Note: Click the amount badges to see the details.</em>')
            var employeeid;
            var employeename;
            var reqname;
            var reqid;
            var uploadedid;
            var thisbadge;
            $('.each-badge-upload').on('click', function(){
                thisbadge = $(this);
                employeeid = $(this).attr('data-employeeid');
                employeename = $(this).attr('data-employeename');
                reqid = $(this).attr('data-reqid');
                reqname = $(this).attr('data-reqname');
                $('#span-text-requirement').text(reqname)
                $('#span-text-employeename').text(employeename)
                $('#modal-upload-requirement').modal('show');
            })
            $(document).on('click','.each-badge-uploadedview', function(){
                thisbadge = $(this);
                employeeid = $(this).attr('data-employeeid');
                employeename = $(this).attr('data-employeename');
                reqid = $(this).attr('data-reqid');
                uploadedid = $(this).attr('data-uploadedid');
                reqname = $(this).attr('data-reqname');
                var filepath = $(this).attr('data-source');
                var filename = $(this).attr('data-filename');
                var extension = $(this).attr('data-ext');
                $('#span-text-requirement-view').text(reqname)
                $('#span-text-employeename-view').text(employeename)
                $('#container-file-info').empty()
                $('#container-file-info').append('<a href="/'+filepath+'" download>'+filename+'.'+extension+'</a>')
                $('#modal-upload-requirement-view').modal('show');
            })
        $('.form-submit').submit( function( e ) {
            var actiontype = $(this).attr('action-type')
            var inputs = new FormData(this)
            inputs.append('employeeid', employeeid);
            inputs.append('reqid', reqid);
            inputs.append('action', 'upload');
            // if($('#input-file').files.length == 0 ){
            //     toastr.warning('No Word document selected!')
            // }else{
                Swal.fire({
                    title: 'Reloading...',
                    onBeforeOpen: () => {
                        Swal.showLoading()
                    },
                    allowOutsideClick: false
                })
                // console.log(inputs)
                $.ajax({
                    url: '/hr/employment/requirements/upload',
                    type:'POST',
                    data: inputs,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success:function(data) {
                        if(data == 0)
                        {
                            toastr.success('Something went wrong!')
                        }else{
                            toastr.success('Uploaded successfully!')
                            $(".swal2-container").remove();
                            $('body').removeClass('swal2-shown')
                            $('body').removeClass('swal2-height-auto')
                            thisbadge.attr('data-uploadedid',data.id)
                            thisbadge.attr('data-source',data.filepath)
                            thisbadge.attr('data-filename',data.filename)
                            thisbadge.attr('data-ext',data.extension)
                            thisbadge.removeClass('each-badge-upload');
                            thisbadge.removeClass('text-muted');
                            thisbadge.addClass('each-badge-uploadedview');
                            thisbadge.addClass('badge-success');
                            thisbadge.empty();
                            thisbadge.append('<i class="fa fa-check"></i>');
                            if(actiontype == 'upload')
                            {
                                $('#input-file').val('')
                                $('#modal-upload-requirement').modal('toggle')
                            }else{
                                $('#input-file-view').val('')
                                $('#modal-upload-requirement-view').modal('toggle')
                            }
                        }
                        
                    }
                })
                e.preventDefault();
            // }


        })
            $('#btn-modal-delete-uploaded').on('click', function(){
                    Swal.fire({
                        title: 'Are you sure you want to delete this uploaded document?',
                        type: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Delete',
                        showCancelButton: true,
                        allowOutsideClick: false,
                    }).then((confirm) => {
                            if (confirm.value) {
                                
                                $.ajax({
                                    url: '/hr/employment/requirements/delete',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'uploadedrequirement_delete',
                                        id    : uploadedid
                                    },
                                    success: function(data){
                                        if(data == 1)
                                        {
                                            $('#col-'+reqid).remove()
                                            toastr.success('Deleted successfully!')
                                            thisbadge.addClass('each-badge-upload');
                                            thisbadge.addClass('text-muted');
                                            thisbadge.removeClass('each-badge-uploadedview');
                                            thisbadge.removeClass('badge-success');
                                            thisbadge.empty()
                                            thisbadge.append('<i class="fa fa-upload text-muted"></i> Upload</span>')
                                            $('#modal-upload-requirement-view').modal('toggle')
                                        }else{
                                            toastr.error('Something went wrong!')
                                        }
                                    }
                                })
                            }
                        })
            })
            $('#btn-tomodal-requirement-add').on('click', function(){
                $('#modal-add-requirement').modal('show')
            })
            $('#btn-modal-add-requirement-save').on('click', function(){
                var newreqtitle = $('#input-requirement-title-add').val();
                if(newreqtitle.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-requirement-title-add').css('border','1px solid red')
                    toastr.warning('Please fill in required field!','Requirement')
                }else{
                    Swal.fire({
                        title: 'Saving changes...',
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    }) 
                    $.ajax({
                        url: '/hr/employment/requirements',
                        type: 'GET',
                        data: {
                            action        : 'requirement_add',
                            reqtitle    : newreqtitle
                        },
                        success:function(data) {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                            if(data == 0)
                            {
                            toastr.error('This requirement exists!','Requirement')
                            }else{
                                toastr.success('Updated successfully!','Requirement')
                                $('#container-reqs').prepend(
                                    `
                                    <div class="col-4 mb-3" id="col-`+data+`">
                                        <div class="card h-100 shadow" style="background-color: white; border-radius: 0px 30px; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                            <div class="card-body">
                                                <label id="label-reqname-`+data+`">`+newreqtitle+`</label><br/>
                                            </div>
                                            <a href="#" class="small-box-footer bg-secondary pl-2 each-requirement" data-id="`+data+`"  data-title="`+newreqtitle+`" style="vertical-align: middle; border-radius: 0px 30px;">
                                            &nbsp;&nbsp;Edit info <i class="fas fa-edit fa-xs"></i>
                                            </a>
                                        </div>
                                    </div>`
                                )
                                $('#modal-add-requirement').modal('toggle')
                            }
                        }
                        ,error:function(){
                            toastr.error('Something went wrong!','Requirement')
                        }
                    })
                }
            })
            var reqid;
            var reqtitle;
            var thisbuttontomodal;
            $(document).on('click','.each-requirement', function(){
                reqid              = $(this).attr('data-id');
                
                reqtitle               = $(this).attr('data-title');
                $('#input-requirement-title-edit').val(reqtitle)
                $('#modal-edit-requirement').modal('show')
                thisbuttontomodal = $(this); 
            })
            $('#btn-modal-edit-requirement-save').on('click', function(){
                var reqtitle = $('#input-requirement-title-edit').val();
                if(reqtitle.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-requirement-title-edit').css('border','1px solid red')
                    toastr.warning('Please fill in required field!','Requirement')
                }else{
                    Swal.fire({
                        title: 'Saving changes...',
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    }) 
                    $.ajax({
                        url: '/hr/employment/requirements',
                        type: 'GET',
                        data: {
                            action        : 'requirement_edit',
                            reqid    : reqid,
                            reqtitle    : reqtitle
                        },
                        success:function(data) {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                            if(data == 1)
                            {
                                thisbuttontomodal.attr('data-title', reqtitle);
                                toastr.success('Updated successfully!','Requirement')
                                $('#label-reqname-'+reqid).text(reqtitle)
                                $('#modal-edit-requirement').modal('toggle')
                            }else{
                            toastr.error('Something went wrong!','Requirement')
                            }
                        }
                        ,error:function(){
                            toastr.error('Something went wrong!','Requirement')
                        }
                    })
                }
            })
            $('#btn-modal-delete-requirement').on('click', function(){
                    Swal.fire({
                        title: 'Are you sure you want to delete this requirement?',
                        type: 'warning',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Delete',
                        showCancelButton: true,
                        allowOutsideClick: false,
                    }).then((confirm) => {
                            if (confirm.value) {
                                
                                $.ajax({
                                    url: '/hr/employment/requirements',
                                    type: 'get',
                                    dataType: 'json',
                                    data: {
                                        action  :  'requirement_delete',
                                        reqid    : reqid
                                    },
                                    success: function(data){
                                        if(data == 1)
                                        {
                                            $('#col-'+reqid).remove()
                                            toastr.success('Deleted successfully!')
                                            $('#modal-edit-requirement').modal('toggle');
                                        }else{
                                            toastr.error('Something went wrong!')
                                        }
                                    }
                                })
                            }
                        })
            })
        })
    </script>
@endsection

