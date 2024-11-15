<x-layout>
    <x-slot:pageheading>
        {{'Company Details'}}
    </x-slot:pageheading>
    <form method="POST" action="{{route('company.submit')}}">
        @csrf
        @if(session('message'))
        <div class="alert alert-info">{{session('message')}}</div>
        @endif
        <div class="form-group">   
            <label for="">Name</label>
            <input type="text" name="name" id="name" class="form-control">
            <small id="name_error"></small>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="text" name="email" id="email" class="form-control">
            <small id="email_error"></small>
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="text" name="password" id="password" class="form-control">
            <small id="password_error"></small>
        </div>
        <div class="form-group">
            <label for="">Phone</label>
            <input type="text" name="phone" id="phone" inputmode="tel" class="form-control">
            <small id="phone_error"></small>
        </div>
        {{-- <div class="form-group">
            {!! NoCaptcha::display() !!}
        </div> --}}
        
        <!-- CAPTCHA section -->
        <div class="form-group">
            <label for="captcha">Enter the CAPTCHA</label>
            <br>
            <img src="{{ route('captcha.image') }}" alt="CAPTCHA" id="captcha-image">
            <button type="button" id="refresh-captcha" class="btn btn-sm" style="margin-left: 10px;"><i class="fa fa-sync-alt" style="font-size:24px;color:red"></i></button>
            <br><br>
            <input type="text" name="captcha" id="captcha" class="form-control">
        </div>
        <div>
            <input type="submit" name="submit" id="submit" class="btn btn-success">
        </div>
    </form> 
    <script>
        $('#refresh-captcha').click(function() {
            $('#captcha-image').attr('src', '{{ route('captcha.image') }}' + '?' + Date.now());
        });
    </script>
    {{-- Display the reCAPTCHA script --}}
    {{-- {!! NoCaptcha::renderJs() !!} --}}
</x-layout>