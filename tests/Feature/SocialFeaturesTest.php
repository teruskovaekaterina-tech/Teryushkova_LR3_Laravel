<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Laureate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SocialFeaturesTest extends TestCase
{
    use RefreshDatabase;

    private function createLaureate(User $user, string $name, int $year = 2000): Laureate
    {
        return $user->laureates()->create([
            'name' => $name,
            'birth_date' => '1950-01-01',
            'citizenship' => 'Франция',
            'award_year' => $year,
            'works' => ['Книга'],
        ]);
    }

    public function test_user_can_comment_on_own_and_another_users_laureates(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $ownLaureate = $this->createLaureate($commenter, 'Свой лауреат');
        $otherLaureate = $this->createLaureate($author, 'Чужой лауреат');

        $this->actingAs($commenter)
            ->post(route('comments.store', $ownLaureate), ['body' => 'Комментарий к своей записи'])
            ->assertRedirect(route('laureates.show', $ownLaureate));

        $this->post(route('comments.store', $otherLaureate), ['body' => 'Комментарий к чужой записи'])
            ->assertRedirect(route('laureates.show', $otherLaureate));

        $this->assertDatabaseHas('comments', [
            'laureate_id' => $ownLaureate->id,
            'user_id' => $commenter->id,
            'body' => 'Комментарий к своей записи',
        ]);

        $this->assertDatabaseHas('comments', [
            'laureate_id' => $otherLaureate->id,
            'user_id' => $commenter->id,
            'body' => 'Комментарий к чужой записи',
        ]);

        $comment = Comment::where('body', 'Комментарий к чужой записи')->firstOrFail();
        $this->assertSame($otherLaureate->id, $comment->laureate->id);
        $this->assertSame($commenter->id, $comment->user->id);
    }

    public function test_guest_cannot_add_comment(): void
    {
        $owner = User::factory()->create();
        $laureate = $this->createLaureate($owner, 'Лауреат');

        $this->post(route('comments.store', $laureate), ['body' => 'Комментарий'])
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_adding_friend_creates_reciprocal_friendship(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();

        $this->actingAs($first)
            ->post(route('users.friend', $second))
            ->assertRedirect(route('users.show', $second));

        $this->assertDatabaseHas('friend_user', [
            'user_id' => $first->id,
            'friend_id' => $second->id,
        ]);

        $this->assertDatabaseHas('friend_user', [
            'user_id' => $second->id,
            'friend_id' => $first->id,
        ]);

        $this->assertTrue($first->fresh()->friends()->whereKey($second->id)->exists());
        $this->assertTrue($second->fresh()->friends()->whereKey($first->id)->exists());
    }

    public function test_user_can_remove_friendship_in_both_directions(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        $first->addFriend($second);

        $this->actingAs($first)
            ->delete(route('users.unfriend', $second))
            ->assertRedirect(route('users.show', $second));

        $this->assertDatabaseMissing('friend_user', [
            'user_id' => $first->id,
            'friend_id' => $second->id,
        ]);

        $this->assertDatabaseMissing('friend_user', [
            'user_id' => $second->id,
            'friend_id' => $first->id,
        ]);
    }

    public function test_user_cannot_add_themselves_as_a_friend(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('users.friend', $user))
            ->assertForbidden();

        $this->assertDatabaseCount('friend_user', 0);
    }

    public function test_feed_shows_friends_laureates_in_newest_first_order(): void
    {
        $viewer = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();
        $viewer->addFriend($friend);

        $older = $this->createLaureate($friend, 'Старая запись');
        $newer = $this->createLaureate($friend, 'Новая запись');
        $this->createLaureate($stranger, 'Запись постороннего');

        DB::table('laureates')->where('id', $older->id)->update([
            'created_at' => now()->subDay(),
        ]);

        DB::table('laureates')->where('id', $newer->id)->update([
            'created_at' => now(),
        ]);

        $this->actingAs($viewer)
            ->get(route('feed.index'))
            ->assertOk()
            ->assertSeeInOrder(['Новая запись', 'Старая запись'])
            ->assertDontSee('Запись постороннего');
    }

    public function test_comments_from_friends_are_highlighted_on_laureate_page(): void
    {
        $viewer = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();
        $owner = User::factory()->create();
        $viewer->addFriend($friend);
        $laureate = $this->createLaureate($owner, 'Лауреат с комментариями');

        $laureate->comments()->create([
            'user_id' => $friend->id,
            'body' => 'Комментарий друга',
        ]);

        $laureate->comments()->create([
            'user_id' => $stranger->id,
            'body' => 'Комментарий другого пользователя',
        ]);

        $this->actingAs($viewer)
            ->get(route('laureates.show', $laureate))
            ->assertOk()
            ->assertSee('comment-from-friend')
            ->assertSee('Комментарий друга')
            ->assertSee('Комментарий другого пользователя');
    }
}
