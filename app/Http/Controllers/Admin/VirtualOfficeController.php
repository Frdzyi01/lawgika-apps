<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Correspondence;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VirtualOfficeController extends Controller
{
    /**
     * Display a listing of the Virtual Office clients.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Order::with(['user', 'roomBenefits'])
            ->where(function ($q) {
                $q->where('service_name', 'like', '%Virtual Office%')
                  ->orWhereJsonContains('form_data->service', 'virtual-office');
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q2) use ($search) {
                      $q2->where('company_name', 'like', "%{$search}%")
                         ->orWhere('pic_name', 'like', "%{$search}%")
                         ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $virtualOffices = $query->orderBy('created_at', 'desc')->paginate(10);

        foreach ($virtualOffices as $vo) {
            $this->appendVirtualOfficeData($vo);
        }

        return view('admin.virtual-office.index', compact('virtualOffices', 'search'));
    }

    /**
     * Display the specified Virtual Office client detail.
     */
    public function show($id)
    {
        $vo = Order::with(['user', 'roomBenefits'])->findOrFail($id);
        
        // Ensure it is a virtual office order
        if (!str_contains(strtolower($vo->service_name), 'virtual office') && ($vo->form_data['service'] ?? '') !== 'virtual-office') {
            abort(404, 'Data bukan Virtual Office');
        }

        $this->appendVirtualOfficeData($vo);

        // Fetch root correspondences for this user
        $correspondences = Correspondence::root()
            ->where('user_id', $vo->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.virtual-office.show', compact('vo', 'correspondences'));
    }

    /**
     * Appends calculated fields to the order object for Virtual Office view.
     */
    private function appendVirtualOfficeData($vo)
    {
        $vo->vo_package = $vo->form_data['package'] ?? 'Virtual Office';
        if (str_contains(strtolower($vo->service_name), 'premium')) $vo->vo_package = 'Premium';
        if (str_contains(strtolower($vo->service_name), 'eksklusif')) $vo->vo_package = 'Eksklusif';
        if (str_contains(strtolower($vo->service_name), 'enterprise')) $vo->vo_package = 'Enterprise';

        $vo->vo_package = ucfirst($vo->vo_package);

        // Calculate dates & benefits
        $vo->benefit_meeting = null;
        $vo->benefit_podcast = null;
        $vo->tanggal_aktif = null;
        $vo->tanggal_expired = null;
        $vo->sisa_hari = 0;
        $vo->vo_status = 'Pending';
        $vo->vo_status_color = 'warning';

        if ($vo->status === 'approved') {
            $benefits = $vo->roomBenefits;
            
            if ($benefits->isNotEmpty()) {
                $vo->benefit_meeting = $benefits->where('type', 'meeting')->first();
                $vo->benefit_podcast = $benefits->where('type', 'podcast')->first();
                
                $vo->tanggal_aktif = $benefits->first()->created_at;
                $vo->tanggal_expired = $benefits->first()->expired_at;
                
                $days = Carbon::now()->diffInDays(Carbon::parse($vo->tanggal_expired), false);
                $vo->sisa_hari = max(0, $days);

                if (Carbon::now()->startOfDay()->greaterThan(Carbon::parse($vo->tanggal_expired)->startOfDay())) {
                    $vo->vo_status = 'Expired';
                    $vo->vo_status_color = 'danger';
                } elseif ($days <= 30) {
                    $vo->vo_status = 'Segera Berakhir';
                    $vo->vo_status_color = 'warning';
                } else {
                    $vo->vo_status = 'Aktif';
                    $vo->vo_status_color = 'success';
                }
            } else {
                // Fallback if no benefits found but order is approved
                $vo->tanggal_aktif = $vo->updated_at;
                $vo->tanggal_expired = Carbon::parse($vo->updated_at)->addYear();
                
                $days = Carbon::now()->diffInDays($vo->tanggal_expired, false);
                $vo->sisa_hari = max(0, $days);

                if (Carbon::now()->startOfDay()->greaterThan($vo->tanggal_expired->startOfDay())) {
                    $vo->vo_status = 'Expired';
                    $vo->vo_status_color = 'danger';
                } elseif ($days <= 30) {
                    $vo->vo_status = 'Segera Berakhir';
                    $vo->vo_status_color = 'warning';
                } else {
                    $vo->vo_status = 'Aktif';
                    $vo->vo_status_color = 'success';
                }
            }
        }
    }
}
