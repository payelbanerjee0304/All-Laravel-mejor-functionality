<?php

namespace App\Http\Controllers;

use MongoDB\BSON\ObjectId;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\FormBuilder;
use App\Models\AnswerSubmission;

class FormBuilderController extends Controller
{
    public function create(Request $request)
    {
        return view('formbuilder.create');  
    }
    public function store(Request $request)
    {
        echo "<pre>";
        print_r($request->questions);die;
        // $request->validate([
        //     'questions' => 'required|array',
        //     'questions.*.question' => 'required|string',
        //     'questions.*.type' => 'required|string',
        //     'questions.*.options' => 'nullable|array', 
        //     // 'questions.*.toggleTwo' => 'nullable|boolean',
        // ]);
        
        $taskpoints = $request->input('taskpoints');
        $questions = $request->input('questions');
        $totalSubtasks = count($questions);
        $subtaskPointsProvided = array_filter(array_column($questions, 'subtaskpoint'));

        // Distribute task points equally among subtasks if no subtask points are provided
        if (empty($subtaskPointsProvided)) {
            $equalSubtaskPoint = $taskpoints / $totalSubtasks;
            foreach ($questions as &$question) {
                $question['subtaskpoint'] = $equalSubtaskPoint;
            }
        }
        foreach ($questions as &$questionGroup) {
            foreach ($questionGroup as &$question) {
                
                // Rename keys if they exist
        if (isset($question['question'])) {
            $title = $question['question'];
            unset($question['question']);
        }
        if (isset($question['type'])) {
            $selecttype = $question['type'];
            unset($question['type']);
        }
        // Convert isMandetory value
        if (isset($question['isMandetory'])) {
            $isMandetory = $question['isMandetory'] === 'on';
        }
        if (isset($question['togglefile'])) {
            $togglefile = $question['togglefile'] === 'on';
        }
        
                // Transform options into the desired structure
                if (isset($question['options']) && is_array($question['options'])) {
                    $transformedOptions = [];
                    foreach ($question['options'] as $option) {
                        $parttasks = [];
                        if (isset($option['parttask']) && is_array($option['parttask'])) {
                            foreach ($option['parttask'] as $parttask) {
                                $parttasks[] = [
                                    'question' => $parttask['question'],
                                    'type' => $parttask['type'],
                                    'options' => isset($parttask['options']) ? $parttask['options'] : [],
                                    'parttask_togglefile' => (bool)$parttask['parttogglefile'],
                                ];
                            }
                        }
    
                        $transformedOptions[] = [
                            'text' => $option['text'],
                            'parttask' => $parttasks
                        ];
                    }
                    $question = [
                        '_id' => new ObjectId(),
                        'title' => $title,
                        'selecttype' => $selecttype,
                        'options' => $transformedOptions,
                        'isMandetory' => $isMandetory,
                        'togglefile' => $togglefile
                    ];
                }
            }
        }

        // Prepare the data for insertion
        $data = [
            'taskpoints' => $taskpoints,
            'subtask' => $questions
        ];

        // Insert data into the MongoDB collection
        // DB::table('testtask')->insert($data);

        // return redirect()->back()->with('success', 'Data has been successfully saved.');
        // if($taskpoints==$totalSubtaskPoints)
        {
            FormBuilder::insert([$data]);
            return redirect('/create')->with('success', 'Questions created successfully!');
        }
        // else
        // {
        //     return redirect('/create')->with('success', 'both are not equal');
        // }
    }
    
    
    public function fetch($fet){
        $id=$fet;
        $form=FormBuilder::where(['_id' => $id])->first();

        return view('formbuilder.fetch',compact('form'));
    }
    
