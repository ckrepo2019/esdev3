@extends($extends)


@section('content')
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
<!-- Bootstrap Color Picker -->
<link rel="stylesheet" href="{{asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3>
                        School Files
                    </h3>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item">School Files</li>
                </ol>
                </div>
            </div>
        </div>
    </section>
  
<section class="content-body m-0 p-0">
    
    <div class="container-fluid m-0">
    <!-- Small boxes (Stat box) -->
    <div class="row">
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
        <div class="col-md-4 text-right">
            
            @if(count($authorizedusers)>0)
                @if(count(collect($authorizedusers)->where('userid', auth()->user()->id)) > 0)
                    <label>&nbsp;</label>
                    <br/>
                    <button type="button" class="btn btn-primary" id="btn-add-folder"><i class="fa fa-plus"></i> Folder</button>
                @endif
            @endif
        </div>
    </div>
    <div class="row">
        @if(count($folders)>0)

            @foreach ($folders as $folder)
                @if(count(collect($folder->usertypes)->where('usertypeid', auth()->user()->type)) > 0)
                    <div class="col-md-4 eachdepartment"  data-string="{{$folder->foldername}}<">
                    <!-- small box -->
                        <a href="/administrator/folderview?folderid={{$folder->id}}" class="small-box-footer" style="color: inherit;" data-toggle="tooltip" data-placement="bottom" title="{{$folder->foldername}}">
                            <div class="info-box shadow">
                                {{-- <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span> --}}
                
                                <div class="info-box-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span class="info-box-text">{{$folder->foldername}}</span>
                                {{-- <span class="info-box-number">Regular</span> --}}
                                </div>
                                <!-- /.info-box-content -->
                            </div>
                        </a>
                        {{-- @if($folder->color == null)
                        <div class="small-box bg-info">
                        @else
                        <div class="small-box" style="background-color: {{$folder->color}}">
                        @endif
                                <div class="inner">
                                    <h4>{{$folder->foldername}}</h4>
                                    <p></p>
                                </div>
                            <div class="icon">
                                <i class="fa fa-folder-open"></i>
                            </div>
                            <a href="/administrator/folderview?folderid={{$folder->id}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div> --}}
                    </div>
                @endif
            @endforeach
        @endif
      <!-- ./col -->
    </div>
    <!-- /.row -->
  </div>
</section>
<div class="modal fade" id="modal-add-folder" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" >New Folder</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" >
                <div class="row">
                    <div class="col-6" >
                        <label>Visible to:</label><br/>
                        <div class="row">
                            <div class="col-12" style="height: 400px; overflow: scroll;">
                                <div class="form-group clearfix">
                                    @foreach($usertypes as $usertype)
                                        <div class="icheck-primary">
                                        <input type="checkbox" class="usertypes" id="checkboxPrimary{{$usertype->id}}" checked="" value="{{$usertype->id}}">
                                        <label for="checkboxPrimary{{$usertype->id}}">
                                            {{$usertype->utype}}
                                        </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <label>File Types:</label><br/>
                        <div class="form-group clearfix">
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryIMAGE" checked="" value="image">
                                <label for="checkboxPrimaryIMAGE">
                                    IMAGE
                                </label>
                            </div>
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryVIDEO" checked="" value="video">
                                <label for="checkboxPrimaryVIDEO">
                                    VIDEO
                                </label>
                            </div>
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryWORD" checked="" value="word">
                                <label for="checkboxPrimaryWORD">
                                    WORD
                                </label>
                            </div>
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryEXCEL" checked="" value="excel">
                                <label for="checkboxPrimaryEXCEL">
                                    EXCEL
                                </label>
                            </div>
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryPDF" checked="" value="pdf">
                                <label for="checkboxPrimaryPDF">
                                    PDF
                                </label>
                            </div>
                            <div class="icheck-primary">
                                <input type="checkbox" class="filetypes" id="checkboxPrimaryPPT" checked="" value="ppt">
                                <label for="checkboxPrimaryPPT">
                                    PPT
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label>Folder Name</label>
                        <input type="text" class="form-control" placeholder="Folder name" id="input-new-folder" required/>
                    </div>
                    <div class="col-12 mt-2">
                        <label>Color</label>
                        <input type="text" id="input-new-color" class="form-control my-colorpicker1 colorpicker-element" data-colorpicker-id="1" data-original-title="" title="">
                    </div>
                </div>
                <div class="row" id="validations">
                    <div class="col-12" id="modal-input-usertype-empty">
                        <label class="text-danger">Please select audience</label>
                    </div>
                    <div class="col-12" id="modal-input-filetype-empty">
                        <label class="text-danger">Please select filetype</label>
                    </div>
                    <div class="col-12" id="modal-input-foldername-empty">
                        <label class="text-danger">Please enter folder name</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="modal-btn-add-close">Close</button>
                <button type="button" class="btn btn-primary" id="modal-btn-add-submit">Create</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- bootstrap color picker -->
  <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
  <script src="{{asset('plugins/pace-progress/pace.min.js') }}"></script>
  <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
  <!-- dropzonejs -->
  <script src="{{asset('plugins/dropzone/min/dropzone.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
  <!-- bootstrap color picker -->
  <script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
  <!-- Select2 -->
  <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')}}"></script>
<script>
        $('.my-colorpicker1').colorpicker()
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip()
        $("#input-filter").on("keyup", function() {
            var input = $(this).val().toUpperCase();
            var visibleCards = 0;
            var hiddenCards = 0;

            $(".container").append($("<div class='card-group card-group-filter'></div>"));


            $(".eachdepartment").each(function() {
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
        $('#validations').hide();
        $('#modal-input-usertype-empty').hide();
        $('#modal-input-filetype-empty').hide();
        $('#modal-input-foldername-empty').hide();

        $('#btn-add-folder').on('click', function(){
            $('#modal-add-folder').modal('show')
        })

        $('#modal-btn-add-submit').on('click', function(){
            var invalidvalues = 0;
            if($('#input-new-folder').val().replace(/^\s+|\s+$/g, "").length == 0){
                $('#validations').show();
                $('#modal-input-foldername-empty').show();
                invalidvalues+=1;
            }
            if($('.usertypes:checked').length == 0)
            {
                $('#validations').show();
                $('#modal-input-usertype-empty').show();
                invalidvalues+=1;
            }
            if($('.filetypes:checked').length == 0)
            {
                $('#validations').show();
                $('#modal-input-filetype-empty').show();
                invalidvalues+=1;
            }

            if(invalidvalues == 0)
            {
                var foldername = $('#input-new-folder').val();

                var colorpicked = $('#input-new-color').val();

                var usertypes = [];

                $('.usertypes:checked').each(function(){
                    usertypes.push($(this).val())
                })

                var filetypes = [];

                $('.filetypes:checked').each(function(){
                    filetypes.push($(this).val())
                })

                $.ajax({
                    url: '/administrator/addfolder',
                    type: 'GET',
                    dataType: "json",
                    data: {
                        foldername  : foldername,
                        colorpicked : colorpicked,
                        usertypes   : usertypes,
                        filetypes   : filetypes
                    },
                    complete:function(data)
                    {
                        window.location.reload()
                    }
                })
            }
        })
    })
</script>
@endsection