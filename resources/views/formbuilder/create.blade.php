<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Questions</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .form-section {
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
        .parttask-container{
            background-color: #babe7f;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Create Questions</h2>
    @if(session('success'))
        <div class="alert alert-info">{{ session('success') }}</div>
    @endif
    
    <form action="{{url('/questions')}}" method="POST" id="dynamic-form">
        @csrf
        <div class="form-group">
            <label for="taskpoints">Task Points</label>
            <input type="number" name="taskpoints" id="taskpoints" class="form-control" min="0">
            <small id="taskpoints_error" class="error"></small>
        </div>
        
        <div id="form-container">
            <div class="form-section">
                <div class="question-section">
                    <div class="form-group">
                        <label for="question">Question</label>
                        <input type="text" name="questions[0][0][question]" class="form-control">
                        <small class="ques_error error"></small>
                    </div>
                    <div class="form-group">
                        <label for="type">Select type</label>
                        <select id="typ" name="questions[0][0][type]" class="form-control">
                            <option value="">Select</option>
                            <option value="shortanswer">Short Answer</option>
                            <option value="paragraph">Paragraph</option>
                            <option value="multiple choice">Multiple Choice</option>
                            <option value="checkbox">CheckBox</option>
                            <option value="dropdown">Dropdown</option>
                            <option value="fileupload">File Upload</option>
                        </select>
                        <small class="typ_error error"></small>
                    </div>
                    <div class="form-group options-container" style="display:none;">
                        <label for="options">Options</label>
                        <div class="options">
                            <div class="option mb-2">
                                <span class="option-sign"></span>
                                <input type="text" name="questions[0][0][options][0][text]" class="form-control" placeholder="Option 1">
                                <button type="button" class="add-parttask btn btn-info btn-sm">Add Part Task</button>
                                <div class="parttask-container"></div>
                            </div>
                        </div>
                        <button type="button" class="add-option btn btn-secondary btn-sm">Add Another Option</button>
                    </div>
                    <div class="form-group">
                    <input type="hidden" name="questions[0][0][isMandetory]" value="off">
                        <label class="switch">
                            <input type="checkbox" name="questions[0][0][isMandetory]" class="form-check-input isMandetory" id="isMandetory-0">
                            <span class="slider round"></span>
                        </label>
                        <label class="form-check-label" for="isMandetory-0">Mandetory</label>
                    </div>
                    <div class="form-group">
                    <input type="hidden" name="questions[0][0][togglefile]" value="off">
                        <label class="switch">
                            <input type="checkbox" name="questions[0][0][togglefile]" class="form-check-input togglefile" id="togglefile-0">
                            <span class="slider round"></span>
                        </label>
                        <label class="form-check-label" for="togglefile-0">File Upload</label>
                    </div>
                </div>
                <button type="button" class="clone-question btn btn-info mb-3">++</button>
                <div class="form-group">
                    <label for="subtaskpoint">Subtask Point</label>
                    <input type="number" name="questions[0][subtaskpoint]" class="form-control" min="0">
                    <small class="subtask_error error"></small>
                </div>
            </div>
        </div>
        
        <small id="points_error" class="error text-danger"></small>
        <button type="button" id="add-form" class="btn btn-primary mb-3">+</button>
        <button type="submit" form="dynamic-form" class="btn btn-success btn-lg">Submit</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
    $('#dynamic-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the default form submission

        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,  // Important! Prevent jQuery from processing the data
            contentType: false,
            success: function(response) {
                alert('Form submitted successfully');
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert('Form submission failed');
                // Optionally, you can display error messages
            }
        });
    });

    // Handle select type changes
    $('.select-type').on('change', function() {
        var selectedType = $(this).val();
        var $fileInputContainer = $(this).closest('.question-section').find('.file-input-container');
        
        if (selectedType === 'fileupload') {
            $fileInputContainer.show();
        } else {
            $fileInputContainer.hide();
        }
    }).each(function() {
        // Trigger change event on page load
        $(this).trigger('change');
    });
});

