// const { default: axios } = require("axios");

// require("./bootstrap");

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");

window.Echo = new Echo({
    broadcaster: "pusher",
    key: process.env.MIX_PUSHER_APP_KEY,
    // cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    encrypted: true,
    enabledTransports: ["ws", "wss"],
});

console.log(window.Echo); // Should output the Echo instance
