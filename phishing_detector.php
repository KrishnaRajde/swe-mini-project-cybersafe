<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$message = "";
$flags = [];
$result = "";

if (isset($_POST['check'])) {
    $message = trim($_POST['message']);
    $text = strtolower($message);

    if ($message != "") {
        $urgent = ["urgent", "immediately", "act now", "within 24 hours", "account suspended", "account will be blocked", "verify now", "last warning"];
        $money = ["you have won", "lottery", "prize", "claim your reward", "cashback", "free gift", "refund", "gift card"];
        $personal = ["otp", "password", "pin", "cvv", "bank details", "aadhaar", "kyc", "login details", "card number"];
        $shortLinks = ["bit.ly", "tinyurl", "goo.gl", "t.co", "is.gd", "cutt.ly"];

        foreach ($urgent as $w) {
            if (strpos($text, $w) !== false) {
                $flags[] = "Creates false urgency or panic (e.g., \"" . $w . "\")";
            }
        }
        foreach ($money as $w) {
            if (strpos($text, $w) !== false) {
                $flags[] = "Promises free money or lottery prizes (e.g., \"" . $w . "\")";
            }
        }
        foreach ($personal as $w) {
            if (strpos($text, $w) !== false) {
                $flags[] = "Asks for sensitive personal info (e.g., \"" . $w . "\")";
            }
        }
        foreach ($shortLinks as $w) {
            if (strpos($text, $w) !== false) {
                $flags[] = "Uses a shortened link (" . $w . ") which hides where the link actually goes";
            }
        }

        if (strpos($text, "http://") !== false) {
            $flags[] = "Contains an insecure web link (http instead of secure https)";
        }
        if (preg_match('/https?:\/\/\d+\.\d+\.\d+\.\d+/', $text)) {
            $flags[] = "The link uses raw numbers (IP address) instead of a real company website name";
        }
        if (strpos($text, "dear customer") !== false || strpos($text, "dear user") !== false) {
            $flags[] = "Uses a generic greeting ('Dear customer') instead of addressing you by name";
        }
        if (substr_count($message, "!") >= 3) {
            $flags[] = "Uses too many exclamation marks (!!!) to make it look dramatic";
        }

        $count = count($flags);
        if ($count == 0) {
            $result = "Looks Safe";
        } elseif ($count <= 2) {
            $result = "Suspicious";
        } else {
            $result = "Likely Phishing";
        }

        mark_module_complete($conn, (int) $_SESSION['user_id'], "phishing");
    }
}

$title = "Phishing Detector";
include "includes/header.php";
?>

