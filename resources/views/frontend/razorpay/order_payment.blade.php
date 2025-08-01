<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" href="{{ static_asset('assets/css/vendors.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-pkd.css') }}">
    <link rel="stylesheet" href="{{ static_asset('assets/css/custom-style.css') }}">
</head>
<body>
    <section class="py-4 mb-4 bg-light">
        <div class="container text-center">

            <form action="{!!route('api.razorpay.payment')!!}" method="POST" id='rozer-pay' style="display: none;">
                <!-- Note that the amount is in paise = 50 INR -->
                <!--amount need to be in paisa-->
                <script src="https://checkout.razorpay.com/v1/checkout.js"
                        data-key="{{ env('RAZOR_KEY') }}"
                        data-amount={{round($combined_order->grand_total) * 100}}
                        data-order_id="{{ $res->id }}"
                        data-buttontext=""
                        data-name="{{ env('APP_NAME') }}"
                        data-description="Cart Payment"
                        data-image="{{ uploaded_asset(get_setting('header_logo')) }}"
                        data-prefill.name= "{{ $user->name}}"
                        data-prefill.email= "{{ $user->email}}"
                        data-theme.color="#ff7529">
                </script>
                <input type="hidden" name="_token" value="{!!csrf_token()!!}">
            </form>
        </div>
    </section>

    <!-- SCRIPTS -->
    <script src="{{ static_asset('assets/js/vendors.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#rozer-pay').submit()
        });
    </script>
</body>
</html>
