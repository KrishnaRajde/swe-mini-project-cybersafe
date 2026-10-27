# CyberSafe - Cyber Security Awareness Portal

A clean, beginner-friendly web application for Diploma Semester 3 Computer / IT Engineering mini-project.

## Technologies Used
- **Frontend:** HTML5, Tailwind CSS, Lucide Icons, Custom CSS (`style.css`), GSAP ScrollTrigger
- **Backend:** PHP (Procedural style, beginner-friendly)
- **Database:** MySQL / MariaDB (Single `users` table)
- **Libraries:** html2canvas (ID card PNG download)
- All libraries and fonts are saved inside the `assets/` folder, so the website works **without internet**.

## Features & Modules
1. **User Authentication:**
   - Registration (`register.php`) with password hashing (`password_hash`).
   - Secure login (`login.php`) and session protection (`check_login()`).
   - Clean logout (`logout.php`).

2. **Dashboard (`dashboard.php`):**
   - Displays student name and latest quiz score.
   - Core cybersecurity safety tips.
   - Quick navigation shortcuts to all tools and modules.

3. **Cyber Security Quiz (`quiz.php`):**
   - 10 practical questions on passwords, phishing, and safe browsing.
   - Updates `quiz_score` in the `users` table on submit.

4. **Phishing Detector (`phishing_detector.php`):**
   - Analyzes suspicious SMS, emails, and WhatsApp messages for phishing keywords and red flags.

5. **Password Analyzer (`password_analyzer.php`):**
   - Real-time password complexity tester, crack-time estimation, and recommendations.

6. **Password Generator (`password_generator.php`):**
   - Generates strong random passwords using browser cryptography (`crypto.getRandomValues`).
   - Custom length slider, character toggles, and one-click copy to clipboard.

7. **Photo Upload & Certified ID Card (`upload_photo.php`, `id_card.php`):**
   - Upload profile photo (JPG/PNG max 2MB) saved to `uploads/`.
   - Displays styled CyberSafe Certified ID with zero-padded ID number (`CS-XXXXX`), quiz score, issue date, and certified badge.
   - One-click PNG download (html2canvas) and print support (`window.print()`).

## How to Run on Any Laptop / PC (Windows, Mac or Linux)

1. Install **XAMPP** (or MAMP / WAMP).
2. Copy the project folder into the `htdocs` folder:
   - Windows: `C:\xampp\htdocs\swe-mini-project`
   - Mac: `/Applications/XAMPP/xamppfiles/htdocs/swe-mini-project`
3. Open the XAMPP Control Panel and start **Apache** and **MySQL**.
4. Open `http://localhost/swe-mini-project/` in the browser.

That's it. You do **not** need to import the database by hand. The first time the website opens, `includes/db.php` automatically:
- finds MySQL on port 3306, 3307 or 8889 (MAMP),
- creates the `cybersafe` database and the `users` table if they don't exist,
- adds any missing columns to an older `users` table.

(`database.sql` is still included if you want to create the database yourself from phpMyAdmin.)

### Photo uploads on Mac only
On Windows, photo upload works straight away. On a Mac, Apache may not be allowed to write into the `uploads` folder. If the Upload Photo page shows a permission message, open Terminal and run:
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/swe-mini-project
chmod -R 777 uploads
```

