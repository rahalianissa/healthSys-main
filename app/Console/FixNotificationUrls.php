<?php

namespace App\Console\Commands;

use App\Models\Notification;
use Illuminate\Console\Command;

class FixNotificationUrls extends Command
{
    protected $signature = 'notifications:fix-urls';
    protected $description = 'Fix notification URLs for patient appointments';

    public function handle()
    {
        $notifications = Notification::where('type', 'LIKE', '%appointment%')->get();
        
        $count = 0;
        foreach ($notifications as $notification) {
            $data = $notification->data;
            $modified = false;
            
            // Corriger URL pour patient
            if (isset($data['url']) && str_contains($data['url'], '/appointments/') && !str_contains($data['url'], '/patient/')) {
                $data['url'] = str_replace('/appointments/', '/patient/appointments/', $data['url']);
                $modified = true;
            }
            
            // Corriger action_url si présent dans les données
            if (isset($data['action_url']) && str_contains($data['action_url'], '/appointments/') && !str_contains($data['action_url'], '/patient/')) {
                $data['action_url'] = str_replace('/appointments/', '/patient/appointments/', $data['action_url']);
                $modified = true;
            }
            
            if ($modified) {
                $notification->data = $data;
                $notification->save();
                $count++;
                $this->info("Fixed notification #{$notification->id}");
            }
        }
        
        $this->info("Fixed {$count} notifications.");
    }
}