</script>
<script>
    $(document).ready(function() {
        
        $('input[type=checkbox].isMandetory').each(function() {
            $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
        });
        $('input[type=checkbox].togglefile').each(function() {
            $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
        });
        // Function to add a remove button to a form section
        function addRemoveButton(formSection) {
            if (!formSection.find('.remove-form').length) {
                formSection.append('<button type="button" class="remove-form btn btn-warning btn-sm">-</button>');
            }
        }

        // Function to add a remove button to a question section
        function addRemoveQuestionButton(questionSection) {
            questionSection.append('<button type="button" class="remove-question btn btn-danger btn-sm">--</button>');
        }

        // Function to render options based on the selected type for a specific form section
        function renderOptions(optionsContainer, type) {
            const options = optionsContainer.find('.option');
            if (options.length > 1) {
                options.slice(1).remove();
            }
            updateOptionSigns(optionsContainer, type);
        }

        // Function to update option signs based on the type
        function updateOptionSigns(optionsContainer, type) {
            optionsContainer.find('.option-sign').each(function() {
                let sign = '';
                if (type === 'multiple choice') {
                    sign = '<input type="radio" disabled>';
                } else if (type === 'checkbox') {
                    sign = '<input type="checkbox" disabled>';
                }
                $(this).html(sign);
            });
        }

        // Function to validate all form sections
        function validateForms() {
            let isValid = true;
            const taskpoints = parseInt($('#taskpoints').val()) || 0;
            let totalSubtaskpoints = 0;
            let allSubtaskPointsEmpty = true;

            $('.form-section').each(function() {
                const question = $(this).find('input[name*="[question]"]');
                const type = $(this).find('select[name*="[type]"]');
                const subtaskpoint = parseInt($(this).find('input[name*="[subtaskpoint]"]').val()) || 0;
                totalSubtaskpoints += subtaskpoint;

                if (question.val().trim() === '') {
                    $(this).find('.ques_error').text('This field is required.');
                    isValid = false;
                } else {
                    $(this).find('.ques_error').text('');
                }

                if (type.val() === '') {
                    $(this).find('.typ_error').text('This field is required.');
                    isValid = false;
                } else {
                    $(this).find('.typ_error').text('');
                }
            });

            if (!allSubtaskPointsEmpty) {
                if (taskpoints !== totalSubtaskpoints) {
                    $('#points_error').text('Task points must equal the sum of all subtask points.');
                    isValid = false;
                } else {
                    $('#points_error').text('');
                }
            }
            return isValid;
        }

        // Function to handle adding a new form section
        function handleAddForm() {
            const formCount = $('.form-section').length;
            const firstFormSection = $('.form-section:first').clone();

            // Remove all but the first .question-section from the cloned form section
            firstFormSection.find('.question-section:gt(0)').remove();

            // Update input names with the correct indices for the form section
            firstFormSection.find('input, select').each(function() {
                const nameAttr = $(this).attr('name');
                if (nameAttr) {
                    // Update form section index only (e.g., [0] to [1])
                    const name = nameAttr.replace(/\[\d+\](\[\d+\])?/, '[' + formCount + ']');
                    $(this).attr('name', name);
                    if ($(this).is(':checkbox')) {
                        $(this).prop('checked', false);
                    } else {
                        $(this).val('');
                    }
                }
            });

            // Hide the options container
            firstFormSection.find('.options-container').hide();

            // Reset part task containers
            firstFormSection.find('.parttask-container').empty();

            // Reset options container to only show the first option
            const optionsContainer = firstFormSection.find('.options-container');
            optionsContainer.find('.option:gt(0)').remove(); // Remove all but the first option
            optionsContainer.find('.option:first input').val('').attr('placeholder', 'Option 1');
            optionsContainer.find('.parttask-container').empty(); // Clear any pre-added part tasks

            // Append the modified form section to the form container
            firstFormSection.appendTo('#form-container');

            // Add remove button to the new form section
            addRemoveButton(firstFormSection);

            // Update input names in the first question section of the new form section
            firstFormSection.find('.question-section:first input, .question-section:first select').each(function() {
                const nameAttr = $(this).attr('name');
                if (nameAttr) {
                    // Update form section index and reset question index (e.g., [0][0] to [1][0])
                    const name = nameAttr.replace(/\[\d+\](\[\d+\])?/, '[' + formCount + '][0]');
                    $(this).attr('name', name);
                }
            });
            firstFormSection.find('input[type=hidden][name*="[isMandetory]"]').remove();
            firstFormSection.find('input[type=checkbox].isMandetory').each(function() {
                $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
            });
            firstFormSection.find('input[type=hidden][name*="[togglefile]"]').remove();
            firstFormSection.find('input[type=checkbox].togglefile').each(function() {
                $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
            });
        }

        // Function to handle adding a new question section
        function handleAddQuestionSection(button) {
            const formSection = $(button).closest('.form-section');
            const formIndex = formSection.index();
            const questionCount = formSection.find('.question-section').length;
            const newQuestionSection = formSection.find('.question-section:first').clone();

            newQuestionSection.find('input, select').each(function() {
                const nameAttr = $(this).attr('name');
                if (nameAttr) {
                    const updatedNameAttr = nameAttr.replace(/\[\d+\]\[\d+\]/g, '[' + formIndex + '][' + questionCount + ']');
                    $(this).attr('name', updatedNameAttr);
                }

                if ($(this).is(':checkbox')) {
                    $(this).prop('checked', false);
                } else {
                    $(this).val('');
                }
            });

            // Add hidden input for isMandetory
            newQuestionSection.find('input[type=checkbox].isMandetory').each(function() {
                $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
            });
            newQuestionSection.find('input[type=checkbox].togglefile').each(function() {
                $(this).before('<input type="hidden" name="' + $(this).attr('name') + '" value="off">');
            });

            // Reset part task containers
            newQuestionSection.find('.parttask-container').empty();
            
            // Reset options container
            const optionsContainer = newQuestionSection.find('.options-container');
            optionsContainer.find('.option:gt(0)').remove(); // Remove all but the first option
            optionsContainer.find('.option:first input').val('').attr('placeholder', 'Option 1');

            newQuestionSection.find('.options-container').hide();
            newQuestionSection.insertBefore($(button));

            // Add remove button to the new question section
            addRemoveQuestionButton(newQuestionSection);
        }

        // Event handlers
        $(document).on('click', '.remove-form', function() {
            $(this).closest('.form-section').remove();
            validateForms();
        });

        $(document).on('click', '.clone-question', function() {
            handleAddQuestionSection(this);
        });

        $(document).on('click', '.remove-question', function() {
            $(this).closest('.question-section').remove();
        });

        $(document).on('change', 'select[name*="[type]"]', function() {
            const type = $(this).val();
            const optionsContainer = $(this).closest('.question-section').find('.options-container');
            if (type === 'multiple choice' || type === 'checkbox' || type === 'dropdown') {
                optionsContainer.show();
                renderOptions(optionsContainer, type);
            } else {
                optionsContainer.hide();
                optionsContainer.find('.option').slice(1).remove();
            }
        });

        $(document).on('click', '.add-option', function() {
    const optionsContainer = $(this).closest('.options-container');
    const optionCount = optionsContainer.find('.option').length;

    // Clone the first option element
    const newOption = optionsContainer.find('.option:first').clone();

    // Clear the text input and part task container of the cloned option
    newOption.find('input[type=text]').val('').attr('placeholder', 'Option ' + (optionCount + 1));

    // Update the name attribute of the new option input for [text]
    newOption.find('input[type=text]').attr('name', function(i, val) {
        // Regex to replace the last index in the name attribute
        return val.replace(/\[options\]\[\d+\]/, '[options][' + optionCount + ']').replace(/\[text\]/, '[text]');
    });

    // Remove part tasks from the cloned option
    newOption.find('.parttask-container').empty();

    // Append the new option to the options container
    optionsContainer.find('.options').append(newOption);

    // Update option signs based on the type (if applicable)
    updateOptionSigns(optionsContainer, optionsContainer.closest('.question-section').find('select[name*="[type]"]').val());
});



        // Handle adding a part task
        $(document).on('click', '.add-parttask', function() {
            const partTaskContainer = $(this).siblings('.parttask-container');
            const formIndex = $(this).closest('.form-section').index();
            const questionIndex = $(this).closest('.question-section').index();
            const optionIndex = $(this).closest('.option').index();
            const partTaskCount = partTaskContainer.children().length;

            const newPartTask = `
        <div class="parttask">
            <div class="form-group">
                <label for="parttask-question">Part Task Question</label>
                <input type="text" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][question]" class="form-control">
            </div>
            <div class="form-group">
                <input type="hidden" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][parttogglefile]" value="off">
                <label class="switch">
                    <input type="checkbox" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][parttogglefile]" class="form-check-input parttogglefile" id="parttogglefile-0">
                    <span class="slider round"></span>
                </label>
                <label class="form-check-label" for="parttogglefile-0">Parttask File Upload</label>
            </div>
            <div class="form-group">
                <label for="parttask-type">Part Task Type</label>
                <div class="form-check">
                    <input type="radio" class="form-check-input parttask-type" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][type]" value="paraphrase">
                    <label class="form-check-label">Paragraph</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input parttask-type" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][type]" value="radiopart">
                    <label class="form-check-label">Radio</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input parttask-type" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][type]" value="checkpart">
                    <label class="form-check-label">Checkbox</label>
                </div>
            </div>
            <div class="form-group parttask-radio-container" style="display:none;">
                <label for="parttask-options">Options</label>
                <div class="parttask-options">
                    <div class="parttask-option mb-2">
                        <input type="text" name="questions[${formIndex}][${questionIndex}][options][${optionIndex}][parttask][${partTaskCount}][options][]" class="form-control" placeholder="Option 1">
                    </div>
                </div>
                <button type="button" class="add-parttask-option btn btn-secondary btn-sm">Add Another Option for Part Task</button>
            </div>
        </div>
    `;

            partTaskContainer.append(newPartTask);
        });

        $(document).on('change', '.parttask-type', function() {
            const type = $(this).val();
            const partTaskContainer = $(this).closest('.parttask');

            partTaskContainer.find('.parttask-radio-container').hide();
            if (type === 'radiopart' || type === 'checkpart') {
                partTaskContainer.find('.parttask-radio-container').show();
            }
        });

        $(document).on('click', '.add-parttask-option', function() {
            const partTaskOptionsContainer = $(this).siblings('.parttask-options');
            const partTaskOptionCount = partTaskOptionsContainer.children().length;
            const newPartTaskOption = partTaskOptionsContainer.find('.parttask-option:first').clone();

            newPartTaskOption.find('input').val('').attr('name', newPartTaskOption.find('input').attr('name').replace(/\[\d+\]$/, '[' + partTaskOptionCount + ']'));
            partTaskOptionsContainer.append(newPartTaskOption);
        });

        $('#add-form').click(handleAddForm);

        $('#dynamic-form').on('submit', function(event) {
            if (!validateForms()) {
                event.preventDefault();
            }
        });

        $(document).on('input', 'input[name*="[subtaskpoint]"], input[name*="[question]"], select[name*="[type]"]', validateForms);
    });
