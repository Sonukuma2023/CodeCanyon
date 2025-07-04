@extends('admin.layouts.master')
@section('title', 'All Notifications')

@section('content')
<style>
    .notifications-page-wrapper {
        max-height: 500px;
        overflow-y: auto;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .notifications-page-item {
        border: 1px solid #e9ecef;
        border-left: 5px solid #0d6efd;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 8px;
        background-color: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }

    .notifications-page-item.unread {
        border-left-color: #dc3545;
        background-color: #f1f3f5;
    }

    .notifications-page-time {
        font-size: 13px;
        color: #6c757d;
        margin-top: 6px;
    }

    .notifications-page-dot {
        width: 10px;
        height: 10px;
        background-color: #dc3545;
        border-radius: 50%;
        margin-left: 1rem;
        margin-top: 4px;
    }

    .mark-btn {
        margin-bottom: 15px;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
		<h5 class="mb-0">All Notifications</h5>
		<button id="markAllRead" class="btn btn-outline-primary btn-sm rounded-pill d-flex align-items-center gap-1 mark-btn">
			<i class="mdi mdi-check-all"></i> Mark All as Read
		</button>
	</div>


    <div class="card-body">
        <div class="notifications-page-wrapper" id="notificationBox">
            <p class="text-center">Loading notifications...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
$(document).ready(function () {
    function fetchNotifications() {
		$.get("{{ route('admin.allNotifications') }}", function(res) {
			const container = $('#notificationBox');
			container.empty();

			if (!res.allNotifications || res.allNotifications.length === 0) {
				container.html('<p class="text-center">No notifications found.</p>');
				return;
			}

			res.allNotifications.forEach(function(notification) {
				const isUnread = notification.read_at === null;

				const html = `
					<a href="${notification.url ?? '#'}" class="text-decoration-none">
						<div class="notifications-page-item ${isUnread ? 'unread' : ''} d-flex justify-content-between align-items-start">
							<div class="flex-grow-1">
								<strong class="text-dark">${notification.content ?? 'New Notification'}</strong>
								<div class="notifications-page-time">${moment(notification.created_at).fromNow()}</div>
							</div>
							${isUnread ? '<span class="notifications-page-dot ms-3 mt-1"></span>' : ''}
						</div>
					</a>
				`;
				container.append(html);
			});
		});
	}

    fetchNotifications();
	
	var channel = pusher.subscribe('my-channel');
	  channel.bind('NotificationSent', function(data) {
		console.log('Received data:', data);
		fetchNotifications();
	});

    $('#markAllRead').on('click', function () {
        $.post("{{ route('admin.markReadNotifications') }}", {
            _token: '{{ csrf_token() }}'
        }, function (res) {
            fetchNotifications();
			
			toastr.success(res.message, 'Success');
        });
    });
});
</script>
@endsection
