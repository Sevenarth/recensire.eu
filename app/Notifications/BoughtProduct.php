<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\TestUnit;

class BoughtProduct extends Notification
{
    use Queueable;

    private $testUnit;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TestUnit $testUnit)
    {
        $this->testUnit = $testUnit;
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
                    ->subject((!empty($this->testUnit->tester) ? $this->testUnit->tester->name : 'Un tester') . ' ha comprato un prodotto!')
                    ->greeting('Ciao!')
                    ->line('Il tester ' . (!empty($this->testUnit->tester) ? $this->testUnit->tester->name : '-') . ' ('.(!empty($this->testUnit->tester) ? $this->testUnit->tester->email : '-').') ha appena accettato un test e comprato il prodotto associato.')
                    ->line('**Numero di ordine Amazon:** ' . $this->testUnit->amazon_order_id)
                    ->line('**Account PayPal:** ' . $this->testUnit->paypal_account)
                    ->action('Vai all\'unità di test', route('panel.testOrders.testUnits.view', $this->testUnit->id));
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