    public function answersubmit(Request $request, $subtaskIndex)
    {
        echo "<pre>";
        print_r($request->all());die;
        // Retrieve the form ID from the request
        $formId = $request->input('formId');
        $taskpoints = $request->input('taskpoints');
        $subtaskpoint = $request->input('subtaskpoint');
        $questions = $request->input('questions');
        $questionGroup = $questions[$subtaskIndex]; 

        // $totalSubtasks = count($questions);
        // $subtaskPointsProvided = array_filter(array_column($questions, 'subtaskpoint'));

        // $questionGroup['subtaskpoint'] = $subtaskpoint;


        foreach ($questionGroup as $questionIndex => &$question) {
            // print_r($request->file("questions.$subtaskIndex.$questionIndex.togglefile"));die;
            $title = $question['question'] ?? null;
            $selecttype = $question['type'] ?? null;
            $isMandetory =$question['isMandetory'] ?? null;
            if($request->file("questions.$subtaskIndex.$questionIndex.togglefile")){
                $file=$request->file("questions.$subtaskIndex.$questionIndex.togglefile");
                $filename=time()."_".$file->getClientOriginalName();
                $uploadlocation="./task/togglefile";
                $file->move($uploadlocation,$filename);
                $togglefilePath = $uploadlocation . '/' . $filename;
                $togglefile = $togglefilePath;
            }

            if($request->file("questions.$subtaskIndex.$questionIndex.Answer.0.parttask.parttask_togglefile")){
                $file2=$request->file("questions.$subtaskIndex.$questionIndex.Answer.0.parttask.parttask_togglefile");
                $filename2=time()."_".$file2->getClientOriginalName();
                $uploadlocation="./task/parttask_togglefile";
                $file2->move($uploadlocation,$filename2);
                $ParttaskToggleFilePath = $uploadlocation . '/' . $filename2;
                $ParttaskToggleFile = $ParttaskToggleFilePath;
            }
            
            if ($selecttype === 'fileupload' && $request->hasFile("questions.$subtaskIndex.$questionIndex.file")) {
                $file1 = $request->file("questions.$subtaskIndex.$questionIndex.file");
                $filename1 = time() . "_" . $file1->getClientOriginalName();
                $uploadlocation = "./task";
                $file1->move($uploadlocation, $filename1);
                $filePath = $uploadlocation . '/' . $filename1;
                $answer = $filePath;
            } else if ($selecttype === "shortanswer"){
                $answer = $request->input("questions.$subtaskIndex.$questionIndex.shortanswer");
            } else if ($selecttype === 'paragraph') {
                $answer = $request->input("questions.$subtaskIndex.$questionIndex.paragraph");
            } else if ($selecttype === 'dropdown') {
                $answer = $request->input("questions.$subtaskIndex.$questionIndex.dropdown");
                $dropdownOptions = $request->input("questions.$subtaskIndex.$questionIndex.Answer");
                if (isset($dropdownOptions) && is_array($dropdownOptions)) {
                    foreach ($dropdownOptions as $option) {
                        if (isset($option['parttask']) && is_array($option['parttask'])) {
                            // print_r($option['parttask']);die;
                            $parttask_question=$option['parttask']['parttask_question'];
                            $parttask_answer = $option['parttask']['parttask_answer'];
                            // $parttask_togglefile = $option['parttask']['parttask_togglefile'];
                            if($request->file("questions.$subtaskIndex.$questionIndex.parttask_togglefile")){
                                $file2=$request->file("questions.$subtaskIndex.$questionIndex.parttask_togglefile");
                                $filename2=time()."_".$file2->getClientOriginalName();
                                $uploadlocation="./task/parttask_togglefile";
                                $file2->move($uploadlocation,$filename2);
                                $parttaskToggleFilePath = $uploadlocation . '/' . $filename;
                                $parttaskToggleFile = $parttaskToggleFilePath;
                            }
                        }
                    }
                }
            } else {
                $answer = null;
            }
            $questionGroup[$questionIndex] = [
                'title' => $title,
                'selecttype' => $selecttype,
                'answer' => $answer,
                'parttask_question' => ($selecttype === 'dropdown') ? $parttask_question : null,
                'parttask_answer' => ($selecttype === 'dropdown') ? $parttask_answer : [],
                'parttask_togglefile' => ($selecttype === 'dropdown') ? $ParttaskToggleFile : null,
                'isMandetory' => $isMandetory,
                'togglefile' => $togglefile,
            ];
        }
        $data = [
            'taskpoints' => $taskpoints,
            'form_id' => $formId,
            'subtask' => [$subtaskIndex => $questionGroup] 
        ];
        $existingSubmission = AnswerSubmission::where('form_id', $formId)->first();
        if ($existingSubmission) {
            // Merge existing subtask data with new data
            $existingData = $existingSubmission->toArray();
            $existingSubtasks = $existingData['subtask'] ?? [];
            $existingSubtasks[$subtaskIndex] = $questionGroup;
            $existingData['subtask'] = $existingSubtasks;
            $existingData['taskpoints'] = $taskpoints;
            $existingSubmission->update($existingData);
        } else 
        {
            AnswerSubmission::insert($data);
        }
        return response()->json(['success' => 'Subtask submitted successfully']);
    }

}
