<?php

namespace Tests\Unit;

use App\Models\Order;
use App\Models\RoomBenefit;
use PHPUnit\Framework\TestCase;

class RoomBenefitEligibilityTest extends TestCase
{
    /** @test */
    public function it_approves_pt_eksklusif()
    {
        $order = new Order();
        $order->form_data = [
            'service' => 'pendirian-pt',
            'package' => 'eksklusif'
        ];

        $this->assertTrue(RoomBenefit::isEligibleForOrder($order));
    }

    /** @test */
    public function it_approves_pt_enterprise()
    {
        $order = new Order();
        $order->form_data = [
            'service' => 'pendirian-pt',
            'package' => 'enterprise'
        ];

        $this->assertTrue(RoomBenefit::isEligibleForOrder($order));
    }

    /** @test */
    public function it_rejects_cv_eksklusif()
    {
        $order = new Order();
        $order->form_data = [
            'service' => 'pendirian-cv',
            'package' => 'eksklusif'
        ];

        $this->assertFalse(RoomBenefit::isEligibleForOrder($order));
    }

    /** @test */
    public function it_rejects_yayasan_enterprise()
    {
        $order = new Order();
        $order->form_data = [
            'service' => 'pendirian-yayasan',
            'package' => 'enterprise'
        ];

        $this->assertFalse(RoomBenefit::isEligibleForOrder($order));
    }

    /** @test */
    public function it_supports_legacy_orders_without_form_data_using_service_name()
    {
        // 1. Valid PT Eksklusif
        $order1 = new Order();
        $order1->service_name = 'Pendirian PT – Paket Eksklusif';
        $this->assertTrue(RoomBenefit::isEligibleForOrder($order1));

        // 2. Invalid CV Eksklusif
        $order2 = new Order();
        $order2->service_name = 'Pendirian CV – Paket Eksklusif';
        $this->assertFalse(RoomBenefit::isEligibleForOrder($order2));
        
        // 3. Invalid PT Perorangan Eksklusif
        $order3 = new Order();
        $order3->service_name = 'Pendirian PT Perorangan – Paket Eksklusif';
        $this->assertFalse(RoomBenefit::isEligibleForOrder($order3));
    }
}
