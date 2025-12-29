<?php

namespace App\Http\Controllers;

use App\Enums\ROLE;
use App\Models\User;
use App\Models\Agent;
use App\Models\Client;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Log;

class UserController extends CrudController
{
  protected $table = 'users';

  protected $modelClass = User::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  public function afterReadOne($item, $request)
  {
    if ($item->photo_id && is_null($item->photo)) {
      $item->load('photo');
    }

    if ($item->hasRole(ROLE::CLIENT)) {
      $item->load('client');
    }

    if ($item->hasRole(ROLE::AGENT)) {
      $item->load('agent');
    }
  }

  public function createOne(Request $request)
  {
    try {
      $request->merge(['password' => Hash::make($request->password)]);

      return parent::createOne($request);
    } catch (\Exception $e) {
      Log::error('Error caught in function UserController.createOne : ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function afterCreateOne($item, $request)
  {
    try {
      DB::transaction(function () use ($item, $request) {
        // Handle multiple roles
        $roles = [];
        if ($request->has('roles')) {
          $roles = is_string($request->roles) ? json_decode($request->roles, true) : $request->roles;
        }

        foreach ($roles as $roleName) {
          $roleEnum = ROLE::from($roleName);
          $item->assignRole($roleEnum);
        }

        if (in_array(ROLE::AGENT->value, $roles)) {
          $this->createAgentProfile($item, $request);
        }

        if (in_array(ROLE::CLIENT->value, $roles)) {
          $this->createClientProfile($item, $request);
        }
      });
    } catch (\Exception $e) {
      Log::error('Error caught in function UserController.afterCreateOne : ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function updateOne($id, Request $request)
  {
    try {
      if (isset($request->password) && ! empty($request->password)) {
        $request->merge(['password' => Hash::make($request->password)]);
      } else {
        $request->request->remove('password');
      }

      return parent::updateOne($id, $request);
    } catch (\Exception $e) {
      Log::error('Error caught in function UserController.updateOne : ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  public function afterUpdateOne($item, $request)
  {
    try {
      DB::transaction(function () use ($item, $request) {
        // Handle multiple roles for updates
        $roles = [];
        if ($request->has('roles')) {
          $roles = is_string($request->roles) ? json_decode($request->roles, true) : $request->roles;
        }

        foreach ($roles as $roleName) {
          $roleEnum = ROLE::from($roleName);
          if (!$item->hasRole($roleEnum)) {
            $item->assignRole($roleEnum);
          }
        }

        // Update or create Agent profile if AGENT role is selected
        if (in_array(ROLE::AGENT->value, $roles)) {
          $this->updateOrCreateAgentProfile($item, $request);
        }

        // Update or create Client profile if CLIENT role is selected
        if (in_array(ROLE::CLIENT->value, $roles)) {
          $this->updateOrCreateClientProfile($item, $request);
        }
      });
    } catch (\Exception $e) {
      Log::error('Error caught in function UserController.afterUpdateOne : ' . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json(['success' => false, 'errors' => [__('common.unexpected_error')]]);
    }
  }

  private function createAgentProfile($user, $request)
  {
    $agentData = [
      'licence_number' => $request->licence_number,
      'experience' => $request->experience,
      'bio' => $request->bio,
      'agency_name' => $request->agency_name,
      'agency_address' => $request->agency_address,
      'user_id' => $user->id,
    ];

    if ($request->photo_id) {
      $agentData['photo_id'] = $request->photo_id;
    }

    $agent = Agent::create($agentData);

    // Sync languages if provided
    if ($request->has('languages') && is_array($request->languages)) {
      $languageIds = Language::whereIn('name', $request->languages)->pluck('id');
      $agent->languages()->sync($languageIds);
    }
  }

  private function updateOrCreateAgentProfile($user, $request)
  {
    $agentData = [
      'licence_number' => $request->licence_number,
      'experience' => $request->experience,
      'bio' => $request->bio,
      'agency_name' => $request->agency_name,
      'agency_address' => $request->agency_address,
    ];

    if ($request->photo_id) {
      $agentData['photo_id'] = $request->photo_id;
    }

    $agent = $user->agent()->updateOrCreate(['user_id' => $user->id], $agentData);

    // Sync languages if provided
    if ($request->has('languages') && is_array($request->languages)) {
      $languageIds = Language::whereIn('name', $request->languages)->pluck('id');
      $agent->languages()->sync($languageIds);
    }
  }

  private function createClientProfile($user, $request)
  {
    $clientData = [
      'nic_number' => $request->nic_number,
      'passport' => $request->passport,
      'user_id' => $user->id,
    ];

    if ($request->image_id) {
      $clientData['image_id'] = $request->image_id;
    }

    Client::create($clientData);
  }

  private function updateOrCreateClientProfile($user, $request)
  {
    $clientData = [
      'nic_number' => $request->nic_number,
      'passport' => $request->passport,
    ];

    if ($request->image_id) {
      $clientData['image_id'] = $request->image_id;
    }

    $user->client()->updateOrCreate(['user_id' => $user->id], $clientData);
  }
}
