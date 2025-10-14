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
    <div id="layout">
        @include('components.sidebar')

        <div id="main-layout">
            <div id="layout-header">
                <h1>Notifications</h1>
            </div>

            <div class="notification-container">
                <ul id="notificationList" class="notification-list"></ul>
            </div>
        </div>
    </div>

<style>
    /* === Layout === */
    #layout {
        display: flex;
        flex-direction: row;
        height: 100vh;
    }

    #main-layout {
        display: flex;
        flex-direction: column;
        padding: 1rem;
        width: 100%;
        transition: width 0.3s ease-in-out;
        margin-left: 12rem;
        margin-right: .7rem;
        overflow: hidden;
    }

    #layout-header {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        height: 4rem;
        padding: 1rem;
        background: white;
        border-radius: .7rem;
        border: 1px solid black;
        box-shadow: .1rem .1rem 0 black;
        font-size: .9rem;
    }

    /* === Notification list === */
    .notification-container {
        padding: 20px;
        width: 100%;
        height: 100%;
        overflow-y: auto;
        margin-top: 20px;
    }

    .notification-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .notification-item {
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid black;
        border-radius: .7rem;
        box-shadow: 2px 2px 0 black;
        padding: 1rem;
        margin-bottom: 1rem;
        opacity: 0;
        transform: translateY(15px);
        transition: all 0.4s ease;
    }

    .notification-item.show {
        opacity: 1;
        transform: translateY(0);
    }

    .notification-item .type {
        font-weight: 700;
        color: #b9570d;
        margin-bottom: .4rem;
    }

    .notification-item .message {
        color: #333;
        margin-bottom: .5rem;
        line-height: 1.4;
    }

    .notification-item .timestamp {
        font-size: 0.8rem;
        color: #777;
        align-self: flex-end;
    }

    /* === Empty message === */
    .empty-message {
        text-align: center;
        color: #777;
        font-style: italic;
        margin-top: 2rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const list = document.getElementById('notificationList');

    async function loadNotifications() {
        try {
            const res = await fetch('/popUpManager');
            if (!res.ok) throw new Error(`Server responded with ${res.status}`);
            
            const data = await res.json();
            list.innerHTML = ''; // Clear old list

            if (!Array.isArray(data) || data.length === 0) {
                list.innerHTML = '<li class="empty-message">No new notifications.</li>';
                return;
            }

            data.forEach((n, i) => {
                const li = document.createElement('li');
                li.className = 'notification-item';
                li.innerHTML = `
                    <span class="type">${n.type ? `[${n.type}]` : '[Notification]'}</span>
                    <span class="message">${n.message || 'No message available.'}</span>
                    <div class="timestamp">${n.timestamp ? new Date(n.timestamp).toLocaleString() : ''}</div>
                `;
                list.appendChild(li);

                // Add animation delay for each notification
                setTimeout(() => li.classList.add('show'), 100 * i);
            });
        } catch (err) {
            console.error('Error loading notifications:', err);
            list.innerHTML = '<li class="empty-message">Failed to load notifications.</li>';
        }
    }

    // Initial load
    loadNotifications();

    // Auto refresh every 60 seconds
    setInterval(loadNotifications, 60000);
});
</script>
</body>
</html>
