<?php

use \App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('should be able to create a new question bigger than 255 characters', function () {

    $user = User::factory()->create();
    actingAs($user);

    $request = post(route('question.store'), [
        'question' => str_repeat('*', 260) . '?',
    ]);

    $request->assertRedirect(route('dashboard'));

    assertDatabaseCount('questions', 1);
    assertDatabaseHas('questions', ['question' => str_repeat('*', 260) . '?']);
});

it('should check if ends with question mark ?', function () {
    $user = User::factory()-> create();
    actingAs($user);

    $request = post(route(name: 'question.store'), [
        'question' => str_repeat(string:'*', times:10),
    ]);

    $request->assertSessionHasErrors([
        'question' => 'Are you sure that is a question? It is missing the question mark in the end.'
    ]);
    assertDatabaseCount(table:'questions', count:0);
});

it('should have at least 10 characters', function () {
    $user = User::factory()-> create();
    actingAs($user);

    $request = post(route(name: 'question.store'), [
        'question' => str_repeat(string:'*', times:8) . '?',
    ]);

    $request->assertSessionHasErrors(['question' => __('validation.min.string', ['min' => 10, 'attribute' => 'question'])]);
    assertDatabaseCount(table:'questions', count:0);
});