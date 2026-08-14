<?php

use App\Models\PlatformSetting;

it('retrieves a platform setting by key', function () {
    PlatformSetting::set('pricing_enabled', true);

    expect(PlatformSetting::get('pricing_enabled'))->toBeTrue();
});

it('returns default value when key does not exist', function () {
    expect(PlatformSetting::get('nonexistent_key', 'default'))->toBe('default');
});

it('returns null when key does not exist and no default', function () {
    expect(PlatformSetting::get('nonexistent_key'))->toBeNull();
});

it('sets and retrieves a boolean value', function () {
    PlatformSetting::set('pricing_enabled', false);

    expect(PlatformSetting::get('pricing_enabled'))->toBeFalse();
});

it('sets and updates an existing value', function () {
    PlatformSetting::set('pricing_enabled', true);
    PlatformSetting::set('pricing_enabled', false);

    expect(PlatformSetting::get('pricing_enabled'))->toBeFalse();
});

it('sets and retrieves a string value', function () {
    PlatformSetting::set('platform_name', 'Ilthres');

    expect(PlatformSetting::get('platform_name'))->toBe('Ilthres');
});

it('sets and retrieves a numeric value', function () {
    PlatformSetting::set('max_upload_size', 10);

    expect(PlatformSetting::get('max_upload_size'))->toBe(10.0);
});

it('creates only one record per key', function () {
    PlatformSetting::set('pricing_enabled', true);
    PlatformSetting::set('pricing_enabled', false);
    PlatformSetting::set('pricing_enabled', true);

    expect(PlatformSetting::where('key', 'pricing_enabled')->count())->toBe(1);
    expect(PlatformSetting::get('pricing_enabled'))->toBeTrue();
});
