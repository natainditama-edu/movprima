<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title><?= $this->renderSection("title", "MovPrima") ?> | MovPrima</title>

    <meta name="description" content="<?= $this->renderSection("meta_description", "Temukan ulasan dan rekomendasi film terbaik di MovPrima. Bagikan pendapatmu dan jadilah bagian dari komunitas pecinta film.") ?>" />
    <link rel="icon" href="/favicon.ico" sizes="any" />
    <meta name="theme-color" content="#0a0a0f" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

    <!-- Tailwind CSS (utilities only) -->
    <link rel="stylesheet" href="/assets/css/app.css" />

    <!-- UTS CSS -->
    <link rel="stylesheet" href="/assets/css/libs/owl.carousel-2.3.4.min.css" />
    <link rel="stylesheet" href="/assets/css/libs/lenis-1.3.23.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <link rel="stylesheet" href="/assets/css/custom.css" />

    <!-- UTS JS Vendors -->
    <script defer src="/assets/js/libs/jquery-3.7.1.min.js"></script>
    <script defer src="/assets/js/libs/owl.carousel-2.3.4.min.js"></script>
    <script defer src="/assets/js/libs/lucide-1.9.0.min.js"></script>
    <script defer src="/assets/js/libs/gsap-3.12.5.min.js"></script>
    <script defer src="/assets/js/libs/ScrollTrigger-3.12.5.min.js"></script>
    <script defer src="/assets/js/libs/lenis-1.3.23.min.js"></script>

    <!-- Site JS -->
    <script defer src="/assets/js/app.js"></script>
    <script defer src="/assets/js/assets.js"></script>
    <script defer src="/assets/js/animations.js"></script>

    <?= $this->renderSection("styles") ?>
</head>

