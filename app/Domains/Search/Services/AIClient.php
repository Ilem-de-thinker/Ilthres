<?php

namespace App\Domains\Search\Services;

use App\Models\PlatformSetting;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Facades\Prism;
use Prism\Prism\Text\PendingRequest;

class AIClient
{
    protected string $provider;

    protected string $model;

    protected int $timeout;

    public function __construct()
    {
        $this->provider = PlatformSetting::get('ai_provider', 'openai');
        $this->model = PlatformSetting::get('ai_model', 'gpt-4o-mini');
        $this->timeout = (int) PlatformSetting::get('ai_timeout', 30);
    }

    /**
     * Create a Prism text request configured with the platform's AI settings.
     */
    public function text(): PendingRequest
    {
        $prismProvider = $this->resolveProvider();

        return Prism::text()
            ->using($prismProvider, $this->model)
            ->withClientOptions(['timeout' => $this->timeout]);
    }

    /**
     * Send a prompt and get the generated text.
     */
    public function generate(string $systemPrompt, string $userPrompt): ?string
    {
        $response = $this->text()
            ->withSystemPrompt($systemPrompt)
            ->withPrompt($userPrompt)
            ->asText();

        return $response->text ?: null;
    }

    /**
     * Resolve the Prism provider enum from the stored provider name.
     */
    protected function resolveProvider(): Provider
    {
        $map = [
            'openai' => Provider::OpenAI,
            'anthropic' => Provider::Anthropic,
            'deepseek' => Provider::DeepSeek,
            'groq' => Provider::Groq,
            'gemini' => Provider::Gemini,
            'mistral' => Provider::Mistral,
            'ollama' => Provider::Ollama,
            'openrouter' => Provider::OpenRouter,
            'perplexity' => Provider::Perplexity,
            'xai' => Provider::XAI,
        ];

        return $map[$this->provider] ?? Provider::OpenAI;
    }

    /**
     * Get the currently configured provider name.
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Get the currently configured model name.
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * Get available provider options for the settings UI.
     */
    public static function availableProviders(): array
    {
        return [
            'openai' => 'OpenAI',
            'anthropic' => 'Anthropic',
            'deepseek' => 'DeepSeek',
            'groq' => 'Groq (Free)',
            'gemini' => 'Google Gemini',
            'mistral' => 'Mistral',
            'ollama' => 'Ollama (Local)',
            'openrouter' => 'OpenRouter',
            'perplexity' => 'Perplexity',
            'xai' => 'xAI',
        ];
    }

    /**
     * Recommended default models per provider.
     */
    public static function defaultModels(): array
    {
        return [
            'openai' => 'gpt-4o-mini',
            'anthropic' => 'claude-3-5-haiku-20241022',
            'deepseek' => 'deepseek-chat',
            'groq' => 'llama-3.3-70b-versatile',
            'gemini' => 'gemini-2.0-flash',
            'mistral' => 'mistral-large-latest',
            'ollama' => 'llama3.2',
            'openrouter' => 'openai/gpt-4o-mini',
            'perplexity' => 'sonar-pro',
            'xai' => 'grok-2-1212',
        ];
    }
}
