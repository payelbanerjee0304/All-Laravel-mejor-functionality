<x-layout>
    <x-slot:color>
        {{'danger'}}
    </x-slot:color>
    <x-slot:pageheading>
        {{'Upload From Excel'}}
    </x-slot:pageheading>
    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @endif
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
        @csrf
        <div class="form-group">
            <label for="file">Choose Excel File</label>
            <input type="file" name="file" id="file" class="form-control">
            <small id="file_error" class="text-danger"></small>
        </div>
        <button class="btn btn-info" type="submit" id="importBtn">Import</button>
    </form>
    <script>
        $(document).ready(function() {
            $('#uploadForm').on('submit', function(event) {
                var fileInput = $('#file');
                var fileError = $('#file_error');
                
                // Check if file input is empty
                if (fileInput.get(0).files.length === 0) {
                    fileError.text('Please select a file.');
                    event.preventDefault(); // Prevent form submission
                } else {
                    fileError.text(''); // Clear error message
                }
            });
    
            // Optional: Clear error message when file is selected
            $('#file').on('change', function() {
                $('#file_error').text('');
            });
        });
    </script>
</x-layout>