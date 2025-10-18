<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fit Journey</title>
  <link rel="stylesheet" href="css/Main-index.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
</head>
<body>
  <header class="header">
    <div class="container header-container">
      <div class="logo">
        <div class="logo-icon"></div>
        <div class="logo-text">FITNEY</div>
      </div>
      <nav class="nav">
        <ul class="nav-list">
          <li><a href="#home" class="nav-link active" data-page="index">Home</a></li>
          <li class="dropdown">
            <a href="#services" class="nav-link" data-page="services">Services</a>
          </li>
          <li class="dropdown">
            <a href="#innovation" class="nav-link" data-page="tips">Tips</a>
          </li>
          <li><a href="#feedback" class="nav-link" data-page="contact">Contact</a></li>
        </ul>
      </nav>
          <a href="Login.php" class="btn btn-signin" data-transition="signin-transition">Log in</a>
    </div>
  </header>

  <main>
    <section id="home" class="hero container">
      <div class="hero-text">
        <h1>Our Healthcare Solutions Meet Every Need</h1>
        <p>Marilah kita menuju gaya hidup yang lebih baik</p>
        <p class="hero-tagline">Mulai perjalanan kesehatan Anda hari ini dengan solusi yang dipersonalisasi.</p>
        <a href="signup.php" class="btn btn-primary">Ayo Mulai</a>
      </div>
      <div class="hero-image-wrapper">
        <div class="hero-image-shapes"></div>
        <img src="sapiens.svg" alt="Healthcare" class="hero-image" />
        <img src="uplouds/icon/star.png" alt="Glowing Star" class="glowing-star" />
        <img src="uplouds/icon/star.png" alt="Glowing Star" class="glowing-star" />
        <img src="uplouds/icon/star.png" alt="Glowing Star" class="glowing-star" />
        <img src="uplouds/icon/star.png" alt="Glowing Star" class="glowing-star" />
        <img src="uplouds/icon/star.png" alt="Glowing Star" class="glowing-star" />
      </div>
    </section>

    <section>
        <div class="fitney-banner infinity-scroll">
            <div class="scroll-content">
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
            </div>
        </div>
    </section>

    <section id="services" class="services container">
      <div class="services-header">
        <h2>Kami Menawarkan Layanan Unik</h2>
        <p class="services-description">Perjalanan menuju kesehatan dan kesejahteraan yang lebih baik. Perawatan untuk kondisi spesifik yang sederhana namun ingin ditingkatkan, dan mencerminkan nada yang ingin Anda sampaikan.</p>
      </div>
      <div class="service-cards">
        <article class="card">
          <img src="./uploads/icon/smartwatch_9911679.png" alt="Calori Counter" class="card-icon">
          <h3>Calori Counter</h3>
          <p>Track your daily calorie intake effortlessly and stay on top of your health goals.</p>
          <div class="card-image-wrapper">
            <a href="login.php" class="btn btn-circle">→</a>
          </div>
        </article>
        <article class="card">
          <img src="./uploads/icon/list_14763755.png" alt="Daily List" class="card-icon">
          <h3>Daily List</h3>
          <p>Organize your daily activities and boost your productivity with ease.</p>
          <div class="card-image-wrapper">
            <a href="login.php" class="btn btn-circle">→</a>
          </div>
        </article>
      </div>
    </section>

    <section>
        <div class="fitney-banner-1">
          </div>
    </section>

    <section class="parallax-showcase">
      <div class="parallax-content">
        <h2>Join Our Community</h2>
        <p>Start your journey towards a healthier and more active lifestyle today. We have all the tools you need.</p>
        <a href="signup.php" class="btn btn-primary">Get Started Now</a>
      </div>
    </section>

    <section>
        <div class="fitney-banner-1 infinity-scroll">
            <div class="scroll-content">
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
                <span>FITNEY</span>
            </div>
        </div>
    </section>

    <section id="innovation" class="innovation container">
      <div class="innovation-text">
        <h2>Tips for a Healthier Lifestyle</h2>
        <p>Kita akan memberikan Tips yang dapat mempermudah dalam menjaga tubuh sehari-hari</p>
      </div>
      <div class="Tips-container">
        <div class="card-primary">
          <div class="health-tip-card">
            <div class="tip-icon">💧</div>
            <h3>Hidrasi yang Cukup</h3>
            <p>Minum 8-10 gelas air per hari untuk menjaga tubuh tetap terhidrasi. Air membantu proses metabolisme, mengangkat toksin, dan menjaga fungsi organ tetap optimal.</p>
            <div class="tip-benefit">
              <span>✓ Meningkatkan energi</span>
              <span>✓ Meningkatkan fokus</span>
              <span>✓ Meningkatkan kulit sehat</span>
            </div>
          </div>
        </div>
        <div class="card-secondary">
          <div class="health-tip-card">
            <div class="tip-icon">🥗</div>
            <h3>Gizi Seimbang</h3>
            <p>Konsumsi makanan bergizi dengan proporsi karbohidrat, protein, dan lemak sehat. Tambahkan 5 porsi buah dan sayur setiap hari untuk vitamin dan mineral.</p>
            <div class="tip-benefit">
              <span>✓ Sistem imun kuat</span>
              <span>✓ Energi stabil</span>
              <span>✓ Pencernaan sehat</span>
            </div>
          </div>
        </div>

        <div class="health-tip-card">
          <div class="tip-icon">🏃‍♂️</div>
          <h3>Aktivitas Fisik</h3>
          <p>Lakukan minimal 30 menit aktivitas fisik setiap hari. Olahraga ringan seperti jalan kaki, bersepeda, atau yoga dapat meningkatkan kesehatan jantung dan mood.</p>
          <div class="tip-benefit">
            <span>✓ Meningkatkan mood</span>
            <span>✓ Meningkatkan kualitas tidur</span>
            <span>✓ Meningkatkan kekuatan otot</span>
          </div>
        </div>
      </div>

    </section>

    <section>
        <div class="fitney-banner-1">
          </div>
    </section>

    <section id="feedback" class="feedback container">
        <div class="feedback-header">
          <h2>Let Us Know What You Think</h2>
          <p class="feedback-description">Bagikan pengalaman Anda menggunakan layanan kami. Pendapat Anda sangat berarti untuk membantu kami meningkatkan kualitas informasi dan pelayanan kesehatan yang kami sediakan</p>
        </div>
        <div class="feedback-cards">
          <article class="feedback-card feedback-card-gray">
            <h4>MUHAMAD RAZZY MUFLIH</h4>
            <p>Kalo ada apa apa hubungi aku yaa.</p>
            <p class="phone">📞 +62-895-3560-29060</p>
          </article>
          <article class="feedback-card feedback-card-blue">
            <h4>ZAIDAN RIZKI PRABOWO</h4>
            <p>KESELEO!? Hubungi aku yaa</p>
            <p class="phone">📞 +62-853-5323-5675</p>
          </article>
          <article class="feedback-card feedback-card-pink">
            <h4>NIDDA ALPI'AH</h4>
            <p>Just Call Me First</p>
            <p class="phone">📞 +62-822-4974-3714</p>
          </article>
        </div>
      </section>
  </main>

  <footer class="footer container">
    <!-- Tombol Kembali ke Atas -->
    <button id="backToTop" class="back-to-top" aria-label="Kembali ke atas">
      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 19V5M5 12l7-7 7 7"/>
      </svg>
    </button>

    <div class="footer-top">
      <div class="footer-logo-desc">
        <div class="logo">
          <div class="logo-text">FITNEY</div>
        </div>
        <p>Kesehatan adalah harta paling berharga yang sering kita abaikan. Website ini hadir sebagai sahabat kesehatan Anda—menyediakan informasi lengkap, dan layanan yang memudahkan Anda untuk hidup lebih sehat dan bahagia.</p>
      </div>
        <div class="footer-column newsletter">
          <h5>Stay in Loop</h5>
          <p>Get Productivity tips & email hacks in your inbox</p>
          <form>
            <input type="email" placeholder="Your email" required />
            <button type="submit">SUBSCRIBE</button>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2024 FitJourney. All rights reserved.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
 <script src="js/index.js"></script>\
 <script src="js/transition-fix.js"></script>
</body>
</html>
