@extends('principalsportal.layouts.app2')

@section('pagespecificscripts')
<link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    
@endsection

@section('content')
<section class="content-header">
    <div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">Blocks</li>
        </ol>
        </div>
    </div>
    </div>
</section>


<form method="GET" action="/principalpostannouncement">
<div class="row">
    <div class="col-md-4 ">
        <div class="main-card mb-3 card principalannouncement">
            <div class="card-header bg-info">
                <h5 class="card-title">Message Type</h5>
            </div>
            <div class="card-body">
                
                <div class="form-group clearfix mb-0">
                    <div class="icheck-primary d-inline mr-3">
                        <input type="radio" id="radioPrimary1" value="1" name="announcetype" checked>
                        <label for="radioPrimary1">System Message 1</label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" value="2" name="announcetype" >
                        <label for="radioPrimary2">Text Blast 3</label>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="main-card mb-3 card principalcompose">
            <div class="card-header bg-info">
                <h5 class="card-title">Recievers</h5>
            </div>
            <div class="card-body p-0">
                <div class="position-relative form-group clearfix mb-0 p-3  ">
                        <div class="icheck-primary d-inline mr-4">
                            <input type="checkbox" name="all" id="all" {{ old('all')=="on" ? 'checked':'' }}>
                            <label  for="all">All</label>
                        </div>
                        <div class="icheck-primary d-inline mr-4">
                            <input type="checkbox" name="teachers" id="teachers" 
                             {{ old('teachers')=="on" ? 'checked':'' }}
                             >
                            <label class="" for="teachers">Teachers</label>
                        </div>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" name="students" id="students"
                             {{ old('students')=="on" ? 'checked':'' }} 
                            >
                            <label class="" for="students">Students</label>
                        </div>
                  
                
                    <input type="hidden" class="form-control @error('G') is-invalid @enderror" id="none">
                    @error('G')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="dropdown-divider mt-0"></div>
                <h5 class="card-title pl-3">Grade Levels</h5>
                <br>
                

                <div class="position-relative form-group clearfix mb-0 p-3 gradelevelholder">
                        @foreach(App\Models\Principal\LoadSections::gradelevel() as $item)
                            <div class="icheck-primary d-inline  mr-4">
                                <input type="checkbox" name="G[]" id="G{{$item->id}}"  value= "{{$item->id}}" class=" gradelevel"
                                @if(old('G'))
                                    {{ in_array($item->id,old('G')) ? 'checked': ''}}
                                @endif
                                >
                                <label style="width:40%" for="G{{$item->id}}">{{$item->levelname}}</label>
                            </div>
                        @endforeach
                </div>
                {{-- <div class="dropdown-divider"></div>
                <h5 class="card-title pl-3">Sections</h5>
                <br>
                <div class="position-relative form-group clearfix mb-0 p-3">
                    @foreach(App\Models\Principal\LoadSections::sections() as $item)
                        <div class="icheck-primary d-inline col-md-2">
                            <input type="checkbox" name="S[]" id="S{{$item->id}}"  value="{{$item->id}}" 
                            @if(old('S'))
                                {{ in_array($item->id,old('S')) ? 'checked': ''}}
                            @endif
                            class="section">
                            <label style="width:45%" for="S{{$item->id}}">{{$item->sectionname}}</label></div>
                    @endforeach
                </div> --}}
            </div>
        </div>
        
    </div>
   
    <div class="col-md-8">
        {{-- <div class="main-card mb-3 card">
            <div class="card-header bg-info">
                <h5 class="card-title">Message Type</h5>
            </div>
            <div class="card-body">
                <div class="form-group clearfix mb-0">
                    <div class="icheck-primary d-inline mr-3">
                        <input type="radio" id="radioPrimary1" value="1" name="announcetype" checked>
                        <label for="radioPrimary1">System Message</label>
                    </div>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="radioPrimary2" value="2" name="announcetype" >
                        <label for="radioPrimary2">Text Blast</label>
                    </div>
                
                </div>
            </div>
        </div> --}}
        <div class="main-card mb-3 card principalcompose">
            <div class="card-header bg-info">
                <h3 class="card-title">Compose Announcement</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <input name="title"  id="title" class="form-control @error('title') is-invalid @enderror"" placeholder="Title:">
                    @error('title')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <textarea placeholder="Message Here.." rows="5" name="content" id="content" class="form-control @error('content') is-invalid @enderror"></textarea>
                    @error('content')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <textarea id="compose-textarea" class="form-control" style="height: 300px">
                      <h1><u>Heading Of Message</u></h1>
                      <h4>Subheading</h4>
                      <p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain
                        was born and I will give you a complete account of the system, and expound the actual teachings
                        of the great explorer of the truth, the master-builder of human happiness. No one rejects,
                        dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know
                        how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again
                        is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain,
                        but because occasionally circumstances occur in which toil and pain can procure him some great
                        pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise,
                        except to obtain some advantage from it? But who has any right to find fault with a man who
                        chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that
                        produces no resultant pleasure? On the other hand, we denounce with righteous indignation and
                        dislike men who are so beguiled and demoralized by the charms of pleasure of the moment, so
                        blinded by desire, that they cannot foresee</p>
                      <ul>
                        <li>List item one</li>
                        <li>List item two</li>
                        <li>List item three</li>
                        <li>List item four</li>
                      </ul>
                      <p>Thank you,</p>
                      <p>John Doe</p>
                    </textarea>
                </div>
               
                {{-- <button class="btn btn-primary pull-right">Post Announcement</button> --}}
            </div>
            <div class="card-footer">
                <div class="float-right">
                    <button type="submit" class="btn btn-primary"><i class="far fa-envelope"></i> Post Announcements</button>
                </div>
            </div>
        </div>
    </div>
</div>

</form>
       <script>
            $( document ).ready(function() {

                $('input:checkbox').change(function(){
                    console.log('HELO');
                })

                $('#all').change(function(){
                    if($(this).is(':checked')){
                        $('input:checkbox').prop("checked", true);
                    }
                    else{
                        $('input:checkbox').prop("checked", false);
                    }
                 
                    
                })
                $('#students').change(function(){
                    if($(this).is(':checked')){
                        $('.gradelevel').prop("checked", true);
                        $('.section').prop("checked", true);
                    }
                    else{
                        $('.gradelevel').prop("checked", true);
                        $('input:checkbox').prop("checked", false);
                    }

                   
                })

                $('.gradelevel').change(function(){
                    $('.gradelevel, .section').each(function(){
                        if($(this).not(':checked')){
                            $('#students').prop("checked", false);
                            $('#all').prop("checked", false);
                        }
                    })
                })

                

              
            });
       </script>
        
@endsection




