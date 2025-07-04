@extends('admin.layouts.master')

@section('title', 'Messages - ' . $user->name)

@section('content')
<style>
    .chat-box {
        max-height: 500px;
        min-height: 500px;
        overflow-y: auto;
        padding: 20px;
        background: #f9f9f9;
        border-radius: 10px;
    }

    .message {
        max-width: 60%;
        word-wrap: break-word;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 15px;
        position: relative;
        display: inline-block;
    }

    .message.admin {
        margin-left: auto;
        background: #0d6efd;
        color: white;
        text-align: right;
    }

    .message.user {
        margin-right: auto;
        background: #e0f7fa;
        color: #333;
        text-align: left;
    }

    .timestamp {
        font-size: 11px;
        margin-top: 5px;
        opacity: 0.7;
        display: block;
    }

    #messages-container {
        display: flex;
        flex-direction: column;
    }

    /* For responsiveness */
    @media (max-width: 768px) {
        .message {
            max-width: 85%;
        }

        .chat-box {
            padding: 10px;
        }
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Chat with {{ $user->name }}</h5>
        <a href="{{ route('admin.viewusers') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>

    <div class="card-body">
        <div class="chat-box" id="chatBox">
            <div id="messages-container"></div>
        </div>

        <form id="messageForm">
            @csrf
            <textarea name="message_content" id="message_content" rows="2" class="form-control mt-3" placeholder="Type your message..." style="resize: none;"></textarea>
            <div class="text-end mt-2">
                <button class="btn btn-primary">Send</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
$(document).ready(function () {
    function convertUtcToLocal(utcDateTime) {
        const utcDate = new Date(utcDateTime + ' UTC');
        return moment(utcDate).fromNow();
    }

    function loadMessages() {
        $.ajax({
            url: "{{ route('admin.fetchMessages', ['id' => $user->id]) }}",
            type: "GET",
            success: function (response) {
                $('#messages-container').empty();
                response.messages.forEach(function (msg) {
                    const isAdmin = msg.sender_id === {{ auth()->id() }};
                    const time = convertUtcToLocal(msg.sent_at);
                    const html = `
                        <div class="message ${isAdmin ? 'admin' : 'user'}">
                            ${msg.message}
                            <span class="timestamp">${time}</span>
                        </div>
                    `;
                    $('#messages-container').append(html);
                });
                $('#chatBox').scrollTop($('#chatBox')[0].scrollHeight);
            },
            error: function () {
                $('#messages-container').html('<p>Error loading messages.</p>');
            }
        });
    }

    loadMessages();
    setInterval(loadMessages, 60000);
	
	
	var channel = pusher.subscribe('my-channel');
	  channel.bind('MessageSent', function(data) {
		console.log('Received data:', data);
		loadMessages();
	  });

    $('#messageForm').on('submit', function (e) {
        e.preventDefault();
        const message = $('#message_content').val().trim();
        if (!message) return;

        $.ajax({
            url: "{{ route('admin.messageSave', ['id' => $user->id]) }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message_content: message
            },
            success: function () {
                $('#message_content').val('');
                loadMessages();
            },
            error: function (xhr, status, error) {
				console.error('AJAX Error:', {
					status: status,
					error: error,
					response: xhr.responseText
				});

				// Show a user-friendly message
				// alert('Failed to send message. Please check the console for more details.');
			}
        });
    });
});
</script>
@endsection