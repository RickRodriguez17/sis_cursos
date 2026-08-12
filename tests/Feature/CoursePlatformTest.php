<?php

namespace Tests\Feature;

use App\Livewire\Admin\CourseEditor;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
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
        }
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'paid']);
        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_non_admin_cannot_access_admin_crud(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.courses.index'))
            ->assertForbidden();
    }

    public function test_admin_can_create_course_and_lesson(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        Livewire::actingAs($admin)
            ->test(CourseEditor::class)
            ->set('title', 'Curso nuevo')
            ->set('slug', 'curso-nuevo')
            ->set('shortDescription', 'Descripción corta')
            ->set('description', 'Descripción completa')
            ->set('price', '120')
            ->call('saveCourse')
            ->set('lessonTitle', 'Primera lección')
            ->set('videoType', 'youtube')
            ->set('videoUrl', 'https://youtube.com/watch?v=demo')
            ->call('saveLesson');

        $this->assertDatabaseHas('courses', ['slug' => 'curso-nuevo']);
        $this->assertDatabaseHas('lessons', ['title' => 'Primera lección', 'video_type' => 'youtube']);
    }

    public function test_webhook_rejects_invalid_secret(): void
    {
        config(['services.payment_webhook_secret' => 'correcto']);

        $this->postJson('/pagos/webhook/fake', ['external_id' => 'missing', 'status' => 'paid'])
            ->assertUnauthorized();
    }

    public function test_private_video_cannot_be_accessed_without_enrollment(): void
    {
        Storage::disk('local')->put('course-videos/private.mp4', 'video');
        $user = User::factory()->create();
        $course = Course::create([
            'title' => 'Curso privado',
            'slug' => 'curso-privado',
            'short_description' => 'Corto',
            'description' => 'Largo',
            'price' => 50,
            'is_published' => true,
        ]);
        $lesson = $course->lessons()->create([
            'title' => 'Video privado',
            'video_type' => 'file',
            'video_path' => 'course-videos/private.mp4',
            'is_preview' => false,
        ]);

        $this->actingAs($user)
            ->get(route('lessons.video', [$course, $lesson]))
            ->assertForbidden();
    }
}
