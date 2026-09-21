<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use App\Services\CarmiePlaybookService;

class CarmieAssistantController extends Controller
{
    protected CarmiePlaybookService $playbookService;

    public function __construct(CarmiePlaybookService $playbookService)
    {
        $this->playbookService = $playbookService;
    }

    /**
     * Handle chat messages and questions from users to Carmie (Deterministic Local Playbook).
     */
    public function ask(Request $request): JsonResponse
    {
        $query = trim((string)$request->input('query', ''));
        if ($query === '') {
            return response()->json([
                'status' => 'ERROR',
                'reply' => "Hi! I'm **Carmie**, your Carmel-linx Academic Assistant! 😊 How can I help you today?"
            ]);
        }

        $context = [
            'current_url'  => (string)$request->input('current_url', ''),
            'user_role'    => (string)Session::get('userRole', $request->input('user_role', 'Staff')),
            'subject_code' => (string)$request->input('subject_code', ''),
            'subject_name' => (string)$request->input('subject_name', ''),
        ];

        $normalizedQuery = $this->playbookService->normalizeQuery($query);

        // Search verified Carmel-linx local playbook
        $matchedTopic = $this->playbookService->search($query, $context);

        if ($matchedTopic) {
            $formattedSteps = "";
            foreach ($matchedTopic['steps'] as $step) {
                $formattedSteps .= "• " . $step . "\n\n";
            }

            $revisions = $matchedTopic['revisions'] ?? [];
            $badge = "";
            if (in_array('2021', $revisions, true) && !in_array('2026', $revisions, true)) {
                $badge = "📘 **[Revision 2021 - SBTE Keralam]**\n\n";
            } elseif (in_array('2026', $revisions, true) && !in_array('2021', $revisions, true)) {
                $badge = "📙 **[Revision 2026 - Outcome-Based Curriculum]**\n\n";
            } else {
                $badge = "🎓 **[Universal Academic Workflow - SBTE Keralam]**\n\n";
            }

            $refText = !empty($matchedTopic['manual_reference'])
                ? "📖 **Manual Reference:** " . $matchedTopic['manual_reference'] . "\n\n"
                : "";

            $reply = $badge
                   . "### " . $matchedTopic['title'] . "\n\n"
                   . $refText
                   . $matchedTopic['summary'] . "\n\n"
                   . "**Step-by-step instructions:**\n\n"
                   . $formattedSteps
                   . "\n🔗 *[Read full details in Carmel-Linx User Manual](/docs/carmel_linx_user_manual.html)*";

            // Record in evolving question model
            $this->playbookService->logUserQuestion($query, $normalizedQuery, $context, $matchedTopic, $reply, 'local_playbook');

            return response()->json([
                'status'       => 'SUCCESS',
                'reply'        => $reply,
                'matched_topic'=> $matchedTopic['title'] ?? null,
                'action_label' => $matchedTopic['action_label'] ?? 'Open Workspace',
                'action_route' => $matchedTopic['action_route'] ?? '#',
                'source'       => 'local_playbook'
            ]);
        }

        // Default friendly fallback
        $fallback = "Hi! I'm **Carmie**, your Carmel-linx academic companion and guide! 🌸\n\n"
                  . "I can provide precise, syllabus-verified answers categorized by curriculum revision:\n\n"
                  . "📘 **Revision 2021 Workspaces:**\n"
                  . "• **Major Project (2021)**: 75 CIA (40% diary + 40% review + 20% attendance) + 50 ESE (2 examiners, 8 rubrics) = 125M Total & Group Breakdown reports\n"
                  . "• **Seminar (2021)**: 75 CIA Only, two-faculty evaluation (guide + committee)\n"
                  . "• **Drawing (2021)**: Practical/Lab criteria, drawing plate rubrics & continuous CIA\n"
                  . "• **Theory & Lab (2021)**: SITTTR lesson planner, 75M formative/summative\n\n"
                  . "📙 **Revision 2026 Workspaces:**\n"
                  . "• **Theory (2026)**: 40 CIE (Table 2.1 Attendance 5M + Table 2.2 Self-Learning 15M + Series 20M) + 60 ESE\n"
                  . "• **CO-PO Matrix**: Interactive PO1–PO11 mapping with continuous live autosave\n"
                  . "• **Practicum (2026)**: 90-Hour combined Theory + Practical workspace\n\n"
                  . "📊 **Universal Attainment & Surveys:**\n"
                  . "• 80% Direct + 20% Indirect Online Student Exit Survey across all 5 virtual classrooms.\n\n"
                  . "Click any revision tab above or type your question!\n\n"
                  . "📖 *Reference: [Carmel-Linx Master User Manual](/docs/carmel_linx_user_manual.html)*";

        $this->playbookService->logUserQuestion($query, $normalizedQuery, $context, null, $fallback, 'fallback');

        return response()->json([
            'status'       => 'SUCCESS',
            'reply'        => $fallback,
            'source'       => 'local_playbook'
        ]);
    }

    /**
     * Get starter suggestions based on context and category.
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $url = (string)$request->input('current_url', '');
        $role = (string)Session::get('userRole', $request->input('user_role', 'Staff'));
        $category = (string)$request->input('category', 'all');

        $suggestions = $this->playbookService->getStarterSuggestions($url, $role, $category);

        return response()->json([
            'status' => 'SUCCESS',
            'suggestions' => $suggestions
        ]);
    }
}
