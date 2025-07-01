<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User; // Import model User

class NewUserRegisteredNotification extends Notification implements ShouldQueue 
{
    use Queueable;

    protected $newUser;

    /**
     * Create a new notification instance.
     *
     * @param \App\Models\User $newUser
     * @return void
     */
    public function __construct(User $newUser)
    {
        $this->newUser = $newUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Pendaftaran Pengguna Baru Menunggu Konfirmasi')
                    ->line('Pengguna baru telah mendaftar dan menunggu konfirmasi Anda.')
                    ->line('Nama: ' . $this->newUser->name)
                    ->line('Email: ' . $this->newUser->email)
                    ->action('Lihat Pendaftaran Menunggu', url('/admin/pending-registrations')) 
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
            'user_id' => $this->newUser->id,
            'user_name' => $this->newUser->name,
            'user_email' => $this->newUser->email,
            'message' => 'Pengguna baru ' . $this->newUser->name . ' telah mendaftar dan menunggu konfirmasi.',
        ];
    }
}