</script>



<script>
    $(document).ready(function() {
        // Function to validate all form sections
        function validateForms() {
            var isValid = true;
            var taskpoints = parseInt($('#taskpoints').val()) || 0;
            var totalSubtaskpoints = 0;
            var allSubtaskPointsEmpty = true; // Flag to track if all subtask points are empty

            // Loop through each form section
            $('.form-section').each(function(index) {
                var question = $(this).find('input[name*="[question]"]');
                var type = $(this).find('select[name*="[type]"]');
                var subtaskpoint = parseInt($(this).find('input[name*="[subtaskpoint]"]').val()) || 0;
                totalSubtaskpoints += subtaskpoint;

                // Check if the question field is empty
                if (question.val().trim() === '') {
                    $(this).find('.ques_error').text('This field is required.');
                    isValid = false;
                } else {
                    $(this).find('.ques_error').text('');
                }

                // Check if the type field is not selected
                if (type.val() === '') {
                    $(this).find('.typ_error').text('This field is required.');
                    isValid = false;
                } else {
                    $(this).find('.typ_error').text('');
                }

                // Check if the subtask point field is empty
                if (subtaskpoint === 0) {
                    $(this).find('.subtask_error').text('');
                } else {
                    allSubtaskPointsEmpty = false; // Set the flag to false if any subtask point is not empty
                    $(this).find('.subtask_error').text('');
                }
            });

            // Check if taskpoints match the sum of subtask points
            if (!allSubtaskPointsEmpty) {
                if (taskpoints !== totalSubtaskpoints) {
                    $('#points_error').text('Task points must equal the sum of all subtask points.');
                    isValid = false;
                } else {
                    $('#points_error').text('');
                }
            }

            // Check if any subtask point exceeds taskpoints
            if (totalSubtaskpoints > taskpoints) {
                $('#points_error').text('Total subtask points cannot exceed task points.');
                isValid = false;
            }

            return isValid;
        }

        // Validate all form sections on submit
        $('#dynamic-form').on('submit', function(event) {
            if (!validateForms()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        // Prevent input if subtask point exceeds task points
        $(document).on('input', 'input[name*="[subtaskpoint]"]', function() {
            var taskpoints = parseInt($('#taskpoints').val()) || 0;
            var currentSubtaskPoint = parseInt($(this).val()) || 0;
            var totalSubtaskpoints = 0;

            $('.form-section').each(function(index) {
                var subtaskpoint = parseInt($(this).find('input[name*="[subtaskpoint]"]').val()) || 0;
                totalSubtaskpoints += subtaskpoint;
            });

            if (totalSubtaskpoints > taskpoints) {
                $(this).val(taskpoints - (totalSubtaskpoints - currentSubtaskPoint));
            }
            validateForms();
        });

        // Disable other subtask point inputs if total matches task points
        $(document).on('input', 'input[name*="[subtaskpoint]"]', function() {
            var taskpoints = parseInt($('#taskpoints').val()) || 0;
            var totalSubtaskpoints = 0;

            $('.form-section').each(function(index) {
                var subtaskpoint = parseInt($(this).find('input[name*="[subtaskpoint]"]').val()) || 0;
                totalSubtaskpoints += subtaskpoint;
            });

            if (totalSubtaskpoints >= taskpoints) {
                $('input[name*="[subtaskpoint]"]').each(function() {
                    if (!$(this).val()) {
                        $(this).prop('disabled', true);
                    }
                });
            } else {
                $('input[name*="[subtaskpoint]"]').prop('disabled', false);
            }
            validateForms();
        });
    });
</script>
</body>
</html>