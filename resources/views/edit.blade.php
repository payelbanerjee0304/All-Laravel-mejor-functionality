<x-layout>
    <x-slot:pageheading>
        {{'Edit user profile'}}
    </x-slot:pageheading>
    <form id="registrationForm">
        @csrf
        @if(isset($user))

        <input type="text" name="uid" id="uid" value="{{$user->id}}" hidden>
        <div class="form-group">   
            <label for="">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}">
            <small id="name_error"></small>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}">
            <button id="change">Change</button>
            <div id="otpSection" style="display: none;">
                <p>Email verification code sent to your email.</p>
                <input type="text" id="otp" class="form-control" placeholder="Enter OTP">
                <button id="verify">Verify</button>
            </div>
            <div id="verifiedMessage" style="display: none;">
                <em>Email verified <span style="color: green;">✔</span></em>
            </div>
            <small id="email_error"></small>
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input type="text" name="password" id="password" class="form-control" value="{{$user->password}}">
            <small id="password_error"></small>
        </div>
        <div class="form-group">
            <label for="">Phone</label>
            <input type="text" name="phone" id="phone" inputmode="tel" class="form-control" value="{{$user->phone}}">
            <button id="changePhone">Change</button>
            <div id="otpSectionPhone" style="display: none;">
                <p>OTP has been sent to your phone number.</p>
                <input type="text" id="phoneOtp" class="form-control" placeholder="Enter OTP">
                <button id="verifyPhoneOtp">Verify</button>
            </div>
            <div id="verifiedMessagePhone" style="display: none;">
                <em>Phone number verified <span style="color: green;">✔</span></em>
            </div>
            <small id="phone_error"></small>
        </div>
        <div class="form-group">
            <label for="">State</label>
            <select name="state" id="state" class="form-control">
                @if($state)
                    <option value="{{ $state->_id }}">{{ $state->name }}</option>
                @endif
                <option value="">Select</option>
                @foreach($stateDB as $state)
                <option value="{{$state['_id']}}">{{$state['name']}}</option>
                @endforeach
            </select>
            <small id="state_error"></small>
        </div>
        <div class="form-group" id="districtdiv" >
            <label for="">District</label>
            <select name="district" id="district" class="form-control">
                @if($district)
                    <option value="{{ $district->_id }}">{{ $district->name }}</option>
                @endif
                <option value="">Select</option>
                {{-- @foreach($districtDB as $district)
                <option value="{{$district['_id']}}">{{$district['name']}}</option>
                @endforeach --}}
            </select>
            <small id="district_error"></small>
        </div>
        <div class="form-group" id="citydiv" >
            <label for="">City</label>
            <select name="city" id="city" class="form-control">
                @if($city)
                    <option value="{{ $city->_id }}">{{ $city->name }}</option>
                @endif
                <option value="">Select</option>
                {{-- @foreach($cityDB as $city)
                <option value="{{$city['_id']}}">{{$city['name']}}</option>
                @endforeach --}}
            </select>
            <small id="city_error"></small>
        </div>
        <div class="form-group">
            <label for="">Gender</label>
            <label class="btn btn-secondary">
                <input type="radio" id="gender_male" name="gender" class="gender" autocomplete="off" value="Male"@if($user->gender=="Male") checked @endif>Male
                </label>
                <label class="btn btn-secondary">
                <input type="radio" id="gender_female" name="gender" class="gender" autocomplete="off" value="Female"@if($user->gender=="Female") checked @endif>Female
                </label>
                <label class="btn btn-secondary">
                <input type="radio" id="gender_others" name="gender" class="gender" autocomplete="off" value="Others"@if($user->gender=="Others") checked @endif>Others
            </label><br>
            <small id="gender_error"></small>
        </div>
        @php
			$language=$user->language;
		@endphp
        <div class="form-group">
            <input type="checkbox" name="language[]" class="language" id="language" class="language button btn-secondary" value="English"@if(in_array('English', $language)) checked @endif>English
            <input type="checkbox" name="language[]" class="language" id="language" class="language button btn-secondary" value="Hindi"@if(in_array('Hindi', $language)) checked @endif>Hindi
            <input type="checkbox" name="language[]" class="language" id="language" class="language button btn-secondary" value="Bengali"@if(in_array('Bengali', $language)) checked @endif>Bengali
            <br>
            <small id="language_error"></small>
        </div>
        <div class="form-group">
            <label for="">Profile Picture</label>
            <input type="file" name="profilePicture" id="profilePicture" class="form-control">
            <small id="profile_picture_error"></small>
            <img id="current_profile_picture" src="{{$user->profile_picture}}" height="100">
        </div>
        <div class="form-group">
            <label for="">All Images</label>
            <input type="file" name="images[]" id="images" class="form-control" multiple>
            <small id="images_error"></small>
            <div id="selected_images"></div>
            <div id="current_images">
                @foreach ($user->images as $image)
                    <img src="{{$image}}" height="100">
                @endforeach
            </div>
        </div>
        <div>
            <input type="submit" name="submit" id="submit" class="btn btn-success">
        </div>
        @endif
    </form>
    {{-- <!-- Modal for cropping image -->
    <div id="cropModal" style="display: none;">
        <img id="cropImage" style="max-width: 100%;">
        <button id="cropButton">Crop</button>
    </div> --}}
    <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-labelledby="cropModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropModalLabel">Crop Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="image_to_crop" src="" alt="Image to crop">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="crop_button">Crop</button>
                </div>
            </div>
        </div>
    </div>
    {{-- pp --}}
    <div class="modal fade" id="cropProfilePictureModal" tabindex="-1" role="dialog" aria-labelledby="cropProfilePictureModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cropProfilePictureModalLabel">Crop Profile Picture</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="profile_image_to_crop" src="" alt="Profile Picture to crop">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="crop_profile_button">Crop</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>

