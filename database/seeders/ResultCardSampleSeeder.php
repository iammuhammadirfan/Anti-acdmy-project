<?php

namespace Database\Seeders;

use App\Models\IetsResult;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ResultCardSampleSeeder extends Seeder
{
    public function run(): void
    {
        $cardsDir = storage_path('app/public/results/cards');
        if (!File::exists($cardsDir)) {
            File::makeDirectory($cardsDir, 0755, true);
        }

        $publicCardsDir = public_path('storage/results/cards');
        if (!File::exists($publicCardsDir)) {
            File::makeDirectory($publicCardsDir, 0755, true);
        }

        // Helper to generate a styled 800x1000 result banner
        $makeCard = function ($filename, $bgColor, $accentColor, $testType, $studentName, $scoreLabel, $scoreVal, $subScores) use ($cardsDir, $publicCardsDir) {
            $dest = $cardsDir . '/' . $filename;
            $publicDest = $publicCardsDir . '/' . $filename;

            if (file_exists($dest)) {
                @copy($dest, $publicDest);
                return 'results/cards/' . $filename;
            }

            if (!extension_loaded('gd')) {
                return null;
            }

            $w = 800;
            $h = 1000;
            $img = imagecreatetruecolor($w, $h);

            // Background fill
            $bg = imagecolorallocate($img, $bgColor[0], $bgColor[1], $bgColor[2]);
            imagefill($img, 0, 0, $bg);

            // Card Inner Panel
            $panel = imagecolorallocate($img, max(0, $bgColor[0] - 10), max(0, $bgColor[1] - 10), max(0, $bgColor[2] - 10));
            $border = imagecolorallocate($img, $accentColor[0], $accentColor[1], $accentColor[2]);
            $white = imagecolorallocate($img, 255, 255, 255);
            $muted = imagecolorallocate($img, 180, 195, 215);
            $cardBg = imagecolorallocate($img, 255, 255, 255);
            $darkText = imagecolorallocate($img, 15, 23, 42);

            // Top Header Banner
            imagefilledrectangle($img, 40, 40, $w - 40, 140, $panel);
            imagerectangle($img, 40, 40, $w - 40, 140, $border);

            imagestring($img, 5, 60, 60, "PRIME ACADEMY & TESTING CENTRE", $white);
            imagestring($img, 5, 60, 95, "OFFICIAL CANDIDATE SCORECARD", $border);
            imagestring($img, 5, $w - 220, 75, "[" . strtoupper($testType) . " VERIFIED]", $white);

            // Congratulation section
            imagestring($img, 5, 60, 190, "CONGRATULATIONS", $border);
            imagestring($img, 5, 60, 225, strtoupper($studentName), $white);
            imagestring($img, 4, 60, 260, "On Achieving Target Band & University Acceptance", $muted);

            // Main Score Box
            imagefilledrectangle($img, 60, 310, $w - 60, 520, $cardBg);
            imagerectangle($img, 60, 310, $w - 60, 520, $border);

            imagestring($img, 5, 90, 335, strtoupper($testType) . " " . strtoupper($scoreLabel), $darkText);
            imagestring($img, 5, 90, 380, "SCORE: " . $scoreVal, $border);
            imagestring($img, 4, 90, 440, "STATUS: DESIRED BAND CLEARED (GLOBAL RECOGNITION)", $darkText);
            imagestring($img, 3, 90, 475, "Official Test Report Verified by Examiner Board", $muted);

            // 4 Module Badges
            $colW = ($w - 120) / 4;
            $keys = array_keys($subScores);
            for ($i = 0; $i < 4; $i++) {
                $x1 = 60 + ($i * $colW) + 5;
                $x2 = $x1 + $colW - 10;
                imagefilledrectangle($img, (int)$x1, 560, (int)$x2, 680, $panel);
                imagerectangle($img, (int)$x1, 560, (int)$x2, 680, $border);

                imagestring($img, 4, (int)$x1 + 15, 580, strtoupper($keys[$i]), $muted);
                imagestring($img, 5, (int)$x1 + 15, 625, (string)$subScores[$keys[$i]], $white);
            }

            // Footer
            imagefilledrectangle($img, 60, 740, $w - 60, 930, $panel);
            imagerectangle($img, 60, 740, $w - 60, 930, $border);
            imagestring($img, 5, 90, 770, "ADMISSIONS OPEN FOR NEXT SESSION", $white);
            imagestring($img, 4, 90, 810, "Contact: +92 322 8886 536", $border);
            imagestring($img, 4, 90, 845, "Shahdara Lahore & Knowledge Park Campus", $muted);
            imagestring($img, 3, 90, 885, "Verified Digital ID & Test Report Security Seal Active", $white);

            imagejpeg($img, $dest, 92);
            @copy($dest, $publicDest);
            imagedestroy($img);

            return 'results/cards/' . $filename;
        };

        // 1. Menahil Ahmad (IELTS)
        $userBanner = 'results/menahil-ahmad-ielts-result.png';
        IetsResult::updateOrCreate(
            ['student_name' => 'Menahil Ahmad'],
            [
                'test_type' => 'IELTS',
                'overall_band' => '7.0',
                'result_image' => $userBanner,
                'student_image' => $userBanner,
                'certificate_image' => $userBanner,
                'test_date' => now()->subDays(5),
                'is_featured' => true,
                'description' => 'Listening: 8.5 | Reading: 6.5 | Writing: 6.5 | Speaking: 7.0 — Over All Score: 7.0',
            ]
        );

        // 2. IELTS Academic - Hamza Tariq
        $ieltsCard = $makeCard(
            'card-hamza-ielts.jpg',
            [15, 23, 42],
            [225, 29, 72],
            'IELTS',
            'Hamza Tariq',
            'Overall Band',
            '8.5',
            ['Listening' => '9.0', 'Reading' => '8.5', 'Writing' => '8.0', 'Speaking' => '8.5']
        );
        IetsResult::updateOrCreate(
            ['student_name' => 'Hamza Tariq'],
            [
                'test_type' => 'IELTS',
                'overall_band' => '8.5',
                'result_image' => $ieltsCard ?: $userBanner,
                'student_image' => $ieltsCard ?: $userBanner,
                'certificate_image' => $ieltsCard ?: $userBanner,
                'test_date' => now()->subDays(12),
                'is_featured' => true,
                'description' => 'Scored Band 8.5 on first attempt! Admitted to Oxford University MSc Computer Science.',
            ]
        );

        // 3. PTE Academic - Ayesha Noor
        $pteCard1 = $makeCard(
            'card-ayesha-pte.jpg',
            [16, 24, 40],
            [217, 119, 6],
            'PTE',
            'Ayesha Noor',
            'Overall Score',
            '86',
            ['Listening' => '88', 'Reading' => '84', 'Writing' => '86', 'Speaking' => '90']
        );
        IetsResult::updateOrCreate(
            ['student_name' => 'Ayesha Noor'],
            [
                'test_type' => 'PTE',
                'overall_band' => '86',
                'result_image' => $pteCard1 ?: $userBanner,
                'student_image' => $pteCard1 ?: $userBanner,
                'certificate_image' => $pteCard1 ?: $userBanner,
                'test_date' => now()->subDays(8),
                'is_featured' => true,
                'description' => 'Achieved PTE Score 86/90 in 4 weeks of Intensive Pearson Mock tests.',
            ]
        );

        // 4. PTE Academic - Bilal Raza
        $pteCard2 = $makeCard(
            'card-bilal-pte.jpg',
            [15, 23, 42],
            [234, 88, 12],
            'PTE',
            'Bilal Raza',
            'Overall Score',
            '79',
            ['Listening' => '81', 'Reading' => '78', 'Writing' => '77', 'Speaking' => '82']
        );
        IetsResult::updateOrCreate(
            ['student_name' => 'Bilal Raza'],
            [
                'test_type' => 'PTE',
                'overall_band' => '79',
                'result_image' => $pteCard2 ?: $userBanner,
                'student_image' => $pteCard2 ?: $userBanner,
                'certificate_image' => $pteCard2 ?: $userBanner,
                'test_date' => now()->subDays(15),
                'is_featured' => true,
                'description' => 'Target score achieved for Australian PR visa nomination (Superior English level).',
            ]
        );

        // 5. TOEFL iBT - Zainab Fatima
        $toeflCard1 = $makeCard(
            'card-zainab-toefl.jpg',
            [24, 24, 48],
            [99, 102, 241],
            'TOEFL',
            'Zainab Fatima',
            'Total Score',
            '112',
            ['Reading' => '29', 'Listening' => '29', 'Speaking' => '27', 'Writing' => '27']
        );
        IetsResult::updateOrCreate(
            ['student_name' => 'Zainab Fatima'],
            [
                'test_type' => 'TOEFL',
                'overall_band' => '112',
                'result_image' => $toeflCard1 ?: $userBanner,
                'student_image' => $toeflCard1 ?: $userBanner,
                'certificate_image' => $toeflCard1 ?: $userBanner,
                'test_date' => now()->subDays(10),
                'is_featured' => true,
                'description' => 'Scored 112/120 on TOEFL iBT. Secured Fulbright Scholarship for Columbia University.',
            ]
        );

        // 6. TOEFL iBT - Usman Ali
        $toeflCard2 = $makeCard(
            'card-usman-toefl.jpg',
            [20, 20, 42],
            [129, 140, 248],
            'TOEFL',
            'Usman Ali',
            'Total Score',
            '105',
            ['Reading' => '28', 'Listening' => '27', 'Speaking' => '25', 'Writing' => '25']
        );
        IetsResult::updateOrCreate(
            ['student_name' => 'Usman Ali'],
            [
                'test_type' => 'TOEFL',
                'overall_band' => '105',
                'result_image' => $toeflCard2 ?: $userBanner,
                'student_image' => $toeflCard2 ?: $userBanner,
                'certificate_image' => $toeflCard2 ?: $userBanner,
                'test_date' => now()->subDays(18),
                'is_featured' => true,
                'description' => 'Cleared TOEFL cutoff for Harvard Kennedy School graduate program admission.',
            ]
        );
    }
}
