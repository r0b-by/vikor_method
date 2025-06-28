<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class ProfileUpdateApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate->Notifications->Messages->MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Perubahan Profil Disetujui!')
                    ->line('Selamat ' . $this->user->name . ', perubahan profil Anda telah disetujui oleh administrator.')
                    ->action('Lihat Profil Anda', url('/users/' . $this->user->id . '/edit')) // Ganti dengan rute edit profil yang sesuai
                    ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'profile_update_approved',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => 'Perubahan profil Anda telah disetujui.',
        ];
    }
}
