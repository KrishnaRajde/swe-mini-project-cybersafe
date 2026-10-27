<?php
include "includes/db.php";
$title = "Home";
include "includes/header.php";
?>

<div id="section-home" class="scroll-mt-24 text-center py-6 sm:py-10">
  <h1 class="text-2xl sm:text-4xl font-extrabold text-[#1C211E] tracking-tight mb-2">
    Cyber Security Awareness
  </h1>
  <p class="text-[#205C43] font-medium text-sm sm:text-base mb-3">
    Stay Safe in the Digital World
  </p>
  <p class="text-xs sm:text-sm text-[#5F665F] max-w-xl mx-auto mb-6">
    A simple web project built to help students and beginners understand basic cyber safety, identify online scams, and test password strengths.
  </p>
  <div class="flex flex-wrap items-center justify-center gap-3">
    <a href="#section-safety" class="btn-green px-5 py-2.5 rounded-lg text-xs font-bold flex items-center gap-1.5">
      <span>Get Started</span>
    </a>
    <a href="#password-checker" class="btn-outline px-4 py-2.5 rounded-lg text-xs font-bold flex items-center gap-1.5">
      <span>Test Password</span>
      <i data-lucide="key-round" class="w-3.5 h-3.5"></i>
    </a>
  </div>
</div>

<div id="section-safety" class="scroll-mt-24 space-y-4 max-w-4xl mx-auto mb-14">
  <div class="flex items-center justify-between pb-2 border-b border-[#E4E1D8]">
    <h2 class="text-lg font-bold text-[#1C211E] flex items-center gap-2.5">
      <span>Cyber Safety Fundamentals</span>
    </h2>
    <a href="learn.php" class="text-xs text-[#205C43] hover:underline font-medium flex items-center gap-1">
      <span>View All 5 Modules</span>
    </a>
  </div>

  <div class="cyber-box rounded-xl p-5 sm:p-6 border-l-4 border-l-[#205C43] border border-[#E4E1D8]">
    <h3 class="text-base font-bold text-[#1C211E] mb-2 flex items-center gap-2"> <span>What is Cyber Security?</span>
    </h3>
    <p class="text-[#1C211E] text-sm leading-relaxed">
      Cyber Security is the practice of protecting computers, smartphones, networks, and personal data from digital attacks. In today's world, everything is connected to the internet — making it very important to stay safe online. Hackers use many tricks to steal your personal information, money, or damage your devices.
    </p>
  </div>

  <div class="cyber-box rounded-xl p-5 sm:p-6 border-l-4 border-l-[#B7791F] border border-[#E4E1D8]">
    <h3 class="text-base font-bold text-[#8A5A12] mb-3 flex items-center gap-2"> <span>Why Does It Matter?</span>
    </h3>
    <ul class="space-y-2 text-sm text-[#1C211E]">
      <li class="flex items-center gap-2.5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#B7791F] shrink-0"></span>
        <span>Over 4,000 cyber attacks happen every day worldwide.</span>
      </li>
      <li class="flex items-center gap-2.5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#B7791F] shrink-0"></span>
        <span>Phishing emails trick millions of users into giving away passwords.</span>
      </li>
      <li class="flex items-center gap-2.5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#B7791F] shrink-0"></span>
        <span>Weak passwords are the #1 cause of account hacking.</span>
      </li>
      <li class="flex items-center gap-2.5">
        <span class="w-1.5 h-1.5 rounded-full bg-[#B7791F] shrink-0"></span>
        <span>Anyone with a phone or computer can become a victim if not careful.</span>
      </li>
    </ul>
  </div>

  <div class="text-center pt-2">
    <a href="learn.php" class="btn-green px-5 py-2.5 rounded-lg text-xs font-bold inline-block">
      Read Complete Safety Tips Guide &rarr;
    </a>
  </div>
</div>

