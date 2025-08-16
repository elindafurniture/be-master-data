<?php

use App\Models\Branch;
use Tests\Mocks\AuthMock;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in(__FILE__);
uses(AuthMock::class);

describe('Show Branch', function () {
    beforeEach(function () {
        $this->mockAuthMiddleware();
    });

    it('can show branch by id', function () {
        $branch = Branch::factory()->create([
            'code' => 'BR',
            'name' => 'Branch Test',
            'alamat' => 'Test Address',
            'phone' => '08123456789',
            'deleted_status' => 1
        ]);

        $response = $this->getJson("/api/master/branch/{$branch->id}");

        $response->assertStatus(200)
                 ->assertJsonStructure([
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
                     'created_at',
                     'updated_at'
                 ]);

        $response->assertJson([
            'id' => $branch->id,
            'code' => 'BR',
            'name' => 'Branch Test',
            'alamat' => 'Test Address',
            'phone' => '08123456789'
        ]);
    });

    it('returns 404 for non-existent branch', function () {
        $response = $this->getJson('/api/master/branch/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Branch not found'
                 ]);
    });

    it('returns 404 for deleted branch', function () {
        $branch = Branch::factory()->create([
            'deleted_status' => null
        ]);

        $response = $this->getJson("/api/master/branch/{$branch->id}");

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Branch not found'
                 ]);
    });
});
