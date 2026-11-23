<?php

namespace Tests\Feature;

use App\Models\Laureate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_can_open_login_and_registration(): void
    {
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/laureates/create')->assertRedirect('/login');
        $this->get('/users')->assertRedirect('/login');
    }

    public function test_registration_creates_a_regular_user_and_claims_legacy_laureates(): void
    {
        $laureate = Laureate::create([
            'name' => 'Старый лауреат',
            'birth_date' => '1950-01-01',
            'citizenship' => 'Франция',
            'award_year' => 2000,
            'works' => ['Книга'],
        ]);

        $this->post('/register', [
            'name' => 'Анна',
            'username' => 'anna_1',
            'email' => 'anna@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/users/anna_1');

        $user = User::where('username', 'anna_1')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->is_admin);
        $this->assertSame($user->id, $laureate->fresh()->user_id);
    }


    public function test_first_two_users_share_old_records_including_deleted(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            $laureate = Laureate::create([
                'name' => 'Лауреат '.$i,
                'birth_date' => '1950-01-01',
                'citizenship' => 'Франция',
                'award_year' => 2000 + $i,
                'works' => ['Книга'],
            ]);

            if ($i > 5) {
                DB::table('laureates')->where('id', $laureate->id)->update(['deleted_at' => now()]);
            }
        }

        $this->post('/register', [
            'name' => 'Анна',
            'username' => 'anna',
            'email' => 'anna@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/users/anna');

        $this->post('/logout')->assertRedirect('/login');

        $this->post('/register', [
            'name' => 'Пётр',
            'username' => 'petr',
            'email' => 'petr@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect('/users/petr');

        $this->assertSame(5, User::where('username', 'anna')->firstOrFail()->laureates()->withTrashed()->count());
        $this->assertSame(4, User::where('username', 'petr')->firstOrFail()->laureates()->withTrashed()->count());
        $this->assertSame(4, Laureate::onlyTrashed()->count());
        $this->assertSame(3, User::where('username', 'anna')->firstOrFail()->laureates()->count());
        $this->assertSame(2, User::where('username', 'petr')->firstOrFail()->laureates()->count());
        $this->assertSame(0, Laureate::withTrashed()->whereNull('user_id')->count());
    }

    public function test_registration_validates_unique_username(): void
    {
        User::factory()->create(['username' => 'taken']);

        $this->post('/register', [
            'name' => 'Второй',
            'username' => 'taken',
            'email' => 'new@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertSessionHasErrors('username');
    }

    public function test_user_can_login_and_logout(): void
    {
        $user = User::factory()->create(['email' => 'anna@example.com', 'password' => 'password123']);

        $this->post('/login', ['email' => 'anna@example.com', 'password' => 'password123'])
            ->assertRedirect('/laureates');

        $this->assertAuthenticatedAs($user);
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }
}
