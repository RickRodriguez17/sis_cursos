<?php

namespace Tests\Feature;

use App\Livewire\Admin\CourseEditor;
use App\Models\Course;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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
        $lesson = $course->lessons()->create(['title' => 'Privada', 'is_preview' => false]);
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

    public function test_fake_payment_qr_returns_raw_svg(): void
    {
        $user = User::factory()->create();
        $order = Order::create(['user_id' => $user->id, 'total' => 50, 'external_reference' => 'REF']);
        $order->payment()->create([
            'gateway' => 'fake',
            'external_id' => 'REF',
            'amount' => 50,
            'payload' => ['qr_payload' => 'SIS-CURSOS|orden=1|monto=50|ref=REF'],
        ]);

        $response = $this->actingAs($user)->get(route('orders.qr', $order));
        $content = ltrim($response->getContent());

        $response->assertOk()->assertHeader('Content-Type', 'image/svg+xml');
        $this->assertTrue(str_starts_with($content, '<'));
        $this->assertStringContainsString('<svg', $content);
        $this->assertStringNotContainsString('data:image', $content);
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

    public function test_admin_can_access_orders_and_enrollments_pages(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.orders'))
            ->assertOk()
            ->assertViewIs('admin.orders');

        $this->actingAs($admin)
            ->get(route('admin.enrollments'))
            ->assertOk()
            ->assertViewIs('admin.enrollments');
    }

    public function test_non_admin_cannot_access_orders_or_enrollments_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.orders'))
            ->assertForbidden();

        $this->actingAs($user)
            ->get(route('admin.enrollments'))
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
            ->set('videoFile', UploadedFile::fake()->create('primera.mp4', 10, 'video/mp4'))
            ->call('saveLesson');

        $this->assertDatabaseHas('courses', ['slug' => 'curso-nuevo']);
        $this->assertDatabaseHas('lessons', ['title' => 'Primera lección']);
    }

    public function test_lesson_creation_requires_a_video_file(): void
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
            ->call('saveLesson')
            ->assertHasErrors(['videoFile' => 'required']);
    }

    public function test_video_stream_supports_byte_ranges(): void
    {
        Storage::disk('local')->put('course-videos/range.mp4', '0123456789');
        $course = Course::create([
            'title' => 'Curso',
            'slug' => 'curso-rango',
            'short_description' => 'Corto',
            'description' => 'Largo',
            'price' => 50,
            'is_published' => true,
        ]);
        $lesson = $course->lessons()->create([
            'title' => 'Video',
            'video_path' => 'course-videos/range.mp4',
            'is_preview' => true,
        ]);

        $response = $this->get(
            route('lessons.stream', [$course, $lesson]),
            ['Range' => 'bytes=2-5'],
        );

        $response->assertStatus(206)
            ->assertHeader('Accept-Ranges', 'bytes')
            ->assertHeader('Content-Range', 'bytes 2-5/10')
            ->assertHeader('Content-Length', '4');
        $this->assertSame('2345', $response->streamedContent());
    }

    public function test_lesson_without_video_returns_not_found(): void
    {
        $course = Course::create([
            'title' => 'Curso pendiente',
            'slug' => 'curso-pendiente',
            'short_description' => 'Corto',
            'description' => 'Largo',
            'price' => 50,
            'is_published' => true,
        ]);
        $lesson = $course->lessons()->create([
            'title' => 'Pendiente',
            'is_preview' => true,
        ]);

        $this->get(route('lessons.video', [$course, $lesson]))->assertNotFound();
        $this->get(route('lessons.stream', [$course, $lesson]))->assertNotFound();
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
            'video_path' => 'course-videos/private.mp4',
            'is_preview' => false,
        ]);

        $this->actingAs($user)
            ->get(route('lessons.video', [$course, $lesson]))
            ->assertForbidden();
    }
}
