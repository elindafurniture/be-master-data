<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Mocks\AuthMock;

uses(RefreshDatabase::class)->in(__FILE__);
uses(AuthMock::class);

afterAll(function () {
    Artisan::call('migrate:refresh');
});

describe('Service Store Branch', function () {
    beforeEach(function () {
        $this->mockAuthMiddleware();
    });

    it('should be error when several fields are empty', function () {
        $payload = [
            'code' => '',
            'name' => '',
            'alamat' => '',
            'phone' => '',
        ];

        $response = $this->postJson('/api/master/branch', $payload);
        $response->assertStatus(400);

        $responseData = $response->json();
        expect($responseData['errors'])->toContain('The code field is required.');
        expect($responseData['errors'])->toContain('The name field is required.');
        expect($responseData['errors'])->toContain('The alamat field is required.');
        expect($responseData['errors'])->toContain('The phone field is required.');
    });

    it('should be error when code already exists', function () {
        $payload = [
            'code' => 'BR',
            'name' => 'Main Branch',
            'alamat' => 'Jl. Contoh No.1',
            'phone' => '08123456789',
            // 'logo' => uploaded file kalau mau diuji nanti
        ];

        $this->postJson('/api/master/branch', $payload);

        $response = $this->postJson('/api/master/branch', $payload);
        $response->assertStatus(400);

        $responseData = $response->json();
        expect($responseData['errors'])->toContain('The code has already been taken.');
    });

    it('should successfully store a branch', function () {
        $payload = [
            'code' => 'BR',
            'name' => 'Main Branch',
            'alamat' => 'Jl. Contoh No.1',
            'phone' => '08123456789',
            // 'logo' => uploaded file kalau mau diuji nanti
        ];

        $response = $this->postJson('/api/master/branch', $payload);
        $response->assertStatus(200);

        $responseData = $response->json();
        expect($responseData['status'])->toBe('success');
        expect($responseData['message'])->toBe('Branch created successfully');
        expect($responseData['data']['code'])->toBe('BR');
        expect($responseData['data']['name'])->toBe('Main Branch');

        // Cek database juga
        $this->assertDatabaseHas('branches', [
            'code' => 'BR',
            'name' => 'Main Branch',
            'alamat' => 'Jl. Contoh No.1',
            'phone' => '08123456789',
        ]);
    });
});
