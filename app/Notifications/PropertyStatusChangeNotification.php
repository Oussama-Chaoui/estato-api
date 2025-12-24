<?php

namespace App\Notifications;

use App\Enums\NOTIFICATION_TYPE;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PropertyStatusChangeNotification extends Notification implements ShouldQueue, ShouldBroadcast
{
  use Queueable;

  protected $propertyData;
  protected $oldStatus;
  protected $newStatus;

  /**
   * Create a new notification instance.
   */
  public function __construct($propertyData, $oldStatus, $newStatus)
  {
    $this->propertyData = $propertyData;
    $this->oldStatus = $oldStatus;
    $this->newStatus = $newStatus;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
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
    return NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return array<int, string>
   */
  public function broadcastOn(): array
  {
    \Log::info('🔔 PropertyStatusChangeNotification: broadcastOn() called', [
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
    \Log::info('🔔 PropertyStatusChangeNotification: broadcastAs() called', [
      'event_name' => 'PropertyStatusChangeNotification'
    ]);
    return 'PropertyStatusChangeNotification';
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'for_sale' => [
                'en' => 'For Sale',
                'fr' => 'À Vendre',
                'es' => 'En Venta'
            ],
            'sold' => [
                'en' => 'Sold',
                'fr' => 'Vendu',
                'es' => 'Vendido'
            ],
            'rented' => [
                'en' => 'Rented',
                'fr' => 'Loué',
                'es' => 'Alquilado'
            ],
            'off_market' => [
                'en' => 'Off Market',
                'fr' => 'Hors Marché',
                'es' => 'Fuera del Mercado'
            ],
        ];

        $oldStatusLabels = $statusLabels[$this->oldStatus] ?? ['en' => $this->oldStatus, 'fr' => $this->oldStatus, 'es' => $this->oldStatus];
        $newStatusLabels = $statusLabels[$this->newStatus] ?? ['en' => $this->newStatus, 'fr' => $this->newStatus, 'es' => $this->newStatus];

        return [
            'id' => $this->id,
            'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
            'title' => [
                'en' => 'Property Status Changed',
                'fr' => 'Statut de Propriété Modifié',
                'es' => 'Estado de Propiedad Cambiado'
            ],
            'message' => [
                'en' => "Property '{$this->propertyData['title']}' status changed from {$oldStatusLabels['en']} to {$newStatusLabels['en']}",
                'fr' => "Le statut de la propriété '{$this->propertyData['title']}' a changé de {$oldStatusLabels['fr']} à {$newStatusLabels['fr']}",
                'es' => "El estado de la propiedad '{$this->propertyData['title']}' cambió de {$oldStatusLabels['es']} a {$newStatusLabels['es']}"
            ],
            'property_id' => $this->propertyData['id'],
            'property_title' => $this->propertyData['title'],
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_labels' => $oldStatusLabels,
            'new_status_labels' => $newStatusLabels,
            'action_url' => '/properties/' . $this->propertyData['id'],
            'icon' => 'home',
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
        $statusLabels = [
            'for_sale' => [
                'en' => 'For Sale',
                'fr' => 'À Vendre',
                'es' => 'En Venta'
            ],
            'sold' => [
                'en' => 'Sold',
                'fr' => 'Vendu',
                'es' => 'Vendido'
            ],
            'rented' => [
                'en' => 'Rented',
                'fr' => 'Loué',
                'es' => 'Alquilado'
            ],
            'off_market' => [
                'en' => 'Off Market',
                'fr' => 'Hors Marché',
                'es' => 'Fuera del Mercado'
            ],
        ];

        $oldStatusLabels = $statusLabels[$this->oldStatus] ?? ['en' => $this->oldStatus, 'fr' => $this->oldStatus, 'es' => $this->oldStatus];
        $newStatusLabels = $statusLabels[$this->newStatus] ?? ['en' => $this->newStatus, 'fr' => $this->newStatus, 'es' => $this->newStatus];

        return [
            'id' => $this->id,
            'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
            'title' => [
                'en' => 'Property Status Changed',
                'fr' => 'Statut de Propriété Modifié',
                'es' => 'Estado de Propiedad Cambiado'
            ],
            'message' => [
                'en' => "Property '{$this->propertyData['title']}' status changed from {$oldStatusLabels['en']} to {$newStatusLabels['en']}",
                'fr' => "Le statut de la propriété '{$this->propertyData['title']}' a changé de {$oldStatusLabels['fr']} à {$newStatusLabels['fr']}",
                'es' => "El estado de la propiedad '{$this->propertyData['title']}' cambió de {$oldStatusLabels['es']} a {$newStatusLabels['es']}"
            ],
            'property_id' => $this->propertyData['id'],
            'property_title' => $this->propertyData['title'],
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'old_status_labels' => $oldStatusLabels,
            'new_status_labels' => $newStatusLabels,
            'action_url' => '/properties/' . $this->propertyData['id'],
            'icon' => 'home',
            'created_at' => now()->toISOString(),
        ];
    }
}
