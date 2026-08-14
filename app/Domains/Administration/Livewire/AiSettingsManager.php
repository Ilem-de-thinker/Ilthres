<?php

namespace App\Domains\Administration\Livewire;

use App\Domains\Search\Services\AIClient;
use App\Models\PlatformSetting;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class AiSettingsManager extends Component
{
    public string $provider = 'openai';

    public string $model = '';

    public int $timeout = 30;

    public string $apiKey = '';

    public array $availableProviders = [];

    public array $defaultModels = [];

    public function mount(): void
    {
        $this->availableProviders = AIClient::availableProviders();
        $this->defaultModels = AIClient::defaultModels();

        $this->provider = PlatformSetting::get('ai_provider', 'openai');
        $this->model = PlatformSetting::get('ai_model', AIClient::defaultModels()[$this->provider] ?? '');
        $this->timeout = (int) PlatformSetting::get('ai_timeout', 30);
        $this->apiKey = PlatformSetting::get('ai_api_key', '');
    }

    public function updatedProvider(string $value): void
    {
        $this->model = $this->defaultModels[$value] ?? '';
    }

    public function save(): void
    {
        $this->validate([
            'provider' => 'required|string|in:'.implode(',', array_keys($this->availableProviders)),
            'model' => 'required|string|max:255',
            'timeout' => 'required|integer|min:5|max:300',
            'apiKey' => 'nullable|string|max:255',
        ]);

        PlatformSetting::set('ai_provider', $this->provider);
        PlatformSetting::set('ai_model', $this->model);
        PlatformSetting::set('ai_timeout', $this->timeout);
        PlatformSetting::set('ai_api_key', $this->apiKey);

        if ($this->apiKey) {
            $envKey = match ($this->provider) {
                'deepseek' => 'DEEPSEEK_API_KEY',
                'anthropic' => 'ANTHROPIC_API_KEY',
                'groq' => 'GROQ_API_KEY',
                'gemini' => 'GEMINI_API_KEY',
                'mistral' => 'MISTRAL_API_KEY',
                'ollama' => null,
                'openrouter' => 'OPENROUTER_API_KEY',
                'perplexity' => 'PERPLEXITY_API_KEY',
                'xai' => 'XAI_API_KEY',
                default => 'OPENAI_API_KEY',
            };

            PlatformSetting::set('ai_api_key_env_var', $envKey ?? '');
        }

        session()->flash('success', 'AI settings saved successfully.');
    }

    public function testConnection(): void
    {
        try {
            $client = app(AIClient::class);
            $response = $client->generate(
                'You are a helpful assistant.',
                'Reply with exactly: OK'
            );

            if ($response && str_contains($response, 'OK')) {
                session()->flash('success', 'Connection successful! AI model responded correctly.');
            } else {
                session()->flash('error', 'Connection made but unexpected response: '.substr($response ?? 'null', 0, 100));
            }
        } catch (\Throwable $e) {
            session()->flash('error', 'Connection failed: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.administration.ai-settings-manager');
    }
}
