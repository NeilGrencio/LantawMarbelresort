<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lantaw-Marbel Resort - Inquiry Logs</title>
    <link rel="icon" href="{{ asset('favico.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <!-- Left Sidebar: Guest List -->
            <div class="chat-list-container">
                <h1>Inquiry Logs</h1>
                <div class="chat-list">
                    @foreach ($chats as $guestID => $messages)
                        @php
                            $latest = $messages->last();
                        @endphp
                        <div class="chat-list-card" data-guest="{{ $guestID }}"
                            data-gavatar="{{ $latest->g_avatar }}" data-gname="{{ $latest->g_fullname }}"
                            onclick="selectMessage(this)" data-messages='@json($messages)'>
                            <img class="profile-picture" src="{{ asset('storage/' . $latest->g_avatar) }}" />
                            <div class="chat-info">
                                <h3>{{ $latest->g_fullname }}</h3>
                                <p>{{ Str::limit($latest->chat, 35) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Chat Area -->
            <div class="chat-area">
                <div class="chat-header">
                    <h2>Select a conversation</h2>
                </div>

                <div id="no-message-prompt" class="no-message-prompt">
                    <i class="fa-regular fa-message fa-2x"></i>
                    <p>No conversation selected.<br>Select a guest from the left panel to view messages.</p>
                </div>

                <div id="chat-thread" class="chat-thread"></div>
                
                <form id="reply-form" method="POST" class="reply-form">
                    @csrf
                    <input type="hidden" name="guestID" id="guestID-hidden">
                    <div class="reply-send">
                        <textarea id="reply" name="reply" placeholder="Type a message..." required rows="1"></textarea>
                        <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        #inquiry {
            background: rgba(255,255,255,0.15);
            border-left: 4px solid #ff9100;
            color: white;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f7f8fa;
            margin: 0;
            height: 100vh;
        }

        #layout {
            display: flex;
            height: 100%;
            width:100%;
        }

        #main-layout {
            display: flex;
            width:100%;
            margin-left:14rem;
            gap: 1rem;
            padding: 1rem;
            overflow: hidden;
        }

        /* Left Sidebar */
        .chat-list-container {
            width: 22rem;
            background: #fff;
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .chat-list-container h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .chat-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .chat-list-card {
            display: flex;
            align-items: center;
            padding: 0.75rem;
            background: #f5f5f5;
            border-radius: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .chat-list-card:hover {
            background: #f78a21;
            color: white;
        }

        .chat-list-card.active {
            background: #10012e;
            color: white;
        }

        .profile-picture {
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.75rem;
        }

        .chat-info h3 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .chat-info p {
            margin: 0.2rem 0 0 0;
            font-size: 0.85rem;
            color: #666;
        }

        /* Right Chat Area */
        .chat-area {
            flex: 1;
            background: #fff;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .chat-header {
            background: #f78a21;
            color: white;
            padding: 1rem;
            font-size: 1.2rem;
            font-weight: 600;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .chat-thread {
            flex: 1;
            padding: 1rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            background: #f7f8fa;
        }

        .guest-msg {
            background: #e1e1e1;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            align-self: flex-start;
            max-width: 70%;
        }

        .staff-msg {
            background: #d1e7ff;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            align-self: flex-end;
            max-width: 70%;
        }

        .reply-send {
            display: flex;
            flex-direction: row;
            width: 100%;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            align-items: flex-end;
        }

        .reply-send textarea {
            width: 100%;
            padding: 0.7rem;
            border-radius: 0.7rem;
            border: 2px solid black;
            resize: none;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            line-height: 1.4rem;
        }

        .reply-send button {
            flex-shrink: 0;
            background: #F78A21;
            border-radius: 0.7rem;
            padding: 0.7rem 1rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reply-send button:hover {
            background: grey;
            color: white;
        }


        /* Scrollbar Modern */
        .chat-list::-webkit-scrollbar,
        .chat-thread::-webkit-scrollbar {
            width: 8px;
        }

        .chat-list::-webkit-scrollbar-thumb,
        .chat-thread::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .chat-list::-webkit-scrollbar-track,
        .chat-thread::-webkit-scrollbar-track {
            background: transparent;
        }
        .no-message-prompt {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #888;
            font-size: 1rem;
            text-align: center;
            padding: 2rem;
        }

        .no-message-prompt i {
            margin-bottom: 1rem;
            color: #f78a21;
        }
    </style>

    <script>
        function selectMessage(element) {
            const guestID = element.getAttribute('data-guest');
            const guestName = element.getAttribute('data-gname');
            const guestAvatar = element.getAttribute('data-gavatar');
            const messages = JSON.parse(element.getAttribute('data-messages'));

            const replyTextarea = document.getElementById('reply');

            replyTextarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });

            // Hide placeholder and show reply form
            document.getElementById('no-message-prompt').style.display = 'none';
            document.getElementById('reply-form').style.display = 'flex';

            const chatHeader = document.querySelector('.chat-header');
            chatHeader.innerHTML = `
                <img class="profile-picture" src="/storage/${guestAvatar}">
                <h2>${guestName}</h2>
            `;

            const chatThread = document.getElementById('chat-thread');
            chatThread.innerHTML = "";
            messages.forEach(msg => {
                const alignClass = msg.reply === "guest" ? "guest-msg" : "staff-msg";
                chatThread.innerHTML += `
                    <div class="${alignClass}">
                        <p><strong>${msg.reply === "guest" ? msg.g_fullname : msg.s_fullname}</strong></p>
                        <p>${msg.chat}</p>
                        <small>${msg.formatted_datesent}</small>
                    </div>
                `;
            });
            chatThread.scrollTop = chatThread.scrollHeight;

            document.getElementById('guestID-hidden').value = guestID;

            document.querySelectorAll('.chat-list-card').forEach(card => card.classList.remove('active'));
            element.classList.add('active');
        }

    </script>
</body>
</html>
