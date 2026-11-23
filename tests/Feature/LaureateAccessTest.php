<?php

namespace Tests\Feature;

use App\Models\Laureate;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaureateAccessTest extends TestCase
{
    use RefreshDatabase;

    private function laureate(User $user): Laureate
    {
        return $user->laureates()->create([
            'name' => 'Тестовый лауреат',
            'birth_date' => '1950-01-01',
            'citizenship' => 'Франция',
            'award_year' => 2000,
            'works' => ['Произведение'],
        ]);
    }

    private function data(): array
    {
        return [
            'name' => 'Новое имя',
            'birth_date' => '1951-01-01',
            'citizenship' => 'Германия',
            'award_year' => 2001,
            'works_text' => "Первая книга\nВторая книга",
        ];
    }

    public function test_authenticated_user_can_create_own_laureate(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/laureates', $this->data())
            ->assertRedirect('/users/'.$user->username);

        $laureate = Laureate::firstOrFail();
        $this->assertSame($user->id, $laureate->user_id);
        $this->assertSame(['Первая книга', 'Вторая книга'], $laureate->works);
        $this->get('/laureates/'.$laureate->id)->assertOk();
    }


    public function test_image_upload_is_resized_and_saved(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $data = $this->data();
        $data['image'] = UploadedFile::fake()->image('portrait.png', 1600, 800);

        $this->actingAs($user)->post('/laureates', $data)->assertRedirect();

        $path = Laureate::firstOrFail()->image_path;
        Storage::disk('public')->assertExists($path);
        [$width, $height] = getimagesize(Storage::disk('public')->path($path));
        $this->assertSame(1200, $width);
        $this->assertSame(600, $height);
    }

    public function test_owner_can_edit_update_and_soft_delete(): void
    {
        $user = User::factory()->create();
        $laureate = $this->laureate($user);

        $this->actingAs($user)->get('/laureates/'.$laureate->id.'/edit')->assertOk();
        $this->put('/laureates/'.$laureate->id, $this->data())->assertRedirect();
        $this->assertSame('Новое имя', $laureate->fresh()->name);
        $this->delete('/laureates/'.$laureate->id)->assertRedirect();
        $this->assertSoftDeleted('laureates', ['id' => $laureate->id]);
        $this->get('/users/'.$user->username)->assertSee('Удалён');
    }

    public function test_user_cannot_edit_or_delete_another_users_laureate(): void
    {
        $owner = User::factory()->create();
        $another = User::factory()->create();
        $laureate = $this->laureate($owner);

        $this->actingAs($another)->get('/laureates/'.$laureate->id.'/edit')->assertForbidden();
        $this->put('/laureates/'.$laureate->id, $this->data())->assertForbidden();
        $this->delete('/laureates/'.$laureate->id)->assertForbidden();
        $this->assertDatabaseHas('laureates', ['id' => $laureate->id, 'name' => 'Тестовый лауреат', 'deleted_at' => null]);
        $this->get('/users/'.$owner->username)
            ->assertOk()->assertDontSee(route('laureates.edit', $laureate));
    }

    public function test_model_events_block_editing_without_permission(): void
    {
        $owner = User::factory()->create();
        $another = User::factory()->create();
        $laureate = $this->laureate($owner);
        $this->actingAs($another);

        $this->expectException(AuthorizationException::class);
        $laureate->update(['name' => 'Чужое изменение']);
    }

    public function test_admin_can_manage_restore_and_force_delete(): void
    {
        $owner = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);
        $laureate = $this->laureate($owner);

        $this->actingAs($admin)->put('/laureates/'.$laureate->id, $this->data())->assertRedirect();
        $this->delete('/laureates/'.$laureate->id)->assertRedirect();
        $this->assertSoftDeleted('laureates', ['id' => $laureate->id]);
        $this->post('/laureates/'.$laureate->id.'/restore')->assertRedirect('/users/'.$owner->username);
        $this->assertNotSoftDeleted('laureates', ['id' => $laureate->id]);
        $this->delete('/laureates/'.$laureate->id.'/force')->assertRedirect('/users/'.$owner->username);
        $this->assertDatabaseMissing('laureates', ['id' => $laureate->id]);
    }

    public function test_only_admin_can_restore_or_force_delete(): void
    {
        $owner = User::factory()->create();
        $laureate = $this->laureate($owner);
        $this->actingAs($owner)->delete('/laureates/'.$laureate->id)->assertRedirect();

        $this->post('/laureates/'.$laureate->id.'/restore')->assertForbidden();
        $this->delete('/laureates/'.$laureate->id.'/force')->assertForbidden();
        $this->assertSoftDeleted('laureates', ['id' => $laureate->id]);
    }

    public function test_user_list_and_username_links_show_deleted_and_active_records(): void
    {
        $owner = User::factory()->create(['username' => 'owner_1']);
        $another = User::factory()->create(['username' => 'owner_2']);
        $this->laureate($owner);
        $deleted = $this->laureate($another);
        $this->actingAs($another);
        $deleted->delete();

        $this->get('/users')->assertOk()->assertSee('/users/owner_1')->assertSee('/users/owner_2');
        $this->get('/users/owner_1')->assertOk()->assertSee('Тестовый лауреат');
        $this->get('/users/owner_2')->assertOk()->assertSee('Удалён');
        $this->get('/users/'.$owner->id)->assertNotFound();
    }
}
