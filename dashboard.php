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

$title = "Dashboard";
include "includes/header.php";

$quiz_score = isset($user['quiz_score']) ? (int) $user['quiz_score'] : 0;
$photo = $user['photo'] ?? "";
$has_photo = !empty($photo) && (file_exists($photo) || file_exists(__DIR__ . '/' . $photo));
$cybersafe_id = "CS-" . str_pad($user['user_id'], 5, "0", STR_PAD_LEFT);
$progress = get_module_progress($conn, $user_id);
$progress_pct = (int) round($progress['done'] / $progress['total'] * 100);
$remaining = $progress['total'] - $progress['done'];

$module_info = [
    "learn"     => ["desc" => "Read 6 short safety lessons"],
    "analyzer"  => ["desc" => "Check how strong a password is"],
    "generator" => ["desc" => "Create a strong random password"],
    "phishing"  => ["desc" => "Scan a suspicious message for scams"],
    "quiz"      => ["desc" => "Answer 10 quick questions"],
];

$next_key = null;
foreach ($progress['modules'] as $key => $mod) {
    if (!$mod['done']) {
        $next_key = $key;
        break;
    }
}
?>

<div class="max-w-3xl mx-auto space-y-5">

  <div class="flex items-center gap-4">
    <a href="upload_photo.php" class="relative w-14 h-14 rounded-full border-2 border-[#205C43] overflow-hidden bg-[#F6F5EF] shrink-0 flex items-center justify-center group" title="Change photo">
      <?php if ($has_photo) { ?>
        <img src="<?php echo htmlspecialchars($photo); ?>" alt="Your photo" class="w-full h-full object-cover">
      <?php } else { ?>
        <i data-lucide="user" class="w-6 h-6 text-[#5F665F]"></i>
      <?php } ?>
      <span class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
        <i data-lucide="camera" class="w-4 h-4 text-[#205C43]"></i>
      </span>
    </a>
    <div class="min-w-0">
      <h1 class="text-2xl sm:text-3xl font-bold text-[#1C211E] truncate">
        Welcome back, <span class="text-[#205C43]"><?php echo htmlspecialchars($user['name']); ?></span>
      </h1>
      <p class="text-xs sm:text-sm text-[#5F665F] mt-0.5">
        Your CyberSafe ID: <span class="font-mono text-[#205C43]"><?php echo htmlspecialchars($cybersafe_id); ?></span>
      </p>
    </div>
  </div>

  <div class="cyber-box rounded-2xl p-5 sm:p-6 border border-[#E4E1D8]">
    <div class="flex items-end justify-between gap-3 mb-2">
      <div>
        <div class="text-xs text-[#5F665F] font-medium">Your progress</div>
        <div class="text-3xl font-extrabold text-[#1C211E] mt-0.5">
          <?php echo $progress['done']; ?><span class="text-base text-[#5F665F] font-normal"> / <?php echo $progress['total']; ?> modules done</span>
        </div>
      </div>
      <div class="text-sm font-bold text-[#205C43]"><?php echo $progress_pct; ?>%</div>
    </div>
    <div class="w-full bg-[#E6EFE9] rounded-full h-2.5 overflow-hidden mb-5">
      <div class="h-full bg-[#205C43] rounded-full transition-all duration-500" style="width: <?php echo $progress_pct; ?>%"></div>
    </div>

    <?php if ($next_key !== null) { ?>
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-xl bg-[#F6F5EF] border border-[#E4E1D8]">
        <div class="flex items-center gap-3">
          <div>
            <div class="text-[11px] text-[#5F665F] uppercase tracking-wider font-semibold">Next step</div>
            <div class="text-sm font-bold text-[#1C211E]"><?php echo htmlspecialchars($progress['modules'][$next_key]['name']); ?></div>
          </div>
        </div>
        <a href="<?php echo htmlspecialchars($progress['modules'][$next_key]['page']); ?>" class="btn-green py-2.5 px-5 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5">
          <span><?php echo $progress['done'] == 0 ? 'Start learning' : 'Continue'; ?></span>
        </a>
      </div>
    <?php } else { ?>
      <div class="flex items-center gap-3 p-4 rounded-xl bg-[#E6EFE9] border border-[#C9DDD0]">
        <div class="text-sm text-[#1C211E]"><strong class="text-[#205C43]">All modules complete!</strong> Your Certified ID Card is ready.</div>
      </div>
    <?php } ?>
  </div>

  <div class="cyber-box rounded-2xl border border-[#E4E1D8] overflow-hidden">
    <div class="px-5 sm:px-6 py-4 border-b border-[#E4E1D8]">
      <h2 class="text-base font-bold text-[#1C211E]">Your modules</h2>
      <p class="text-xs text-[#5F665F] mt-0.5">Finish all 5 to unlock your ID card.</p>
    </div>

    <?php $n = 0; foreach ($progress['modules'] as $key => $mod) { $n++; ?>
      <a href="<?php echo htmlspecialchars($mod['page']); ?>" class="flex items-center gap-4 px-5 sm:px-6 py-4 border-b border-[#E4E1D8] last:border-b-0 hover:bg-[#F6F5EF] transition-colors group">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 <?php echo $mod['done'] ? 'bg-[#205C43] text-[#FFFFFF]' : 'bg-[#F6F5EF] border border-[#E4E1D8] text-[#5F665F]'; ?>">
          <?php if ($mod['done']) { ?>
            <i data-lucide="check" class="w-5 h-5"></i>
          <?php } else { ?>
            <span class="text-sm font-bold"><?php echo $n; ?></span>
          <?php } ?>
        </div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-bold text-[#1C211E] flex items-center gap-2">
            <span><?php echo htmlspecialchars($mod['name']); ?></span>
          </div>
          <div class="text-xs text-[#5F665F] mt-0.5">
            <?php echo $module_info[$key]['desc']; ?>
            <?php if ($key === 'quiz' && $mod['done']) { ?>
              &middot; <span class="text-[#205C43] font-semibold">Last score: <?php echo $quiz_score; ?>/10</span>
            <?php } ?>
          </div>
        </div>
        <?php if ($mod['done']) { ?>
          <span class="text-xs font-semibold text-[#205C43] shrink-0">Done</span>
        <?php } else { ?>
          <span class="text-xs font-semibold text-[#5F665F] group-hover:text-[#205C43] flex items-center gap-1 shrink-0 transition-colors">
            Start
          </span>
        <?php } ?>
      </a>
    <?php } ?>
  </div>

  <div class="cyber-box rounded-2xl p-5 sm:p-6 border <?php echo $progress['all_done'] ? 'border-[#C9DDD0]' : 'border-[#E4E1D8]'; ?> flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <div>
        <div class="text-sm font-bold text-[#1C211E]">CyberSafe Certified ID Card</div>
        <div class="text-xs text-[#5F665F] mt-0.5">
          <?php if ($progress['all_done']) { ?>
            Unlocked! <?php echo $has_photo ? 'Download or print it anytime.' : 'Add a photo to make it yours.'; ?>
          <?php } else { ?>
            Locked &middot; complete <?php echo $remaining; ?> more module<?php echo $remaining == 1 ? '' : 's'; ?> to unlock.
          <?php } ?>
        </div>
      </div>
    </div>
    <?php if ($progress['all_done']) { ?>
      <div class="flex items-center gap-2">
        <?php if (!$has_photo) { ?>
          <a href="upload_photo.php" class="btn-outline py-2 px-4 rounded-xl text-xs font-bold flex items-center gap-1.5">
            <span>Add Photo</span>
          </a>
        <?php } ?>
        <a href="id_card.php" class="btn-green py-2 px-4 rounded-xl text-xs font-bold flex items-center gap-1.5">
          <span>View Card</span>
        </a>
      </div>
    <?php } else { ?>
      <span class="text-xs font-semibold text-[#5F665F] flex items-center gap-1.5 shrink-0">
        <span><?php echo $progress['done']; ?>/<?php echo $progress['total']; ?></span>
      </span>
    <?php } ?>
  </div>

</div>

<?php include "includes/footer.php"; ?>
