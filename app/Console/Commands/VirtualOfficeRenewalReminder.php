<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VirtualOfficeRenewalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'virtual-office:renewal-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim WhatsApp reminder renewal (H-30, H-7, & Hari H Expired) ke client Virtual Office.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService): int
    {
        $this->info('=== Virtual Office Renewal Reminder (H-30, H-7 & Hari H Expired) ===');
        $this->info('Tanggal: ' . now()->format('d M Y H:i:s'));

        $sentH30     = $this->processH30Reminder($whatsAppService);
        $this->newLine();
        $sentH7      = $this->processH7Reminder($whatsAppService);
        $this->newLine();
        $sentExpired = $this->processExpiredReminder($whatsAppService);

        $this->newLine();
        $this->info('=== Selesai ===');
        $this->table(
            ['Reminder Type', 'Terkirim', 'Gagal', 'Di-skip'],
            [
                ['H-30 Reminder',   $sentH30['sent'],     $sentH30['failed'],     $sentH30['skipped']],
                ['H-7 Reminder',    $sentH7['sent'],      $sentH7['failed'],      $sentH7['skipped']],
                ['Hari H Expired',  $sentExpired['sent'], $sentExpired['failed'], $sentExpired['skipped']],
            ]
        );

        return self::SUCCESS;
    }

    /**
     * Process H-30 Renewal Reminders
     */
    private function processH30Reminder(WhatsAppService $whatsAppService): array
    {
        $targetDate = Carbon::today()->addDays(30);
        $this->info("--- [H-30] Mencari VO dengan expired_at pada: {$targetDate->format('d M Y')} ---");

        $virtualOffices = Order::with(['user', 'roomBenefits'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('service_name', 'like', '%Virtual Office%')
                  ->orWhereJsonContains('form_data->service', 'virtual-office');
            })
            ->whereNull('renewal_reminder_h30_sent_at')
            ->get();

        $sent = 0; $failed = 0; $skipped = 0;

        foreach ($virtualOffices as $vo) {
            $benefit = $vo->roomBenefits->first();
            if (!$benefit || !$benefit->expired_at) {
                $skipped++;
                continue;
            }

            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();
            if (!$expiredAt->equalTo($targetDate)) {
                $skipped++;
                continue;
            }

            $this->info("  [MATCH H-30] VO #{$vo->id} ({$vo->order_number}) - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyVirtualOfficeRenewalReminder($vo);

                if ($log && $log->status === 'SUCCESS') {
                    $vo->update(['renewal_reminder_h30_sent_at' => now()]);
                    $this->info("  [SENT H-30] WhatsApp H-30 terkirim untuk VO #{$vo->id}");
                    $sent++;
                } else {
                    $this->error("  [FAILED H-30] WhatsApp H-30 gagal untuk VO #{$vo->id}");
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error('VirtualOfficeRenewalReminder H30 - Exception', [
                    'order_id' => $vo->id,
                    'error'    => $e->getMessage(),
                ]);
                $this->error("  [ERROR H-30] VO #{$vo->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed, 'skipped' => $skipped];
    }

    /**
     * Process H-7 Renewal Reminders
     */
    private function processH7Reminder(WhatsAppService $whatsAppService): array
    {
        $targetDate = Carbon::today()->addDays(7);
        $this->info("--- [H-7] Mencari VO dengan expired_at pada: {$targetDate->format('d M Y')} ---");

        $virtualOffices = Order::with(['user', 'roomBenefits'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('service_name', 'like', '%Virtual Office%')
                  ->orWhereJsonContains('form_data->service', 'virtual-office');
            })
            ->whereNull('renewal_reminder_h7_sent_at')
            ->get();

        $sent = 0; $failed = 0; $skipped = 0;

        foreach ($virtualOffices as $vo) {
            $benefit = $vo->roomBenefits->first();
            if (!$benefit || !$benefit->expired_at) {
                $skipped++;
                continue;
            }

            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();
            if (!$expiredAt->equalTo($targetDate)) {
                $skipped++;
                continue;
            }

            $this->info("  [MATCH H-7] VO #{$vo->id} ({$vo->order_number}) - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyVirtualOfficeRenewalReminderH7($vo);

                if ($log && $log->status === 'SUCCESS') {
                    $vo->update(['renewal_reminder_h7_sent_at' => now()]);
                    $this->info("  [SENT H-7] WhatsApp H-7 terkirim untuk VO #{$vo->id}");
                    $sent++;
                } else {
                    $this->error("  [FAILED H-7] WhatsApp H-7 gagal untuk VO #{$vo->id}");
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error('VirtualOfficeRenewalReminder H7 - Exception', [
                    'order_id' => $vo->id,
                    'error'    => $e->getMessage(),
                ]);
                $this->error("  [ERROR H-7] VO #{$vo->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed, 'skipped' => $skipped];
    }

    /**
     * Process Hari H Expired Reminders
     */
    private function processExpiredReminder(WhatsAppService $whatsAppService): array
    {
        $targetDate = Carbon::today();
        $this->info("--- [Hari H Expired] Mencari VO dengan expired_at pada hari ini: {$targetDate->format('d M Y')} ---");

        $virtualOffices = Order::with(['user', 'roomBenefits'])
            ->where('status', 'approved')
            ->where(function ($q) {
                $q->where('service_name', 'like', '%Virtual Office%')
                  ->orWhereJsonContains('form_data->service', 'virtual-office');
            })
            ->whereNull('renewal_reminder_expired_sent_at')
            ->get();

        $sent = 0; $failed = 0; $skipped = 0;

        foreach ($virtualOffices as $vo) {
            $benefit = $vo->roomBenefits->first();
            if (!$benefit || !$benefit->expired_at) {
                $skipped++;
                continue;
            }

            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();
            if (!$expiredAt->equalTo($targetDate)) {
                $skipped++;
                continue;
            }

            $this->info("  [MATCH Expired] VO #{$vo->id} ({$vo->order_number}) - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyVirtualOfficeExpired($vo);

                if ($log && $log->status === 'SUCCESS') {
                    $vo->update(['renewal_reminder_expired_sent_at' => now()]);
                    $this->info("  [SENT Expired] WhatsApp Hari H Expired terkirim untuk VO #{$vo->id}");
                    $sent++;
                } else {
                    $this->error("  [FAILED Expired] WhatsApp Hari H Expired gagal untuk VO #{$vo->id}");
                    $failed++;
                }
            } catch (\Exception $e) {
                Log::error('VirtualOfficeRenewalReminder Expired - Exception', [
                    'order_id' => $vo->id,
                    'error'    => $e->getMessage(),
                ]);
                $this->error("  [ERROR Expired] VO #{$vo->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        return ['sent' => $sent, 'failed' => $failed, 'skipped' => $skipped];
    }
}
