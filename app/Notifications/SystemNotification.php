<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\SystemNotification; 
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
        return ['mail', 'database']; // Add 'sms', 'slack', etc. if needed
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

        // ✅ Use salutation() instead of closing()
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
            // 👨‍⚕️ DOCTOR INVITATION
            'doctor.invitation' => [
                'type_key' => 'doctor.invitation',
                'subject' => '🎉 Bienvenue sur HealthSys - Votre compte est activé',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Vous avez été ajouté comme médecin sur la plateforme HealthSys.',
                    '',
                    '📧 Email de connexion: {{email}}',
                    '🔑 Votre mot de passe: {{password}}',
                    '',
                    '💡 Vous pouvez modifier votre mot de passe à tout moment depuis votre profil.',
                ],
                'action_text' => 'Se connecter maintenant',
                'action_url' => '/login',
                'db_message' => 'Compte activé. Identifiants envoyés par email.',
                'url' => '/login',
                'icon' => 'fa-user-plus',
                'closing' => "Cordialement,\nL'équipe HealthSys", // ✅ This works with salutation()
            ],

            // 🩺 NEW APPOINTMENT FOR DOCTOR
            'appointment.new' => [
                'type_key' => 'appointment.new',
                'subject' => '📅 Nouveau rendez-vous assigné - HealthSys',
                'greeting' => 'Bonjour Dr. {{name}},',
                'lines' => [
                    'Un nouveau rendez-vous a été programmé :',
                    '👤 Patient: {{patient_name}}',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                    '📝 Motif: {{reason}}',
                ],
                'action_text' => 'Voir le rendez-vous',
                'action_url' => '/doctor/appointments/{{id}}',
                'db_message' => 'Nouveau rdv avec {{patient_name}} le {{date}}',
                'url' => '/doctor/appointments/{{id}}',
                'icon' => 'fa-calendar-plus',
            ],


            // 📅 APPOINTMENTS
            'appointment.confirmation' => [
                'type_key' => 'appointment.confirmation',
                'subject' => 'Confirmation de rendez-vous - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Votre rendez-vous a été confirmé avec succès.',
                    ' Date: {{date}}',
                    ' Heure: {{time}}',
                    '‍⚕️ Médecin: Dr. {{doctor}}',
                ],
                'action_text' => 'Voir le rendez-vous',
                'action_url' => '/appointments/{{id}}',
                'db_message' => 'Rendez-vous confirmé le {{date}} à {{time}}',
                'url' => '/appointments/{{id}}',
                'icon' => 'fa-calendar-check',
            ],
            'appointment.reminder' => [
                'type_key' => 'appointment.reminder',
                'subject' => 'Rappel de rendez-vous - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Vous avez un rendez-vous prévu demain :',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                    '👨‍⚕️ Médecin: Dr. {{doctor}}',
                ],
                'action_text' => 'Confirmer ma présence',
                'action_url' => '/appointments/{{id}}/confirm',
                'db_message' => 'Rappel: Rdv demain à {{time}}',
                'url' => '/appointments/{{id}}',
                'icon' => 'fa-clock',
            ],
            'appointment.cancellation' => [
                'type_key' => 'appointment.cancellation',
                'subject' => 'Annulation de rendez-vous - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Votre rendez-vous a été annulé.',
                    '📅 Date: {{date}}',
                    '🕐 Heure: {{time}}',
                    '👨‍⚕️ Médecin: Dr. {{doctor}}',
                ],
                'action_text' => 'Prendre un nouveau rendez-vous',
                'action_url' => '/patient/appointments/book',
                'db_message' => 'Rendez-vous annulé',
                'url' => '/patient/appointments',
                'icon' => 'fa-calendar-xmark',
            ],

            //  MEDICAL RESULTS
            'result.available' => [
                'type_key' => 'result.available',
                'subject' => 'Résultats médicaux disponibles - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Vos résultats d\'analyse sont maintenant disponibles.',
                    '📄 Type: {{result_type}}',
                    '🏥 Laboratoire: {{lab_name}}',
                ],
                'action_text' => 'Consulter les résultats',
                'action_url' => '/patient/medical-record/{{consultation_id}}',
                'db_message' => 'Résultats disponibles: {{result_type}}',
                'url' => '/patient/medical-record/{{consultation_id}}',
                'icon' => 'fa-file-medical',
            ],

            // 💬 COMMUNICATION
            'message.new' => [
                'type_key' => 'message.new',
                'subject' => 'Nouveau message - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Vous avez reçu un nouveau message de {{sender}}.',
                    '📝 Aperçu: {{preview}}',
                ],
                'action_text' => 'Lire le message',
                'action_url' => '/messages/{{message_id}}',
                'db_message' => 'Nouveau message de {{sender}}',
                'url' => '/messages/{{message_id}}',
                'icon' => 'fa-envelope',
            ],

            // 💰 ADMINISTRATIVE
            'invoice.due' => [
                'type_key' => 'invoice.due',
                'subject' => 'Rappel de paiement - HealthSys',
                'greeting' => 'Bonjour {{name}},',
                'lines' => [
                    'Un paiement est en attente pour le montant de {{amount}} DH.',
                    '📄 Facture #: {{invoice_number}}',
                    '📅 Date limite: {{due_date}}',
                ],
                'action_text' => 'Payer maintenant',
                'action_url' => '/invoices/{{invoice_id}}/pay',
                'db_message' => 'Paiement en attente: {{amount}} DH',
                'url' => '/invoices/{{invoice_id}}/pay',
                'icon' => 'fa-credit-card',
            ],
            
            //  Add new types here by copying a block and changing keys
        ][$type] ?? throw new InvalidArgumentException("Notification type '{$type}' not configured in SystemNotification.");
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