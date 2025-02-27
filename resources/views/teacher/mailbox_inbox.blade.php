@extends('teacher.layouts.app')

@section('content')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <section class="content">
        @if(session()->has('deleted'))
<div class="alert alert-success alert-dismissible col-12">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h5><i class="icon fas fa-check"></i> Alert!</h5>
    {{ session()->get('deleted') }}
</div>
@endif
        <div class="row">
            <div class="col-md-3">
                <a href="/mailbox/compose/{{Crypt::encrypt(auth()->user()->id)}}" class="btn btn-primary btn-block mb-3 text-white">Compose</a>
                <div class="card">
                    <div class="card-header bg-info">
                        <h3 class="card-title">Folders</h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item active bg-secondary">
                                <a href="/mailbox/inbox/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link">
                                <i class="fas fa-inbox"></i> Inbox
                            </a>
                            </li>
                            <li class="nav-item">
                            <a href="/mailbox/sent/{{Crypt::encrypt(auth()->user()->id)}}" class="nav-link">
                                <i class="far fa-envelope"></i> Sent
                            </a>
                            </li>
                            <li class="nav-item">
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
            <div class="card-header bg-success">
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
                    <div class="card-header bg-info">
                        <h3 class="card-title">Inbox</h3>
              <!-- /.card-tools -->
                    </div>
            <!-- /.card-header -->
                    <div class="card-body p-0" style="overflow: scroll;">
                        <div class="table-responsive mailbox-messages p-2" >
                            <table id="example2" class="table table-hover table-striped mt-2" style="width: 100%;">
                                <thead  class="bg-warning">
                                    <tr>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $unique = 0;
                                    @endphp
                                    @foreach ($announcements as $inbox)
                                        <tr>
                                            <td class="mailbox-star">

                                                    @if($inbox->status == 1)
                                                        <i class="fas fa-star text-secondary"></i>
                                                    @elseif($inbox->status == 0)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @endif
                                            </td>
                                            <td class="mailbox-name">
                                                @if($inbox->status == 0)
                                                    <form name="read" action="/mailbox/read/{{Crypt::encrypt(auth()->user()->id)}}" method="get">
                                                        <input type="hidden" name="message_id" value="{{$inbox->id}}"/>
                                                        <input type="hidden" name="message_notificationid" value="{{$inbox->notificationid}}"/>
                                                        <input type="hidden" name="message_date" value="{{$inbox->created_at}}"/>
                                                        <input type="hidden" name="message" value="deleteinbox"/>
                                                        <strong><a type="button" class="submitBtn{{$unique}}"> {{$inbox->firstname}} {{$inbox->middlename}} {{$inbox->lastname}} {{$inbox->suffix}}</a></strong>
                                                    </form>
                                                @else
                                                    <form name="read" action="/mailbox/read/{{Crypt::encrypt(auth()->user()->id)}}" method="get">
                                                        <input type="hidden" name="message_id" value="{{$inbox->id}}"/>
                                                        <input type="hidden" name="message_notificationid" value="{{$inbox->notificationid}}"/>
                                                        <input type="hidden" name="message_date" value="{{$inbox->created_at}}"/>
                                                        <input type="hidden" name="message" value="deleteinbox"/>
                                                        <a type="button" class="submitBtn{{$unique}}"> {{$inbox->firstname}} {{$inbox->middlename}} {{$inbox->lastname}} {{$inbox->suffix}}</a>
                                                    </form>
                                                @endif
                                            </td>
                                            <td class="mailbox-subject"><b>{{$inbox->title}}</b></td>
                                            <td class="mailbox-date">
                                                @if($inbox->status == 1)
                                                    <small>{{$inbox->created_at}}</small>
                                                @else
                                                    <strong><small>{{$inbox->created_at}}</small></strong>
                                                @endif
                                            </td>
                                        </tr>
                                        @php
                                            $unique+=1;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                    <!-- /.table -->
                        </div>
              <!-- /.mail-box-messages -->
                    </div>
                <!-- /.card-body -->
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
    })
    $('a').on('click',function(){
        $(this).closest('form').submit()
        // $('form[name=read]').submit();
    })
    $(document).ready(function () {
        
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
