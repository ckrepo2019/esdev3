@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
</style>
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
              <div class="card-header">
                <h3 class="card-title">Read</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="mailbox-read-info">
                    <h5>{{$message->title}}</h5>
                  <h6>From: {{$message->firstname}} {{$message->middlename}} {{$message->lastname}} {{$message->suffix}}
                    <span class="mailbox-read-time float-right">{{$message->created_at}}</span></h6>
                </div>
                <!-- /.mailbox-read-info -->
                <div class="mailbox-controls with-border text-center">
                  <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Delete">
                      <i class="far fa-trash-alt"></i></button>
                    <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Reply">
                      <i class="fas fa-reply"></i></button>
                    {{-- <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" data-container="body" title="Forward"> --}}
                      {{-- <i class="fas fa-share"></i></button> --}}
                  </div>
                  <!-- /.btn-group -->
                  <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" title="Print">
                    <i class="fas fa-print"></i></button>
                </div>
                <!-- /.mailbox-controls -->
                <div class="mailbox-read-message">
                    <textarea id="read-textarea" class="read-textarea" placeholder="Place some text here"
style="width: 100%; font-size: 14px; line-height: 18px; border: 1px solid white; ">{{$message->content}}</textarea>
                </div>
                <!-- /.mailbox-read-message -->
              </div>
              <!-- /.card-body -->
              <!-- /.card-footer -->
              <div class="card-footer">
                <div class="float-right">
                  <button type="button" class="btn btn-default"><i class="fas fa-reply"></i> Reply</button>
                  {{-- <button type="button" class="btn btn-default"><i class="fas fa-share"></i> Forward</button> --}}
                </div>
                <button type="button" class="btn btn-default"><i class="far fa-trash-alt"></i> Delete</button>
                <button type="button" class="btn btn-default"><i class="fas fa-print"></i> Print</button>
              </div>
              <!-- /.card-footer -->
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
    $(function () {
        $('.select2').select2();
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
    $('#read-textarea').attr('contenteditable','false');
        $('.read-textarea').summernote({
            height: 300,
            toolbar: []
        });
        $('.read-textarea').summernote('disable');
        // $('.read-textarea').css('background-color','white','!important');
        $('.note-editable').css('backgroundColor','white');
                $('.note-editor').removeClass('card');
        $('.note-editor').css('border','hidden');
        // $('.note-editable').css('border','1px solid white');

    })
        
</script>
@endsection
