@extends('studentPortal.layouts.app2')

@section('content')
  <section class="content pt-3">
      <div class="container-fluid">
          <div class="row">
              <div class="col-md-12">
                  <div class="card card-primary card-outline">
                    <div class="card-header">
                      <h3 class="card-title">Read Notification</h3>
                      <div class="card-tools">
                      </div>
                    </div>
                    <!-- /.card-header -->
                  
                    <div class="card-body p-0">
                      <div class="mailbox-read-info">
                        <h5>Title: {{$content[0]->title}}</h5>
                        <h6>From: {{$content[0]->name}}
                        <span class="mailbox-read-time float-right">{{\Carbon\Carbon::create($content[0]->created_at)->isoFormat('MMM DD, YYYY HH:MM a')}}</span></h6>
                      </div>

                        <div class="mailbox-read-message"  id="content">
                         
                        </div>
                          
                     
                    </div>
                  </div>
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