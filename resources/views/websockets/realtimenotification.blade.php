<style>
    .img-circle {
        border-radius: 50% !important;
    }

    .img-size-50 {
        width: 50px;
        height: 50px;
    }

    .bg-unseen {
        background-color: #ffb6c1 !important;
        border-radius: 0px !important;
    }
</style>

<div>

    @php
        $userID = auth()->user()->id;
        $usertype = Session::get('currentPortal');
    @endphp
</div>

{{-- <script src="{{ asset('/js/app.js') }}"></script> --}}
<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<script>
    var currentCount = 0;
    var unseenMsgCount = 0;
    var currentCountNotif = 0;
    var curCreatedDate = '';
    let requestInProgress = false;
    let requestInProgress2 = false;
    var userID = {!! json_encode($userID) !!};
    var usertype = {!! json_encode($usertype) !!};

    $(document).ready(function() {
        checkBellHolderExist();
        checkifMsgIconExist();
        getAllNotificationsv2();
        renderAllMessages2();
        renderAllMessagesStyles();
        console.log(userID);

        setInterval(() => {
            getAllNotificationsv2();
            renderAllMessages2();
        }, 5000);

    });

    function createClickableToastr(status, message, purpose, linkUrl, notifId) {
        console.log('taosting..');

        return new Promise((resolve, reject) => {
            toastr[status](
                `<div style="cursor: pointer; color: white;">${message}</div>`,
                purpose, {
                    allowHtml: true,
                    timeOut: 10000,
                    onclick: async () => {
                        try {
                            await notifIsRead(
                                notifId
                            ); // Wait for the notification to be marked as read

                            if (!linkUrl || typeof linkUrl !== 'string' || linkUrl.trim() === '' ||
                                linkUrl.startsWith('http') || linkUrl.startsWith('mailto:')) {
                                return resolve();
                            }

                            let newlink = linkUrl.startsWith('/') ? linkUrl : '/' + linkUrl;

                            window.location.href =
                                `${window.location.origin}${newlink}`; // Redirect after completion
                            resolve();

                        } catch (error) {
                            console.error(
                                "Error marking notification as read:",
                                error);
                            toastr.error(
                                "Failed to mark as read, please try again."
                            );
                            reject(error);
                        }
                    }
                }
            );
        });
    }

    function notifIsRead(notifId) {
        return $.ajax({
            type: "POST",
            url: "{{ route('notification.markAsRead') }}", // Use route for marking as read
            data: {
                id: notifId,
                _token: "{{ csrf_token() }}" // CSRF token for security
            },
            dataType: "json"
        });
    }

    function notifIsDisplayed() {
        return $.ajax({
            type: "GET",
            url: "{{ route('notification.markAsDisplayed') }}", // Use route for marking as displayed
            data: {
                _token: "{{ csrf_token() }}" // CSRF token for security
            },
            dataType: "json",
            success: function(response) {
                // console.log('Notifications marked as displayed');
            },
            error: function(xhr, status, error) {
                console.error('Error marking notifications as displayed:', error);
            },
        });
    }


    function checkBellHolderExist() {
        console.log('checking...');

        // Check if notification dropdown already exists
        if ($('#notificationBellHolder').length === 0) {
            console.log('notificationBellHolder does not exist');

            // Insert the notification dropdown structure before the comments dropdown
            $('.navbar-nav.ml-auto .nav-item.dropdown').first().before(`
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell text-white"></i>
                    <span class="badge badge-primary navbar-badge" id="notifbell_count">0</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationBellHolder">
                    <a href="/notificationv2/index" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li>
        `);
        }
    }

    function handleNotificationClick(notifId, linkUrl) {
        if (!linkUrl || typeof linkUrl !== 'string' || linkUrl.trim() === '' || linkUrl == 'null') {
            return;
        }
        notifIsRead(notifId)
            .then(() => {
                let newlink = linkUrl.startsWith('/') ? linkUrl : '/' + linkUrl;

                window.location.href = `${window.location.origin}${newlink}`;
            })
            .catch(error => {
                console.error("Error marking notification as read:", error);
            });
    }

    function getAllNotificationsv2() {
        console.log('loading notif...');

        const style = document.createElement('style');
        style.id = 'pace-custom-style'; // Add an ID for easier removal later
        style.innerHTML = `
            .pace {
                display: none !important; /* Forcefully hide */
            }
        `;
        document.head.appendChild(style);

        // Prevent sending multiple requests concurrently
        if (requestInProgress) {
            console.log("Request already in progress.");
            return;
        }

        requestInProgress = true; // Set flag to true while the request is in progress

        // Reset notification count
        $('#notification_count').text(0);
        var totalunseen = 0;
        const maxLength = 50; // Max character length for message
        const icons = ['fa-bell', 'fa-envelope', 'fa-info-circle', 'fa-check-circle', 'fa-exclamation-circle'];

        $.ajax({
            type: "GET",
            url: "{{ route('getallnotificationsv2') }}",
            data: {
                userid: userID
            },
            dataType: "json",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data, status, xhr) {
                if (xhr.status === 200) {
                    // console.log(data); // Log response for debugging
                    // data.notifications = []

                    totalunseen = data.notifications.filter(notification => notification.seen == 0).length;
                    const notifications = data.notifications.slice(0, 5);

                    const notifications2 = data.notifications // Limit to 5 notifications

                    notifications2.map(notification => {
                        const linkUrl = (usertype == 10 && notification.link ==
                                'hr/requirements/employee') ? 'hr/requirements/index' :
                            notification
                            .link;
                        if (notification.seen == 0 && notification.displayed == 0) {
                            createClickableToastr('info', notification.message, notification.about
                                .toUpperCase(), linkUrl, notification.id);
                        }
                    })

                    // Map notifications and generate HTML
                    const notificationHtml = notifications.length > 0 ? notifications.map(notification => {
                            const truncatedMessage = notification.message.length > maxLength ?
                                notification.message.substring(0, maxLength) + '...' : notification
                                .message;
                            const randomIcon = icons[Math.floor(Math.random() * icons.length)];
                            const linkUrl = (usertype == 10 && notification.link ==
                                    'hr/requirements/employee') ? 'hr/requirements/index' :
                                notification
                                .link;

                            return `
                        <a href="javascript:void(0);" onclick="handleNotificationClick(${notification.id}, '${linkUrl}')" class="media dropdown-item" style="display: flex; align-items: center; background-color: ${notification.seen == 0 ? '#d0e9fc' : 'transparent'};">
                            <i class="fas ${randomIcon} mr-3" style="color: #007bff; font-size: 1.5em;"></i>
                            <div class="media-body text-dark">
                                <h3 class="dropdown-item-title">${notification.about.toUpperCase() || 'Notification'}</h3>
                                <p class="text-sm">${truncatedMessage}</p>
                                <p class="text-sm text-muted">
                                    <i class="far fa-clock mr-1" style="color: gray;"></i>${moment(notification.created_at).locale('en-ph').fromNow()}
                                </p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                    `;
                        }).join('') :
                        `<div class="text-center"><p class="text-muted">No notifications found</p></div>`;

                    // Insert notifications and footer link into the DOM
                    const footerLink =
                        `<a href="{{ route('notificationv2.index') }}" class="dropdown-item dropdown-footer text-center">See All Notifications</a>`;
                    $('#notifbell_count').text(totalunseen);

                    if (data.notifications.length != currentCountNotif) {
                        console.log('rendering notif...');

                        $('#notificationBellHolder').html(notificationHtml + footerLink);

                        // Call after rendering notifications
                        notifIsDisplayed().done(function(response) {
                            // console.log('Notifications marked as displayed');
                        }).fail(function(error) {
                            console.error('Error marking notifications as displayed:', error);
                        });

                        currentCountNotif = data.notifications.length;
                    }

                    const noNotificationsFound = document.querySelector(
                        '#notificationBellHolder > div.text-center');
                    if (!noNotificationsFound && data.notifications.length == 0) {
                        $('#notificationBellHolder').prepend(
                            `<div class="text-center"><p class="text-muted mt-2">No notifications found</p></div>`
                        );
                    }

                } else {
                    console.error("Unexpected status code", xhr.status);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching notifications:", error);
            },
            complete: function() {
                // Reset Pace.js after request completion
                if (typeof Pace !== "undefined") {
                    $('.pace').show(); // Ensure the pace element is visible again
                }

                requestInProgress = false; // Reset the request flag
            }
        });
    }


    function renderAllMessagesStyles() {
        const notificationHolder = document.getElementById('notification_holder');

        if (notificationHolder) {
            const observer = new MutationObserver(() => {
                // Apply styles to dynamically added elements
                $('#notification_holder .media').css({
                    'padding': '1rem',
                    'display': 'flex',
                    'align-items': 'center',
                    'background-color': '#f8f9fa',
                    'border-radius': '8px',
                });
                $('#notification_holder .img-size-50').css({
                    'border': '2px solid #e2e3e5'
                });
                $('#notification_holder .dropdown-item-title').css({
                    'font-weight': '600',
                    'color': '#333'
                });
                $('#notification_holder .text-sm').css({
                    'color': '#555'
                });
                $('#notification_holder .text-sm.text-muted').css({
                    'font-size': '0.85em',
                    'color': '#888'
                });
            });

            // Start observing `#notification_holder` for child additions
            observer.observe(notificationHolder, {
                childList: true
            });
        } else {
            console.error("notification_holder element not found.");
        }
    }

    function getAllMessages2() {

        return $.ajax({
            type: "GET",
            data: {
                header: 1,
                purpose: 'notification',
            },
            url: "/hr/settings/notification/getAllMessages",
            complete: function() {
                requestInProgress2 = false; // Reset the request flag
            }
        });
    }

    function getAllMessages3() {

        return $.ajax({
            type: "GET",
            data: {
                header2: 1,
                purpose: 'notification',
            },
            url: "/hr/settings/notification/getAllMessages",
        });
    }

    function renderAllMessages2() {

        // Prevent sending multiple requests concurrently
        if (requestInProgress2) {
            console.log("Request already in progress.");
            return;
        }

        requestInProgress2 = true; // Set flag to true while the request is in progress
        // Fetch all messages
        getAllMessages2().then(function(data) {
            console.log('getAllMessages2...', data);
            var count = data.length;
            unseenMsgCount = data.filter(msg => msg.seen == 0).length;
            var createddate = data[0] ? data[0].createddatetime : '';
            // Render the messages
            var renderHtml = data.length > 0 ?
                data.slice(0, 5).map(entry => {
                    let unseenClass = entry.seen == 0 ? 'bg-unseen' : '';
                    if (entry.type && entry.type == 'reply') {
                        return `
                            <a class="media ${unseenClass}" href="javascript:void(0)" data-seen="${entry.seen}"
                            onclick="markMessageAsRead(${entry.id}, '${entry.data_id}', 'reply');"  >
                                <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" alt="User Avatar" 
                                    onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" 
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        REPLIED BY ${entry.name}
                                        <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">
                                        ${entry.message.length > 50 ? entry.message.substring(0, 50) + '...' : entry.message}
                                    </p>
                                    <p class="text-sm text-muted"><i class="far fa-clock mr-1" style="color:gray !important"></i>${ moment(entry.createddatetime).locale('en-ph').fromNow()}</p>
                                </div>
                            </a>
                        <div class="dropdown-divider"></div>`;
                    } else {
                        return `
                        <a class="media ${unseenClass}" href="javascript:void(0)" data-seen="${entry.seen}"
                        onclick="markMessageAsRead(${entry.id}, '${entry.data_id}');"  >
                            <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}" alt="User Avatar" 
                                onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'" 
                                class="img-size-50 img-circle mr-3">
                            <div class="media-body">
                                <h3 class="dropdown-item-title">
                                    ${entry.name}
                                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                                </h3>
                                <p class="text-sm">
                                    ${entry.additionalmessage.length > 50 ? entry.additionalmessage.substring(0, 50) + '...' : entry.additionalmessage}
                                </p>
                                <p class="text-sm text-muted"><i class="far fa-clock mr-1" style="color:gray !important"></i>${moment(entry.createddatetime).fromNow()}</p>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>`;
                    }
                }).join('') :
                `<div class="text-center"><p class="text-muted mt-2">No message found</p></div>`;
            const msgToast = data
            msgToast.map(notification => {
                const linkUrl = notification.link;
                if (notification.seen == 0 && notification.displayed == 0) {
                    if (notification.type && notification.type == 'reply') {
                        createClickableToastr2('success', notification.message, 'REPLIED BY ' +
                            notification.full_name, notification.id, notification
                            .data_id, 'reply');
                    } else {
                        createClickableToastr2('success', notification.additionalmessage, notification
                            .full_name, notification.id, notification.data_id);
                    }
                }
            })

            // Prepend the rendered messages
            if (count != currentCount || createddate != curCreatedDate) {
                $('#notification_holder').empty();
                $('#notification_holder').prepend(renderHtml);
                currentCount = data.length;
                curCreatedDate = createddate;
            }

            // Ensure "See All Messages" appears only once
            if ($('#notification_holder a.dropdown-footer').length === 0) {
                $('#notification_holder').append(
                    '<a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>'
                );
            }

            // Handle no messages
            if (data.length > 0) {
                const noMessageFound = document.querySelector('#notification_holder > div.text-center');
                if (noMessageFound) {
                    noMessageFound.remove();
                }
            } else {
                const noMessageFound = document.querySelector('#notification_holder > div.text-center');
                if (!noMessageFound) {
                    const noMessageText = document.createElement('div');
                    noMessageText.classList.add('text-center');
                    noMessageText.innerHTML = '<p class="text-muted mt-2">No message found</p>';
                    $('#notification_holder').prepend(noMessageText);
                }
            }

            msgIsDisplayed();

        });

        $('#notification_count').text(unseenMsgCount);
    }

    function msgIsDisplayed() {
        return $.ajax({
            type: "GET",
            url: "{{ route('messages.markAsDisplayed') }}", // Use route for marking as displayed
            data: {
                _token: "{{ csrf_token() }}" // CSRF token for security
            },
            dataType: "json",
            success: function(response) {
                // console.log('Notifications marked as displayed');
            },
            error: function(xhr, status, error) {
                console.error('Error marking messages as displayed:', error);
            },
        });
    }

    function createClickableToastr2(status, message, purpose, notifId, dataId, type = null) {
        console.log('taosting..');

        const linkUrl = `/hr/settings/notification/index?id=${dataId}`;


        return new Promise((resolve, reject) => {
            toastr[status](
                `<div style="cursor: pointer; color: white;">${message}</div>`,
                purpose, {
                    allowHtml: true,
                    timeOut: 10000,
                    onclick: async () => {
                        try {
                            await markMessageAsRead(
                                notifId, dataId, type
                            ); // Wait for the notification to be marked as read

                            if (!linkUrl || typeof linkUrl !== 'string' || linkUrl.trim() === '' ||
                                linkUrl.startsWith('http') || linkUrl.startsWith('mailto:')) {
                                return resolve();
                            }

                            let newlink = linkUrl.startsWith('/') ? linkUrl : '/' + linkUrl;

                            window.location.href =
                                `${window.location.origin}${newlink}`; // Redirect after completion
                            resolve();

                        } catch (error) {
                            console.error(
                                "Error marking notification as read:",
                                error);
                            toastr.error(
                                "Failed to mark as read, please try again."
                            );
                            reject(error);
                        }
                    }
                }
            );
        });
    }

    // Mark a message as read
    function markMessageAsRead(id, dataId, type = null) {
        $.ajax({
            type: "GET",
            url: "/hr/settings/notification/mark-as-read",
            data: {
                id: id,
                type: type
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            success: function(data) {
                console.log('markMessageAsRead..', data);

                if (data[0].status == 1) {
                    console.log(data.message);
                    window.location.href = `/hr/settings/notification/index?id=${dataId}`;
                } else {
                    console.error('Failed to mark as read.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    function checkifMsgIconExist() {
        const notificationHolder = document.querySelector('#notification_holder');

        if (!notificationHolder && !$('#notification_holder').length) {
            const newNotificationLi = `
            <li class="nav-item dropdown user user-menu">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-comments text-white"></i>
                            <span class="badge badge-danger navbar-badge" id="notification_count">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                            <div class="text-center mt-2"><p class="text-muted">No message found</p></div>
                            <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                </ul>
            </li>
        `;
            $('#notificationBellHolder').closest('li').after(newNotificationLi);
        }
    }
</script>
