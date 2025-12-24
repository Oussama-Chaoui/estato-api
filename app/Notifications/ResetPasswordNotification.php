<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
  use Queueable;

  public $token;
  public $locale;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($token, $locale = null)
  {
    $this->token = $token;
    $this->locale = $locale ?? app()->getLocale();
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
    // Set the locale for this notification
    app()->setLocale($this->locale);

    // Encode email in a clean way - use base64 encoding for a cleaner URL
    $encodedEmail = base64_encode($notifiable->email);
    $resetUrl = url(env('APP_URL') . '/auth/reset-password/' . $this->token . '?e=' . $encodedEmail);
    
    return (new MailMessage)
      ->subject(__('passwords.email_subject'))
      ->line(__('passwords.email_line_1'))
      ->action(__('passwords.email_action'), $resetUrl)
      ->line(__('passwords.email_line_2'));
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
