<?php

namespace App\Events;

use App\Enums\NOTIFICATION_TYPE;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentApplicationNotificationEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $agentData;

  /**
   * Create a new event instance.
   */
  public function __construct($agentData)
  {
    $this->agentData = $agentData;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, \Illuminate\Broadcasting\Channel>
   */
  public function broadcastOn(): array
  {
    \Log::info('🔔 AgentApplicationNotificationEvent: broadcastOn() called', [
      'channels' => ['private-admin-notifications']
    ]);

    return [
      new PrivateChannel('admin-notifications'),
    ];
  }

  /**
   * Get the data to broadcast.
   *
   * @return array
   */
  public function broadcastWith(): array
  {
    $broadcastData = [
      'id' => uniqid(),
      'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
      'title' => [
        'en' => 'New Agent Application',
        'fr' => 'Nouvelle Candidature Agent',
        'es' => 'Nueva Solicitud de Agente'
      ],
      'message' => [
        'en' => "New agent application from {$this->agentData['name']}",
        'fr' => "Nouvelle candidature d'agent de {$this->agentData['name']}",
        'es' => "Nueva solicitud de agente de {$this->agentData['name']}"
      ],
      'agent_id' => $this->agentData['id'],
      'agent_name' => $this->agentData['name'],
      'agency_name' => $this->agentData['agency_name'],
      'licence_number' => $this->agentData['licence_number'],
      'action_url' => '/agent-applications',
      'icon' => 'person_add',
      'created_at' => now()->toISOString(),
    ];

    \Log::info('🔔 AgentApplicationNotificationEvent: broadcastWith() called', [
      'broadcast_data' => $broadcastData
    ]);

    return $broadcastData;
  }

  /**
   * The event's broadcast name.
   *
   * @return string
   */
  public function broadcastAs(): string
  {
    \Log::info('🔔 AgentApplicationNotificationEvent: broadcastAs() called', [
      'event_name' => 'AgentApplicationNotification'
    ]);

    return 'AgentApplicationNotification';
  }
}
