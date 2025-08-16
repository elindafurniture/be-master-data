<?php

use App\Models\Branch;
use Tests\Mocks\AuthMock;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in(__FILE__);
uses(AuthMock::class);

describe('Update Branch', function () {
    beforeEach(function () {
        $this->mockAuthMiddleware();
    });

    it('can update branch', function () {
        $branch = Branch::factory()->create([
            'code' => 'BR',
            'name' => 'Original Name',
            'alamat' => 'Original Address',
            'phone' => '08123456789',
            'deleted_status' => 1
        ]);

        $updateData = [
            'code' => 'B2',
            'name' => 'Updated Name',
            'alamat' => 'Updated Address',
            'phone' => '08987654321'
        ];

        $response = $this->putJson("/api/master/branch/{$branch->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Branch updated successfully'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'code',
                         'name',
                         'alamat',
                         'phone',
                         'updated_by',
                         'updated_by_name'
                     ]
                 ]);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'code' => 'B2',
            'name' => 'Updated Name',
            'alamat' => 'Updated Address',
            'phone' => '08987654321'
        ]);
    });

    it('cannot update with duplicate code', function () {
        // Create first branch
        Branch::factory()->create([
            'code' => 'B1',
            'deleted_status' => 1
        ]);

        // Create second branch
        $branch2 = Branch::factory()->create([
            'code' => 'B2',
            'deleted_status' => 1
        ]);

        $updateData = [
            'code' => 'B1', // Try to use code from first branch
            'name' => 'Updated Name',
            'alamat' => 'Updated Address',
            'phone' => '08987654321'
        ];

        $response = $this->putJson("/api/master/branch/{$branch2->id}", $updateData);

        $response->assertStatus(400);
        
        $responseData = $response->json();
        expect($responseData['errors'])->toContain('The code has already been taken.');
    });

    it('returns 404 for non-existent branch', function () {
        $updateData = [
            'code' => 'B2',
            'name' => 'Updated Name',
            'alamat' => 'Updated Address',
            'phone' => '08987654321'
        ];

        $response = $this->putJson('/api/master/branch/999', $updateData);

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Branch not found'
                 ]);
    });

    it('validates required fields', function () {
        $branch = Branch::factory()->create([
            'deleted_status' => 1
        ]);

        $response = $this->putJson("/api/master/branch/{$branch->id}", []);

        $response->assertStatus(400);
        
        $responseData = $response->json();
        expect($responseData['errors'])->toContain('The code field is required.');
        expect($responseData['errors'])->toContain('The name field is required.');
        expect($responseData['errors'])->toContain('The alamat field is required.');
        expect($responseData['errors'])->toContain('The phone field is required.');
    });

    it('can update branch with logo', function () {
        $branch = Branch::factory()->create([
            'deleted_status' => 1
        ]);

        $updateData = [
            'code' => 'B2',
            'name' => 'Updated Name',
            'alamat' => 'Updated Address',
            'phone' => '08987654321'
        ];

        $response = $this->putJson("/api/master/branch/{$branch->id}", $updateData);

        $response->assertStatus(200);
    });
});
