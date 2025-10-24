<?php

namespace App\Livewire\Admin;

use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SubscriptionManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filterPlan = 'all';
    public $filterStatus = 'all';
    public $selectedTenant = null;
    public $showDetail = false;
    public $showUpgradeModal = false;

    // Form fields for subscription changes
    public $newPlan = null;
    public $billingCycle = 'monthly';

    protected $queryString = ['search', 'filterPlan', 'filterStatus'];

    // Plan details
    private $planDetails = [
        'free' => [
            'name' => 'Free',
            'price' => 0,
            'shipments_limit' => 10,
            'users_limit' => 2,
            'support' => 'Community',
            'color' => 'gray',
        ],
        'starter' => [
            'name' => 'Starter',
            'price' => 99,
            'shipments_limit' => 100,
            'users_limit' => 5,
            'support' => 'Email',
            'color' => 'green',
        ],
        'professional' => [
            'name' => 'Professional',
            'price' => 299,
            'shipments_limit' => 1000,
            'users_limit' => 20,
            'support' => 'Priority Email',
            'color' => 'blue',
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 999,
            'shipments_limit' => 'Unlimited',
            'users_limit' => 'Unlimited',
            'support' => '24/7 Phone & Email',
            'color' => 'purple',
        ],
    ];

    /**
     * Render the component
     */
    public function render()
    {
        $query = Tenant::query();

        // Search by name
        if ($this->search) {
            $query->where('name', 'like', "%{$this->search}%");
        }

        // Filter by plan
        if ($this->filterPlan !== 'all') {
            $query->where('subscription_plan', $this->filterPlan);
        }

        // Filter by status
        if ($this->filterStatus !== 'all') {
            $query->where('subscription_status', $this->filterStatus);
        }

        $tenants = $query->orderBy('name')
                         ->paginate(15);

        return view('livewire.admin.subscription-manager', [
            'tenants' => $tenants,
            'planDetails' => $this->planDetails,
        ]);
    }

    /**
     * View subscription details
     */
    public function viewDetails($tenantId)
    {
        try {
            $this->selectedTenant = Tenant::findOrFail($tenantId);
            $this->showDetail = true;
        } catch (\Exception $e) {
            Log::error('Error loading tenant details: ' . $e->getMessage());
            session()->flash('error', 'Failed to load tenant details');
        }
    }

    /**
     * Close detail modal
     */
    public function closeDetail()
    {
        $this->selectedTenant = null;
        $this->showDetail = false;
    }

    /**
     * Open upgrade modal
     */
    public function openUpgradeModal()
    {
        if (!$this->selectedTenant) {
            return;
        }

        $this->newPlan = $this->selectedTenant->subscription_plan;
        $this->showUpgradeModal = true;
    }

    /**
     * Close upgrade modal
     */
    public function closeUpgradeModal()
    {
        $this->showUpgradeModal = false;
        $this->newPlan = null;
    }

    /**
     * Upgrade/Downgrade plan
     */
    public function changePlan()
    {
        if (!$this->selectedTenant || !$this->newPlan) {
            session()->flash('error', 'Invalid plan selection');
            return;
        }

        try {
            $oldPlan = $this->selectedTenant->subscription_plan;
            $this->selectedTenant->subscription_plan = $this->newPlan;
            $this->selectedTenant->save();

            session()->flash('success', "Plan changed from {$oldPlan} to {$this->newPlan} successfully!");
            $this->showUpgradeModal = false;
            $this->selectedTenant = Tenant::find($this->selectedTenant->id);
        } catch (\Exception $e) {
            Log::error('Error changing plan: ' . $e->getMessage());
            session()->flash('error', 'Failed to change plan: ' . $e->getMessage());
        }
    }

    /**
     * Renew subscription
     */
    public function renewSubscription()
    {
        if (!$this->selectedTenant) {
            return;
        }

        try {
            $this->selectedTenant->subscription_expires_at = Carbon::now()->addYear();
            $this->selectedTenant->subscription_status = 'active';
            $this->selectedTenant->save();

            session()->flash('success', 'Subscription renewed successfully!');
            $this->selectedTenant = Tenant::find($this->selectedTenant->id);
        } catch (\Exception $e) {
            Log::error('Error renewing subscription: ' . $e->getMessage());
            session()->flash('error', 'Failed to renew subscription');
        }
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription()
    {
        if (!$this->selectedTenant) {
            return;
        }

        try {
            $this->selectedTenant->subscription_status = 'cancelled';
            $this->selectedTenant->save();

            session()->flash('success', 'Subscription cancelled!');
            $this->selectedTenant = Tenant::find($this->selectedTenant->id);
        } catch (\Exception $e) {
            Log::error('Error cancelling subscription: ' . $e->getMessage());
            session()->flash('error', 'Failed to cancel subscription');
        }
    }

    /**
     * Get plan details
     */
    public function getPlanDetails($planKey)
    {
        return $this->planDetails[$planKey] ?? null;
    }

    /**
     * Get status color class
     */
    public function getStatusColor($status)
    {
        return match($status) {
            'active' => 'green',
            'suspended' => 'yellow',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get plan color class
     */
    public function getPlanColor($plan)
    {
        return $this->planDetails[$plan]['color'] ?? 'gray';
    }
}
