<?php

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/cart');
    $response->assertRedirect('/login');
});

test('authenticated users can visit the cart', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get('/cart');
    $response->assertStatus(200);
});