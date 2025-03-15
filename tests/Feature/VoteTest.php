<?php

namespace Tests\Feature;

use App\Events\VoteUpdated;
use App\Models\Option;
use App\Models\Poll;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;


class VoteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_vote_successfully()
    {
        Event::fake();

        $poll = Poll::factory()->create();
        $option = Option::factory()->create(['poll_id' => $poll->id]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson("/polls/{$poll->id}/vote", [
            'option_id' => $option->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Vote submitted successfully']);

        $this->assertDatabaseHas('votes', [
            'poll_id' => $poll->id,
            'option_id' => $option->id,
            'user_id' => $user->id,
        ]);

        Event::assertDispatched(VoteUpdated::class);
    }

    /** @test */
    public function a_user_cannot_vote_more_than_once()
    {
        Event::fake();

        $poll = Poll::factory()->create();
        $option = Option::factory()->create(['poll_id' => $poll->id]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $firstResponse = $this->postJson("/polls/{$poll->id}/vote", [
            'option_id' => $option->id,
        ]);

        if ($firstResponse->status() !== 200) {
            dd($firstResponse->json());
        }

        $secondResponse = $this->postJson("/polls/{$poll->id}/vote", [
            'option_id' => $option->id,
        ]);

        if ($secondResponse->status() !== 400) {
            dd($secondResponse->json());
        }

        $secondResponse->assertStatus(400)
            ->assertJson(['message' => 'You have already voted']);

        $this->assertCount(1, Vote::all());
    }

    /** @test */
    public function a_guest_cannot_vote_more_than_once_based_on_ip_address()
    {
        Event::fake();

        $poll = Poll::factory()->create();
        $option = Option::factory()->create(['poll_id' => $poll->id]);

        $ipAddress = '127.0.0.1';

        $this->withServerVariables(['REMOTE_ADDR' => $ipAddress])
            ->postJson("/polls/{$poll->id}/vote", [
                'option_id' => $option->id,
            ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => $ipAddress])
            ->postJson("/polls/{$poll->id}/vote", [
                'option_id' => $option->id,
            ]);

        $response->assertStatus(400)
            ->assertJson(['message' => 'You have already voted']);

        $this->assertCount(1, Vote::all());
    }

    /** @test */
    public function a_user_cannot_vote_for_an_invalid_option()
    {
        Event::fake();

        $poll = Poll::factory()->create();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson("/api/polls/{$poll->id}/vote", [
            'option_id' => 999,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['option_id']);
    }
}
