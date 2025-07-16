<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\SmsTemplate;
use App\Utility\SmsUtility;
use App\Utility\NotificationUtility;
use Carbon\Carbon;
use DB;

class SendAirportMessages extends Command
{
    protected $signature = 'orders:send-airport-messages';
    protected $description = 'Send SMS when CN or BD airport time is due';

    public function handle()
    {
        $now = Carbon::now()->startOfMinute();
        $oneMinute = $now->copy()->addMinute();

        $orders = Order::whereHas('orderCost', function ($q) use ($now, $oneMinute) {
                $q->where(function ($sub) use ($now, $oneMinute) {
                    $sub->whereNotNull('cn_airport_message')
                        ->whereNull('cn_airport_msg_sent_at')
                        ->whereBetween('cn_airport_message', [$now, $oneMinute]);
                })->orWhere(function ($sub) use ($now, $oneMinute) {
                    $sub->whereNotNull('bd_airport_message')
                        ->whereNull('bd_airport_msg_sent_at')
                        ->whereBetween('bd_airport_message', [$now, $oneMinute]);
                });
            })
            ->with(['orderCost', 'user'])
            ->get();

        foreach ($orders as $order) {
            DB::beginTransaction();

            try {
                $orderCost = $order->orderCost;

               $cnDue = $orderCost->cn_airport_message &&
                         !$orderCost->cn_airport_msg_sent_at &&
                         $orderCost->cn_airport_message->between($now, $oneMinute);
                
                $bdDue = $orderCost->bd_airport_message &&
                         !$orderCost->bd_airport_msg_sent_at &&
                         $orderCost->bd_airport_message->between($now, $oneMinute);



                if (!$cnDue && !$bdDue) {
                    DB::rollBack();
                    continue;
                }

                if ($cnDue) {
                    $order->status = 'receive_in_china_airport';
                    $orderCost->cn_airport_msg_sent_at = now();
                    $orderCost->bd_airport_message = now()->addDays(3);
                } elseif ($bdDue) {
                    $order->status = 'receive_in_bangladesh_airport';
                    $orderCost->bd_airport_msg_sent_at = now();
                }

                $order->save();
                $orderCost->save();

                // Send SMS
                if (
                    addon_is_activated('otp_system') &&
                    optional(SmsTemplate::where('identifier', 'delivery_status_change')->first())->status == 1
                ) {
                    $phone = optional(json_decode($order->shipping_address))->phone;
                    SmsUtility::delivery_status_change($phone, $order);
                }

                // Notification
                NotificationUtility::sendNotification($order, $order->status);

                if (get_setting('google_firebase') == 1 && $order->user && $order->user->device_token) {
                    $payload = new \stdClass();
                    $payload->device_token = $order->user->device_token;
                    $payload->title = 'Order updated!';
                    $payload->text = "Your order {$order->code} has been {$order->status}.";
                    $payload->type = 'order';
                    $payload->id = $order->id;
                    $payload->user_id = $order->user->id;

                    NotificationUtility::sendFirebaseNotification($payload);
                }

                DB::commit();
                \Log::info("Message sent for Order ID: {$order->id}");
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Order SMS failed: " . $e->getMessage());
            }
        }

        return 0;
    }
}
