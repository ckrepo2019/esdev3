@php
    $check_refid = DB::table('usertype')
        ->where('id', Session::get('currentPortal'))
        ->where('deleted', 0)
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
    } elseif (Session::get('currentPortal') == 6) {
        $extend = 'adminPortal.layouts.app2';
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
            } elseif ($check_refid->refid == 26) {
                $extend = 'hr.layouts.app';
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
            } elseif ($check_refid->refid == 33) {
                $extend = 'inventory.layouts.app2';
            } elseif ($check_refid->refid == 35) {
                $extend = 'tesda.layouts.app2';
            } elseif ($check_refid->refid == 36) {
                $extend = 'tesda_trainer.layouts.app2';
            }
             else {
                $extend = 'general.defaultportal.layouts.app';
            } 
        } else {
            $extend = 'general.defaultportal.layouts.app';
        }
    }
@endphp
@extends($extend)
@section('content')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <style>
        .image-container {
            position: relative;
            display: inline-block;
            margin: 5px;
        }

        .badge2 {
            position: absolute;
            top: -5px;
            right: 10px;
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
            font-size: 10px;
            width: 25px !important
        }

        .rounded-circle {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }

        .label {
            margin-top: 5px;
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .shadow-sm {
            box-shadow: none;
        }

        .shadow-none {
            box-shadow: none !important;
        }

        .message-container {
            transition: transform 0.3s ease-in-out;
        }

        .message-container:hover {
            transform: scale(1.05);
        }

        .modal-dialog {
            position: fixed;
            bottom: 0;
            right: 0;
            margin: 0;
            max-width: 400px;
            /* Adjust as necessary */
            width: 100%;
        }

        .modal-content {
            border-radius: 15px;
            /* Rounded corners */
        }

        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, .075) !important;
            border: 0 !important;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .shadow-none {
            box-shadow: none !important;
            border: 1px solid rgb(224, 222, 222) !important;
        }

        .hover-light-gray {
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .hover-light-gray:hover {
            background-color: #e0e0e0;
            /* Light gray hover color */
        }

        .selected {
            background-color: #d4edda;
            /* Light green background for selected item */
        }
    </style>

    {{-- <section class="content-header p-0" style="padding-top: 15px!important;">
    <div class="container-fluid">
        <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Notifications</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/home">Home</a></li>
                    <li class="breadcrumb-item active">Notifications</li>
                </ol>
                </div>
        </div>
    </div>
</section> --}}

    @php
        $refid = DB::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid;
        $teacherid = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->value('id');
        $teacherpicurl = DB::table('teacher')
            ->where('userid', auth()->user()->id)
            ->value('picurl');

    @endphp

    {{-- MODAL --}}

    <div class="modal fade" id="composeModal" tabindex="-1" aria-labelledby="composeModalLabel" aria-hidden="true"
        data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="composeModalLabel">New Message</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">&times;</span></button>
                </div>
                <form method="POST" action="/hr/settings/notification/sendnotification" id="multiple-files-upload"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <select name="recipient" id="recipientEmail" class="form-control">
                                    <option value="" disabled>Select Recipient</option>
                                    <option value="All">All</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sendToAll" id="sendToAll">
                                    <label class="form-check-label" for="sendToAll">
                                        Send to all
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="sendByDepartment"
                                        id="sendByDepartment">
                                    <label class="form-check-label" for="sendByDepartment">
                                        Send by department
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <select name="" class="form-control" id="messageType">
                                    <option value="message">Message</option>
                                    <option value="request">Request</option>
                                    <option value="concern">Concern</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="emailSubject" placeholder="Subject">
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="emailBody" rows="8" placeholder="Compose your message..."></textarea>
                            </div>
                            <div class="mb-3">
                                <div id="previewImageHolder" style="display: none">
                                    <hr>
                                    <p>Attached files:</p>
                                    <div id="previewImage" class="row">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="button" class="btn btn-primary btn-sm" id="sendMessage" data-department="0"
                            style="border-radius: 10px">Send</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                            style="border-radius: 10px">Discard</button>

                        {{-- <a href="javascript:void(0);" style="font-size: 20px; margin-left: 10px;">
                        <i class="fas fa-paperclip rotate-icon text-dark" id="attachfile"></i>
                        <input type="file" id="fileInput"  style="display: none;">
                    </a>
                    <a href="javascript:void(0);" style="font-size: 20px; margin-left: 10px;">
                        <i class="fas fa-link rotate-icon text-dark" id="attachfile"></i>
                        <input type="file" id="fileInput"  style="display: none;">
                    </a> --}}
                        <a href="javascript:void(0);" style="font-size: 20px; margin-left: 10px;">
                            <i class="fas fa-image rotate-icon text-dark" id="attachfile"></i>
                            <input type="file" id="image" accept="image/*" style="display: none;">
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL END --}}


    <section class="content">

        <div class="row mt-5">
            <div class="col-lg-6 col-xl-3">
                <div class="card mb-2 shadow-sm" id="profile_holder" style="display:none">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 bg-primary" style="height: 80px;"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-4 text-center">
                                <img class="img-fluid elevation-2" src="{{ asset('dist/img/download.png') }}"
                                    id="profilepic"
                                    style="width: 80px; height: 80px; border-radius: 50%; position: absolute; top: -40px; left: 10px"
                                    alt="Profile Picture">
                            </div>
                            <div class="col-md-8 col-8 pt-2 pl-3">
                                <h5 id="name_user" style="font-size: 1rem;"></h5>
                                <small style="line-height: 2px;">
                                    <p id="department_user" class="m-0"></p>
                                </small>
                            </div>
                        </div>
                        <div class="row text-center mt-3">
                            <div class="col">
                                <a class="profile" href="#"><b id="requestCount">12</b></a>
                                <p style="font-size: 0.8rem; font-weight: 500;">Request</p>
                            </div>
                            <div class="col">
                                <a class="profile" href="#"><b id="concernCount">8</b></a>
                                <p style="font-size: 0.8rem; font-weight: 500;">Concerns</p>
                            </div>
                            <div class="col">
                                <a class="profile" href="#"><b id="messageCount">23</b></a>
                                <p style="font-size: 0.8rem; font-weight: 500;">Messages</p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col text-center">
                                <button type="button" class="btn btn-success btn-sm" id="sendnotification"
                                    data-id="0">Send Notifications</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card  shadow-sm">
                    <div class="card-header d-flex align-items-center bg-white">
                        <span style="font-weight: 500" class="ml-2">Users</span>
                        <form class="w-100" action="/hr/employees/notifications" method="GET">
                            <div class="input-group p-2">
                                <input type="text" class="form-control form-control-sm" name="search"
                                    id="user_search" placeholder="Search for users">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row" style="max-height: 300px; overflow-y: auto;">
                            <ul class="list-unstyled" id="users_div_holder" style="width: 100%;">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-2">
                <div class="mb-3">
                    <a class="btn btn-sm btn-outline-secondary" id="backButton">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                    <a class="btn btn-sm btn-outline-secondary" id="newMessage">
                        <i class="fa fa-envelope"></i> New Message
                    </a>
                </div>
                <div class="card shadow-sm rounded" id="message_holder">
                </div>
            </div>
            <div class="d-md-none d-lg-none col-sm-12 d-sm-block d-xl-block col-xl-3 mt-2">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-center">TODAY'S SCHEDULE</h6>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::now()->format('l, F j, Y') }}
                        </small>
                        <div class="mt-3 p-5 bg-light text-dark rounded">
                            <p>Start of Classes!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </section>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script>
        (function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });
            // Use Toast here
        })();

        const csrfToken = "{{ csrf_token() }}";
        const id = {{ $id }};
        const picurl = '{{ $teacherpicurl }}' === '' ? "''" : "{{ $teacherpicurl }}";


        function getUsers(id) {

            var search = $('#user_search').val();

            return $.ajax({
                type: "GET",
                data: {
                    search: search,
                    id: id
                },
                url: "/hr/settings/notification/valid_employees",
            });
        }


        function getMessages(id) {

            return $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "/hr/settings/notification/getMessages",
            });
        }


        function getDepartment() {

            return $.ajax({
                type: "GET",
                url: "/hr/settings/notification/getDepartment",
            });
        }


        function getAllMessages() {

            return $.ajax({
                type: "GET",
                url: "/hr/settings/notification/getAllMessages",
            });
        }

        function renderMessages(id) {

            getMessages(id).then(function(data) {

                var renderHtml = data.length > 0 ? data.map(entry => {
                        return `
                    <div class="card-header d-flex align-items-center bg-white">
                        <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'"  alt="Profile Image" class="rounded-circle me-3" style="width: 50px; height: 50px;">
                        <div class="ml-2">
                            <h6 class="mb-0">${entry.full_name} <span class="bg-primary text-white rounded p-1" style="font-size: 12px;">${entry.messagettype}</span> <span class="text-secondary" style="font-size: 12px;">posted a new message for ${entry.recepientfull_name}</span> </h6>
                            <small class="text-muted">${moment(entry.createddatetime, "YYYY-MM-DD HH:mm:ss").fromNow()}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title">Subject:  <b> ${entry.subject}  </b> </h6> <br\>
                        <p> ${entry.additionalmessage}</b></p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" id="image-holder${entry.id}" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white d-flex align-items-center">
                        <div class="row mr-2">
                            <div class="col-md-12">
                                <div class="like-dislike">
                                    <i class="fas fa-thumbs-up me-3 text-success sendLike" data-id="${entry.id}"></i>
                                </div>
                            </div>
                            
                        </div>
                        <div class="input-group">
                            <img src="/${picurl ? picurl : 'dist/img/download.png'}" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" alt="Profile Image" class="rounded-circle mr-2" style="width: 40px; height: 40px;">
                            <input type="text" class="form-control" id="input-reply${entry.id}" placeholder="Reply Here" aria-label="Reply">
                            <button class="btn btn-outline sendReply" data-id="${entry.id}" type="button "><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </div>
                    
        

                    <div id="reply-section${entry.id}">

                    </div>
                
                    `;
                    }).join('') :
                    `<div class="card shadow-none border-0 mb-3 d-flex align-items-center justify-content-center" id="message_holder" style="height: 100px;"><div class="text-center"><h6 class="text-secondary">No message found</h6></div></div>`;
                $('#message_holder').html(renderHtml);


                renderimage(data);
                renderReply(data);

            })
        }

        function getReply(id) {

            return $.ajax({
                type: "GET",
                data: {
                    id: id
                },
                url: "/hr/settings/notification/getReply",
            });
        }

        function renderReply_submit(id) {

            getReply(id).then(function(data) {
                console.log('replies...', data);

                var renderHtml = data.length > 0 ? data.map(entry => {
                    return `<div class="reply-section  mt-3 p-2 mr-4 rounded d-flex justify-content-start">
                                        <div class="reply-content text-left w-100">
                                            <div class="d-flex align-items-center justify-content-start">
                                                <img  src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" alt="avatar" class="rounded-circle me-3"  style="width: 40px; height: 40px;">
                                                <div class="ml-2">
                                                    <h6 class="mb-0">${entry.full_name}</h6>
                                                    <small class="text-muted">${moment(entry.createddatetime, "YYYY-MM-DD HH:mm:ss").fromNow()}</small>
                                                </div>
                                                
                                            </div>
                                            <div class="reply-body mt-2 ml-5 text-start rounded p-2" style="background-color: #f8f9fa;">
                                                <p>${entry.message === "Like" ? '<i class="fas fa-thumbs-up me-3 text-primary" style="font-size: 1.5rem;"></i>' : entry.message }</p>
                                            </div>
                                        </div>
                                    </div>`;

                }).join('') : ``;
                $('#reply-section' + id).html(renderHtml);
            })


        }


        function renderimage(data) {

            data.forEach(dataentry => {
                var renderHtml = dataentry.img.length > 0 ? dataentry.img.map(entry => {
                    return `<div class="col-md-4">
                                    <img src="{{ asset('${entry.fileurl}') }}" class="w-100" style="width: 150px; height: 150px; object-fit: cover;"/>
                                </div>`;
                }).join('') : ``;

                $('#image-holder' + dataentry.id).html(renderHtml);
            })






        }


        function renderReply(data) {
            console.log('replies...', data);
            data.forEach(dataentry => {
                var renderHtml = dataentry.replies.length > 0 ? dataentry.replies.map(entry => {
                    return `<div class="reply-section  mt-3 p-2 mr-4 rounded d-flex justify-content-start">
                                <div class="reply-content text-left w-100">
                                    <div class="d-flex align-items-center justify-content-start">
                                        <img  src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" alt="avatar" class="rounded-circle me-3"  style="width: 40px; height: 40px;">
                                        <div class="ml-2">
                                            <h6 class="mb-0">${entry.full_name}</h6>
                                            <small class="text-muted">${moment(entry.createddatetime, "YYYY-MM-DD HH:mm:ss").fromNow()}</small>
                                        </div>
                                        
                                    </div>
                                    <div class="reply-body mt-2 ml-5 text-start rounded p-2" style="background-color: #f8f9fa;">
                                        <p>${entry.message === "Like" ? '<i class="fas fa-thumbs-up me-3 text-primary" style="font-size: 1.5rem;"></i>' : entry.message }</p>
                                    </div>
                                </div>
                            </div>`;
                }).join('') : ``;

                $('#reply-section' + dataentry.id).html(renderHtml);
            })





        }

        function renderUsers() {
            getUsers().then(function(data) {
                console.log('USERLISTS...', data);

                $('#recipientEmail').select2({
                    data: data,
                    theme: 'bootstrap4',
                });

                var renderHtml = data.length > 0 ? data.map(entry => {
                    // Check if totalCurrentMsg exists and is greater than zero
                    let badgeHtml = entry.totalCurrentMsg > 0 ?
                        `<span class="badge badge-primary ml-auto">${entry.totalCurrentMsg}</span>` : '';
                    let nameHtml = entry.totalCurrentMsg > 0 ? `<strong>${entry.full_name}</strong>` : entry
                        .full_name; // Conditionally bold the name
                    let styleHtml = entry.totalCurrentMsg > 0 ? `style="background-color: #f5f5f5;"` :
                        ``; // Conditionally light gray the item

                    return `
                    <li class="d-flex align-items-center justify-content-between p-2 userClick hover-light-gray" 
                        data-id="${entry.id}" ${styleHtml}>
                        <div class="d-flex align-items-center">
                            <img class="rounded-circle me-2" src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" 
                                onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" 
                                alt="avatar" style="width: 40px; height: 40px;">
                            <div class="d-flex flex-column ml-2">
                                <p class="mb-0 empname">${nameHtml}</p>
                                <p class="mb-0 text-muted" style="font-size: .8rem;">${entry.designation}</p>
                            </div>
                        </div>
                        ${badgeHtml}
                    </li>
                    `;
                }).join('') : `<li class="d-flex align-items-center mt-3">No user Found</li>`;

                $('#users_div_holder').html(renderHtml);

                // Add click event listener to list items
                $('#users_div_holder').on('click', '.userClick', function() {
                    // Remove the 'selected' class from other items
                    $('.userClick').removeClass('selected');
                    // Add the 'selected' class to the clicked item
                    $(this).addClass('selected');
                    // If the item has a style attribute, remove it
                    if ($(this).attr('style')) {
                        $(this).removeAttr('style');
                    }
                    // Make the badge disappear
                    $(this).find('.badge').remove();
                    // Make the full name not bold
                    $(this).find('.empname').css('font-weight', 'normal');
                });
            });
        }


        function renderAllMessages() {

            getAllMessages().then(function(data) {
                console.log('Messages', data);

                var renderHtml = data.length > 0 ? data.map(entry => {
                    return `<div class="card shadow-none border-0 mb-3 message-container userClick " data-id="${entry.data_id}">
                        <div class="card-body p-3 d-flex align-items-center">
                            <img class="rounded-circle me-3" src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" 
                                onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" 
                                alt="avatar" style="width: 50px; height: 50px; object-fit: cover;">
                            <div class="d-flex flex-column flex-grow-1 ml-2">
                                <h6 class="card-subtitle mb-1 fw-bold text-primary">${entry.name}</h6>
                                <p class="card-text mb-1 text-secondary">${entry.additionalmessage}</p>
                            </div>
                            <p class="card-text mb-0 text-muted"><small>${moment(entry.createddatetime, "YYYY-MM-DD HH:mm:ss").fromNow()}</small></p>
                        </div>
                    </div>`;
                }).join('') : `<div class="text-center p-5"><p class="text-muted">No message found</p>`;
                $('#message_holder').html(renderHtml);



            })

        }

        $(document).on('input', '#user_search', function() {

            renderUsers();
        })

        $(document).on('click', '.userClick', function() {
            var id = $(this).data('id');
            console.log(id);

            renderMessages(id);

            $('#sendnotification').data('id', id);
            $('#profile_holder').show()
            getUsers(id).then(function(data) {

                $('#messageCount').text(data[0].messageCount);
                $('#concernCount').text(data[0].concernCount);
                $('#requestCount').text(data[0].requestCount);
                $('#name_user').text(data[0].full_name);
                $('#department_user').text(data[0].designation);
                if (data[0].picurl == null) {
                    $('#profilepic').attr('src', '{{ asset('dist/img/download.png') }}');
                } else {
                    $('#profilepic').attr('src', `/${data[0].picurl}`);
                    $('#profilepic').attr('onerror',
                        "this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'");
                }



            })
        })

        $(document).on('click', '#backButton', function() {
            renderAllMessages();
        })

        $(document).on('click', '#sendnotification', function() {

            var id = $(this).data('id');
            console.log(id);

            $('#recipientEmail').val(id).trigger('change');
            $('#composeModal').modal('show');
        })

        $(document).on('click', '#newMessage', function() {

            $('#composeModal').modal('show');
        })


        $(document).on('click', '#sendMessage', function() {
            var valid_data = true;
            var recipientid = $('#recipientEmail').val();
            var subject = $('#emailSubject').val();
            var additionalmessage = $('#emailBody').val();
            var messagettype = $('#messageType').val();
            var sendByDepartment = $('#sendByDepartment').is(':checked');


            console.log(sendByDepartment);

            var formData = new FormData();
            formData.append('subject', subject);
            formData.append('recipientid', recipientid);
            formData.append('additionalmessage', additionalmessage);
            formData.append('messageType', messagettype);
            var imageArray = [];


            $('.files').each(function() {
                var value = $(this).val();
                imageArray.push({
                    value: value
                });
            });

            formData.append('imageArray', JSON.stringify(imageArray));


            if (sendByDepartment == true) {
                formData.append('sendByDepartment', sendByDepartment);
            }


            if (recipientid == null) {
                Toast.fire({
                    type: 'error',
                    title: 'Please select recipient'
                })

                valid_data = false;

            }

            if (subject.replace(/^$/, '') == '') {
                Toast.fire({
                    type: 'error',
                    title: 'Subject is required'
                })
                valid_data = false;
            }

            if (additionalmessage.replace(/^$/, '') == '') {
                Toast.fire({
                    type: 'error',
                    title: 'Message is required'
                })
                valid_data = false;
            }



            if (valid_data) {

                Swal.fire({
                    title: 'Please wait...',
                    html: `
        <div class="row" style="justify-content: center !important; display: grid !important;">
            <div class="loader1holder2">
                <div class="loader2">
                    <img src="{{ asset('assets/images/plane.gif') }}" alt="Loading..." style="width: 150px; height: 150px;">    
                </div>
            </div>
            <div class="loaderholder">
                <div class="loader"></div>
            </div>
            
            <div class="note"><small><strong>Please wait a moment while the process completes.</strong></small></div>
        </div>`,
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                $.ajax({
                    type: "POST",
                    url: "/hr/settings/notification/sendnotificationv2",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        Swal.close(); // Close the Swal loading modal
                        renderAllMessages(); // Optional: Refresh messages or update the UI

                        if (data[0].status == 0) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            });
                        } else {
                            $('#composeModal').modal('hide'); // Hide modal after successful submission
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close(); // Close the Swal loading modal on error
                        Toast.fire({
                            type: 'error',
                            title: 'An error occurred while sending the notification.'
                        });
                        console.error('AJAX error:', error); // Log the error for debugging
                    }
                });
            }


        })



        $(document).on('click', '.sendReply', function() {

            var id = $(this).data('id');
            var message = $('#input-reply' + id).val();

            var formData = new FormData();
            formData.append('messageid', id);
            formData.append('message', message);

            var valid_data = true;

            if (message.replace(/^$/, '') == '') {
                valid_data = false;
            }

            if (valid_data) {

                $.ajax({
                    type: "POST",
                    url: "/hr/settings/notification/sendReply",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(data) {
                        renderReply_submit(id);
                        Toast.fire({
                            type: 'success',
                            title: data[0].message
                        })
                        $('#input-reply' + id).val('');
                    }
                });


            }



        });


        $(document).on('click', '.sendLike', function() {

            var id = $(this).data('id');
            var message = 'Like';

            var formData = new FormData();
            formData.append('messageid', id);
            formData.append('message', message);

            var valid_data = true;


            if (valid_data) {

                $.ajax({
                    type: "POST",
                    url: "/hr/settings/notification/sendReply",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(data) {
                        renderReply_submit(id);
                        Toast.fire({
                            type: 'Success',
                            title: data[0].message
                        })
                    }
                });


            }



        });


        $(document).on('click', '#sendByDepartment', function() {

            if (!$('#sendToAll').is(':checked')) {
                if ($(this).is(':checked')) {

                    getDepartment().then(function(data) {
                        $('#recipientEmail').empty();

                        $('#recipientEmail').select2({
                            data: data,
                            theme: 'bootstrap4',
                        });
                    });

                } else {

                    getUsers().then(function(data) {
                        $('#recipientEmail').empty();
                        $('#recipientEmail').select2({
                            data: data,
                            theme: 'bootstrap4',
                        });
                    });

                }
            }




        });


        $(document).on('click', "#attachfile", function() {
            $("#image").click();
        });

        $(document).on('change', '#image', function() {

            var fileInput = $(this)[0];
            var file = fileInput.files[0];
            var id = $(this).data('id');


            // AJAX request to upload the file
            var formData = new FormData();
            formData.append('file', file);

            $.ajax({
                url: '/hr/settings/notification/uploadattachedfile',
                type: 'POST',
                data: formData,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                processData: false,

                success: function(response) {


                    $('#previewImageHolder').show();
                    $('#previewImage').append(
                        `<div class="col-md-4">
                            <img src="{{ asset('${response.url}') }}" class="img-thumbnail w-100" width="40" height="40"/>
                            <input type="hidden" class="files" value="${response.url}">
                        </div>`
                    );


                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while uploading the file. Please try again later.');
                    // Hide the progress bar
                    $('.progress' + id).hide();
                }
            });



        });


        $(document).on('click', '#sendToAll', function() {

            if ($(this).is(':checked')) {

                if (!$('#recipientEmail option[value="All"]').length) {
                    $('#recipientEmail').prepend('<option value="All">All</option>');
                }
                $('#recipientEmail').val('All').trigger('change');
                $('#recipientEmail').prop('disabled', true);

            } else {

                if (!$('#recipientEmail option[value="All"]').length) {
                    $('#recipientEmail').prepend('<option value="All">All</option>');
                }
                $('#recipientEmail').val('').trigger('change');
                $('#recipientEmail').prop('disabled', false);

            }

        });





        $(document).ready(function() {

            if (id != '0') {
                renderMessages(id);
            } else {
                renderAllMessages();
            }
            renderUsers();


        })
    </script>
@endsection
