@extends('user.layouts.master')
@section('title', 'Chat')
@section('content')
<style>
    .chat-wrapper {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 40px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
        min-height: 600px;
    }

    .chat-header {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .chat-box {
        max-height: 400px;
		min-height: 500px;
        overflow-y: auto;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .message {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 15px;
        position: relative;
        word-wrap: break-word;
        display: inline-block;
        clear: both;
    }

    .message.you {
        background-color: #dcf8c6;
        color: #333;
        float: right;
        text-align: right;
    }

    .message.other {
        background-color: #fff;
        border: 1px solid #ddd;
        color: #333;
        float: left;
        text-align: left;
    }

    .timestamp {
        display: block;
        font-size: 11px;
        color: #999;
        margin-top: 5px;
    }

    .chat-input textarea {
        resize: none;
    }
</style>

<div class="container">
    <div class="chat-wrapper">
        <div class="chat-header">Chat with Admin</div>
        <div class="chat-box" id="chatBox">
            <div id="messages-container"></div>
        </div>

        <form id="chatForm">
            @csrf
            <div class="chat-input">
                <textarea class="form-control" rows="3" name="message_content" id="message_content" placeholder="Type your message..."></textarea>
                <div class="text-end mt-2">
                    <button class="btn btn-primary">Send</button>
                </div>
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
            url: "{{ route('user.fetchMessages') }}",
            type: "GET",
            success: function (response) {
                $('#messages-container').empty();
                response.messages.forEach(function (msg) {
                    const isYou = msg.sender_id === {{ auth()->id() }};
                    const time = convertUtcToLocal(msg.sent_at);
                    const html = `
                        <div class="message ${isYou ? 'you' : 'other'}">
                            ${msg.message}
                            <span class="timestamp">${time}</span>
                        </div>
                        <div class="clearfix"></div>
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

    $('#chatForm').on('submit', function (e) {
        e.preventDefault();
        const message = $('#message_content').val();
        if (!message.trim()) return;

        $.ajax({
            url: "{{ route('user.messageSave') }}",
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message_content: message
            },
            success: function (response) {
                $('#message_content').val('');
                loadMessages();
            },
            error: function () {
                alert('Failed to send message.');
            }
        });
    });
});
</script>
@endsection
