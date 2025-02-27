@extends($extends)

@section('headerjavascript')
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <!-- Bootstrap4 Duallistbox -->
    <link rel="stylesheet" href="{{asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')}}">
@endsection

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3>
                    My Documents
                </h3>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item active">My Documents</li>
                <li class="breadcrumb-item active">Folders</li>
            </ol>
            </div>
        </div>
    </div>
</section>
<section class="content-body">
    <div class="row mb-2">
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fa fa-folder"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Folders</span>
              <span class="info-box-number" id="folder-count">{{count($myfolders)}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box shadow-sm">
            <span class="info-box-icon bg-warning"><i class="fa fa-file-alt"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Files</span>
              <span class="info-box-number">{{collect($myfolders)->sum('filescount')}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box shadow-sm">
            <div class="info-box-content">
              <span class="info-box-text">Shared Folders</span>
              <span class="info-box-number">{{collect($myfolders)->where('visible','1')->count()}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-12">
          <div class="info-box shadow-sm">
            <div class="info-box-content">
              <span class="info-box-text">Shared Files</span>
              <span class="info-box-number">{{collect($myfolders)->sum('filescount')}}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card" style="border: unset;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Search folder</label>
                
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
            <button type="button" class="btn btn-default mb-2" data-toggle="modal" data-target="#modal-showcreatenewfolder"><i class="fa fa-plus"></i> Create New Folder</button>
            <div class="row mb-2" id="folder-container">
                @if(count($myfolders)>0)
                    @foreach($myfolders as $folder)
                        @php
                        
                            if($folder->folderabbrv == null || $folder->folderabbrv == '')
                            {
                                $abbrv = " ";
        
                            }else{
                                $abbrv = $folder->folderabbrv;
                            }
                            $classbg = '';
                            $stylebg = '';
                            if($folder->color == null)
                            {
                                $classbg = 'bg-info';
                            }else{
                                $stylebg = 'style=background-color:'.$folder->color;
                            }
                        @endphp
                        <div class="col-md-6 col-sm-6 col-12 each-folder" data-string="{{$folder->foldername}} {{$folder->folderabbrv}}<">
                            <a href="/mydocs/filesindex?folderid={{$folder->id}}&extends={{$extends}}" style="color: inherit;">
                                <div class="info-box shadow-sm">
                                    <span class="info-box-icon" {{$stylebg}}><i class="far fa-folder"></i></span>
                                    <div class="info-box-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <span class="info-box-text">{{$folder->foldername}}</span>
                                        <span class="info-box-number">{{$folder->folderabbrv}}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border: 1px solid orange;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;">
                <div class="card-header">
                    <h4 class="card-title" style="font-weight: bold;">Shared with me</h4>
                </div>
            </div>
            <div class="card" style="border: 1px solid orange;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; height: 250px;">
                <div class="card-header">
                    <h4 class="card-title" style="font-weight: bold;">Files ({{count($sharedfiles)}})</h4>
                </div>
                <div class="card-body p-0" style="height: 300px; overflow-y: scroll;">
                    <div class="row mb-2">
                        @if(count($sharedfiles)>0)
                            @foreach($sharedfiles as $sharedfile)
                                <div class="col-md-12 m-0">
                                    <a href="{{asset($sharedfile->filepath)}}" class="each-shared-file" data-id="{{$sharedfile->id}}" data-title="{{$sharedfile->filename}}" style="cursor: pointer; color: inherit;" download>
                                        <div class="info-box shadow-sm p-1 m-0" >
                                            <span class="info-box-icon"><i class="far fa-file"></i></span>
                                            <div class="info-box-content p-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <span class="info-box-text">{{$sharedfile->filename}}</span>
                                                <span class="info-box-number" style="font-size: 11px;">{{$sharedfile->lastname}}, {{$sharedfile->firstname}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="card" style="border: 1px solid orange;box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important;">
                <div class="card-header">
                    <h4 class="card-title" style="font-weight: bold;">Folders ({{count($sharedfolders)}})</h4>
                </div>
                <div class="card-body p-0" style="height: 350px; overflow-y: scroll;">
                    <div class="row">
                        @if(count($sharedfolders)>0)
                            @foreach($sharedfolders as $sharedfolder)
                                <div class="col-md-12 m-0">
                                    <a class="each-shared-folder" data-id="{{$sharedfolder->id}}" data-title="{{$sharedfolder->foldername}}" style="cursor: pointer;">
                                        <div class="info-box shadow-sm p-1 m-0" >
                                            @if($sharedfolder->folderabbrv == null)
                                            <span class="info-box-icon" style="font-size: 16px;"><i class="far fa-folder"></i></span>
                                            @else
                                            <span class="info-box-icon" style="font-size: 13px;">{{$sharedfolder->folderabbrv}}</span>
                                            @endif
                                            <div class="info-box-content p-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <span class="info-box-text">{{$sharedfolder->foldername}}</span>
                                                <span class="info-box-number" style="font-size: 11px;">{{$sharedfolder->lastname}}, {{$sharedfolder->firstname}}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modal-showcreatenewfolder">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Create New Folder</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Folder Name</label>
                        <input type="text" class="form-control" name="newfolder-name" id="newfolder-name"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <label>Abbreviation</label>
                        <input type="text" class="form-control" name="newfolder-abbrv" id="newfolder-abbrv"/>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Color</label>
                            <input type="text" class="form-control colorpicker-newfolder" name="newfolder-color" id="newfolder-color"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Audience</label>
                        <div class="form-group clearfix">
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="audience1" name="audience" value="1">
                            <label for="audience1">
                                Public
                            </label>
                          </div>
                          <div class="icheck-primary d-inline">
                            <input type="radio" id="audience2" name="audience" value="0" checked>
                            <label for="audience2">
                                Only Me
                            </label>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default btn-close-modal" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" id="btn-newfolder-submit">Create</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
      <div class="modal fade" id="modal-show-sharedfolder">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="modal-show-sharedfolder-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" id="modal-show-sharedfolder-container">
                  
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
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
        
        $(document).ready(function(){

            $('.colorpicker-newfolder').colorpicker();

            $("#input-filter").on("keyup", function() {
                var input = $(this).val().toUpperCase();
                var visibleCards = 0;
                var hiddenCards = 0;

                $(".container").append($("<div class='card-group card-group-filter'></div>"));


                $(".each-folder").each(function() {
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
            $('#btn-newfolder-submit').on('click', function(){
                var name = $('#newfolder-name').val();
                var abbrv = $('#newfolder-abbrv').val();
                var color = $('#newfolder-color').val();
                var audience = $('input[name="audience"]:checked').val();

                if(name.replace(/^\s+|\s+$/g, "").length == 0)
                {

                    $('#newfolder-name').css('border','1px solid red');
                    toastr.warning('Please fill in required field!');

                }else{

                    $.ajax({
                        url: '/mydocs/createfolder',
                        type:"GET",
                        dataType:"json",
                        data:{
                            name        : name,
                            abbrv       : abbrv,
                            color       : color,
                            audience    : audience
                        },
                        success: function(data){

                            if(data.status == 1)
                            {
                                toastr.success('Created successfully!')
                                var foldercount = parseInt($('#folder-count').text());
                                $('#folder-count').text(foldercount+1)
                                if(data.info.folderabbrv == null || data.info.folderabbrv == '')
                                {
                                    var abbrv = " ";
                                }else{
                                    var abbrv = data.info.folderabbrv;
                                }
                                var classbg = '';
                                var stylebg = ''
                                if(data.info.color == null)
                                {
                                    classbg = 'bg-info';
                                }else{
                                    stylebg = 'style="background-color: '+data.info.color+'"';
                                }
                                $('#folder-container').prepend(
                                    '<div class="col-md-6 col-sm-6 col-12 each-folder" data-string="'+data.info.foldername+' '+abbrv+'<">'+
                                        '<a href="/mydocs/filesindex?folderid='+data.info.id+'&extends={{$extends}}" style="color: inherit;">'+
                                            '<div class="info-box shadow-sm">'+
                                                '<span class="info-box-icon" '+stylebg+'><i class="far fa-folder"></i></span>'+
                                                '<div class="info-box-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">'+
                                                    '<span class="info-box-text">'+data.info.foldername+'</span>'+
                                                    '<span class="info-box-number">'+abbrv+'</span>'+
                                                '</div>'+
                                            '</div>'+
                                        '</a>'+
                                    '</div>'
                                )
                                $('.btn-close-modal').click()
                                $('body').removeClass('modal-open')

                            }else if(data.status == 0){
                                toastr.warning('Folder name already exists!')
                            }else{
                                toastr.danger('Something went wrong!')
                            }
                        }
                    })

                }

            })
            $('.each-shared-folder').on('click',function(){
                $('#modal-show-sharedfolder').modal('show');
                $('#modal-show-sharedfolder-title').text($(this).attr('data-title'))
                var folderid = $(this).attr('data-id');
                $.ajax({
                    url: '/mydocs/sharedfoldergetfiles',
                    type: 'GET',
                    data: {
                        folderid    : folderid,
                    },
                    success:function(data){
                        $('#modal-show-sharedfolder-container').empty()
                        $('#modal-show-sharedfolder-container').append(data)
                    }
                })
            })
        })
    </script>
@endsection