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


});

