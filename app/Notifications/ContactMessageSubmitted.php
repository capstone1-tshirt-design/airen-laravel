<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    private $userData;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
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
        if (!array_key_exists('user', $this->userData)) {
            return (new MailMessage)
                ->subject('New contact message from ' . $this->userData['name'])
                ->greeting('Details: ')
                ->line('Name: ' . $this->userData['name'])
                ->line('Email: ' . $this->userData['email'])
                ->line('Subject: ' . $this->userData['subject'])
                ->line('Message: ' . $this->userData['message'])
                ->markdown('mail.contact');
        } else {
            return (new MailMessage)
                ->subject('Your message has been sent')
                ->greeting('Hi ' . $this->userData['name'] . ', ')
                ->line('We will check your message as soon as possible')
                ->line('Details: ')
                ->line('Name: ' . $this->userData['name'])
                ->line('Email: ' . $this->userData['email'])
                ->line('Subject: ' . $this->userData['subject'])
                ->line('Message: ' . $this->userData['message'])
                ->markdown('mail.contact');
        }
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
            //
        ];
    }
}
