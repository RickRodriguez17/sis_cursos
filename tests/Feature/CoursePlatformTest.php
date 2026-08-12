<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CoursePlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_non_preview_lesson(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['title' => 'Curso', 'slug' => 'curso', 'short_description' => 'Corto', 'description' => 'Largo', 'price' => 50, 'is_published' => true]);
        $lesson = $course->lessons()->create(['title' => 'Privada', 'video_type' => 'youtube', 'video_url' => 'https://youtube.com', 'is_preview' => false]);
        $this->actingAs($user)->get(route('lessons.video', [$course, $lesson]))->assertForbidden();
    }

    public function test_checkout_creates_order_items_and_payment(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['title' => 'Curso', 'slug' => 'curso', 'short_description' => 'Corto', 'description' => 'Largo', 'price' => 50, 'is_published' => true]);
        $this->actingAs($user)->withSession(['cart' => [$course->id]])->post(route('checkout'))->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $user->id, 'total' => 50]);
        $this->assertDatabaseHas('order_items', ['course_id' => $course->id]);
        $this->assertDatabaseHas('payments', ['gateway' => 'fake']);
    }

    public function test_webhook_is_idempotent_and_grants_access(): void
    {
        $user = User::factory()->create();
        $course = Course::create(['title' => 'Curso', 'slug' => 'curso', 'short_description' => 'Corto', 'description' => 'Largo', 'price' => 50, 'is_published' => true]);
        $order = Order::create(['user_id' => $user->id, 'total' => 50, 'external_reference' => 'REF']);
        $order->items()->create(['course_id' => $course->id, 'price' => 50]);
        $order->payment()->create(['gateway' => 'fake', 'external_id' => 'REF', 'amount' => 50]);
        foreach ([1, 2] as $_) {
            $this->post('/pagos/webhook/fake', ['external_id' => 'REF', 'status' => 'paid'])->assertOk();
        }$this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
        $this->assertDatabaseCount('enrollments', 1);
    }
}
