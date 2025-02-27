@extends('studentPortal.layouts.app2')


@section('content')
{{-- 
    <div class="row">
        <div class="col-md-12">

        </div>
    </div> --}}
    <div class="card">
        <div class="card-header bg-primary">
            <h4>School Requirements</h4>
        </div>
        <div class="card-body">
            <div class="row">
              <div class="col-5 col-sm-4">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                    @if(count($requirements)>0)
                        @foreach($requirements as $key => $requirement)
                            @if($key == 0)
                            <a class="nav-link active" id="vert-tabs-{{$requirement->id}}-tab" data-toggle="pill" href="#vert-tabs-{{$requirement->id}}" role="tab" aria-controls="vert-tabs-{{$requirement->id}}" aria-selected="true">{{$requirement->description}}</a>
                            @else
                            <a class="nav-link" id="vert-tabs-{{$requirement->id}}-tab" data-toggle="pill" href="#vert-tabs-{{$requirement->id}}" role="tab" aria-controls="vert-tabs-{{$requirement->id}}" aria-selected="false">{{$requirement->description}}</a>
                            @endif
                        @endforeach
                    @endif
                  {{-- <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Messages</a>
                  <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Settings</a> --}}
                </div>
              </div>
              <div class="col-6 col-sm-8">
                <div class="tab-content" id="vert-tabs-tabContent">
                    
                    @foreach($requirements as $key => $requirement)
                        {{-- @if($key == 0) --}}
                            <div class="tab-pane text-left fade @if($key == 0) show active @endif" id="vert-tabs-{{$requirement->id}}" role="tabpanel" aria-labelledby="vert-tabs-{{$requirement->id}}-tab">
                                @if($requirement->picurl == null)
                                    <form action="/student/studentrequirementsuploadphoto" method="post" name="submitfiles"  enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12 mb-2 text-right">
                                                <small class="font-weight-bold">UPLOAD {{$requirement->description}}</small>
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                    <input type="hidden" name="studid" value="{{$studentinfo->id}}"/>
                                                    <input type="hidden" name="reqid" value="{{$requirement->id}}"/>
                                                    <input type="hidden" name="queuecoderef" value="{{$queuecoderef}}"/>
                                                    <input type="file" class="form-control"  accept="image/*" name="input-photo" required/>
                                            </div>
                                            <div class="col-md-12 text-right">
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </div>
                                    </form>
                                @else
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <img src="{{asset($requirement->picurl)}}" onerror="this.onerror = null, this.src='{{asset('assets/images/error-404-page-file-found.jpg')}}'" style="border-radius: unset !important; width:100%;" alt="User Image"/>
                                        </div>
                                        @if($requirement->status == 0)
                                        <div class="col-md-12 text-right">
                                            <button type="button" class="btn btn-danger delete-subreq" data-id="{{$requirement->submittedreqid}}">Delete</button>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                                    {{-- </div>
                                </div> --}}
                            </div>
                        {{-- @else
                            <div class="tab-pane fade" id="vert-tabs-{{$requirement->id}}" role="tabpanel" aria-labelledby="vert-tabs-{{$requirement->id}}-tab">
                            Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                            </div>
                        @endif --}}
                    @endforeach
                </div>
              </div>
            </div>
        </div>
    </div>
@endsection

@section('footerscript')
<script>
    
    $(document).on('click','.delete-subreq', function(){
                var id = $(this).attr('data-id');
                var thisbtn = $(this);
                Swal.fire({
                    title: 'Are you sure you want to delete this submitted requirement?',
                    html: 'You won\'t be able to revert this!<br/>Would you like to continue?',
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Continue'
                })
                .then((result) => {
                    if (result.value) {
                        thisbtn.prop('disabled', true)
                        $.ajax({
                            url: '/student/studentrequirementsdeletephoto',
                            type:'GET',
                            dataType: 'json',
                            data: {
                                id      :  id
                            },
                            success:function(data) {
                                    window.location.reload()
                            }
                        })
                    }
                })
            })
</script>
@endsection
