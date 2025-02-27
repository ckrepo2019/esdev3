

@extends('hr.layouts.app')
@section('content')
<style>
    #table-types td, #table-types th{
        padding: 2px;
    }
    .card{
        border: none !important;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- <h1>Payroll</h1> -->
          <h4 >
            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
            Holidays</h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Holidays</li>
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
    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Holidays</a></li>
    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Types</a></li>
    </ul>
    </div>
    </div>
    
    <div class="tab-content">
        <div class="tab-pane active" id="tab_1">
            {{-- <div class="card" style="background-color: white; border-radius: 10px; box-shadow:0 0 1px rgb(0 0 0 / 13%), 0 1px 3px rgb(0 0 0 / 20%) !important;">
                <div class="card-body">
                    <table class="table" id="table-types">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Title</th>
                                <th>No Work</th>
                                <th>Working</th>
                                <th style="width: 5%;">Edit</th>
                                <th style="width: 5%;">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($holidaytypes) > 0)
                                @foreach($holidaytypes as $type)
                                    <tr>
                                        <td>
                                            <div class="form-group m-0">
                                            <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{$type->typename}}" disabled/>
                                            </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-0">
                                            <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{$type->ratepercentagenowork}}" disabled/>
                                            <div class="input-group-append">
                                            <div class="input-group-text">%</div>
                                            </div>
                                            </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group m-0">
                                            <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" value="{{$type->ratepercentageworkon}}" disabled/>
                                            <div class="input-group-append">
                                                <div class="input-group-text">%</div>
                                            </div>
                                            </div>
                                            </div>
                                        </td>
                                        <td class="text-right">@if($type->type == 1) <button type="button" class="btn btn-sm btn-outline-warning"><i class="fa fa-edit"></i></button>@else @endif</td>
                                        <td class="text-right">@if($type->type == 1) <button type="button" class="btn btn-sm btn-outline-danger"><i class="fa fa-trash-alt"></i></button>@else @endif</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> --}}
        </div>
        
        <div class="tab-pane" id="tab_2">
            <div class="row mb-3">
                <div class="col-12 text-right">
                    <button type="button" class="btn btn-outline-primary" id="btn-tomodal-type-add"><i class="fa fa-plus"></i> Add Type</button>
                </div>
            </div> 
            @if(count($holidaytypes) > 0)
                <div class="row" id="container-types">
                    @foreach($holidaytypes as $type)
                        <div class="col-4 mb-3">
                            <div class="card h-100 shadow" style="background-color: white; border-radius: 0px 30px; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                {{-- <div class="card-header">
                                    <label>{{$type->typename}}</label>
                                </div> --}}
                                <div class="card-body">
                                    <label id="label-typename-{{$type->id}}">{{$type->typename}}</label><br/>
                                    <small>Rate (%) / <strong>No Work</strong>: <u id="rate-nowork-{{$type->id}}">{{$type->ratepercentagenowork}}</u>&nbsp;&nbsp;%</small><br/>
                                    <small>Rate (%) / <strong>Work</strong>: <u id="rate-work-{{$type->id}}">{{$type->ratepercentageworkon}}</u>&nbsp;&nbsp;%</small>
                                </div>
                                <a href="#" class="small-box-footer bg-secondary pl-2 each-type" data-id="{{$type->id}}"  data-title="{{$type->typename}}"  data-ratenowork="{{$type->ratepercentagenowork}}"  data-ratework="{{$type->ratepercentageworkon}}" style="vertical-align: middle; border-radius: 0px 30px;">
                                &nbsp;&nbsp;Edit info <i class="fas fa-edit fa-xs"></i>
                                 </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        
        </div>
    </div>
    
    </div>
    
 <div class="modal fade" id="modal-add-type">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control" id="input-type-title-add"/>
                    </div>
                    <div class="col-md-6">
                        <label>Rate (%) / No Work</label>
                        <input type="number" class="form-control" id="input-type-ratenowork-add" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                    <div class="col-md-6">
                        <label>Rate (%) / Work</label>
                        <input type="number" class="form-control" id="input-type-ratework-add" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-add-type-save">Save changes</button>
            </div>
        </div>
    
    </div>
    
</div>
 <div class="modal fade" id="modal-edit-type">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label>Title</label>
                        <input type="text" class="form-control" id="input-type-title-edit"/>
                    </div>
                    <div class="col-md-6">
                        <label>Rate (%) / No Work</label>
                        <input type="number" class="form-control" id="input-type-ratenowork-edit" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                    <div class="col-md-6">
                        <label>Rate (%) / Work</label>
                        <input type="number" class="form-control" id="input-type-ratework-edit" step=".01" min="0"  onkeydown="return event.keyCode !== 69"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn-modal-edit-type-save">Save changes</button>
            </div>
        </div>
    
    </div>
    
</div>
    
