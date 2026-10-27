<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$progress = get_module_progress($conn, (int) $_SESSION['user_id']);
$module_done = $progress['modules']['analyzer']['done'];

$title = "Password Analyzer";
include "includes/header.php";
?>

<div class="max-w-2xl mx-auto">
  <div class="mb-6 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] mb-2 flex items-center justify-center sm:justify-start gap-2.5">
      <span>Password Analyzer</span>
    </h1>
    <p class="text-sm text-[#5F665F]">
      Type any password to test how secure it is against modern brute-force attacks and hacking tricks.
    </p>
    <p id="moduleStatus" class="text-xs text-[#205C43] font-semibold mt-2 items-center justify-center sm:justify-start gap-1.5 <?php echo $module_done ? 'flex' : 'hidden'; ?>">
      <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
      <span>Module completed</span>
    </p>
  </div>

  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#E4E1D8] mb-6">
    <div class="flex items-center justify-between gap-2 mb-3">
      <label class="text-xs font-semibold text-[#1C211E]">
        Enter Password to Analyze:
      </label>
      <a href="password_generator.php" class="text-xs text-[#205C43] hover:underline flex items-center gap-1 font-semibold">
        <span>Need a new password?</span>
      </a>
    </div>

    <div class="relative mb-4">
      <input 
        type="password" 
        id="pwdInput" 
        placeholder="Type your password here..." 
        class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-xl px-4 py-3 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
        autocomplete="off"
      >
      <button type="button" id="eyeBtn" class="absolute right-3.5 top-3.5 text-[#5F665F] hover:text-[#205C43] text-sm" title="Show/hide password">
        <i data-lucide="eye" class="w-4 h-4"></i>
      </button>
    </div>

    <div class="mb-5 bg-[#F6F5EF] p-4 rounded-xl border border-[#E4E1D8]">
      <div class="flex justify-between items-center text-xs mb-1.5">
        <span class="text-[#5F665F]">Strength Rating: <strong id="pwdRating" class="text-[#5F665F]">Not entered</strong></span>
        <span id="crackEstimate" class="text-[#205C43] text-xs font-mono"></span>
      </div>
      <div class="w-full bg-[#E6EFE9] rounded-full h-2.5 overflow-hidden">
        <div id="pwdBar" class="h-full bg-[#5F665F] transition-all duration-300 w-0"></div>
      </div>
    </div>

    <h3 class="text-xs font-bold text-[#1C211E] mb-2 uppercase tracking-wider">Security Requirements Checklist:</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs text-[#5F665F] mb-6">
      <div id="c-len" class="flex items-center gap-2 p-2.5 rounded-lg bg-[#F6F5EF] border border-[#E4E1D8]">
        <i data-lucide="x-circle" class="w-4 h-4 text-[#B42318]"></i>
        <span>At least 10 characters</span>
      </div>
      <div id="c-mix" class="flex items-center gap-2 p-2.5 rounded-lg bg-[#F6F5EF] border border-[#E4E1D8]">
        <i data-lucide="x-circle" class="w-4 h-4 text-[#B42318]"></i>
        <span>Both uppercase &amp; lowercase letters</span>
      </div>
      <div id="c-num" class="flex items-center gap-2 p-2.5 rounded-lg bg-[#F6F5EF] border border-[#E4E1D8]">
        <i data-lucide="x-circle" class="w-4 h-4 text-[#B42318]"></i>
        <span>At least one number (0-9)</span>
      </div>
      <div id="c-sym" class="flex items-center gap-2 p-2.5 rounded-lg bg-[#F6F5EF] border border-[#E4E1D8]">
        <i data-lucide="x-circle" class="w-4 h-4 text-[#B42318]"></i>
        <span>At least one special symbol (!@#$)</span>
      </div>
    </div>

    <div id="suggestionsBox" class="p-4 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8] text-xs">
      <h4 class="font-bold text-[#205C43] mb-1.5 flex items-center gap-1.5"> <span>Recommendations:</span>
      </h4>
      <ul id="suggestionList" class="space-y-1 text-[#1C211E]">
        <li>&bull; Enter a password above to view personalized security recommendations.</li>
      </ul>
    </div>
  </div>
</div>

<script>
  var moduleDone = <?php echo $module_done ? 'true' : 'false'; ?>;
  function reportModuleComplete() {
    if (moduleDone) return;
    moduleDone = true;
    var body = new FormData();
    body.append('module', 'analyzer');
    body.append('csrf', <?php echo json_encode(module_csrf_token()); ?>);
    fetch('complete_module.php', { method: 'POST', body: body, credentials: 'same-origin' })
      .then(function(res) { return res.json(); })
      .then(function(data) {
        if (data && data.ok) {
          var status = document.getElementById('moduleStatus');
          status.classList.remove('hidden');
          status.classList.add('flex');
        } else {
          moduleDone = false;
        }
      })
      .catch(function() { moduleDone = false; });
  }
</script>

<script>
  var pwdInput = document.getElementById('pwdInput');
  var pwdBar = document.getElementById('pwdBar');
  var pwdRating = document.getElementById('pwdRating');
  var crackEstimate = document.getElementById('crackEstimate');
  var eyeBtn = document.getElementById('eyeBtn');
  var suggestionList = document.getElementById('suggestionList');

  var cLen = document.getElementById('c-len');
  var cMix = document.getElementById('c-mix');
  var cNum = document.getElementById('c-num');
  var cSym = document.getElementById('c-sym');

  function setBadge(el, text, ok) {
    el.innerHTML = ok 
      ? '<i data-lucide="check-circle" class="w-4 h-4 text-[#205C43] inline"></i> <span class="text-[#205C43] font-medium">' + text + '</span>'
      : '<i data-lucide="x-circle" class="w-4 h-4 text-[#B42318] inline"></i> <span class="text-[#5F665F]">' + text + '</span>';
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  function analyze(pass) {
    if (!pass) {
      pwdBar.style.width = '0%';
      pwdRating.innerText = 'Not entered';
      pwdRating.className = 'text-[#5F665F]';
      crackEstimate.innerText = '';
      setBadge(cLen, 'At least 10 characters', false);
      setBadge(cMix, 'Both uppercase & lowercase letters', false);
      setBadge(cNum, 'At least one number (0-9)', false);
      setBadge(cSym, 'At least one special symbol (!@#$)', false);
      suggestionList.innerHTML = '<li>&bull; Enter a password above to view personalized security recommendations.</li>';
      return;
    }

    var hasLen = pass.length >= 10;
    var hasUpper = /[A-Z]/.test(pass);
    var hasLower = /[a-z]/.test(pass);
    var hasMix = hasUpper && hasLower;
    var hasNum = /[0-9]/.test(pass);
    var hasSym = /[^A-Za-z0-9]/.test(pass);

    setBadge(cLen, 'At least 10 characters (' + pass.length + ' entered)', hasLen);
    setBadge(cMix, 'Both uppercase & lowercase letters', hasMix);
    setBadge(cNum, 'At least one number (0-9)', hasNum);
    setBadge(cSym, 'At least one special symbol (!@#$)', hasSym);

    var score = 0;
    if (pass.length >= 8) score += 20;
    if (pass.length >= 12) score += 20;
    if (hasMix) score += 20;
    if (hasNum) score += 20;
    if (hasSym) score += 20;

    pwdBar.style.width = score + '%';

    var tips = [];
    if (!hasLen) tips.push('Make your password at least 10-12 characters long. Length is the #1 defense against cracking.');
    if (!hasMix) tips.push('Add both capital (A-Z) and small (a-z) letters to make it harder to guess.');
    if (!hasNum) tips.push('Include numbers (0-9) inside the password, not just at the very end.');
    if (!hasSym) tips.push('Add special symbols like #, $, %, or ! to maximize complexity.');

    var commonWords = ['password', '123456', 'admin', 'qwerty', 'welcome', 'letmein'];
    for (var i = 0; i < commonWords.length; i++) {
      if (pass.toLowerCase().indexOf(commonWords[i]) !== -1) {
        score = Math.min(score, 30);
        tips.unshift('Avoid common dictionary words like "' + commonWords[i] + '". Hackers test these first!');
      }
    }

    if (score <= 40) {
      pwdBar.className = 'h-full bg-[#B42318] transition-all duration-300';
      pwdRating.innerText = 'Weak - Easy to Crack';
      pwdRating.className = 'text-[#B42318] font-bold';
      crackEstimate.innerText = 'Est. crack time: a few seconds';
    } else if (score <= 75) {
      pwdBar.className = 'h-full bg-[#B7791F] transition-all duration-300';
      pwdRating.innerText = 'Moderate - Fairly Safe';
      pwdRating.className = 'text-[#8A5A12] font-bold';
      crackEstimate.innerText = 'Est. crack time: a few weeks or months';
    } else {
      pwdBar.className = 'h-full bg-[#205C43] transition-all duration-300';
      pwdRating.innerText = 'Strong - Very Secure!';
      pwdRating.className = 'text-[#205C43] font-bold';
      crackEstimate.innerText = 'Est. crack time: centuries';
    }

    if (tips.length === 0) {
      suggestionList.innerHTML = '<li class="text-[#205C43] font-medium flex items-center gap-1.5"><i data-lucide="check" class="w-4 h-4"></i> Great job! This password meets all security best practices.</li>';
    } else {
      var html = '';
      tips.forEach(function(t) {
        html += '<li>&bull; ' + t + '</li>';
      });
      suggestionList.innerHTML = html;
    }
    if (typeof lucide !== 'undefined') lucide.createIcons();
  }

  var completeTimer = null;
  pwdInput.addEventListener('input', function() {
    analyze(pwdInput.value);
    clearTimeout(completeTimer);
    if (pwdInput.value.length > 0) {
      completeTimer = setTimeout(reportModuleComplete, 800);
    }
  });

  var isVisible = false;
  eyeBtn.addEventListener('click', function() {
    isVisible = !isVisible;
    pwdInput.type = isVisible ? 'text' : 'password';
    eyeBtn.innerHTML = isVisible ? '<i data-lucide="eye-off" class="w-4 h-4"></i>' : '<i data-lucide="eye" class="w-4 h-4"></i>';
    if (typeof lucide !== 'undefined') lucide.createIcons();
  });
</script>

<?php include "includes/footer.php"; ?>
