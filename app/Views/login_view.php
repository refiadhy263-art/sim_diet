<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SIMDIET - Login</title>
  <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css') ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Noty.js -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta/lib/noty.css">
  <script src="https://cdn.jsdelivr.net/npm/noty@3.2.0-beta"></script>
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    /* Custom Noty.js Styling */
    .noty_base {
      font-family: 'Inter', sans-serif;

    }

    .noty_body {
      padding: 16px;
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(10px);
      z-index: 2;
    }

    /* Success Notification */
    .noty_type__success {
      background: linear-gradient(135deg, #10b981 0%, #059669 100%);
      color: white;
      border-left: 4px solid #059669;
    }

    /* Error Notification */
    .noty_type__error {
      background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
      color: white;
      border-left: 6px solid #dc2626;
    }

    /* Warning Notification */
    .noty_type__warning {
      background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
      color: white;
      border-left: 4px solid #d97706;
    }

    /* Info Notification */
    .noty_type__info {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      border-left: 4px solid #1d4ed8;
    }

    /* Progress Bar */
    .noty_progressbar {
      height: 3px;
      background: rgba(255, 255, 255, 0.5);
    }

    /* Close Button */
    .noty_close_button {
      color: white;
      opacity: 0.7;
      padding: 0 8px;
      font-size: 20px;
    }

    .noty_close_button:hover {
      opacity: 1;
    }

    /* Theme Nest adjustments */
    .noty_theme__nest .noty_body {
      border-radius: 8px;
    }
  </style>
</head>
<body class="h-screen flex items-center justify-center">
  <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md">
    <div class="text-center mb-6">
      <h1 class="text-3xl font-bold text-gray-800">SIMDIET RS</h1>
      <p class="text-gray-500">Sistem Informasi Manajemen Diet Pasien</p>
    </div>
    <form class="space-y-4" method="post" action="<?= base_url('login/process') ?>">
        <?= csrf_field() ?>
      <div>
        <label class="block text-sm font-medium text-gray-700">Role</label>
        <select name="role" id="role" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
          <option value="">-- Pilih Role --</option>
        <?php foreach(role() as $key => $value): ?>
          <option value="<?= $key ?>"><?= $value ?></option>
        <?php endforeach; ?>
        </select>
      </div>
      <div id="bangsalGroup" class="hidden">
        <label class="block text-sm font-medium text-gray-700">Pilih Bangsal</label>
        <select name="id_bangsal" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
          <?php foreach($bangsalList as $b): ?>
                        <option value="<?= esc($b['id_bangsal']) ?>">
                           <?= esc($b['nama_bangsal']) ?>
                        </option>
            <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Username</label>
        <input type="text" name="username" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <div class="relative mt-1">
          <input type="password" id="password" name="password" class="w-full px-4 py-2  border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10 outline-none transition-all" required>
          <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-500 hover:text-blue-500 focus:outline-none">
            <i id="toggleIcon" class="fa-solid fa-eye"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition">Masuk</button>
    </form>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.getElementById('role').addEventListener('change', function() {
      const role = this.value;
      const bangsalGroup = document.getElementById('bangsalGroup');
      bangsalGroup.classList.toggle('hidden', !(role === '2' || role === '4'));
    });

    function togglePassword() {
      const passwordInput = document.getElementById('password');
      const toggleIcon = document.getElementById('toggleIcon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }



    <?php if(session()->getFlashdata('msg')): ?>
      alert('<?= session()->getFlashdata('msg') ?>');
    <?php endif; ?>
  </script>
</body>
</html>