<!DOCTYPE html>
<html>
<head>
  <title>Admin Chat Panel</title>
  <style>
    body { background:#f5f5f5; color:#333; font-family: Arial, sans-serif; }
    #chat {
      height:300px;
      overflow-y:scroll;
      border:1px solid #ccc;
      padding:10px;
      background:#fff;
      margin-bottom: 10px;
    }
    button {
      padding:8px 12px;
      margin:5px;
      background:red;
      color:white;
      border:none;
      border-radius: 4px;
      cursor:pointer;
    }
    .system {
      color: gray;
      font-style: italic;
    }
    .time {
      font-size: 11px;
      color: #888;
      margin-right: 5px;
    }
  </style>
</head>
<body>

<h2>Admin Panel</h2>

<div id="chat"></div>

<!-- Admin action -->
<button onclick="clearChat()">Clear Chat</button>

<script>
// Fetch chat messages
function loadMessages() {
  fetch("server.php")
    .then(res => res.json())
    .then(data => {
      let html = "";
      data.messages.forEach(msg => {
        const dateTime = `[${msg.datetime}]`;
        if (msg.type === "system") {
          html += `<div class="system"><span class="time">${dateTime}</span>${msg.text}</div>`;
        } else {
          html += `<div><span class="time">${dateTime}</span>${msg.text}</div>`;
        }
      });
      document.getElementById("chat").innerHTML = html;
    });
}

// Clear chat
function clearChat() {
  fetch("server.php?clear=true")
    .then(() => loadMessages());
}

// Auto-refresh every 2 seconds
setInterval(loadMessages, 2000);

// Initial load
loadMessages();
</script>

</body>
</html>