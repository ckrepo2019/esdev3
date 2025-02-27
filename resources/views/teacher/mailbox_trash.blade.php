@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
{{-- {{$announcements}} --}}

@if(session()->has('recycled'))
    <div class="alert alert-success alert-dismissible col-12">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('recycled') }}
    </div>
@endif
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
                <li class="nav-item">
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
                <li class="nav-item active bg-secondary">
                  <a href="/mailbox/trash/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link">
                    <i class="far fa-trash-alt"></i> Trash
                  </a>
                </li>
              </ul>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          <div class="card">
            <div class="card-header">
                <h3 class="card-title">Labels</h3>
              </div>
              <div class="card-body p-0">
                <ul class="nav nav-pills flex-column">
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-star text-warning"></i>
                        Unread
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-star text-secondary"></i> Read
                    </a>
                  </li>
                </ul>
              </div>
              <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Trash</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0" style="overflow: scroll">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
                </button>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-sm recyclebtn"><i class="fas fa-sync-alt"></i></button>
                </div>
              </div>
              <div class="table-responsive mailbox-messages p-2" >
                <table id="example2" class="table table-hover table-striped mt-2" style="width: 100%;table-layout:fixed;">
                    <thead>
                        <tr>
                            <th style="width: 10%;">&nbsp;</th>
                            <th style="width: 10%;">&nbsp;</th>
                            <th style="width: 50%;">&nbsp;</th>
                            <th style="width: 30%;">&nbsp;</th>
                            {{-- <th>&nbsp;</th> --}}
                        </tr>
                    </thead>
                  <tbody>
                      @foreach ($messages as $inbox)
                      <tr>
                        <td>
                          <div class="icheck-primary">
                            <input type="checkbox" name="messageid" value="{{$inbox['message']->id}}" id="check1{{$inbox['message']->id}}">
                            <label for="check1{{$inbox['message']->id}}"></label>
                          </div>
                        </td>
                        <td class="mailbox-star"><a href="#"><i class="fas fa-star text-warning"></i></a></td>
                        {{-- <td class="mailbox-name">
                            
                        </td> --}}
                        <td class="mailbox-subject" style=" white-space: nowrap !important;overflow: hidden !important;text-overflow: ellipsis !important;"><b>{{$inbox['message']->title}}</b>
                            <br>
                            <a  style=" white-space: nowrap !important;overflow: hidden !important;text-overflow: ellipsis !important;">
                                @foreach ($inbox['recipients'] as $recipient)
                                    <small>
                                        {{$recipient['role']}} {{$recipient['firstname']}}  {{$recipient['middlename']}} {{$recipient['lastname']}} {{$recipient['suffix']}} /
                                    </small>
                                @endforeach
                            </a>
                        </td>
                        <td class="mailbox-date"><small>{{$inbox['message']->created_at}}</small></td>
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
                    <button type="button" class="btn btn-default btn-sm recyclebtn"><i class="fas fa-sync-alt"></i></button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="/mailbox/trash/recycle" method="get">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Recycle</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
              </div>
            <div class="modal-body" id="messagecontainer">
                Are you sure you want to recycle selected message/s?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Recycle</button>
            </div>
        </div>
        </form>
    </div>
</div>
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
                "order": [[ 3, "desc" ]],
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
    $(document).ready(function () {
        
        $(".recyclebtn").click(function(){
            
            if ($("input[name='messageid']:checked").length == 0) {
            //do something
            }else{
                $.each($("input[name='messageid']:checked"), function(){
                    // favorite.push($(this).val());
                    $('#messagecontainer').append(
                        '<input type="hidden" name="messageids[]" value="'+$(this).val()+'"/>'
                        );
                });
                    // $('input[name=messageids]').val(favorite);
                $('#myModal').modal('show');
            }
            // var favorite = [];
        });
        window.setTimeout(function () {
            $(".alert-success").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
        window.setTimeout(function () {
            $(".alert-danger").fadeTo(500, 0).slideUp(500, function () {
                $(this).remove();
            });
        }, 5000);
    });
</script>

@endsection
