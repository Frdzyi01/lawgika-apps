<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'invoice_number')) {
                $table->string('invoice_number', 100)->nullable()->unique()->after('order_number');
            }
            if (!Schema::hasColumn('orders', 'transaction_date')) {
                $table->date('transaction_date')->nullable()->index()->after('invoice_number');
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method', 100)->nullable()->after('payment_proof');
            }
            if (!Schema::hasColumn('orders', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('created_by');
            }
            if (!Schema::hasColumn('orders', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('submitted_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('orders', 'rejected_by')) {
                $table->foreignId('rejected_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
            if (!Schema::hasColumn('orders', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_at');
            }
            if (!Schema::hasColumn('orders', 'revision_count')) {
                $table->unsignedInteger('revision_count')->default(0)->after('rejection_reason');
            }
            if (!Schema::hasColumn('orders', 'last_revised_at')) {
                $table->timestamp('last_revised_at')->nullable()->after('revision_count');
            }
            if (!Schema::hasColumn('orders', 'deleted_by')) {
                $table->foreignId('deleted_by')->nullable()->after('last_revised_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('orders', 'delete_reason')) {
                $table->text('delete_reason')->nullable()->after('deleted_by');
            }
            if (!Schema::hasColumn('orders', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('room_benefits', function (Blueprint $table) {
            if (!Schema::hasColumn('room_benefits', 'started_at')) {
                $table->timestamp('started_at')->nullable()->after('is_active');
            }
            if (!Schema::hasColumn('room_benefits', 'approved_by')) {
                $table->foreignId('approved_by')->nullable()->after('expired_at')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('room_benefits', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });

        if (!Schema::hasTable('order_histories')) {
            Schema::create('order_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->string('action', 50)->index();
                $table->string('from_status', 50)->nullable();
                $table->string('to_status', 50)->nullable();
                $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('actor_role', 30)->nullable();
                $table->text('notes')->nullable();
                $table->json('snapshot')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'created_at']);
            });
        }

        if (!Schema::hasTable('order_audit_logs')) {
            Schema::create('order_audit_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('user_role', 30)->nullable();
                $table->string('action', 60)->index();
                $table->text('reason')->nullable();
                $table->json('old_values')->nullable();
                $table->json('new_values')->nullable();
                $table->string('ip_address', 45)->nullable();
                $table->timestamps();

                $table->index(['order_id', 'created_at']);
            });
        }

        if (!Schema::hasTable('order_change_requests')) {
            Schema::create('order_change_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
                $table->string('request_type', 20)->index(); // edit | delete
                $table->string('status', 20)->default('pending')->index(); // pending | approved | rejected
                $table->text('reason');
                $table->json('proposed_data')->nullable();
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->text('review_note')->nullable();
                $table->timestamps();

                $table->index(['order_id', 'status']);
            });
        }

        // Backfill legacy orders safely
        DB::statement("
            UPDATE orders
            SET
                invoice_number = COALESCE(invoice_number, CONCAT('INV-', order_number)),
                transaction_date = COALESCE(transaction_date, DATE(created_at)),
                submitted_at = COALESCE(submitted_at, created_at)
        ");

        DB::statement("
            UPDATE orders
            SET approved_at = COALESCE(approved_at, updated_at, created_at)
            WHERE status IN ('approved', 'processing', 'completed', 'verified')
        ");

        // Backfill legacy room_benefits started_at without altering existing expired_at
        DB::statement("
            UPDATE room_benefits rb
            LEFT JOIN orders o ON o.id = rb.order_id
            SET rb.started_at = COALESCE(
                rb.started_at,
                CASE
                    WHEN o.transaction_date IS NOT NULL THEN TIMESTAMP(o.transaction_date, '00:00:00')
                    ELSE rb.created_at
                END
            ),
            rb.approved_at = COALESCE(rb.approved_at, o.approved_at, rb.created_at)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('order_change_requests');
        Schema::dropIfExists('order_audit_logs');
        Schema::dropIfExists('order_histories');

        Schema::table('room_benefits', function (Blueprint $table) {
            if (Schema::hasColumn('room_benefits', 'approved_by')) {
                $table->dropConstrainedForeignId('approved_by');
            }
            if (Schema::hasColumn('room_benefits', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
            if (Schema::hasColumn('room_benefits', 'started_at')) {
                $table->dropColumn('started_at');
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            foreach (['approved_by', 'rejected_by', 'deleted_by'] as $fk) {
                if (Schema::hasColumn('orders', $fk)) {
                    $table->dropConstrainedForeignId($fk);
                }
            }
            foreach ([
                'invoice_number', 'transaction_date', 'payment_method', 'submitted_at',
                'approved_at', 'rejected_at', 'rejection_reason', 'revision_count',
                'last_revised_at', 'delete_reason', 'deleted_at'
            ] as $col) {
                if (Schema::hasColumn('orders', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

