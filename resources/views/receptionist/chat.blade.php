<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>
    <div id="layout">
        @include('components.receptionist_sidebar')

        <div id="main-layout">
            <div class="chat-list-container">
                <h1>Inquiry Logs</h1>
                <div class="chat-list">
                    @foreach($chat as $chats)
                    @php
                        $g_avatar = $chats->g_avatar ?? null;
                        $g_fullname = $chats->g_fullname ?? 'Unknown Guest';
                        $g_avatar_url = $g_avatar ? asset('storage/' . $g_avatar) : asset('storage/profile.jpg');

                        $s_avatar = $chats->s_avatar ?? null;
                        $s_fullname = $chats->s_fullname ?? 'Staff';
                        $s_avatar_url = $s_avatar ? asset('storage/' . $s_avatar) : asset('storage/profile.jpg');
                    @endphp

                    <div class="chat-list-card"
                        data-chatID="{{ $chats->chatID }}"
                        data-guest="{{ $chats->gID }}" 
                        data-gavatar="{{ $g_avatar_url }}"
                        data-gname="{{ $g_fullname }}" 
                        data-chat="{{ $chats->chat }}" 
                        data-chatdate="{{ $chats->datesent }}"
                        data-staff="{{ $chats->sID }}"
                        data-savatar="{{ $s_avatar_url }}"
                        data-sname="{{ $s_fullname }}" 
                        data-reply="{{ $chats->reply }}" 
                        data-replydate="{{ $chats->datereplied }}"
                        onclick="selectMessage(this)">
                        
                        <img id="profile-picture" src="{{ $g_avatar_url }}"/>
                        <div id="chat-information">
                            <h2>{{ $g_fullname }}</h2>
                            <p>{{ $chats->chat }}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="chat-area">
                @php $latestchat = $chat->first(); @endphp

                @if($latestchat)
                    @php
                        $g_avatar = $latestchat->g_avatar ?? null;
                        $g_avatar_url = $g_avatar ? asset('storage/' . $g_avatar) : asset('storage/profile.jpg');
                        $g_fullname = $latestchat->g_fullname ?? 'Unknown Guest';

                        $s_avatar = $latestchat->s_avatar ?? null;
                        $s_avatar_url = $s_avatar ? asset('storage/' . $s_avatar) : asset('storage/profile.jpg');
                        $s_fullname = $latestchat->s_fullname ?? 'Staff';
                    @endphp

                    <div class="chat-header">
                        <img id="profile-picture" src="{{ $g_avatar_url }}">
                        <h2>{{ $g_fullname }}</h2>
                        <div id="view-container" data-url="{{ url('manager/view_guest/' . $latestchat->gID) }}">
                            <p id="view-text">View Profile</p>
                            <i class="fa-solid fa-chevron-right fa-2x"></i>
                        </div>
                    </div>

                    <div id="chat-card">
                        <img id="profile-picture" 
                             src="{{ $latestchat->g_avatar ? asset('storage/' . $latestchat->g_avatar) : asset('storage/profile.jpg') }}"/>
                        <div>
                            <div id="sender-name">
                                <h3>{{ $latestchat->g_fullname }}</h3>
                            </div>
                            <div id="chat-details">
                                <p>{{ $latestchat->chat }}</p>
                                <p>{{ $latestchat->datesent }}</p>
                            </div>
                        </div>
                    </div>

                    <div id="reply-card">
                        @if($latestchat->reply)
                            <div>
                                <div id="reply-name">
                                    <h3>{{ $s_fullname }}</h3>
                                </div>
                                <div id="reply-details">
                                    <p>{{ $latestchat->reply }}</p>
                                    <p>{{ $latestchat->datereplied }}</p>
                                </div>
                            </div>
                            <img id="profile-picture" src="{{ $s_avatar_url }}"/>
                        @endif
                    </div>

                    <form id="reply-form" method="POST">
                        @csrf
                        <input type="hidden" name="chatID" id="chatID-hidden" value="{{ $latestchat->chatID }}">
                        <div id="reply-send">
                            <input type="text" id="reply" name="reply" placeholder="Reply..." required readonly value="{{ old('reply') }}">
                            <button type="submit"><i class="fa-solid fa-paper-plane fa-lg"></i></button>
                        </div>
                    </form>
                @else
                    <div class="chat-header">
                        <img id="profile-picture" src="{{ asset('storage/profile.jpg') }}">
                        <h2>No Chats Yet</h2>
                    </div>
                    <div id="chat-card"><p>No messages to display.</p></div>
                @endif

                @if (session('success'))
                    <div class="alert-message">
                        <h2>{{ session('success') }}</h2>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert-message">
                        <h2>{{ session('error') }}</h2>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