@endsection
@section('footerscripts')
    <script>
        $(document).ready(function(){
            $('#btn-tomodal-type-add').on('click', function(){
                $('#modal-add-type').modal('show')
            })
            $('#btn-modal-add-type-save').on('click', function(){
                var newtypetitle = $('#input-type-title-add').val();
                var newtyperatenowork = $('#input-type-ratenowork-add').val();
                var newtyperatework = $('#input-type-ratework-add').val();
                if(newtypetitle.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-type-title-add').css('border','1px solid red')
                    toastr.warning('Please fill in required field!','Type')
                }else{
                    Swal.fire({
                        title: 'Saving changes...',
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    }) 
                    $.ajax({
                        url: '/holidaytypes',
                        type: 'GET',
                        data: {
                            action        : 'holiday_add',
                            typetitle    : newtypetitle,
                            typeratenowork    : newtyperatenowork,
                            typeratework  : newtyperatework
                        },
                        success:function(data) {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                            if(data == 0)
                            {
                            toastr.error('This type exists!','Type')
                            }else{
                                toastr.success('Updated successfully!','Type')
                                $('#container-types').prepend(
                                    `
                                    <div class="col-4 mb-3">
                                        <div class="card h-100 shadow" style="background-color: white; border-radius: 0px 30px; box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;">
                                            <div class="card-body">
                                                <label id="label-typename-`+data+`">`+newtypetitle+`</label><br/>
                                                <small>Rate (%) / <strong>No Work</strong>: <u id="rate-nowork-`+data+`">`+newtyperatenowork+`</u>&nbsp;&nbsp;%</small><br/>
                                                <small>Rate (%) / <strong>Work</strong>: <u id="rate-work-`+data+`">`+newtyperatework+`</u>&nbsp;&nbsp;%</small>
                                            </div>
                                            <a href="#" class="small-box-footer bg-secondary pl-2 each-type" data-id="`+data+`"  data-title="`+newtypetitle+`"  data-ratenowork="`+newtyperatenowork+`"  data-ratework="`+newtyperatework+`" style="vertical-align: middle; border-radius: 0px 30px;">
                                            &nbsp;&nbsp;Edit info <i class="fas fa-edit fa-xs"></i>
                                            </a>
                                        </div>
                                    </div>`
                                )
                                $('#modal-add-type').modal('toggle')
                            }
                        }
                        ,error:function(){
                            toastr.error('Something went wrong!','Type')
                        }
                    })
                }
            })
            var typeid;
            var typetitle;
            var typeratenowork;
            var typeratework;
            var thisbuttontomodal;
            $(document).on('click','.each-type', function(){
                typeid              = $(this).attr('data-id');
                
                title               = $(this).attr('data-title');
                typeratenowork      = $(this).attr('data-ratenowork');
                typeratework        = $(this).attr('data-ratework');
                $('#input-type-title-edit').val(title)
                $('#input-type-ratenowork-edit').val(typeratenowork)
                $('#input-type-ratework-edit').val(typeratework)
                $('#modal-edit-type').modal('show')
                thisbuttontomodal = $(this);
            })
            $('#btn-modal-edit-type-save').on('click', function(){
                var typetitle = $('#input-type-title-edit').val();
                var typeratenowork = $('#input-type-ratenowork-edit').val();
                var typeratework = $('#input-type-ratework-edit').val();
                if(typetitle.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $('#input-type-title-edit').css('border','1px solid red')
                    toastr.warning('Please fill in required field!','Type')
                }else{
                    Swal.fire({
                        title: 'Saving changes...',
                        onBeforeOpen: () => {
                            Swal.showLoading()
                        },
                        allowOutsideClick: false
                    }) 
                    $.ajax({
                        url: '/holidaytypes',
                        type: 'GET',
                        data: {
                            action        : 'holiday_edit',
                            typeid    : typeid,
                            typetitle    : typetitle,
                            typeratenowork    : typeratenowork,
                            typeratework  : typeratework
                        },
                        success:function(data) {
                                $(".swal2-container").remove();
                                $('body').removeClass('swal2-shown')
                                $('body').removeClass('swal2-height-auto')
                            if(data == 1)
                            {
                                thisbuttontomodal.attr('data-title', typetitle);
                                thisbuttontomodal.attr('data-ratenowork', typeratenowork);
                                thisbuttontomodal.attr('data-ratework', typeratework);
                                toastr.success('Updated successfully!','Type')
                                $('#label-typename-'+typeid).text(typetitle)
                                $('#rate-nowork-'+typeid).text(typeratenowork)
                                $('#rate-work-'+typeid).text(typeratework)
                                $('#modal-edit-type').modal('toggle')
                            }else{
                            toastr.error('Something went wrong!','Type')
                            }
                        }
                        ,error:function(){
                            toastr.error('Something went wrong!','Type')
                        }
                    })
                }
            })
        })
    </script>
@endsection

