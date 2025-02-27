<html itemscope itemtype="http://schema.org/Product" prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/html">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
        <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    </head>
    <style>
        /* html { overflow-x: hidden; }

        #api-parent { text-align: center; }

        .customheight { margin: auto; height:80vh; display: flex; }

        .col { flex: 1; /* additionally, equal width */ padding: 1em; }

        .main-content{ height:60vh; overflow-y: scroll; }

        @media only screen and (max-width: 767px)
        {
            .main-content{ height:35vh; overflow-y: scroll; }
            #sharebutton{}
        }

        .main-footer{ bottom:0; width: 100%; margin-left: 0px !important;}
        
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
        } */
        /* {
            display: hidden !important;
        } */
    </style>
    <body style="width: 100%;background-color: #343a40;">
        {{-- <div class="row customheight"> --}}
            {{-- <div class="col-4 col">
                <div class="card h-100">
                    <div class="card-header">
                        Shared Files
                        (Uploading of files only)
                    </div>
                    <div class="card-body">
                        <main class="main-container">
                            <div class="main-content" style="overflow-y: scroll">
                                
                            </div>
                            <footer class="main-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input id="fileid" type="file" class="form-control mb-2" multiple accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,text/plain, application/pdf, image/*,video/*,audio/*" name="content" style="border-radius: 25px;"/>
                                    </div>
                                </div>
                            </footer>
                      </main>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-12 col">
                <div class="card h-100">
                    @if(auth()->user()->type != 7)
                    <div class="card-header">
                        <div class="row">
                            <div class="col-1">
                                <button type="button" id="sharebutton" class="btn btn-sm btn-primary">
                                    <i class="fa fa-copy"></i>
                                </button> 
                            </div>
                            <div class="col-11">
                                <input type="text"  id="classroomcode" class="form-control form-control-sm" value="{{Crypt::encrypt('/virtualclassroom'.'/'.$code)}}" disabled/>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card-body" id="api-parent">
                    </div>
                </div>
            </div>
        </div> --}}
        <script type="text/javascript" src="{{asset('assets/scripts/main.js')}}"></script>
        <script type="text/javascript" src="{{asset('assets/scripts/jquery.min.js')}}"></script>
        <!-- SweetAlert2 -->
        <script src="{{asset('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
        <script src="{{asset('plugins/sweetalert2/sweetalert2.all.min.js')}}"></script>
        <script src="https://meet.jit.si/external_api.js"></script>
        <script>
            $('.invite-more-button').css('display','hidden')
            const parentElement = document.getElementById('api-parent');
            var domain = "meet.jit.si";
            var options = {
                roomName: "{{$classroom->code}}"
                // width: 700,
                // height: 200,
                // parentNode: parentElement
                // configOverwrite: {
                    
                // }
                
                @if(auth()->user()->type == 7 )
                ,
                configOverwrite: {disableKick: true}
                ,
                interfaceConfigOverwrite: {
                    SHOW_JITSI_WATERMARK: false,
                    HIDE_INVITE_MORE_HEADER: true,
    startWithAudioMuted: false,
                    // filmStripOnly: true,
                    TOOLBAR_BUTTONS: [
                        'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                        'fodeviceselection', 'profile', 'chat', 'recording',
                        'livestreaming', 'sharedvideo', 'raisehand',
                        'videoquality','tileview', 'filmstrip', 'stats', 'shortcuts', 'videobackgroundblur', 'download'
                    ]
                    // TOOLBAR_BUTTONS: [
                    //     'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    //     'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    //     'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    //     'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts','invite',
                    //     'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security'
                    // ]
                    // LANG_DETECTION: true,
                    // TOOLBAR_BUTTONS: ['microphone', 'camera', 'tileview'],
                    // filmStripOnly: false,
                    // DEFAULT_BACKGROUND: '#4dbdea'
                }
                @else
                    ,
                    configOverwrite: {}
                    ,
                    interfaceConfigOverwrite: {
                        SHOW_JITSI_WATERMARK: false,
                        // filmStripOnly: true,
                        TOOLBAR_BUTTONS: [
                            'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                            'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                            'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                            'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts', 'invite',
                            'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security'
                        ]
                        // LANG_DETECTION: true,
                        // TOOLBAR_BUTTONS: ['microphone', 'camera', 'tileview'],
                        // filmStripOnly: false,
                        // DEFAULT_BACKGROUND: '#4dbdea'
                    }
                @endif
                ,
                userInfo: {
                    displayName: '{{auth()->user()->name}}' // PHP backend user name
                }
                // fileRecordingsEnabled: true,
                // liveStreamingEnabled: true
            }
            var api = new JitsiMeetExternalAPI(domain, options);
            var pass = '{{$classroom->code}}';
            setTimeout(() => {
            // why timeout: I got some trouble calling event listeners without setting a timeout :)

                // when local user is trying to enter in a locked room 
                api.addEventListener('passwordRequired', () => {
                    api.executeCommand('password', pass);
                });

                // when local user has joined the video conference 
                api.addEventListener('videoConferenceJoined', (response) => {
                    api.executeCommand('password', pass);
                });

            }, 10);
            // $(document).ready(function(){
                
            //     var addattachment = 0;

            //     if (window.File && window.FileList && window.FileReader) {
            //         $("#fileid").on("change", function(e) {
            //             var clickedButton = this;
            //             var files = e.target.files,
            //             filesLength = files.length;
            //             addattachment=filesLength;
            //             var swalpreview = Swal.fire({
            //                 html: '<div class="row">'+
            //                         '<div class="col-12" id="attachmentscontainer">'+
            //                         '</div>'+
            //                     '</div>',
            //                 confirmButtonColor: '#3085d6',
            //                 confirmButtonText: 'Upload',
            //                 showCancelButton: true
            //             }).then((confirm) => {
            //                 if (confirm.value) {
            //                    console.log('asdad')
            //                 }else {
            //                     $('#fileid').val('')
            //                 }
            //             })
            //             for (var i = 0; i < filesLength; i++) {
            //                 var f = files[i]
            //                 var fileextension = f.name.split('.').pop()
            //                 var fileReader = new FileReader();
            //                 fileReader.fileName = f.name 
            //                 fileReader.onload = (function(e) {
            //                     var file = e.target;
            //                     $('#attachmentscontainer').append(
            //                     "<span class=\"pip\">" +
            //                     "<img class=\"imageThumb\" src=\"" + e.target.result + "\" onerror=\"this.onerror = null, this.src=\"{{asset('assets/images/mp4.png')}}\" title=\"" + e.target.fileName + "\" style='width:100px;height:100px;display: inline;'/>" +
            //                     "<br/><span class=\"remove\"><i class='fa fa-trash'></i></span>" +
            //                     "</span>"
            //                     )
            //                 });
            //                 fileReader.readAsDataURL(f);
            //             }
            //         });
            //     } else {
            //         alert("Your browser doesn't support to File API")
            //     }
                
            //     $(document).on('click','.remove',function(){
            //         // addattachment-=1;
            //         // console.log(addattachment)
            //         $(this).parent(".pip").remove();
            //             addattachment-=1;
            //         // return false;
            //         if(addattachment == 0){
            //             $('.swal2-container').remove();
            //             $('#fileid').val('')
            //         }
            //     });
                
            //     @if(auth()->user()->type != 7 )
            //         $("#sharebutton").click(function(event){
            //             var $tempElement = $('<input>');
            //                 $("body").append($tempElement);
            //                 $tempElement.val($('#classroomcode').val).select();
            //                 document.execCommand("Copy");
            //                 $tempElement.remove();
            //         });
            //     @endif
            // })
        </script>
    </body>
</html>