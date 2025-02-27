@extends('parentsportal.layouts.app2')

@include('generalPages.viewAllNotifications')

{{-- @section('pagespecificscripts')
    <script>
        let loadAllNotification = new EventSource("/parentloadAllNotification", {withCredentials: true});
        loadAllNotification.onmessage = function (e) {
            let data = JSON.parse(e.data);
            $('.notall').empty();
            $('.notall').append(data);
            
         };
     </script>
@endsection


@section('content')
<section class="content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-header bg-success">
                    <h3 class="card-title">Inbox</h3>

                    <div class="card-tools">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" placeholder="Search Announcement">
                        <div class="input-group-append">
                        <div class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                        <tbody class="notall">
                        @foreach ($notifications as $key=>$item)
                            <tr>
                                @if($item->notificationInfo->type=='1')
                                    <td> 
                                        @if($item->notificationInfo->status=='0')
                                            <i class="fas fa-bookmark text-muted"></i>
                                        @else
                                            <i class="fas fa-bookmark text-info"></i>
                                        @endif

                                        <a href="/viewAnnouncement/{{$item->notificationInfo->id}}">{{$item->notificationContent->name}} posted an announcement</a>
                                      <span class="float-right">{{ \Carbon\Carbon::parse($item->notificationInfo->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW)}}</span>
                                    </td>
                                @endif
                                @if($item->notificationInfo->type=='2')
                                    <td>
                                        @if($item->notificationInfo->status=='0')
                                            <i class="fas fa-bookmark text-muted"></i>
                                        @else
                                            <i class="fas fa-bookmark text-info"></i>
                                        @endif
                                        </i><a href="/grades">{{$item->notificationContent->subjdesc}} Quarter {{$item->notificationContent->quarter}} Grades was posted</a>
                                            <span class="float-right">{{ \Carbon\Carbon::parse($item->notificationInfo->created_at)->diffForHumans(DB::select('select current_timestamp')[0]->current_timestamp,\Carbon\CarbonInterface::DIFF_RELATIVE_TO_NOW)}}</span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection --}}