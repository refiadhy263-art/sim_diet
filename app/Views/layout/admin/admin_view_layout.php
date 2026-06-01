<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $this->renderSection('title') ?: 'SIMDIET - Admin' ?></title>
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="<?= base_url('../assets/img/aiska33.png') ?>">
  <style>
    * { font-family: 'Inter', sans-serif; }
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
    <aside class="w-64 bg-white shadow-lg flex flex-col z-10 no-print">
      <div class="p-5 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-gray-800 text-sm">SIMDIET Admin</h2>
            <p class="text-xs text-gray-500">Administrator</p>
          </div>
        </div>
      </div>
      
      <!-- Navigation Menu -->
      <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
        <a href="<?= route_to('admin.dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>📊</span> Dashboard
        </a>
        <a href="<?= route_to('admin.perawat') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>👩‍⚕️</span> Data Perawat
        </a>
        <a href="<?= route_to('admin.gizi') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🍽️</span> Data Ahli Gizi
        </a>
        <a href="<?= route_to('admin.pasien') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🏥</span> Data Pasien
        </a>
        <a href="<?= route_to('admin.diet') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🍽️</span> Jenis Diet
        </a>
        <a href="<?= route_to('admin.bentuk') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🥣</span> Bentuk Diet
        </a>
        <a href="<?= route_to('admin.bangsal') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🛏️</span> Data Bangsal
        </a>
        <a href="<?= route_to('admin.bed') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>🛌</span> Manajemen Bed
        </a>
        <a href="<?= route_to('admin.logs') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
          <span>📜</span> Log Aktivitas
        </a>
      </nav>

      <!-- Footer Actions -->
      <button onclick="resetAllData()" id="resetDataBtn" class="w-full flex items-center justify-center gap-2 py-2 text-sm text-orange-600 hover:bg-orange-50 rounded-lg mt-2 transition">
        🔄 Reset Data Awal
      </button>
      <div class="p-4 border-t border-gray-200">
        <a href="<?= route_to('logout') ?>" class="w-full flex items-center justify-center gap-2 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
          🚪 Keluar
        </a>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-hidden bg-gray-100">
      <!-- Header -->
      <header class="bg-white shadow-sm px-6 py-4 border-b border-gray-200 no-print">
        <div class="flex items-center justify-between">
          <div>
            <h1 id="page-title" class="text-xl font-bold text-gray-800"><?= $this->renderSection('page_title') ?: 'Dashboard' ?></h1>
            <p id="page-subtitle" class="text-sm text-gray-500"><?= $this->renderSection('page_subtitle') ?: '' ?></p>
          </div>
          <div class="px-3 py-2 bg-gray-100 rounded-lg">
            <span id="current-date" class="text-sm text-gray-600"></span>
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
  <script src="<?= base_url('assets/vendor/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= base_url('assets/vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/select2/js/select2.full.min.js') ?>"></script>
  <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

  <script>
    // Set current date
    document.getElementById('current-date').textContent = new Date().toLocaleDateString('id-ID', {
      weekday: 'long',
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });

    // Global Noty.js function
    function showNoty(message, type = 'info', timeout = 4000) {
      new Noty({
        type: type,
        layout: 'top',
        text: message,
        timeout: timeout,
        progressBar: true,
        closeWith: ['click'],
        theme: 'nest'
      }).show();
    }

    // Reset data function
    function resetAllData() {
      if (confirm('PERINGATAN: Semua data akan dihapus dan dikembalikan ke data awal. Lanjutkan?')) {
        // Handle reset logic here
        alert('Data telah direset ke kondisi awal.');
        location.reload();
      }
    }

    // Initialize Select2
    $(function() {
      $('.select2').select2({});
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      // Initialize DataTables
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false
      });
      
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true
      });
    });
  </script>

  <?= $this->renderSection('script') ?>
</body>
</html>