<style>
 #inquiry { color: #F78A21;}
    body{overflow-y: auto;}
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction:row;
        width:100%;
        height: auto;
        padding:.5rem;
        margin-left:15rem;
        gap:1rem;   
    }
    #title-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        max-height:5rem;
        padding:1rem;
        border-radius: 2rem;
        align-content: center;
        align-items: center;
        gap: 1rem;
    }
    #title-header h1 {
        display: flex;
        align-items: center;
    }
    #view-container {
        display: flex;
        align-items: center;
        position: relative;
        margin-left:auto;
        margin-right:1rem;
        cursor:pointer;
    }

    #view-text {
        opacity: 0;
        visibility: hidden;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
        transition: all 0.3s ease;
        padding: 0.3rem 0.6rem;
        margin-left: 0.5rem;
        border-radius: 5px;
    }

    #view-container:hover #view-text {
        opacity: 1;
        visibility: visible;
        width: auto;
    }
    .chat-list-container{
        height:100%;
        width:20rem;
        display:flex;
        flex-direction:column;
        background:white;
        border-radius:.7rem;
        box-shadow:.1rem .2rem 0 rgba(0,0,0,0.2);
        padding:1rem;
    }
    .chat-list{
        height:100%;
        width:100%;
        display:flex;
        flex-direction:column;
        gap:1rem;
        overflow-y:auto;
    }
    .chat-list-card{
        height:5rem;
        width:100%;
        display:flex;
        flex-direction:row;
        background:rgb(245, 245, 245);
        padding:.5rem;
        gap:.5rem;
        cursor:pointer;
        transition:all .3s ease;
    }
    .chat-list-card:hover{
        background:rgb(165, 165, 165);
        color:white;
    }
    #profile-picture{
        width:3rem;
        height:3rem;
        border-radius:50%;
        border:solid 1px black;
        display:flex;
        align-self:center;
        object-fit: cover;
        background:grey;
    }
    #chat-information{
        display:flex;
        flex-direction:column;
        width:100%;
        height:100%;
        font-size:.7rem;
        gap:.2rem;
    }
    #chat-information p{
        margin-top:-1rem;
    }
    .chat-list-card.active {
        background-color: #10012e;
        color: white;
    }
    .chat-area{
        width:100%;
        height:100%;
        border-radius:.7rem;
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        background:#ffffff;
        display:flex;
        flex-direction:column;
        align-items:center;
        gap:1rem;
    }
    #chat-card{
        background:rgb(42, 109, 255);
        display: flex;
        flex-direction: row;
        width:60%;
        border-radius:.7rem;
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        padding:1rem;
        gap:1rem;
        align-self:start;
        margin-top:auto;
        bottom:1;
        margin-left:1rem;
    }
    #reply-send{
        display:flex;
        flex-direction:row;
        width:100%;
        height:5rem;
        padding:.7rem;
        gap:1rem;
    }
    form{
        width:100%;
    }
    input{
        width:100%;
        padding:.7rem;
        border-radius:.7rem;
        background:white;
        border:solid 2px black;
    }
    button{
        width:7rem;
        background:none;
        border:none;
        cursor:pointer;
        background:#F78A21;
        border-radius:.7rem;
        transition:all .3s ease;
    }
    button:hover{
        background:grey;
        color:white;
    }
    .chat-header{
        display:flex;
        flex-direction:row;
        width:100%;
        background:black;
        gap:1rem;
        padding:1rem;
        color:white;
        border-top-right-radius:.7rem;
        border-top-left-radius:.7rem;
        align-items:center;
    }
    #reply-card{
        background:rgb(0, 95, 13);
        color:white;    
        display: none;
        flex-direction: row;
        width:60%;
        border-radius:.7rem;
        box-shadow:.2rem .2rem 0 rgba(0,0,0,0.2);
        padding:1rem;
        gap:1rem;
        align-self:end;
        align-items:end;
        justify-content: end;
        margin-right:1rem;
    }
    #reply-name,
    #reply-details {
        display:flex;
        width:100%;
        flex-direction:column;
        justify-content:end;
        align-items:end;
    }
    .alert-message{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: fixed;
        right: 50%;
        transform: translate(50%, 0);
        bottom: 1rem;
        height: fit-content;
        min-height: 10rem;
        max-height: 30rem;
        width: fit-content;
        min-width: 20rem;
        max-width: 90vw;
        background: rgb(255, 255, 255);
        z-index: 1000;
        border-radius: 1rem;
        box-shadow: 0 0 1rem rgba(0,0,0,0.5);
        margin: auto;
        padding: 1rem;
        flex-wrap: wrap;
        word-wrap: break-word;
    }
</style>
<script>
    function selectMessage(element) {
            const guestName = element.getAttribute('data-gname');
            const guestAvatar = element.getAttribute('data-gavatar') || '/storage/profile.jpg';
            const guestMessage = element.getAttribute('data-chat');
            const chatDate = element.getAttribute('data-chatdate');

            const staffName = element.getAttribute('data-sname');
            const staffReply = element.getAttribute('data-reply');
            const staffAvatar = element.getAttribute('data-savatar') || '/storage/profile.jpg';
            const replyDate = element.getAttribute('data-replydate');
            const chatID = element.getAttribute('data-chatID');

            // Update Guest Chat Info
            document.querySelector('.chat-header h2').textContent = guestName;
            document.querySelector('.chat-header #profile-picture').src = guestAvatar;
            document.getElementById('chat-card').innerHTML = `
                <img id="profile-picture" src="${guestAvatar}"/>
                <div>
                    <div id="sender-name"><h3>${guestName}</h3></div>
                    <div id="chat-details">
                        <p>${guestMessage}</p>
                        <p>${chatDate}</p>
                    </div>
                </div>
            `;

            // Update Reply
            const replyCard = document.getElementById('reply-card');
            if (staffReply) {
                replyCard.innerHTML = `
                    <div>
                        <div id="reply-name"><h3>${staffName}</h3></div>
                        <div id="reply-details">
                            <p>${staffReply}</p>
                            <p>${replyDate}</p>
                        </div>
                    </div>
                    <img id="profile-picture" src="${staffAvatar}"/>
                `;
            } else {
                replyCard.innerHTML = ''; // Clear if no reply
            }

            // Set chatID hidden field
            document.getElementById('chatID-hidden').value = chatID;

            // Enable reply input
            document.getElementById('reply').removeAttribute('readonly');
        }
</script>