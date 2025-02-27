@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
@endsection

@section('content')
<style>
    #modal-view-file .modal {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            overflow: hidden;
        }
    
    #modal-view-file .modal-dialog {
        position: fixed;
        margin: 0;
        width: 100%;
        height: 100%;
        padding: 0;
    }
    @media (min-width: 576px)
    {
        #modal-view-file .modal-dialog {
            max-width:  unset !important;
            margin: unset !important;
        }
    }
    #modal-view-file .modal-content {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        border: 2px solid #3c7dcf;
        border-radius: 0;
        box-shadow: none;
    }

    #modal-view-file .modal-header {
        position: absolute;
        top: 0;
        right: 0;
        left: 0;
        height: 50px;
        padding: 10px;
        background: #6598d9;
        border: 0;
    }

    #modal-view-file .modal-title {
        font-weight: 300;
        font-size: 2em;
        color: #fff;
        line-height: 30px;
    }

    #modal-view-file .modal-body {
        position: absolute;
        top: 50px;
        bottom: 60px;
        width: 100%;
        font-weight: 300;
        overflow: auto;
        background-color: rgba(0,0,0,.0001) !important;
    }
    #modal-view-file .modal-footer {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        padding: 10px;
        background: #f1f3f5;
    }
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    {{$folderinfo->foldername}}
                </h3>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item"><a href="/mydocs/index">My Documents</a></li>
                <li class="breadcrumb-item active">{{$folderinfo->foldername}}</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box shadow-sm">
                    <span class="info-box-icon border"><i class="far fa-file"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-text">Public</span>
                      <span class="info-box-number">{{collect($files)->where('visible','1')->count()}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                  <div class="info-box shadow-sm">
                    <span class="info-box-icon border"><i class="far fa-file"></i></span>
        
                    <div class="info-box-content">
                      <span class="info-box-text">Only Me</span>
                      <span class="info-box-number">{{collect($files)->where('visible','0')->count()}}</span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                </div>
            </div>
            <div class="card" style="border: unset;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Search File Name</label>
                
                                <div class="input-group">
                                  <div class="input-group-prepend">
                                    <span class="input-group-text">
                                      <i class="fa fa-search"></i>
                                    </span>
                                  </div>
                                  <input type="text" class="form-control" id="input-filter">
                                </div>
                                <!-- /.input group -->
                              </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2" id="file-container">
                @if(count($files)>0)
                    @foreach($files as $file)
                        <div class="col-12 each-file-div" data-string="{{$file->filename}}<">
                            <a class="each-file" style="color: inherit;cursor: pointer;" data-id="{{$file->id}}">
                                <div class="info-box shadow-sm">
                                    <span class="info-box-icon" ><i class="fa fa-paperclip"></i></span>
                                    <div class="info-box-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="info-box-text">{{$file->filename}}</span>
                                        <span class="info-box-number">{{$file->extension}}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border: unset;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;">
                <div class="card-header">  
                    <button type="button" class="btn btn-default btn-block mb-2" id="btn-delete-folder"><i class="fa fa-trash-alt text-danger"></i> Delete Folder</button>                  
                    <button type="button" class="btn btn-primary btn-block" id="btn-showuploadfile"><i class="fa fa-plus"></i> Upload Files</button>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Folder Name</label>
                            <input type="text" class="form-control" value="{{$folderinfo->foldername}}" name="folder-name" id="folder-name"  disabled/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Abbreviation</label>
                            <input type="text" class="form-control" value="{{$folderinfo->folderabbrv}}" name="folder-abbr" id="folder-abbr"  disabled/>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <label>Color</label>
                            <input type="text" class="form-control colorpicker-folder" value="{{$folderinfo->color}}" name="folder-color" id="folder-color"  disabled/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label>Audience</label>
                            <div class="form-group clearfix">
                              <div class="icheck-primary">
                                <input type="radio" id="audience1" name="audience" value="1"/>
                                <label for="audience1">
                                    Public
                                </label>
                              </div>
                              <div class="icheck-primary">
                                <input type="radio" id="audience2" name="audience" value="0"/>
                                <label for="audience2">
                                    Only Me
                                </label>
                              </div>
                              <div class="icheck-primary">
                                <input type="radio" id="audience3" name="audience" value="2"/>
                                <label for="audience3">
                                    Custom (Selected Users)
                                </label>
                              </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="select2-container">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="modal-add-file" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
                <div class="modal-content">
                        <div class="modal-header">
                                <h4 class="modal-title" >Add file(s)</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                                </button>
                        </div>
                        <div class="modal-body" style="max-height: calc(100vh - 200px) !important;
                        overflow-y: auto !important;">
                                <div class="form-group">
                                    <label for="document">Documents</label>
                                    <div class="needsclick dropzone" id="document-dropzone">

                                    </div>
                                </div>
                        </div>
                </div>
                <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="modal-custom-audience" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb2">
                            <div class="form-group">
                            <label>Multiple</label>
                            <select class="duallistbox" multiple="multiple">
                                @foreach ($users as $user)
                                    <option value="{{$user->userid}}" @if($user->selected == 1) selected @endif>{{$user->lastname}}, {{$user->firstname}}</option>
                                @endforeach
                            </select>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-12 text-right">
                            <div class="icheck-primary">
                                <input type="checkbox" id="candownload" checked>
                                <label for="candownload">
                                    Can download
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary" id="btn-customaudience-submit">Save Changes</button>
                </div>
            </div>
                <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <div class="modal fade" id="modal-view-file" aria-hidden="true" style="display: none;" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content" id="view-file-container">
            </div>
        </div>
    </div>
</section>
@endsection
@section('footerjavascript')
    <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
    <!-- bootstrap color picker -->
    <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <script type="text/javascript">
        $('.duallistbox').bootstrapDualListbox()
            // var acceptedfiles ='.pdf';
        var uploadedDocumentMap = {}
        Dropzone.options.documentDropzone = {
            url: '/mydocs/uploadfiles?folderid={{$folderinfo->id}}',
            maxFilesize: 50, // MB
            addRemoveLinks: true,
            // acceptedFiles: acceptedfiles.slice(0,-1),
            type: 'POST',
            headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            success: function (file, response) {
                $('form').append('<input type="hidden" name="document[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
                $('.dz-remove').remove()
                window.location.reload()
            },
            removedfile: function (file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="document[]"][value="' + name + '"]').remove()
            }
        }
        $(document).ready(function(){


            @if($folderinfo->visible == 1)
                $('#audience1').prop('checked',true)
            @elseif($folderinfo->visible == 0)
                $('#audience2').prop('checked',true)
            @elseif($folderinfo->visible == 2)
                $('#audience3').prop('checked',true)
            @endif
            $(document).on('dblclick','input',function () {
                $(this).prop('disabled', true)
                $(this).removeAttr('disabled');
                $(this).focus()
                $('.colorpicker-folder').colorpicker({color : "{{$folderinfo->color}}"})
                $('.colorpicker-folder').colorpicker('setValue', "{{$folderinfo->color}}")
            })
            $(document).on("keydown",'input',function search(e) {
                if(e.keyCode==13){
                    var thiselement = $(this);
                    var name    = $('#folder-name').val();
                    var abbr    = $('#folder-abbr').val();
                    var color   = $('#folder-color').val();
                    if(name.replace(/^\s+|\s+$/g, "").length == 0)
                    {
                        $('#folder-name').css('border','1px solid red');
                        toastr.warning('Please fill in required field!');
                    }else{
                        $.ajax({
                            url: '/mydocs/folderedit',
                            type: 'GET',
                            data: {
                                foldername      : name,
                                folderabbr      : abbr,
                                foldercolor     : color,
                                folderid        : '{{$folderinfo->id}}'
                            },
                            datatype        : 'json',
                            success:function(data){
                                // thiselement.val(data.code)
                                thiselement.prop('disabled',true)
                                toastr.success('Upadated successfully!');
                            }
                        })
                    }
                }
            });
            $('input[name="audience"]').on('click', function(){
                if($(this).val() == 2)
                {
                    $('#modal-custom-audience').modal('show');
                }else{
                    $('#select2-container').empty()
                    $.ajax({
                        url: '/mydocs/folderedit',
                        type: 'GET',
                        data: {
                            visible      : $(this).val(),
                            folderid     : '{{$folderinfo->id}}'
                        },
                        datatype        : 'json',
                        success:function(data){
                            toastr.success('Upadated successfully!');
                        }
                    })
                }
            })
            $('#btn-customaudience-submit').on('click', function(){
                var selectedusers = [];
                var items = $('select[name="_helper2"] option');
                $.each(items, function(){
                    //this will log the value for each item inside the boxview2 list
                    selectedusers.push($(this).val())
                    // console.log(selectedusers)
                });
                if(selectedusers.length == 0)
                {
                    toastr.warning('Please select a user!');
                }else{
                    if($('#candownload').is(":checked"))
                    {
                        var candownload = 1;
                    }else{
                        var candownload = 0;
                    }
                    console.log(selectedusers)
                    $.ajax({
                        url: '/mydocs/folderedit',
                        type: 'GET',
                        data: {
                            folderid     : '{{$folderinfo->id}}',
                            candownload  : candownload,
                            selectedusers: JSON.stringify(selectedusers)
                        },
                        success:function(data){
                            toastr.success('Upadated successfully!');
                            $('#modal-custom-audience').modal('hide')
                            // window.location.reload();
                        }
                    })
                }
            })
            
            $('#btn-showuploadfile').on('click', function(){
                $('#modal-add-file').modal('show')
                $('#modal-add-file').on('show.bs.modal', function () {
                    $('.modal .modal-body').css('overflow-y', 'auto'); 
                    $('.modal .modal-body').css('max-height', $(window).height() * 0.7);
                });
            })
            $("#input-filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".each-file-div").each(function() {
                    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {

                    $(".card-group.card-group-filter:first-of-type").append($(this));
                    $(this).hide();
                    hiddenCards++;

                    } else {

                    $(".card-group.card-group-filter:last-of-type").prepend($(this));
                    $(this).show();
                    visibleCards++;

                    if (((visibleCards % 4) == 0)) {
                        $(".container").append($("<div class='card-group card-group-filter'></div>"));
                    }
                    }
                });

            });
            $('a.each-file').on('click', function(){
                var fileid = $(this).attr('data-id');
                $('#modal-view-file').modal('show')
                $.ajax({
                    url: '/mydocs/fileview',
                    type: 'GET',
                    data: {
                        fileid     : fileid
                    },
                    success:function(data){
                        // thiselement.val(data.code)
                        $('#view-file-container').empty();
                        $('#view-file-container').append(data);
                        
                    }
                })
            })
            $('#btn-delete-folder').on('click', function(){
                var folderid = '{{$folderinfo->id}}';
                Swal.fire({
                    title: 'Are you sure you want to delete this folder?',
                    // text: "You won't be able to revert this!",
                    html: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: '/mydocs/folderdelete',
                            type:"GET",
                            dataType:"json",
                            data:{
                                folderid   :  folderid,
                            },
                            // headers: { 'X-CSRF-TOKEN': token },,
                            complete: function(){
                                toastr.success('Deleted successfully!')
                                window.location.href = "{{ route('mydocsindex')}}";
                            }
                        })
                    }
                })
            })
        })
    </script>
@endsection