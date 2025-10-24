<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReferenceController;
use App\Livewire\Permissions;
use App\Livewire\Roles;
use App\Livewire\Employees;
use App\Livewire\Bank;
use App\Livewire\Settings;
use App\Livewire\Companies;
use App\Livewire\Languages;
use App\Livewire\BankDetails;
use App\Livewire\CreateReferences;
use App\Livewire\ReferencesList;
use App\Livewire\EmployeeReferenceNumbers;
use App\Livewire\EmployeeNumberRanges;
use App\Livewire\DatabaseEntriesCreate;
use App\Livewire\DatabaseEntriesList;
use App\Livewire\ReferencesEdit;
use App\Livewire\Invoices;
use App\Livewire\InvoiceDetail;
use App\Livewire\Shipments;
use App\Livewire\CreateIncInvoice;
use App\Livewire\IncInvoice;
use App\Livewire\IncInvoiceDetail;
use App\Livewire\IncInvoiceEdit;
use App\Livewire\FreightTypes;
use App\Livewire\UserReference;
use App\Livewire\Destinations;
use App\Livewire\Auth\RegisterTenant;
use App\Livewire\CompanySetup;
use App\Livewire\Admin\TenantManager;
use App\Livewire\Admin\SubscriptionManager;
use App\Livewire\Admin\AnalyticsDashboard;
use App\Livewire\Admin\AdminUserManager;
use App\Livewire\Admin\RegistrationApprovalPanel;

Livewire::setScriptRoute(function($handle) {
    return Route::get('/carriergo/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function($handle) {
    return Route::get('/carriergo/livewire/update', $handle);
});

// Public routes
Route::get('/register', RegisterTenant::class)->name('tenant.register')->middleware('guest');
Route::get('/setup/company/{registration_id}', CompanySetup::class)->name('company-setup')->middleware('guest');
Route::get('/track-shipment', Shipments::class)->name('shipment.track');
Route::get('/', Shipments::class)->name('dashboard');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
	Route::get('/user/reference', UserReference::class)->name('user.reference');

	Route::middleware(['admin'])->group(function () {
		// roles
		Route::get('/roles', Roles::class)->name('roles.list');

		// permissions
		Route::get('permissions', Permissions::class)->name('permissions.list');

		// employees
		Route::get('/employees', Employees::class)->name('users.list');

		// companies
		Route::get('/companies', Companies::class)->name('companies.list');

		// languages
		// Route::get('/languages', Languages::class)->name('languages.list');
		
		// bank details
		Route::get('/bankDetails', BankDetails::class)->name('bankDetails.list');		

		// settings
		Route::get('/settings', Settings::class)->name('settings.list');

		// admin panel - tenant management
		Route::get('/admin/tenants', TenantManager::class)->name('admin.tenants.list');

		// admin panel - subscription management
		Route::get('/admin/subscriptions', SubscriptionManager::class)->name('admin.subscriptions.list');

		// admin panel - analytics
		Route::get('/admin/analytics', AnalyticsDashboard::class)->name('admin.analytics');

		// admin panel - admin user management
		Route::get('/admin/users', AdminUserManager::class)->name('admin.users.list');

		// admin panel - registration approvals
		Route::get('/admin/registrations/approvals', RegistrationApprovalPanel::class)->name('admin.registrations.approvals');

	});

	// freight types
	Route::get('/freighttypes', FreightTypes::class)->name('freighttypes.list')->lazy(enabled: false);

	// destinations
	Route::get('/destinations', Destinations::class)->name('destinations.list')->lazy(enabled: false);

	// database entries
	Route::get('/database', DatabaseEntriesList::class)->name('databases.list');
	Route::get('/database/create', DatabaseEntriesCreate::class)->name('databases.create');

	// references
	Route::get('/references', ReferencesList::class)->name('references.list');
	Route::get('/references/create', CreateReferences::class)->name('references.create');
	Route::get('/references/edit/{referenceId}', ReferencesEdit::class)->name('references.edit');
	
	// invoices
	Route::get('/invoices', Invoices::class)->name('invoices.list');

	// employee reference numbers
	Route::get('/employeeReferenceNumbers', EmployeeReferenceNumbers::class)->name('employeeReferenceNumbers.list');

	// reference number rangers
	Route::get('/employeeNumberRanges', EmployeeNumberRanges::class)->name('employeeNumberRanges.list');

	// transport orders
	Route::get('/transport-orders/{id}/download', [ReferenceController::class, 'download_transport_order'])->name('transport-orders.download');
	Route::get('/transport-orders/{id}/mail', [ReferenceController::class, 'sendMail_transport_order'])->name('transport-orders.mail');

	// driver authorizations
	Route::get('/driver-authorization/{id}/download', [ReferenceController::class, 'download_driver_authorization'])->name('driver-authorization.download');
	Route::get('/driver-authorization/{id}/mail', [ReferenceController::class, 'sendMail_driver_authorization'])->name('driver-authorization.mail');

	// booking order
	Route::get('/order/{id}/download', [ReferenceController::class, 'download_order'])->name('order.download');
	Route::get('/order/{id}/mail', [ReferenceController::class, 'sendMail_order'])->name('order.mail');

	// guarantee
	Route::get('/guarantee/{id}/download', [ReferenceController::class, 'download_guarantee'])->name('guarantee.download');
	Route::get('/guarantee/{id}/mail', [ReferenceController::class, 'sendMail_guarantee'])->name('guarantee.mail');

	// invoice
	Route::get('/invoice/{id}/download', [ReferenceController::class, 'download_invoice'])->name('invoice.download');
	Route::get('/invoice/{id}/mail', [ReferenceController::class, 'sendMail_invoice'])->name('invoice.mail');
	Route::get('/invoice/detail/{id}', InvoiceDetail::class)->name('invoice.detail');

	// document
	Route::get('/document/{id}/download', [ReferenceController::class, 'download_document'])->name('document.download');
	Route::get('/document/{id}/mail', [ReferenceController::class, 'sendMail_document'])->name('document.mail');

	// incoming invoices
	Route::get('/incinvoice/create', CreateIncInvoice::class)->name('incinvoices.create');
	Route::get('/incinvoice', IncInvoice::class)->name('incinvoices.list');
	Route::get('/incinvoice/detail/{id}', IncInvoiceDetail::class)->name('incinvoices.detail');
	Route::get('/incinvoice/edit/{id}', IncInvoiceEdit::class)->name('incinvoices.edit');
	Route::get('/incinvoice/{id}/download', [ReferenceController::class, 'download_incinvoice'])->name('incinvoice.download');
	Route::get('incinvoice/document/download/{invoice}', [ReferenceController::class, 'downloadIncivoiceDocument'])->name('incinvoice.document.download');


});

Route::view('/error', 'error')->name('error');

require __DIR__.'/auth.php';
