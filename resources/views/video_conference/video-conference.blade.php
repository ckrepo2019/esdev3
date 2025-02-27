@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->select('refid', 'resourcepath')
        ->first();

    if (Session::get('currentPortal') == 14) {
        $extend = 'deanportal.layouts.app2';
    } elseif (auth()->user()->type == 17) {
        $extend = 'superadmin.layouts.app2';
    } elseif (Session::get('currentPortal') == 3) {
        $extend = 'registrar.layouts.app';
    } elseif (Session::get('currentPortal') == 8) {
        $extend = 'admission.layouts.app2';
    } elseif (Session::get('currentPortal') == 1) {
        $extend = 'teacher.layouts.app';
    } elseif (Session::get('currentPortal') == 2) {
        $extend = 'principalsportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 4) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 15) {
        $extend = 'finance.layouts.app';
    } elseif (Session::get('currentPortal') == 18) {
        $extend = 'ctportal.layouts.app2';
    } elseif (Session::get('currentPortal') == 10) {
        $extend = 'hr.layouts.app';
    } elseif (Session::get('currentPortal') == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } elseif (auth()->user()->type == 16) {
        $extend = 'chairpersonportal.layouts.app2';
    } else {
        if (isset($check_refid->refid)) {
            if ($check_refid->resourcepath == null) {
                $extend = 'general.defaultportal.layouts.app';
            } elseif ($check_refid->refid == 27) {
                $extend = 'academiccoor.layouts.app2';
            } elseif ($check_refid->refid == 22) {
                $extend = 'principalcoor.layouts.app2';
            } elseif ($check_refid->refid == 29) {
                $extend = 'idmanagement.layouts.app2';
            } elseif ($check_refid->refid == 23) {
                $extend = 'clinic.index';
            } elseif ($check_refid->refid == 24) {
                $extend = 'clinic_nurse.index';
            } elseif ($check_refid->refid == 25) {
                $extend = 'clinic_doctor.index';
            } elseif ($check_refid->refid == 31) {
                $extend = 'guidanceV2.layouts.app2';
            } else {
                $extend = 'general.defaultportal.layouts.app';
            }
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)

@section('pagespecificscripts')
    <title>Video Conference</title>
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="videoConferenceModal" tabindex="-1" role="dialog" aria-labelledby="videoConferenceModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoConferenceModalLabel">Video Conference</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="meet" style="height: 90vh; width: 100%;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="joinModal" tabindex="-1" role="dialog" aria-labelledby="joinModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="joinModalLabel">Join Virtual Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="roomNameJoin">Room Name</label>
                            <input type="text" class="form-control" id="roomNameJoin" aria-describedby="roomNameFeedback"
                                required>
                            <small id="roomNameFeedback" class="form-text text-muted">Enter room name you want to
                                join</small>
                            <div class="invalid-feedback">
                                Invalid room name
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnJoinRoom">Join</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="roomModalLabel">Create Virtual Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="roomName">Room Name</label>
                        <input type="text" class="form-control" id="roomName" placeholder="Enter room name">
                        <div class="invalid-feedback">
                            Virtual room is required.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addRoom">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Room</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this room?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div>
                <h1>CK Video Conference</h1>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#roomModal">
                    + Create Room
                </button>
                <button type="button" class="btn btn-success join_room">
                    <i class="nav-icon fas fa-video"></i> Join Room
                </button>


                <div class="row mt-4">
                    <div class="col-md-12">
                        <table class="table table-valign-middle table-striped" style="width: 100%">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="20%">Room Name</th>
                                    <th width="20%">Created Date</th>
                                    <th width="20%">Creator</th>
                                    <th width="15%">Audience </th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="tbl_rooms">

                            </tbody>
                        </table>

                    </div>
                </div>

            </div>
            {{-- <div id="meet" style="height: 90vh;" hidden></div> --}}
            {{-- <h1>Join Jitsi Meet Room</h1> --}}
            {{-- <button id="google-login-btn">Login with Google</button> --}}
            {{-- <a id="join-meeting-btn" href="https://meet.jit.si/CustomRoomName" target="_blank" >Join Meeting</a> --}}
        </div>
    </section>
@endsection

@section('footerjavascript')
    <script src="https://meet.jit.si/external_api.js"></script>
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>
        $(document).ready(function() {

            virtualRooms()

            $("#addRoom").click(function() {
                var roomName = $("#roomName").val();
                if (roomName == "" || roomName == null) {
                    notify('error', 'Room Name is required');
                    $("#roomName").addClass('is-invalid');
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "{{ route('createvirtualroom') }}",
                    data: {
                        roomName: roomName,
                        creator: '{{ auth()->user()->name }}'
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $("#roomName").removeClass('is-invalid');
                        $("#roomName").addClass('is-valid');
                        $("#roomName").val('');
                        $("#roomModal").modal('hide');
                        notify(response.status, response.message);
                        virtualRooms()
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });

            })

            $('.join_room').click(function() {
                $("#joinModal").modal('show');
            })

            $('#btnJoinRoom').on('click', function() {
                var roomName = $('#roomNameJoin').val();
                if (roomName == "" || roomName == null) {
                    notify('error', 'Room Name is required');
                    $('#roomNameJoin').addClass('is-invalid');
                    return;
                }
                $.ajax({
                    type: "GET",
                    url: "{{ route('checkvirtualroom') }}",
                    data: {
                        roomName: $('#roomNameJoin').val(),
                    },
                    success: function(response) {
                        $("#joinModal").modal('hide');
                        notify(response.status, response.message);
                        if (response.status == 'success') {
                            joinRoom(roomName)
                        }
                    },
                    error: function(error) {
                        console.log(error)
                    }
                });
            })

        });

        function virtualRooms() {
            $.ajax({
                type: "GET",
                url: "{{ route('virtualrooms') }}",
                success: function(response) {
                    console.log(response);
                    let rooms = response;
                    $('#tbl_rooms').empty();
                    $.each(rooms, function(index, room) {

                        let buttonText = 'Join';
                        if (room.creator === "{{ auth()->user()->name }}") {
                            buttonText = 'Start';
                        }

                        $('#tbl_rooms').append(`<tr>
                        <td><a href="javascript:void(0)" onclick="joinRoom('${room.room_name}')"><i class="ml-2 fas fa-link"></i> <span>${room.room_name}</span></a></td>
                        <td>${room.created_at_formatted.split(' ')[0]}<br>
                            <small class="text-muted">${room.created_at_formatted.split(' ')[1]} ${room.created_at_formatted.split(' ')[2]}</small>
                        </td>
                        <td>${room.creator ? room.creator : 'Not Specified'}</td>
                        <td>${room.audience ? room.audience : 0}</td>
                        <td class="${room.is_active ? 'text-success' : 'text-danger'}">${room.is_active ? 'Active' : 'Closed'}</td>

                        <td>
                            <button class="btn btn-sm btn-info" onclick="joinRoom('${room.room_name}')"><i class="nav-icon fas fa-video"></i> ${buttonText}</button>
                            <button class="btn btn-sm btn-danger" data-id="${room.id}" onclick="deleteRoom('${room.room_name}')"><i class="nav-icon fas fa-trash"></i> Delete</button>
                        </td>
                        
                        </tr>`);
                    });
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        deleteRoom = (roomName) => {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Delete this Room?',
                text: "You won't be able to revert this!",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('deletevirtualroom') }}",
                        data: {
                            id: id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            notify(response.status, response.message);
                            virtualRooms()
                        },
                        error: function(error) {
                            console.log(error)
                        }
                    });
                }
            });
        }

        joinRoom = (roomName) => {
            initRooms(roomName);
            var params = 'width=' + screen.width;
            params += ', height=' + screen.height;
            params += ', top=0, left=0'
            params += ', fullscreen=yes';
            window.open(`https://meet.jit.si/${roomName}`, '_blank', params);
        }

        initRooms = (roomName) => {
            const domain = "meet.jit.si";
            const options = {
                roomName: roomName,
                width: '100%',
                height: '100%',
                parentNode: document.querySelector('#meet')
            };
            const api = new JitsiMeetExternalAPI(domain, options);
            api.addEventListener('videoConferenceJoined', () => {
                console.log('The room is active.');
                // Perform additional actions if needed
            });

            api.addEventListener('videoConferenceLeft', () => {
                console.log('The room is closed.');
                // Perform additional actions if needed
            });
        }

        // joinRoom = (roomName, isCreator) => {
        //     const meetContainer = document.getElementById('meet');
        //     meetContainer.innerHTML = '';
        //     const script = document.createElement('script');
        //     script.src = 'https://meet.jit.si/external_api.js';
        //     document.head.appendChild(script);
        //     const domain = "meet.jit.si";
        //     const options = {
        //         roomName: roomName,
        //         width: '100%',
        //         height: '100%',
        //         parentNode: meetContainer,
        //         configOverwrite: {
        //             startWithVideoMuted: !isCreator,
        //             startWithAudioMuted: !isCreator,
        //             prejoinPageEnabled: !isCreator,
        //             enableWelcomePage: !isCreator
        //         },
        //         interfaceConfigOverwrite: {
        //             AUTO_PIN_LATEST_SCREEN_SHARE: 'remote-only',
        //             DISABLE_PRESENCE_STATUS: true,
        //             DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
        //             DISABLE_RINGING: true
        //         }
        //     };
        //     const api = new JitsiMeetExternalAPI(domain, options);
        //     if (isCreator) {
        //         api.executeCommand('displayName', '{{ auth()->user()->name }}');
        //         api.executeCommand('password', '{{ auth()->user()->id }}');
        //         api.executeCommand('displayName', '{{ auth()->user()->name }}');
        //         api.executeCommand('password', '{{ auth()->user()->id }}');
        //         api.executeCommand('startSilent');
        //     }
        //     $('#videoConferenceModal').modal({ backdrop: 'static', keyboard: false });

        // }

        // joinRoom = (roomName) => {
        //     $('#meet').html(`<iframe src="https://meet.jit.si/${roomName}" frameborder="0" width="100%" height="100%"></iframe>`);
        //     $('#videoConferenceModal').modal({ backdrop: 'static', keyboard: false });
        // }
    </script>
@endsection
