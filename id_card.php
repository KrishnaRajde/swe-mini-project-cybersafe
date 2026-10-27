<?php
include "includes/db.php";
include "includes/modules.php";
check_login();

$user_id = (int) $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "SELECT user_id, name, email, quiz_score, photo FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: login.php");
    exit;
}

$cybersafe_id = "CS-" . str_pad($user['user_id'], 5, "0", STR_PAD_LEFT);
$issue_date = date("d-M-Y");
$quiz_score = isset($user['quiz_score']) ? (int) $user['quiz_score'] : 0;
$photo = $user['photo'] ?? "";

$score_color = "score-green";
if ($quiz_score < 5) {
    $score_color = "score-red";
} elseif ($quiz_score < 8) {
    $score_color = "score-yellow";
}

$progress = get_module_progress($conn, $user_id);

$title = "CyberSafe ID Card";
include "includes/header.php";

if (!$progress['all_done']) {
?>

<div class="max-w-md mx-auto my-8">
  <div class="cyber-box rounded-2xl p-6 sm:p-8 text-center">
    <h1 class="text-xl font-bold text-[#1C211E]">ID Card Locked</h1>
    <p class="text-xs text-[#5F665F] mt-1 mb-5">
      Complete all <?php echo $progress['total']; ?> modules to unlock your CyberSafe Certified ID Card.
    </p>

    <div class="flex justify-between items-center text-xs mb-1.5">
      <span class="text-[#5F665F]">Progress</span>
      <strong class="text-[#205C43]"><?php echo $progress['done']; ?>/<?php echo $progress['total']; ?></strong>
    </div>
    <div class="w-full bg-[#E6EFE9] rounded-full h-2 overflow-hidden mb-5">
      <div class="h-full bg-[#205C43]" style="width: <?php echo (int) round($progress['done'] / $progress['total'] * 100); ?>%"></div>
    </div>

    <ul class="space-y-2 text-xs text-left mb-6">
      <?php foreach ($progress['modules'] as $mod) { ?>
        <li class="flex items-center justify-between gap-2 p-2.5 rounded-lg bg-[#F6F5EF] border <?php echo $mod['done'] ? 'border-[#C9DDD0]' : 'border-[#E4E1D8]'; ?>">
          <span class="flex items-center gap-2 <?php echo $mod['done'] ? 'text-[#205C43]' : 'text-[#1C211E]'; ?>">
            <i data-lucide="<?php echo $mod['done'] ? 'check-circle-2' : 'circle'; ?>" class="w-4 h-4 shrink-0"></i>
            <span><?php echo htmlspecialchars($mod['name']); ?></span>
          </span>
          <?php if ($mod['done']) { ?>
            <span class="text-[#205C43] font-semibold">Completed</span>
          <?php } else { ?>
            <a href="<?php echo htmlspecialchars($mod['page']); ?>" class="text-[#5F665F] hover:text-[#205C43] font-semibold">Start &rarr;</a>
          <?php } ?>
        </li>
      <?php } ?>
    </ul>

    <a href="dashboard.php" class="btn-green py-2.5 px-6 rounded-lg text-xs font-bold inline-flex items-center gap-2">
      <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
      <span>Back to Dashboard</span>
    </a>
  </div>
</div>

<?php
    include "includes/footer.php";
    exit;
}
?>

<script src="assets/js/html2canvas.min.js"></script>

