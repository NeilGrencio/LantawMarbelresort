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
        @include('components.sidebar')
        <div id="main-layout">
            <div class="chat-list-container">
                <h1>Inquiry Logs</h1>
                <div class="chat-list">
                    @foreach($chat as $chats)
                        <div class="chat-list-card"
                        data-chatID="{{$chats->chatID}}"
                        
                        data-guest="{{$chats->gID}}" 
                        data-gavatar="{{$chats->g_avatar}}"
                        data-gname="{{$chats->g_fullname}}" 
                        data-chat="{{$chats->chat}}" 
                        data-chatdate="{{$chats->datesent}}"

                        data-staff="{{$chats->sID}}"
                        data-savatar="{{$chats->s_avatar}}"
                        data-sname="{{$chats->s_fullname}}" 
                        data-reply="{{$chats->reply}}" 
                        data-replydate="{{$chats->datereplied}}"

                        onclick="selectMessage(this)">

                            <img id="profile-picture" src="{{asset('storage/' . $chats->g_avatar)}}"/>
                            <div id="chat-information">
                                <h2>{{$chats->g_fullname}}</h2>
                                <p>{{$chats->chat}}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>            
            <div class="chat-area">
                @php $latestchat = $chat->first(); @endphp
                <div class="chat-header">
                    <img id="profile-picture" src="{{asset('storage/' . $latestchat->g_avatar)}}">
                    <h2>{{$latestchat->g_fullname}}</h2>
                    <div id="view-container" data-url="{{url('manager/view_guest/' . $latestchat->gID)}}">
                        <p id="view-text">View Profile</p>
                        <i class="fa-solid fa-chevron-right fa-2x"></i>
                    </div>
                </div>
                <div id="chat-card">
                    @if($latestchat)
                        <img id="profile-picture" src="{{asset('storage/' . $latestchat->g_avatar)}}"/>
                        <div >
                            <div id="sender-name">
                                <h3>{{ $latestchat->g_fullname }}</h3>
                            </div>
                            <div id="chat-details">
                                <p>{{ $latestchat->chat }}</p>
                                <p>{{ $latestchat->datesent }}</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div id="reply-card">
                    @if($latestchat->reply)
                        <div>
                            <div id="reply-name">
                                <h3>{{ $latestchat->s_fullname }}</h3>
                            </div>
                            <div id="reply-details">
                                <p>{{ $latestchat->reply }}</p>
                                <p>{{ $latestchat->datereplied }}</p>
                            </div>
                        </div>
                        <img id="profile-picture" src="{{ asset('storage/' . $latestchat->s_avatar)}}"/>
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
        margin-left:12rem;
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
        const gID = element.getAttribute('data-guest');
        const gavatar = element.getAttribute('data-gavatar');
        const name = element.getAttribute('data-gname');
        const chat = element.getAttribute('data-chat');
        const date = element.getAttribute('data-chatdate');
        const gimagePath = `/storage/${gavatar}`;

        const baseUrl = `{{ url('manager/view_guest') }}`;
        const urlViewProfile = `${baseUrl}/${gID}`;

        const sAvatar = element.getAttribute('data-savatar');
        const sName = element.getAttribute('data-sname');
        const sReply = element.getAttribute('data-reply');
        const sDate = element.getAttribute('data-replydate');
        const simagePath = `/storage/${sAvatar}`;

        const chatCard = document.getElementById('chat-card');
        const chatHeader = document.querySelector('.chat-header');
        const replyCard = document.getElementById('reply-card');

        const chatID = element.getAttribute('data-chatid');
        const form = document.getElementById('reply-form');
        const replynInput = document.getElementById('reply');

        const message = document.querySelector('.alert-message');

        // Auto-hide success/error message
        if (message) {
            setTimeout(() => {
                message.style.display = 'none';
            }, 3500);
        }

        const baseAction = `{{ url('manager/chat') }}`;
        if (form) {
            form.setAttribute('action', `${baseAction}/${chatID}`);
            document.getElementById('chatID-hidden').value = chatID;
        }



        if (chatHeader) {
            chatHeader.innerHTML = `
                <img id="profile-picture" src="${gimagePath}">
                <h2>${name}</h2>
                <div id="view-container" data-url="${urlViewProfile}">
                    <p id="view-text">View Profile</p>
                    <i class="fa-solid fa-chevron-right fa-2x"></i>
                </div>
            `;

            const viewProfile = chatHeader.querySelector('#view-container');
            if (viewProfile) {
                viewProfile.addEventListener('click', function () {
                    window.location.href = this.dataset.url;
                });
            }
        }

        if (chatCard) {
            chatCard.innerHTML = `
                <img id="profile-picture" src="${gimagePath}">  
                <div>
                    <div id="sender-name">
                        <h3>${name}</h3>
                    </div>
                    <div id="chat-details">
                        <p>${chat}</p>
                        <p>${date}</p>
                    </div>
                </div>
            `;
        }

        if (replyCard) {
            replyCard.innerHTML = `
            <div>
                <div id="reply-name">
                    <h3>${sName}</h3>
                </div>
                <div id="reply-details">
                    <p>${sReply}</p>
                    <p>${sDate}</p>
                </div>
            </div>
            <img id="profile-picture" src="${simagePath}"/>
        `;



            replyCard.style.display = "flex";
            replynInput.readOnly = true;
            replynInput.placeholder = 'Already replied!';
            replynInput.style.background = 'rgb(199, 199, 199)';
            if (sReply && sReply.trim() !== "") {
                replyCard.style.display = "flex";
                replynInput.readOnly = true;
                replynInput.placeholder = 'Already replied!';
                replynInput.style.background = 'rgb(199, 199, 199)';
            } else {
                replyCard.style.display = "none";
                replynInput.readOnly = false;
                replynInput.placeholder = 'Reply..';
                replynInput.style.background = 'white';
            }

        }

        // Highlight selected card
        document.querySelectorAll('.chat-list-card').forEach(card => {
            card.classList.remove('active');
        });
        element.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const firstCard = document.querySelector('.chat-list-card');
        if (firstCard) {
            selectMessage(firstCard);
        }
    });

    
</script>