$(document).ready(function(){

    function changeEmail(){
        let email= $('#email').val();
        
    }
    

    function validateName() {
        let name = $('#name').val().trim();
        if (name === '') {
            $("#name_error").text('Name Field is Required').css('color','red');
            return false;
        } else {
            $("#name_error").text('');
            return true;
        }
    }

    function validateEmail() {
        let email = $('#email').val();
        let emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$/i;
    
        if (email.trim() === '') {
            $("#email_error").text('Email Field is Required').css('color','red');
            return false;
        } else if (!emailRegex.test(email)) {
            $("#email_error").text('Invalid Email Address').css('color', 'red');
            return false;
        } else {
            $("#email_error").text('');
            return true;
        }
    }

    // function validatePhone() {

    //     let phone = $('#phone').val().trim();
    //     let phoneRegex=/^\d{10}$/;

    //     if (phone === '') {
    //         $("#phone_error").text('Phone Field is Required').css('color','red');
    //         return false;
    //     } else if (!phoneRegex.test(phone)) {
    //         $('#phone_error').text('Phone number must be 10 digits').css('color', 'red');
    //     } else {
    //         $("#phone_error").text('');
    //         return true;
    //     }
    // } 
    function validatePassword() {
        let password = $('#password').val().trim();
        // let passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+])[0-9a-zA-Z!@#$%^&*()_+]{8,}$/;
        let errors = [];
        if (!/(?=.*[a-z])/.test(password)) {
            errors.push('one lowercase letter');
        }
        if (!/(?=.*[A-Z])/.test(password)) {
            errors.push('one uppercase letter');
        }
        if (!/(?=.*\d)/.test(password)) {
            errors.push('one digit');
        }
        if (!/(?=.*[!@#$%^&*()_+])/.test(password)) {
            errors.push('one special character');
        }

        let passwordRegex= /^.{8}$/;

        if (password === '') {
            $("#password_error").text('Password Field is Required').css('color','red');
            return false;
        // } else if (!passwordRegex.test(password)) {
        //     $("#password_error").text('Password must be alphanumeric with at least one uppercase letter, one lowercase letter, one digit, and one special character').css('color','red');
        //     return false;
        }else if (errors.length > 0) {
            let errorMessage = 'Password must have at least ';
            errorMessage += errors.join(', ');
            errorMessage += '.';
            $("#password_error").text(errorMessage).css('color','red');
            return false;
        }else if(!passwordRegex.test(password)){
            $("#password_error").text("Password must have 8 charecter").css('color','red');
        }
        else {
            $("#password_error").text('');
            return true;
        }
    }

    function validateState() {
        let state = $('#state').val();
        if (state === '') {
            $("#state_error").text('State Field is Required').css('color','red');
            return false;
        } else {
            $("#state_error").text('');
            return true;
        }
    }
    function validateDistrict() {
        let district = $('#district').val();
        if (district === '') {
            $("#district_error").text('District Field is Required').css('color','red');
            return false;
        } else {
            $("#district_error").text('');
            return true;
        }
    }
    function validateCity() {
        let city = $('#city').val();
        if (city === '') {
            $("#city_error").text('City Field is Required').css('color','red');
            return false;
        } else {
            $("#city_error").text('');
            return true;
        }
    }
    $('#state').change(function() {
        let stateId = $(this).val();
        // console.log(stateId);
        validateState();
        if (stateId) {
            $.ajax({
                type: "GET",
                url: "{{ route('getDistricts', '') }}/" + stateId,
                success: function(districts) {
                    // console.log(districts);
                    $('#district').empty().append('<option value="">Select</option>');
                    if(districts.length>0){
                        $.each(districts, function(index, district) {
                            $('#district').append('<option value="' + district._id + '">' + district.name + '</option>');
                        });
                        $('#city').empty().append('<option value="">Select</option>'); // Clear city dropdown
                        $("#districtdiv").attr("hidden",false);
                    }
                    else{
                        $("#districtdiv").attr("hidden",true);
                        $("#citydiv").attr("hidden",true);
                    }
                }
            });
        } else {
            $('#district').empty().append('<option value="">Select</option>');
            $('#city').empty().append('<option value="">Select</option>');
        }
    });

    $('#district').change(function() {
        let districtId = $(this).val();
        // console.log(districtId);
        validateDistrict();
        if (districtId) {
            $.ajax({
                type: "GET",
                url: "{{ route('getCities', '') }}/" + districtId,
                success: function(cities) {
                    // console.log(cities);
                    $('#city').empty().append('<option value="">Select</option>');
                    if(cities.length>0){
                        $.each(cities, function(index, city) {
                            $('#city').append('<option value="' + city._id + '">' + city.name + '</option>');
                            $("#citydiv").attr("hidden",false);
                        });
                    }
                    else{
                        $("#citydiv").attr("hidden",true);
                    }
                }
            });
        } else {
            $('#city').empty().append('<option value="">Select</option>');
        }
    });

    $('#city').change(function(){
        validateCity();
    })

    function validateGender() {
        let gender = $('input[name="gender"]:checked').val();
        if ($('.gender:checked').length === 0) {
            $("#gender_error").text('Gender Field is Required').css('color','red');
            return false;
        } else {
            $("#gender_error").text('');
            return true;
        }
    }

    function validateLanguage(){
        if ($('.language:checked').length === 0) {
            $("#language_error").text('Select at least one Language').css('color', 'red');
            return false;
        } else {
            $("#language_error").text('');
            return true;
        }
    }

    function validateProfilePicture() {
        let image = $('#profilePicture').val();
        if (image === '') {
            $("#profile_picture_error").text('Profile Picture Field is Required').css('color','red');
            return false;
        } else {
            $("#profile_picture_error").text('');
            return true;
        }
    }
    
    function validateImages() {
        let images = $('#name').val();
        if (images === '') {
            $("#images_error").text('Images Field is Required').css('color','red');
            return false;
        } else {
            $("#images_error").text('');
            return true;
        }
    }

    $('#name').keyup(function(){
        validateName();
    });
    $('#email').keyup(function(){
        validateEmail();
    });
    // $('#phone').on('input', function() {
    //     let sanitized = $(this).val().replace(/\D/g, '');
    //     if (sanitized.length > 10) {
    //         sanitized = sanitized.substring(0, 10);
    //     }
    //     $(this).val(sanitized);
    //     validatePhone();
    // });
    
    $('#password').on('input', function(){
        let sanitized = $(this).val(); // No need to remove non-alphanumeric characters
        if (sanitized.length > 8) {
            sanitized = sanitized.substring(0, 8); // Truncate to 8 characters
        }
        $(this).val(sanitized);
        validatePassword();
    });

    $('.gender').change(function(){
        validateGender();
    })
    $('.language').change(function(){
        validateLanguage();
    })

    // $('#profilePicture').change(function(){
    //     validateProfilePicture();
    // })

    // $('#images').change(function() {
    //     validateImages();
    // });
    
    //bb
    let cropper;
    let croppedImages = [];
    let allImages = [];
    let currentImageIndex;
    let selectedFiles = []; // Array to keep track of all selected files
    let fileInput = document.getElementById('images');

    // Function to add an image with a remove button
    function addImageWithRemoveButton(src, index) {
        let container = $('<div>', {
            class: 'image-container',
            'data-index': index,
        });
        let imgElement = $('<img>', {
            src: src,
            class: 'croppable-image',
            click: function () {
                currentImageIndex = index;
                $('#image_to_crop').attr('src', src);
                $('#cropModal').modal('show');
            }
        });
        let removeButton = $('<span>', {
            text: '×',
            class: 'remove-button',
            click: function () {
                container.remove();
                delete allImages[index]; // Remove the original image
                delete croppedImages[index]; // Remove the cropped image
                selectedFiles.splice(index, 1); // Remove from selected files
                updateFileInput();
            }
        });

        container.append(imgElement).append(removeButton);
        $('#selected_images').append(container);
    }

    // Function to update the input files list
    function updateFileInput() {
        let dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        fileInput.files = dt.files;

        // Trigger validation if no files are selected
        if (fileInput.files.length === 0) {
            
            fileInput.reportValidity();
        } else {
            fileInput.setCustomValidity("");
        }
    }

    // Function to convert dataURL to Blob
    function dataURLToBlob(dataURL) {
        let byteString = atob(dataURL.split(',')[1]);
        let mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
        let ab = new ArrayBuffer(byteString.length);
        let ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: mimeString });
        
    }

    $('#images').on('change', function(e) {
        $('#current_images').hide();
        
        Array.from(e.target.files).forEach((file, index) => {
            selectedFiles.push(file); // Add to selected files array
            let reader = new FileReader();
            reader.onload = function(event) {
                addImageWithRemoveButton(event.target.result, allImages.length);
                allImages.push(event.target.result); // Store the original image
                croppedImages.push(null); // Placeholder for the cropped image
            }
            reader.readAsDataURL(file);
        });
        updateFileInput(); // Update the input element
    });

    $('#cropModal').on('shown.bs.modal', function() {
        let image = document.getElementById('image_to_crop');
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 1,
                autoCropArea: 0.5
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
    });

    $('#crop_button').click(function() {
        cropper.getCroppedCanvas().toBlob((blob) => {
            let reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => {
                let base64data = reader.result;
                croppedImages[currentImageIndex] = base64data;
                // Use data-index attribute to find the correct container
                $('#selected_images .image-container[data-index="' + currentImageIndex + '"] img').attr('src', base64data);
                $('#cropModal').modal('hide');
            }
        });
    });

    //pp
    let profilePictureCropper;
    let croppedProfilePicture;

    // Function to initialize Cropper for profile picture
    function initProfilePictureCropper(imageSrc) {
        let image = document.getElementById('profile_image_to_crop');

        if (profilePictureCropper) {
            profilePictureCropper.destroy();
        }
        image.src = imageSrc;
        
        profilePictureCropper = new Cropper(image, {
            aspectRatio: NaN, // Allow free aspect ratio cropping
            viewMode: 1,
            autoCropArea: 1,
            crop(event) {
                // Optional: You can add crop event handling here
            },
        });
    }

    // Show profile picture cropping modal on file selection
    $('#profilePicture').change(function (e) {
        $('#current_profile_picture').hide();
        if (e.target.files && e.target.files.length > 0) {
            let reader = new FileReader();
            reader.onload = function (event) {
                $('#profile_image_to_crop').attr('src', event.target.result);
                $('#cropProfilePictureModal').modal('show');
                initProfilePictureCropper(event.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    $('#crop_profile_button').click(function () {
        profilePictureCropper.getCroppedCanvas().toBlob((blob) => {
            croppedProfilePicture = blob; // Store cropped image data
            let reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => {
                let base64data = reader.result;
                $('#profilePicturePreview').attr('src', base64data); 
                $('#cropProfilePictureModal').modal('hide');
            }
        });
    });

    $('#registrationForm').submit(function(e){
        e.preventDefault();

        let isValid= true;

        if(!validateName()){
            isValid= false;
        }
        if(!validateEmail()){
            isValid= false;
        }
        // if(!validatePhone()){
        //     isValid= false;
        // }
        if (!validateState()) {
        isValid = false;
        }
        if ($('#districtDiv').is(':visible') && !validateDistrict()) {
            isValid = false;
        }
        if ($('#cityDiv').is(':visible') && !validateCity()) {
            isValid = false;
        }
        if(!validatePassword()){
            isValid= false;
        }
        if(!validateGender()){
            isValid= false;
        }
        if(!validateLanguage()){
            isValid= false;
        }
        // if(!validateProfilePicture()){
        //     isValid=false;
        // }
        // if(!validateImages()){
        //     isValid= false;
        // }
        
        if(isValid===true){
            let uid= $('#uid').val();
            let name= $('#name').val();
            let email=$('#email').val();
            let password= $('#password').val();
            let phone=$('#phone').val();
            let state=$('#state').val();
            let district=$('#district').val();
            let city=$('#city').val();
            let gender = $('input[name="gender"]:checked').val();
            let languages=[];
            $('.language:checked').each(function(){
                languages.push($(this).val());
                // console.log($(this).val());
            });

            let formData= new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('uid',uid);
            formData.append('name',name);
            formData.append('email',email);
            formData.append('password',password);
            formData.append('phone',phone);
            formData.append('state',state);
            formData.append('district',district);
            formData.append('city',city);
            formData.append('gender',gender);
            languages.forEach(function(language) {
                formData.append('languages[]', language);
            });

            // Append cropped profile picture if available
            if (croppedProfilePicture) {
                formData.append('profilePicture', croppedProfilePicture, 'profile_picture.jpg');
            }
            // Append cropped images
            croppedImages.forEach((croppedImage, index) => {
                if (croppedImage) {
                    formData.append('images[]', croppedImage);
                } else {
                    formData.append('images[]', allImages[index]);
                }
            });

            $.ajax({
                type: "POST",
                url: "{{route('update')}}",
                data: formData,
                processData: false, // Important for FormData
                contentType: false, // Important for FormData
                success: function(response){
                    if(response.status==='Success')
                    {
                        alert(response.message);
                        window.location.href = '/listing';
                    }
                }
            })
        }
    })
})
</script>

<script>
    $(document).ready(function() {
        $('#change').click(function(e) {
            e.preventDefault();

            var email = $('#email').val();

            $.ajax({
                type: 'POST',
                url: "{{route('sendOtp')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email
                },
                success: function(response) {
                    if (response.success) {
                        $('#change').hide();
                        $('#otpSection').show();
                    }
                }
            });
        });

        $('#verify').click(function(e) {
            e.preventDefault();

            var email = $('#email').val();
            var otp = $('#otp').val();

            $.ajax({
                url: '/verify-otp',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: email,
                    otp: otp
                },
                success: function(response) {
                    if (response.success) {
                        $('#verify').text('Verified');
                        $('#otpSection').hide();
                        $('#verifiedMessage').show();
                    } else {
                        alert('Invalid OTP');
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#changePhone').click(function(e) {
            e.preventDefault();

            var phone = $('#phone').val();
            var formattedPhone = '+91' + phone;

            $.ajax({
                type: 'POST',
                url: "{{route('sendPhoneOtp')}}",
                data: {
                    _token: '{{ csrf_token() }}',
                    phone: formattedPhone
                },
                success: function(response) {
                    if (response.success) {
                        $('#changePhone').hide();
                        $('#otpSectionPhone').show();
                    }
                }
            });
        });

        $('#verifyPhoneOtp').click(function(e) {
            e.preventDefault();

            var phoneOtp = $('#phoneOtp').val();

            $.ajax({
                url: '/verify-phone-otp',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    otp: phoneOtp
                },
                success: function(response) {
                    if (response.success) {
                        $('#verifyPhoneOtp').text('Verified');
                        $('#otpSectionPhone').hide();
                        $('#verifiedMessagePhone').show();
                    } else {
                        alert('Invalid OTP');
                    }
                }
            });
        });
    });
</script>
</x-layout>