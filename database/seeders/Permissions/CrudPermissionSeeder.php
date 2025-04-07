<?php

namespace Database\Seeders\Permissions;

use App\Enums\ROLE as ROLE_ENUM;
use App\Models\Role;
use App\Services\ACLService;
use Illuminate\Database\Seeder;

class CrudPermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(ACLService $aclService)
  {
    // Create Scoped permissions
    $aclService->createScopePermissions('properties', ['create', 'read', 'read_own', 'update', 'delete']);

    // Assign permissions to roles
    $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();
    $agentRole = Role::where('name', ROLE_ENUM::AGENT)->first();
    $aclService->assignScopePermissionsToRole($adminRole, 'properties', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($agentRole, 'properties', ['read']);
  }

  public function rollback(ACLService $aclService)
  {
    $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();
    $aclService->removeScopePermissionsFromRole($adminRole, 'properties', ['create', 'read', 'update', 'delete']);
  }
}
