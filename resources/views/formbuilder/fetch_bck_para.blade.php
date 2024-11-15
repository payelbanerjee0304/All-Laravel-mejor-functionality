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
                                        <label for="question">Question</label>
                                        <input type="text" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][question]" class="form-control" value="{{ $questionData['title'] }}">
                                        <small class="ques_error error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="type">Select type</label>
                                        <input type="text" id="typ-{{ $subtaskIndex }}-{{ $questionIndex }}" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][type]" class="form-control select-type" value="{{$questionData['selecttype']}}">
                                        
                                        <small class="typ_error error"></small>
                                    </div>
    
                                    
                                    <div class="form-group file-input-container" style="display: {{ $questionData['selecttype'] == 'fileupload' ? 'block' : 'none' }};">
                                        <label for="file">Upload File</label>
                                        <input type="file" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][file]" class="form-control file-input">
                                        <small class="file_error error"></small>
                                    </div>
                                    <div class="form-group shortanswer-input-container" style="display: {{ $questionData['selecttype'] == 'shortanswer' ? 'block' : 'none' }};">
                                        <label for="shortanswer-{{ $subtaskIndex }}-{{ $questionIndex }}">Short Answer</label>
                                        <input type="text" name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][shortanswer]" class="form-control shortanswer-input">
                                        <small class="shortanswer_error error"></small>
                                    </div>
                                    <div class="form-group paragraph-input-container" style="display: {{ $questionData['selecttype'] == 'paragraph' ? 'block' : 'none' }};">
                                        <label for="paragraph-{{ $subtaskIndex }}-{{ $questionIndex }}">Paragraph</label>
                                        <textarea name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][paragraph]" class="form-control paragraph-input" rows="4"></textarea>
                                        <small class="paragraph_error error"></small>
                                    </div>
                                    
                                    <div class="form-group dropdown-input-container" style="display: {{ $questionData['selecttype'] == 'dropdown' ? 'block' : 'none' }};">
                                        <label for="dropdown-{{ $subtaskIndex }}-{{ $questionIndex }}">Dropdown</label>
                                        <select name="questions[{{ $subtaskIndex }}][{{ $questionIndex }}][dropdown]" id="" class="form-control select-type" data-subtask-index="{{ $subtaskIndex }}" data-question-index="{{ $questionIndex }}">
                                            <option value="">Select</option>
                                            @foreach ($questionData['options'] as $optionIndex => $option)
                                                <option value="{{ $option['text'] }}">{{ $option['text'] }}</option>
                                            @endforeach
                                        </select>

                                        <!-- partTask part -->
                                        @foreach ($questionData['options'] as $optionIndex => $option)
                                            @foreach ($option['parttask'] as $parttaskIndex => $parttask)
                                                <div id="parttask-{{ $option['text'] }}-{{ $subtaskIndex }}-{{ $questionIndex }}" class="parttask">
                                                    <p>Question: {{ $parttask['question'] }}</p>
                                                    
                                                    @if ($parttask['type'] == 'radiopart')
                                                        <!-- Radio buttons -->
                                                        @foreach ($parttask['options'] as $radioOption)
                                                            <label>
                                                                <input type="radio" name="parttask[{{ $subtaskIndex }}][{{ $questionIndex }}]" value="{{ $radioOption }}"> {{ $radioOption }}
                                                            </label><br>
                                                        @endforeach
                                                    @elseif ($parttask['type'] == 'checkpart')
                                                        <!-- Checkboxes -->
                                                        @foreach ($parttask['options'] as $checkOption)
                                                            <label>
                                                                <input type="checkbox" name="parttask[{{ $subtaskIndex }}][{{ $questionIndex }}][]" value="{{ $checkOption }}"> {{ $checkOption }}
                                                            </label><br>
                                                        @endforeach
                                                    @elseif ($parttask['type'] == 'paraphrase')
                                                    <label">
                                                        <input type="text" name="parttask[{{ $subtaskIndex }}][{{ $questionIndex }}]" id="" class="form-control">
                                                    </label>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endforeach
                                        <small class="dropdown_error error"></small>
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
                            <input type="number" name="subtaskpoint" id="subtaskpoint" class="form-control" min="0" value="{{ $subtask['subtaskpoint'] }}">
                            <small class="subtask_error error"></small>
                        </div>
    
                        <!-- Submit Button for Each Subtask -->
                        <button type="button" class="submit-subtask btn btn-success btn-lg" data-subtask-index="{{ $subtaskIndex }}">Submit Subtask {{ $subtaskIndex + 1 }}</button>
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

            var subtaskIndex = $(this).data('subtask-index');
            var $section = $('#subtask-section-' + subtaskIndex);
            var formData = new FormData();

            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('taskpoints', $('#taskpoints').val());
            formData.append('formId', $('#formId').val());
            formData.append('subtaskpoint', $('#subtaskpoint').val());

            $section.find('input, textarea, select').each(function() {
                var name = $(this).attr('name');
                var value = $(this).val();
                // console.log('Name:', name, 'Value:', value);

                // If the input is a file type
                // if ($(this).attr('type') === 'file') {
                //     var files = $(this)[0].files;
                //     if (files.length > 0) {
                //         formData.append(name, files[0]);
                //     }
                // } else {
                //     // formData.append(name, value);
                //     // Collect answer for dropdown type
                //     if ($(this).hasClass('select-type')) {
                //         var selectedValue = $(this).val();
                //         if (selectedValue) {
                //             var answerText = selectedValue;
                //             var parttaskData = [];

                //             // Collect parttask answers related to the selected dropdown option
                //             var parttaskSection = $('#parttask-' + selectedValue + '-' + subtaskIndex + '-' + $(this).data('question-index'));
                //             parttaskSection.find('input[type="text"], input[type="radio"]:checked, input[type="checkbox"]:checked').each(function() {
                //                 var parttaskAnswer = $(this).val();
                //                 parttaskData.push(parttaskAnswer); // Collect parttask answers
                //             });

                //             // Store dropdown answer and related parttask data in FormData
                //             formData.append('questions[' + subtaskIndex + '][' + $(this).data('question-index') + '][Answer][0][answertext]', answerText);
                //             formData.append('questions[' + subtaskIndex + '][' + $(this).data('question-index') + '][Answer][0][parttask][question]', 'what is ' + answerText);
                //             parttaskData.forEach(function(answer, idx) {
                //                 formData.append('questions[' + subtaskIndex + '][' + $(this).data('question-index') + '][Answer][0][parttask][parttask_answer][' + idx + ']', answer);
                //             }.bind(this));
                //         }
                //     } else {
                //         formData.append(name, value);
                //     }
                // }
                $section.find('input, select').each(function() {
        var name = $(this).attr('name');
        var value = $(this).val();

        // Debugging
        console.log('Name:', name, 'Value:', value);

        if ($(this).attr('type') === 'file') {
            var files = $(this)[0].files;
            if (files.length > 0) {
                formData.append(name, files[0]);
            }
        } else {
            formData.append(name, value);
        }
    });
            });

            $.ajax({
                url: '/answersubmit/' + subtaskIndex, // Route with subtaskIndex
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log('Subtask submitted successfully');
                    $section.find('.alert').remove(); 
                    $section.append('<div class="alert alert-success">Subtask submitted successfully!</div>');
                },
                error: function(xhr, status, error) {
                    console.log('Error submitting subtask:', error);
                    $section.find('.alert').remove(); 
                    $section.append('<div class="alert alert-danger">Subtask submission failed.</div>');
                }
            });
        });
    });

    $('.select-type').on('change', function() {
        var selectedValue = $(this).val(); 
        var subtaskIndex = $(this).data('subtask-index'); 
        var questionIndex = $(this).data('question-index'); 

        $('.parttask').hide();

        var selector = '#parttask-' + selectedValue + '-' + subtaskIndex + '-' + questionIndex;

        var $parttask = $(selector);
        if ($parttask.length) {
            $parttask.show();
        }
    });

    $('.select-type').each(function() {
        $(this).trigger('change');
    });

</script>

</body>
</html>