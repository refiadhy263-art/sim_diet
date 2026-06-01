<?php

if (!function_exists('status')) {
    /**
     * Function to handle if-else for status.
     *
     * @param string $status The status value ('active' or 'inactive').
     * @return string The result based on the status.
     */
    function status($status)
    {
        if ($status === '1') {
            return "Aktif";
        } else {
            return "Non Aktif";
        }
    }
}
if (!function_exists('bulan')) {
    function bulan($i)
    {
        // Array bulan dalam bahasa Indonesia
        $bulan = [
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        // Mengambil bagian tanggal dari input
        // Mengambil bulan dalam angka

        return $bulan[$i];
    }
}

function tanggal_indo($tanggal, $format = 'd F Y', $bahasa = 'id')
{
    $bulan_id = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    $bulan_en = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
        12 => 'December'
    ];

    $bulan = $bahasa === 'id' ? $bulan_id : $bulan_en;

    $tanggal = date_create($tanggal);
    $hari = date_format($tanggal, 'd');
    $bulan_text = $bulan[(int) date_format($tanggal, 'm')];
    $tahun = date_format($tanggal, 'Y');

    return str_replace(['d', 'F', 'Y'], [$hari, $bulan_text, $tahun], $format);
}

if (!function_exists('role')) {
    function role()
    {
        // Mengambil bagian tanggal dari input
        // Mengambil tahun dalam angka
        $role = [
            '1' => 'Admin',
            '2' => 'Perawat Bangsal',
            '3' => 'Petugas Gizi',
            '4' => 'Pramusaji Bangsal'
        ];
        return $role;
    }
}

if (!function_exists('hari')) {
    function hari($angka)
    {
        $hari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];

        // Pastikan angka berada dalam rentang 0-6
        if ($angka >= 0 && $angka <= 6) {
            return $hari[$angka];
        } else {
            return "Angka tidak valid. Harus antara 0 dan 6.";
        }
    }
}

if (!function_exists('nama_role')) {
    function nama_role($role)
    {
        $roles = [
            '1' => 'Admin',
            '2' => 'Perawat Bangsal',
            '3' => 'Petugas Gizi',
            '4' => 'Pramusaji Bangsal'
        ];
        return $roles[$role] ?? 'Unknown';
    }
}




if (!function_exists('orderStatus')) {
    function orderStatus($status)
    {
        $statusMap = [
            '0' => ['label' => 'Menunggu', 'color' => 'bg-amber-50 text-amber-600'],
            '1' => ['label' => 'Sedang Disiapkan', 'color' => 'bg-blue-50 text-blue-600'],
            '2' => ['label' => 'Siap Antar', 'color' => 'bg-green-50 text-green-600'],
            '3' => ['label' => 'Sedang Diantar', 'color' => 'bg-purple-50 text-purple-600'],
            '4'  => ['label' => 'Selesai', 'color' => 'bg-gray-50 text-gray-600'],
        ];

        return $statusMap[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-gray-50 text-gray-600'];
    }
    
}


if (!function_exists('rawatStatus')) {
    function rawatStatus($status)
    {
        $statusMap = [
            '0' => ['label' => 'Dirawat', 'color' => 'bg-amber-50 text-amber-600'],
            '1' => ['label' => 'Pulang', 'color' => 'bg-blue-50 text-blue-600'],
            '2' => ['label' => 'Meninggal', 'color' => 'bg-red-50 text-red-600'],
            '3' => ['label' => 'Pindah Bangsal', 'color' => 'bg-purple-50 text-purple-600'],
        ];

        return $statusMap[$status] ?? ['label' => ucfirst($status), 'color' => 'bg-gray-50 text-gray-600'];
    }
    
}

if (!function_exists('active_page')) {
    function active_page($page, $activeClass = 'bg-blue-50 text-blue-700 font-semibold')
    {
        $request = \Config\Services::request();
        $currentURI = $request->getPath();
        
        // Normalize paths by trimming slashes
        $currentURI = trim($currentURI, '/');
        $page = trim($page, '/');
        
        if ($currentURI === $page) {
            return $activeClass;
        }
        
        // Check if current URI starts with the page followed by a slash (for subpages)
        if (strpos($currentURI, $page . '/') === 0) {
            return $activeClass;
        }
        
        return '';
    }
}
