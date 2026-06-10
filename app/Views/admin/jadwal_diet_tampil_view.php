<?= $this->extend('layout/admin/admin_layout_view') ?>

<?= $this->section('title') ?>
<title><?= $title ?> | SIMDIET </title>
<link href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="space-y-4">
    
    <div class="flex justify-between items-center flex-wrap gap-3">
        <h2 class="text-xl font-bold flex items-center gap-2"><i class="fi fi-rr-clock text-blue-600 flex items-center"></i> Edit Jadwal Aktif Order Makanan</h2>
      
    </div>

   <div class="bg-white rounded-2xl shadow-md overflow-hidden">
    <form id="form-jadwal-diet" class="bg-white rounded-2xl shadow-md p-6" method="post" action="<?= base_url('jadwal_diet/save') ?>" onsubmit="simpanJadwal(event)">
        <?= csrf_field() ?>
    <div class="p-4 bg-white border-b">
        <p class="text-sm text-gray-500 font-medium"></p>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-white border-b">
            <tr>
                <th class="p-4 text-left">Jadwal Pagi</th>
                <th class="p-4 text-left">Jadwal Siang</th>
                <th class="p-4 text-left">Jadwal Malam</th>
            </tr>
        </thead>
        <tbody class="divide-y border-t">
                <?php foreach ($jadwal_diet as $j): ?>
                    <input type="hidden" name="id_jadwal_diet" value="<?= esc($j['id_jadwal_diet']) ?>">
                    <tr class="hover:bg-white transition-colors">
                        <td class="p-4 font-bold text-gray-700">
                            <input type="time" name="jadwal_pagi" value="<?= esc($j['jadwal_pagi']) ?>" class="border border-gray-300 rounded-lg p-2 bg-white shadow-sm focus:ring-blue-500 outline-none w-full max-w-xs">
                        </td>
                        <td class="p-4 font-mono text-gray-500 text-xs">
                            <input type="time" name="jadwal_siang" value="<?= esc($j['jadwal_siang']) ?>" class="border border-gray-300 rounded-lg p-2 bg-white shadow-sm focus:ring-blue-500 outline-none w-full max-w-xs">
                        </td>
                        <td class="p-4 font-bold text-blue-600">
                            <input type="time" name="jadwal_malam" value="<?= esc($j['jadwal_malam']) ?>" class="border border-gray-300 rounded-lg p-2 bg-white shadow-sm focus:ring-blue-500 outline-none w-full max-w-xs">
                        </td>
                      
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="p-4 text-right">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Simpan
                        </button>
                    </td>
                </tr>
           
        </tbody>
    </table>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.3.8/js/dataTables.tailwindcss.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Save Action
function simpanJadwal(event) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Simpan perubahan jadwal diet?',
        text: "Pastikan jadwal yang diinput sudah benar.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#d1d5db',
        confirmButtonText: 'Ya, simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            submitForm();
        }
    });
}

function submitForm() {
    const formData = new FormData(document.getElementById('form-jadwal-diet'));
    const url = '<?= base_url('jadwal_diet/save') ?>';
    
    console.log('Sending request to:', url);
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.text(); // Get text first
    })
    .then(text => {
        console.log('Raw response:', text);
        
        try {
            const data = JSON.parse(text);
            console.log('Parsed JSON:', data);
            
            if(data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 2000
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Gagal menyimpan data.'
                });
            }
        } catch (e) {
            console.error('JSON parse error:', e);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Invalid response format: ' + text.substring(0, 100)
            });
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Koneksi',
            text: 'Tidak dapat terhubung ke server: ' + error.message
        });
    });
}
</script>
<?= $this->endSection() ?>