<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Questions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .error { 
            color: red; 
        }
        .option-sign { 
            display: inline-block; width: 20px; 
        }
        .switch { 
            position: relative; 
            display: inline-block; 
            width: 34px; 
            height: 20px; 
        }
        .switch input { 
            opacity: 0; 
            width: 0; 
            height: 0; 
        }
        .slider { 
            position: absolute; 
            cursor: pointer; 
            top: 0; 
            left: 0; 
            right: 0; 
            bottom: 0; 
            background-color: #ccc; 
            transition: .4s; 
            border-radius: 20px; 
        }
        .slider:before { 
            position: absolute; 
            content: ""; 
            height: 12px; 
            width: 12px; 
            left: 4px; 
            bottom: 4px; 
            background-color: 
            white; transition: .4s; 
            border-radius: 50%; 
        }
        input:checked + .slider { 
            background-color: #2196F3; 
        }
        input:checked + .slider:before { 
            transform: translateX(14px); 
        }
        body {
            background-color: #FDFFE2;
        }
        .subtask-section {
            background-color: #012416;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .question-section {
            background-color: #717444;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
        .parttask-section{
            background-color: #babe7f;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Fetch Questions</h2>
    
        <div class="form-group">
            <label for="taskpoints">Task Points</label>
            <input type="number" name="taskpoints" id="taskpoints" class="form-control" min="0" value="{{ $form->taskpoints }}">
            <small id="taskpoints_error" class="error"></small>
        </div>
    
        <input type="hidden" name="formId" id="formId" value="{{ $form->_id }}">
    
        <div id="form-container">
            @foreach ($form->subtask as $subtaskIndex => $subtask)
                <div class="subtask-form" id="subtask-form-{{ $subtaskIndex }}">
                    
                    <div class="form-section">
                        @foreach ($subtask as $questionIndex => $questionData)
                            @if (is_array($questionData))
                                <div class="question-section">
                                    <div class="form-group">
                                        <label for="question">Question</label>
                                        <input type="text" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][question]" class="form-control" value="{{ $questionData['title'] }}">
                                        <small class="ques_error error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Select type</label>
                                        <select id="typ-{{ $subtaskIndex }}-{{ $questionIndex }}" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][type]" class="form-control select-type">
                                            <option value="">Select</option>
                                            <option value="shortanswer" {{ $questionData['selecttype'] == 'shortanswer' ? 'selected' : '' }}>Short Answer</option>
                                            <option value="paragraph" {{ $questionData['selecttype'] == 'paragraph' ? 'selected' : '' }}>Paragraph</option>
                                            <option value="multiple choice" {{ $questionData['selecttype'] == 'multiple choice' ? 'selected' : '' }}>Multiple Choice</option>
                                            <option value="checkbox" {{ $questionData['selecttype'] == 'checkbox' ? 'selected' : '' }}>CheckBox</option>
                                            <option value="dropdown" {{ $questionData['selecttype'] == 'dropdown' ? 'selected' : '' }}>Dropdown</option>
                                            <option value="fileupload" {{ $questionData['selecttype'] == 'fileupload' ? 'selected' : '' }}>File Upload</option>
                                        </select>
                                        <small class="typ_error error"></small>
                                    </div>
    
                                    <!-- Options or File Upload -->
                                    <div class="form-group options-container" style="display:{{ $questionData['selecttype'] ? 'block' : 'none' }};">
                                        <label for="options">Options</label>
                                        <div class="options">
                                            @foreach ($questionData['options'] as $optionIndex => $option)
                                                <div class="option mb-2">
                                                    <input type="text" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][options][{{ $optionIndex }}][text]" class="form-control" value="{{ $option['text'] }}" placeholder="Option {{ $optionIndex + 1 }}">
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="add-option btn btn-secondary btn-sm">Add Another Option</button>
                                    </div>
    
                                    <!-- File Upload Field -->
                                    <div class="form-group file-input-container" style="display: {{ $questionData['selecttype'] == 'fileupload' ? 'block' : 'none' }};">
                                        <label for="file">Upload File</label>
                                        <input type="file" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][file]" class="form-control file-input">
                                        <small class="file_error error"></small>
                                    </div>
    
                                    <!-- Mandatory Switch -->
                                    <div class="form-group">
                                        <input type="hidden" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][isMandetory]" value="off">
                                        <label class="switch">
                                            <input type="checkbox" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][isMandetory]" class="form-check-input isMandetory" id="isMandetory-{{ $subtaskIndex }}-{{ $questionIndex }}" {{ $questionData['isMandetory'] ? 'checked' : '' }}>
                                            <span class="slider round"></span>
                                        </label>
                                        <label class="form-check-label" for="isMandetory-{{ $subtaskIndex }}-{{ $questionIndex }}">Mandatory</label>
                                    </div>
                                </div>
                            @endif
                        @endforeach
    
                        <!-- Subtask Points -->
                        <div class="form-group">
                            <label for="subtaskpoint">Subtask Point</label>
                            <input type="number" name="questions[{{ $subtaskIndex }}][subtaskpoint]" class="form-control" min="0" value="{{ $subtask['subtaskpoint'] }}">
                            <small class="subtask_error error"></small>
                        </div>
    
                        <!-- Submit Button for Each Subtask -->
                        <button type="button" class="submit-subtask btn btn-success btn-lg" data-subtask-index="{{ $subtaskIndex }}">Submit Subtask {{ $subtaskIndex + 1 }}</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
    // Attach event handler to subtask submit buttons
    $('.submit-subtask').on('click', function(event) {
        event.preventDefault();

        // Get the subtask index from the button's data attribute
        var subtaskIndex = $(this).data('subtask-index');
        var $container = $('#subtask-form-' + subtaskIndex); // Identify the correct subtask form
        var formData = new FormData();

        // Add CSRF token directly to formData
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('taskpoints', $('#taskpoints').val());
        formData.append('formId', $('#formId').val());

        // Collect form inputs and files, appending to formData
        $container.find('input, select').each(function() {
            var name = $(this).attr('name');
            var value = $(this).val();

            // If the input is a file type
            if ($(this).attr('type') === 'file') {
                var files = $(this)[0].files;
                if (files.length > 0) {
                    formData.append(name, files[0]); // Append file
                }
            } else {
                formData.append(name, value); // Append other inputs
            }
        });

        // Perform the AJAX request
        $.ajax({
            url: '/answersubmit/' + subtaskIndex, // Route with subtaskIndex
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Subtask submitted successfully');
                $container.find('.alert').remove(); 
                $container.append('<div class="alert alert-success">Subtask submitted successfully!</div>');
            },
            error: function(xhr, status, error) {
                console.log('Error submitting subtask:', error);
                $container.find('.alert').remove(); 
                $container.append('<div class="alert alert-danger">Subtask submission failed.</div>');
            }
        });
    });

    // Handle select type change events
    $('.select-type').on('change', function() {
        var selectedType = $(this).val();
        var $fileInputContainer = $(this).closest('.question-section').find('.file-input-container');

        // Show or hide file input based on selected type
        if (selectedType === 'fileupload') {
            $fileInputContainer.show();
        } else {
            $fileInputContainer.hide();
        }
    }).each(function() {
        $(this).trigger('change'); // Trigger change event on page load to set initial state
    });
});

</script>

</body>
</html>