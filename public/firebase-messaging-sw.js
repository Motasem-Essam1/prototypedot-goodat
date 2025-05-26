importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js');

/* Initialize the Firebase app in the service worker by passing in the messagingSenderId. */
firebase.initializeApp({
    apiKey: "AIzaSyA47fTB49E6fDpSTRilLCw07RSci1W4P3Q",
    authDomain: "good-push-ddc80.firebaseapp.com",
    projectId: "good-push-ddc80",
    storageBucket: "good-push-ddc80.appspot.com",
    messagingSenderId: "84611328299",
    appId: "1:84611328299:web:9baa9f2a5bd57641db7d6e",
    measurementId: "G-W1MG0XM9G9",
});

// Retrieve an instance of Firebase Messaging so that it can handle background
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    /* Customize notification here */
    const notificationTitle = 'Deft@';
    const notificationOptions = {
        body: payload.data.body,
        icon: "assets/img/logo.png",
        data: {
            row: payload.data.row,
        }
    };

    self.clients.matchAll({includeUncontrolled: true}).then(function (clients) {
        //you can see your main window client in this list.
        clients.forEach(function(client) {
            client.postMessage('YOUR_MESSAGE_HERE');
        })
    })

    self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    var promise = new Promise(function(resolve) {
        setTimeout(resolve, 1000);
    }).then(function() {
        // Check item type
        let row = JSON.parse(event.notification.data.row);
        let idOrSlug = '';
        let url = null;
        if (row.item_type == 'provider') {
            idOrSlug = row.item ? row.item.id : '';
            url = `/service-provider/${idOrSlug}`;
        } else if(row.item_type == 'service') {
            idOrSlug = row.item ? row.item.service_slug : '';
            url = `/service-view/${idOrSlug}`;
        } else if(row.item_type == 'task') {
            idOrSlug = row.item ? row.item.task_slug : '';
            url = `/task-view/${idOrSlug}`;
        }

        return clients.openWindow(url);
    });

    event.waitUntil(promise);
});

