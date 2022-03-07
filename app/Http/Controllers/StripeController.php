<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Session;
use Stripe;


class StripeController extends Controller
{
    //


    public function index()
    {
      return view('stripe.index');
    }
   public function process(Request $request)
   {
    
    //  dd("Aqui", $request->all(), $request->get('tokenId'), $request->get('amount'));
        // dd($request->all()) 
        // HAcer consultas con el id -> precio * 100
        // descripcion del prodcuto de la base (id+descripcion)
        // metadata 
       $stripe = Stripe::charges()->create([
           'source' => $request->get('tokenId'),
           'currency' => 'MXN',
           'amount' => $request->get('amount') * 100, 
           "metadata" => ["order_id" => "6735"],
           'description' => 'My First Test Charge (created for API docs)',
       ]);

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
