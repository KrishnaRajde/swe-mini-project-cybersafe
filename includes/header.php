<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($title) ? $title . " - CyberSafe" : "CyberSafe - Cyber Security Awareness"; ?></title>
  
  <script src="assets/js/tailwind.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            ivory: '#F6F5EF',
            forest: '#205C43',
            ink: '#1C211E',
          },
          fontFamily: {
            sans: ['"IBM Plex Sans"', 'system-ui', 'sans-serif'],
            mono: ['"IBM Plex Mono"', 'monospace'],
          },
          fontSize: {
            xs: ['0.8125rem', '1.25rem'],
          },
          borderRadius: {
            xl: '0.5rem',
            '2xl': '0.625rem',
          },
          boxShadow: {
            DEFAULT: 'none',
            md: '0 1px 0 rgba(0,0,0,0.25)',
            lg: 'none',
            xl: 'none',
          }
        }
      }
    }
  </script>
  
  <script src="assets/js/lucide.min.js"></script>
  
  
  <link href="assets/fonts/fonts.css" rel="stylesheet">

  <link rel="stylesheet" href="style.css?v=<?php echo filemtime(__DIR__ . '/../style.css'); ?>">
</head>
<body class="min-h-screen flex flex-col bg-[#F6F5EF] text-[#1C211E]">

<header class="bg-[#FFFFFF] border-b border-[#E4E1D8] sticky top-0 z-50">
  <div class="max-w-6xl mx-auto px-4">
    <div class="flex items-center justify-between h-16">
      
      <a href="index.php" class="flex items-center gap-2 text-[#1C211E] font-semibold text-lg">
        <span>Cyber<span class="text-[#205C43]">Safe</span></span>
      </a>

      <nav class="hidden md:flex items-center gap-1 text-sm font-medium" id="mainNav">
        <a href="home.php" id="nav-home" class="nav-tab px-3 py-1.5 rounded <?php echo (!isset($title) || $title == 'Home') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Home</a>
        <a href="learn.php" id="nav-safety" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'Safety Tips') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Safety Tips</a>
        <a href="password_analyzer.php" id="nav-analyzer" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'Password Analyzer') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Password Analyzer</a>
        <a href="password_generator.php" id="nav-generator" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'Password Generator') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Generator</a>
        <a href="phishing_detector.php" id="nav-phishing" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'Phishing Detector') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Phishing Detector</a>
        <a href="quiz.php" id="nav-quiz" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'Quiz') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">Quiz</a>
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="id_card.php" id="nav-idcard" class="nav-tab px-3 py-1.5 rounded <?php echo (isset($title) && $title == 'CyberSafe ID Card') ? 'active' : 'text-[#5F665F] hover:text-[#205C43]'; ?>">ID Card</a>
        <?php } ?>
      </nav>

      <div class="hidden md:flex items-center gap-3 text-sm">
        <?php if (isset($_SESSION['user_id'])) { ?>
          <a href="dashboard.php" class="text-[#1C211E] hover:text-[#205C43] font-medium">Dashboard</a>
          <a href="logout.php" class="text-[#5F665F] hover:text-[#B42318]">Logout</a>
        <?php } else { ?>
          <a href="login.php" class="text-[#1C211E] hover:text-[#205C43] text-xs font-semibold px-2 py-1 transition-colors">Login</a>
          <a href="register.php" class="btn-green px-4 py-1.5 rounded-lg text-xs font-bold flex items-center gap-1.5">Register
          </a>
        <?php } ?>
      </div>

      <button id="navToggle" class="md:hidden text-[#1C211E] p-2 hover:text-[#205C43]">
        <i data-lucide="menu" class="w-5 h-5"></i>
      </button>

    </div>
  </div>

  <div id="navDropdown" class="hidden md:hidden bg-[#FFFFFF] border-t border-[#E4E1D8] px-4 py-3 space-y-2 text-sm">
    <a href="home.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Home</a>
    <a href="learn.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Safety Tips</a>
    <a href="password_analyzer.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Password Analyzer</a>
    <a href="password_generator.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Password Generator</a>
    <a href="phishing_detector.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Phishing Detector</a>
    <a href="quiz.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] py-1">Quiz</a>
    <?php if (isset($_SESSION['user_id'])) { ?>
      <a href="id_card.php" class="mobile-nav-link block text-[#205C43] font-bold py-1 flex items-center gap-1.5">
        <i data-lucide="id-card" class="w-4 h-4"></i>
        <span>My ID Card</span>
      </a>
      <a href="dashboard.php" class="mobile-nav-link block text-[#1C211E] hover:text-[#205C43] font-bold py-1 flex items-center gap-1.5">
        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
        <span>Dashboard (<?php echo htmlspecialchars($_SESSION['name']); ?>)</span>
      </a>
      <a href="logout.php" class="block text-[#B42318] text-xs py-1 flex items-center gap-1">
        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
        <span>Logout</span>
      </a>
    <?php } else { ?>
      <div class="pt-2 border-t border-[#E4E1D8] flex gap-2">
        <a href="login.php" class="px-3 py-1.5 rounded bg-[#F6F5EF] text-[#1C211E] border border-[#E4E1D8] text-xs">Login</a>
        <a href="register.php" class="btn-green px-4 py-1.5 rounded-lg text-xs font-bold">Register</a>
      </div>
    <?php } ?>
  </div>
</header>

<main class="flex-1 max-w-6xl w-full mx-auto px-4 py-8">
