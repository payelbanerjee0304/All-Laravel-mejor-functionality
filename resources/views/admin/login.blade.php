<x-layout>
    <x-slot:pageheading>
        {{'Admin Login'}}
    </x-slot:pageheading>
    <form  id="loginForm">
        @csrf
        @if(session('message'))
        <div class="alert alert-info">{{session('message')}}</div>
        @endif
        <div class="form-group">
            <label>Email</label>
            <input type="text" id="email" name="email" class="form-control">
            <div id="email_error" class="error"></div>
            @error('email')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="password" name="password" class="form-control">
            <div id="password_error" class="error"></div>
            @error('password')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group" id="otpDiv" style="display:none;">
            <label>One Time Password</label>
            {{-- <input type="number" name="otp" id="otp" class="form-control"> --}}
            <div class="otp-inputs" id="otp-form">
                <input type="text" name="field1" id="field1" class="otp-input" maxlength="1">
                <input type="text" name="field2" id="field2" class="otp-input" maxlength="1">
                <input type="text" name="field3" id="field3" class="otp-input" maxlength="1">
                <input type="text" name="field4" id="field4" class="otp-input" maxlength="1">
                <input type="text" name="field5" id="field5" class="otp-input" maxlength="1">
                <input type="text" name="field6" id="field6" class="otp-input" maxlength="1">
            </div>
        </div>
        <div id="sendOtp" class="form-group">
            <input type="submit" name="submit" value="Send OTP" class="btn btn-success btn-lg">
            <input type="reset" name="reset" value="Reset" class="btn btn-danger btn-lg">
        </div>
        <div  id="forgot">
            {{-- <a href="{{route('forget.password')}}">Forgot Password</a> --}}
        </div>
        <div id="finalSubmit" class="form-group" style="display:none;">
            <input type="submit" name="finalSubmitbutton" id="finalSubmitbutton" value="Submit" class="btn btn-success btn-lg login_btn">
            <input type="button" name="resend" id="resend" class="btn btn-success btn-lg login_btn" value="Resend" style="display: none;">Time left: <span id="timer"></span>
        </div>
    </form>
    <script>
        const form = document.querySelector("#otp-form");
        const inputs = document.querySelectorAll(".otp-input");
        const verifyBtn = document.querySelector(".login_btn");
        const isAllInputFilled = () => {
          return Array.from(inputs).every((item) => item.value);
        };
        const getOtpText = () => {
          let text = "";
          inputs.forEach((input) => {
            text += input.value;
          });
          return text;
        };
        const verifyOTP = () => {
          if (isAllInputFilled()) {
            const text = getOtpText();
            //alert(`Your OTP is "${text}". OTP is correct`);
          }
        };
        const toggleFilledClass = (field) => {
          if (field.value) {
            field.classList.add("filled");
          } else {
            field.classList.remove("filled");
          }
        };
        form.addEventListener("input", (e) => {
          const target = e.target;
          const value = target.value;
          //console.log({ target, value });
          toggleFilledClass(target);
          if (target.nextElementSibling) {
            target.nextElementSibling.focus();
          }
          verifyOTP();
        });
        inputs.forEach((input, currentIndex) => {
          // fill check
          toggleFilledClass(input);
          // paste event
          input.addEventListener("paste", (e) => {
            e.preventDefault();
            const text = e.clipboardData.getData("text");
            //console.log(text);
            inputs.forEach((item, index) => {
              if (index >= currentIndex && text[index - currentIndex]) {
                item.focus();
                item.value = text[index - currentIndex] || "";
                toggleFilledClass(item);
                verifyOTP();
              }
            });
          });
          // backspace event
          input.addEventListener("keydown", (e) => {
            if (e.keyCode === 8) {
              e.preventDefault();
              input.value = "";
              // console.log(input.value);
              toggleFilledClass(input);
              if (input.previousElementSibling) {
                input.previousElementSibling.focus();
              }
            } else {
              if (input.value && input.nextElementSibling) {
                input.nextElementSibling.focus();
              }
            }
          });
        });
        verifyBtn.addEventListener("click", () => {
          verifyOTP();
        });
      </script>
    <script>
        $(document).ready(function() {
           $('.text-danger').remove();
  
          var timerDisplay = $('#timer');
          var countdownDuration = 60; // 2 minutes in seconds
          var resend = $('#resend');
  
          function startCountdown() {
              var secondsLeft = countdownDuration;
  
              function updateTimerDisplay() {
                  var minutes = Math.floor(secondsLeft / 60);
                  var seconds = secondsLeft % 60;
  
                  // Format the time as MM:SS
                  var formattedTime = (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') +
                      seconds;
  
                  $('#timer').text(formattedTime);
              }
  
              updateTimerDisplay();
  
              var countdownInterval = setInterval(function() {
                  secondsLeft--;
  
                  updateTimerDisplay();
  
                  if (secondsLeft <= 0) {
  
                      clearInterval(countdownInterval);

                      $('#resend, #otpvia2').show();
                      $('#finalSubmitbutton, #field1, #field2, #field3, #field4, #field5, #field6, #vcodetext, #otpDiv')
                          .hide();
                      $('#field1, #field2, #field3, #field4, #field5, #field6').val('');
                  }
              }, 1000);
          }
  
           $('input[name="email"], input[name="password"]').on('input', function() {
              $(this).siblings('.text-danger').text('').css('color', 'black');
          });
  
            function showErrorSwal(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message,
                    customClass: {
                        popup: 'my-swal-popup',
                        icon: 'my-swal-icon',
                        title: 'my-swal-title',
                        content: 'my-swal-content',
                        text: 'my-swal-content',
                        confirmButton: 'my-swal-confirm-button'
                    }
                });
            }
            function handleFormSubmission(){
                let email = $('#email').val();
                 let password = $('#password').val();

                // Perform your Ajax request here
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.save') }}",
                    data: {
                          "_token": "{{ csrf_token() }}",
                          email:email,
                          password: password,
                    },
                    success: function(response) {
                       if(response.status=='error'){
                          showErrorSwal(response.message);
                             $('#email').prop('enabled', true);
                            $('#password').prop('enabled', true);
                            $('#otpDiv').hide();
                            $('#sendOtp').show();
                            $('#finalSubmit').hide();
                          // console.log(response);
  
                       }else{
                        
                            $('#email').prop('disabled', true);
                            $('#password').prop('disabled', true);
                            $('#otpDiv').show();
                            $('#forgot').hide();
                            $('#sendOtp').hide();
                            $('#finalSubmit').show();
                            
                            otp = response[0]['otp'];
                            // console.log(otp);
                            Swal.fire({
                                icon: 'success',
                                title: 'Your One-time password',
                                text: otp,
                                customClass: {
                                    popup: 'my-swal-popup',
                                    icon: 'my-swal-icon',
                                    title: 'my-swal-title',
                                    content: 'my-swal-content',
                                    text: 'my-swal-content',
                                    confirmButton: 'my-swal-confirm-button'
                                }
                            });
                          }
                          startCountdown();
                    },
                    error: function (error) {
                    // Clear existing error messages
                    $('.text-danger').remove();
  
                    // Display validation errors below each input field
                    
                    $.each(error.responseJSON.errors, function (field, messages) {
                        var inputField = $('#' + field);
                        inputField.after('<small class="text-danger">' + messages[0] + '</small>');
                    });
                }
                });
            }
            $("#loginForm").submit(function(e) {
                e.preventDefault();
                handleFormSubmission();
            });

            $('#resend').click(function() {
                $('#finalSubmitbutton, #field1, #field2, #field3, #field4, #field5, #field6, #vcodetext, #enphone')
                    .show();
                $('#resend').hide();
                handleFormSubmission();               
            });

            $(document).on('click', '#finalSubmitbutton', function(e) {
            e.preventDefault();
            // Getting values from input fields
            let email = $('#email').val();
            let password = $('#password').val();
            // let otp = $('#otp').val();
            var field1 = $('#field1').val();
            var field2 = $('#field2').val();
            var field3 = $('#field3').val();
            var field4 = $('#field4').val();
            var field5 = $('#field5').val();
            var field6 = $('#field6').val();
            var combinedValue = field1 + field2 + field3 + field4 + field5 + field6;
            $.ajax({
                type: 'POST',
                url: '{{ route("admin.verifyAndLogin") }}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    email:email,
                    password: password,
                    otp: combinedValue
                },
                success: function(response) {
                    // console.log(response);
                    // console.log(response.user[0].id);
                    // var idNumber = response.user[0].id;
                        // Redirect to the dashboard page with the phone number as a query parameter
                    window.location.href = '{{ url('/listing') }}'; //
                },
                error: function(xhr, status, error) {
                        var response = JSON.parse(xhr.responseText);
                        if (xhr.status === 404 && response.status === 'error') {
                            showErrorSwal(response.message);
                        } else {
                            $.each(response.errors, function(field, message) {
                                $('#' + field + '_error').text(message[0]);
                            });
                        }
                    }
            });
            });
        });
    </script>
    <style>
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
    
        .otp-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 24px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>    
</x-layout>