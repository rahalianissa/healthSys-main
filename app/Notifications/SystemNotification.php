<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use InvalidArgumentException;

class SystemNotification extends Notification
{
    use Queueable;

    protected $config;
    protected $payload;

    public function __construct(string $type, array $payload = [])
    {
        $this->config = $this->getConfig($type);
        $this->payload = $payload;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $mail = new MailMessage();
        $mail->subject($this->config['subject']);
        
        if (!empty($this->config['greeting'])) {
            $mail->greeting($this->replace($this->config['greeting'], $notifiable));
        }

        foreach ($this->config['lines'] as $line) {
            $mail->line($this->replace($line, $notifiable));
        }

        if (!empty($this->config['action_text']) && !empty($this->config['action_url'])) {
            $mail->action(
                $this->config['action_text'], 
                $this->replace($this->config['action_url'], $notifiable)
            );
        }

        if (!empty($this->config['closing'])) {
            $mail->salutation($this->config['closing']);
        }

        return $mail;
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => $this->config['type_key'],
            'title' => $this->config['subject'],
            'message' => $this->replace($this->config['db_message'] ?? '', $notifiable),
            'icon' => $this->config['icon'] ?? 'fa-bell',
            'url' => $this->replace($this->config['url'] ?? '#', $notifiable),
            'read_at' => null,
        ];
    }

    protected function getConfig(string $type): array
    {
        return [
            'doctor.invitation' => [
                'type_key' => 'doctor.invitation',
                'subject' => '🎉 Bienvenue sur HealthSys - Votre compte est activé',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Vous avez été ajouté comme médecin sur la plateforme HealthSys.',
                    '📧 Email de connexion: {{email}}',
                    '🔑 Votre mot de passe: {{password}}',
                ],
                'action_text' => 'Se connecter maintenant',
                'action_url' => '/login',
                'db_message' => 'Compte activé',
                'url' => '/login',
                'icon' => 'fa-user-plus',
                'closing' => "Cordialement,\nL'équipe HealthSys",
            ],
            'appointment.new' => [
                'type_key' => 'appointment.new',
                'subject' => '📅 Nouveau rendez-vous - HealthSys',
                'greeting' => 'Bonjour Dr. {{name}},',
                'lines' => [
                    'Un nouveau rendez-vous a été programmé :',
                    '👤 Patient: {{patient_name}}',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                ],
                'action_text' => 'Voir le rendez-vous',
                'action_url' => '/doctor/appointments/{{id}}',
                'db_message' => 'Nouveau rdv avec {{patient_name}}',
                'url' => '/doctor/appointments/{{id}}',
                'icon' => 'fa-calendar-plus',
            ],
            'appointment.new_for_secretary' => [
                'type_key' => 'appointment.new_for_secretary',
                'subject' => '📅 Nouveau rendez-vous à confirmer',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Un nouveau rendez-vous nécessite votre confirmation :',
                    '👤 Patient: {{patient_name}}',
                    '👨‍⚕️ Médecin: Dr. {{doctor_name}}',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                ],
                'action_text' => 'Confirmer',
                'action_url' => '/secretaire/appointments/{{id}}',
                'db_message' => 'RDV à confirmer: {{patient_name}}',
                'url' => '/secretaire/appointments/{{id}}',
                'icon' => 'fa-calendar-plus',
            ],
            'appointment.confirmed_for_patient' => [
                'type_key' => 'appointment.confirmed_for_patient',
                'subject' => '✅ Votre rendez-vous est confirmé',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Votre rendez-vous a été confirmé.',
                    '👨‍⚕️ Médecin: Dr. {{doctor_name}}',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                ],
                'action_text' => 'Voir mon rendez-vous',
                'action_url' => '/patient/appointments',
                'db_message' => 'RDV confirmé avec Dr. {{doctor_name}}',
                'url' => '/patient/appointments',
                'icon' => 'fa-calendar-check',
            ],
            'appointment.cancellation' => [
                'type_key' => 'appointment.cancellation',
                'subject' => '❌ Annulation de rendez-vous - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Votre rendez-vous a été annulé.',
                    '👨‍⚕️ Médecin: Dr. {{doctor}}',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                    '',
                    '📝 Motif: {{reason}}',
                ],
                'action_text' => 'Prendre un nouveau rendez-vous',
                'action_url' => '/patient/appointments',
                'db_message' => 'Rendez-vous annulé avec Dr. {{doctor}} le {{date}}',
                'url' => '/patient/appointments',
                'icon' => 'fa-calendar-xmark',
            ],
            'patient.new_registration' => [
                'type_key' => 'patient.new_registration',
                'subject' => '👤 Nouveau patient inscrit',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Un nouveau patient vient de s\'inscrire :',
                    '👤 Nom: {{patient_name}}',
                    '📧 Email: {{patient_email}}',
                    '📞 Téléphone: {{patient_phone}}',
                ],
                'action_text' => 'Voir le dossier',
                'action_url' => '/secretaire/patients/{{patient_id}}',
                'db_message' => 'Nouveau patient: {{patient_name}}',
                'url' => '/secretaire/patients/{{patient_id}}',
                'icon' => 'fa-user-plus',
            ],
        ][$type] ?? throw new InvalidArgumentException("Notification type '{$type}' not configured.");

    }
    

    protected function replace(string $text, $notifiable): string
    {
        $data = array_merge([
            'name' => $notifiable->name ?? 'Utilisateur',
        ], $this->payload);

        foreach ($data as $key => $value) {
            $text = str_replace("{{{$key}}}", $value ?? '', $text);
        }
        return $text;
    }
}