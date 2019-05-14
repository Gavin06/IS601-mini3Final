<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;


trait UploadTrait1
{
    public function uploadOne(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null)
    {
        $name = !is_null($filename) ? $filename : Str::random(25);

        $file = $uploadedFile->storeAs($folder, $name . '.jpg', $disk);

        return $file;
    }
}

class questionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use UploadTrait1;

    public function testUpload()
    {
        $user = factory(\App\User::class)->make();
        $user->save();
        $question = factory(\App\Question::class)->make();
        $question->user()->associate($user);

        $name = $question->user_id . '_' . time();
        $image = UploadedFile::fake()->image($name);
        $folder = '/uploads/questions/';
        $this->uploadOne($image, $folder, 'public', $name);
        $filePath = 'storage/app/public' . $folder . $name;
        $question->image = $filePath;
        $question->save();

        $this->assertFileExists($filePath . '.jpg');

    }
}
