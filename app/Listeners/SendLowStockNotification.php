<?php

namespace App\Listeners;

use App\Events\LowStockEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\LowStockMail;
use App\Notifications\LowStockNotification;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
class SendLowStockNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LowStockEvent $event): void
    {
        $product = $event->product;
        // 1. Encontrar al administrador de la tienda
        $user = User::where('store_id', $product->store_id)
        ->where('role', 'admin')
        ->first();  

        if ($user){
            Log::error("No se encontró un administrador para la tienda ID: {$product->store_id} para enviar alerta de stock.");
            return;
        }

        // 2. Enviar notificación por correo electrónico
        Mail::to($user->email)->send(new LowStockMail($product));

        // 3. Enviar notificación por SMS
        $user->notify(new LowStockNotification($product));      
    }
}
