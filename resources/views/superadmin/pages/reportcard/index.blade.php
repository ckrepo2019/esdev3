
@php

$check_refid = DB::table('usertype')->where('id',Session::get('currentPortal'))->select('refid')->first();

if(Session::get('currentPortal') == 3){
      $extend = 'registrar.layouts.app';
}else if(auth()->user()->type == 17){
      $extend = 'superadmin.layouts.app2';
}else if(auth()->user()->type == 10){
      $extend = 'hr.layouts.app';
}else if(Session::get('currentPortal') == 7){
      $extend = 'studentPortal.layouts.app2';
}else if(Session::get('currentPortal') == 6){
      $extend = 'adminPortal.layouts.app2';
}else if(Session::get('currentPortal') == 9){
      $extend = 'parentsportal.layouts.app2';
}else if(Session::get('currentPortal') == 2){
      $extend = 'principalsportal.layouts.app2';
}else if(Session::get('currentPortal') == 1){
      $extend = 'teacher.layouts.app';
}else if ( Session::get('currentPortal') == 14){
      $extend = 'deanportal.layouts.app2';
}else if ( Session::get('currentPortal') == 16){
      $extend = 'chairpersonportal.layouts.app2';
}else{
    if(isset($check_refid->refid)){
        if($check_refid->refid == 27){
                $extend = 'academiccoor.layouts.app2';
        }
    }else{
        $extend = 'general.defaultportal.layouts.app';
    }
}
@endphp

@extends($extend)

@section('pagespecificscripts')
      
      <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
      <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
      <style>
         
            .select2-container--default .select2-selection--single .select2-selection__rendered {
                  margin-top: -9px;
            }
            .shadow {
                  box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
                  border: 0;
            }
            .calendar-table{
                  display: none;
            }
            .drp-buttons{
                  display: none !important;
            }
            #et{
                  height: 10px;
                  visibility: hidden;
            }
            .form-control-sm-form {
                  height: calc(1.4rem + 1px);
                  padding: 0.75rem 0.3rem;
                  font-size: .875rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }
            input[type=search]{
                  height: calc(1.7em + 2px) !important;
            }

            .btn-group-sm>.btn, .btn-sm {
                  padding: 0.25rem 0.5rem;
                  font-size: .7rem;
                  line-height: 1.5;
                  border-radius: 0.2rem;
            }

            .tooltip > .arrow {
                  visibility: hidden;
            }
      </style>


@endsection

@section('content')

@php
        $resourcesPath = resource_path('views/principalsportal/forms/sf9layout');
        dd($resourcesPath);
        // $files = array_diff(scandir($resourcesPath), array('.', '..'));
        $all_files = array();
      
        // foreach($files as $item){
        //     try{
        //         $resourcesPath = resource_path('views/principalsportal/forms/sf9layout/'.$item);
        //         $temp_files = array_diff(scandir($resourcesPath), array('.', '..'));
        //         if(count($temp_files) > 0 ){
        //             $tem_data = (object)[
        //                 'school'=>$item,
        //                 'files'=>$temp_files
        //             ];
        //             array_push($all_files,$tem_data);
        //         }
        //     }catch(\Exception $e){}
        // }

        $academicprogram = DB::table('academicprogram')
                            ->where('id','!=',6)
                            ->get();



 
@endphp


<section class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1>Report Card Layout</h1>
              </div>
              <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="/home">Home</a></li>
                  <li class="breadcrumb-item active">Report Card Layout</li>
              </ol>
              </div>
          </div>
      </div>
</section>
<section class="content pt-0">
<div class="modal fade" id="pdf-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header pb-2 pt-2 border-0">
                <h4 class="modal-title" style="font-size: 1.1rem !important" id="format_desc"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-md-4">
                        <label for="" class="mb-1"><span class="text-danger">*</span>Academic Program</label>
                        <select name="" id="filter_acad" class="form-control form-control-sm-form select2">
                              <option value="">Select Academic Program</option>
                              @foreach ($academicprogram as $item)
                                    <option value="{{$item->id}}">{{$item->progname}}</option>
                              @endforeach
                        </select>
                    </div>
                    <div class="col-md-8 text-right pt-2">
                        <button class="btn btn-primary" id="assign">Assign</button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" id="blanktemplateview">
                        <p>Please select grade level.</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>


