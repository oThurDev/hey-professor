<?php

use \App\Models\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it(description: 'should be able to create a new question bigger than 255 characters', function () {
    $user = User::factory()-> create();
    actingAs($user);

    $request = post(route(name: 'question.store'), [
        'question' => str_repeat(string:'*', times:260) . '?',
    ]);

    $request->assertRedirect(route(name: 'dashboard'));
    assertDatabaseCount(table: 'questions', count: 1);
    assertDatabaseHas(table: 'questions', ['question' => str_repeat('*', 260) . '?'])
});

it('should check if ends with question mark ?', function () {
    
});

it('should have at least 10 characters', function () {
    
});