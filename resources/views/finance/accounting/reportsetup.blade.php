@extends('finance.layouts.app')

@section('content')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
<style>
    
    .select2-container {
            z-index: 9999;
            margin: 0px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #007bff;
    border-color: #006fe6;
    color: #fff;
    padding: 0 10px;
    margin-top: .31rem;
}
.select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
    color: rgba(255,255,255,.7);
    float: right;
    margin-left: 5px;
    margin-right: -2px;
}

.save-button  {
  background-color: #004A7F;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  /* font-family: Arial;
  font-size: 20px; */
  /* padding: 5px 10px; */
  text-align: center;
  text-decoration: none;
  -webkit-animation: saveglowing 1500ms infinite;
  -moz-animation: saveglowing 1500ms infinite;
  -o-animation: saveglowing 1500ms infinite;
  animation: saveglowing 1500ms infinite;
}
@-webkit-keyframes saveglowing {
  0% { background-color: #28a745; -webkit-box-shadow: 0 0 3px #28a745; }
  50% { background-color: #28a745; -webkit-box-shadow: 0 0 40px #28a745; }
  100% { background-color: #28a745; -webkit-box-shadow: 0 0 3px #28a745; }
}

@-moz-keyframes saveglowing {
  0% { background-color: #28a745; -moz-box-shadow: 0 0 3px #28a745; }
  50% { background-color: #28a745; -moz-box-shadow: 0 0 40px #28a745; }
  100% { background-color: #28a745; -moz-box-shadow: 0 0 3px #28a745; }
}

@-o-keyframes saveglowing {
  0% { background-color: #28a745; box-shadow: 0 0 3px #28a745; }
  50% { background-color: #28a745; box-shadow: 0 0 40px #28a745; }
  100% { background-color: #28a745; box-shadow: 0 0 3px #28a745; }
}

@keyframes saveglowing {
  0% { background-color: #28a745; box-shadow: 0 0 3px #28a745; }
  50% { background-color: #28a745; box-shadow: 0 0 40px #28a745; }
  100% { background-color: #28a745; box-shadow: 0 0 3px #28a745; }
}
.edit-button  {
  background-color: #004A7F;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  /* font-family: Arial;
  font-size: 20px; */
  /* padding: 5px 10px; */
  text-align: center;
  text-decoration: none;
  -webkit-animation: editglowing 1500ms infinite;
  -moz-animation: editglowing 1500ms infinite;
  -o-animation: editglowing 1500ms infinite;
  animation: editglowing 1500ms infinite;
}
@-webkit-keyframes editglowing {
  0% { background-color: #ffc107; -webkit-box-shadow: 0 0 3px #ffc107; }
  50% { background-color: #ffc107; -webkit-box-shadow: 0 0 40px #ffc107; }
  100% { background-color: #ffc107; -webkit-box-shadow: 0 0 3px #ffc107; }
}

@-moz-keyframes editglowing {
  0% { background-color: #ffc107; -moz-box-shadow: 0 0 3px #ffc107; }
  50% { background-color: #ffc107; -moz-box-shadow: 0 0 40px #ffc107; }
  100% { background-color: #ffc107; -moz-box-shadow: 0 0 3px #ffc107; }
}

@-o-keyframes editglowing {
  0% { background-color: #ffc107; box-shadow: 0 0 3px #ffc107; }
  50% { background-color: #ffc107; box-shadow: 0 0 40px #ffc107; }
  100% { background-color: #ffc107; box-shadow: 0 0 3px #ffc107; }
}

@keyframes editglowing {
  0% { background-color: #ffc107; box-shadow: 0 0 3px #ffc107; }
  50% { background-color: #ffc107; box-shadow: 0 0 40px #ffc107; }
  100% { background-color: #ffc107; box-shadow: 0 0 3px #ffc107; }
}
</style>
<div class="row p-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info">
                <div class="row">
                    <div class="col-md-6">
                        <label>Select Configuration</label> 
                        <select class="form-control" id="select-configuration">
                            @if(count($configtypes) == 0)
                                <option value="">No Reports Found</option>
                            @else
                                <option value="">Select Report</option>
                                @foreach($configtypes as $configtype)
                                    <option value="{{$configtype->id}}">{{$configtype->description}}</option>
                                @endforeach
                            @endif
                        </select>
                      </div>
                      <div class="col-md-2">
                          <label>&nbsp;</label><br/>
                            <button type="button" class="btn btn-default" id="addReport"><i class="fa fa-plus"></i> Create</button>
                        </div>
                      <div class="col-md-4 text-right">
                        <label>&nbsp;</label><br/>
                        <button type="button" class="btn btn-default" id="btn-exportsetup"><i class="fa fa-download"></i> Export</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="container-report">
            <div class="card-body" id="configcontainer">
                <div class="row mb-2" id="btn-btncontainer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-info" id="btn-addheader"><i class="fa fa-plus"></i> Add header</button>
                    </div>
                </div>
                <div id="container-header">
                    {{-- <div class="row" id="container-header">

                    </div>
                    <div class="row">
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <script>
        $(document).ready(function(){
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 500
            });
            function generate(configuration)
            {
                $('#container-header').empty();
                if(configuration == null || configuration == "")
                {
                    $('#container-report').hide();
                    $('#btn-exportsetup').hide();
                }else{
                    $('#container-report').show();
                    $('#btn-exportsetup').show();
                    $.ajax({
                        url: '{{ route('getconfigsetup')}}',
                        type:"GET",
                        data:{
                            configid: configuration
                        },
                        // headers: { 'X-CSRF-TOKEN': token },,
                        success: function(data){
                            $('#container-header').prepend(data)
                            $('.input-description').prop('disabled',true)
                            $('.select-maps').prop('disabled',true)
                        }
                    })
                }
            }
            generate(null);
            $('#select-configuration').on('change', function(){
                generate($(this).val())
            })
            
            $(document).on('click','#addReport', function(){
                Swal.fire({
                    title: 'New Report',
                    input: 'text',
                    inputAttributes: {
                        id: 'newreport'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Create',
                    allowOutsideClick: false,
                    preConfirm: () => {
                        if($("#newreport").val().replace(/^\s+|\s+$/g, "").length == 0 ){
                            Swal.showValidationMessage(
                                "Please fill in the required section!"
                            );
                        }
                    }
                }).then((result) => {
                    if (result.value) {
                            // $('form[name=createbook]').submit();
                            $.ajax({
                                url: '{{ route('createreport')}}',
                                type:"GET",
                                dataType:"json",
                                data:{
                                    newreport      : $('#newreport').val(),
                                },
                                // headers: { 'X-CSRF-TOKEN': token },,
                                success: function(data){
                                    if(data[0] == 0)
                                    {
                                        $('#configselection').append(
                                            '<option value="'+data[1]+'">'+data[2]+'</option>'
                                        )
                                        toastr.success('Created successfully','Report')
                                    }else{
                                        toastr.warning('Report already exists', 'Report')
                                    }
                                    
                                }
                            })
                    }
                })
            })
            $('#btn-addheader').on('click', function(){
                $(this).prop('disabled',true)
                $.ajax({
                    url: '{{ route('getaddheader')}}',
                    type:"GET",
                    // headers: { 'X-CSRF-TOKEN': token },,
                    success: function(data){
                        $('#container-header').prepend(data)
                        
                    }
                })
            })
            $(document).on('click','.btn-submitaddheader', function(){
                var configid    = $('#select-configuration').val();
                var newheaderid = $(this).closest('.row').find('.select-header').val();
                $.ajax({
                    url: '{{ route('submitaddheader')}}',
                    type:"GET",
                    dataType: 'json',
                    data: {
                        configid    :   configid,
                        newheaderid :   newheaderid
                    },
                    success: function(data){
                      if(data == 1)
                      {
                            toastr.success('Added successfully!', 'Header')
                            generate(configid);
                      }else{
                            toastr.error('Something went wrong!', 'Header')
                      }  
                        
                    }
                })
            })
            $(document).on('click','.btn-addsubheader', function(){
                $('.btn-addsubheader').prop('disabled',true)
                var headerid = $(this).attr('data-id');
                $.ajax({
                    url: '{{ route('getaddsubheader')}}',
                    type:"GET",
                    data: {
                        headerid    :   headerid
                    },
                    success: function(data){
                        $('#container-subheader'+headerid).prepend(data)
                        
                    }
                })
            })
            $(document).on('click', '.btn-removeadd', function(){
                var dataheader = $(this).closest('.row').find('.select-header').length;
                var datasubheader = $(this).closest('.row').find('.select-subheader').length;
                var datadetail = $(this).closest('.row').find('.input-detail').length;
                if(dataheader>0)
                {
                    $('#btn-addheader').removeAttr('disabled')
                }
                if(datasubheader>0)
                {
                    $('.btn-addsubheader').removeAttr('disabled');
                }
                if(datadetail>0)
                {
                    $('.btn-adddetail').removeAttr('disabled');
                }
                $(this).closest('.row').remove();
            })
            $(document).on('click','.btn-submitaddsubheader', function(){
                var headerid    = $(this).attr('data-id');
                var newsubheaderid = $(this).closest('.row').find('.select-subheader').val();
                $.ajax({
                    url: '{{ route('submitaddsubheader')}}',
                    type:"GET",
                    dataType: 'json',
                    data: {
                        headerid    :   headerid,
                        newsubheaderid :   newsubheaderid
                    },
                    success: function(data){
                      if(data == 1)
                      {
                            toastr.success('Added successfully!', 'Sub Header')
                            generate($('#select-configuration').val());
                      }else{
                            toastr.error('Something went wrong!', 'Sub Header')
                      }  
                        
                    }
                })
            })
            $(document).on('click','.btn-adddetail', function(){
                $('.btn-adddetail').prop('disabled',true)
                var subheaderid = $(this).attr('data-id');
                $.ajax({
                    url: '{{ route('getaddmap')}}',
                    type:"GET",
                    data: {
                        subheaderid    :   subheaderid
                    },
                    success: function(data){
                        $('#container-detail'+subheaderid).prepend(data)
                        
                    }
                })
            })
    // $('form').dblclick(function () {
    //     $(this).find('input,select').removeProp('disabled').removeClass('no-pointer');
    // }).find(':input').addClass('no-pointer');
            $(document).on('dblclick','.input-group', function(){
                $(this).find('input').removeAttr('disabled');
                $(this).find('select').removeAttr('disabled');
                $(this).closest('.detailrow').find('.btn-editdetail').addClass('edit-button');
            })
            $(document).on('click','.btn-editdetail', function(){
                var detailid        = $(this).attr('data-id');
                var editdescription = $(this).closest('.detailrow').find('.input-description').val();
                var editmapid       = $(this).closest('.detailrow').find('.select-maps').val();
                if(editdescription.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('.detailrow').find('.input-description').css('border','1px solid red')
                    toastr.warning('Please fill in required field!', 'Detail')
                }else{
                    $(this).closest('.detailrow').find('.input-description').removeAttr('style');
                    $.ajax({
                        url: '{{ route('submiteditdetail')}}',
                        type:"GET",
                        data: {
                            detailid            :   detailid,
                            editdescription     :   editdescription,
                            editmapid           :   editmapid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Updated successfully!', 'Detail')
                                generate($('#select-configuration').val());
                            }else{
                                toastr.error('Something went wrong!', 'Detail')
                            }
                        }
                    })
                }
            })
            $(document).on('click', '.btn-submitadddetail', function(){
                var subheaderid = $(this).attr('data-id');
                var detailname = $(this).closest('.row').find('.input-detail').val();
                var mapid       = $(this).closest('.row').find('.select-map').val();

                if(detailname.replace(/^\s+|\s+$/g, "").length == 0)
                {
                    $(this).closest('.row').find('.input-detail').css('border','1px solid red')
                    toastr.warning('Please fill in required field!', 'Detail')
                }else{
                    $(this).closest('.row').find('.input-detail').removeAttr('style');
                    $.ajax({
                        url: '{{ route('submitadddetail')}}',
                        type:"GET",
                        data: {
                            subheaderid     :   subheaderid,
                            detailname      :   detailname,
                            mapid           :   mapid
                        },
                        success: function(data){
                            if(data == 1)
                            {
                                toastr.success('Added successfully!', 'Detail')
                                generate($('#select-configuration').val());
                            }else{
                                toastr.error('Something went wrong!', 'Detail')
                            }
                        }
                    })
                }
            })
            $(document).on('click', '.btn-deleteheader', function(){
                var headerid = $(this).attr('data-id');
                // var thisrow = $(this).closest('.rowparticular');
                Swal.fire({
                    title: 'Are you sure you want to delete this header?',
                    text: 'Subheaders and details will also be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('deleteheader')}}',
                            type:"GET",
                            data: {
                                headerid: headerid
                            },
                            dataType: 'json',
                            complete:function()
                            {
                                toastr.success('Deleted successfully!','Header')
                                generate($('#select-configuration').val());
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-deletesubheader', function(){
                var subheaderid = $(this).attr('data-id');
                // var thisrow = $(this).closest('.rowparticular');
                Swal.fire({
                    title: 'Are you sure you want to delete this subheader?',
                    text: 'Deetails will also be deleted.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('deletesubheader')}}',
                            type:"GET",
                            data: {
                                subheaderid: subheaderid
                            },
                            dataType: 'json',
                            complete:function()
                            {
                                toastr.success('Deleted successfully!','Sub Header')
                                generate($('#select-configuration').val());
                            }
                        })
                    }
                })
            })
            $(document).on('click', '.btn-deletedetail', function(){
                var detailid = $(this).attr('data-id');
                // var thisrow = $(this).closest('.rowparticular');
                Swal.fire({
                    title: 'Are you sure you want to delete this detail?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '{{ route('deletedetail')}}',
                            type:"GET",
                            data: {
                                detailid: detailid
                            },
                            dataType: 'json',
                            complete:function()
                            {
                                toastr.success('Deleted successfully!','Detail')
                                generate($('#select-configuration').val());
                            }
                        })
                    }
                })
            })

            $(document).on('click','#btn-exportsetup', function(){
                var setupid = $('#select-configuration').val();
				window.open("{{ route('setupexport')}}?setupid="+setupid);
            })
            
        });
    </script>
  @endsection