@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
{{-- {{$announcements}} --}}
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="/mailbox/compose/{{Crypt::encrypt(auth()->user()->id)}}" class="btn btn-primary btn-block mb-3 text-white">Compose</a>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Folders</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
            <div class="card-body p-0">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                    <a href="/mailbox/inbox/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link">
                    <i class="fas fa-inbox"></i> Inbox
                    {{-- <span class="badge bg-primary float-right">12</span> --}}
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/mailbox/sent/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link">
                    <i class="far fa-envelope"></i> Sent
                  </a>
                </li>
                {{-- <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-file-alt"></i> Drafts
                  </a>
                </li> --}}
                {{-- <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-filter"></i> Junk
                    <span class="badge bg-warning float-right">65</span>
                  </a>
                </li> --}}
                {{-- <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="far fa-trash-alt"></i> Trash
                  </a>
                </li> --}}
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <form action="/mailbox/compose/send" method="GET">
                    <div class="card-header">
                        <h3 class="card-title">Compose New Message</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group">
                            <div class="btn-group">
                                <button type="button" name="recievertype" class="btn btn-info">Principal</button>
                                <button type="button" name="recievertype" class="btn btn-info">Teacher</button>
                                <button type="button" name="recievertype" class="btn btn-info">Section</button>
                                <button type="button" name="recievertype" class="btn btn-info">Student</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="userid" value="{{auth()->user()->id}}" class="form-control">
                            <input type="hidden" name="receivertype" value="" class="form-control">
                            <div class="select2-purple">
                            <select id="select2" class="form-control select2 m-0 receivers" multiple="multiple" data-placeholder="To:" data-dropdown-css-class="select2-purple" name="recipients[]" required>
                            
                            </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <input class="form-control" name="title"  placeholder="Subject:" required>
                        </div>
                        <div class="form-group">
                            <textarea id="compose-textarea" class="form-control" name="content" style="height: 300px" required>
                            </textarea>
                        </div>
                    </div>
              <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="float-right">
                            <button type="button" class="btn btn-default"><i class="fas fa-pencil-alt"></i> Draft</button>
                            <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                        </div>
                        <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
                    </div>
              <!-- /.card-footer -->
                </form>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
      <!-- /.row -->
</section>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<script>
    $('button[name=recievertype]').on('click', function() {
        $(this).siblings().removeClass('active')
        $(this).addClass('active');
        var receivertype = $(this).text();
        $('input[name=receivertype]').val(receivertype)
        var userid = $('input[name=userid]').val();
        // console.log(receivertype)
        $.ajax({
                url: '/mailbox/compose/getreceiver',
                type:"GET",
                dataType:"json",
                data:{
                receivertype: receivertype,
                userid: userid
            },
            success:function(data) {
                if(data[0]=="Principal"){
                    $('.receivers').empty();
                    $.each(data[1], function(key, value){
                        $('.receivers').append(
                            '<option value="'+value.userid+'">'+value.utype+' - '+value.firstname+' '+value.middlename+' '+value.lastname+' '+value.suffix+'</option>'
                        );
                    })
                }
                else if(data[0]=="Teacher"){
                    $('.receivers').empty();
                    $.each(data[1], function(key, value){
                        $('.receivers').append(
                            '<option value="'+value.userid+'">'+value.utype+' - '+value.firstname+' '+value.middlename+' '+value.lastname+' '+value.suffix+'</option>'
                        );
                    })
                }
                else if(data[0]=="Section"){
                    $('.receivers').empty();
                    $.each(data[1], function(key, value){
                        $('.receivers').append(
                            '<option value="'+value.sectionid+'">'+value.levelname+' - '+value.sectionname+'</option>'
                        );
                    })
                }
                else if(data[0]=="Student"){
                    $('.receivers').empty();
                    $.each(data[1], function(key, value){
                        $('.receivers').append(
                            '<option value="'+value.studid+'">STUDENT - '+value.firstname+' '+value.middlename+' '+value.lastname+' '+value.suffix+'</option>'
                        );
                    })
                }
            }
        })
    });
    $(function () {
        //Enable check and uncheck all functionality
        $('.checkbox-toggle').click(function () {
        var clicks = $(this).data('clicks')
        if (clicks) {
            //Uncheck all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
            $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        } else {
            //Check all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
            $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')
        }
        $(this).data('clicks', !clicks)
        })

        //Handle starring for glyphicon and font awesome
    })
</script>


<script>
    $(function () {
                $('.select2').select2();
        // Summernote
        // $('#compose-textarea').summernote();
        $('#compose-textarea').summernote({
            height: 300,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'help' ] ]
            ]
        })
    })
        
</script>
@endsection
