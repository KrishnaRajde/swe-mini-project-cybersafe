<?php
include "includes/db.php";

$error = "";
$email_value = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $email_value = $email;

    $stmt = mysqli_prepare($conn, "SELECT user_id, name, password FROM users WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Incorrect email or password. Please try again.";
    }
}

$title = "Login";
include "includes/header.php";
?>

<style>
  .buddy-wrap {
    position: relative;
    width: 168px;
    height: 168px;
    margin: 0 auto 18px;
    animation: buddyPop 0.7s cubic-bezier(.2, 1.4, .4, 1) both;
  }

  @keyframes buddyPop {
    from { transform: scale(0.6) translateY(16px); opacity: 0; }
    to { transform: none; opacity: 1; }
  }

  #buddy { position: relative; z-index: 1; width: 100%; height: 100%; display: block; }

  #buddy .b-head {
    transform-box: view-box;
    transform-origin: 100px 140px;
    transition: transform 0.35s cubic-bezier(.3, 1.3, .5, 1);
  }

  #buddy .b-pupils { transition: transform 0.12s ease-out; }

  #buddy .b-eye {
    transform-box: fill-box;
    transform-origin: center;
    transition: transform 0.09s ease;
  }

  #buddy.blink .b-eye,
  #buddy.hiding .b-eye { transform: scaleY(0.1); }
  #buddy.hiding.peeking .b-eye.right { transform: none; }

  #buddy .b-brows {
    transition: transform 0.25s ease;
  }

  #buddy.happy .b-brows { transform: translateY(-2.5px); }
  #buddy.sad .b-brows { transform: translateY(1.5px); }

  #buddy .b-mouth { opacity: 0; transition: opacity 0.15s ease; }
  #buddy .b-mouth.m-smile { opacity: 1; }
  #buddy.happy .b-mouth.m-smile,
  #buddy.hiding .b-mouth.m-smile,
  #buddy.sad .b-mouth.m-smile { opacity: 0; }
  #buddy.happy:not(.hiding) .b-mouth.m-grin { opacity: 1; }
  #buddy.hiding:not(.peeking) .b-mouth.m-oh { opacity: 1; }
  #buddy.hiding.peeking .b-mouth.m-smirk { opacity: 1; }
  #buddy.sad:not(.hiding):not(.happy) .b-mouth.m-frown { opacity: 1; }

  #buddy .b-arm {
    transform-box: view-box;
    transform: translateY(125px);
    transition: transform 0.45s cubic-bezier(.3, 1.25, .5, 1);
  }

  #buddy .b-arm.right { transition-delay: 0.05s; transform-origin: 114px 98px; }
  #buddy.hiding .b-arm { transform: translateY(0); }
  #buddy.hiding.peeking .b-arm.right { transform: translate(12px, 28px) rotate(10deg); transition-delay: 0s; }

  #buddy.shake .b-head { animation: headShake 0.5s ease; }
  @keyframes headShake {
    0%, 100% { transform: rotate(0); }
    20% { transform: rotate(-7deg); }
    40% { transform: rotate(6deg); }
    60% { transform: rotate(-4deg); }
    80% { transform: rotate(3deg); }
  }

  .buddy-bubble {
    position: absolute;
    left: 50%;
    bottom: -6px;
    transform: translateX(-50%);
    white-space: nowrap;
    z-index: 2;
    font-size: 11px;
    font-weight: 600;
    color: #FFFFFF;
    background: #205C43;
    padding: 4px 10px;
    border-radius: 999px;
    transition: opacity 0.25s ease, transform 0.25s ease;
  }

  .buddy-bubble.swap { opacity: 0; transform: translateX(-50%) translateY(4px); }

  @media (prefers-reduced-motion: reduce) {
    .buddy-wrap { animation: none; }
    #buddy .b-head, #buddy .b-arm, #buddy .b-pupils { transition-duration: 0.01s; }
  }
</style>

