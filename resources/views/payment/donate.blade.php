<x-layout>
    <x-slot:pageheading>
        {{'Donation Page'}}
    </x-slot:pageheading>

    @if(!isset($orderId))
        <form action="{{ route('payment.initiate') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="amount">Donation Amount:</label>
                <input type="number" name="amount" id="amount" required class="form-control">
            </div>
            <div>
                <button type="submit" class="btn btn-success">Donate</button>
            </div>
        </form>
    @endif

    @if(isset($orderId))
        
        <button id="rzp-button1">Donate Now</button>

        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
        <script>
            var options = {
                "key": "{{ $razorpayId }}", 
                "amount": "{{ $amount * 100 }}", 
                "currency": "INR",
                "name": "Refreshed",
                "description": "Donation",
                "order_id": "{{ $orderId }}", 
                "handler": function (response){
                    $.ajax({
                        url: '{{ route('payment.success') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature
                        },
                        success: function (data) {
                            alert('Payment successful!');
                            window.location.href = "{{ route('donate') }}";
                        },
                        error: function (error) {
                            alert('Payment failed!');
                        }
                    });
                },
                "prefill": {
                    "name": "Your Name",
                    "email": "email@example.com"
                },
                "theme": {
                    "color": "#F37254"
                }
            };

            var rzp1 = new Razorpay(options);
            document.getElementById('rzp-button1').onclick = function(e){
                rzp1.open();
                e.preventDefault();
            }
        </script>
    @endif
</x-layout>
