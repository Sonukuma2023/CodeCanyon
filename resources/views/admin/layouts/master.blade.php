<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>All In OneScript | One-Stop Shop for Ready-Made Scripts</title>
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/owl-carousel-2/owl.carousel.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('backend/assets/vendors/owl-carousel-2/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
	<style>
	.preview-thumbnail {
        
		position: relative;
	}

	.preview-thumbnail .bg-danger {
		width: 10px;
		height: 10px;
		top: 0;
		right: 0;
	}
	</style>
</head>

<body>
    <div class="container-scroller">

        @include('admin.layouts.navbar')

        @include('admin.layouts.sidebar')
        <div class="container-fluid page-body-wrapper">

            <div class="main-panel">
                <div class="content-wrapper">

                    @yield('content')

                </div>


            </div>


        </div>


    </div>
    <!-- container-scroller -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('backend/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendors/progressbar.js/progressbar.min.js') }}">
    </script>
    <script src="{{ asset('backend/assets/vendors/jvectormap/jquery-jvectormap.min.js') }}">
    </script>
    <script
        src="{{ asset('backend/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}">
    </script>
    <script src="{{ asset('backend/assets/vendors/owl-carousel-2/owl.carousel.min.js') }}">
    </script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script src="{{ asset('backend/assets/js/off-canvas.js') }}"></script>
    <script src="{{ asset('backend/assets/js/hoverable-collapse.js') }}"></script>
    <script src="{{ asset('backend/assets/js/misc.js') }}"></script>
    <script src="{{ asset('backend/assets/js/settings.js') }}"></script>
    <script src="{{ asset('backend/assets/js/todolist.js') }}"></script>
    <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>
    <script>
        @if(session('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000"
            };
            toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "5000"
            };
            toastr.error('{{ session('error') }}');
        @endif

        @if ($errors->any())
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "timeOut": "8000"
            };
            toastr.error("{!! implode('<br>', $errors->all()) !!}");
        @endif
    </script>

	<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
	<script>
		Pusher.logToConsole = true;
		var pusher = new Pusher('d6a1f321f4efde2a2722', {
			cluster: 'ap2'
		});
	</script>

	<script>
	function loadNotifications() {
		$.get("{{ route('admin.fetchNotifications') }}", function (res) {
			const list = $('#notification-list');
			const badge = $('#messageDropdown .count');

			list.find('.notification-item, .notification-footer').remove();

			let total = 0;
			let unreadCount = 0;

			if (res.notifications.length === 0) {
				list.append('<p class="p-3 mb-0 text-center notification-item">No new messages</p>');
			} else {
				res.notifications.forEach(function (notification) {
					const isUnread = notification.read_at === null;

					const html = `
						<a class="dropdown-item preview-item notification-item" href="${notification.url}">
							<div class="preview-thumbnail position-relative">
								<img src="{{ asset('backend/assets/images/faces/face4.jpg') }}" alt="image" class="rounded-circle profile-pic">
								${isUnread
									? '<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>'
									: ''
								}
							</div>
							<div class="preview-item-content">
								<p class="preview-subject ellipsis mb-1">${notification.content ?? 'New Notification'}</p>
								<p class="text-muted mb-0">${moment(notification.created_at).fromNow()}</p>
							</div>
						</a>
						<div class="dropdown-divider notification-item"></div>
					`;
					list.append(html);
					total++;
					if (isUnread) unreadCount++;
				});

				list.append(`
					<div class="text-center p-2 notification-footer">
						<a href="{{ route('admin.notifications') }}" class="text-primary fw-semibold text-decoration-none d-inline-flex align-items-center gap-1">
							View All <i class="mdi mdi-arrow-right"></i>
						</a>
					</div>
				`);

			}

			if (unreadCount > 0) {
				badge.text(unreadCount).show();
			} else {
				badge.hide();
			}
		});
	}

	$(document).ready(function () {
		loadNotifications();
		setInterval(loadNotifications, 60000);
		$('#messageDropdown').on('click', loadNotifications);
	});

	var channel = pusher.subscribe('my-channel');
	  channel.bind('NotificationSent', function(data) {
		console.log('Received data:', data);
		loadNotifications();
	});
	</script>



	@yield('scripts')
</body>

</html>
