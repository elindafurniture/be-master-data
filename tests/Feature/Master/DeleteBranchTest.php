<?php

use App\Models\Branch;
use Tests\Mocks\AuthMock;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in(__FILE__);
uses(AuthMock::class);

describe('Delete Branch', function () {
    beforeEach(function () {
        $this->mockAuthMiddleware();
    });

    it('can soft delete branch', function () {
        $branch = Branch::factory()->create([
            'code' => 'BR',
            'name' => 'Branch to Delete',
            'deleted_status' => 1
        ]);

        $response = $this->deleteJson("/api/master/branch/{$branch->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Branch deleted successfully'
                 ])
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'id',
                         'code',
                         'name',
                         'deleted_by',
                         'deleted_by_name',
                         'deleted_status'
                     ]
                 ]);

        $this->assertDatabaseHas('branches', [
            'id' => $branch->id,
            'deleted_status' => null
        ]);

        $this->assertSoftDeleted('branches', [
            'id' => $branch->id
        ]);
    });

    it('returns 404 for non-existent branch on delete', function () {
        $response = $this->deleteJson('/api/master/branch/999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Branch not found'
                 ]);
    });
});
