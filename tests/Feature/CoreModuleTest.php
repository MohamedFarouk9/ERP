<?php

declare(strict_types=1);

test('core module api ping endpoint returns active status', function () {
    $response = $this->getJson('/api/v1/core/ping');

    $response->assertOk()
        ->assertJson([
            'module' => 'core',
            'status' => 'active',
        ]);
});

test('core module web route responds successfully', function () {
    $response = $this->get('/core');

    $response->assertOk()
        ->assertJson([
            'module' => 'core',
            'channel' => 'web',
        ]);
});
