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
                <div class="subtask-section" id="subtask-section-{{ $subtaskIndex }}">
                    @foreach ($subtask as $questionIndex => $questionData)
                        @if (is_array($questionData))
                            <div class="question-section">
                                <div class="form-group">
                                    <label for="question-{{ $subtaskIndex }}-{{ $questionIndex }}">Question</label>
                                    <input type="text" id="question-{{ $subtaskIndex }}-{{ $questionIndex }}" class="form-control" value="{{ $questionData['title'] }}">
                                    <small class="ques_error error"></small>
                                </div>
                                <div class="form-group">
                                    <label for="type-{{ $subtaskIndex }}-{{ $questionIndex }}">Select Type</label>
                                    <input type="text" id="type-{{ $subtaskIndex }}-{{ $questionIndex }}"class="form-control select-type" value="{{$questionData['selecttype']}}">
                                    <small class="typ_error error"></small>
                                </div>
                                <div class="form-group file-input-container" style="display: {{ $questionData['selecttype'] == 'fileupload' ? 'block' : 'none' }};">
                                    <label for="file-{{ $subtaskIndex }}-{{ $questionIndex }}">Upload File</label>
                                    <input type="file" id="file-{{ $subtaskIndex }}-{{ $questionIndex }}" class="form-control">
                                    <small class="file_error error"></small>
                                </div>
                                <div class="form-group shortanswer-input-container" style="display: {{ $questionData['selecttype'] == 'shortanswer' ? 'block' : 'none' }};">
                                    <label for="shortanswer-{{ $subtaskIndex }}-{{ $questionIndex }}">Short Answer</label>
                                    <input type="text" id="shortanswer-{{ $subtaskIndex }}-{{ $questionIndex }}" class="form-control">
                                    <small class="shortanswer_error error"></small>
                                </div>
                                
                                <div class="form-group paragraph-input-container" style="display: {{ $questionData['selecttype'] == 'paragraph' ? 'block' : 'none' }};">
                                    <label for="paragraph-{{ $subtaskIndex }}-{{ $questionIndex }}">Paragraph</label>
                                    <textarea id="paragraph-{{ $subtaskIndex }}-{{ $questionIndex }}" class="form-control"></textarea>
                                    <small class="paragraph_error error"></small>
                                </div>
                                <div class="form-group options-container" style="display: {{ in_array($questionData['selecttype'], ['multiple choice', 'checkbox', 'dropdown']) ? 'block' : 'none' }};">
                                    <label for="options-{{ $subtaskIndex }}-{{ $questionIndex }}">Options</label>
                                    <div class="options">
                                        @foreach ($questionData['options'] as $optionIndex => $option)
                                            <div class="option mb-2">
                                                @if($questionData['selecttype']=="multiple choice")
                                                <label class="btn btn-secondary">
                                                    <input type="radio" name="options" id="option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}" value="{{ $option['text'] }}" placeholder="Option {{ $optionIndex + 1 }}">{{ $option['text'] }}
                                                </label>
                                                @elseif($questionData['selecttype']=="checkbox")
                                                <label class="btn btn-secondary">
                                                    <input type="checkbox" id="option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}" value="{{ $option['text'] }}" placeholder="Option {{ $optionIndex + 1 }}">{{ $option['text'] }}
                                                </label>
                                                @elseif($questionData['selecttype']=="dropdown")
                                                <select id="option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}" class="form-control">
                                                    <option value="">Select</option>
                                                    @foreach ($questionData['options'] as $optionIndex => $option)
                                                    <option value="{{ $option['text'] }}">{{ $option['text'] }}</option>
                                                    @endforeach
                                                </select>
                                                @else
                                                <label class="btn btn-secondary">
                                                    <input type="text" id="option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}" value="{{ $option['text'] }}" placeholder="Option {{ $optionIndex + 1 }}">{{ $option['text'] }}
                                                </label>
                                                @endif
                                                @if (isset($option['parttask']))
                                                    @foreach ($option['parttask'] as $partTaskIndex => $partTask)
                                                        <div class="parttask-section mt-3" data-option-index="{{ $optionIndex }}" style="display: none;">
                                                            <label for="parttask-question-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}">Part Task Question</label>
                                                            <input type="text" id="parttask-question-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}" class="form-control" value="{{ $partTask['question'] }}" placeholder="Part Task Question {{ $partTaskIndex + 1 }}">

                                                            <label for="parttask-type-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}">Part Task Type</label>
                                                            <input type="text" id="parttask-type-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}" value="{{$partTask['type']}}" class="form-control">

                                                            @if (isset($partTask['options']) && is_array($partTask['options']))
                                                                <div class="parttask-options mt-2">
                                                                    @foreach ($partTask['options'] as $partTaskOptionIndex => $partTaskOption)
                                                                        <div class="parttask-option mb-2">
                                                                            @if($partTask['type']=="radiopart")
                                                                            <label class="btn btn-info">
                                                                                <input type="radio" id="parttask-option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}-{{ $partTaskOptionIndex }}" value="{{ $partTaskOption }}" placeholder="Part Task Option {{ $partTaskOptionIndex + 1 }}">{{ $partTaskOption }}
                                                                            </label>
                                                                            @elseif($partTask['type']=="checkpart")
                                                                            <label class="btn btn-info">
                                                                                <input type="checkbox" id="parttask-option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}-{{ $partTaskOptionIndex }}" value="{{ $partTaskOption }}" placeholder="Part Task Option {{ $partTaskOptionIndex + 1 }}">{{ $partTaskOption }}
                                                                            </label>
                                                                            @elseif($partTask['type']=="paraphrase")
                                                                                <input type="text" id="parttask-option-{{ $subtaskIndex }}-{{ $questionIndex }}-{{ $optionIndex }}-{{ $partTaskIndex }}-{{ $partTaskOptionIndex }}" class="form-control" value="{{ $partTaskOption }}" placeholder="Part Task Option {{ $partTaskOptionIndex + 1 }}">
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <input type="hidden" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][isMandetory]" value="off">
                                    <label class="switch">
                                        <input type="checkbox" id="isMandetory-{{ $subtaskIndex }}-{{ $questionIndex }}" {{ $questionData['isMandetory'] ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                    <label class="form-check-label" for="isMandetory-{{ $subtaskIndex }}-{{ $questionIndex }}">Mandatory</label>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="form-group">
                        <label for="subtaskpoint-{{ $subtaskIndex }}">Subtask Point</label>
                        <input type="number" id="subtaskpoint-{{ $subtaskIndex }}" class="form-control" min="0" value="{{ $subtask['subtaskpoint'] }}">
                        <small class="subtask_error error"></small>
                    </div>
                    <button type="button" class="submit-subtask btn btn-success btn-lg" data-subtask-index="{{ $subtaskIndex }}">Submit Subtask {{ $subtaskIndex + 1 }}</button>
                </div>
            @endforeach
        </div>
    </div>
    

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script>
    $(document).ready(function() {
        $('.submit-subtask').on('click', function(event) {
            event.preventDefault();

            var subtaskIndex = $(this).data('subtask-index');
            var $section = $('#subtask-section-' + subtaskIndex);
            var formData = new FormData();

            // Add CSRF token
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('taskpoints', $('#taskpoints').val());
            formData.append('formId', $('#formId').val());

            // Collect inputs and files for the subtask
            $section.find('.question-section').each(function() {
                var $this = $(this);
                var questionIndex = $this.find('input[type="text"]').attr('id').split('-').pop();
                // var selectType = $this.find('select').val();
                var selectType = $this.find('#type-' + subtaskIndex + '-' + questionIndex).val();
                formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][question]', $this.find('input[type="text"]').val());
                formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][type]', selectType);

                if (selectType === 'fileupload') {
                    var fileInput = $this.find('input[type="file"]')[0];
                    if (fileInput.files.length > 0) {
                        formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][file]', fileInput.files[0]);
                    }
                }else if (selectType === 'shortanswer') {
                    var shortAnswerInput = $this.find('input[id^="shortanswer-"]')[0];
                    formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][shortanswer]', shortAnswerInput.value);
                } else if (selectType === 'paragraph') {
                    var paragraphInput = $this.find('textarea[id^="paragraph-"]')[0];
                    formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][paragraph]', paragraphInput.value);
                }

                // Collect options
                $this.find('.options .option').each(function(index) {
                    console.log('Option ID:', $(this).find('input, select').attr('id'));
                    console.log('Is checkbox checked:', $(this).find('input[type="checkbox"]').is(':checked'));
                    var inputElement;
                    // Check the input type dynamically
                    if ($(this).find('input[type="radio"]').length > 0) {
                        inputElement = $(this).find('input[type="radio"]');
                    } else if ($(this).find('input[type="checkbox"]').length > 0) {
                        inputElement = $(this).find('input[type="checkbox"]');
                    } else if ($(this).find('select').length > 0) {
                        inputElement = $(this).find('select');
                    }else if ($(this).find('input[type="text"]').length > 0) {
                        inputElement = $(this).find('input[type="text"]');
                    } else {
                        console.log('No valid input type found');
                        return;
                    }
                    
                    // Extract optionIndex from the input element's ID
                    var optionId = inputElement.attr('id');
                    if (optionId) {
                        var optionIndex = optionId.split('-').pop();  // Get the option index from the 'id'
                        if (inputElement.is(':radio')) {
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][text]', inputElement.val());
                        } else if (inputElement.is(':checkbox')) {
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][text]', inputElement.val());
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][checked]', inputElement.is(':checked'));
                        } else if (inputElement.is('select')) {
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][text]', inputElement.find('option:selected').text());
                        } else if (inputElement.is(':text')) {
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][text]', inputElement.val());
                        }
                    }

                    // Get the value of the input (radio, checkbox, or select)
                    var optionValue;
                    
                    // Handle different input types
                    if (inputElement.is('input[type="radio"]') || inputElement.is('input[type="checkbox"]')) {
                        console.log('Checkbox ID:', inputElement.attr('id'));
                        console.log('Is checked:', inputElement.is(':checked'));
                        optionValue = inputElement.is(':checked') ? inputElement.val() : null;
                    } else if (inputElement.is('select')) {
                        optionValue = inputElement.val();
                    } else if (inputElement.is('input[type="text"]')) {
                        optionValue = inputElement.val();
                    }

                    // Append the option data to formData
                    // if (optionValue !== null && optionValue !== '') {
                        formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + optionIndex + '][text]', optionValue);
                    // }

                    // Collect parttasks
                    // if (optionValue !== null && optionValue !== '') {
                        $(this).find('.parttask-section').each(function(partTaskIndex) {
                            var partTaskQuestion = $(this).find('input[type="text"]').val();
                            // var partTaskType = $(this).find('select').val();
                            var partTaskType = $(this).find('input[id^="parttask-type"]').val();
                            
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + index + '][parttask][' + partTaskIndex + '][question]', partTaskQuestion);
                            formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + index + '][parttask][' + partTaskIndex + '][type]', partTaskType);

                            // Collect parttask options
                            $(this).find('.parttask-options .parttask-option').each(function(partTaskOptionIndex) {
                                // var partTaskOptionText = $(this).find('input[type="radio"]').val();
                                // formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + index + '][parttask][' + partTaskIndex + '][options][' + partTaskOptionIndex + ']', partTaskOptionText);
                                var partTaskOptionText;

                                // Check for different input types (radio, checkbox, text)
                                if ($(this).find('input[type="radio"]').length > 0) {
                                    partTaskOptionText = $(this).find('input[type="radio"]').val();
                                } else if ($(this).find('input[type="checkbox"]').length > 0) {
                                    partTaskOptionText = $(this).find('input[type="checkbox"]').val();
                                } else if ($(this).find('input[type="text"]').length > 0) {
                                    partTaskOptionText = $(this).find('input[type="text"]').val();
                                }

                                formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][options][' + index + '][parttask][' + partTaskIndex + '][options][' + partTaskOptionIndex + ']', partTaskOptionText);
                            });
                        });
                    // }
                });

                formData.append('questions[' + subtaskIndex + '][' + questionIndex + '][isMandetory]', $this.find('input[type="checkbox"]').is(':checked') ? 'on' : 'off');
            });

            formData.append('questions[' + subtaskIndex + '][subtaskpoint]', $('#subtaskpoint-' + subtaskIndex).val());

            $.ajax({
                url: '/answersubmit/' + subtaskIndex,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Subtask submitted successfully');
                    $section.append('<div class="alert alert-success">Subtask submitted successfully!</div>');
                },
                error: function(xhr, status, error) {
                    console.log('Error submitting subtask:', error);
                    $section.append('<div class="alert alert-danger">Subtask submission failed.</div>');
                }
            });
        });

        $('.select-type').on('change', function() {
            var selectedType = $(this).val();
            var $questionSection = $(this).closest('.question-section');
            
            // File input container
            var $fileInputContainer = $questionSection.find('.file-input-container');
            if (selectedType === 'fileupload') {
                $fileInputContainer.show();
            } else {
                $fileInputContainer.hide();
            }

            // Options container
            var $optionsContainer = $questionSection.find('.options-container');
            if (['multiple choice', 'checkbox', 'dropdown'].includes(selectedType)) {
                $optionsContainer.show();
            } else {
                $optionsContainer.hide();
            }

            function updateParttasks() {
                var $options = $questionSection.find('.options .option');

                $options.each(function() {
                    // Extract the option index from the ID
                    var optionIndex = $(this).find('input, select').attr('id').split('-').pop();
                    var $parttaskSections = $questionSection.find('.parttask-section[data-option-index="' + optionIndex + '"]');

                    var isCheckedOrSelected = false;

                    // Check if a checkbox is checked
                    if ($(this).find('input[type="checkbox"]').length && $(this).find('input[type="checkbox"]').is(':checked')) {
                        isCheckedOrSelected = true;
                    }

                    // Check if a radio button is checked (only if no checkbox was checked)
                    if ($(this).find('input[type="radio"]').length && $(this).find('input[type="radio"]').is(':checked')) {
                        isCheckedOrSelected = true;
                    }

                    // Check if a select option is selected (only if no checkbox or radio was checked)
                    if ($(this).find('select').length && $(this).find('select').find('option:selected').val() !== "") {
                        isCheckedOrSelected = true;
                    }

                    // Show or hide the parttask sections based on the state
                    if (isCheckedOrSelected) {
                        $parttaskSections.show();
                    } else {
                        $parttaskSections.hide();
                    }
                });
            }

            // Attach the update function to change events for checkboxes, radio buttons, and selects
            $(document).on('change', '.options input[type="checkbox"], .options input[type="radio"], .options select', function() {
                updateParttasks();
            });

            // Initial call to set the correct visibility on page load
            updateParttasks();

        }).each(function() {
            $(this).trigger('change');
        });
    });
</script>

</body>
</html>