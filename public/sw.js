// Listener for push events
self.addEventListener("push", (event) => {
    const notification = event.data.json();
    const options = {
        body: notification.body,
        icon: "static/icon.png", // Corrected typo: should be 'static' not 'staic'
        data: {
            notifURL: notification.url
        }
    };

    event.waitUntil(
        self.registration.showNotification(notification.title, options)
    );
});

// Listener for notification click events
self.addEventListener("notificationclick", (event) => {
    event.notification.close(); // Close the notification

    event.waitUntil(
        clients.openWindow('/'+event.notification.data.notifURL)
    );
});
