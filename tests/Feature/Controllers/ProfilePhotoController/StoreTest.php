<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\post;

it('requires authentication', function () {
    post(route('profile.photo.store'))->assertRedirectToRoute('login');
});

it('updates profile photo', function () {
    Event::fake();

    $user = User::factory()->create();

    actingAs($user)
        ->post(route('profile.photo.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'photo' => UploadedFile::fake()->image('photo.jpg'),
        ]);

    $user->refresh();

    expect($user->profile_photo_path)->not()->toBeNull();
});