<div id="password-checker" class="max-w-4xl mx-auto scroll-mt-24 mb-14">
  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#E4E1D8]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-4 pb-3 border-b border-[#E4E1D8]">
      <div>
        <h3 class="text-lg font-bold text-[#1C211E] flex items-center gap-2.5">
          <span>Password Strength Checker</span>
        </h3>
        <p class="text-xs text-[#5F665F] mt-0.5">Test any password to see how strong it is and get instant tips.</p>
      </div>
      <button id="genBtn" type="button" class="btn-outline-green px-3 py-1.5 rounded-lg text-xs font-semibold self-start sm:self-auto flex items-center gap-1.5">
        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
        <span>Generate Strong Password</span>
      </button>
    </div>

    <?php if (isset($_SESSION['user_id'])) { ?>
      <div class="relative mb-3">
        <input 
          type="password" 
          id="pwdInput" 
          placeholder="Type a password to test..." 
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-xl px-4 py-3 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
        >
        <button type="button" id="eyeBtn" class="absolute right-3.5 top-3.5 text-[#5F665F] hover:text-[#205C43] text-sm" title="Show/hide password">
          <i data-lucide="eye" class="w-4 h-4"></i>
        </button>
      </div>

      <div class="mb-4">
        <div class="flex justify-between items-center text-xs mb-1">
          <span class="text-[#5F665F]">Strength: <strong id="pwdRating" class="text-[#5F665F]">Not entered</strong></span>
          <span id="crackEstimate" class="text-[#205C43] text-[11px] font-mono"></span>
        </div>
        <div class="w-full bg-[#E6EFE9] rounded-full h-2 overflow-hidden">
          <div id="pwdBar" class="h-full bg-[#5F665F] transition-all duration-300 w-0"></div>
        </div>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs text-[#5F665F] pt-3 border-t border-[#E4E1D8]">
        <div id="c-len" class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-3.5 h-3.5 text-[#B42318]"></i> 10+ characters</div>
        <div id="c-num" class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-3.5 h-3.5 text-[#B42318]"></i> Numbers (0-9)</div>
        <div id="c-mix" class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-3.5 h-3.5 text-[#B42318]"></i> Capital letters</div>
        <div id="c-sym" class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-3.5 h-3.5 text-[#B42318]"></i> Symbols (!@#$)</div>
      </div>
    <?php } else { ?>
      <div class="text-center py-6">
        <h4 class="text-base font-bold text-[#1C211E] mb-1">Login Required to Use Password Checker</h4>
        <p class="text-xs text-[#5F665F] mb-4 max-w-md mx-auto">
          Please sign in to your CyberSafe account to test passwords and generate secure keys.
        </p>
        <a href="login.php?msg=login_required" class="btn-green py-2 px-6 rounded-lg text-xs font-bold inline-block">
          Login to Access &rarr;
        </a>
      </div>
    <?php } ?>
  </div>
</div>

<div id="section-phishing" class="max-w-4xl mx-auto scroll-mt-24 mb-14">
  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#E4E1D8]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-3 border-b border-[#E4E1D8]">
      <div>
        <h3 class="text-lg font-bold text-[#1C211E] flex items-center gap-2.5">
          <span>Phishing Detector Module</span>
        </h3>
        <p class="text-xs text-[#5F665F] mt-0.5">Detect scam emails, SMS alerts, and suspicious messages before you click.</p>
      </div>
      <a href="<?php echo isset($_SESSION['user_id']) ? 'phishing_detector.php' : 'login.php?msg=login_required'; ?>" class="btn-green py-2 px-5 rounded-lg text-xs font-bold self-start sm:self-auto shrink-0 flex items-center gap-1.5">
        <span>Open Phishing Checker</span>
      </a>
    </div>

    <div class="grid sm:grid-cols-3 gap-4 text-xs">
      <div class="bg-[#F6F5EF] p-4 rounded-xl border border-[#E4E1D8]">
        <div class="text-[#B42318] font-bold mb-1.5 flex items-center gap-2">
          <i data-lucide="alert-triangle" class="w-4 h-4"></i>
          <span>Urgent Panic</span>
        </div>
        <p class="text-[#5F665F] leading-relaxed">
          "Your bank account will be blocked today! Update your KYC right now to avoid penalty."
        </p>
      </div>
      <div class="bg-[#F6F5EF] p-4 rounded-xl border border-[#E4E1D8]">
        <div class="text-[#8A5A12] font-bold mb-1.5 flex items-center gap-2">
          <i data-lucide="gift" class="w-4 h-4"></i>
          <span>Fake Lottery / Prize</span>
        </div>
        <p class="text-[#5F665F] leading-relaxed">
          "Congratulations! You won ₹1,00,000 cash prize. Click here to claim your reward immediately."
        </p>
      </div>
      <div class="bg-[#F6F5EF] p-4 rounded-xl border border-[#E4E1D8]">
        <div class="text-[#205C43] font-bold mb-1.5 flex items-center gap-2">
          <i data-lucide="link" class="w-4 h-4"></i>
          <span>Fake Short Links</span>
        </div>
        <p class="text-[#5F665F] leading-relaxed">
          Scammers use tinyurl or bit.ly links that take you to fake login forms to steal passwords.
        </p>
      </div>
    </div>
  </div>
</div>

<div id="section-quiz" class="max-w-4xl mx-auto scroll-mt-24 mb-14">
  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#E4E1D8]">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-3 border-b border-[#E4E1D8]">
      <div>
        <h3 class="text-lg font-bold text-[#1C211E] flex items-center gap-2.5">
          <span>Cyber Security Awareness Quiz</span>
        </h3>
        <p class="text-xs text-[#5F665F] mt-0.5">Test what you know about passwords, scams, and safe browsing habits.</p>
      </div>
      <a href="<?php echo isset($_SESSION['user_id']) ? 'quiz.php' : 'login.php?msg=login_required'; ?>" class="btn-green py-2 px-5 rounded-lg text-xs font-bold self-start sm:self-auto shrink-0 flex items-center gap-1.5">
        <span>Start 10-Question Quiz</span>
      </a>
    </div>

    <div class="grid sm:grid-cols-3 gap-3 text-xs text-[#1C211E]">
      <div class="flex items-center gap-3 bg-[#F6F5EF] p-3.5 rounded-xl border border-[#E4E1D8]">
        <div>
          <strong class="block text-[#1C211E]">10 Practical Questions</strong>
          <span class="text-[#5F665F]">1 mark each, multiple choice</span>
        </div>
      </div>
      <div class="flex items-center gap-3 bg-[#F6F5EF] p-3.5 rounded-xl border border-[#E4E1D8]">
        <div>
          <strong class="block text-[#1C211E]">Instant Results</strong>
          <span class="text-[#5F665F]">Learn why each answer is right</span>
        </div>
      </div>
      <div class="flex items-center gap-3 bg-[#F6F5EF] p-3.5 rounded-xl border border-[#E4E1D8]">
        <div>
          <strong class="block text-[#1C211E]">Check Your Knowledge</strong>
          <span class="text-[#5F665F]">Score 7 or more to pass</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="section-idcard" class="max-w-4xl mx-auto scroll-mt-24 mb-16">
  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#C9DDD0]">
    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
      <div class="flex items-start gap-4">
        <div>
          <h3 class="text-lg font-bold text-[#1C211E] flex items-center gap-2">
            Get your CyberSafe ID card
          </h3>
          <p class="text-xs text-[#5F665F] mt-1.5 leading-relaxed max-w-xl">
            Finish all 5 modules, upload your photo, and download your ID card as a PNG or print it.
          </p>
        </div>
      </div>
      <a href="<?php echo isset($_SESSION['user_id']) ? 'id_card.php' : 'login.php?msg=login_required'; ?>" class="btn-green px-6 py-3 rounded-xl text-xs font-bold shrink-0 text-center self-stretch md:self-auto flex items-center justify-center gap-2">
        <span>Generate ID Card</span>
      </a>
    </div>
  </div>
</div>

<script>
  var pwdInput = document.getElementById('pwdInput');
  var pwdBar = document.getElementById('pwdBar');
  var pwdRating = document.getElementById('pwdRating');
  var crackEstimate = document.getElementById('crackEstimate');
  var eyeBtn = document.getElementById('eyeBtn');
  var genBtn = document.getElementById('genBtn');

  var cLen = document.getElementById('c-len');
  var cNum = document.getElementById('c-num');
  var cMix = document.getElementById('c-mix');
  var cSym = document.getElementById('c-sym');

  function setCheck(el, text, ok) {
    el.innerHTML = ok 
      ? '<i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#205C43] inline"></i> <span class="text-[#205C43]">' + text + '</span>'
      : '<i data-lucide="x-circle" class="w-3.5 h-3.5 text-[#B42318] inline"></i> <span>' + text + '</span>';
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  function checkPassword(pass) {
    if (!pass) {
      pwdBar.style.width = '0%';
      pwdRating.innerText = 'Not entered';
      pwdRating.className = 'text-[#5F665F]';
      crackEstimate.innerText = '';
      setCheck(cLen, '10+ characters', false);
      setCheck(cNum, 'Numbers (0-9)', false);
      setCheck(cMix, 'Capital letters', false);
      setCheck(cSym, 'Symbols (!@#$)', false);
      return;
    }

    var hasLen = pass.length >= 10;
    var hasNum = /[0-9]/.test(pass);
    var hasMix = /[A-Z]/.test(pass) && /[a-z]/.test(pass);
    var hasSym = /[^A-Za-z0-9]/.test(pass);

    setCheck(cLen, '10+ characters', hasLen);
    setCheck(cNum, 'Numbers (0-9)', hasNum);
    setCheck(cMix, 'Capital letters', hasMix);
    setCheck(cSym, 'Symbols (!@#$)', hasSym);

    var score = 0;
    if (pass.length >= 8) score += 20;
    if (pass.length >= 12) score += 20;
    if (hasNum) score += 20;
    if (hasMix) score += 20;
    if (hasSym) score += 20;

    pwdBar.style.width = score + '%';

    if (score <= 40) {
      pwdBar.className = 'h-full bg-[#B42318] transition-all duration-300';
      pwdRating.innerText = 'Weak - Easy to guess';
      pwdRating.className = 'text-[#B42318] font-bold';
      crackEstimate.innerText = 'Cracked in: a few seconds';
    } else if (score <= 60) {
      pwdBar.className = 'h-full bg-[#B7791F] transition-all duration-300';
      pwdRating.innerText = 'Medium - Can be improved';
      pwdRating.className = 'text-[#8A5A12] font-bold';
      crackEstimate.innerText = 'Cracked in: a few hours or days';
    } else {
      pwdBar.className = 'h-full bg-[#205C43] transition-all duration-300';
      pwdRating.innerText = 'Strong - Very safe!';
      pwdRating.className = 'text-[#205C43] font-bold';
      crackEstimate.innerText = 'Cracked in: centuries';
    }
  }

  if (pwdInput) {
    pwdInput.addEventListener('input', function() {
      checkPassword(pwdInput.value);
    });

    var visible = false;
    if (eyeBtn) {
      eyeBtn.addEventListener('click', function() {
        visible = !visible;
        pwdInput.type = visible ? 'text' : 'password';
        eyeBtn.innerHTML = visible ? '<i data-lucide="eye-off" class="w-4 h-4"></i>' : '<i data-lucide="eye" class="w-4 h-4"></i>';
        if (typeof lucide !== 'undefined') lucide.createIcons();
      });
    }

    if (genBtn) {
      genBtn.addEventListener('click', function() {
        var chars = "abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ23456789!@#$%^&*";
        var pass = "";
        for (var i = 0; i < 14; i++) {
          pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        pwdInput.value = pass;
        pwdInput.type = 'text';
        visible = true;
        if (eyeBtn) {
          eyeBtn.innerHTML = '<i data-lucide="eye-off" class="w-4 h-4"></i>';
          if (typeof lucide !== 'undefined') lucide.createIcons();
        }
        checkPassword(pass);
      });
    }
  }
</script>

<?php include "includes/footer.php"; ?>
