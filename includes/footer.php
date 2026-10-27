<?php
?>
</main>

<script src="assets/js/gsap.min.js"></script>
<script src="assets/js/ScrollTrigger.min.js"></script>

<script>
  var navToggle = document.getElementById('navToggle');
  var navDropdown = document.getElementById('navDropdown');
  if (navToggle && navDropdown) {
    navToggle.addEventListener('click', function() {
      navDropdown.classList.toggle('hidden');
    });

    var mobileLinks = document.querySelectorAll('.mobile-nav-link');
    mobileLinks.forEach(function(link) {
      link.addEventListener('click', function() {
        navDropdown.classList.add('hidden');
      });
    });
  }

  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);

    var navSections = [
      { sectionId: 'section-home', navId: 'nav-home' },
      { sectionId: 'section-safety', navId: 'nav-safety' },
      { sectionId: 'password-checker', navId: 'nav-analyzer' },
      { sectionId: 'section-phishing', navId: 'nav-phishing' },
      { sectionId: 'section-quiz', navId: 'nav-quiz' }
    ];

    function activateTab(navId) {
      var allTabs = document.querySelectorAll('.nav-tab');
      allTabs.forEach(function(tab) {
        tab.classList.remove('active');
        tab.classList.remove('text-[#205C43]', 'font-bold', 'border-b-2', 'border-[#205C43]');
        tab.classList.add('text-[#5F665F]');
      });

      var activeTab = document.getElementById(navId);
      if (activeTab) {
        activeTab.classList.remove('text-[#5F665F]');
        activeTab.classList.add('active');
      }
    }

    navSections.forEach(function(item) {
      var sec = document.getElementById(item.sectionId);
      if (sec) {
        ScrollTrigger.create({
          trigger: sec,
          start: 'top 45%',
          end: 'bottom 45%',
          onEnter: function() {
            activateTab(item.navId);
          },
          onEnterBack: function() {
            activateTab(item.navId);
          }
        });
      }
    });

    window.addEventListener('scroll', function() {
      if (window.scrollY < 80) {
        var homeEl = document.getElementById('section-home');
        if (homeEl) {
          activateTab('nav-home');
        }
      }
    });

    gsap.utils.toArray('.cyber-box').forEach(function(card) {
      gsap.from(card, {
        scrollTrigger: {
          trigger: card,
          start: 'top 90%',
          toggleActions: 'play none none none'
        },
        opacity: 0,
        y: 20,
        duration: 0.5,
        ease: 'power2.out'
      });
    });
  }

  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
</script>

</body>
</html>
