<?php
include "includes/db.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($name == "" || $email == "" || $password == "") {
        $error = "Please fill in all the fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long.";
    } elseif ($password != $confirm) {
        $error = "Passwords do not match. Please re-enter.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "This email is already registered. Please sign in instead.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);
            if (mysqli_stmt_execute($stmt)) {
                $success = "Account created successfully! You can now log in.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}

$title = "Register";
include "includes/header.php";
?>

<div class="max-w-md mx-auto my-8">
  <div class="cyber-box rounded-2xl p-6 sm:p-8">
    <div class="text-center mb-6">
      <h1 class="text-2xl font-bold text-[#1C211E]">Create an Account</h1>
      <p class="text-xs text-[#5F665F] mt-1">Join CyberSafe to save your test scores and track your habits</p>
    </div>

    <?php if ($error) { ?>
      <div class="p-3 rounded-lg bg-[#FDF1EF] border border-[#F0C8C2] text-[#B42318] mb-4 text-xs flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 text-[#B42318]"></i>
        <span><?php echo htmlspecialchars($error); ?></span>
      </div>
    <?php } ?>

    <?php if ($success) { ?>
      <div class="p-3 rounded-lg bg-[#E6EFE9] border border-[#C9DDD0] text-[#205C43] mb-4 text-xs flex items-center justify-between gap-2">
        <span class="flex items-center gap-1.5"><i data-lucide="check-circle-2" class="w-4 h-4"></i><?php echo htmlspecialchars($success); ?></span>
        <a href="login.php" class="underline font-bold text-[#205C43]">Sign In</a>
      </div>
    <?php } ?>

    <form method="post" class="space-y-3.5 text-xs">
      <div>
        <label class="block text-[#1C211E] font-semibold mb-1">Your Name</label>
        <input 
          type="text" 
          name="name" 
          required 
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
          placeholder="Golu Firewall"
          value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
        >
      </div>

      <div>
        <label class="block text-[#1C211E] font-semibold mb-1">Email Address</label>
        <input 
          type="email" 
          name="email" 
          required 
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
          placeholder="goluFirewall@gmail.com"
          value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
        >
      </div>

      <div>
        <label class="block text-[#1C211E] font-semibold mb-1">Password</label>
        <div class="relative">
          <input 
            type="password" 
            name="password" 
            id="regPass" 
            required 
            class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
            placeholder="At least 6 characters"
          >
          <button type="button" id="toggleRegVis" class="absolute right-3 top-3 text-[#5F665F] hover:text-[#205C43] text-xs">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <div>
        <label class="block text-[#1C211E] font-semibold mb-1">Confirm Password</label>
        <input 
          type="password" 
          name="confirm" 
          required 
          class="w-full bg-[#F6F5EF] border border-[#E4E1D8] focus:border-[#BDB9AE] rounded-lg px-3.5 py-2.5 text-sm text-[#1C211E] placeholder-[#8A8F87] focus:outline-none transition-colors"
          placeholder="Re-enter password"
        >
      </div>

      <button type="submit" name="register" class="btn-green w-full py-2.5 rounded-lg text-xs font-bold mt-2">
        Create Account
      </button>
    </form>

    <div class="mt-6 pt-4 border-t border-[#E4E1D8] text-center text-xs text-[#5F665F]">
      Already have an account? 
      <a href="login.php" class="text-[#205C43] font-semibold hover:underline ml-1">Log in here</a>
    </div>
  </div>
</div>

<script>
  var toggleBtn = document.getElementById('toggleRegVis');
  var passInput = document.getElementById('regPass');
  if (toggleBtn && passInput) {
    var vis = false;
    toggleBtn.addEventListener('click', function() {
      vis = !vis;
      passInput.type = vis ? 'text' : 'password';
      toggleBtn.innerHTML = vis ? '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.4 10.4 0 0 1 12 5c7 0 10 7 10 7a13.2 13.2 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.5 13.5 0 0 0 2 12s3 7 10 7a9.7 9.7 0 0 0 5.39-1.61"/><path d="M2 2l20 20"/></svg>' : '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
    });
  }
</script>

<?php include "includes/footer.php"; ?>
