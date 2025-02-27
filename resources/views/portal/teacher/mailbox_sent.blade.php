@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
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
              <h3 class="card-title">Sent</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                <!-- /.float-right -->
              </div>
              <div class="table-responsive mailbox-messages p-2" >
                <table id="example2" class="table table-hover table-striped mt-2" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            {{-- <th>&nbsp;</th> --}}
                        </tr>
                    </thead>
                  <tbody>
                      @foreach ($messages as $inbox)
                      <tr>
                        <td>
                          <div class="icheck-primary">
                            <input type="checkbox" value="" id="check1{{$inbox['message']->id}}">
                            <label for="check1{{$inbox['message']->id}}"></label>
                          </div>
                        </td>
                        <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                        {{-- <td class="mailbox-name">
                            
                        </td> --}}
                        <td class="mailbox-subject" style=" white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><b>{{$inbox['message']->title}}</b>
                            <br>
                            <a>
                                @foreach ($inbox['recipients'] as $recipient)
                                    <small>
                                        {{$recipient['role']}} {{$recipient['firstname']}}  {{$recipient['middlename']}} {{$recipient['lastname']}} {{$recipient['suffix']}} /
                                    </small>
                                @endforeach
                            </a>
                        </td>
                        <td class="mailbox-date">{{$inbox['message']->created_at}}</td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer p-0">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="far fa-trash-alt"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fas fa-sync-alt"></i></button>
                <!-- /.float-right -->
              </div>
            </div>
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
<script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
<script>
  $(function () {
        $("#example2").DataTable({
            pageLength : 10,
            lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'Show All']]
        });
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

@endsection
