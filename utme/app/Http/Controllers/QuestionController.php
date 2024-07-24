<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $questions = Question::getAll();
       return response()->json($questions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'question' => 'required|string|unique:questions,question',
                'options' => 'required|array|min:4', // Ensure there are at least 4 options
                'options.*' => 'required|string', // Validate each option
                'subject' => 'required|string', // Validate the subject
                'subtopic' => 'required|array', // Validate subtopic as an array
                'subtopic.key' => 'required|string', // Validate subtopic key
                'subtopic.value' => 'required|numeric', // Validate subtopic value
                'image' => 'nullable|string', // validate base64 image string
                'answer' => 'required|string', // Validate the answer
            ]);

            // Validate that an option is not repeated
            $validator->after(function ($validator) use ($request) {
                $options = $request->options;
                if (count(array_unique($options)) < count($options)) {
                    $validator->errors()->add('options', 'Options must be unique.');
                }
            });

            // Validate that the answer is one of the options
            $validator->after(function ($validator) use ($request) {
                $options = $request->options;
                $value = $request->answer;
                if (!in_array($value, $options)) {
                    $validator->errors()->add('options', 'The selected answer must be one of the options.');
                }
            });

            // Validation answer
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

             $realFullPath = null;

             // Handle the base64 image upload
            if ($request->image) {

                $image_64 = $request->image; //your base64 encoded data

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($image_64, 0, strpos($image_64, ',')+1); 

                // find substring fro replace here eg: data:image/png;base64,

                $image = str_replace($replace, '', $image_64); 

                $image = str_replace(' ', '+', $image); 

                $imageName = Str::random(10).'.'.$extension;

                // Save the file
                $fullPath = base_path('../utmefiles') . '/' . $imageName;
                File::put($fullPath, base64_decode($image));
                
                 // Generate a URL to the file
                $realFullPath = asset('utmefiles/' . $imageName);
            }

            $data = Question::create([
                'question' => $request->question,
                'options' => $request->options,
                'answer' => $request->answer,
                'subject' => $request->subject,
                'subject_topic' => $request->subtopic['key'],
                'mark' => $request->subtopic['value'],
                'image' =>  $realFullPath,
            ]);

            return response()->json(['data' => $data], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Question not found'], 404);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $data = Question::findOrFail($id);
            return response()->json(['data' => $data], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Question not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'question' => 'required|string',
                'options' => 'required|array|min:4', // Ensure there are at least 4 options
                'options.*' => 'required|string', // Validate each option
                'subject' => 'required|string', // Validate the subject
                'subtopic' => 'required|array', // Validate subtopic as an array
                'subtopic.key' => 'required|string', // Validate subtopic key
                'subtopic.value' => 'required|numeric', // Validate subtopic value
                'image' => 'nullable|string', // validate base64 image string
                'answer' => 'required|string', // Validate the answer
            ]);

            // Validate that an option is not repeated
            $validator->after(function ($validator) use ($request) {
                $options = $request->options;
                if (count(array_unique($options)) < count($options)) {
                    $validator->errors()->add('options', 'Options must be unique.');
                }
            });

            // Validate that the answer is one of the options
            $validator->after(function ($validator) use ($request) {
                $options = $request->options;
                $value = $request->answer;
                if (!in_array($value, $options)) {
                    $validator->errors()->add('options', 'The selected answer must be one of the options.');
                }
            });

            // Validation fails
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Find the question by its ID
            $question = Question::findOrFail($id);

            // Update the attributes
            $question->question = $request->question;
            $question->options = $request->options;
            $question->answer = $request->answer;
            $question->subject = $request->subject;
            $question->subject_topic = $request->subtopic['key'];
            $question->mark = $request->subtopic['value'];

             // Handle the base64 image upload
            if ($request->image) {

                $image_64 = $request->image; //your base64 encoded data

                $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf

                $replace = substr($image_64, 0, strpos($image_64, ',')+1); 

                // find substring fro replace here eg: data:image/png;base64,

                $image = str_replace($replace, '', $image_64); 

                $image = str_replace(' ', '+', $image); 

                $imageName = Str::random(10).'.'.$extension;

               // Save the file
                $fullPath = base_path('../utmefiles') . '/' . $imageName;
                File::put($fullPath, base64_decode($image));
                
                // Get the real path
                $question->image =  asset('utmefiles/' . $imageName);
            }

            // Save the changes
            $data = $question->save();

            return response()->json(['data' => $data], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Question not found'], 404);
        } catch(Exception $e){
            return response()->json(['error' => $e->getMessage()], $e->getResponse()->getStatusCode() ?? 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $question = Question::findOrFail($id);
            return response()->json(['data' => $question->delete()], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Question not found'], 404);
        }
    }

}
