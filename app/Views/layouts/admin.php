<!doctype html>
<html lang="id" class="dark">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta name="robots" content="noindex, nofollow">
  <title><?= $this->renderSection("title", "Admin") ?> | MovPrima Admin</title>

  <meta name="theme-color" content="#0a0a0f" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" />

  <!-- Font Awesome -->
  <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />

  <!-- Tailwind CSS (utilities only) via script as requested -->
  <link rel="preload" href="/assets/css/app.css" as="style" />
  <link rel="stylesheet" href="/assets/css/app.css" />

  <!-- Custom Theme CSS -->
  <link rel="preload" href="/assets/css/style.css" as="style" />
  <link rel="stylesheet" href="/assets/css/style.css" />
  <link rel="stylesheet" href="/assets/css/custom.css" />

  <!-- DataTables & jQuery -->
  <link rel="preconnect" href="https://code.jquery.com" crossorigin />
  <link rel="preconnect" href="https://cdn.datatables.net" crossorigin />
  <script defer src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script defer src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
  <script defer src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.tailwindcss.css">

  <!-- App JS -->
  <script defer src="/assets/js/libs/lucide-1.9.0.min.js"></script>

  <?= $this->renderSection("styles") ?>

  <style>
    /* Admin specific overrides */
    body {
      background-color: #0a0a0f;
      color: #ffffff;
      font-family: Arial, Helvetica, sans-serif;
      overflow-x: hidden;
    }
    h1, h2, h3, h4, h5, h6, .font-display {
      font-family: 'Barlow Condensed', sans-serif;
    }
    .admin-sidebar {
      background: linear-gradient(180deg, #12121c 0%, #0a0a0f 100%);
      border-right: 1px solid rgba(255, 255, 255, 0.1);
    }
    .admin-sidebar.active .sidebar-brand-content  {
      padding-left: 10px;
    }
    .admin-sidebar a.active {
      background: var(--primary, #e50914);
      color: #fff;
    }
    .admin-sidebar a:hover:not(.active) {
      background: rgba(255, 255, 255, 0.05);
    }

    /* DataTables aggressive overrides for dark theme */
    .dt-container {
      color: #a1a1aa !important;
      font-size: var(--text-sm); /* 0.875rem (14px) */
      line-height: var(--text-sm--line-height); /* calc(1.25 / 0.875) */
    }
    .dt-container .grid.grid-cols-2 {
      display: flex !important;
      flex-direction: column !important;
      gap: 1rem;
    }
    @media (min-width: 768px) {
      .dt-container .grid.grid-cols-2 {
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center;
      }
    }
    .dt-length label {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
    }
    .dt-search {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
    }
    .dt-info {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
      text-align: center;
    }
    .dt-paging nav ul {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: center;
    }
    @media (min-width: 768px) {
      .dt-length label, .dt-info { justify-content: flex-start; }
      .dt-search, .dt-paging nav ul { justify-content: flex-end; }
    }
    .dt-paging nav ul a {
        color: rgb(82 82 91); /* zinc-600 */
        transition: color 150ms ease, background-color 150ms ease;
        background-color: transparent;
        width: 30px;
        height: 30px;
        padding: 0.25rem;
        border-radius: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .dt-paging nav ul a:hover {
        color: rgb(113 113 122); /* zinc-500 */
        background-color: rgba(113, 113, 122, 0.1);
    }
    .dt-paging nav ul a[aria-current="page"] {
        color: rgb(113 113 122); /* zinc-500 */
        background-color: rgba(113, 113, 122, 0.1);
    }
    #datatable_wrapper.dt-container .dt-paging nav ul {
      margin: 0 !important;
    }
    .dt-container input[type="search"], .dt-container select {
        background-color: rgba(0, 0, 0, 0.5) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: white !important;
        border-radius: 6px !important;
        padding: 6px 12px !important;
        min-height: 32px !important;
        height: 32px !important;
        max-height: 32px !important;
    }
    .dt-container input[type="search"]::placeholder {
        font-size: var(--text-xs); /* 0.75rem (12px) */
        line-height: var(--text-xs--line-height); /* calc(1 / 0.75) */
    }
    .dt-empty{
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 1.25rem;
        padding-bottom: 1.25rem;
        text-align: center;
        font-weight: 700;
        letter-spacing: 0.1em;
        font-size: 0.75rem !important;
        line-height: 1rem;
        white-space: nowrap;
        border-left: 1px solid rgba(255,255,255,0.1) !important;
        border-right: 1px solid rgba(255,255,255,0.1) !important;
    }
    .dt-container input[type="search"]:focus, .dt-container select:focus {
        outline: none !important;
        border-color: var(--primary) !important;
        box-shadow: none !important;
    }

    /* Table overrides */
    .dt-container table {
        color: white !important;
        border-collapse: collapse !important;
        background: transparent !important;
    }
    .dt-container table thead, .dt-container table thead tr, .dt-container table thead th, .dt-container table thead td {
        background-color: rgba(0,0,0,0.3) !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        color: #a1a1aa !important;
    }
    .dt-container table tbody tr {
        background-color: transparent !important;
    }
    .dt-container table tbody tr td {
        border-bottom: 1px solid rgba(255,255,255,0.05) !important;
        background-color: transparent !important;
    }
    .dt-container table tbody tr:hover, .dt-container table tbody tr:hover td {
        background-color: rgba(255,255,255,0.03) !important;
    }

    /* DataTables Pagination Overrides (Tailwind specific) */
    .dt-container .pagination, .dt-container nav ul {
        display: flex !important;
        gap: 4px !important;
        box-shadow: none !important;
        margin-top: 1rem !important;
        flex-wrap: wrap;
        list-style: none !important;
        padding: 0 !important;
    }
    .dt-container .pagination .page-item .page-link, .dt-container nav ul li a, .dt-container nav ul li button {
        background-color: rgba(255, 255, 255, 0.05) !important;
        color: #a1a1aa !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        border-radius: 4px !important;
        padding: 6px 12px !important;
        margin: 0 !important;
        transition: all 0.2s !important;
        display: inline-block !important;
        text-decoration: none !important;
    }
    .dt-container .pagination .page-item:not(.active):not(.disabled) .page-link:hover, .dt-container nav ul li:not(.active):not(.disabled) a:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: white !important;
    }
    .dt-container .pagination .page-item.active .page-link, .dt-container nav ul li.active a, .dt-container nav ul li [aria-current="page"] {
        background-color: var(--primary) !important;
        color: white !important;
        border-color: var(--primary) !important;
        font-weight: bold !important;
        box-shadow: 0 4px 14px 0 rgba(229, 9, 20, 0.39) !important;
    }
    .dt-container .pagination .page-item.disabled .page-link, .dt-container nav ul li.disabled a, .dt-container nav ul li button[disabled] {
        opacity: 0.3 !important;
        cursor: not-allowed !important;
        background-color: transparent !important;
    }

    /* Remove Tailwind DataTables artifacts */
    .dt-container .grid {
        margin-bottom: 1rem;
        margin-top: 1rem;
    }
  </style>
</head>

<body class="min-h-screen flex flex-row">
  <!-- Sidebar -->
  <aside id="admin-sidebar" class="admin-sidebar w-60 min-h-screen flex flex-col fixed inset-y-0 left-0 z-100 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
    <!-- Brand -->
    <div class="py-4 px-4 border-b border-white/10 flex items-center justify-between sidebar-brand">
      <div class="sidebar-brand-content">
        <a href="/admin" class="flex items-center gap-1 hover:bg-transparent!" style="text-decoration:none;">
          <i data-lucide="clapperboard" class="w-5 h-5" style="color: var(--primary);"></i>
          <span class="font-display tracking-widest text-base font-bold uppercase text-white m-0 leading-none">
            Mov<span style="color: var(--primary);">Prima</span>
          </span>
        </a>
        <p class="text-xs text-zinc-500 mt-2 font-display uppercase tracking-wider">Panel Admin</p>
      </div>
      <button id="close-sidebar-btn" class="text-zinc-400 hover:text-white lg:hidden flex items-center justify-center w-6 h-6 bg-white/5 hover:bg-white/10 rounded transition-colors">
        <i data-lucide="x" class="w-4 h-4"></i>
      </button>
    </div>

    <!-- Nav links -->
    <nav class="flex-1 overflow-y-auto py-4">
      <ul class="flex flex-col gap-1 px-3 list-none p-0 m-0">
        <?php
        $path = parse_url(current_url(), PHP_URL_PATH);
        $path = preg_replace("#^/index\.php#", "", $path);
        $isAdmin = function (string $seg = "") use ($path) {
          return $path === "/admin" . ($seg ? "/$seg" : "") || str_starts_with($path, "/admin/" . $seg);
        };
        ?>
        <li>
          <a href="/admin" class="flex items-center gap-2 px-4 py-2 rounded transition-colors text-sm! font-bold uppercase font-display tracking-wider <?= $path === "/admin" ? "active" : "text-zinc-400" ?>" style="text-decoration:none;">
            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dasbor
          </a>
        </li>
        <li>
          <a href="/admin/movies" class="flex items-center gap-2 px-4 py-2 rounded transition-colors text-sm! font-bold uppercase font-display tracking-wider <?= $isAdmin("movies") ? "active" : "text-zinc-400" ?>" style="text-decoration:none;">
            <i data-lucide="film" class="w-4 h-4"></i> Film
          </a>
        </li>
        <li>
          <a href="/admin/genres" class="flex items-center gap-2 px-4 py-2 rounded transition-colors text-sm! font-bold uppercase font-display tracking-wider <?= $isAdmin("genres") ? "active" : "text-zinc-400" ?>" style="text-decoration:none;">
            <i data-lucide="tag" class="w-4 h-4"></i> Genre
          </a>
        </li>
        <li>
          <a href="/admin/reviews" class="flex items-center gap-2 px-4 py-2 rounded transition-colors text-sm! font-bold uppercase font-display tracking-wider <?= $isAdmin("reviews") ? "active" : "text-zinc-400" ?>" style="text-decoration:none;">
            <i data-lucide="star" class="w-4 h-4"></i> Ulasan
          </a>
        </li>
        <li>
          <a href="/admin/users" class="flex items-center gap-2 px-4 py-2 rounded transition-colors text-sm! font-bold uppercase font-display tracking-wider <?= $isAdmin("users") ? "active" : "text-zinc-400" ?>" style="text-decoration:none;">
            <i data-lucide="users" class="w-4 h-4"></i> Pengguna
          </a>
        </li>
      </ul>
    </nav>

    <!-- Sidebar user footer -->
    <div class="p-4 border-t border-white/10 mt-auto">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 bg-black text-white border border-white/20 rounded-full flex items-center justify-center shrink-0 shadow-lg">
          <span class="text-sm font-bold font-display">
            <?= strtoupper(substr(session()->get("user_name") ?? "A", 0, 1)) ?>
          </span>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-white truncate m-0 font-display tracking-wide"><?= esc((string) (session()->get("user_name") ?? "Admin")) ?></p>
          <p class="text-xs text-zinc-500 m-0 uppercase font-display tracking-wider">Administrator</p>
        </div>
        <a href="/auth/logout" class="text-zinc-500 hover:text-(--primary) transition-colors hover:bg-transparent!" title="Keluar">
          <i data-lucide="log-out" class="w-5 h-5"></i>
        </a>
      </div>
    </div>
  </aside>

  <!-- Main content area -->
  <div class="flex-1 lg:ml-60 flex flex-col min-h-screen w-full min-w-0 overflow-x-hidden">

    <!-- Mobile top bar -->
    <header class="bg-[#12121c] border-b border-white/10 lg:hidden sticky top-0 z-40 flex items-center justify-between px-6 py-4 shadow-xl">
      <a href="/admin" class="flex items-center gap-1 hover:bg-transparent!" style="text-decoration:none;">
        <i data-lucide="clapperboard" class="w-5 h-5" style="color: var(--primary);"></i>
        <span class="font-display tracking-widest text-base font-bold uppercase text-white m-0 leading-none">
            Mov<span style="color: var(--primary);">Prima</span>
        </span>
      </a>
      <button id="mobile-menu-btn" class="text-zinc-400 hover:text-white flex items-center justify-center bg-transparent!">
        <i data-lucide="menu" class="w-4 h-4"></i>
      </button>
    </header>

      <!-- Flash messages -->
    <?php if (session()->getFlashdata("success") || session()->getFlashdata("error")): ?>
      <div id="flash-message-container" class="fixed top-4 right-4 z-100 w-[90%] max-w-xl transition-opacity duration-300">
        <?php if (session()->getFlashdata("success")): ?>
          <div class="p-4 mb-4 rounded bg-green-900/90 border border-green-500 text-green-100 flex items-center justify-between gap-3 shadow-lg backdrop-blur-sm">
            <div class="flex gap-3 items-center">
              <i data-lucide="check-circle" class="mt-0.5 shrink-0 w-5 h-5"></i>
              <span class="text-xs"><?= esc((string) session()->getFlashdata("success")) ?></span>
            </div>
            <button onclick="document.getElementById('flash-message-container').remove()" class="text-green-300 hover:text-white transition-colors flex items-center justify-center">
              <i data-lucide="x" class="w-5 h-5"></i>
            </button>
          </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata("error")): ?>
          <div class="p-4 mb-4 rounded bg-red-900/90 border border-red-500 text-red-100 flex items-center justify-between gap-3 shadow-lg backdrop-blur-sm">
            <div class="flex gap-3 items-center">
              <i data-lucide="alert-circle" class="mt-0.5 shrink-0 w-5 h-5"></i>
              <span class="text-xs"><?= esc((string) session()->getFlashdata("error")) ?></span>
            </div>
            <button onclick="document.getElementById('flash-message-container').remove()" class="text-red-300 hover:text-white transition-colors flex items-center justify-center">
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

      <main class="flex-1 p-6 w-full container mx-auto">
          <?= $this->renderSection("content") ?>
      </main>
  </div>

  <?= $this->renderSection("scripts") ?>
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }

        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar-btn');
        const sidebar = document.getElementById('admin-sidebar');

        if (mobileMenuBtn && sidebar) {
          mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            sidebar.classList.remove('-translate-x-full');
          });
        }

        if (closeSidebarBtn && sidebar) {
          closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebar.classList.add('-translate-x-full');
          });
        }

        // Initialize DataTable if table with id datatable exists
        if(document.getElementById('datatable')) {
          new DataTable('#datatable', {
            responsive: true,
            pagingType: 'numbers',
            language: {
                search: "Cari:",
                searchPlaceholder: "Masukkan kata kunci",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang tersedia",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                  first: "Awal",
                  last: "Akhir",
                  next: "Selanjutnya",
                  previous: "Sebelumnya"
                }
              }
          });
        }
      });
    </script>
</body>
</html>
