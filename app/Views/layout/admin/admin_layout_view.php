<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <?= $this->renderSection('title') ?>
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.1.0/uicons-regular-rounded/css/uicons-regular-rounded.css">

  <?= $this->renderSection('style') ?>
  <style>
    * { font-family: 'Inter', sans-serif; }
    body { background-color: #f3f4f6; font-size: 14px; }

    .modal-backdrop {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);
      z-index: 50; display: flex; align-items: center; justify-content: center;
    }
    .sidebar-item:hover { background-color: #eff6ff; color: #1e40af; }
    
    /* CSS KHUSUS AGAR SIDEBAR & TOMBOL HILANG SAAT DI PRINT */
    @media print {
      aside, .no-print, header, #resetDataBtn { display: none !important; }
      main { width: 100% !important; padding: 0 !important; margin: 0 !important; }
      body { background: white; }
      .bg-white { border: none !important; box-shadow: none !important; }
      table { width: 100% !important; border-collapse: collapse; }
      th, td { border: 1px solid #e2e8f0 !important; padding: 8px !important; }
    }

    
    <?= $this->renderSection('style') ?>
  </style>
</head>
<body class="bg-gray-100">
  <div class="flex h-screen">
    <!-- Sidebar -->
    <?= $this->include('layout/admin/admin_sidebar_view') ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden bg-gray-100">
      <!-- Header -->
      <header class="bg-white shadow-sm px-6 py-4 border-b border-gray-200 no-print">
        <div class="flex items-center justify-between gap-4">
          <div class="flex items-center gap-3">
            <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 shadow-sm hover:bg-gray-50 lg:hidden" onclick="toggleSidebar(true)" aria-label="Buka menu">
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
              </svg>
            </button>
            <div class="text-xl font-bold text-gray-800"><?= $this->renderSection('title') ?></div>
          </div>
          <div class="px-3 py-2 bg-blue-100 rounded-lg">
            <span class="text-sm text-gray-600"> <i class="fi fi-rr-calendar mr-2"></i> <?= hari(date('w')).", ".tanggal_indo(date('Y-m-d')) ?></span>
          </div>
        </div>
      </header>

      <!-- Content Area -->
      <div id="content-area" class="flex-1 p-6 overflow-y-auto">
        <?= $this->renderSection('content') ?>
      </div>
    </main>
  </div>

  <!-- Scripts -->
  <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
  
  <script>
    // Set current date
    // document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', {
    //   weekday: 'long',
    //   year: 'numeric',
    //   month: 'long',
    //   day: 'numeric'
    // });

    // Reset data function
    function resetAllData() {
      if (confirm('PERINGATAN: Semua data akan dihapus dan dikembalikan ke data awal. Lanjutkan?')) {
        // Handle reset logic here
        alert('Data telah direset ke kondisi awal.');
        location.reload();
      }
    }

    // Mobile sidebar toggle
    function toggleSidebar(show) {
      const sidebar = document.getElementById('sidebar');
      const backdrop = document.getElementById('sidebar-backdrop');
      if (!sidebar || !backdrop) return;
      const shouldShow = typeof show === 'boolean' ? show : sidebar.classList.contains('-translate-x-full');
      if (shouldShow) {
        sidebar.classList.remove('-translate-x-full');
        backdrop.classList.remove('hidden');
      } else {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
      }
    }

    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        toggleSidebar(false);
      }
    });

   
  </script>

  <?= $this->renderSection('script') ?>
</body>
</html>
