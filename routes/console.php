<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ── Virtual Office Renewal Reminder (H-30, H-7, & Expired) ────────────────────
Schedule::command('virtual-office:renewal-reminder')->dailyAt('09:00');

// ── Meeting Room Renewal Reminder (H-30, H-7, & Expired) ──────────────────────
Schedule::command('meeting-room:renewal-reminder')->dailyAt('09:00');

// ── Studio Podcast Renewal Reminder H-30 ──────────────────────────────────────
Schedule::command('podcast-room:renewal-reminder')->dailyAt('09:00');
