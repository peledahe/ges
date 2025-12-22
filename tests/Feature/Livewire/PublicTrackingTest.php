<?php

namespace Tests\Feature\Livewire;

use App\Livewire\PublicTracking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PublicTrackingTest extends TestCase
{
    public function test_renders_successfully()
    {
        Livewire::test(PublicTracking::class)
            ->assertStatus(200);
    }
}
