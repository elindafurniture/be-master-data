<?php

use App\Models\Branch;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Mocks\AuthMock;

uses(RefreshDatabase::class)->in(__FILE__);
uses(AuthMock::class);

afterAll(function () {
    Artisan::call('migrate:refresh');
});

describe('Service List Branch', function () {
    beforeEach(function () {
        $this->mockAuthMiddleware();
    });
    
    it('should be success get list', function () {
        Branch::factory()->count(5)->create();

        $response = $this->getJson('/api/master/branch');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'code',
                    'name',
                    'alamat',
                    'phone',
                    'logo',
                    'pic_id',
                    'created_by',
                    'created_by_name',
                    'updated_by',
                    'updated_by_name',
                    'deleted_by',
                    'deleted_by_name',
                    'deleted_status',
                    'deleted_at',
                    'created_at',
                    'updated_at'
                ]
            ],
            'links',
        ]);
    });

    it('should return empty when no branches exist', function () {
        $response = $this->getJson('/api/master/branch');

        $response->assertStatus(200);
        $response->assertJson([
            'data' => []
        ]);
    });

    it('should search branches by name and code only', function () {
        // Create test branches
        Branch::factory()->create([
            'code' => 'BR001',
            'name' => 'Jakarta Branch',
            'alamat' => 'Jl. Sudirman No. 1',
            'phone' => '021-1234567'
        ]);

        Branch::factory()->create([
            'code' => 'BR002',
            'name' => 'Surabaya Branch',
            'alamat' => 'Jl. Thamrin No. 2',
            'phone' => '031-7654321'
        ]);

        Branch::factory()->create([
            'code' => 'BR003',
            'name' => 'Bandung Branch',
            'alamat' => 'Jl. Asia Afrika No. 3',
            'phone' => '022-1111111'
        ]);

        // Test search by name
        $response = $this->getJson('/api/master/branch?search=Jakarta');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);
        expect($responseData['data'][0]['name'])->toBe('Jakarta Branch');

        // Test search by code
        $response = $this->getJson('/api/master/branch?search=BR002');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);
        expect($responseData['data'][0]['code'])->toBe('BR002');

        // Test search that should not find by alamat or phone
        $response = $this->getJson('/api/master/branch?search=Sudirman');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(0);

        $response = $this->getJson('/api/master/branch?search=1234567');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(0);
    });

    it('should search with case insensitive', function () {
        Branch::factory()->create([
            'code' => 'BR001',
            'name' => 'Jakarta Branch',
        ]);

        // Test case insensitive search - should work with any case
        $response = $this->getJson('/api/master/branch?search=jakarta');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);

        $response = $this->getJson('/api/master/branch?search=JAKARTA');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);

        $response = $this->getJson('/api/master/branch?search=br001');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);

        $response = $this->getJson('/api/master/branch?search=BR001');
        $response->assertStatus(200);
        $responseData = $response->json();
        expect($responseData['data'])->toHaveCount(1);
    });

});