<div class="max-w-md mx-auto my-8">
  <div class="cyber-box rounded-2xl p-6 sm:p-8">
    <div class="text-center mb-6">

      <div class="buddy-wrap">
        <svg id="buddy" viewBox="0 0 200 200" aria-hidden="true" class="<?php echo $error ? 'sad' : ''; ?>">
          <defs>
            <clipPath id="bClip"><circle cx="100" cy="100" r="96"/></clipPath>

            <g id="bArmShape">
              <path d="M40 210 C46 168 58 134 72 112 L102 114 C98 144 95 176 94 210 Z" fill="#205C43"/>
              <path d="M71 113 L102 115" stroke="#F6F5EF" stroke-width="3" stroke-linecap="round"/>
              <g fill="#f1c09a" stroke="#d9a07a" stroke-width="1">
                <rect x="71" y="76" width="8" height="26" rx="4"/>
                <rect x="79" y="71" width="8" height="30" rx="4"/>
                <rect x="87" y="71" width="8" height="30" rx="4"/>
                <rect x="95" y="76" width="7.5" height="26" rx="3.75"/>
                <ellipse cx="86.5" cy="101" rx="17" ry="12"/>
                <ellipse cx="104" cy="104" rx="4.5" ry="9" transform="rotate(-35 104 104)"/>
              </g>
            </g>
          </defs>

          <g clip-path="url(#bClip)">
            <circle cx="100" cy="100" r="96" fill="#E6EFE9"/>
            <path d="M20 60 H180 M20 100 H180 M20 140 H180 M60 10 V190 M100 10 V190 M140 10 V190" stroke="#205C43" stroke-opacity="0.06" stroke-width="1"/>

            <path d="M24 214 C26 172 58 152 100 152 C142 152 174 172 176 214 Z" fill="#205C43"/>
            <path d="M68 156 C78 174 122 174 132 156" fill="none" stroke="#184734" stroke-width="5" stroke-linecap="round"/>
            <path d="M91 168 L89 190 M109 168 L111 190" stroke="#F6F5EF" stroke-width="2.2" stroke-linecap="round"/>
            <circle cx="89" cy="191" r="2.4" fill="#F6F5EF"/>
            <circle cx="111" cy="191" r="2.4" fill="#F6F5EF"/>
            <path d="M138 176 l7 -3 l7 3 v5 c0 4 -3 7 -7 8 c-4 -1 -7 -4 -7 -8 z" fill="none" stroke="#F6F5EF" stroke-width="1.6" stroke-linejoin="round"/>

            <path d="M88 126 H112 V158 C106 163 94 163 88 158 Z" fill="#dfa57d"/>

            <g class="b-head">
              <ellipse cx="64" cy="98" rx="7" ry="10" fill="#e8b28b"/>
              <ellipse cx="136" cy="98" rx="7" ry="10" fill="#e8b28b"/>
              <circle cx="62" cy="106" r="1.8" fill="#205C43"/>

              <path d="M65 90 C65 62 80 50 100 50 C120 50 135 62 135 90 C135 118 121 138 100 138 C79 138 65 118 65 90 Z" fill="#f1c09a"/>
              <path d="M70 116 C78 132 90 138 100 138 C110 138 122 132 130 116 C122 126 112 131 100 131 C88 131 78 126 70 116 Z" fill="#e3ab83" opacity="0.6"/>

              <path d="M63 92 C57 70 61 47 79 37 C95 28 121 28 135 40 C143 48 143 63 139 75 L137 92 C134 80 131 72 127 67 C118 70 108 66 102 61 C95 68 84 70 74 66 C69 74 65 82 63 92 Z" fill="#2B1D14"/>
              <path d="M68 54 C67 32 90 18 114 19 C132 20 146 30 148 44 C141 37 131 34 122 36 C128 40 130 46 128 51 C117 43 98 41 84 47 C77 50 72 52 68 54 Z" fill="#2B1D14"/>
              <path d="M82 40 C95 29 115 25 133 31 M90 48 C101 41 114 39 125 43 M76 50 C84 46 92 45 99 46" fill="none" stroke="#6b4e3a" stroke-width="1.8" stroke-linecap="round" opacity="0.75"/>
              <path d="M64 92 C63 84 64 76 67 70 L69 90 Z M136 92 C137 84 136 76 133 70 L131 90 Z" fill="#2a1f18" opacity="0.6"/>

              <g class="b-brows">
                <path d="M77 79 Q86 73 95 78" fill="none" stroke="#24180f" stroke-width="3.6" stroke-linecap="round"/>
                <path d="M105 78 Q114 73 123 79" fill="none" stroke="#24180f" stroke-width="3.6" stroke-linecap="round"/>
              </g>

              <g class="b-eye left">
                <ellipse cx="86" cy="92" rx="7.2" ry="7.6" fill="#fff"/>
              </g>
              <g class="b-eye right">
                <ellipse cx="114" cy="92" rx="7.2" ry="7.6" fill="#fff"/>
              </g>
              <g class="b-pupils">
                <g class="b-eye left">
                  <circle cx="86" cy="92.5" r="4.2" fill="#1b1410"/>
                  <circle cx="87.6" cy="90.6" r="1.4" fill="#fff"/>
                </g>
                <g class="b-eye right">
                  <circle cx="114" cy="92.5" r="4.2" fill="#1b1410"/>
                  <circle cx="115.6" cy="90.6" r="1.4" fill="#fff"/>
                </g>
              </g>

              <path d="M100 96 Q96.5 106 99 108.5 Q102 109.5 104.5 107.5" fill="none" stroke="#cf9168" stroke-width="2" stroke-linecap="round"/>
              <ellipse cx="78" cy="108" rx="6" ry="3.5" fill="#f08a7a" opacity="0.22"/>
              <ellipse cx="122" cy="108" rx="6" ry="3.5" fill="#f08a7a" opacity="0.22"/>

              <path class="b-mouth m-smile" d="M90 118 Q100 126 110 118" fill="none" stroke="#7c3b2c" stroke-width="2.6" stroke-linecap="round"/>
              <g class="b-mouth m-grin">
                <path d="M87 116 Q100 134 113 116 Z" fill="#6b2420"/>
                <path d="M89.5 117 H110.5 L109 120 H91 Z" fill="#fff"/>
                <path d="M94 126 Q100 130 106 126 Q100 124 94 126 Z" fill="#e8746a"/>
              </g>
              <ellipse class="b-mouth m-oh" cx="100" cy="121" rx="4" ry="5" fill="#6b2420"/>
              <path class="b-mouth m-smirk" d="M92 121 Q103 125 110 116" fill="none" stroke="#7c3b2c" stroke-width="2.6" stroke-linecap="round"/>
              <path class="b-mouth m-frown" d="M91 123 Q100 116 109 123" fill="none" stroke="#7c3b2c" stroke-width="2.6" stroke-linecap="round"/>
            </g>

            <g class="b-arm left"><use href="#bArmShape"/></g>
            <g class="b-arm right"><use href="#bArmShape" transform="translate(200 0) scale(-1 1)"/></g>
          </g>

          <circle cx="100" cy="100" r="96" fill="none" stroke="#C9DDD0" stroke-width="2"/>
        </svg>
        <div class="buddy-bubble" id="buddyBubble"><?php echo $error ? 'Oops! Try again' : 'Hey there!'; ?></div>
      </div>

      <h1 class="text-2xl font-bold text-[#1C211E]">Welcome Back</h1>
      <p class="text-xs text-[#5F665F] mt-1">Sign in to view your quiz scores and safety dashboard</p>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'login_required' && empty($error)) { ?>
      <div class="p-3 rounded-lg bg-[#FBF4E6] border border-[#EBD5AC] text-[#8A5A12] mb-4 text-xs flex items-center gap-2">
        <i data-lucide="lock" class="w-4 h-4 shrink-0 text-[#8A5A12]"></i>
        <span>Please log in to your account to access that feature.</span>
      </div>
    <?php } ?>

    <?php if ($error) { ?>
      <div class="p-3 rounded-lg bg-[#FDF1EF] border border-[#F0C8C2] text-[#B42318] mb-4 text-xs flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-[#B42318]"></i>
        <span><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php } ?>

    <form method="post" class="space-y-4 text-xs">
      <div>
        <label for="loginEmail" class="block text-[#1C211E] font-semibold mb-1">Email Address</label>
        <input
          type="email"
          name="email"
          id="loginEmail"
          required
          autocomplete="email"
          value="<?php echo htmlspecialchars($email_value); ?>"
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
          placeholder="name@example.com"
        >
      </div>

      <div>
        <label for="loginPass" class="block text-[#1C211E] font-semibold mb-1">Password</label>
        <div class="relative">
          <input
            type="password"
            name="password"
            id="loginPass"
            required
            autocomplete="current-password"
            class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 pr-10 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
            placeholder="••••••••"
          >
          <button type="button" id="toggleLoginVis" class="absolute right-3 top-3 text-[#5F665F] hover:text-[#205C43] text-xs" title="Show/hide password">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" name="login" class="btn-green w-full py-2.5 rounded-lg text-xs font-bold mt-2">
        Sign In
      </button>
    </form>

    <div class="mt-6 pt-4 border-t border-[#E4E1D8] text-center text-xs text-[#5F665F]">
      Don't have an account?
      <a href="register.php" class="text-[#205C43] font-semibold hover:underline ml-1">Create one here</a>
    </div>
  </div>
