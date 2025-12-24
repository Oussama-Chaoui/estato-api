<?php

namespace App\Notifications;

use App\Enums\NOTIFICATION_TYPE;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AgentApplicationNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
    use Queueable;

    protected $agentData;

    /**
     * Create a new notification instance.
     */
    public function __construct($agentData)
    {
        $this->agentData = $agentData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        \Log::info('🔔 AgentApplicationNotification: via() called', [
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'channels' => ['database', 'broadcast']
        ]);
        return ['database', 'broadcast'];
    }

    /**
     * Get the notification's database type.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function databaseType($notifiable): string
    {
        return NOTIFICATION_TYPE::AGENT_APPLICATION->value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, string>
     */
    public function broadcastOn(): array
    {
        \Log::info('🔔 AgentApplicationNotification: broadcastOn() called', [
            'channels' => ['private-admin-notifications']
        ]);
        return ['private-admin-notifications'];
    }

    /**
     * Get the broadcast event name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        \Log::info('🔔 AgentApplicationNotification: broadcastAs() called', [
            'event_name' => 'AgentApplicationNotification'
        ]);
        return 'AgentApplicationNotification';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->id,
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
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toBroadcast(object $notifiable): array
    {
        $broadcastData = [
            'id' => $this->id,
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

        \Log::info('🔔 AgentApplicationNotification: toBroadcast() called', [
            'notifiable_id' => $notifiable->id,
            'notification_id' => $this->id,
            'broadcast_data' => $broadcastData
        ]);

        return $broadcastData;
    }
}
