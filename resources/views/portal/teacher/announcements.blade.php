@extends('teacher.layouts.app')

@section('content')
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-microphone"></i>
            ANNOUNCEMENT
          </h3>
        </div>
        <div class="card-body">
            @if((string)Session::get('messageSuccess') == true)
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-check"></i> Alert!</h5>
                    {{ (string)Session::get('messageSuccess') }}
                </div>
            @endif
            @if((string)Session::get('messageWarning') == true)
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alert!</h5>
                    {{ (string)Session::get('messageWarning') }}
                </div>
            @endif
            <div class="alert alert-info alert-dismissible" id="alertNoSubjects" >
                {{-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> --}}
                <h5><i class="icon fas fa-info"></i> Alert!</h5>
                You are not yet assigned to any subjects. 
              </div>
            <div class="row" id="announcementPanel">
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active logs" id="vert-tabs-logs-tab" data-toggle="pill" href="#vert-tabs-logs" role="tab" aria-controls="vert-tabs-logs" aria-selected="true">Logs</a>
                        <a class="nav-link compose" id="vert-tabs-compose-tab" data-toggle="pill" href="#vert-tabs-compose" role="tab" aria-controls="vert-tabs-compose" aria-selected="false">Compose</a>
                    </div>
                </div>
                <div class="col-7 col-sm-9" >
                    <div class="tab-content" id="vert-tabs-tabContent">
                        <div class="tab-pane fade active show" id="vert-tabs-logs" role="tabpanel" aria-labelledby="vert-tabs-logs-tab" style="height: 500px;
                        overflow-y: scroll">
                            <div id="accordion">
                                <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                                @foreach ($logs as $log)
                                    <div class="card-primary" style="border: 1px solid #ddd;">
                                        <a data-toggle="collapse" data-parent="#accordion" class="logname" id="{{$log->id}}" href="#collapse{{$log->id}}">
                                            <div class="card-header">
                                                    {{$log->title}}
                                                    <span class="float-right">
                                                        @php
                                                        // use \Carbon\Carbon;
                                                        $datetime = \Carbon\Carbon::create($log->created_at)->isoFormat('dddd, MMMM D, YYYY - h:m:s A')  
                                                        @endphp
                                                        {{$datetime}}
                                                    </span>
                                            </div>
                                        </a>
                                        <div id="collapse{{$log->id}}" class="panel-collapse collapse in">
                                                <textarea class="textareaLogs" placeholder="Place some text here"
                          style="width: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" disabled>{{$log->content}}</textarea>
                                                
                                            <div class="card-footer">
                                                @foreach ($log->sections as $section)
                                                    <span class="right badge badge-info" style="display:inline;position:relative">
                                                        {{$section}}
                                                    </span> &nbsp;
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane fade" id="vert-tabs-compose" role="tabpanel" aria-labelledby="vert-tabs-compose-tab">
                            <form action="/post_announcements" method="GET">
                                <input type="hidden" name="teacherid" class="form-control" value="{{$teacher_info[0]->id}}"/>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Title" required/>
                                &nbsp;
                                <textarea class="textarea" placeholder="Place some text here"
                          style="width: 100%; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"  d="announcement_content" name="announcement_content" rows="5" required></textarea>
                                {{-- <textarea class="form-control" rows="5" id="announcement_content" name="announcement_content" placeholder="Content" required></textarea> --}}
                                &nbsp;
                                <div class="form-group">
                                    <label>Recipients:</label>
                                    <div class="select2-purple">
                                      <select id="select2" class="select2 m-0" multiple="multiple" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" name="recipients[]" required>
                                        {{-- <option value="advisory" >Advisory</option> --}}
                                        @foreach ($assignedSched as $sched)
                                            <option value="{{$sched->sectionname}} - {{$sched->sectionid}}">{{$sched->levelname}} - {{$sched->sectionname}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                  </div>
                                  <button id="btnSubmit" type="submit" class="btn btn-success float-right"><i class="fa fa-paper-plane"></i> Publish </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>

<script>
    $('#alertNoSubjects').hide();
    $(document).ready(function(){       
        if('{{$assignedSched}}'.length == 0) {
            $('#alertNoSubjects').show();
            $('#announcementPanel').hide();
        }
        else{
            $('.logs').on('click', function(){
                $('.note-editable').attr('contenteditable','false');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editor').removeClass('card');
            });
            $('.logname').on('click', function(){
                var logid = $(this).attr('id');
                $('.note-editable').attr('contenteditable','false');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editable').css('backgroundColor','white');
                $('.note-editor').removeClass('card');
            });
            $('.compose').on('click', function(){
                $('.note-editable').attr('contenteditable','true');
            });
            $(function () {
                $('.select2').select2();
                // Summernote
                
                $('.textareaLogs').summernote({
                    height: 200,
                    toolbar: []
                })
                $('.textarea').summernote({
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
            $('#alertNoSubjects').hide();
            $('#announcementPanel').show();
        }
    })
        
</script>
@endsection
