<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GrammarCheckService
{
    protected $apiUrl = 'https://api.languagetool.org/v2/check';
    
    public function checkGrammar($text, $language = 'en-US')
    {
        try {
            $response = Http::post($this->apiUrl, [
                'text' => $text,
                'language' => $language,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $this->processGrammarResults($data, $text);
            }

            Log::error('Grammar check API failed', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return [
                'success' => false,
                'message' => 'Grammar check service unavailable',
                'suggestions' => []
            ];

        } catch (\Exception $e) {
            Log::error('Grammar check error', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Grammar check failed',
                'suggestions' => []
            ];
        }
    }

    protected function processGrammarResults($data, $originalText)
    {
        $suggestions = [];
        $issues = [];

        if (isset($data['matches']) && is_array($data['matches'])) {
            foreach ($data['matches'] as $match) {
                $issue = [
                    'message' => $match['message'] ?? '',
                    'shortMessage' => $match['shortMessage'] ?? '',
                    'offset' => $match['offset'] ?? 0,
                    'length' => $match['length'] ?? 0,
                    'replacements' => $match['replacements'] ?? [],
                    'rule' => $match['rule'] ?? [],
                    'context' => $match['context'] ?? [],
                    'sentence' => $match['sentence'] ?? '',
                ];

                $issues[] = $issue;

                // Get the best replacement suggestion
                if (!empty($match['replacements'])) {
                    $bestReplacement = $match['replacements'][0]['value'] ?? '';
                    if ($bestReplacement) {
                        $suggestions[] = [
                            'original' => substr($originalText, $match['offset'], $match['length']),
                            'suggestion' => $bestReplacement,
                            'message' => $match['message'],
                            'offset' => $match['offset'],
                            'length' => $match['length']
                        ];
                    }
                }
            }
        }

        return [
            'success' => true,
            'message' => count($issues) > 0 ? 'Grammar issues found' : 'No grammar issues found',
            'issues' => $issues,
            'suggestions' => $suggestions,
            'issueCount' => count($issues)
        ];
    }

    public function getCorrectedText($text, $suggestions)
    {
        $correctedText = $text;
        $offsetAdjustment = 0;

        // Sort suggestions by offset in descending order to avoid offset issues
        usort($suggestions, function($a, $b) {
            return $b['offset'] - $a['offset'];
        });

        foreach ($suggestions as $suggestion) {
            $start = $suggestion['offset'] + $offsetAdjustment;
            $length = $suggestion['length'];
            
            if ($start >= 0 && $start + $length <= strlen($correctedText)) {
                $correctedText = substr_replace($correctedText, $suggestion['suggestion'], $start, $length);
                $offsetAdjustment += strlen($suggestion['suggestion']) - $length;
            }
        }

        return $correctedText;
    }
} 