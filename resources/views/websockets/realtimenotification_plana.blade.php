<style>
    .img-circle {
        border-radius: 50% !important;
    }

    .img-size-50 {
        width: 50px;
        height: 50px;
    }
</style>

<div>

    @php
        $userID = auth()->user()->id;
        $usertype = Session::get('currentPortal');
    @endphp
</div>

<script src="{{ asset('/js/app.js') }}"></script>
<script>
    var userID = {!! json_encode($userID) !!};
    var usertype = {!! json_encode($usertype) !!};

    $(document).ready(function() {
        renderAllMessages2();
        renderAllMessagesStyles();
        checkBellHolderExist();
        getAllNotificationsv2();
        console.log(userID);

        let notificationCount = 0; // Initialize notification count
        const icons = ['fa-bell', 'fa-envelope', 'fa-info-circle', 'fa-check-circle', 'fa-exclamation-circle'];

        if (typeof Echo !== 'undefined') {
            Echo.channel('notifications').listen('.NotificationEvent', e => {
                try {
                    // Log the event data
                    console.log(e);
                    const respo = e.response;

                    if (respo.receiver_id != userID) {
                        return;
                    }

                    // Define the link URL, which can be customized based on `respo` data
                    let linkUrl = (usertype == 10 && respo.link ===
                            'hr/requirements/employee') ?
                        'hr/requirements/index' : respo.link;

                    linkUrl = linkUrl || '#'; // Default to '#' if no link is provided

                    // Function to generate the toastr with clickable area
                    async function createClickableToastr(status, message, purpose, linkUrl, notifId) {
                        try {
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

                                            if (!linkUrl || linkUrl === '#') {
                                                return
                                            }
                                            window.location.href =
                                                `${window.location.origin}/${linkUrl}`; // Redirect after completion

                                        } catch (error) {
                                            console.error(
                                                "Error marking notification as read:",
                                                error);
                                            toastr.error(
                                                "Failed to mark as read, please try again."
                                            );
                                        }
                                    }
                                }
                            );
                        } catch (error) {
                            console.error("Error creating toastr notification:", error);
                            toastr.error("An error occurred displaying the notification.");
                        }
                    }


                    // Display the toastr notification based on `status`

                    if (respo.status === 'approved') {
                        createClickableToastr('success', respo.message, respo.purpose
                            .toUpperCase(),
                            linkUrl, respo.id);
                    } else if (respo.status === 'rejected') {
                        createClickableToastr('error', respo.message, respo.purpose
                            .toUpperCase(),
                            linkUrl, respo.id);
                    } else if (respo.status === 'returned') {
                        createClickableToastr('warning', respo.message, respo.purpose
                            .toUpperCase(),
                            linkUrl, respo.id);
                    } else {
                        createClickableToastr('info', respo.message, respo.purpose
                            .toUpperCase(),
                            linkUrl, respo.id);
                    }

                    if (respo.type == 'notification') {
                        // Increment the notification count and update badge
                        notificationCount++;
                        $('#notifbell_count').text(notificationCount);

                        getAllNotificationsv2();
                    } else {
                        renderAllMessages2();
                    }

                } catch (error) {
                    console.error("Error handling notification event:", error);
                    toastr.error("An error occurred processing the notification.");
                }
            });

        } else {
            console.warn("Echo is not defined. Notification listener not initialized.");
        }

    });

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

    function checkBellHolderExist() {
        console.log('checking...');

        // Check if notification dropdown already exists
        if ($('#notificationBellHolder').length === 0) {
            console.log('notificationBellHolder does not exist');

            // Insert the notification dropdown structure before the comments dropdown
            $('.navbar-nav.ml-auto .nav-item.dropdown').first().before(`
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
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
        if (!linkUrl) {
            return;
        }
        notifIsRead(notifId)
            .then(() => {
                window.location.href = `${window.location.origin}${linkUrl}`;
            })
            .catch(error => {
                console.error("Error marking notification as read:", error);
            });
    }

    function getAllNotificationsv2() {
        $('#notification_count').text(0);
        var totalunseen = 0;
        const maxLength = 50; // Max character length for message
        const icons = ['fa-bell', 'fa-envelope', 'fa-info-circle', 'fa-check-circle',
            'fa-exclamation-circle'
        ]; // Icon list

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
            success: function(data) {
                console.log(data); // Log response for debugging
                totalunseen = data.notifications.filter(notification => notification.seen == 0)
                    .length;
                const notifications = data.notifications.slice(0, 5); // Limit to 5 notifications
                // Create HTML based on notifications array or fallback message
                const notificationHtml = notifications.length > 0 ? notifications.map(notification => {
                        // Truncate message if it exceeds maxLength

                        const truncatedMessage = notification.message.length > maxLength ?
                            notification.message.substring(0, maxLength) + '...' :
                            notification.message;

                        // Select a random icon for each notification
                        const randomIcon = icons[Math.floor(Math.random() * icons.length)];

                        const linkUrl = (usertype == 10 && notification.link ==
                                'hr/requirements/employee') ?
                            'hr/requirements/index' :
                            notification.link;

                        return `
                            <a href="javascript:void(0);" onclick="handleNotificationClick(${notification.id}, '${linkUrl}')" 
                            class="media dropdown-item" 
                            style="display: flex; align-items: center; background-color: ${notification.seen == 0 ? '#d0e9fc  !important' : 'transparent'}; ">
                                <i class="fas ${randomIcon} mr-3" style="color: #007bff; font-size: 1.5em;"></i>
                                <div class="media-body text-dark">
                                    <h3 class="dropdown-item-title">${notification.about.toUpperCase() || 'Notification'}</h3>
                                    <p class="text-sm">${truncatedMessage}</p>
                                    <p class="text-sm text-muted">
                                        <i class="far fa-clock mr-1" style="color: gray !important;"></i>${moment(notification.created_at).locale('en-ph').fromNow()}
                                    </p>
                                </div>
                            </a>
                            <div class="dropdown-divider"></div>
                        `;

                    }).join('') :
                    `<div class="text-center"><p class="text-muted">No notifications found</p></div>`;

                // Append the footer link to "See All Notifications"
                const footerLink = `
                <a href="{{ route('notificationv2.index') }}" class="dropdown-item dropdown-footer text-center">
                    See All Notifications
                </a>
            `;
                $('#notifbell_count').text(totalunseen);
                $('#notificationBellHolder').html(notificationHtml + footerLink); // Insert into DOM
            },
            error: function(xhr, status, error) {
                console.error("Error fetching notifications:", error); // Error handling
            },
            complete: function() {
                console.log("AJAX request completed."); // Completion log
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
            },
            url: "/hr/settings/notification/getAllMessages",
        });
    }

    function getAllMessages3() {

        return $.ajax({
            type: "GET",
            data: {
                header2: 1,
            },
            url: "/hr/settings/notification/getAllMessages",
        });
    }

    function renderAllMessages2() {

        getAllMessages2().then(function(data) {


            var renderHtml = data.length > 0 ? data.map(entry => {
                return ` <a class="media" href="/hr/settings/notification/index?id=${entry.data_id}">
                <img src="/${entry.picurl ? entry.picurl : 'dist/img/download.png'}"  alt="User Avatar" onerror="this.onerror = null; this.src='{{ asset('dist/img/download.png') }}'"  class="img-size-50 img-circle mr-3">
                <div class="media-body">
                    <h3 class="dropdown-item-title">
                    ${entry.name}
                    <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                    </h3>
                    <p class="text-sm">
                    ${entry.additionalmessage.length > 50 ? entry.additionalmessage.substring(0, 50) + '...' : entry.additionalmessage}
                    </p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1" style="color:gray !important"></i>${entry.time_ago}</p>
                </div>
            </a>
            <div class="dropdown-divider"></div>`;

            }).join('') : `<div class="text-center"><p class="text-muted">No message found</p></div>`;
            $('#notification_holder').prepend(renderHtml);




        })


        getAllMessages3().then(function(data) {

            var count = data.length;
            $('#notification_count').text(count);

        })

    }
</script>