<div class="max-w-3xl mx-auto">
  <div class="mb-6 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] mb-2 flex items-center justify-center sm:justify-start gap-2.5">
      <span>Phishing Message Detector</span>
    </h1>
    <p class="text-sm text-[#5F665F]">
      Got a weird email, SMS, or WhatsApp message? Paste it here to check for common scam warning signs.
    </p>
  </div>

  <div class="cyber-box rounded-xl p-5 sm:p-6 mb-6 border border-[#E4E1D8]">
    <form method="post">
      <label class="block text-xs font-semibold text-[#1C211E] mb-2">
        Paste the suspicious message or email text below:
      </label>
      <textarea 
        name="message" 
        id="msgBox" 
        rows="6" 
        class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg p-3 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
        placeholder="Example: Dear customer, your account will be blocked within 24 hours. Please click http://... to verify your KYC."
        required
      ><?php echo htmlspecialchars($message); ?></textarea>

      <div class="mt-2.5 flex flex-wrap items-center gap-2 text-xs">
        <span class="text-[#5F665F]">Try a sample:</span>
        <button type="button" class="sample-btn px-2.5 py-1 rounded bg-[#F6F5EF] text-[#1C211E] hover:text-[#205C43] border border-[#E4E1D8] hover:border-[#BDB9AE] transition-colors" data-type="bank">
          Bank KYC Scam
        </button>
        <button type="button" class="sample-btn px-2.5 py-1 rounded bg-[#F6F5EF] text-[#1C211E] hover:text-[#205C43] border border-[#E4E1D8] hover:border-[#BDB9AE] transition-colors" data-type="lottery">
          Lottery Prize Scam
        </button>
        <button type="button" class="sample-btn px-2.5 py-1 rounded bg-[#F6F5EF] text-[#1C211E] hover:text-[#205C43] border border-[#E4E1D8] hover:border-[#BDB9AE] transition-colors" data-type="normal">
          Normal Message
        </button>
      </div>

      <div class="mt-5 flex justify-end">
        <button type="submit" name="check" class="btn-green px-6 py-2.5 rounded-lg text-xs font-bold flex items-center gap-2">
          <span>Check Message</span>
        </button>
      </div>
    </form>
  </div>

  <?php if ($result != "") { 
      $cardColor = "border-[#C9DDD0] bg-[#E6EFE9]";
      $textColor = "text-[#205C43]";
      $lucideIcon = "check-circle-2";
      $summary = "We didn't find any common phishing keywords or red flags.";

      if ($result == "Suspicious") {
          $cardColor = "border-[#EBD5AC] bg-[#FBF4E6]";
          $textColor = "text-[#8A5A12]";
          $lucideIcon = "alert-triangle";
          $summary = "Be careful! This message contains some suspicious signs often used in scams.";
      } elseif ($result == "Likely Phishing") {
          $cardColor = "border-[#F0C8C2] bg-[#FDF1EF]";
          $textColor = "text-[#B42318]";
          $lucideIcon = "alert-circle";
          $summary = "Warning! This message has multiple red flags commonly seen in phishing scams. Do not click links or share details!";
      }
  ?>
    <div class="rounded-xl p-5 border-2 <?php echo $cardColor; ?> mb-6">
      <div class="flex items-center gap-3 mb-2">
        <i data-lucide="<?php echo $lucideIcon; ?>" class="w-6 h-6 <?php echo $textColor; ?>"></i>
        <h3 class="text-lg font-bold <?php echo $textColor; ?>">
          Result: <?php echo $result; ?>
        </h3>
      </div>
      <p class="text-xs text-[#1C211E] mb-3"><?php echo $summary; ?></p>

      <?php if (count($flags) > 0) { ?>
        <div class="bg-[#F6F5EF] rounded-lg p-3.5 border border-[#E4E1D8] text-xs">
          <strong class="text-[#1C211E] block mb-1.5">Warning signs detected:</strong>
          <ul class="space-y-1.5 text-[#1C211E]">
            <?php foreach ($flags as $f) { ?>
              <li class="flex items-start gap-2">
                <span class="text-[#B42318] font-bold">&bull;</span>
                <span><?php echo htmlspecialchars($f); ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

  <div class="cyber-box rounded-xl p-5 text-xs text-[#1C211E] border border-[#E4E1D8]">
    <h4 class="font-bold text-[#205C43] mb-2 text-sm flex items-center gap-2"> <span>Quick Safety Tips</span>
    </h4>
    <ul class="space-y-1 text-[#5F665F]">
      <li>&bull; Never share OTPs, PINs, or passwords with anyone over call or SMS.</li>
      <li>&bull; Check the sender's email address closely to make sure it's genuine.</li>
      <li>&bull; If an email looks urgent, open the official app or website directly instead of clicking links.</li>
    </ul>
  </div>
</div>

<script>
  var msgBox = document.getElementById('msgBox');
  var samples = {
    bank: "URGENT: Dear customer, your bank account will be blocked within 24 hours due to pending KYC verification. Please click http://192.168.1.1/update-kyc and submit your OTP immediately to avoid disruption!",
    lottery: "CONGRATULATIONS!! You have won a cash prize of ₹25,000 in our lucky draw! Claim your reward today at bit.ly/free-reward-gift card before it expires!",
    normal: "Hey, can you review the report we discussed this morning? It's saved in the project folder on Google Drive. Thanks!"
  };

  document.querySelectorAll('.sample-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var t = this.getAttribute('data-type');
      if (samples[t]) {
        msgBox.value = samples[t];
      }
    });
  });
</script>

<?php include "includes/footer.php"; ?>
