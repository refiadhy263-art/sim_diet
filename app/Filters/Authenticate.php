<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Authenticate implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!session('logged_in')) {
            return redirect()->to(site_url(''));
        }

        $uri = trim($request->getUri()->getPath(), '/');
        $role = (string) session()->get('role');

        $adminRoutes = [
            'perawat',
            'perawat/getData',
            'perawat/save',
            'perawat/edit',
            'perawat/update',
            'perawat/delete',
            'gizi',
            'gizi/getData',
            'gizi/save',
            'gizi/edit',
            'gizi/update',
            'gizi/delete',
            'pramusaji',
            'pramusaji/getData',
            'pramusaji/save',
            'pramusaji/edit',
            'pramusaji/update',
            'pramusaji/delete',
            'jenis_diet',
            'jenis_diet/getData',
            'jenis_diet/save',
            'jenis_diet/edit',
            'jenis_diet/update',
            'jenis_diet/delete',
            'bentuk_diet',
            'bentuk_diet/getData',
            'bentuk_diet/save',
            'bentuk_diet/edit',
            'bentuk_diet/update',
            'bentuk_diet/delete',
            'bangsal',
            'bangsal/getData',
            'bangsal/save',
            'bangsal/edit',
            'bangsal/update',
            'bangsal/delete',
            'bed',
            'bed/getData',
            'bed/save',
            'bed/edit',
            'bed/update',
            'bed/delete',
            'logs',
            'logs/getData',
            'pasien/getBeds',
            'pasien/getData',
            'pasien/save',
            'pasien/edit',
            'pasien/update',
            'pasien/delete',
        ];

        $perawatRoutes = [
            'dashboard/getPerawatDashboardData',
            'pasien/dirawat',
            'pasien/edit_status_rawat',
            'pasien/update_status_rawat',
            'pasien/getPasienDirawat',
            'pasien/getBangsalTersedia',
            'pasien/pindah_bangsal',
            'pasien/pulang_meninggal',
            'pasien/kembalikan_dirawat',
        ];

        $giziRoutes = [
            'dashboard/getGiziDashboardData',
            'pasien/all',
            'pasien/getAllData',
            'pasien/print_label',
            'pasien/rekap_bangsal',
            'pasien/detail_bangsal',
            'pasien/update_batch_status',
            'pasien/rekap_order',
        ];

        $pramusajiRoutes = [
            'dashboard/getPramusajiDashboardData',
            'dashboard/getDashboardData',
            'dashboard/prosesAntar',
            'dashboard/verifyReception',
        ];

        $matches = function (string $uri, array $patterns): bool {
            foreach ($patterns as $pattern) {
                if (str_starts_with($uri, $pattern)) {
                    return true;
                }
            }
            return false;
        };

        if ($matches($uri, $adminRoutes) && $role !== '1') {
            return redirect()->to(site_url('dashboard'));
        }

        if ($matches($uri, $perawatRoutes) && $role !== '2') {
            return redirect()->to(site_url('dashboard'));
        }

        if ($matches($uri, $giziRoutes) && $role !== '3') {
            return redirect()->to(site_url('dashboard'));
        }

        if ($matches($uri, $pramusajiRoutes) && $role !== '4') {
            return redirect()->to(site_url('dashboard'));
        }

        if (!empty($arguments)) {
            $allowedRoles = [];
            foreach ($arguments as $arg) {
                foreach (explode(',', $arg) as $roleItem) {
                    $trimmed = trim($roleItem);
                    if ($trimmed !== '') {
                        $allowedRoles[] = $trimmed;
                    }
                }
            }

            if (!in_array($role, $allowedRoles, true)) {
                return redirect()->to(site_url('dashboard'));
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
