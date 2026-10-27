<?php
session_start();
$next = isset($_SESSION['user_id']) ? "dashboard.php" : "login.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CyberSafe - Think Before You Click</title>
<link href="assets/fonts/fonts.css" rel="stylesheet">
<style>
* { box-sizing: border-box; }
body { margin: 0; background: #F6F5EF; color: #1C211E; font-family: "Inter", Arial, sans-serif; overflow-x: hidden; }
body.lock { overflow: hidden; }
a { color: inherit; text-decoration: none; }

.hero { position: relative; margin: 16px; min-height: calc(100vh - 32px); border: 1px solid #E4E1D8; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; background: #F6F5EF; }
.hero.glitch { animation: glitch 0.5s steps(2); }
#cloud { position: absolute; inset: 0; width: 100%; height: 100%; }
header, .content, .bottom { position: relative; z-index: 2; }
header { display: flex; justify-content: space-between; align-items: center; padding: 32px 44px 0; }
.brand { display: flex; font-family: "Inter Tight", sans-serif; font-weight: 600; font-size: 20px; }
.brand b { color: #3E7A5A; font-weight: 600; }

.btn { display: inline-flex; align-items: center; gap: 14px; padding: 5px 5px 5px 22px; border-radius: 999px; font-size: 15px; font-weight: 500; background: #205C43; color: #F6F5EF; transition: background 0.2s; }
.btn:hover { background: #184734; }
.btn span { width: 36px; height: 36px; border-radius: 50%; background: #F6F5EF; color: #205C43; display: grid; place-items: center; transition: transform 0.3s; }
.btn:hover span { transform: rotate(45deg); }
.content .btn { margin-top: 40px; }

.content { flex: 1; display: grid; grid-template-columns: 1.1fr 1fr; align-items: center; padding: 48px 6% 32px 9%; }
h1 { font-family: "Inter Tight", sans-serif; font-weight: 500; font-size: clamp(44px, 6.2vw, 116px); line-height: 0.95; letter-spacing: -0.045em; margin: 0; }
h1 span { display: block; animation: rise 1s ease both; }

.bottom { display: flex; justify-content: center; padding: 0 44px 28px; }
nav { display: flex; gap: 4px; padding: 5px; border-radius: 12px; background: #EEEDE5; border: 1px solid #E4E1D8; }
nav a { padding: 9px 16px; border-radius: 8px; font-size: 14px; }
nav a:hover { background: #E4E1D8; }
nav a:first-child { background: #F6F5EF; }

#intro { position: fixed; inset: 0; z-index: 100; background: #12201A; color: #8DB59C; font-family: "JetBrains Mono", monospace; visibility: hidden; clip-path: circle(0 at var(--x) var(--y)); transition: clip-path 0.7s ease-in-out; }
#intro.open { visibility: visible; clip-path: circle(150vmax at var(--x) var(--y)); }
#intro.leaving { opacity: 0; transition: opacity 0.4s; }
#matrix { position: absolute; inset: 0; opacity: 0.3; }

.terminal { position: absolute; inset: 0; display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px; padding: 5vh 5vw; transition: opacity 0.4s; }
.panel { border: 1px solid #2C4537; border-radius: 10px; background: #0E1914; display: flex; flex-direction: column; overflow: hidden; }
.panel p { margin: 0; padding: 10px 14px; border-bottom: 1px solid #2C4537; font-size: 12px; color: #7D8F84; }
.panel p::before { content: "● ● ●  "; color: #4E6B5B; }
pre { flex: 1; margin: 0; padding: 16px; font-size: 13px; line-height: 1.6; white-space: pre-wrap; overflow: hidden; display: flex; flex-direction: column; justify-content: flex-end; }
#code { font-size: 12px; opacity: 0.6; }
.white { color: #F6F5EF; }
.ok { color: #7FC79A; }
.warn { color: #D9B66A; }
.dim { opacity: 0.5; }
.caret { display: inline-block; width: 8px; height: 1em; background: #8DB59C; animation: blink 0.8s steps(1) infinite; }

.granted { position: absolute; inset: 0; display: grid; place-items: center; opacity: 0; pointer-events: none; }
.granted div { font-weight: 700; font-size: clamp(26px, 5vw, 64px); letter-spacing: 0.25em; padding: 18px 34px; border: 2px solid #8DB59C; background: #12201A; color: #F6F5EF; }
#intro.granted-on .granted { animation: flash 0.9s forwards; }

.loader { position: absolute; inset: 0; display: grid; place-items: center; padding: 24px; opacity: 0; transition: opacity 0.5s; }
#intro.loading .terminal { opacity: 0; }
#intro.loading .loader { opacity: 1; }
.box { width: min(520px, 100%); text-align: center; }
.ring { position: relative; width: 170px; height: 170px; margin: 0 auto 32px; }
.ring svg { width: 100%; height: 100%; transform: rotate(-90deg); }
.spin { position: absolute; inset: -14px; border: 1px dashed #2C4537; border-radius: 50%; animation: spin 8s linear infinite; }
#pct { position: absolute; inset: 0; display: grid; place-items: center; font-size: 30px; font-weight: 700; color: #F6F5EF; }
.bar { height: 4px; background: #23382C; border-radius: 4px; }
#barFill { height: 100%; width: 0; background: #8DB59C; }
#status { text-align: left; margin-top: 12px; font-size: 12px; }
ul { list-style: none; padding: 0; margin: 24px 0 0; text-align: left; font-size: 13px; color: #55665B; }
li { margin-bottom: 8px; }
li::before { content: "[ ] "; }
li.done { color: #F6F5EF; }
li.done::before { content: "[✓] "; color: #8DB59C; }
.tip { margin-top: 26px; padding: 16px; border: 1px solid #2C4537; border-radius: 10px; text-align: left; font-size: 11px; }
#tip { margin: 6px 0 0; color: #F6F5EF; font-family: "Inter", sans-serif; font-size: 14px; }
#skip { position: absolute; right: 24px; bottom: 22px; font-family: inherit; font-size: 12px; color: #7D8F84; background: #12201A; border: 1px solid #2C4537; border-radius: 8px; padding: 8px 12px; cursor: pointer; }
#skip:hover { color: #F6F5EF; }

@keyframes rise { from { opacity: 0; transform: translateY(40px); } }
@keyframes glitch { 25% { transform: translate(-4px, 2px); filter: hue-rotate(40deg); } 50% { transform: translate(4px, -2px); } 75% { transform: translate(-2px, 1px); filter: hue-rotate(-30deg); } }
@keyframes blink { 50% { opacity: 0; } }
@keyframes flash { 15% { opacity: 1; } 30% { opacity: 0.4; } 40%, 80% { opacity: 1; } 100% { opacity: 0; } }
@keyframes spin { to { transform: rotate(360deg); } }

@media (max-width: 960px) {
  .content { grid-template-columns: 1fr; align-content: center; gap: 16px; padding: 90px 24px 24px; }
  nav, .panel + .panel { display: none; }
  .terminal { grid-template-columns: 1fr; padding: 16px 16px 68px; }
}
@media (max-width: 640px) {
  .hero { margin: 10px; min-height: calc(100vh - 20px); }
  header { padding: 22px 20px 0; }
  header .btn { padding-left: 16px; font-size: 13px; }
  .bottom { padding: 0 20px 22px; }
}
</style>
</head>
<body>

<div class="hero" id="hero">
  <canvas id="cloud"></canvas>
  <header>
    <a href="index.php" class="brand"><span>CYBER<b>SAFE</b></span></a>
    <a href="<?php echo $next; ?>" class="btn begin">Let's Begin <span>&#8599;</span></a>
  </header>
  <div class="content">
    <div>
      <h1><span>Think Before</span><span style="animation-delay:.12s">You Click,</span></h1>
      <a href="<?php echo $next; ?>" class="btn begin">Let's Begin <span>&#8599;</span></a>
    </div>
  </div>
  <div class="bottom">
    <nav>
      <a href="index.php">Home</a>
      <a href="home.php">Portal</a>
      <a href="register.php">Register</a>
    </nav>
  </div>
</div>

<div id="intro">
  <canvas id="matrix"></canvas>
  <div class="terminal">
    <div class="panel"><p>guest@cybersafe: ~</p><pre id="term"></pre></div>
    <div class="panel"><p>secure_core.php</p><pre id="code"></pre></div>
  </div>
  <div class="granted"><div>ACCESS GRANTED</div></div>
  <div class="loader">
    <div class="box">
      <div class="ring">
        <div class="spin"></div>
        <svg viewBox="0 0 100 100" fill="none" stroke-width="4">
          <circle cx="50" cy="50" r="44" stroke="#23382C"/>
          <circle id="ring" cx="50" cy="50" r="44" stroke="#8DB59C" stroke-linecap="round" stroke-dasharray="276" stroke-dashoffset="276"/>
        </svg>
        <div id="pct">0%</div>
      </div>
      <div class="bar"><div id="barFill"></div></div>
      <div id="status">Encrypting session...</div>
      <ul>
        <li>Encrypting your session</li>
        <li>Loading phishing signatures</li>
        <li>Arming password analyzer</li>
        <li>Preparing secure login</li>
      </ul>
      <div class="tip">// CYBER TIP<p id="tip"></p></div>
    </div>
  </div>
  <button id="skip">Skip intro [Esc]</button>
</div>

<script>
var next = "<?php echo $next; ?>";
var hero = document.getElementById("hero");
var canvas = document.getElementById("cloud");
var ctx = canvas.getContext("2d");
var intro = document.getElementById("intro");
var term = document.getElementById("term");
var W, H, dots = [], mx = -999, my = -999, tx = 0, ty = 0;
var startTime = performance.now();
var started = false, opened = false, leaving = false;

function el(id) {
  return document.getElementById(id);
}

function makeDots() {
  var c = document.createElement("canvas");
  c.width = 720;
  c.height = 240;
  var g = c.getContext("2d");
  g.fillStyle = "white";
  g.font = "600 190px 'Inter Tight', Arial";
  g.textAlign = "center";
  g.textBaseline = "middle";
  g.fillText("SBMP", 360, 120);

  var data = g.getImageData(0, 0, 720, 240).data;
  var pts = [];
  for (var y = 0; y < 240; y += 2)
    for (var x = 0; x < 720; x += 2)
      if (data[(y * 720 + x) * 4 + 3] > 128) pts.push([(x - 360) / 340, (y - 120) / 340]);

  dots = [];
  var count = W < 640 ? 2200 : 5200;
  for (var i = 0; i < count; i++) {
    var p = pts[Math.floor(Math.random() * pts.length)];
    var a = Math.random() * Math.PI * 2;
    var b = Math.acos(Math.random() * 2 - 1);
    dots.push({
      sx: Math.sin(b) * Math.cos(a) * 0.75, sy: Math.cos(b) * 0.75, sz: Math.sin(b) * Math.sin(a) * 0.75,
      x: p[0], y: p[1], z: (Math.random() - 0.5) * 0.1,
      delay: Math.random(), phase: Math.random() * 6.28, size: 0.6 + Math.random() * 0.9
    });
  }
}

function resize() {
  W = hero.offsetWidth;
  H = hero.offsetHeight;
  canvas.width = W * 2;
  canvas.height = H * 2;
  ctx.setTransform(2, 0, 0, 2, 0, 0);
  makeDots();
}

function draw(now) {
  requestAnimationFrame(draw);
  if (opened) return;

  var t = now / 1000;
  var mobile = W < 960;
  var cx = mobile ? W * 0.5 : W * 0.68;
  var cy = mobile ? H * 0.17 : H * 0.34;
  var size = mobile ? Math.min(W * 0.36, 220) : Math.min(W * 0.21, H * 0.6);
  var form = Math.min(1, (now - startTime) / 2600);
  var ry = Math.sin(t * 0.35) * 0.2 + tx * 0.35;
  var rx = Math.sin(t * 0.27) * 0.08 + ty * 0.25;

  ctx.clearRect(0, 0, W, H);
  for (var i = 0; i < dots.length; i++) {
    var d = dots[i];
    var k = Math.max(0, Math.min(1, form * 1.5 - d.delay * 0.5));
    k = k * k * (3 - 2 * k);
    var x = d.sx + (d.x - d.sx) * k;
    var y = d.sy + (d.y - d.sy) * k + Math.sin(x * 3 + t * 1.2) * 0.035;
    var z = d.sz + (d.z - d.sz) * k + Math.sin(x * 4 + t * 1.5 + d.phase) * 0.05;

    var x2 = x * Math.cos(ry) - z * Math.sin(ry);
    var z2 = x * Math.sin(ry) + z * Math.cos(ry);
    var y2 = y * Math.cos(rx) - z2 * Math.sin(rx);
    var z3 = y * Math.sin(rx) + z2 * Math.cos(rx);
    var px = cx + x2 * size * 3 / (3 + z3);
    var py = cy + y2 * size * 3 / (3 + z3);

    var dx = px - mx, dy = py - my;
    var dist = Math.sqrt(dx * dx + dy * dy);
    if (dist > 0 && dist < 130) {
      px += dx / dist * (130 - dist) * 0.26;
      py += dy / dist * (130 - dist) * 0.26;
    }

    var f = Math.max(0, Math.min(1, 0.72 - z3 * 0.7));
    var s = (1 + f * 1.4) * d.size;
    ctx.globalAlpha = 0.45 + f * 0.55;
    ctx.fillStyle = f > 0.62 ? "#205C43" : f > 0.4 ? "#3E7A5A" : "#8DB59C";
    ctx.fillRect(px, py, s, s);
  }
  ctx.globalAlpha = 1;
}

hero.onmousemove = function (e) {
  var r = hero.getBoundingClientRect();
  mx = e.clientX - r.left;
  my = e.clientY - r.top;
  tx = (mx / r.width - 0.5) * 2;
  ty = (my / r.height - 0.5) * 2;
};
hero.onmouseleave = function () {
  mx = my = -999;
  tx = ty = 0;
};
window.onresize = resize;

resize();
requestAnimationFrame(draw);
document.fonts.ready.then(makeDots);


function startMatrix() {
  var m = el("matrix");
  var g = m.getContext("2d");
  var chars = "01アイウエオカキクケコ<>/{}#$%ABCDEF";
  var drops = [];
  m.width = window.innerWidth;
  m.height = window.innerHeight;
  for (var i = 0; i < m.width / 16; i++) drops.push(Math.random() * -50);

  setInterval(function () {
    g.fillStyle = "rgba(18, 32, 26, 0.12)";
    g.fillRect(0, 0, m.width, m.height);
    g.fillStyle = "#8DB59C";
    g.font = "16px monospace";
    for (var i = 0; i < drops.length; i++) {
      g.fillText(chars[Math.floor(Math.random() * chars.length)], i * 16, drops[i] * 16);
      if (drops[i] * 16 > m.height && Math.random() > 0.97) drops[i] = 0;
      drops[i]++;
    }
  }, 45);
}

var codeLines = [
  "<?php", "session_start();", "require 'includes/db.php';", "",
  "function secure_login($email, $password) {",
  "    $stmt = mysqli_prepare($conn, \"SELECT * FROM users WHERE email = ?\");",
  "    mysqli_stmt_bind_param($stmt, \"s\", $email);",
  "    mysqli_stmt_execute($stmt);",
  "    if (password_verify($password, $row['password'])) {",
  "        session_regenerate_id(true);",
  "        return true;", "    }", "    return false;", "}", "",
  "// phishing red flags",
  "$flags = ['urgent', 'kyc', 'lottery', 'bit.ly', 'otp'];",
  "foreach ($flags as $f) {",
  "    if (stripos($message, $f) !== false) $score += 15;", "}", "",
  "$hash = password_hash($password, PASSWORD_DEFAULT);",
  "header('X-Frame-Options: DENY');"
];

function startCode() {
  var n = 0, shown = [];
  setInterval(function () {
    n++; shown.push(n + "  " + codeLines[n % codeLines.length]);
    n++; shown.push(n + "  " + codeLines[n % codeLines.length]);
    shown = shown.slice(-60);
    el("code").textContent = shown.join("\n");
  }, 30);
}

function hex(len) {
  var s = "";
  for (var i = 0; i < len; i++) s += "0123456789abcdef"[Math.floor(Math.random() * 16)];
  return s;
}

function write(div, text) {
  div.textContent = text;
  div.innerHTML += '<span class="caret"></span>';
}

function addLine(text, cls) {
  var old = term.querySelector(".caret");
  if (old) old.remove();
  var div = document.createElement("div");
  div.className = cls || "";
  write(div, text);
  term.appendChild(div);
  return div;
}

function typeCommand(text, done) {
  var line = addLine("guest@cybersafe:~$ ", "white");
  var i = 0;
  function step() {
    if (leaving) return;
    i++;
    write(line, "guest@cybersafe:~$ " + text.slice(0, i));
    if (i < text.length) setTimeout(step, 5 + Math.random() * 9);
    else done();
  }
  setTimeout(step, 40);
}

var script = [
  ["$ ssh guest@cybersafe.local -p 443"],
  ["Connecting to cybersafe.local (127.0.0.1:443) ...", "dim"],
  ["Negotiating keys: x25519 / AES-256-GCM", "dim"],
  ["[ OK ] TLS 1.3 handshake complete", "ok"],
  ["$ sudo ./cybersafe --init --mode=awareness"],
  ["> Loading security modules", ""],
  ["  ├─ phishing_detector.ko ......... [ LOADED ]", "ok"],
  ["  ├─ password_analyzer.ko ......... [ LOADED ]", "ok"],
  ["  ├─ password_generator.ko ........ [ LOADED ]", "ok"],
  ["  ├─ quiz_engine.ko ............... [ LOADED ]", "ok"],
  ["  └─ id_card_forge.ko ............. [ LOADED ]", "ok"],
  ["> Scanning network for threats...", ""],
  ["HEX"], ["HEX"], ["HEX"], ["HEX"], ["HEX"], ["HEX"], ["HEX"], ["HEX"],
  ["[ ! ] 3 phishing patterns blocked: urgent KYC, lottery win, bit.ly/*", "warn"],
  ["> Hashing credential vault (bcrypt, cost=10)", ""],
  ["  $2y$10$" + hex(53), "dim"],
  ["[ OK ] Firewall up · 128 rules applied · encryption ON", "ok"],
  [">>> SYSTEM SECURE — ACCESS GRANTED <<<", ""]
];

function runScript(i) {
  if (leaving) return;
  if (i == script.length) {
    setTimeout(showGranted, 150);
    return;
  }
  var text = script[i][0];
  if (text[0] == "$") {
    typeCommand(text.slice(2), function () { runScript(i + 1); });
  } else if (text == "HEX") {
    addLine("  0x" + hex(8) + "  " + hex(16).replace(/(..)/g, "$1 ") + (Math.random() > 0.7 ? "THREAT_SIG" : "clean"), "dim");
    setTimeout(function () { runScript(i + 1); }, 10);
  } else {
    addLine(text, script[i][1]);
    setTimeout(function () { runScript(i + 1); }, 25 + Math.random() * 30);
  }
}

function showGranted() {
  if (leaving) return;
  intro.classList.add("granted-on");
  setTimeout(showLoader, 700);
}

var tips = [
  "Never share your OTP — not even with someone claiming to be from your bank.",
  "Use a long passphrase with numbers & symbols. Length beats complexity.",
  "Hover over links before clicking. Short links like bit.ly can hide fake sites.",
  "Turn on two-factor authentication for email and social media.",
  "Don't reuse passwords — one leak can unlock all your accounts."
];
var statusText = ["Encrypting session...", "Loading phishing signatures...", "Arming password analyzer...", "Preparing secure login...", "Ready."];

function showLoader() {
  if (leaving) return;
  intro.classList.add("loading");
  var n = Math.floor(Math.random() * tips.length);
  el("tip").textContent = tips[n];
  setInterval(function () {
    n = (n + 1) % tips.length;
    el("tip").textContent = tips[n];
  }, 1000);

  var progress = 0;
  var checks = document.querySelectorAll("li");
  var timer = setInterval(function () {
    progress = Math.min(100, progress + Math.random() * 2);
    el("pct").textContent = Math.floor(progress) + "%";
    el("barFill").style.width = progress + "%";
    el("ring").style.strokeDashoffset = 276 - 276 * progress / 100;
    el("status").textContent = statusText[Math.floor(progress / 25)];
    for (var i = 0; i < checks.length; i++)
      if (progress >= (i + 1) * 25) checks[i].classList.add("done");
    if (progress >= 100) {
      clearInterval(timer);
      setTimeout(goToLogin, 300);
    }
  }, 20);
}

function goToLogin() {
  if (leaving) return;
  leaving = true;
  intro.classList.add("leaving");
  setTimeout(function () { location.href = next; }, 400);
}

function startIntro(x, y) {
  if (started) return;
  started = true;
  hero.classList.add("glitch");
  document.body.classList.add("lock");
  intro.style.setProperty("--x", x + "px");
  intro.style.setProperty("--y", y + "px");
  setTimeout(function () {
    intro.classList.add("open");
    startMatrix();
    startCode();
    setTimeout(function () { opened = true; }, 700);
    setTimeout(function () { runScript(0); }, 300);
  }, 450);
}

document.querySelectorAll(".begin").forEach(function (btn) {
  btn.onclick = function (e) {
    if (e.ctrlKey || e.metaKey || e.shiftKey) return;
    e.preventDefault();
    var r = btn.getBoundingClientRect();
    startIntro(r.left + r.width / 2, r.top + r.height / 2);
  };
});

el("skip").onclick = goToLogin;
document.onkeydown = function (e) {
  if (e.key == "Escape" && started) goToLogin();
};
window.onpageshow = function (e) {
  if (e.persisted) location.reload();
};
</script>
</body>
</html>
