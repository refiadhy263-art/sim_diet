  <div id="sidebar-backdrop" class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden" onclick="toggleSidebar(false)"></div>
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white shadow-lg flex flex-col transform -translate-x-full transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 lg:shadow-none lg:flex lg:w-64 no-print">
      <div class="p-5 border-b border-gray-200">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center shadow-md">
            <i class="fi fi-rr-bowl-rice text-white text-lg flex items-center justify-center"></i>
          </div>
          <div>
            <h2 class="font-bold text-gray-800 text-sm">SIMDIET <br>
              <?= nama_role(session()->get('role')) ?></h2>
            <p class="text-xs text-gray-500">
        <?php if(session()->get('role') == '2' || session()->get('role') == '4'): ?>
            <?= session()->get('kd_bangsal') ?>
        <?php endif; ?>
          </p>
          </div>
        </div>
      </div>
   
      
      <!-- Navigation Menu -->
      <nav class="flex-1 p-3 space-y-1 overflow-y-auto">

      <?php if(session()->get('role') == '1')
        { ?>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('dashboard') ?>">
          <i class="fi fi-rr-apps text-lg flex items-center"></i> Dashboard
        </a>
        <a href="<?= base_url('perawat') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('perawat') ?>">
          <i class="fi fi-rr-doctor text-lg flex items-center"></i> Data Perawat
        </a>
        <a href="<?= base_url('gizi') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('gizi') ?>">
          <i class="fi fi-rr-salad text-lg flex items-center"></i> Data Ahli Gizi
        </a>
         <a href="<?= base_url('pramusaji') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pramusaji') ?>">
          <i class="fi fi-rr-restaurant text-lg flex items-center"></i> Data Pramusaji
        </a>
        <a href="<?= base_url('pasien') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien') ?>">
          <i class="fi fi-rr-hospital text-lg flex items-center"></i> Data Pasien
        </a>
        <a href="<?= base_url('jenis_diet') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2  rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('jenis_diet') ?>">
          <i class="fi fi-rr-bowl-rice text-lg flex items-center"></i> Jenis Diet
        </a>
        <a href="<?= base_url('bentuk_diet') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('bentuk_diet') ?>">
          <i class="fi fi-rr-soup text-lg flex items-center"></i> Bentuk Diet
        </a>
        <a href="<?= base_url('bangsal') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('bangsal') ?>">
          <i class="fi fi-rr-bed-alt text-lg flex items-center"></i> Data Bangsal
        </a>
        <a href="<?= base_url('bed') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('bed') ?>">
          <i class="fi fi-rr-bed text-lg flex items-center"></i> Manajemen Bed
        </a>
        <?php }  if(session()->get('role') == '2'){ ?>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('dashboard') ?>">
          <i class="fi fi-rr-apps text-lg flex items-center"></i> Dashboard
        </a>
        <a href="<?= base_url('pasien/dirawat') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/dirawat') ?>">
          <i class="fi fi-rr-hospital text-lg flex items-center"></i> Data Pasien
        </a>
         <a href="<?= base_url('pasien/edit_status_rawat') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/edit_status_rawat') ?>">
          <i class="fi fi-rr-refresh text-lg flex items-center"></i> Update Status Rawat
        </a>
         <a href="<?= base_url('pasien/pulang_meninggal') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/pulang_meninggal') ?>">
          <i class="fi fi-rr-exit text-lg flex items-center"></i> Pasien Pulang/Meninggal
        </a>

        <?php  }if(session()->get('role') == '3'){ ?>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('dashboard') ?>">
          <i class="fi fi-rr-apps text-lg flex items-center"></i> Dashboard
        </a>
        <a href="<?= base_url('pasien/all') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/all') ?>">
          <i class="fi fi-rr-hospital text-lg flex items-center"></i> Data Pasien
        </a>
          
        <a href="<?= base_url('pasien/rekap_bangsal') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/rekap_bangsal') ?>">
          <i class="fi fi-rr-bed-alt text-lg flex items-center"></i> Rekap Bangsal
        </a>



        <?php  } if(session()->get('role') == '4'){ ?>
        <a href="<?= base_url('dashboard') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pramusaji/dashboard') ?>">
          <i class="fi fi-rr-apps text-lg flex items-center"></i> Dashboard
        </a>
         <a href="<?= base_url('pasien/rekap_order') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('pasien/rekap_order') ?>">
          <i class="fi fi-rr-file-invoice text-lg flex items-center"></i> Rekap Bangsal
        </a>

         <?php  } ?>


        <a href="<?= base_url('logs') ?>" class="sidebar-item w-full flex items-center gap-3 px-4 py-2 rounded-xl text-left text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition <?= active_page('logs') ?>">
          <i class="fi fi-rr-document-signed text-lg flex items-center"></i> Log Aktivitas
        </a>
      </nav>

      <!-- Footer Actions -->
     
      <div class="p-4 border-t border-gray-200">
        <a href="<?= base_url('logout') ?>" class="w-full flex items-center justify-center gap-2 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition">
          <i class="fi fi-rr-sign-out-alt flex items-center"></i> Keluar
        </a>
      </div>
    </aside>