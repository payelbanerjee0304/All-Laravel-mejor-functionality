<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Refreshed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <!-- Example of including jQuery and Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    {{-- font awesome for search cdn --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    
    <!-- Include SweetAlert library -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    
    
    <style>
        .image-container {
            position: relative;
            display: inline-block;
            margin: 10px;
        }

        .croppable-image {
            width: 100px;
            height: 100px;
            cursor: pointer;
        }

        .remove-button {
            position: absolute;
            top: 0px;
            right: 0px;
            background: red;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            padding: 2px 5px;
        }
        #reportrange {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ccc;
            padding: 5px 10px;
            background: #fff;
            cursor: pointer;
            margin-right: 10px;
        }
        #reportrange i {
            margin-right: 8px;
        }
        .filter-section {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 10px;
        }
        .filter-section form {
            display: flex;
            align-items: center;
        }
        .filter-section button {
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="alert alert-{{$color ?? 'primary'}}">
        <h1 class="text-center">Refreshed</h1>
    </div>
    <div class="container">
        <h3 class="">{{$pageheading??'you forgot to put the page heading'}}</h3>
        {{$slot}}
    </div>
</body>
</html>