<div class="max-w-4xl mx-auto my-6">

  <div class="text-center mb-6 no-print">
    <h1 class="text-2xl font-bold text-[#1C211E]">CyberSafe Certified ID Card</h1>
    <p class="text-xs text-[#5F665F] mt-1">Official certificate showing your cybersecurity awareness credential</p>
  </div>

  <?php if ($quiz_score == 0) { ?>
    <div class="max-w-md mx-auto mb-6 p-4 rounded-xl bg-[#FFFFFF] border border-[#E4E1D8] text-xs text-center no-print">
      <div class="text-[#205C43] font-semibold mb-1 flex items-center justify-center gap-1.5">
        <i data-lucide="help-circle" class="w-4 h-4"></i>
        <span>Want to update your Quiz Score?</span>
      </div>
      <p class="text-[#5F665F] mb-3">You can take the 10-question cyber quiz anytime to display your latest score on your ID card.</p>
      <a href="quiz.php" class="btn-green px-4 py-1.5 rounded-lg text-xs font-bold inline-flex items-center gap-1.5">
        <span>Take Quiz Now</span>
      </a>
    </div>
  <?php } ?>

  <div class="id-card-wrapper">
    <div id="idCard">
      
      <div class="id-card-header">
        <div class="flex items-center gap-2">
          <i data-lucide="shield-check" class="w-6 h-6 text-[#205C43]"></i>
          <div>
            <div class="id-header-title">CyberSafe Certified ID</div>
            <div class="id-header-sub">OFFICIAL SECURITY CREDENTIAL</div>
          </div>
        </div>
        <div class="id-chip"></div>
      </div>

      <div class="id-card-body">
        
        <div class="id-photo">
          <?php if (!empty($photo) && (file_exists($photo) || file_exists(__DIR__ . '/' . $photo))) { ?>
            <img src="<?php echo htmlspecialchars($photo); ?>" alt="Profile Photo" crossorigin="anonymous" class="w-full h-full object-cover">
          <?php } else { ?>
            <i data-lucide="user" class="w-8 h-8 text-[#5F665F]"></i>
          <?php } ?>
        </div>

        <div class="id-details">
          <div class="id-name"><?php echo htmlspecialchars($user['name']); ?></div>
          <div class="id-number"><?php echo htmlspecialchars($cybersafe_id); ?></div>
          <div class="id-badge flex items-center gap-1 w-max">
            <i data-lucide="badge-check" class="w-3.5 h-3.5 text-[#205C43]"></i>
            <span>Certified CyberSafe User</span>
          </div>
        </div>

        <div class="id-score-area">
          <div class="id-score-label">Quiz Score</div>
          <div class="id-score-num <?php echo $score_color; ?>">
            <?php echo $quiz_score; ?><span class="text-xs text-[#5F665F] font-normal">/10</span>
          </div>
          <div class="text-[9px] text-[#5F665F]">Awareness</div>
        </div>

      </div>

      <div class="id-card-footer">
        <div>
          <span>Issue Date: </span>
          <strong class="text-[#205C43]"><?php echo htmlspecialchars($issue_date); ?></strong>
        </div>
        <div class="flex items-center gap-1.5">
          <i data-lucide="fingerprint" class="w-3.5 h-3.5 text-[#205C43]"></i>
          <span>Verified Digital ID</span>
        </div>
      </div>

    </div>
  </div>

  <div class="mt-6 flex flex-wrap justify-center items-center gap-3 no-print">
    
    <button type="button" onclick="downloadCard()" class="btn-green py-2.5 px-5 rounded-xl text-xs font-bold flex items-center gap-2">
      <i data-lucide="download" class="w-4 h-4"></i>
      <span>Download PNG</span>
    </button>

    <button type="button" onclick="window.print()" class="btn-outline py-2.5 px-5 rounded-xl text-xs font-bold flex items-center gap-2">
      <i data-lucide="printer" class="w-4 h-4"></i>
      <span>Print Card</span>
    </button>

    <a href="upload_photo.php" class="py-2.5 px-4 rounded-xl bg-[#FFFFFF] text-[#1C211E] hover:text-[#205C43] hover:border-[#BDB9AE] border border-[#E4E1D8] text-xs flex items-center gap-2 transition-colors">
      <i data-lucide="camera" class="w-4 h-4"></i>
      <span><?php echo empty($photo) ? 'Upload Photo' : 'Change Photo'; ?></span>
    </a>

    <a href="quiz.php" class="py-2.5 px-4 rounded-xl bg-[#FFFFFF] text-[#1C211E] hover:text-[#205C43] hover:border-[#BDB9AE] border border-[#E4E1D8] text-xs flex items-center gap-2 transition-colors">
      <i data-lucide="rotate-cw" class="w-4 h-4"></i>
      <span>Retake Quiz</span>
    </a>

  </div>

</div>

<script>
  function downloadCard() {
    var card = document.getElementById("idCard");
    html2canvas(card, {
      scale: 3,
      useCORS: true,
      allowTaint: true,
      backgroundColor: null
    }).then(function(canvas) {
      var link = document.createElement("a");
      link.download = "CyberSafe_Certified_ID_Card.png";
      link.href = canvas.toDataURL("image/png");
      link.click();
    }).catch(function(err) {
      console.error("Canvas error:", err);
      alert("Could not generate PNG download automatically. Using Print dialog instead.");
      window.print();
    });
  }
</script>

<?php include "includes/footer.php"; ?>
