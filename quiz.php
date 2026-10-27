<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$questions = [
    [
        "q" => "What is Phishing?", 
        "a" => [
            "A computer virus that slows down your PC", 
            "A fake message that tries to trick you into giving your password or info", 
            "A tool used to speed up internet connections", 
            "An antivirus firewall setting"
        ], 
        "correct" => 1
    ],
    [
        "q" => "Which of these is the strongest password?", 
        "a" => [
            "password123", 
            "Rahul1998", 
            "T9#vLp!2qX@m", 
            "qwertyuiop"
        ], 
        "correct" => 2
    ],
    [
        "q" => "What does 2FA (Two-Factor Authentication) mean?", 
        "a" => [
            "Two File Access", 
            "Two steps required to log in (like password + OTP on phone)", 
            "Using two different antivirus apps at the same time", 
            "A fast login shortcut"
        ], 
        "correct" => 1
    ],
    [
        "q" => "Someone calls pretending to be your bank and asks for your OTP to verify your account. What should you do?", 
        "a" => [
            "Tell them the OTP, since they called from the bank", 
            "Give only the first 3 digits of the OTP", 
            "Hang up immediately — real banks never ask for OTPs", 
            "Send it by SMS instead"
        ], 
        "correct" => 2
    ],
    [
        "q" => "What does 'HTTPS' with a lock icon in a website address mean?", 
        "a" => [
            "The site is 100% legal and cannot contain any scams", 
            "The connection between your browser and the website is encrypted", 
            "The website is owned by the government", 
            "The site has no advertisements"
        ], 
        "correct" => 1
    ],
    [
        "q" => "What does Ransomware malware do to your computer?", 
        "a" => [
            "Displays irritating ads on your desktop", 
            "Locks your personal files and demands money to unlock them", 
            "Makes your computer run faster", 
            "Deletes temporary internet cookies"
        ], 
        "correct" => 1
    ],
    [
        "q" => "Where is the safest place to download software and apps?", 
        "a" => [
            "Random download websites with pop-up buttons", 
            "Links shared on WhatsApp or Telegram", 
            "Official stores (Google Play, Apple App Store, official website)", 
            "Pop-up ads that say 'Your video player is outdated'"
        ], 
        "correct" => 2
    ],
    [
        "q" => "What should you do before doing online banking or shopping on public Wi-Fi?", 
        "a" => [
            "Nothing, free Wi-Fi is always safe", 
            "Use mobile data or a VPN instead", 
            "Turn down your screen brightness", 
            "Clear your browser history after paying"
        ], 
        "correct" => 1
    ],
    [
        "q" => "You find a lost USB pendrive in a college or office hallway. What should you do?", 
        "a" => [
            "Plug it into your laptop to see whose photos/files are inside", 
            "Give it to IT / security without plugging it in", 
            "Keep it and format it for yourself", 
            "Give it to a friend"
        ], 
        "correct" => 1
    ],
    [
        "q" => "Why is it important to install software and phone updates?", 
        "a" => [
            "They only change the wallpaper and theme", 
            "They fix important security holes that hackers use to attack devices", 
            "They delete unnecessary files", 
            "Updates are not important and can always be skipped"
        ], 
        "correct" => 1
    ]
];

$score = null;
$answers = [];

if (isset($_POST['submit'])) {
    $score = 0;
    foreach ($questions as $i => $q) {
        $ans = isset($_POST['q' . $i]) ? (int) $_POST['q' . $i] : -1;
        $answers[$i] = $ans;
        if ($ans == $q['correct']) {
            $score++;
        }
    }

    $total = count($questions);

    if (isset($_SESSION['user_id'])) {
        $uid = (int) $_SESSION['user_id'];
        $updateStmt = mysqli_prepare($conn, "UPDATE users SET quiz_score = ? WHERE user_id = ?");
        mysqli_stmt_bind_param($updateStmt, "ii", $score, $uid);

        if (mysqli_stmt_execute($updateStmt)) {
            mark_module_complete($conn, $uid, "quiz");
        }
    }
}

$title = "Quiz";
include "includes/header.php";
?>

