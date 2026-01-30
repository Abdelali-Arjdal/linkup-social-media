<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'content' => 'This is a test post',
        ]);

        $response->assertRedirect(route('feed'));
        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'This is a test post',
        ]);
    }

    public function test_unauthenticated_user_cannot_create_post()
    {
        $response = $this->post(route('posts.store'), [
            'content' => 'This is a test post',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_post_content_is_sanitized()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'content' => 'Safe <script>alert("XSS")</script> content',
        ]);

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'content' => 'Safe alert("XSS") content',
        ]);
    }

    public function test_user_can_delete_own_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('feed'));
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_user_cannot_delete_others_post()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->delete(route('posts.destroy', $post));

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_user_can_add_comment_to_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $response = $this->actingAs($user)->post(route('comments.store', $post), [
            'content' => 'Great post!',
        ]);

        $response->assertRedirect(route('feed'));
        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Great post!',
        ]);
    }

    public function test_comment_content_is_sanitized()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user)->post(route('comments.store', $post), [
            'content' => 'Safe <script>alert("XSS")</script> comment',
        ]);

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Safe alert("XSS") comment',
        ]);
    }
}
