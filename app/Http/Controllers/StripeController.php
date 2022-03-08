<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Product;
use Session;
use Stripe;
use Carbon\Carbon;


class StripeController extends Controller
{
    //


    public function index()
    {
        $products = Product::all();        
        return view('stripe.index', compact('products'));
    }
   public function process(Request $request)
   {
        // Hacer consultas con el id -> precio * 100
        // descripcion del prodcuto de la base (id+descripcion)
        // metadata 
        
        $product = Product::find($request['identy']);
   
        $stripe = Stripe::charges()->create([
            'source' => $request->get('tokenId'),
            'currency' => 'MXN',
            'amount' => $product->price, 
            "metadata" => ["product_id" => $product->id],
            'description' => 'My First Test Charge (created for API docs)',
        ]);
        
        $response = json_encode($stripe);
        $payment = new Payment;
        $payment->amount = ($stripe['amount']/100);
        $payment->billing_details_name = $stripe['billing_details']['name'];
        $payment->created = Carbon::parse($stripe['created']);
        $payment->currency = $stripe['currency'];
        $payment->stripe_id = $stripe['id'];
        $payment->payment_method = $stripe['payment_method'];
        $payment->payment_method_card_fingerprint = $stripe['payment_method_details']['card']['fingerprint'];
        $payment->status = $stripe['status'];
        $payment->outcome_network_status = $stripe['outcome']['network_status'];
        $payment->outcome_reason = $stripe['outcome']['reason'] ?: '';
        $payment->outcome_seller_message = $stripe['outcome']['seller_message'];
        $payment->metadata_product_id = $stripe['metadata']['product_id'];
        $payment->source_id =$stripe['source']['id'];
        $payment->response =$response;   
        $payment->save();

        
        // $stripe = Stripe::charges()->create([
        //     'source' => $request->get('tokenId'),
        //     'currency' => 'MXN',
        //     'amount' => $request->get('amount') * 100, 
        //     "metadata" => ["order_id" => "6735"],
        //     'description' => 'My First Test Charge (created for API docs)',
        // ]);

       //Order id -> respuesta de la transaccion
       // Armar una tabla de pagos
       // 
       //
 
       return $stripe;
   }

    public function pagos()
    {
        return view('stripe.pagos');
    }
    public function payStripe(Request $request)
    {
        $this->validate($request, [
            'card_no' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'cvv' => 'required',
        ]);
        
        $stripe = Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try {            
          
            $stripe_obj =  \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $response = \Stripe\Token::create(array(
                "card" => array(
                    "number"    => $request->input('card_no'),
                    "exp_month" => $request->input('expiry_month'),
                    "exp_year"  => $request->input('expiry_year'),
                    "cvc"       => $request->input('cvv')
                )));
            
            if (!isset($response['id'])) {
                return redirect()->route('addmoney.paymentstripe');
            }

            $charge = \Stripe\Charge::create([
                'card' => $response['id'],
                'currency' => 'USD',
                'amount' =>  100 * 100,
                'description' => 'wallet',
            ]);

            
            if($charge['status'] == 'succeeded') {
                return redirect('pagos')->with('success', 'Payment Success!');
 
            } else {
                return redirect('pagos')->with('error', 'something went to wrong.');
            }
 
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
 
    }
}
