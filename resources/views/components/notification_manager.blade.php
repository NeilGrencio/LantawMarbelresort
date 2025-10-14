<body>
    <div id="notification-container"></div>

    <style>
        #notification-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            z-index: 9999;
        }

        .notification {
            position: relative;
            background: #fff;
            border: 1px solid #000;
            box-shadow: 2px 2px 0 black;
            border-radius: 0.5rem;
            width: 20rem;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-20px);
            transition: transform 0.5s ease, opacity 0.5s ease;
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification.hide {
            opacity: 0;
            transform: translateY(50px);
        }

        #content {
            padding: 0.8rem;
        }

        #content h3 {
            margin: 0;
            font-size: 1.1rem;
        }

        #content p {
            margin: 0.3rem 0 0;
            font-size: 0.9rem;
        }

        .loading-bar {
            height: 5px;
            background: orange;
            width: 100%;
        }

        .close-button {
            position: absolute;
            top: 0.4rem;
            right: 0.4rem;
            background: black;
            color: white;
            border-radius: 50%;
            width: 1.4rem;
            height: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .close-button:hover {
            background: darkred;
            transform: scale(1.1);
        }
    </style>

    <script>
    let notificationQueue = JSON.parse(localStorage.getItem('notif_queue') || '[]');
    let activeNotifications = new Map();
    const MAX_VISIBLE = 3;
    let shownNotifications = JSON.parse(localStorage.getItem('shown_notifications') || '[]');

    window.addEventListener('beforeunload', () => {
        localStorage.setItem('notif_queue', JSON.stringify(notificationQueue));
        localStorage.setItem('shown_notifications', JSON.stringify(shownNotifications));
    });

    async function loadNotifications() {
        try {
            const res = await fetch('/popUp');
            if (!res.ok) throw new Error(`Server responded with ${res.status}: ${res.statusText}`);
            const data = await res.json();

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(n => {
                    const uniqueKey = generateNotificationKey(n);
                    if (!shownNotifications.includes(uniqueKey)) {
                        shownNotifications.push(uniqueKey);
                        localStorage.setItem('shown_notifications', JSON.stringify(shownNotifications));

                        notificationQueue.push({
                            title: n.type || "Notification",
                            message: n.message || "New event occurred",
                            key: uniqueKey
                        });
                    }
                });
                localStorage.setItem('notif_queue', JSON.stringify(notificationQueue));
                processNotificationQueue();
            }
        } catch (err) {
            console.error('Error fetching notifications:', err);
            if (!shownNotifications.includes('notif_server_error')) {
                shownNotifications.push('notif_server_error');
                localStorage.setItem('shown_notifications', JSON.stringify(shownNotifications));
                notificationQueue.push({
                    title: "System Alert ⚠️",
                    message: "Unable to fetch notifications. The server might be offline or misconfigured.",
                    key: "notif_server_error"
                });
                processNotificationQueue();
            }
        }
    }

    function generateNotificationKey(n) {
        const idPart = n.id || n.bookingID || n.paymentID || n.daytourID || n.inquiryID || '';
        const msgPart = n.message ? n.message.substring(0, 50) : '';

        function safeBtoa(str) {
            return btoa(unescape(encodeURIComponent(str)));
        }

        let encodedMsg = '';
        try {
            encodedMsg = safeBtoa(msgPart);
        } catch {
            encodedMsg = btoa('fallback');
        }

        return `notif_${n.type || 'General'}_${idPart}_${encodedMsg}`;
    }

    function processNotificationQueue() {
        while (activeNotifications.size < MAX_VISIBLE && notificationQueue.length > 0) {
            const { title, message, key } = notificationQueue.shift();
            showNotification(title, message, key);
        }
        localStorage.setItem('notif_queue', JSON.stringify(notificationQueue));
    }

    function showNotification(title, message, key) {
        const container = document.getElementById('notification-container');
        if (!container) return;

        const notif = document.createElement('div');
        notif.className = 'notification';
        notif.innerHTML = `
            <div id="content">
                <h3>${title}</h3>
                <p>${message}</p>
            </div>
            <div class="loading-bar"></div>
            <div class="close-button"><i class="fa-solid fa-xmark"></i></div>
        `;

        container.appendChild(notif);
        requestAnimationFrame(() => notif.classList.add('show'));

        activeNotifications.set(key, notif);

        const loadingBar = notif.querySelector('.loading-bar');
        loadingBar.animate([{ width: '100%' }, { width: '0%' }], {
            duration: 4000,
            easing: 'linear',
            fill: 'forwards'
        });

        notif.querySelector('.close-button').addEventListener('click', () => closeNotification(key));
        setTimeout(() => closeNotification(key), 4000);
    }

    function closeNotification(key) {
        const notif = activeNotifications.get(key);
        if (!notif) return;

        notif.classList.remove('show');
        notif.classList.add('hide');
        setTimeout(() => {
            notif.remove();
            activeNotifications.delete(key);
            processNotificationQueue();
        }, 500);
    }

    setInterval(loadNotifications, 7000);
    document.addEventListener('DOMContentLoaded', () => {
        processNotificationQueue();
        loadNotifications();
    });
    </script>
</body>
