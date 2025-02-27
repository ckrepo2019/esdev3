{{-- @extends($extends)

@section('content') --}}
<!DOCTYPE html>
<!-- saved from url=(0061)# -->
<html lang="en" class="">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Basic Page Needs
    ================================================== -->
    <title>Sample Page</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Courseplus - Professional Learning Management HTML Template">


    <!-- CSS 
    ================================================== -->
    <link rel="stylesheet" href="{{asset('templatefiles/style.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/night-mode.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/framework.css')}}">
    <link rel="stylesheet" href="{{asset('templatefiles/bootstrap.css')}}">

<!-- SweetAlert2 -->
<link rel="stylesheet" href="{{asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
<!-- summernote -->
    <!-- icons
    ================================================== -->
    <link rel="stylesheet" href="{{asset('templatefiles/icons.css')}}">


    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<style>
    
html::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
	border-radius: 10px;
}
html::-webkit-scrollbar
{
	width: 5px;
	background-color: #3e416d;
}
html::-webkit-scrollbar-thumb
{
	background-color: #3e416d;
	
	background-image: -webkit-linear-gradient(linear, 0 0, 0 100%,
									   color-stop(0.44, rgb(122,153,217)),
									   color-stop(0.72, rgb(73,125,189)),
									   color-stop(0.86, rgb(28,58,148)));
}
</style>
</head>
<body>

    <div class=" " id="main">
        {{-- <div class="d-flex mt-3">
            <nav id="breadcrumbs" class="mb-3">
                <ul>
                    <li><a href="/home"> <i class="uil-home-alt"></i> </a></li>
                    <li>Classrooms</li>
                </ul>
            </nav>
        </div> --}}
        @if(count($recipients)>0)
            <div class="chats-container margin-top-0">

                <div class="chats-container-inner" style="height: -webkit-fill-available;">

                    <!-- chats -->
                    <div class="chats-inbox" style="height: -webkit-fill-available;">
                        <div class="chats-headline">
                            <div class="input-with-icon">
                                <input id="autocomplete-input" type="text" placeholder="Search">
                                <i class="icon-material-outline-search"></i>
                            </div>
                        </div>

                        <ul>
                            @foreach($recipients as $recipient)
                                <li class="active-message" id="{{$recipient->userid}}">
                                    <a href="#">
                                        <div class="message-avatar"><i class="status-icon status-online"></i><img src="{{asset($recipient->picurl)}}" onerror="this.onerror = null, this.src='{{asset($recipient->avatar)}}'" alt=""></div>

                                        <div class="message-by">
                                            <div class="message-by-headline">
                                                <h5 class="recipientname">{{$recipient->firstname}} {{$recipient->middlename}} {{$recipient->lastname}} {{$recipient->suffix}}</h5>
                                                {{-- <span>Yesterday</span> --}}
                                            </div>
                                            {{-- <p>How are you?</p> --}}
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- chats / End -->

                    <!-- Message Content -->
                    <div class="message-content" >

                        <div class="chats-headline">
                            @php
                                if($recipients[0]->usertypeid == '7')
                                {
                                    if(strtolower($recipients[0]->gender) == 'female')
                                    {
                    
                                        $avatar = 'avatar/S(F) 1.png';
                    
                                    }else{
                                        
                                        $avatar = 'avatar/S(M) 3.png';
                                    }
                                }else{
                                    if(strtolower($recipients[0]->gender) == 'female')
                                    {
                    
                                        $avatar = 'avatar/T(F) 3.png';
                    
                                    }else{
                                        
                                        $avatar = 'avatar/T(M) 2.png';
                                    }
                                }
                            @endphp
                            <div class="d-flex">
                                <div class="avatar-parent-child">
                                    <img alt="Image placeholder" src="{{asset($recipients[0]->picurl)}}" onerror="this.onerror = null, this.src='{{asset($avatar)}}'" class="avatar  rounded-circle avatar-sm" id="">
                                    <span class="avatar-child avatar-badge bg-success"></span>
                                </div>
                                <h4 class="ml-2" id="chatheadername"> {{$recipients[0]->firstname}}  {{$recipients[0]->middlename}} {{$recipients[0]->lastname}} {{$recipients[0]->suffix}}
                                    {{-- <span>Online</span> --}}
                                 </h4>
                            </div>

                            <div class="message-action">
                                <a href="#" class="btn btn-icon btn-hover  btn-circle" uk-tooltip="filter" title="" aria-expanded="false">
                                    <i class="uil-outgoing-call"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-hover  btn-circle" uk-tooltip="filter" title="" aria-expanded="false">
                                    <i class="uil-video"></i>
                                </a>
                                <a href="#" class="btn btn-icon btn-hover  btn-circle" uk-tooltip="More" title="" aria-expanded="false">
                                    <i class="uil-ellipsis-h"></i>
                                </a>
                                <div uk-dropdown="pos: left ; mode: click ;animation: uk-animation-slide-bottom-small" class="uk-dropdown">
                                    <ul class="uk-nav uk-dropdown-nav">
                                        <li><a href="#"> Refresh </a></li>
                                        <li><a href="#"> Manage</a></li>
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <!-- Message Content Inner -->
                        <div class="message-content-inner" id="message_holder">
                            {{-- <input type="hidden" name="recipientid" value="{{$recipients[0]->id}}"/>
                            <input type="hidden" name="recusertypeid" value="{{$recipients[0]->usertypeid}}"/> --}}
                            {{-- @if(count($messages)>0)
                                @foreach($messages as $message)
                                    @if($message->mine == 1)
                                        <div class="message-bubble me">
                                            <div class="message-bubble-inner">
                                                <div class="message-avatar">
                                                    <img src="{{asset($message->picurl)}}" onerror="this.onerror = null, this.src='{{asset($message->avatar)}}'" alt="">
                                                </div>
                                                <div class="message-text">
                                                    <input type="hidden" name="messageid" value="{{$message->id}}"/>
                                                    <p>{{$message->content}}</p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @elseif($message->mine == 0)
                                        <div class="message-bubble">
                                            <div class="message-bubble-inner">
                                                <div class="message-avatar">
                                                    <img src="{{asset($message->picurl)}}" onerror="this.onerror = null, this.src='{{asset($message->avatar)}}'" alt="">
                                                </div>
                                                <div class="message-text">
                                                    <input type="hidden" name="messageid" value="{{$message->id}}"/>
                                                    <p>{{$message->content}}</p>
                                                </div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif --}}
                            <!-- Time Sign -->
                            {{-- <div class="message-time-sign">
                                <span>28 June, 2018</span>
                            </div> --}}
                        </div>
                        <!-- Message Content Inner / End -->

                        <!-- Reply Area -->
                        <div class="message-reply">

                            <form class="d-flex align-items-center w-100" id="formsendmessage">
                                <input type="hidden" name="recipientid" value="{{$recipients[0]->userid}}"/>
                                {{-- <input type="hidden" name="recipientusertypeid" value="{{$recipients[0]->usertypeid}}"/> --}}
                                <div class="btn-box d-flex align-items-center mr-3">
                                    <a href="#" class="btn btn-icon  btn-default btn-circle d-inline-block mr-2" uk-tooltip="filter" title="" aria-expanded="false">
                                        <i class="uil-smile-wink"></i>
                                    </a>
                                    <a href="#" class="btn btn-icon  btn-default btn-circle d-inline-block  " uk-tooltip="filter" title="" aria-expanded="false">
                                        <i class="uil-link-alt"></i>
                                    </a>
                                </div>
                                
                                <textarea cols="1" rows="1" placeholder="Your Message" data-autoresize="" name="messagecontent"></textarea>

                                <button type="button" class="send-btn d-inline-block btn btn-default">Send <i class="bx bx-paper-plane"></i></button>
                            </form>

                        </div>

                        <!-- 
                    <div class="message-reply">
                        <textarea cols="1" rows="1" placeholder="Your Message" data-autoresize></textarea>
                        <button class="btn btn-primary ripple-effect">Send</button>
                    </div>-->



                    </div>
                    <!-- Message Content -->

                </div>
            </div>
        @endif
    <!-- Bootstrap -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
    
    <!-- Bootstrap4 Duallistbox -->
    <script src="{{asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <!-- AdminLTE -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <script src="{{asset('templatefiles/framework.js')}}"></script>
    {{-- <script src="{{asset('templatefiles/jquery-3.3.1.min.js')}}"></script> --}}
    {{-- <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script> --}}
    <script src="{{asset('templatefiles/simplebar.js')}}"></script>
    <script src="{{asset('templatefiles/main.js')}}"></script>
    <script src="{{asset('templatefiles/bootstrap-select.min.js')}}"></script>
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('plugins/summernote/summernote-bs4.js')}}"></script>
    <script src="{{asset('templatefiles/chart.min.js')}}"></script>
    {{-- <script type="text/javascript" src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> --}}
    <script src="{{asset('templatefiles/chart-custom.js')}}"></script>
    {{-- <script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script> --}}
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>


    <script>
    

        $(document).ready(function(){

            var userid = '{{$recipients[0]->userid}}'

            
            const messages = document.getElementsByClassName('message-content-inner')[0]

            function appendMessage(msgcontent) {
                // const message = document.getElementsByClassName('message-bubble')[0];
                // const newMessage = message.cloneNode(true);
                messages.append($.parseHTML(msgcontent)[0]);
            }

            function scrollToBottom() {

                messages.scrollTop = messages.scrollHeight;
            }

            loadMessages()

            function loadMessages(){
               
                $.ajax({
                    url: '/messages/loadmessages',
                    type: 'GET',
                    data:{
                        userid      :  userid
                    },
                    success: function(data){
                      
                        $('#message_holder').empty()
                        $('#message_holder').append(data)

                    }
                })
            }

            setInterval(loadMessages, 500);
            $('.message-content-inner').scrollTop($('.message-content-inner')[0].scrollHeight);
            $('.page-menu').addClass('menu-large');

            $(document).on('click','.chats-container-inner li', function(){

                
                        studid = $(this).attr('id')
                        userid = studid;
                        $('#chatheadername').text($(this).find('.recipientname').text())
                        $('input[name="recipientid"]').val(studid);
                        loadMessages()

            })
            $(document).on('click','.send-btn', function(){
                var recipientid = $('input[name="recipientid"]').val();
                userid = recipientid;
                var messagecontent = $('textarea[name="messagecontent"]').val();
                if(messagecontent.replace(/^\s+|\s+$/g, "").length != 0)
                {
                    $.ajax({
                        url: '/messages/sendmessage',
                        type: 'GET',
                        dataType:"json",
                        data:{
                            recipientid     :  recipientid,
                            content         :  messagecontent
                        },
                        complete: function(){
                            $('textarea[name="messagecontent"]').val('')
                        }
                    })
                }

            })
        })
                
        $(document).ready(function(){

            $(document).on('click','#logout',function(){
                Swal.fire({
                title: 'Are you sure you want to logout?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Logout'
                })
                .then((result) => {
                if (result.value) {
                    event.preventDefault(); 
                    $('#logout-form').submit()
                }
                })
            })
        })
    </script>
</body>
</html>