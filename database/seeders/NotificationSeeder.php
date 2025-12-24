<?php

namespace Database\Seeders;

use App\Enums\NOTIFICATION_TYPE;
use App\Models\User;
use App\Models\Property;
use App\Models\Agent;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NotificationSeeder extends Seeder
{
  public function run(): void
  {
    // Get admin user to notify
    $adminUser = User::where('email', 'admin@example.com')->first();
    
    if (!$adminUser) {
      $this->command->warn('Admin user not found. Skipping notification seeder.');
      return;
    }

    // Get some properties and agents for realistic data
    $properties = Property::withSold()->take(5)->get();
    $agents = Agent::take(3)->get();

    $notifications = [];
    $now = Carbon::now();

    // Create 20 diverse notifications
    
    // 1-3: Agent Applications (recent)
    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
        'title' => [
          'en' => 'New Agent Application',
          'fr' => 'Nouvelle Candidature Agent',
          'es' => 'Nueva Solicitud de Agente'
        ],
        'message' => [
          'en' => 'New agent application from Mohammed Hassan',
          'fr' => 'Nouvelle candidature d\'agent de Mohammed Hassan',
          'es' => 'Nueva solicitud de agente de Mohammed Hassan'
        ],
        'agent_name' => 'Mohammed Hassan',
        'agency_name' => 'Morocco Elite Properties',
        'licence_number' => 'LIC-2024-001',
        'action_url' => '/agent-applications',
        'icon' => 'person_add',
        'created_at' => $now->copy()->subMinutes(30)->toISOString(),
      ]),
      'read_at' => null,
      'created_at' => $now->copy()->subMinutes(30),
      'updated_at' => $now->copy()->subMinutes(30),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
        'title' => [
          'en' => 'New Agent Application',
          'fr' => 'Nouvelle Candidature Agent',
          'es' => 'Nueva Solicitud de Agente'
        ],
        'message' => [
          'en' => 'New agent application from Fatima Zahra',
          'fr' => 'Nouvelle candidature d\'agent de Fatima Zahra',
          'es' => 'Nueva solicitud de agente de Fatima Zahra'
        ],
        'agent_name' => 'Fatima Zahra',
        'agency_name' => 'Casablanca Real Estate',
        'licence_number' => 'LIC-2024-002',
        'action_url' => '/agent-applications',
        'icon' => 'person_add',
        'created_at' => $now->copy()->subHours(2)->toISOString(),
      ]),
      'read_at' => null,
      'created_at' => $now->copy()->subHours(2),
      'updated_at' => $now->copy()->subHours(2),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
        'title' => [
          'en' => 'New Agent Application',
          'fr' => 'Nouvelle Candidature Agent',
          'es' => 'Nueva Solicitud de Agente'
        ],
        'message' => [
          'en' => 'New agent application from Ahmed Benani',
          'fr' => 'Nouvelle candidature d\'agent de Ahmed Benani',
          'es' => 'Nueva solicitud de agente de Ahmed Benani'
        ],
        'agent_name' => 'Ahmed Benani',
        'agency_name' => 'Marrakech Property Group',
        'licence_number' => 'LIC-2024-003',
        'action_url' => '/agent-applications',
        'icon' => 'person_add',
        'created_at' => $now->copy()->subHours(5)->toISOString(),
      ]),
      'read_at' => $now->copy()->subHours(4),
      'created_at' => $now->copy()->subHours(5),
      'updated_at' => $now->copy()->subHours(4),
    ];

    // 4-8: Property Status Changes
    if ($properties->count() > 0) {
      $property1 = $properties->first();
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
          'title' => [
            'en' => 'Property Status Changed',
            'fr' => 'Statut de Propriété Modifié',
            'es' => 'Estado de Propiedad Cambiado'
          ],
          'message' => [
            'en' => "Property '{$property1->title['en']}' status changed from Available to Rented",
            'fr' => "Le statut de la propriété '{$property1->title['fr']}' a changé de Disponible à Loué",
            'es' => "El estado de la propiedad '{$property1->title['es']}' cambió de Disponible a Alquilado"
          ],
          'property_id' => $property1->id,
          'property_title' => $property1->title['en'],
          'old_status' => 'available',
          'new_status' => 'rented',
          'action_url' => '/properties/' . $property1->id,
          'icon' => 'home',
          'created_at' => $now->copy()->subHours(6)->toISOString(),
        ]),
        'read_at' => null,
        'created_at' => $now->copy()->subHours(6),
        'updated_at' => $now->copy()->subHours(6),
      ];
    }

    if ($properties->count() > 1) {
      $property2 = $properties->get(1);
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
          'title' => [
            'en' => 'Property Status Changed',
            'fr' => 'Statut de Propriété Modifié',
            'es' => 'Estado de Propiedad Cambiado'
          ],
          'message' => [
            'en' => "Property '{$property2->title['en']}' status changed from For Sale to Sold",
            'fr' => "Le statut de la propriété '{$property2->title['fr']}' a changé de À Vendre à Vendu",
            'es' => "El estado de la propiedad '{$property2->title['es']}' cambió de En Venta a Vendido"
          ],
          'property_id' => $property2->id,
          'property_title' => $property2->title['en'],
          'old_status' => 'for_sale',
          'new_status' => 'sold',
          'action_url' => '/properties/' . $property2->id,
          'icon' => 'home',
          'created_at' => $now->copy()->subHours(12)->toISOString(),
        ]),
        'read_at' => $now->copy()->subHours(10),
        'created_at' => $now->copy()->subHours(12),
        'updated_at' => $now->copy()->subHours(10),
      ];
    }

    if ($properties->count() > 2) {
      $property3 = $properties->get(2);
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE->value,
          'title' => [
            'en' => 'Property Status Changed',
            'fr' => 'Statut de Propriété Modifié',
            'es' => 'Estado de Propiedad Cambiado'
          ],
          'message' => [
            'en' => "Property '{$property3->title['en']}' has been marked as Off Market",
            'fr' => "La propriété '{$property3->title['fr']}' a été marquée comme Hors Marché",
            'es' => "La propiedad '{$property3->title['es']}' se ha marcado como Fuera del Mercado"
          ],
          'property_id' => $property3->id,
          'property_title' => $property3->title['en'],
          'old_status' => 'available',
          'new_status' => 'off_market',
          'action_url' => '/properties/' . $property3->id,
          'icon' => 'home',
          'created_at' => $now->copy()->subDays(1)->toISOString(),
        ]),
        'read_at' => null,
        'created_at' => $now->copy()->subDays(1),
        'updated_at' => $now->copy()->subDays(1),
      ];
    }

    // 9-12: Property Inquiries
    if ($properties->count() > 0 && $agents->count() > 0) {
      $property = $properties->first();
      $agent = $agents->first();
      
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
          'title' => [
            'en' => 'New Property Inquiry',
            'fr' => 'Nouvelle Demande de Propriété',
            'es' => 'Nueva Consulta de Propiedad'
          ],
          'message' => [
            'en' => "New inquiry from client Sarah Martinez for '{$property->title['en']}'",
            'fr' => "Nouvelle demande du client Sarah Martinez pour '{$property->title['fr']}'",
            'es' => "Nueva consulta del cliente Sarah Martinez para '{$property->title['es']}'"
          ],
          'property_id' => $property->id,
          'property_title' => $property->title['en'],
          'client_name' => 'Sarah Martinez',
          'client_email' => 'sarah.martinez@example.com',
          'action_url' => '/properties/' . $property->id,
          'icon' => 'visibility',
          'created_at' => $now->copy()->subHours(8)->toISOString(),
        ]),
        'read_at' => null,
        'created_at' => $now->copy()->subHours(8),
        'updated_at' => $now->copy()->subHours(8),
      ];
    }

    if ($properties->count() > 1) {
      $property = $properties->get(1);
      
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
          'title' => [
            'en' => 'New Property Inquiry',
            'fr' => 'Nouvelle Demande de Propriété',
            'es' => 'Nueva Consulta de Propiedad'
          ],
          'message' => [
            'en' => "Client Jean Dupont is interested in '{$property->title['en']}'",
            'fr' => "Le client Jean Dupont est intéressé par '{$property->title['fr']}'",
            'es' => "El cliente Jean Dupont está interesado en '{$property->title['es']}'"
          ],
          'property_id' => $property->id,
          'property_title' => $property->title['en'],
          'client_name' => 'Jean Dupont',
          'client_email' => 'jean.dupont@example.com',
          'action_url' => '/properties/' . $property->id,
          'icon' => 'visibility',
          'created_at' => $now->copy()->subDays(1)->subHours(3)->toISOString(),
        ]),
        'read_at' => $now->copy()->subDays(1)->subHours(2),
        'created_at' => $now->copy()->subDays(1)->subHours(3),
        'updated_at' => $now->copy()->subDays(1)->subHours(2),
      ];
    }

    if ($properties->count() > 2) {
      $property = $properties->get(2);
      
      $notifications[] = [
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
        'notifiable_type' => 'App\\Models\\User',
        'notifiable_id' => $adminUser->id,
        'data' => json_encode([
          'id' => Str::uuid()->toString(),
          'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
          'title' => [
            'en' => 'New Property Inquiry',
            'fr' => 'Nouvelle Demande de Propriété',
            'es' => 'Nueva Consulta de Propiedad'
          ],
          'message' => [
            'en' => "High priority inquiry from Marcus Chen for '{$property->title['en']}'",
            'fr' => "Demande prioritaire de Marcus Chen pour '{$property->title['fr']}'",
            'es' => "Consulta de alta prioridad de Marcus Chen para '{$property->title['es']}'"
          ],
          'property_id' => $property->id,
          'property_title' => $property->title['en'],
          'client_name' => 'Marcus Chen',
          'client_email' => 'marcus.chen@example.com',
          'action_url' => '/properties/' . $property->id,
          'icon' => 'visibility',
          'created_at' => $now->copy()->subDays(2)->toISOString(),
        ]),
        'read_at' => null,
        'created_at' => $now->copy()->subDays(2),
        'updated_at' => $now->copy()->subDays(2),
      ];
    }

    // 13-15: System Alerts
    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
        'title' => [
          'en' => 'System Alert',
          'fr' => 'Alerte Système',
          'es' => 'Alerta del Sistema'
        ],
        'message' => [
          'en' => 'Database backup completed successfully',
          'fr' => 'Sauvegarde de la base de données terminée avec succès',
          'es' => 'Copia de seguridad de la base de datos completada con éxito'
        ],
        'action_url' => '/',
        'icon' => 'business',
        'created_at' => $now->copy()->subDays(2)->subHours(6)->toISOString(),
      ]),
      'read_at' => $now->copy()->subDays(2)->subHours(5),
      'created_at' => $now->copy()->subDays(2)->subHours(6),
      'updated_at' => $now->copy()->subDays(2)->subHours(5),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
        'title' => [
          'en' => 'System Alert',
          'fr' => 'Alerte Système',
          'es' => 'Alerta del Sistema'
        ],
        'message' => [
          'en' => 'Server maintenance scheduled for tonight at 2:00 AM',
          'fr' => 'Maintenance du serveur prévue ce soir à 2h00',
          'es' => 'Mantenimiento del servidor programado para esta noche a las 2:00 AM'
        ],
        'action_url' => '/',
        'icon' => 'business',
        'created_at' => $now->copy()->subDays(3)->toISOString(),
      ]),
      'read_at' => null,
      'created_at' => $now->copy()->subDays(3),
      'updated_at' => $now->copy()->subDays(3),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
        'title' => [
          'en' => 'System Alert',
          'fr' => 'Alerte Système',
          'es' => 'Alerta del Sistema'
        ],
        'message' => [
          'en' => 'New security update available - please review',
          'fr' => 'Nouvelle mise à jour de sécurité disponible - veuillez consulter',
          'es' => 'Nueva actualización de seguridad disponible - por favor revise'
        ],
        'action_url' => '/',
        'icon' => 'business',
        'created_at' => $now->copy()->subDays(4)->toISOString(),
      ]),
      'read_at' => $now->copy()->subDays(3)->subHours(12),
      'created_at' => $now->copy()->subDays(4),
      'updated_at' => $now->copy()->subDays(3)->subHours(12),
    ];

    // 16-20: Appointments and User Updates (older)
    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::NEW_APPOINTMENT->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::NEW_APPOINTMENT->value,
        'title' => [
          'en' => 'New Appointment Scheduled',
          'fr' => 'Nouveau Rendez-vous Programmé',
          'es' => 'Nueva Cita Programada'
        ],
        'message' => [
          'en' => 'Property viewing scheduled for tomorrow at 10:00 AM',
          'fr' => 'Visite de propriété programmée pour demain à 10h00',
          'es' => 'Visita de propiedad programada para mañana a las 10:00 AM'
        ],
        'appointment_date' => $now->copy()->addDay()->setTime(10, 0)->toISOString(),
        'action_url' => '/',
        'icon' => 'assignment',
        'created_at' => $now->copy()->subDays(5)->toISOString(),
      ]),
      'read_at' => null,
      'created_at' => $now->copy()->subDays(5),
      'updated_at' => $now->copy()->subDays(5),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::NEW_APPOINTMENT->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::NEW_APPOINTMENT->value,
        'title' => [
          'en' => 'New Appointment Scheduled',
          'fr' => 'Nouveau Rendez-vous Programmé',
          'es' => 'Nueva Cita Programada'
        ],
        'message' => [
          'en' => 'Contract signing appointment with client Ahmed Khalil',
          'fr' => 'Rendez-vous de signature de contrat avec le client Ahmed Khalil',
          'es' => 'Cita de firma de contrato con el cliente Ahmed Khalil'
        ],
        'client_name' => 'Ahmed Khalil',
        'action_url' => '/',
        'icon' => 'assignment',
        'created_at' => $now->copy()->subDays(6)->toISOString(),
      ]),
      'read_at' => $now->copy()->subDays(5)->subHours(12),
      'created_at' => $now->copy()->subDays(6),
      'updated_at' => $now->copy()->subDays(5)->subHours(12),
    ];

    $notifications[] = [
      'id' => Str::uuid()->toString(),
      'type' => NOTIFICATION_TYPE::USER_ACCOUNT_UPDATE->value,
      'notifiable_type' => 'App\\Models\\User',
      'notifiable_id' => $adminUser->id,
      'data' => json_encode([
        'id' => Str::uuid()->toString(),
        'type' => NOTIFICATION_TYPE::USER_ACCOUNT_UPDATE->value,
        'title' => [
          'en' => 'Account Updated',
          'fr' => 'Compte Mis à Jour',
          'es' => 'Cuenta Actualizada'
        ],
        'message' => [
          'en' => 'Your account settings have been updated successfully',
          'fr' => 'Les paramètres de votre compte ont été mis à jour avec succès',
          'es' => 'La configuración de su cuenta se ha actualizado correctamente'
        ],
        'action_url' => '/users/me',
        'icon' => 'person',
        'created_at' => $now->copy()->subWeek()->toISOString(),
      ]),
      'read_at' => $now->copy()->subDays(6),
      'created_at' => $now->copy()->subWeek(),
      'updated_at' => $now->copy()->subDays(6),
    ];

    // Additional varied notifications to reach 20
    for ($i = 10; $i <= 20; $i++) {
      $daysAgo = $i - 5;
      $isRead = $i % 3 === 0; // Every third notification is read
      
      $notifTypes = [
        NOTIFICATION_TYPE::AGENT_APPLICATION,
        NOTIFICATION_TYPE::PROPERTY_STATUS_CHANGE,
        NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY,
        NOTIFICATION_TYPE::NEW_APPOINTMENT,
        NOTIFICATION_TYPE::SYSTEM_ALERT,
      ];
      
      $selectedType = $notifTypes[$i % count($notifTypes)];
      
      switch ($selectedType) {
        case NOTIFICATION_TYPE::AGENT_APPLICATION:
          $notifications[] = [
            'id' => Str::uuid()->toString(),
            'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $adminUser->id,
            'data' => json_encode([
              'id' => Str::uuid()->toString(),
              'type' => NOTIFICATION_TYPE::AGENT_APPLICATION->value,
              'title' => [
                'en' => 'New Agent Application',
                'fr' => 'Nouvelle Candidature Agent',
                'es' => 'Nueva Solicitud de Agente'
              ],
              'message' => [
                'en' => "Agent application #$i received",
                'fr' => "Candidature d'agent #$i reçue",
                'es' => "Solicitud de agente #$i recibida"
              ],
              'agent_name' => "Agent Candidate $i",
              'action_url' => '/agent-applications',
              'icon' => 'person_add',
              'created_at' => $now->copy()->subDays($daysAgo)->toISOString(),
            ]),
            'read_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : null,
            'created_at' => $now->copy()->subDays($daysAgo),
            'updated_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : $now->copy()->subDays($daysAgo),
          ];
          break;

        case NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY:
          if ($properties->count() > 0) {
            $property = $properties->random();
            $notifications[] = [
              'id' => Str::uuid()->toString(),
              'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
              'notifiable_type' => 'App\\Models\\User',
              'notifiable_id' => $adminUser->id,
              'data' => json_encode([
                'id' => Str::uuid()->toString(),
                'type' => NOTIFICATION_TYPE::NEW_PROPERTY_INQUIRY->value,
                'title' => [
                  'en' => 'New Property Inquiry',
                  'fr' => 'Nouvelle Demande de Propriété',
                  'es' => 'Nueva Consulta de Propiedad'
                ],
                'message' => [
                  'en' => "Inquiry #$i for property '{$property->title['en']}'",
                  'fr' => "Demande #$i pour la propriété '{$property->title['fr']}'",
                  'es' => "Consulta #$i para la propiedad '{$property->title['es']}'"
                ],
                'property_id' => $property->id,
                'property_title' => $property->title['en'],
                'action_url' => '/properties/' . $property->id,
                'icon' => 'visibility',
                'created_at' => $now->copy()->subDays($daysAgo)->toISOString(),
              ]),
              'read_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : null,
              'created_at' => $now->copy()->subDays($daysAgo),
              'updated_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : $now->copy()->subDays($daysAgo),
            ];
          }
          break;

        case NOTIFICATION_TYPE::SYSTEM_ALERT:
          $notifications[] = [
            'id' => Str::uuid()->toString(),
            'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $adminUser->id,
            'data' => json_encode([
              'id' => Str::uuid()->toString(),
              'type' => NOTIFICATION_TYPE::SYSTEM_ALERT->value,
              'title' => [
                'en' => 'System Alert',
                'fr' => 'Alerte Système',
                'es' => 'Alerta del Sistema'
              ],
              'message' => [
                'en' => "System update #$i completed",
                'fr' => "Mise à jour système #$i terminée",
                'es' => "Actualización del sistema #$i completada"
              ],
              'action_url' => '/',
              'icon' => 'business',
              'created_at' => $now->copy()->subDays($daysAgo)->toISOString(),
            ]),
            'read_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : null,
            'created_at' => $now->copy()->subDays($daysAgo),
            'updated_at' => $isRead ? $now->copy()->subDays($daysAgo - 1) : $now->copy()->subDays($daysAgo),
          ];
          break;
      }
    }

    // Insert all notifications
    DB::table('notifications')->insert($notifications);

    $this->command->info('✅ Created ' . count($notifications) . ' notifications for admin user.');
  }
}

