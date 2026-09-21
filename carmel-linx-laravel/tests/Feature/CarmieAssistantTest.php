<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\CarmiePlaybookService;
use Illuminate\Support\Facades\Blade;

class CarmieAssistantTest extends TestCase
{
    public function test_carmie_suggestions_endpoint_returns_suggestions()
    {
        $response = $this->getJson('/api/carmie/suggestions');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'suggestions' => [
                '*' => ['label', 'query']
            ]
        ]);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertNotEmpty($response->json('suggestions'));
    }

    public function test_carmie_suggestions_filtered_by_category()
    {
        $response = $this->getJson('/api/carmie/suggestions?category=2021');

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $suggestions = $response->json('suggestions');
        $this->assertNotEmpty($suggestions);
        $this->assertStringContainsString('2021', $suggestions[0]['label']);
    }

    public function test_carmie_ask_with_empty_query()
    {
        $response = $this->postJson('/api/carmie/ask', [
            'query' => ''
        ]);

        $response->assertStatus(200);
        $this->assertEquals('ERROR', $response->json('status'));
        $this->assertStringContainsString('Carmie', $response->json('reply'));
    }

    public function test_carmie_ask_matches_playbook_topic()
    {
        $response = $this->postJson('/api/carmie/ask', [
            'query' => 'How is Revision 2021 Major Project evaluated with 75 CIA and 50 ESE?'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertEquals('local_playbook', $response->json('source'));
        $this->assertNotNull($response->json('matched_topic'));
        $this->assertStringContainsString('Major Project', $response->json('reply'));
    }

    public function test_carmie_ask_contextual_guide()
    {
        $response = $this->postJson('/api/carmie/ask', [
            'query' => 'Where am I and what can I do on this page?',
            'current_url' => '/r26/classroom/theory?id=1'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertStringContainsString('Revision 2026 Theory', $response->json('reply'));
    }

    public function test_carmie_ask_fallback_for_unknown_query()
    {
        $response = $this->postJson('/api/carmie/ask', [
            'query' => 'xyz random unknown query 99887766'
        ]);

        $response->assertStatus(200);
        $this->assertEquals('SUCCESS', $response->json('status'));
        $this->assertEquals('local_playbook', $response->json('source'));
        $this->assertStringContainsString('Carmie', $response->json('reply'));
    }

    public function test_carmie_component_renders_cleanly()
    {
        $rendered = Blade::render('<x-carmie-assistant />');
        $this->assertStringContainsString('carmieWidgetContainer', $rendered);
        $this->assertStringContainsString('carmieFabBtn', $rendered);
        $this->assertStringContainsString('carmieChatModal', $rendered);
    }
}
