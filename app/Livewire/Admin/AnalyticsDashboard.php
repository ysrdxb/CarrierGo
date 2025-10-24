<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsDashboard extends Component
{
    public $dateRange = '30'; // days
    public $selectedTenant = null;
    public $analytics = [];

    /**
     * Mount the component
     */
    public function mount()
    {
        $this->loadAnalytics();
    }

    /**
     * Render the component
     */
    public function render()
    {
        $tenants = Tenant::orderBy('name')->get();

        return view('livewire.admin.analytics-dashboard', [
            'tenants' => $tenants,
            'analytics' => $this->analytics,
        ]);
    }

    /**
     * Update date range
     */
    public function updateDateRange($range)
    {
        $this->dateRange = $range;
        $this->loadAnalytics();
    }

    /**
     * Load analytics data
     */
    private function loadAnalytics()
    {
        $startDate = Carbon::now()->subDays((int)$this->dateRange);

        // Overall statistics
        $this->analytics['total_tenants'] = Tenant::count();
        $this->analytics['active_tenants'] = Tenant::where('subscription_status', 'active')->count();
        $this->analytics['suspended_tenants'] = Tenant::where('subscription_status', 'suspended')->count();
        $this->analytics['cancelled_tenants'] = Tenant::where('subscription_status', 'cancelled')->count();

        // Plan distribution
        $this->analytics['plan_distribution'] = [
            'free' => Tenant::where('subscription_plan', 'free')->count(),
            'starter' => Tenant::where('subscription_plan', 'starter')->count(),
            'professional' => Tenant::where('subscription_plan', 'professional')->count(),
            'enterprise' => Tenant::where('subscription_plan', 'enterprise')->count(),
        ];

        // Revenue calculation (placeholder - would need actual invoice data)
        $this->analytics['mrr'] = $this->calculateMRR();
        $this->analytics['arr'] = $this->analytics['mrr'] * 12;

        // Tenant growth
        $this->analytics['new_tenants'] = Tenant::where('created_at', '>=', $startDate)->count();
        $this->analytics['churn_rate'] = $this->calculateChurnRate();

        // Status breakdown
        $this->analytics['status_breakdown'] = [
            'active' => Tenant::where('subscription_status', 'active')->get(),
            'suspended' => Tenant::where('subscription_status', 'suspended')->get(),
            'cancelled' => Tenant::where('subscription_status', 'cancelled')->get(),
        ];

        // Expiring soon (within 30 days)
        $this->analytics['expiring_soon'] = Tenant::where('subscription_expires_at', '<', Carbon::now()->addDays(30))
                                                   ->where('subscription_expires_at', '>=', Carbon::now())
                                                   ->get();

        // Expired subscriptions
        $this->analytics['expired'] = Tenant::where('subscription_expires_at', '<', Carbon::now())
                                            ->get();
    }

    /**
     * Calculate Monthly Recurring Revenue
     */
    private function calculateMRR()
    {
        $planPrices = [
            'free' => 0,
            'starter' => 99,
            'professional' => 299,
            'enterprise' => 999,
        ];

        $mrr = 0;
        foreach ($planPrices as $plan => $price) {
            $count = Tenant::where('subscription_plan', $plan)
                          ->where('subscription_status', 'active')
                          ->count();
            $mrr += $count * $price;
        }

        return $mrr;
    }

    /**
     * Calculate churn rate
     */
    private function calculateChurnRate()
    {
        $cancelled = Tenant::where('subscription_status', 'cancelled')->count();
        $total = Tenant::count();

        return $total > 0 ? round(($cancelled / $total) * 100, 2) : 0;
    }

    /**
     * Get trend data for chart
     */
    public function getTrendData()
    {
        $dates = [];
        $counts = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('M d');
            $counts[] = Tenant::where('created_at', '<=', $date)->count();
        }

        return [
            'labels' => $dates,
            'data' => $counts,
        ];
    }
}
