<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$progress = get_module_progress($conn, (int) $_SESSION['user_id']);
$module_done = $progress['modules']['generator']['done'];

$title = "Password Generator";
include "includes/header.php";
?>

<div class="max-w-2xl mx-auto">
  <div class="mb-6 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] mb-2 flex items-center justify-center sm:justify-start gap-2.5">
      <span>Password Generator</span>
    </h1>
    <p class="text-sm text-[#5F665F]">
      Generate secure, random passwords directly in your browser using cryptographically secure random values.
    </p>
    <p id="moduleStatus" class="text-xs text-[#205C43] font-semibold mt-2 items-center justify-center sm:justify-start gap-1.5 <?php echo $module_done ? 'flex' : 'hidden'; ?>">
      <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
      <span>Module completed</span>
    </p>
  </div>

  <div class="cyber-box rounded-2xl p-6 sm:p-7 border border-[#E4E1D8] mb-6">
    
    <label class="block text-xs font-semibold text-[#1C211E] mb-2">
      Generated Secure Password:
    </label>
    <div class="flex flex-col sm:flex-row items-stretch gap-2 mb-6">
      <div class="relative flex-1">
        <input 
          type="text" 
          id="generatedPassword" 
          readonly 
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] text-[#205C43] font-mono text-base sm:text-lg px-4 py-3 rounded-xl focus:outline-none select-all"
          placeholder="Click generate below..."
        >
      </div>
      <button 
        type="button" 
        id="copyBtn" 
        class="btn-green px-5 py-3 rounded-xl text-xs font-bold flex items-center justify-center gap-2 shrink-0 transition-all"
      >
        <i data-lucide="copy" class="w-4 h-4" id="copyIcon"></i>
        <span id="copyText">Copy Password</span>
      </button>
    </div>

    <div class="border-t border-[#E4E1D8] pt-5 space-y-5">
      
      <div>
        <div class="flex justify-between items-center mb-2">
          <label class="text-xs font-semibold text-[#1C211E]">Password Length:</label>
          <span class="text-sm font-bold text-[#205C43] font-mono bg-[#F6F5EF] px-2.5 py-0.5 rounded border border-[#E4E1D8]">
            <span id="lengthDisplay">16</span> characters
          </span>
        </div>
        <div class="flex items-center gap-3">
          <input 
            type="range" 
            id="lengthRange" 
            min="6" 
            max="32" 
            value="16" 
            class="w-full accent-[#205C43] cursor-pointer"
          >
          <input 
            type="number" 
            id="lengthNum" 
            min="6" 
            max="32" 
            value="16" 
            class="w-16 bg-[#F6F5EF] border border-[#E4E1D8] text-[#205C43] text-center text-xs py-1.5 rounded-lg focus:outline-none focus:border-[#BDB9AE] font-mono"
          >
        </div>
      </div>

      <div>
        <label class="block text-xs font-semibold text-[#1C211E] mb-2.5">Include Character Types:</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
          <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8] hover:border-[#BDB9AE] cursor-pointer transition-colors">
            <input type="checkbox" id="chkUpper" checked class="accent-[#205C43] w-4 h-4 rounded">
            <span class="text-[#1C211E]">Uppercase Letters (A-Z)</span>
          </label>
          <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8] hover:border-[#BDB9AE] cursor-pointer transition-colors">
            <input type="checkbox" id="chkLower" checked class="accent-[#205C43] w-4 h-4 rounded">
            <span class="text-[#1C211E]">Lowercase Letters (a-z)</span>
          </label>
          <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8] hover:border-[#BDB9AE] cursor-pointer transition-colors">
            <input type="checkbox" id="chkNumbers" checked class="accent-[#205C43] w-4 h-4 rounded">
            <span class="text-[#1C211E]">Numbers (0-9)</span>
          </label>
          <label class="flex items-center gap-2.5 p-3 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8] hover:border-[#BDB9AE] cursor-pointer transition-colors">
            <input type="checkbox" id="chkSymbols" checked class="accent-[#205C43] w-4 h-4 rounded">
            <span class="text-[#1C211E]">Special Symbols (!@#$%)</span>
          </label>
        </div>
      </div>

      <div class="pt-2 flex flex-col sm:flex-row items-center justify-between gap-3">
        <button 
          type="button" 
          id="generateBtn" 
          class="btn-green w-full sm:w-auto px-6 py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-2"
        >
          <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
          <span>Generate New Password</span>
        </button>

        <a href="password_analyzer.php" class="text-xs text-[#5F665F] hover:text-[#205C43] transition-colors flex items-center gap-1.5">
          <span>Test strength in Password Analyzer &rarr;</span>
        </a>
      </div>

    </div>
  </div>

</div>

<script>
  var moduleDone = <?php echo $module_done ? 'true' : 'false'; ?>;
  function reportModuleComplete() {
    if (moduleDone) return;
    moduleDone = true;
    var body = new FormData();
    body.append('module', 'generator');
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
  var lengthRange = document.getElementById('lengthRange');
  var lengthNum = document.getElementById('lengthNum');
  var lengthDisplay = document.getElementById('lengthDisplay');

  var chkUpper = document.getElementById('chkUpper');
  var chkLower = document.getElementById('chkLower');
  var chkNumbers = document.getElementById('chkNumbers');
  var chkSymbols = document.getElementById('chkSymbols');

  var generatedPassword = document.getElementById('generatedPassword');
  var generateBtn = document.getElementById('generateBtn');
  var copyBtn = document.getElementById('copyBtn');
  var copyText = document.getElementById('copyText');

  var uppercaseChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
  var lowercaseChars = "abcdefghijklmnopqrstuvwxyz";
  var numberChars = "0123456789";
  var symbolChars = "!@#$%^&*()_+~`|}{[]:;?><,./-=";

  function updateLength(val) {
    var length = parseInt(val);
    if (isNaN(length)) length = 16;
    if (length < 6) length = 6;
    if (length > 32) length = 32;

    lengthRange.value = length;
    lengthNum.value = length;
    lengthDisplay.innerText = length;
    generatePassword();
  }

  lengthRange.addEventListener('input', function() {
    updateLength(this.value);
  });

  lengthNum.addEventListener('input', function() {
    updateLength(this.value);
  });

  function getSecureRandomInt(max) {
    if (window.crypto && window.crypto.getRandomValues) {
      var arr = new Uint32Array(1);
      window.crypto.getRandomValues(arr);
      return arr[0] % max;
    } else {
      return Math.floor(Math.random() * max);
    }
  }

  function generatePassword() {
    var length = parseInt(lengthRange.value);
    var pool = "";

    if (chkUpper.checked) pool += uppercaseChars;
    if (chkLower.checked) pool += lowercaseChars;
    if (chkNumbers.checked) pool += numberChars;
    if (chkSymbols.checked) pool += symbolChars;

    if (pool === "") {
      generatedPassword.value = "Select at least one character type!";
      generatedPassword.classList.remove('text-[#205C43]');
      generatedPassword.classList.add('text-[#B42318]');
      return;
    }

    generatedPassword.classList.remove('text-[#B42318]');
    generatedPassword.classList.add('text-[#205C43]');

    var result = "";
    for (var i = 0; i < length; i++) {
      var idx = getSecureRandomInt(pool.length);
      result += pool.charAt(idx);
    }

    generatedPassword.value = result;
  }

  generateBtn.addEventListener('click', function() {
    generatePassword();
    if (generatedPassword.value.indexOf("Select at least") === -1) reportModuleComplete();
  });
  [chkUpper, chkLower, chkNumbers, chkSymbols].forEach(function(chk) {
    chk.addEventListener('change', generatePassword);
  });

  copyBtn.addEventListener('click', function() {
    var pass = generatedPassword.value;
    if (!pass || pass.indexOf("Select at least") !== -1) return;

    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(pass).then(showCopiedFeedback);
    } else {
      generatedPassword.select();
      document.execCommand('copy');
      showCopiedFeedback();
    }
  });

  function showCopiedFeedback() {
    reportModuleComplete();
    copyText.innerText = "Copied!";
    copyBtn.classList.remove('btn-green');
    copyBtn.classList.add('bg-[#F6F5EF]', 'text-[#205C43]', 'border', 'border-[#205C43]');
    setTimeout(function() {
      copyText.innerText = "Copy Password";
      copyBtn.classList.remove('bg-[#F6F5EF]', 'text-[#205C43]', 'border', 'border-[#205C43]');
      copyBtn.classList.add('btn-green');
    }, 2000);
  }

  generatePassword();
</script>

<?php include "includes/footer.php"; ?>
