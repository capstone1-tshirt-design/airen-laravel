<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\OrderStatus;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShirtOption;
use Illuminate\Database\Eloquent\Builder;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Support\Facades\DB;

class PaypalController extends Controller
{
    protected $paypalClient;

    public function __construct()
    {
        $this->paypalClient = new PayPalClient;
    }

    public function create(Request $request)
    {
        $this->paypalClient->setApiCredentials(config('paypal'));
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
        $order = $this->paypalClient->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'PHP',
                        'value' => $request->amount
                    ],
                    'description' => $request->description
                ]
            ],
        ]);
        return response($order);
    }

    public function approve(Request $request)
    {
        $this->paypalClient->setApiCredentials(config('paypal'));
        $token = $this->paypalClient->getAccessToken();
        $this->paypalClient->setAccessToken($token);
        $result = $this->paypalClient->capturePaymentOrder($request->orderId);

        try {
            if ($result['status'] === 'COMPLETED') {
                DB::transaction(function () use ($request) {
                    $cartItems = $request->cartItems;
                    $customer = User::find($request->customer);
                    $orderStatus = OrderStatus::where('name', 'pending')->first();

                    $order = new Order;

                    $order->id = $request->orderId;
                    $order->customer()->associate($customer);
                    $order->status()->associate($orderStatus);
                    $order->save();

                    for ($i = 0; $i < count($cartItems); $i++) {
                        $cartItem = $cartItems[$i];
                        $orderItem = new OrderItem;
                        $product = Product::find($cartItem['id']);
                        $count = $product->whereRelation('categories', 'name', 'shirt')->count();
                        $orderItem->quantity = $cartItem['quantity'];
                        $orderItem->price = $cartItem['price'];

                        $orderItem->product()->associate($product);
                        $orderItem->order()->associate($order);

                        $orderItem->save();

                        if ($count > 0) {
                            $shirtOption = new ShirtOption;
                            $shirtOption->collar = $request->collar;
                            $shirtOption->shirt_length = $request->shirt_length;
                            $shirtOption->sleeve_length = $request->sleeve_length;
                            $shirtOption->shoulder = $request->shoulder;
                            $shirtOption->chest = $request->chest;
                            $shirtOption->tummy = $request->tummy;
                            $shirtOption->hips = $request->hips;
                            $shirtOption->cuff = $request->cuff;

                            $shirtOption->orderItem()->associate($orderItem);
                            $shirtOption->save();
                        }
                    }
                });
            }
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage()
            ], 500);
        }
        return response($result);
    }
}
