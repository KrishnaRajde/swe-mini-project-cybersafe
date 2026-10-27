<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$user_id = (int) $_SESSION['user_id'];

if (isset($_POST['complete_learn']) && module_csrf_valid($_POST['csrf'] ?? null)) {
    mark_module_complete($conn, $user_id, "learn");
    header("Location: learn.php#learn-complete");
    exit;
}

$progress = get_module_progress($conn, $user_id);
$learn_done = $progress['modules']['learn']['done'];

$title = "Safety Tips";
include "includes/header.php";

$tipsList = [
    [
        "icon" => "mail-warning",
        "title" => "Phishing & Fake Messages",
        "desc" => "Phishing is when scammers send fake emails, SMS, or WhatsApp messages pretending to be your bank, delivery service, or a friend. Their goal is to get your password or OTP.",
        "points" => [
            "Always check the sender's actual email address, not just their display name.",
            "Never click on unknown or shortened links in urgent messages.",
            "No bank or company will ever call or message you to ask for your OTP or PIN."
        ]
    ],
    [
        "icon" => "key-round",
        "title" => "Creating Strong Passwords",
        "desc" => "Using the same password everywhere is very dangerous. If one small website gets hacked, attackers will try that same password on your email, social media, and banking.",
        "points" => [
            "Use at least 12 characters combining letters, numbers, and symbols.",
            "Never use easy-to-guess info like your name, birthday, or '123456'.",
            "Consider using a trusted password manager (like Bitwarden or Google Password Manager) to remember them."
        ]
    ],
    [
        "icon" => "shield-alert",
        "title" => "Malware & Ransomware",
        "desc" => "Malware is malicious software (like viruses or spyware) that can steal your data or spy on your keystrokes. Ransomware locks your personal files and asks for money to unlock them.",
        "points" => [
            "Only download apps from official sources like Google Play, Apple App Store, or official websites.",
            "Avoid downloading cracked software, cheats, or free movies from shady websites.",
            "Regularly back up your important photos and documents to Google Drive or an external pendrive."
        ]
    ],
    [
        "icon" => "wifi",
        "title" => "Using Public Wi-Fi Safely",
        "desc" => "Free Wi-Fi at cafes, railway stations, and hotels is usually not secure. Other people connected to the same network might be able to intercept the websites you visit.",
        "points" => [
            "Never open net banking or make UPI/card payments on public Wi-Fi.",
            "Use your mobile data (hotspot) instead when doing anything sensitive.",
            "Turn off Wi-Fi auto-connect on your phone so it doesn't automatically join random open networks."
        ]
    ],
    [
        "icon" => "refresh-cw",
        "title" => "Keep Your Apps and Phone Updated",
        "desc" => "When your phone, laptop, or browser asks you to update, don't keep clicking 'Remind me tomorrow'. Most updates are issued to fix dangerous security bugs.",
        "points" => [
            "Turn on automatic updates for your phone operating system (Android/iOS) and Windows.",
            "Keep your web browsers (Chrome, Edge, Firefox) updated to the latest version.",
            "Delete old apps on your phone that you don't use anymore."
        ]
    ],
    [
        "icon" => "fingerprint",
        "title" => "Turn on Two-Factor Authentication (2FA)",
        "desc" => "Two-Factor Authentication adds a second step when you sign in (like an OTP sent to your phone or an authenticator code). Even if someone steals your password, they cannot log in without this code.",
        "points" => [
            "Turn on 2FA first on your Gmail/Google account and your primary social accounts.",
            "Use authenticator apps (like Google Authenticator) whenever possible.",
            "Save your backup codes in a safe place in case you lose your phone."
        ]
    ]
];
?>

<div class="max-w-4xl mx-auto">
  <div class="mb-8 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] mb-2 flex items-center justify-center sm:justify-start gap-2.5">
      <span>Cyber Safety Tips</span>
    </h1>
    <p class="text-sm text-[#5F665F]">
      Simple, practical guidelines to keep your accounts and personal data safe online.
    </p>
  </div>

  <div class="grid md:grid-cols-2 gap-5 mb-10">
    <?php foreach ($tipsList as $tip) { ?>
      <div class="cyber-box rounded-xl p-5 flex flex-col justify-between border border-[#E4E1D8]">
        <div>
          <div class="flex items-center gap-2.5 mb-3">
            <h2 class="text-base font-bold text-[#1C211E]"><?php echo $tip['title']; ?></h2>
          </div>
          <p class="text-xs text-[#5F665F] leading-relaxed mb-4">
            <?php echo $tip['desc']; ?>
          </p>
        </div>

        <div class="bg-[#F6F5EF] rounded-lg p-3.5 border border-[#E4E1D8] text-xs">
          <strong class="text-[#205C43] block mb-2 font-semibold flex items-center gap-1.5">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Best Practices:</span>
          </strong>
          <ul class="space-y-1.5 text-[#1C211E]">
            <?php foreach ($tip['points'] as $pt) { ?>
              <li class="flex items-start gap-2">
                <span class="text-[#205C43] text-xs mt-0.5">&bull;</span>
                <span><?php echo $pt; ?></span>
              </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    <?php } ?>
  </div>

  <div id="learn-complete" class="cyber-box rounded-xl p-5 mb-6 border border-[#E4E1D8] flex flex-col sm:flex-row sm:items-center justify-between gap-4 scroll-mt-24">
    <div>
      <h3 class="text-sm font-bold text-[#1C211E] mb-1 flex items-center gap-2"> <span><?php echo $learn_done ? 'Safety Tips module completed' : 'Finished reading all the tips?'; ?></span>
      </h3>
      <p class="text-xs text-[#5F665F]">
        <?php echo $learn_done ? 'Great work! This module now counts towards your ID card.' : 'Mark this module as completed to add it to your progress.'; ?>
      </p>
    </div>
    <?php if ($learn_done) { ?>
      <span class="px-4 py-2 rounded-lg text-xs font-bold text-[#205C43] border border-[#C9DDD0] bg-[#E6EFE9] flex items-center gap-1.5 self-start sm:self-auto shrink-0">
        <i data-lucide="check" class="w-3.5 h-3.5"></i>
        <span>Completed</span>
      </span>
    <?php } else { ?>
      <form method="post" class="shrink-0">
        <input type="hidden" name="csrf" value="<?php echo htmlspecialchars(module_csrf_token()); ?>">
        <button type="submit" name="complete_learn" class="btn-green py-2.5 px-5 rounded-lg text-xs font-bold inline-flex items-center gap-2">
          <i data-lucide="check-circle-2" class="w-4 h-4"></i>
          <span>Mark as Completed</span>
        </button>
      </form>
    <?php } ?>
  </div>

  <div class="cyber-box rounded-xl p-6 text-center border-t-2 border-t-[#205C43] border border-[#E4E1D8]">
    <h3 class="text-base font-bold text-[#1C211E] mb-1">Want to test what you know?</h3>
    <p class="text-xs text-[#5F665F] mb-4">Take our quick 10-question cyber quiz to see how cyber-aware you are.</p>
    <a href="quiz.php" class="btn-green py-2.5 px-6 rounded-lg text-xs font-bold inline-flex items-center gap-2">
      <span>Start the Quiz</span>
    </a>
  </div>
</div>

<?php include "includes/footer.php"; ?>
