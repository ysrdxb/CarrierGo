<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations for tenant-specific tables ONLY.
     * This migration should be run on individual tenant databases, NOT the central database.
     */
    public function up(): void
    {
        // Tenant-Specific Users Table
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('firstname');
                $table->string('lastname');
                $table->string('email')->unique();
                $table->string('phone')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->integer('otp')->default(0);
                $table->string('otp_expiry')->nullable();
                $table->text('image')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Database Entries
        if (!Schema::hasTable('database_entries')) {
            Schema::create('database_entries', function (Blueprint $table) {
                $table->id();
                $table->string('type');
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Companies
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->string('address')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
                $table->string('country')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Settings
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Bank Details
        if (!Schema::hasTable('bank_details')) {
            Schema::create('bank_details', function (Blueprint $table) {
                $table->id();
                $table->string('account_holder')->nullable();
                $table->string('bank_name')->nullable();
                $table->string('account_number')->nullable();
                $table->string('routing_number')->nullable();
                $table->string('swift_code')->nullable();
                $table->string('iban')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Reference Numbers
        if (!Schema::hasTable('reference_numbers')) {
            Schema::create('reference_numbers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('number_range');
                $table->integer('last_used_reference')->default(0);
                $table->integer('year')->default(date('Y'));
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // References
        if (!Schema::hasTable('references')) {
            Schema::create('references', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Reference Additional Fees
        if (!Schema::hasTable('reference_additional_fees')) {
            Schema::create('reference_additional_fees', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reference_id')->constrained('references')->onDelete('cascade');
                $table->string('name');
                $table->decimal('amount', 10, 2);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Destinations
        if (!Schema::hasTable('destinations')) {
            Schema::create('destinations', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->decimal('cost', 10, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Freight Types
        if (!Schema::hasTable('freight_types')) {
            Schema::create('freight_types', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Freights
        if (!Schema::hasTable('freights')) {
            Schema::create('freights', function (Blueprint $table) {
                $table->id();
                $table->foreignId('freight_type_id')->constrained('freight_types')->onDelete('cascade');
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('weight', 10, 2)->nullable();
                $table->decimal('volume', 10, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Transport Orders
        if (!Schema::hasTable('transport_orders')) {
            Schema::create('transport_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Orders
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->decimal('total_amount', 12, 2)->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Languages
        if (!Schema::hasTable('languages')) {
            Schema::create('languages', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->timestamps();
            });
        }

        // Documents
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('file_path');
                $table->string('type')->nullable();
                $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Deliveries
        if (!Schema::hasTable('deliveries')) {
            Schema::create('deliveries', function (Blueprint $table) {
                $table->id();
                $table->string('delivery_number')->unique();
                $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->datetime('delivery_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Invoices
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->decimal('amount', 12, 2);
                $table->string('status')->default('draft');
                $table->date('issued_date')->nullable();
                $table->date('due_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Invoice Items
        if (!Schema::hasTable('invoice_items')) {
            Schema::create('invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total', 12, 2);
                $table->timestamps();
            });
        }

        // Driver Authorizations
        if (!Schema::hasTable('driver_authorizations')) {
            Schema::create('driver_authorizations', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('license_number')->unique();
                $table->string('license_class')->nullable();
                $table->date('expiry_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Guarantees
        if (!Schema::hasTable('guarantees')) {
            Schema::create('guarantees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->date('validity_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Unloading Addresses
        if (!Schema::hasTable('unloading_addresses')) {
            Schema::create('unloading_addresses', function (Blueprint $table) {
                $table->id();
                $table->string('address');
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
                $table->string('country')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // User Reference Numbers
        if (!Schema::hasTable('user_reference_numbers')) {
            Schema::create('user_reference_numbers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('reference_number')->unique();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Incoming Invoices
        if (!Schema::hasTable('incoming_invoices')) {
            Schema::create('incoming_invoices', function (Blueprint $table) {
                $table->id();
                $table->string('invoice_number')->unique();
                $table->string('vendor_name')->nullable();
                $table->decimal('amount', 12, 2)->nullable();
                $table->date('invoice_date')->nullable();
                $table->string('status')->default('received');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Incoming Invoice References
        if (!Schema::hasTable('inc_invoice_references')) {
            Schema::create('inc_invoice_references', function (Blueprint $table) {
                $table->id();
                $table->foreignId('incoming_invoice_id')->constrained('incoming_invoices')->onDelete('cascade');
                $table->foreignId('reference_id')->constrained('references')->onDelete('cascade');
                $table->timestamps();
            });
        }

        // Incoming Invoice Items
        if (!Schema::hasTable('inc_invoice_items')) {
            Schema::create('inc_invoice_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('incoming_invoice_id')->constrained('incoming_invoices')->onDelete('cascade');
                $table->string('description');
                $table->integer('quantity')->default(1);
                $table->decimal('unit_price', 10, 2);
                $table->decimal('total', 12, 2);
                $table->timestamps();
            });
        }

        // References Edit History
        if (!Schema::hasTable('references_edit_histories')) {
            Schema::create('references_edit_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('reference_id')->constrained('references')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->text('changes')->nullable();
                $table->timestamps();
            });
        }

        // Shipments
        if (!Schema::hasTable('shipments')) {
            Schema::create('shipments', function (Blueprint $table) {
                $table->id();
                $table->string('tracking_number')->unique();
                $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade');
                $table->string('status')->default('pending');
                $table->string('origin')->nullable();
                $table->string('destination')->nullable();
                $table->datetime('pickup_date')->nullable();
                $table->datetime('delivery_date')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // Permissions and Roles (Tenant-Specific)
        if (!Schema::hasTable('permissions')) {
            Schema::create('permissions', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('guard_name')->default('web');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('model_has_permissions')) {
            Schema::create('model_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->morphs('model');
                $table->primary(['permission_id', 'model_id', 'model_type'], 'model_has_permissions_permission_model_type_primary');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('model_has_roles')) {
            Schema::create('model_has_roles', function (Blueprint $table) {
                $table->unsignedBigInteger('role_id');
                $table->morphs('model');
                $table->primary(['role_id', 'model_id', 'model_type'], 'model_has_roles_role_model_type_primary');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('role_has_permissions')) {
            Schema::create('role_has_permissions', function (Blueprint $table) {
                $table->unsignedBigInteger('permission_id');
                $table->unsignedBigInteger('role_id');
                $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_has_permissions');
        Schema::dropIfExists('model_has_roles');
        Schema::dropIfExists('model_has_permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('references_edit_histories');
        Schema::dropIfExists('inc_invoice_items');
        Schema::dropIfExists('inc_invoice_references');
        Schema::dropIfExists('incoming_invoices');
        Schema::dropIfExists('user_reference_numbers');
        Schema::dropIfExists('unloading_addresses');
        Schema::dropIfExists('guarantees');
        Schema::dropIfExists('driver_authorizations');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('deliveries');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('languages');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('transport_orders');
        Schema::dropIfExists('freights');
        Schema::dropIfExists('freight_types');
        Schema::dropIfExists('destinations');
        Schema::dropIfExists('reference_additional_fees');
        Schema::dropIfExists('references');
        Schema::dropIfExists('reference_numbers');
        Schema::dropIfExists('bank_details');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('companies');
        Schema::dropIfExists('database_entries');
        Schema::dropIfExists('users');
    }
};