</div>

<script>
  (function () {
    var buddy = document.getElementById('buddy');
    var head = buddy.querySelector('.b-head');
    var pupils = buddy.querySelector('.b-pupils');
    var bubble = document.getElementById('buddyBubble');
    var emailInput = document.getElementById('loginEmail');
    var passInput = document.getElementById('loginPass');
    var toggleBtn = document.getElementById('toggleLoginVis');

    var passVisible = false;
    var measureCtx = document.createElement('canvas').getContext('2d');

    function setBubble(text) {
      if (bubble.textContent === text) return;
      bubble.classList.add('swap');
      setTimeout(function () {
        bubble.textContent = text;
        bubble.classList.remove('swap');
      }, 180);
    }

    function look(x, y, tilt) {
      x = Math.max(-1, Math.min(1, x));
      y = Math.max(-1, Math.min(1, y));
      pupils.style.transform = 'translate(' + (x * 3.2) + 'px,' + (y * 2.6) + 'px)';
      head.style.transform = 'translateX(' + (x * 2.5) + 'px) rotate(' + (tilt || 0) + 'deg)';
    }

    function caretRatio(input) {
      var style = window.getComputedStyle(input);
      measureCtx.font = style.fontWeight + ' ' + style.fontSize + ' ' + style.fontFamily;
      var pos = input.value.length;
      try {
        if (typeof input.selectionEnd === 'number') pos = input.selectionEnd;
      } catch (e) {}
      var textWidth = measureCtx.measureText(input.value.slice(0, pos)).width;
      var inner = input.clientWidth - parseFloat(style.paddingLeft) - parseFloat(style.paddingRight);
      return inner > 0 ? Math.min(1, textWidth / inner) : 0;
    }

    function followEmail() {
      var r = caretRatio(emailInput);
      var x = r * 2 - 1;
      look(x, 1, x * -5);
      var v = emailInput.value;
      buddy.classList.toggle('happy', v.indexOf('@') > 0);
      buddy.classList.remove('sad');
      if (!v) setBubble('What\'s your email?');
      else if (v.indexOf('@') > 0 && /\.[a-z]{2,}$/i.test(v)) setBubble('Looks good!');
      else if (v.indexOf('@') > 0) setBubble('Nice, keep going…');
      else setBubble('Reading along…');
    }

    function hideEyes() {
      buddy.classList.remove('happy', 'sad');
      buddy.classList.add('hiding');
      buddy.classList.toggle('peeking', passVisible);
      if (passVisible) look(0.3, 1, 0); else look(0, 0, 0);
      setBubble(passVisible ? 'Just a tiny peek...' : 'I\'m not looking!');
    }

    function relax() {
      buddy.classList.remove('hiding', 'peeking');
      buddy.classList.toggle('happy', emailInput.value.indexOf('@') > 0);
      look(0, 0, 0);
      setBubble(emailInput.value ? 'Ready when you are!' : 'Hey there!');
    }

    emailInput.addEventListener('focus', followEmail);
    emailInput.addEventListener('input', followEmail);
    emailInput.addEventListener('keyup', followEmail);
    emailInput.addEventListener('click', followEmail);
    emailInput.addEventListener('blur', function () {
      if (document.activeElement !== passInput) relax();
    });

    passInput.addEventListener('focus', hideEyes);
    passInput.addEventListener('blur', function () {
      setTimeout(function () {
        if (document.activeElement !== passInput && document.activeElement !== toggleBtn) relax();
      }, 0);
    });
    toggleBtn.addEventListener('blur', function () {
      setTimeout(function () {
        if (document.activeElement !== passInput && document.activeElement !== toggleBtn) relax();
      }, 0);
    });

    toggleBtn.addEventListener('click', function () {
      passVisible = !passVisible;
      passInput.type = passVisible ? 'text' : 'password';
      this.innerHTML = passVisible ? '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.4 10.4 0 0 1 12 5c7 0 10 7 10 7a13.2 13.2 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.5 13.5 0 0 0 2 12s3 7 10 7a9.7 9.7 0 0 0 5.39-1.61"/><path d="M2 2l20 20"/></svg>' : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
      passInput.focus();
      hideEyes();
    });

    document.addEventListener('mousemove', function (e) {
      var active = document.activeElement;
      if (active === emailInput || active === passInput || active === toggleBtn) return;
      var rect = buddy.getBoundingClientRect();
      var dx = (e.clientX - (rect.left + rect.width / 2)) / (window.innerWidth / 2);
      var dy = (e.clientY - (rect.top + rect.height * 0.46)) / (window.innerHeight / 2);
      look(dx * 1.6, dy * 1.6, dx * 4);
    });

    function scheduleBlink() {
      setTimeout(function () {
        if (!buddy.classList.contains('hiding')) {
          buddy.classList.add('blink');
          setTimeout(function () { buddy.classList.remove('blink'); }, 130);
        }
        scheduleBlink();
      }, 2500 + Math.random() * 3000);
    }
    scheduleBlink();

    if (buddy.classList.contains('sad')) {
      setTimeout(function () {
        buddy.classList.add('shake');
        setTimeout(function () { buddy.classList.remove('shake'); }, 550);
      }, 500);
    }

    if (document.activeElement === emailInput) followEmail();
    if (document.activeElement === passInput) hideEyes();
  })();
</script>

<?php include "includes/footer.php"; ?>
