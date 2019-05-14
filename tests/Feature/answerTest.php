<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.jpg', $disk);

        return $file;
    }
}

class answerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use UploadTrait;

    function testUpload()
    {
        $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);
        $question->save();
        $answer = factory(\App\Answer::class)->make();
        $answer->user()->associate($user);
        $answer->question()->associate($question);

        $name = $answer->user_id . '_' . $answer->question_id . '_' . time();
        $image = UploadedFile::fake()->image($name);
        $folder = '/uploads/answers/';
        $this->uploadOne($image, $folder, 'public', $name);
        $filePath = 'storage/app/public' . $folder . $name;
        $answer->image = $filePath;
        $answer->save();

        $this->assertFileExists($filePath . '.jpg');
    }
}