<body>
    <!-- Branded Splash / Preloader -->
    <!-- <div id="splash" class="splash" aria-hidden="true">
        <div class="splash-corner-tr"></div>
        <div class="splash-content">
            <div class="splash-logo" id="splash-brand">Mov<span>Prima</span></div>
            <p class="splash-tagline" id="splash-tagline">Ulasan &amp; Rekomendasi Film</p>
            <div class="splash-track">
                <div class="splash-bar" id="splash-bar"></div>
            </div>
        </div>
    </div> -->

    <!-- Header / Navbar -->
    <header>
        <div class="headerContainer">
            <a class="alogo flex items-center justify-center gap-1" href="/">
                <i data-lucide="clapperboard" class="brand-icon"></i>
                <span class="brand-name">Mov<span class="brand-accent">Prima</span></span>
            </a>
            <div class="main">
                <nav>
                    <a href="/movies">FILM</a>
                    <a href="/genres">GENRE</a>
                    <a href="/movies?sort=latest">TERBARU</a>
                    <a href="/movies?sort=rating">TERPOPULER</a>
                </nav>
                <div class="mobileNavCon">
                    <i class="mobileNavIcon fa-solid fa-bars"></i>
                    <div class="mobileNavWindow">
                        <div class="mobileNav">
                            <form role="search" method="get" class="search-form" action="/movies">
                                <label>
                                    <input autocomplete="off" type="search" class="search-field" placeholder="Cari film…" name="q" />
                                </label>
                                <input type="submit" class="search-submit" value="Cari" />
                            </form>
                            <a href="/movies">FILM</a>
                            <a href="/genres">GENRE</a>
                            <a href="/movies?sort=latest">TERBARU</a>
                            <a href="/movies?sort=rating">TERPOPULER</a>
                            <?php if (!(session()->get("user_id") ?? 0)): ?>
                            <a href="/auth/login" style="color:var(--primary)">MASUK</a>
                            <a href="/auth/register" style="color:var(--primary)">DAFTAR</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="yenisearch">
                    <form role="search" method="get" class="search-form" action="/movies">
                        <label>
                            <input autocomplete="off" type="search" class="search-field" placeholder="Cari film…" name="q" />
                        </label>
                        <input type="submit" class="search-submit" value="Cari" />
                    </form>
                </div>
            </div>
            <div class="side">
                <?php if (session()->get("user_id") ?? 0): ?>
                <a href="#"><i class="fa-solid fa-bell sideIcon"></i></a>
                <div class="sideMenuCon">
                    <div class="closeSideMenu"><i class="fa-solid fa-xmark"></i></div>
                    <?php $src = "https://i.pravatar.cc/150?u=" . (string) (session()->get("user_id") ?? 0); ?>
                    <img class="sideMenuBttn accountIcon" src="<?= $src ?>" alt="<?= esc((string) (session()->get("user_name") ?? "Pengguna")) ?>" />
                    <div class="sideMenuWindow">
                        <div class="sideMenu">
                            <a href="/profile">PROFIL SAYA</a>
                            <?php if (session()->get("user_role") === "admin"): ?>
                            <a href="/admin">PANEL ADMIN</a>
                            <?php endif; ?>
                            <a href="/auth/logout">KELUAR</a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- Guest links (desktop only, mobile handles in mobileNav) -->
                <div class="hidden md:flex gap-4 items-center h-full">
                    <a href="/auth/login" class="text-white hover:text-(--primary) font-semibold" style="letter-spacing:1px; font-size:1.1rem">MASUK</a>
                    <a href="/auth/register" class="bttn primary" style="padding: 6px 16px;">DAFTAR</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="headerFixer"></div>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata("success") || session()->getFlashdata("error")): ?>
    <div id="flash-message-container" class="fixed top-20 left-1/2 -translate-x-1/2 z-100 w-[90%] max-w-xl transition-opacity duration-300">
        <?php if (session()->getFlashdata("success")): ?>
        <div class="p-4 mb-4 rounded bg-green-900/90 border border-green-500 text-green-100 flex items-start justify-between gap-3 shadow-lg backdrop-blur-sm">
            <div class="flex gap-3">
                <i data-lucide="check-circle" class="mt-0.5 shrink-0"></i>
                <span><?= esc((string) session()->getFlashdata("success")) ?></span>
            </div>
            <button onclick="document.getElementById('flash-message-container').remove()" class="text-green-300 hover:text-white transition-colors flex items-center justify-center" aria-label="Tutup">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata("error")): ?>
        <div class="p-4 mb-4 rounded bg-red-900/90 border border-red-500 text-red-100 flex items-start justify-between gap-3 shadow-lg backdrop-blur-sm">
            <div class="flex gap-3">
                <i data-lucide="alert-circle" class="mt-0.5 shrink-0"></i>
                <span><?= esc((string) session()->getFlashdata("error")) ?></span>
            </div>
            <button onclick="document.getElementById('flash-message-container').remove()" class="text-red-300 hover:text-white transition-colors flex items-center justify-center" aria-label="Tutup">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <?php endif; ?>
    </div>
    <script>
        setTimeout(() => {
            const el = document.getElementById('flash-message-container');
            if (el) {
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        }, 5000);
    </script>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection("content") ?>
    </main>

    <!-- Footer -->
    <footer>
        <div class="flex-container">
            <div class="footerContainer">
                <div class="footerContainerTitle">
                    <p class="h1"><span class="maincolor">MovPrima</span> DALAM GENGGAMANMU!</p>
                    <p class="h2">Bawa Komunitas Film ke Mana Pun Kamu Pergi</p>
                </div>
                <div class="boxes">
                    <div class="boxesItem">
                        <img src="/assets/img/download-app-store.png" alt="Download on the App Store" />
                    </div>
                    <div class="boxesItem">
                        <img src="/assets/img/download-google-play.png" alt="Download on Google Play" />
                    </div>
                </div>
                <div class="ads">
                    <div class="content">
                        <div>
                            <p class="title">Gabung Komunitas Pecinta Film</p>
                            <p class="light">Berikan ulasanmu untuk film-film favorit dan bantu orang lain menemukan tontonan terbaik.</p>
                        </div>
                        <a href="/auth/register" class="bttn colorbttn">DAFTAR SEKARANG</a>
                    </div>
                    <img src="/assets/img/subscribe.gif" alt="Subscribe" />
                </div>
                <div class="classicFooter">
                    <div class="logo">
                        <a href="/">
                            <i data-lucide="clapperboard" class="footer-brand-icon"></i>
                            <span class="footer-brand-name">Mov<span style="color: #e50914">Prima</span></span>
                        </a>
                        <p>Temukan Ribuan Ulasan dan Rekomendasi Film Terbaik di MovPrima</p>
                    </div>
                    <div class="footerLinks">
                        <div class="footerLinksRow nav">
                            <p class="title">JELAJAHI</p>
                            <ul>
                                <li><a href="/movies">Film</a></li>
                                <li><a href="/genres">Genre</a></li>
                                <li><a href="/movies?sort=latest">Terbaru</a></li>
                                <li><a href="/movies?sort=rating">Terpopuler</a></li>
                            </ul>
                        </div>
                        <div class="footerLinksRow nav">
                            <p class="title">BANTUAN</p>
                            <ul>
                                <li><a href="#">Panduan Pengguna</a></li>
                                <li><a href="#">FAQ</a></li>
                                <li><a href="#">Pedoman Ulasan</a></li>
                                <li><a href="#">Keamanan Akun</a></li>
                            </ul>
                        </div>
                        <div class="footerLinksRow nav">
                            <p class="title">TENTANG</p>
                            <ul>
                                <li><a href="#">Iklan</a></li>
                                <li><a href="#">Kontak</a></li>
                                <li><a href="#">Karir</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="socialLinks">
                        <p class="title">IKUTI KAMI</p>
                        <div class="icons">
                            <i class="fa-brands fa-youtube"></i>
                            <i class="fa-brands fa-twitter"></i>
                            <i class="fa-brands fa-instagram"></i>
                            <i class="fa-brands fa-tiktok"></i>
                            <i class="fa-brands fa-facebook"></i>
                        </div>
                        <div class="moreFooterLink">
                            <a href="#">SYARAT LAYANAN</a><a href="#">KEBIJAKAN PRIVASI</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footerBackground">
            <img src="/assets/img/footer-bg.png" alt="Footer Background" />
        </div>
    </footer>

    <?= $this->renderSection("scripts") ?>
</body>
</html>
