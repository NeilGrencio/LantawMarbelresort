<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favico.ico')}}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favico.ico') }}">
    <title>Lantaw-Marbel Resort</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body>
    <body>
    <div id="layout">
        @include('components.receptionist_sidebar')
    <div id="main-layout">
        <div id="layout-header">
            <h1>Notifications</h1>
        </div>
        <div class="notification-container">
            <ul id="notificationList" class="notification-list"></ul>
        </div>
    </div>
</body>
<style>
    #layout{
        display: flex;
        flex-direction: row;
        height:100vh;
    }
    #main-layout{
        display:flex;
        flex-direction: column;
        padding:1rem;
        width:100%;
        transition: width 0.3s ease-in-out;
        margin-left:12rem;
        margin-right:.7rem;
        overflow-y: hidden;
        overflow-x: hidden;
    } 
    #layout-header{
        display: flex;
        flex-direction: row;
        width: 100%;
        height:4rem;
        padding:1rem;
        background:white;
        border-radius: .7rem;
        border:1px solid black;
        box-shadow:.1rem .1rem 0 black;
        align-items: center;
        justify-content: space-between; 
        gap: 1rem;
        font-size: .9rem;
    }
    .notification-container {
        padding: 20px;
        width:100%;
        height:100%;
        margin: 30px auto;
        overflow-y:auto;
    }

    .title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 15px;
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        background: #f9f9f9;
        border-left: 5px solid #c2410e;
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 10px;
        opacity: 0;
        transform: translateY(10px);
        transition: all 0.3s ease;
    }

    .notification-item.show {
        opacity: 1;
        transform: translateY(0);
    }

    .notification-item .type {
        font-weight: 600;
        color: #b9570d;
        margin-right: 10px;
    }

    .notification-item .message {
        flex: 1;
        color: #333;
    }

    .notification-item .timestamp {
        font-size: 0.8rem;
        color: #888;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('notificationList');

    async function loadNotifications() {
        try {
            const res = await fetch('/popUp');
            if (!res.ok) throw new Error(`Server responded with ${res.status}`);
            
            const data = await res.json();
            list.innerHTML = ''; // clear list

            if (data.length === 0) {
                list.innerHTML = '<li>No new notifications.</li>';
                return;
            }

            data.forEach((n, index) => {
                const li = document.createElement('li');
                li.className = 'notification-item';
                li.innerHTML = `
                    <span class="type">[${n.type}]</span>
                    <span class="message">${n.message}</span>
                    <div class="timestamp">${new Date(n.timestamp).toLocaleString()}</div>
                `;
                list.appendChild(li);

                // Animate each with delay
                setTimeout(() => li.classList.add('show'), 100 * index);
            });
        } catch (err) {
            console.error('Error loading notifications:', err);
            list.innerHTML = '<li>Failed to load notifications.</li>';
        }
    }

    // Initial load
    loadNotifications();

    // Auto refresh every 60 seconds
    setInterval(loadNotifications, 60000);
});
</script>   