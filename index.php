<!DOCTYPE html>
<html>
<head>
  <title>Chat App</title>
  <style>
    body { background:#f5f5f5; color:#333; font-family: Arial; }
    #chat {
      height:300px;
      overflow-y:scroll;
      border:1px solid #ccc;
      padding:10px;
      background:#fff;
    }
    input, button {
      padding:8px;
      margin:5px;
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

<h2>Chat App</h2>

<div id="chat"></div>

<input type="text" id="username" placeholder="Your name">
<input type="text" id="message" placeholder="Type message">
<button onclick="sendMessage()">Send</button>

<script>
function sendMessage() {
  const user = document.getElementById("username").value;
  const msg = document.getElementById("message").value;

  fetch("server.php", {
    method: "POST",
    headers: {"Content-Type": "application/x-www-form-urlencoded"},
    body: `user=${encodeURIComponent(user)}&message=${encodeURIComponent(msg)}`
  }).then(() => {
    document.getElementById("message").value = "";
  });
}

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

setInterval(loadMessages, 2000);
</script>

</body>
</html>