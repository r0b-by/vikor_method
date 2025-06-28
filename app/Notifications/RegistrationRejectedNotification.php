<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class RegistrationRejectedNotification extends Notification implements ShouldQueue
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
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
                    ->error() // Menambahkan warna merah/error
                    ->subject('Pendaftaran Anda Ditolak')
                    ->line('Maaf ' . $this->user->name . ', pendaftaran akun Anda di Project SPK Menyenangkan telah ditolak oleh administrator.')
                    ->line('Jika Anda memiliki pertanyaan, silakan hubungi kami.');
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
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'message' => 'Pendaftaran akun Anda telah ditolak.',
        ];
    }
}
