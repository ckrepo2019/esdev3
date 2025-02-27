@extends($extends)

@section('content')

    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <style>
        .swal2-header{
            border: none;
        }
        .course-sidebar{ box-shadow: none;}
        .select2-container {
            z-index: 9999;
            margin: 0px;
        }
        .select2-search__field{
            margin: 0px;
        }
        #floating-button{
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #db4437;
            position: fixed;
            bottom: 80px;
            right: 25px;
            cursor: pointer;
            box-shadow: 0px 2px 5px #666;
        }

        .plus{
            color: white;
            position: absolute;
            top: 0;
            display: block;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 0;
            margin: 0;
            line-height: 47px;
            font-family: 'Roboto';
            font-weight: 300;
            animation: plus-out 0.3s;
            transition: all 0.3s;
        }

        .sidenav {
            height: 100%;
            width: 0;
            position: fixed;
            z-index: 99999;
            top: 0;
            right: 0;
            background-color: white;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 60px;
        }


        .sidenav a:hover {
            color: #f1f1f1;
        }


        #main {
            transition: margin-left .5s;
            padding: 16px;
        }

        @media screen and (max-height: 450px) {
        .sidenav {padding-top: 15px;}
        .sidenav a {font-size: 18px;}
        }

        .simplebar-content::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 3px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }
        .simplebar-content::-webkit-scrollbar
        {
            width: 5px;
            background-color: #F5F5F5;
        }
        .simplebar-content::-webkit-scrollbar-thumb
        {
            background-color: #0ae;
            
            background-image: -webkit-linear-gradient(linear, 0 0, 0 100%,
                            color-stop(.5, rgba(255, 255, 255, .2)),
                            color-stop(.5, transparent), to(transparent));
        }
        .imageThumb {
        max-height: 75px;
        border: 2px solid;
        padding: 1px;
        cursor: pointer;
        }
        .pip {
        display: inline-block;
        margin: 10px 10px 0 0;
        }
        .remove {
        display: block;
        background: #444;
        border: 1px solid black;
        color: white;
        text-align: center;
        cursor: pointer;
        }
        .remove:hover {
        background: white;
        color: black;
        }
        .btn-icon-only {
            line-height: unset !important;
        }
        [class~=course-sidebar] {
            z-index: 0;
        }
    </style>
    <div class="page-content-inner " id="main">
        <div class="d-flex mt-3">
            <nav id="breadcrumbs" class="mb-3">
                <ul>
                    <li><a href="/home"> <i class="uil-home-alt"></i> </a></li>
                    <li>Classrooms</li>
                </ul>
            </nav>
        </div>
        @if(count($recipients)>0)
            <div class="chats-container margin-top-0">

                <div class="chats-container-inner" style="height: 700px; overflow-y: scroll">

                    <!-- chats -->
                    <div class="chats-inbox" style="height: 700px; overflow-y: scroll">
                        <div class="chats-headline">
                            <div class="input-with-icon">
                                <input id="autocomplete-input" type="text" placeholder="Search">
                                <i class="icon-material-outline-search"></i>
                            </div>
                        </div>

                        <ul>
                            @foreach($recipients as $recipient)
                                <li class="active-message" id="{{$recipient->id}}" usertype="{{$recipient->usertypeid}}">
                                    <a href="#">
                                        <div class="message-avatar"><i class="status-icon status-online"></i><img src="{{asset($recipient->picurl)}}" onerror="this.onerror = null, this.src='{{asset($recipient->avatar)}}'" alt=""></div>

                                        <div class="message-by">
                                            <div class="message-by-headline">
                                                <h5>{{$recipient->firstname}} {{$recipient->middlename}} {{$recipient->lastname}} {{$recipient->suffix}}</h5>
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

                            <div class="d-flex">
                                <div class="avatar-parent-child">
                                    {{-- <img alt="Image placeholder" src="../assets/images/avatars/avatar-1.jpg" class="avatar  rounded-circle avatar-sm" id=""> --}}
                                    {{-- <span class="avatar-child avatar-badge bg-success"></span> --}}
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
                                        {{-- <li><a href="#"> Setting</a></li> --}}
                                    </ul>
                                </div>
                            </div>

                        </div>

                        <!-- Message Content Inner -->
                        <div class="message-content-inner">
                            {{-- <input type="hidden" name="recipientid" value="{{$recipients[0]->id}}"/>
                            <input type="hidden" name="recusertypeid" value="{{$recipients[0]->usertypeid}}"/> --}}
                            @if(count($messages)>0)
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
                            @endif
                            <!-- Time Sign -->
                            {{-- <div class="message-time-sign">
                                <span>28 June, 2018</span>
                            </div> --}}



                            {{-- <div class="message-bubble me">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="../assets/images/avatars/avatar-1.jpg" alt="">
                                    </div>
                                    <div class="message-text">
                                        <p>Ok, Understood! 😉</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div> --}}


                            <!-- Time Sign -->
                            {{-- <div class="message-time-sign">
                                <span>Yesterday</span>
                            </div>

                            <div class="message-bubble me">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="../assets/images/avatars/avatar-1.jpg" alt="">
                                    </div>
                                    <div class="message-text">
                                        <p> I just wanted to let you know You’ll receive notifications for all
                                            issues, pull requests!.</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="message-bubble">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="../assets/images/avatars/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="message-text">
                                        <p>You were automatically subscribed
                                            because you’ve been given access to the repository 😎</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="message-bubble me">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="../assets/images/avatars/avatar-1.jpg" alt="">
                                    </div>
                                    <div class="message-text">
                                        <p>Ok But don't forget about last payment. 🙂</p>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="message-bubble">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="../assets/images/avatars/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="message-text w-auto">
                                        <!-- Typing Indicator -->
                                        <div class="typing-indicator">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div> --}}

                        </div>
                        <!-- Message Content Inner / End -->

                        <!-- Reply Area -->
                        <div class="message-reply">

                            <form class="d-flex align-items-center w-100" id="formsendmessage">
                                <input type="hidden" name="recipientid" value="{{$recipients[0]->id}}"/>
                                <input type="hidden" name="recipientusertypeid" value="{{$recipients[0]->usertypeid}}"/>
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
    <!-- SweetAlert2 -->
    <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
    {{-- @include('teacher.ajax.ajaxforms') --}}
    <script>
    

        $(document).ready(function(){
            
            const messages = document.getElementsByClassName('message-content-inner')[0]

            function appendMessage(msgcontent) {
                // const message = document.getElementsByClassName('message-bubble')[0];
                // const newMessage = message.cloneNode(true);
                messages.append($.parseHTML(msgcontent)[0]);
            }

            function scrollToBottom() {

                messages.scrollTop = messages.scrollHeight;
            }

            function getMessages() {
                
                var messageids = [];

                $.each($('.message-content-inner input[name="messageid"]'), function(){
                    messageids.push($(this).val())
                })
                
                var recipientid = $('input[name="recipientid"]').val();
                var recipientusertypeid = $('input[name="recipientusertypeid"]').val();
                
                $.ajax({
                    url: '/messages/autodisplaymessage',
                    type: 'GET',
                    dataType:"json",
                    data:{
                        recipientid     :  recipientid,
                        usertypeid      :  recipientusertypeid,
                        messageids      :  messageids
                    },
                    success: function(data){
                        
                        if(data.length>0)
                        {
                            $.each(data, function(key,value){
                                appendMessage(value)
                            })
                            
                            scrollToBottom();
                        }

                    }

                })
                // Prior to getting your messages.
                shouldScroll = messages.scrollTop + messages.clientHeight === messages.scrollHeight;
                /*
                * Get your messages, we'll just simulate it by appending a new one syncronously.
                */
                // appendMessage();
            // After getting your messages.
                if (!shouldScroll) {
                    // scrollToBottom();
                    $('.message-content-inner').bind('scroll mousedown wheel DOMMouseScroll mousewheel keyup', function(e){
                        if ( e.which > 0 || e.type == "mousedown" || e.type == "mousewheel"){
                        $(".message-content-inner").stop();
                        }
                        });
                }
            }

            setInterval(getMessages, 500);
            $('.message-content-inner').scrollTop($('.message-content-inner')[0].scrollHeight);
            $('.page-menu').addClass('menu-large');
            $(document).on('click','.chats-container-inner li', function(){
                $.ajax({
                    url: '/messages/getmessage',
                    type: 'GET',
                    dataType:"json",
                    data:{
                        id       :  $(this).attr('id'),
                        usertypeid      :  $(this).attr('usertype')
                    },
                    success: function(data){
                        console.log(data)
                        $('.avatar-parent-child').empty()
                        $('.avatar-parent-child').append(
                            '<img src="{{asset("/")}}'+data[0].picurl+'" onerror="this.onerror = null, this.src={{asset("/")}}'+data[0].picurl+'" alt="" class="avatar  rounded-circle avatar-sm" >'
                        )
                        $('#chatheadername').text(data[0].firstname+' '+data[0].middlename+' '+data[0].lastname+' '+data[0].suffix);
                        $('input[name="recipientid"]').val(data[0].id);
                        $('input[name="recipientusertypeid"]').val(data[0].usertypeid);
                    }
                })
            })
            $(document).on('click','.send-btn', function(){
                var recipientid = $('input[name="recipientid"]').val();
                var recipientusertypeid = $('input[name="recipientusertypeid"]').val();
                var messagecontent = $('textarea[name="messagecontent"]').val();
                if(messagecontent.replace(/^\s+|\s+$/g, "").length != 0)
                {
                    $.ajax({
                        url: '/messages/sendmessage',
                        type: 'GET',
                        dataType:"json",
                        data:{
                            recipientid     :  recipientid,
                            usertypeid      :  recipientusertypeid,
                            content         :  messagecontent
                        },
                        complete: function(){
                            $('textarea[name="messagecontent"]').val('')
                        }
                    })
                }

            })
        })
    </script>
@endsection