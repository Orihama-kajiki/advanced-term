<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail;

class RegistrationConfirmation extends VerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->greeting('Reseアカウント登録を受け付けました')
            ->line('Reseに登録いただきありがとうございます。メールアドレスの認証を行うために、以下のボタンをクリックしてください。')
            ->action('メールアドレスを認証する', $this->verificationUrl($notifiable))
            ->line('有効期限は24時間です。期限内に上記のボタンをクリックし、メールアドレスを認証してください。')
            ->line('もし認証が完了しない場合や、このメールに覚えがない場合は、お手数ですがサポートまでお問い合わせください。')
            ->salutation('Rese サポート');
    }
}
