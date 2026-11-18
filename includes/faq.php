<?php
require_once __DIR__ . '/db.php';

const FAQ_STOPWORDS = ['apa','yang','dan','di','ke','dari','untuk','itu','ini','saya','bagaimana','gimana','berapa',
    'apakah','kapan','siapa','dengan','atau','ada','bisa','cara','tentang','mau','ingin','tolong','asabri','pt','nya','sih','kah'];

function faq_tokens(string $s): array
{
    $s = mb_strtolower($s, 'UTF-8');
    $parts = preg_split('/[^\p{L}\p{N}]+/u', $s, -1, PREG_SPLIT_NO_EMPTY);
    return array_values(array_unique(array_filter($parts, fn($w) => mb_strlen($w) > 1 && !in_array($w, FAQ_STOPWORDS, true))));
}

// Skor sederhana: kata yang cocok di pertanyaan/kata kunci bernilai 3, di kategori 2, di jawaban 1.
function faq_search(string $query, int $limit = 3): array
{
    $tokens = faq_tokens($query);
    if (!$tokens) {
        return [];
    }
    $rows = db()->query('SELECT id, kategori, pertanyaan, jawaban, kata_kunci, sumber FROM faq')->fetchAll();
    $scored = [];
    foreach ($rows as $r) {
        $head = faq_tokens($r['pertanyaan'] . ' ' . $r['kata_kunci']);
        $kat = faq_tokens($r['kategori']);
        $body = faq_tokens($r['jawaban']);
        $score = 0;
        foreach ($tokens as $t) {
            foreach ($head as $h) {
                if ($h === $t || (mb_strlen($t) >= 4 && str_starts_with($h, $t))) { $score += 3; break; }
            }
            if (in_array($t, $kat, true)) $score += 2;
            if (in_array($t, $body, true)) $score += 1;
        }
        if ($score > 0) {
            $r['score'] = $score;
            $scored[] = $r;
        }
    }
    usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
    return array_slice($scored, 0, $limit);
}
