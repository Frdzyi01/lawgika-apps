<?php

namespace App\Console\Commands;

use App\Models\RoomBenefit;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MeetingRoomRenewalReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting-room:renewal-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim WhatsApp reminder renewal (H-30, H-7, & Hari H Expired) ke client Paket Meeting Room.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsAppService): int
    {
        $this->info('=== Meeting Room Package Renewal Reminder (H-30, H-7, & Expired) ===');
        $this->info('Tanggal: ' . now()->format('d M Y H:i:s'));

        $sentH30 = 0; $failedH30 = 0;
        $sentH7  = 0; $failedH7  = 0;
        $sentExp = 0; $failedExp = 0;

        // ── 1. Process H-30 Reminders ─────────────────────────────────────────
        $targetH30 = Carbon::today()->addDays(30);
        $this->info("\n--- Checking H-30 Reminders (Target Expired: {$targetH30->format('d M Y')}) ---");

        $benefitsH30 = RoomBenefit::with(['user', 'order'])
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])
            ->whereNull('renewal_reminder_h30_sent_at')
            ->whereNotNull('expired_at')
            ->get();

        foreach ($benefitsH30 as $benefit) {
            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();

            if (!$expiredAt->equalTo($targetH30)) {
                continue;
            }

            $clientName = $benefit->user->company_name ?? ($benefit->user->name ?? 'Client');
            $this->info("  [MATCH H-30] Benefit #{$benefit->id} ({$benefit->paket}) - Client: {$clientName} - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyMeetingRoomRenewalReminderH30($benefit);

                if ($log && $log->status === 'SUCCESS') {
                    $benefit->update(['renewal_reminder_h30_sent_at' => now()]);
                    $this->info("  [SENT H-30] WhatsApp H-30 terkirim untuk Benefit #{$benefit->id}");
                    $sentH30++;
                } else {
                    $this->error("  [FAILED H-30] WhatsApp H-30 gagal untuk Benefit #{$benefit->id}");
                    $failedH30++;
                }
            } catch (\Exception $e) {
                Log::error('MeetingRoomRenewalReminder H30 - Exception', [
                    'benefit_id' => $benefit->id,
                    'error'      => $e->getMessage(),
                ]);
                $this->error("  [ERROR H-30] Benefit #{$benefit->id}: {$e->getMessage()}");
                $failedH30++;
            }
        }

        // ── 2. Process H-7 Reminders ──────────────────────────────────────────
        $targetH7 = Carbon::today()->addDays(7);
        $this->info("\n--- Checking H-7 Reminders (Target Expired: {$targetH7->format('d M Y')}) ---");

        $benefitsH7 = RoomBenefit::with(['user', 'order'])
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])
            ->whereNull('renewal_reminder_h7_sent_at')
            ->whereNotNull('expired_at')
            ->get();

        foreach ($benefitsH7 as $benefit) {
            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();

            if (!$expiredAt->equalTo($targetH7)) {
                continue;
            }

            $clientName = $benefit->user->company_name ?? ($benefit->user->name ?? 'Client');
            $this->info("  [MATCH H-7] Benefit #{$benefit->id} ({$benefit->paket}) - Client: {$clientName} - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyMeetingRoomRenewalReminderH7($benefit);

                if ($log && $log->status === 'SUCCESS') {
                    $benefit->update(['renewal_reminder_h7_sent_at' => now()]);
                    $this->info("  [SENT H-7] WhatsApp H-7 terkirim untuk Benefit #{$benefit->id}");
                    $sentH7++;
                } else {
                    $this->error("  [FAILED H-7] WhatsApp H-7 gagal untuk Benefit #{$benefit->id}");
                    $failedH7++;
                }
            } catch (\Exception $e) {
                Log::error('MeetingRoomRenewalReminder H7 - Exception', [
                    'benefit_id' => $benefit->id,
                    'error'      => $e->getMessage(),
                ]);
                $this->error("  [ERROR H-7] Benefit #{$benefit->id}: {$e->getMessage()}");
                $failedH7++;
            }
        }

        // ── 3. Process Hari H Expired Reminders ───────────────────────────────
        $targetExpired = Carbon::today();
        $this->info("\n--- Checking Hari H Expired Reminders (Target Expired: <= {$targetExpired->format('d M Y')}) ---");

        $benefitsExpired = RoomBenefit::with(['user', 'order'])
            ->where('is_active', true)
            ->whereIn('type', ['meeting', 'shared'])
            ->whereNull('renewal_reminder_expired_sent_at')
            ->whereNotNull('expired_at')
            ->get();

        foreach ($benefitsExpired as $benefit) {
            $expiredAt = Carbon::parse($benefit->expired_at)->startOfDay();

            if ($expiredAt->greaterThan($targetExpired)) {
                continue;
            }

            $clientName = $benefit->user->company_name ?? ($benefit->user->name ?? 'Client');
            $this->info("  [MATCH EXPIRED] Benefit #{$benefit->id} ({$benefit->paket}) - Client: {$clientName} - Expired: {$expiredAt->format('d M Y')}");

            try {
                $log = $whatsAppService->notifyMeetingRoomExpired($benefit);

                if ($log && $log->status === 'SUCCESS') {
                    $benefit->update([
                        'is_active'                        => false,
                        'renewal_reminder_expired_sent_at' => now(),
                    ]);
                    $this->info("  [SENT EXPIRED] WhatsApp Expired terkirim & status dinonaktifkan untuk Benefit #{$benefit->id}");
                    $sentExp++;
                } else {
                    $this->error("  [FAILED EXPIRED] WhatsApp Expired gagal untuk Benefit #{$benefit->id}");
                    $failedExp++;
                }
            } catch (\Exception $e) {
                Log::error('MeetingRoomRenewalReminder Expired - Exception', [
                    'benefit_id' => $benefit->id,
                    'error'      => $e->getMessage(),
                ]);
                $this->error("  [ERROR EXPIRED] Benefit #{$benefit->id}: {$e->getMessage()}");
                $failedExp++;
            }
        }

        $this->newLine();
        $this->info('=== Selesai ===');
        $this->table(
            ['Metric', 'H-30', 'H-7', 'Expired', 'Total'],
            [
                ['Terkirim', $sentH30, $sentH7, $sentExp, $sentH30 + $sentH7 + $sentExp],
                ['Gagal', $failedH30, $failedH7, $failedExp, $failedH30 + $failedH7 + $failedExp],
            ]
        );

        return self::SUCCESS;
    }
}
