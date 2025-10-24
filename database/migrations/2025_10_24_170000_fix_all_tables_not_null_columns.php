<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Fixes NOT NULL columns without defaults in all tenant-enabled tables
     */
    public function up(): void
    {
        // COMPANIES TABLE
        if (Schema::hasTable('companies')) {
            DB::statement('ALTER TABLE companies MODIFY name VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE companies MODIFY address VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE companies MODIFY zip_code VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE companies MODIFY city VARCHAR(255) NULL DEFAULT ""');
        }

        // DELIVERIES TABLE
        if (Schema::hasTable('deliveries')) {
            DB::statement("ALTER TABLE deliveries MODIFY delivery_date DATE NULL DEFAULT NULL");
            DB::statement("ALTER TABLE deliveries MODIFY delivered_by BIGINT UNSIGNED NULL DEFAULT NULL");
            DB::statement("ALTER TABLE deliveries MODIFY status ENUM('Delivered','Shipped') NULL DEFAULT 'Shipped'");
        }

        // INVOICES TABLE
        if (Schema::hasTable('invoices')) {
            DB::statement('ALTER TABLE invoices MODIFY invoice_number VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE invoices MODIFY amount DECIMAL(10,2) NULL DEFAULT 0.00');
            DB::statement('ALTER TABLE invoices MODIFY language VARCHAR(255) NULL DEFAULT "en"');
            DB::statement("ALTER TABLE invoices MODIFY freight_payer BIGINT UNSIGNED NULL DEFAULT NULL");
            DB::statement('ALTER TABLE invoices MODIFY tax_rate DECIMAL(5,2) NULL DEFAULT 0.00');
        }

        // FREIGHTS TABLE
        if (Schema::hasTable('freights')) {
            DB::statement('ALTER TABLE freights MODIFY type VARCHAR(255) NULL DEFAULT ""');
        }

        // DOCUMENTS TABLE
        if (Schema::hasTable('documents')) {
            DB::statement('ALTER TABLE documents MODIFY document_type VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE documents MODIFY document_path VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE documents MODIFY file_name VARCHAR(255) NULL DEFAULT ""');
        }

        // DESTINATIONS TABLE
        if (Schema::hasTable('destinations')) {
            DB::statement('ALTER TABLE destinations MODIFY name VARCHAR(255) NULL DEFAULT ""');
        }

        // FREIGHT_TYPES TABLE
        if (Schema::hasTable('freight_types')) {
            DB::statement('ALTER TABLE freight_types MODIFY name VARCHAR(255) NULL DEFAULT ""');
        }

        // BANK_DETAILS TABLE
        if (Schema::hasTable('bank_details')) {
            DB::statement('ALTER TABLE bank_details MODIFY company_name VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE bank_details MODIFY bank_name VARCHAR(255) NULL DEFAULT ""');
        }

        // SETTINGS TABLE
        if (Schema::hasTable('settings')) {
            DB::statement('ALTER TABLE settings MODIFY company_name VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE settings MODIFY address VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE settings MODIFY zip_code VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE settings MODIFY city VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE settings MODIFY currency VARCHAR(255) NULL DEFAULT "EUR"');
        }

        // REFERENCE_NUMBERS TABLE
        if (Schema::hasTable('reference_numbers')) {
            DB::statement('ALTER TABLE reference_numbers MODIFY number_range VARCHAR(255) NULL DEFAULT "1"');
        }

        // REGISTRATIONS TABLE
        if (Schema::hasTable('registrations')) {
            DB::statement('ALTER TABLE registrations MODIFY company_name VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE registrations MODIFY domain VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE registrations MODIFY firstname VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE registrations MODIFY lastname VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE registrations MODIFY email VARCHAR(255) NULL DEFAULT ""');
            DB::statement('ALTER TABLE registrations MODIFY password_hash VARCHAR(255) NULL DEFAULT ""');
        }

        // ONBOARDING_EMAILS TABLE
        if (Schema::hasTable('onboarding_emails')) {
            DB::statement("ALTER TABLE onboarding_emails MODIFY email_type ENUM('welcome','verify_email','approval_required','approved','rejected','credentials','trial_ending','trial_expired') NULL DEFAULT 'welcome'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse all changes - revert to NOT NULL without defaults
        // This is intentionally left minimal as reverting is not recommended in production
    }
};
