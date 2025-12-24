<?php

namespace App\Observers;

use App\Enums\PROPERTY_STATUS;
use App\Models\Property;
use App\Models\User;
use App\Notifications\PropertyStatusChangeNotification;

class PropertyObserver
{
    /**
     * Handle the Property "created" event.
     */
    public function created(Property $property): void
    {
        // If property is created with SOLD status, set sold_at
        if ($property->status === PROPERTY_STATUS::SOLD->value) {
            $property->sold_at = now();
            $property->saveQuietly(); // Avoid triggering another update event
        }
    }

    /**
     * Handle the Property "updated" event.
     */
    public function updated(Property $property): void
    {
        $oldStatus = $property->getOriginal('status');
        $newStatus = $property->status;

        // Check if status changed to SOLD
        if ($property->wasChanged('status') && $property->status === PROPERTY_STATUS::SOLD->value) {
            $property->sold_at = now();
            $property->saveQuietly(); // Avoid triggering another update event
        }
        
        // Check if status changed from SOLD to something else
        if ($property->wasChanged('status') && $property->getOriginal('status') === PROPERTY_STATUS::SOLD->value) {
            $property->sold_at = null;
            $property->saveQuietly(); // Avoid triggering another update event
        }

        // Send notification if status changed
        if ($property->wasChanged('status') && $oldStatus !== $newStatus) {
            $this->sendPropertyStatusChangeNotification($property, $oldStatus, $newStatus);
        }
    }

    /**
     * Send notification for property status change
     */
    private function sendPropertyStatusChangeNotification(Property $property, string $oldStatus, string $newStatus): void
    {
        // Get admin users to notify
        $adminUsers = User::whereHas('roles', function ($query) {
            $query->where('name', \App\Enums\ROLE::ADMIN->value);
        })->get();

        $propertyData = [
            'id' => $property->id,
            'title' => $property->title['en'] ?? $property->title['fr'] ?? 'Property',
        ];

        // Send notification to each admin user (this will also broadcast via toBroadcast())
        foreach ($adminUsers as $user) {
            $user->notify(new PropertyStatusChangeNotification($propertyData, $oldStatus, $newStatus));
        }
    }
}
