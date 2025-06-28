<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\PendingProfileUpdate; // Import model

class ProfileUpdateSubmittedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $pendingUpdate;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $user Yang mengajukan perubahan
     * @param \App\Models\PendingProfileUpdate $pendingUpdate Detail perubahan
     * @return void
     */
    public function __construct(User $user, PendingProfileUpdate $pendingUpdate)
    {
        $this->user = $user;
        $this->pendingUpdate = $pendingUpdate;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database']; // Notifikasi via email dan disimpan di database
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
                    ->subject('Perubahan Profil Menunggu Konfirmasi')
                    ->line('Perubahan profil dari pengguna ' . $this->user->name . ' (' . $this->user->email . ') sedang menunggu konfirmasi Anda.')
                    ->line('Data yang diajukan:')
                    ->line('Nama Baru: ' . ($this->pendingUpdate->proposed_data['name'] ?? 'N/A'))
                    ->line('Email Baru: ' . ($this->pendingUpdate->proposed_data['email'] ?? 'N/A'))
                    ->action('Tinjau Perubahan Profil', url('/admin/pending-profile-updates')) // Anda akan membuat rute ini nanti
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
            'type' => 'profile_update_submitted',
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'pending_update_id' => $this->pendingUpdate->id,
            'message' => 'Perubahan profil dari ' . $this->user->name . ' menunggu konfirmasi.',
            'proposed_data' => $this->pendingUpdate->proposed_data,
        ];
    }
}
