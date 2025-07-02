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
    $aclService->createScopePermissions('agents', ['create', 'read', 'update', 'delete']);
    $aclService->createScopePermissions('locations', ['create', 'read', 'update', 'delete']);
    $aclService->createScopePermissions('amenities', ['create', 'read', 'update', 'delete']);
    $aclService->createScopePermissions('posts', ['create', 'read', 'update', 'delete']);
    $aclService->createScopePermissions('categories', ['create', 'read', 'update', 'delete']);
    $aclService->createScopePermissions('tags', ['create', 'read', 'update', 'delete']);

    // Assign permissions to roles
    $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();
    $agentRole = Role::where('name', ROLE_ENUM::AGENT)->first();

    // Assign to admin role
    $aclService->assignScopePermissionsToRole($adminRole, 'properties', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'agents', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'locations', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'amenities', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'posts', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'categories', ['create', 'read', 'update', 'delete']);
    $aclService->assignScopePermissionsToRole($adminRole, 'tags', ['create', 'read', 'update', 'delete']);

    // Assign to agent role
    $aclService->assignScopePermissionsToRole($agentRole, 'properties', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'locations', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'amenities', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'agents', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'posts', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'categories', ['read']);
    $aclService->assignScopePermissionsToRole($agentRole, 'tags', ['read']);
  }

  public function rollback(ACLService $aclService)
  {
    $adminRole = Role::where('name', ROLE_ENUM::ADMIN)->first();
    $aclService->removeScopePermissionsFromRole($adminRole, 'properties', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'agents', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'locations', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'amenities', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'posts', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'categories', ['create', 'read', 'update', 'delete']);
    $aclService->removeScopePermissionsFromRole($adminRole, 'tags', ['create', 'read', 'update', 'delete']);

    $agentRole = Role::where('name', ROLE_ENUM::AGENT)->first();
    $aclService->removeScopePermissionsFromRole($agentRole, 'properties', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'locations', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'amenities', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'agents', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'posts', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'categories', ['read']);
    $aclService->removeScopePermissionsFromRole($agentRole, 'tags', ['read']);
  }
}
