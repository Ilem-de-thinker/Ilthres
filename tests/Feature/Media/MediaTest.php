<?php

use App\Domains\Media\Actions\DeleteMedia;
use App\Domains\Media\Actions\UploadMedia as UploadMediaAction;
use App\Domains\Media\Livewire\MediaLibrary;
use App\Domains\Media\Livewire\UploadMedia as UploadMediaComponent;
use App\Models\Business;
use App\Models\Media;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses()->group('media');

beforeEach(function () {
    Storage::fake('public');
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    $plan = Plan::factory()->free()->create(['slug' => 'free']);
    Subscription::factory()->create(['business_id' => $this->business->id, 'plan_id' => $plan->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Media Library', function () {
    it('renders media library page', function () {
        $this->get('/dashboard/media')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(MediaLibrary::class)
            ->assertSee('No files uploaded yet');
    });

    it('lists uploaded media', function () {
        Media::factory()->count(3)->create(['business_id' => $this->business->id]);

        Livewire::test(MediaLibrary::class)
            ->assertSuccessful();
    });
});

describe('Upload Media', function () {
    it('uploads an image', function () {
        $file = UploadedFile::fake()->image('test.jpg', 200, 200);

        Livewire::test(UploadMediaComponent::class)
            ->set('file', $file)
            ->call('handleUpload')
            ->assertSuccessful();

        $this->assertDatabaseHas('media', [
            'business_id' => $this->business->id,
            'original_name' => 'test.jpg',
        ]);
    });

    it('validates file type', function () {
        $file = UploadedFile::fake()->create('test.txt', 100);

        Livewire::test(UploadMediaComponent::class)
            ->set('file', $file)
            ->call('handleUpload')
            ->assertSet('uploadError', 'File type not supported. Allowed: JPEG, PNG, WebP, GIF, PDF, CSV.');
    });

    it('validates file size', function () {
        $file = UploadedFile::fake()->image('large.jpg')->size(11000);

        Livewire::test(UploadMediaComponent::class)
            ->set('file', $file)
            ->call('handleUpload')
            ->assertHasErrors('file');
    });
});

describe('Delete Media', function () {
    it('deletes a media file', function () {
        $media = Media::factory()->create([
            'business_id' => $this->business->id,
            'path' => 'media/test.jpg',
        ]);
        Storage::disk('local')->put('media/test.jpg', 'fake-content');

        app(DeleteMedia::class)($media);

        expect(Media::find($media->id))->toBeNull();
        Storage::disk('local')->assertMissing('media/test.jpg');
    });
});

describe('UploadMedia Action', function () {
    it('creates media record and stores file', function () {
        $file = UploadedFile::fake()->image('photo.png', 100, 100);

        $media = app(UploadMediaAction::class)($this->business, $file, 'logo');

        expect($media)->toBeInstanceOf(Media::class)
            ->and($media->collection)->toBe('logo')
            ->and($media->business_id)->toBe($this->business->id);
    });
});
