@extends('parentsportal.layouts.app2')

@section('content')
<section class="content pt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success card-outline">
                  <div class="card-header bg-success">
                    <h3 class="card-title">Read Notification</h3>
                    <div class="card-tools">
                    </div>
                  </div>
                  <!-- /.card-header -->
                
                  <div class="card-body p-0">
                    <div class="mailbox-read-info">
                      <h5>{{$content[0]->title}}</h5>
                      <h6>From: {{$content[0]->name}}
                      <span class="mailbox-read-time float-right">{{\Carbon\Carbon::create($content[0]->created_at)->isoFormat('MMMM DD, YYYY hh:mm a')}}</span></h6>
                    </div>
                    <!-- /.mailbox-read-info -->
                    
                    <!-- /.mailbox-controls -->
                    <div class="mailbox-read-message" id="content">
                        <p>{{$content[0]->content}}</p>
                    </div>
                    <!-- /.mailbox-read-message -->
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
        </div>
    </div>
</section>

<script>
  $(document).ready(function(){

    console.log(  $('#content')[0].innerHTML = '{!! $content[0]->content !!}')

  })
</script>

@endsection