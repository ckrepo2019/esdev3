
@extends('ctportal.layouts.app2')

@section('pagespecificscripts')

@endsection

@section('content')

      <section class="content">
        <div class="content-header">
            <div class="container-fluid">
              <div class="row mb-2">
                <div class="col-sm-12">
                  <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item"><a href="/college/teacher/vc/index">Virtual Classrooms</a></li>
                    <li class="breadcrumb-item active">{{$classroom->classroomname}}</li>
                  </ol>
                </div><!-- /.col -->
              </div><!-- /.row -->
            </div><!-- /.container-fluid -->
          </div>
          
        <div class="row">
            <div class="col-12 col-sm-12">
              <div class="card card-primary card-tabs">
                <div class="card-header p-0 pt-1">
                  <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    {{-- <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-one-files-tab" data-toggle="pill" href="#custom-tabs-one-files" role="tab" aria-controls="custom-tabs-one-files" aria-selected="true">Files</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-one-assignments-tab" data-toggle="pill" href="#custom-tabs-one-assignments" role="tab" aria-controls="custom-tabs-one-assignments" aria-selected="false">Assignment</a>
                    </li> --}}
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-one-students-tab" data-toggle="pill" href="#custom-tabs-one-students" role="tab" aria-controls="custom-tabs-one-students" aria-selected="true">Students</a>
                    </li>
                    <li class="nav-item">
                      @if(DB::table('schoolinfo')->first()->withVC == 1)
                      <a class="nav-link btn" id="call" role="tab" href="#">
                          <i class="fa fa-video"></i> Start a Call 
                      </a>
                      @elseif(DB::table('schoolinfo')->first()->withVC == 2)
                      <a class="nav-link btn" id="msteams" role="tab" href="#">
                          <i class="fa fa-video"></i> OPEN MSTeams
                      </a>
                      @endif
                      {{-- <a class="nav-link btn" id="call" role="tab" href="#">
                          <i class="fa fa-video"></i> Start a Call 
                      </a> --}}
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-one-tabContent">
                    {{-- <div class="tab-pane fade show active" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur. 
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                       Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam. 
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                       Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. 
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                       Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis. 
                    </div> --}}
                  </div>
                </div>
                <!-- /.card -->
              </div>
            </div>
        </div>
      </section>
      <script>
          $(document).ready(function(){
              
            $.ajax({
                    url: '/college/teacher/vc/getstudents',
                    type: 'GET',
                    datatype: 'json',
                    data: {
                        classroomid: '{{$classroom->id}}'
                    },
                    success: function(data){
                        console.log(data)
                        $('#custom-tabs-one-tabContent').empty()
                        $('#custom-tabs-one-tabContent').append(data)
                    }
              })
          })
          $(document).on('click','#custom-tabs-one-students-tab', function(){
              $.ajax({
                    url: '/college/teacher/vc/getstudents',
                    type: 'GET',
                    datatype: 'json',
                    data: {
                        classroomid: '{{$classroom->id}}'
                    },
                    success: function(data){
                        console.log(data)
                        $('#custom-tabs-one-tabContent').empty()
                        $('#custom-tabs-one-tabContent').append(data)
                    }
              })
          })
          $(document).on('click','#custom-tabs-one-assignments-tab', function(){
              $.ajax({
                    url: '/college/teacher/vc/getassignments',
                    type: 'GET',
                    datatype: 'json',
                    data: {
                        classroomid: '{{$classroom->id}}'
                    },
                    success: function(data){
                        console.log(data)
                        $('#custom-tabs-one-tabContent').empty()
                        $('#custom-tabs-one-tabContent').append(data)
                    }
              })
          })
          
        $(document).on('click', '#call',function(){
            var codex ='{{$classroom->id}}';
            window.open('/college/teacher/vc/call'+'/'+codex+'','newwindow','width=700,height=700,top=0, left=960');
        })
        $(document).on('click', '#msteams',function(){
            window.open('https://teams.microsoft.com/_?culture=en-us&country=US&lm=deeplink&lmsrc=homePageWeb&cmpid=WebSignIn','width=700,height=700,top=0, left=960');
        })
      </script>
@endsection

@section('footerscript')

@endsection

