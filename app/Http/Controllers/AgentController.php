<?php

namespace App\Http\Controllers;

use App\Enums\AGENT_APPLICATION_STATUS;
use App\Models\Agent;
use App\Models\AgentAppliance;
use App\Models\User;
use App\Notifications\AgentApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Broadcast;

class AgentController extends CrudController
{
  protected $table = 'agents';

  protected $modelClass = Agent::class;

  protected function getTable()
  {
    return $this->table;
  }

  protected function getModelClass()
  {
    return $this->modelClass;
  }

  public function applyAsAgent(Request $request)
  {
    try {
      return DB::transaction(function () use ($request) {
        $proto = new AgentAppliance;
        $validated = $request->validate(
          $proto->rules(),
          method_exists($proto, 'validationMessages')
            ? $proto->validationMessages()
            : []
        );

        $validated['status'] = AGENT_APPLICATION_STATUS::PENDING->value;

        $existingApplication = AgentAppliance::where('name', $validated['name'])
          ->where('phone', $validated['phone'])
          ->where(function ($query) use ($validated) {
            if (empty($validated['email'])) {
              $query->whereNull('email');
            } else {
              $query->where('email', $validated['email']);
            }
          })
          ->first();

        if ($existingApplication) {
          $existingApplication->touch();
          $agentApplication = $existingApplication;
        } else {
          $agentApplication = AgentAppliance::create($validated);

          $this->sendAgentApplicationNotification($agentApplication);
        }

        return response()->json([
          'success' => true,
          'data' => ['item' => $agentApplication],
          'message' => __('agents.application_submitted'),
        ]);
      });
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => Arr::flatten($e->errors()),
      ]);
    } catch (\Exception $e) {
      Log::error('AgentController::applyAsAgent error: ' . $e->getMessage());
      return response()->json([
        'success' => false,
        'errors' => [__('common.unexpected_error')],
      ]);
    }
  }

  private function sendAgentApplicationNotification(AgentAppliance $agentApplication): void
  {
    $adminUsers = User::whereHas('roles', function ($query) {
      $query->where('name', \App\Enums\ROLE::ADMIN->value);
    })->get();

    $agentData = [
      'id' => $agentApplication->id,
      'name' => $agentApplication->name,
      'email' => $agentApplication->email,
      'phone' => $agentApplication->phone,
      'agency_name' => 'N/A',
      'licence_number' => 'N/A',
    ];

    foreach ($adminUsers as $user) {
      try {
        $notification = new AgentApplicationNotification($agentData);
        $user->notify($notification);

        event(new \App\Events\AgentApplicationNotificationEvent($agentData));
      } catch (\Exception $e) {
        \Log::error('🔔 AgentController: Failed to send notification', [
          'admin_user_id' => $user->id,
          'admin_email' => $user->email,
          'agent_application_id' => $agentApplication->id,
          'error' => $e->getMessage()
        ]);
      }
    }
  }
}
