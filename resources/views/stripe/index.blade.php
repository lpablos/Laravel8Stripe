<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Laravel 8 Stripe Payment Gateway Integration Example - LaraTutorials.com</title>
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
      <style>
         .container{
         padding: 0.5%;
         } 
      </style>
   </head>
   <body>
      <div class="container">
         <div class="row">
            <div class="col-md-12 mt-2 mb-2">
               <h3 class="text-center">Laravel 8 Stripe Payment Gateway Integration Example Tutorial</h3><hr>
            </div>            
            <div class="col-md-12 mt-2 mb-2">
               <pre id="res_token"></pre>
            </div>
         </div>
         <div class="row">
            @foreach ( $products as $product)
               <div class="col-md-4">
                  <button class="btn btn-primary btn-block" onclick="stripePay({{$product->id}},{{$product->price}})">{{$product->name}} ${{$product->price}}</button>
               </div>                
            @endforeach
            {{-- <div class="col-md-4">
               <button class="btn btn-success btn-block" onclick="stripePay(50,2)">Pay $50</button>
            </div>
            <div class="col-md-4">
               <button class="btn btn-info btn-block" onclick="stripePay(100,3)">Pay $100</button>
            </div> --}}
         </div>
      </div>
<script src = "https://checkout.stripe.com/checkout.js" > </script> 
<script type = "text/javascript">
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });

function stripePay(identy, amount) {
   // console.log(amount,identy);
    var handler = StripeCheckout.configure({
        key: 'pk_test_gsQNMTguJLdYgr3cR1h26mpF', // your publisher key id
        locale: 'auto',
        token: function(token) {
            // You can access the token ID with `token.id`.
            // Get the token ID to your server-side code for use.
            console.log('Token Created!!');
            console.log(token)
            $('#res_token').html(JSON.stringify(token));
            $.ajax({
                url: '{{ route("payment.process") }}',
                method: 'post',
                data: {
                    tokenId: token.id,
                    amount: amount,
                    identy: identy
                },
                success: (response) => {
                    console.log(response)
                },
                error: (error) => {
                    console.log(error);
                    alert('Oops! Something went wrong')
                }
            })
        }
    });
    handler.open({
         theme: 'night',
         name: 'Beneflex',
         description: '2 widgets',
         amount: amount*100
    });
} 
</script>
   </body>
</html>