<div class="max-w-3xl mx-auto">
  <div class="mb-6 text-center sm:text-left">
    <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] mb-2 flex items-center justify-center sm:justify-start gap-2.5">
      <span>Cyber Security Quiz</span>
    </h1>
  </div>

  <?php if ($score !== null) { ?>
    <div class="cyber-box rounded-xl p-6 sm:p-7 mb-8 text-center border-t-4 border-t-[#205C43]">
      <h2 class="text-xl sm:text-2xl font-bold text-[#1C211E] mb-1">
        You scored <span class="text-[#205C43]"><?php echo $score; ?></span> out of <?php echo count($questions); ?>!
      </h2>
      <p class="text-xs sm:text-sm text-[#5F665F] mb-6">
        <?php if ($score >= 8) { ?>
          <span class="text-[#205C43] font-semibold">Excellent!</span> You have very strong cyber awareness habits.
        <?php } elseif ($score >= 5) { ?>
          <span class="text-[#8A5A12] font-semibold">Good job!</span> You know the basics, but there are a few areas to brush up on.
        <?php } else { ?>
          <span class="text-[#B42318] font-semibold">Keep learning!</span> Check out our <a href="learn.php" class="text-[#205C43] underline">Safety Tips</a> to learn how to stay protected.
        <?php } ?>
      </p>

      <div class="flex items-center justify-center gap-3">
        <a href="quiz.php" class="btn-green py-2 px-5 rounded-lg text-xs font-bold flex items-center gap-1.5">
          <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i>
          <span>Try Again</span>
        </a>
        <a href="dashboard.php" class="btn-outline py-2 px-5 rounded-lg text-xs font-bold flex items-center gap-1.5">
          <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
          <span>View Dashboard</span>
        </a>
      </div>
    </div>

    <h3 class="text-base font-bold text-[#1C211E] mb-4">Review Your Answers</h3>
    <div class="space-y-3 mb-8">
      <?php foreach ($questions as $i => $q) { 
          $right = $answers[$i] == $q['correct'];
      ?>
        <div class="cyber-box rounded-xl p-4 border-l-4 <?php echo $right ? 'border-l-[#205C43]' : 'border-l-[#B42318]'; ?> text-xs sm:text-sm">
          <p class="font-semibold text-[#1C211E] mb-2"><?php echo ($i + 1) . ". " . $q['q']; ?></p>
          
          <?php if (!$right) { ?>
            <p class="text-[#B42318] mb-1 flex items-center gap-1.5">
              <i data-lucide="x" class="w-3.5 h-3.5"></i>
              <span>Your answer: <?php echo $answers[$i] >= 0 ? htmlspecialchars($q['a'][$answers[$i]]) : "Not answered"; ?></span>
            </p>
          <?php } ?>

          <p class="text-[#205C43] font-medium flex items-center gap-1.5">
            <i data-lucide="check" class="w-3.5 h-3.5"></i>
            <span>Correct answer: <?php echo htmlspecialchars($q['a'][$q['correct']]); ?></span>
          </p>
        </div>
      <?php } ?>
    </div>

  <?php } else { ?>

    <div class="mb-4 flex items-center justify-between text-xs text-[#5F665F]">
      <span>Select one answer for each question:</span>
      <span>Answered: <strong id="answeredCount" class="text-[#205C43]">0</strong> / <?php echo count($questions); ?></span>
    </div>

    <form method="post" id="quizForm" class="space-y-4">
      <?php foreach ($questions as $i => $q) { ?>
        <div class="cyber-box rounded-xl p-5 border border-[#E4E1D8]">
          <p class="font-bold text-[#1C211E] text-sm mb-3">
            <?php echo ($i + 1) . ". " . $q['q']; ?>
          </p>

          <div class="space-y-2 text-xs sm:text-sm">
            <?php foreach ($q['a'] as $j => $opt) { ?>
              <label class="flex items-start gap-2.5 p-2.5 rounded-lg bg-[#F6F5EF] border border-[#E4E1D8] hover:border-[#BDB9AE] cursor-pointer transition-colors">
                <input 
                  type="radio" 
                  name="q<?php echo $i; ?>" 
                  value="<?php echo $j; ?>" 
                  class="mt-1 accent-[#205C43] shrink-0"
                >
                <span class="text-[#1C211E]"><?php echo $opt; ?></span>
              </label>
            <?php } ?>
          </div>
        </div>
      <?php } ?>

      <div class="pt-2 flex justify-end">
        <button type="submit" name="submit" class="btn-green px-8 py-3 rounded-lg text-xs font-bold">
          Submit Quiz
        </button>
      </div>
    </form>

    <script>
      var form = document.getElementById("quizForm");
      var counter = document.getElementById("answeredCount");
      form.addEventListener("change", function() {
        var count = form.querySelectorAll("input[type=radio]:checked").length;
        counter.innerText = count;
      });

      form.addEventListener("submit", function(e) {
        var count = form.querySelectorAll("input[type=radio]:checked").length;
        if (count < <?php echo count($questions); ?>) {
          if (!confirm("You haven't answered all questions (" + count + " of <?php echo count($questions); ?>). Submit anyway?")) {
            e.preventDefault();
          }
        }
      });
    </script>

  <?php } ?>
</div>

<?php include "includes/footer.php"; ?>