<div class="container-fluid">
    <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="info-box shadow-lg">
                                <div class="info-box-content">
                                    <div class="row" style="margin-top: 15px;">
                                        <div class="col-md-12" style="font-size:.9rem">
                                            <table class="table table-sm table-striped display table-bordered" id="datatable_1" width="100%">
                                                <thead>
                                                        <tr>
                                                            <th width="30%">Description</th>
                                                            <th width="10%" class="text-center">School</th>
                                                            <th width="20%">File Name</th>
                                                            <th width="30%" class="text-center">Acad</th>
                                                            <th width="10%"></th>
                                                        </tr>
                                                        @php
                                                            $format_count = 1;
                                                        @endphp
                                                        @foreach($all_files as $key=>$item)
                                                            @foreach($item->files as $file)
                                                                <tr>
                                                                    <td class="align-middle">{{'Format '.$format_count}}</td>
                                                                    <td class="text-center align-middle">{{$item->school}}</td>
                                                                    <td class="align-middle">{{$file}}</td>
                                                                    <td class="align-middle acadholder" data-school="{{$item->school}}" data-template="{{$file}}"></td>
                                                                    <td ><a class="btn btn-primary text-light viewtemplate" data-school="{{$item->school}}" data-template="{{$file}}" data-desc="{{'Format '.$format_count}}" style="font-size: 10px; margin: 0px 0px 0px 5px"><i class="fas fa-eye"></i> view</a></td>
                                                                </tr>
                                                            @endforeach
                                                            @php
                                                                $format_count += 1;
                                                            @endphp
                                                        @endforeach
                                                </thead>
                                                <tbody  style="font-size: 12px;"></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
</section>
  
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>

<script>
    var allowButtons = true;
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
    })
</script>

<script>
    $(document).ready(function () {

        $('#filter_acad').select2({
            placeholder: 'Academic Program'
        });
      
        var selected_school = null;
        var selected_template = null;

        $(document).on('click','#assign',function(){

            Swal.fire({
                    title: 'Are you sure you want to assign template?',
                    text: 'This will remove the existing active template.',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Assign'
            }).then((result) => {
                    if (result.value) {
                    $.ajax({
                        type:'GET',
                        url: '/setup/reportcard/addtemplate',
                        data:{
                            schoolname:selected_school,
                            acadprogid:$('#filter_acad').val(),
                            description:selected_template,
                            templatepath:'principalsportal.forms.sf9layout.'+selected_school+'.'+selected_template.replace('.blade.php','')
                        },
                        success:function(data) {
                            if(data[0].status == 1){
                                Toast.fire({
                                    type: 'success',
                                    title: data[0].data
                                })
                                active_tempate()

                            }else{
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].data
                                })
                            }      
                        }
                    })
                }
            })
        })

        $(document).on('click','.viewtemplate',function(){
            selected_school = $(this).attr('data-school');
            selected_template = $(this).attr('data-template');
            var desc = $(this).attr('data-desc');
            $('#filter_acad').val("").change();
            $('#assign').attr('disabled','disabled')

            $('#format_desc').text(desc)
            $('#blanktemplateview')[0].innerHTML = '<p>Please select academic program.</p>'
            $('#pdf-modal').modal('show');
        })
       
        $(document).on('change','#filter_acad',function(){
            $('#assign').removeAttr('disabled')
            if($(this).val() != "" && $(this).val() != null){
                $('#blanktemplateview').html('<object data="/sf9template/blank?school='+selected_school+'&template='+selected_template+'&acad='+$(this).val()+'" height="500px" width="100%"></object>');
            }
        })

        active_tempate()

        function active_tempate(){
            
            $.ajax({
                type:'GET',
                url: '/setup/reportcard/activatetemplate',
                success:function(data) {
                    $('.acadholder').empty()
                       $.each(data,function(a,b){
                            $('td[data-school="'+b.schoolname+'"][data-template="'+b.description+'"]').append('<span class="badge badge-primary mr-2">'+b.progname+'</span>')
                       })
                }
            })
        }
       
        
    });
</script>

@endsection

@section('footerscript')

@endsection

