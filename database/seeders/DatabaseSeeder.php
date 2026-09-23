<?php

namespace Database\Seeders;

use App\Http\Controllers\Admin\UserController;
use App\Models\AppointmentSetting;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\CampusGallery;
use App\Models\Classroom;
use App\Models\Faq;
use App\Models\HistoryTimeline;
use App\Models\IetsProgram;
use App\Models\IetsResult;
use App\Models\PageSection;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Statistic;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles & Permissions
        $roles = [
            'super-admin' => ['name' => 'Super Administrator', 'description' => 'Unrestricted root system access'],
            'admin' => ['name' => 'Academy Administrator', 'description' => 'Academic and operational supervisor'],
            'editor' => ['name' => 'Content Editor', 'description' => 'Manages blogs, gallery, videos, and FAQs'],
            'counselor' => ['name' => 'Admissions Counselor', 'description' => 'Manages appointments, messages, and student inquiries'],
        ];

        $createdRoles = [];
        foreach ($roles as $slug => $data) {
            $createdRoles[$slug] = Role::firstOrCreate(['slug' => $slug], [
                'name' => $data['name'],
                'description' => $data['description'],
            ]);
        }

        $actions = ['view', 'create', 'edit', 'delete', 'publish'];
        $modules = UserController::$modules;

        $allPermissionIds = [];
        $editorPermissionIds = [];
        $counselorPermissionIds = [];

        foreach ($modules as $moduleKey => $moduleName) {
            foreach ($actions as $act) {
                $perm = Permission::firstOrCreate(
                    ['module' => $moduleKey, 'action' => $act],
                    ['description' => "Can {$act} {$moduleName}"]
                );
                $allPermissionIds[] = $perm->id;

                if (in_array($moduleKey, ['blog', 'gallery', 'videos', 'faq', 'media'])) {
                    $editorPermissionIds[] = $perm->id;
                }

                if (in_array($moduleKey, ['appointments', 'calendar', 'contact', 'students', 'teachers'])) {
                    $counselorPermissionIds[] = $perm->id;
                }
            }
        }

        // Attach permissions
        $createdRoles['admin']->permissions()->sync($allPermissionIds);
        $createdRoles['editor']->permissions()->sync($editorPermissionIds);
        $createdRoles['counselor']->permissions()->sync($counselorPermissionIds);

        // 2. Default Users
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@antiacademy.edu'],
            [
                'name' => 'Master Administrator',
                'password' => Hash::make('Admin@123456'),
                'phone' => '+1 (555) 234-5678',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->roles()->sync([$createdRoles['super-admin']->id]);

        $counselor = User::updateOrCreate(
            ['email' => 'counselor@antiacademy.edu'],
            [
                'name' => 'Sarah Jenkins (Lead Counselor)',
                'password' => Hash::make('Counselor@123'),
                'phone' => '+1 (555) 987-6543',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
        $counselor->roles()->sync([$createdRoles['counselor']->id]);

        // 3. Hero Sliders
        $sliders = [
            [
                'heading' => 'Master IETS with Official Cambridge Certified Mentors',
                'short_description' => 'Target Band 8.0+ with personalized 1-on-1 coaching, real exam mock trials, and cutting-edge acoustic AI speech evaluation labs.',
                'image' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'Book Free Evaluation',
                'button_url' => '/appointments',
                'secondary_button_text' => 'Explore Programs',
                'secondary_button_url' => '/iets',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'heading' => 'Global University Admissions & Visa Mentorship',
                'short_description' => 'Direct partnerships with leading universities in the UK, Canada, Australia, and USA. Transform high test scores into ivy-league admissions.',
                'image' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'Meet Our Faculty',
                'button_url' => '/teachers',
                'secondary_button_text' => 'Campus Tour',
                'secondary_button_url' => '/gallery',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'heading' => 'State-of-the-Art Smart Speech & Testing Labs',
                'short_description' => 'Experience live computer-delivered and paper-based mock examinations in soundproof digital simulation booths identical to test day.',
                'image' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=1920&auto=format&fit=crop',
                'button_text' => 'Explore Facilities',
                'button_url' => '/classrooms',
                'secondary_button_text' => 'Contact Us',
                'secondary_button_url' => '/contact',
                'display_order' => 3,
                'status' => true,
            ],
        ];

        foreach ($sliders as $s) {
            Slider::updateOrCreate(['heading' => $s['heading']], $s);
        }

        // 4. Statistics
        $statistics = [
            ['metric_key' => 'band_success_rate', 'label' => 'Band 8.0+ Success Rate', 'value' => '99.4', 'suffix' => '%', 'icon' => 'academic-cap', 'display_order' => 1, 'is_active' => true],
            ['metric_key' => 'certified_examiners', 'label' => 'Certified Senior Examiners', 'value' => '45', 'suffix' => '+', 'icon' => 'user-group', 'display_order' => 2, 'is_active' => true],
            ['metric_key' => 'graduates_enrolled', 'label' => 'Graduates Abroad', 'value' => '18,500', 'suffix' => '+', 'icon' => 'globe-alt', 'display_order' => 3, 'is_active' => true],
            ['metric_key' => 'satisfaction_index', 'label' => 'Student Satisfaction', 'value' => '99.8', 'suffix' => '%', 'icon' => 'star', 'display_order' => 4, 'is_active' => true],
        ];
        foreach ($statistics as $stat) {
            Statistic::updateOrCreate(['metric_key' => $stat['metric_key']], $stat);
        }

        // 5. History Timelines
        $timelines = [
            ['year' => '2012', 'title' => 'Inception & Academic Charter', 'description' => 'Founded by senior Cambridge language examiners with the mission of delivering rigorous English fluency and standardized test mastery.', 'display_order' => 1, 'status' => true],
            ['year' => '2015', 'title' => 'British Council & IDP Official Test Venue', 'description' => 'Accredited as an authorized regional testing and preparation hub with capacity for 500 examinees weekly.', 'display_order' => 2, 'status' => true],
            ['year' => '2019', 'title' => 'Launch of Digital Smart Acoustic Labs', 'description' => 'Pioneered computer-delivered test simulation booths equipped with high-fidelity speech recording and pronunciation AI.', 'display_order' => 3, 'status' => true],
            ['year' => '2023', 'title' => 'Global University Partnership Alliance', 'description' => 'Established formal pathways with 150+ accredited universities offering conditional admission and scholarship waivers.', 'display_order' => 4, 'status' => true],
            ['year' => '2025', 'title' => 'Next-Gen Agentic AI Advisory Integration', 'description' => 'Integrated real-time database-driven autonomous AI tutors to assist students 24/7 with essay feedback and exam scheduling.', 'display_order' => 5, 'status' => true],
        ];
        foreach ($timelines as $tl) {
            HistoryTimeline::updateOrCreate(['title' => $tl['title']], $tl);
        }

        // 6. Teachers
        $teachers = [
            [
                'name' => 'Dr. Arthur Pendleton',
                'slug' => 'dr-arthur-pendleton',
                'designation' => 'Director of Academic Affairs & Chief Examiner',
                'qualification' => 'Ph.D. in Applied Linguistics (Oxford), Cambridge DELTA',
                'experience' => '18 Years Experience',
                'subject' => 'Academic Writing Task 2 & High-Stakes Lexical Precision',
                'classes_taught' => 'Academic Writing Masterclass, Executive Speaking',
                'bio' => 'Former lead IELTS testing supervisor with nearly two decades of international pedagogical experience. Dr. Pendleton has trained over 6,000 scholars to reach Band 8.5+ through structural argumentation frameworks.',
                'email' => 'a.pendleton@antiacademy.edu',
                'phone' => '+1 (555) 301-4411',
                'profile_image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=600&auto=format&fit=crop',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'name' => 'Elena Rostova, M.Ed.',
                'slug' => 'elena-rostova',
                'designation' => 'Head of Speaking Fluency & Phonetics',
                'qualification' => 'Master of Education (TESOL), Cambridge CELTA Trainer',
                'experience' => '14 Years Experience',
                'subject' => 'Pronunciation, Accent Modulation & Idiomatic Fluency',
                'classes_taught' => 'Speaking Band 9 Clinics, Phonetics & Shadowing',
                'bio' => 'Specializes in overcoming test anxiety, impromptu coherence, and complex grammatical structures under pressure. Elena has a 98% track record of elevating speaking scores by a minimum of 1.5 bands.',
                'email' => 'e.rostova@antiacademy.edu',
                'phone' => '+1 (555) 301-4422',
                'profile_image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'name' => 'Marcus Chen',
                'slug' => 'marcus-chen',
                'designation' => 'Lead Reading & Analytical Speed Mentor',
                'qualification' => 'B.A. English Literature (Columbia), IDP Certified Master Trainer',
                'experience' => '11 Years Experience',
                'subject' => 'Skimming, Scanning & True/False/Not Given Traps',
                'classes_taught' => 'Speed Reading Dynamics, Academic Passage Deconstruction',
                'bio' => 'Developer of the 45-Minute Diagnostic Comprehension Framework. Marcus focuses on academic passage analysis, identifying distractor patterns, and pacing discipline for Band 9.0 reading achievement.',
                'email' => 'm.chen@antiacademy.edu',
                'phone' => '+1 (555) 301-4433',
                'profile_image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=600&auto=format&fit=crop',
                'display_order' => 3,
                'status' => true,
            ],
            [
                'name' => 'Dr. Sarah Al-Mansoor',
                'slug' => 'dr-sarah-al-mansoor',
                'designation' => 'Listening Lab Director & Phonology Specialist',
                'qualification' => 'Ph.D. in Phonetics & Speech Science (UCL)',
                'experience' => '15 Years Experience',
                'subject' => 'Connected Speech, Multilingual Accents & Rapid Note-taking',
                'classes_taught' => 'Acoustic Shadowing Lab, Listening Section 4 Mastery',
                'bio' => 'Pioneer of the Acoustic Shadowing Technique used across our soundproof digital booths. Dr. Sarah trains students to anticipate speaker transitions and trap answers in Section 3 and 4 dialogues.',
                'email' => 's.almansoor@antiacademy.edu',
                'phone' => '+1 (555) 301-4444',
                'profile_image' => 'https://images.unsplash.com/photo-1580894732444-8ecded7900cd?q=80&w=600&auto=format&fit=crop',
                'display_order' => 4,
                'status' => true,
            ],
            [
                'name' => 'David O\'Connor',
                'slug' => 'david-oconnor',
                'designation' => 'General Training & Immigration Track Lead',
                'qualification' => 'M.A. International Communication (Melbourne), CELTA',
                'experience' => '12 Years Experience',
                'subject' => 'Express Entry CLB 9/10, Formal & Semi-Formal Correspondence',
                'classes_taught' => 'General Training Fast-Track, PR Strategy Clinics',
                'bio' => 'David oversees candidates targeting permanent residency and professional registration in Canada, Australia, and New Zealand, providing tailored letter writing and workplace dialogue workshops.',
                'email' => 'd.oconnor@antiacademy.edu',
                'phone' => '+1 (555) 301-4455',
                'profile_image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=600&auto=format&fit=crop',
                'display_order' => 5,
                'status' => true,
            ],
        ];

        foreach ($teachers as $t) {
            Teacher::updateOrCreate(['slug' => $t['slug']], $t);
        }

        // 7. Classrooms
        $classrooms = [
            [
                'title' => 'Oxford High-Stakes Simulation Theatre',
                'slug' => 'oxford-high-stakes-simulation-theatre',
                'description' => 'Designed for full-length timed mock tests recreating official Cambridge paper-based exam protocol under strict supervision.',
                'images' => ['https://images.unsplash.com/photo-1577495508048-b635879837f1?q=80&w=800&auto=format&fit=crop'],
                'capacity' => 60,
                'facilities' => ['Tiered Ergonomic Seating', 'Individual HD Display Terminals', 'Dual High-Fidelity Audio Monitors', 'Real-Time Clock & Proctoring Hub'],
                'class_type' => 'Seminar',
                'status' => true,
            ],
            [
                'title' => 'Smart Computer-Delivered Digital Acoustic Lab',
                'slug' => 'smart-computer-delivered-digital-acoustic-lab',
                'description' => 'Dedicated digital facility for computer-delivered IELTS training with immediate computerized band breakdown and audio diagnostic playback.',
                'images' => ['https://images.unsplash.com/photo-1516321318423-f06f85e504b3?q=80&w=800&auto=format&fit=crop'],
                'capacity' => 35,
                'facilities' => ['Noise-Cancelling Studio Headsets', 'High-Speed Secure Terminals', 'Speech Recognition Software', 'Ergonomic Keyboards'],
                'class_type' => 'Lab',
                'status' => true,
            ],
            [
                'title' => 'Cambridge Interactive Workshop Suite',
                'slug' => 'cambridge-interactive-workshop-suite',
                'description' => 'Fosters intensive collaborative peer reviews, live essay deconstruction on smartboards, and group strategy masterclasses.',
                'images' => ['https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=800&auto=format&fit=crop'],
                'capacity' => 24,
                'facilities' => ['Interactive 4K Smartboards', 'Modular Collaboration Pods', 'Extensive Resource Library', 'Wireless Document Presenters'],
                'class_type' => 'Lecture',
                'status' => true,
            ],
            [
                'title' => 'Private 1-on-1 Speaking Evaluation Studios',
                'slug' => 'private-speaking-evaluation-studios',
                'description' => 'Four individual soundproof testing booths where candidates conduct face-to-face speaking evaluations recorded for phonetic critique.',
                'images' => ['https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=800&auto=format&fit=crop'],
                'capacity' => 4,
                'facilities' => ['Acoustic Foam Soundproofing', 'Broadcast-Grade Cardioid Microphones', 'Studio Video Recording', 'Examiner Assessment Console'],
                'class_type' => 'Audio-Visual',
                'status' => true,
            ],
        ];

        foreach ($classrooms as $c) {
            Classroom::updateOrCreate(['slug' => $c['slug']], $c);
        }

        // 8. Campus Gallery
        $galleries = [
            ['title' => 'Central Academic Atrium & Student Commons', 'category' => 'classroom', 'image_path' => 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=800&auto=format&fit=crop', 'caption' => 'The vibrant architectural centerpiece of Anti Academy with private study niches and coffee bar.', 'display_order' => 1, 'status' => true],
            ['title' => 'Annual Global Scholar Convocation', 'category' => 'events', 'image_path' => 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?q=80&w=800&auto=format&fit=crop', 'caption' => 'Celebrating alumni who achieved Band 8.5+ and secured admissions into world-leading universities.', 'display_order' => 2, 'status' => true],
            ['title' => 'Advanced Digital Listening Station', 'category' => 'lab', 'image_path' => 'https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?q=80&w=800&auto=format&fit=crop', 'caption' => 'Students training with high-resolution acoustic soundscapes to master tricky regional dialects.', 'display_order' => 3, 'status' => true],
            ['title' => 'Interactive Writing Feedback Session', 'category' => 'student_activity', 'image_path' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?q=80&w=800&auto=format&fit=crop', 'caption' => 'Master trainers deconstructing Band 9 model essays with real-time digital markup.', 'display_order' => 4, 'status' => true],
            ['title' => 'Executive Language Lounge', 'category' => 'library', 'image_path' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=800&auto=format&fit=crop', 'caption' => 'Quiet reading areas stocked with international newspapers, academic journals, and prep manuals.', 'display_order' => 5, 'status' => true],
            ['title' => 'Official Test Day Simulation Briefing', 'category' => 'events', 'image_path' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=800&auto=format&fit=crop', 'caption' => 'Weekly weekend full mock test registration, security checkpoint, and timing protocol.', 'display_order' => 6, 'status' => true],
        ];

        foreach ($galleries as $g) {
            CampusGallery::updateOrCreate(['title' => $g['title']], $g);
        }

        // 9. IETS Programs
        $programs = [
            [
                'title' => 'IETS Academic Comprehensive (Target Band 8.0+)',
                'slug' => 'iets-academic-comprehensive',
                'category' => 'writing',
                'summary' => 'Our flagship intensive program engineered specifically for prospective medical practitioners, graduate scholars, and doctoral candidates aiming for elite university admission.',
                'content' => 'Full 12-week immersive coaching with 12 proctored mock exams, individualized feedback, and audio speech diagnostics.',
                'features' => [
                    '12 Full-Length Proctored Mock Exams with Examiner Feedback',
                    'Unlimited 1-on-1 Speaking Simulations with Dr. Pendleton & Team',
                    'Access to 24/7 Digital Speech & Acoustic Lab',
                    'Official Cambridge Prep Manuals & Personalized Error Journal',
                    'Guaranteed Score Progression or Free Batch Repeat',
                ],
                'image' => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'title' => 'IETS General Training Fast-Track (Immigration & PR)',
                'slug' => 'iets-general-training-fast-track',
                'category' => 'general',
                'summary' => 'Tailored specifically for Canadian Express Entry, Australian SkillSelect, and New Zealand skilled migration applicants needing max CRS score points.',
                'content' => 'Comprehensive 8-week module emphasizing professional correspondence, workplace reading comprehension, and social fluency.',
                'features' => [
                    '8 Dedicated General Training Mock Tests',
                    'CLB Conversion Calculator & PR Strategy Advisory',
                    'Flexible Evening & Weekend Batches for Working Professionals',
                    'Direct 1-on-1 Feedback on Every Letter Submitted',
                ],
                'image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?q=80&w=800&auto=format&fit=crop',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'title' => 'Intensive 30-Day Score Booster Bootcamp',
                'slug' => 'intensive-30-day-score-booster',
                'category' => 'mock_test',
                'summary' => 'An aggressive, high-velocity workshop designed for candidates who have previously attempted the test or have an urgent test deadline within weeks.',
                'content' => 'Rapid fault isolation, writing template eradication, and speed listening drills designed to boost 1 to 1.5 bands in 30 days.',
                'features' => [
                    'Daily 2-Hour High-Intensity Live Drills',
                    'Rapid 24-Hour Essay Turnaround with Line-by-Line Correction',
                    'Targeted Weak-Area Diagnostic Sessions',
                    'Emergency Exam Readiness Clearance Certificate',
                ],
                'image' => 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?q=80&w=800&auto=format&fit=crop',
                'display_order' => 3,
                'status' => true,
            ],
        ];

        foreach ($programs as $p) {
            IetsProgram::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 10. IETS Results
        $results = [
            [
                'student_name' => 'Alexander Wright',
                'student_image' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=400&auto=format&fit=crop',
                'test_type' => 'IELTS Academic',
                'overall_band' => 8.5,
                'listening_score' => 9.0,
                'reading_score' => 9.0,
                'writing_score' => 7.5,
                'speaking_score' => 8.5,
                'test_date' => '2025-08-15',
                'description' => 'Dr. Pendleton transformed how I approached Writing Task 2. Oxford University accepted my score with distinction!',
                'is_featured' => true,
            ],
            [
                'student_name' => 'Mei-Ling Sophia Zhou',
                'student_image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?q=80&w=400&auto=format&fit=crop',
                'test_type' => 'IELTS Academic',
                'overall_band' => 8.5,
                'listening_score' => 8.5,
                'reading_score' => 9.0,
                'writing_score' => 8.0,
                'speaking_score' => 8.5,
                'test_date' => '2025-09-02',
                'description' => 'Within 6 weeks at Anti Academy, I hit an overall 8.5 and got into Johns Hopkins Bioengineering program!',
                'is_featured' => true,
            ],
            [
                'student_name' => 'Tariq Al-Fahim',
                'student_image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=400&auto=format&fit=crop',
                'test_type' => 'IELTS General',
                'overall_band' => 8.0,
                'listening_score' => 8.5,
                'reading_score' => 8.0,
                'writing_score' => 7.5,
                'speaking_score' => 8.0,
                'test_date' => '2025-10-18',
                'description' => 'David O\'Connor\'s General Training sessions gave me the exact CLB 10 points needed for Canadian Express Entry.',
                'is_featured' => true,
            ],
            [
                'student_name' => 'Camila Fernandes',
                'student_image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=400&auto=format&fit=crop',
                'test_type' => 'IELTS Academic',
                'overall_band' => 8.0,
                'listening_score' => 8.5,
                'reading_score' => 8.5,
                'writing_score' => 7.0,
                'speaking_score' => 8.0,
                'test_date' => '2025-11-20',
                'description' => 'The speaking clinics with Elena eliminated my pronunciation anxieties. Melbourne Law School confirmed my admission!',
                'is_featured' => true,
            ],
        ];

        foreach ($results as $res) {
            IetsResult::updateOrCreate(
                [
                    'student_name' => $res['student_name'],
                    'test_date' => $res['test_date'],
                ],
                $res
            );
        }

        // 11. Video Categories & Videos
        $vCatStrategy = VideoCategory::updateOrCreate(
            ['slug' => 'band-9-strategies'],
            [
                'name' => 'Band 9 Strategies & Masterclasses',
                'status' => true,
            ]
        );
        $vCatStories = VideoCategory::updateOrCreate(
            ['slug' => 'student-transformations'],
            [
                'name' => 'Student Transformations',
                'status' => true,
            ]
        );

        Video::updateOrCreate(
            ['slug' => 'how-to-score-band-9-writing-task-2'],
            [
                'category_id' => $vCatStrategy->id,
                'title' => 'How to Score Band 9 in Academic Writing Task 2 — Complete Breakdown',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop',
                'description' => 'Dr. Pendleton walks through paragraph balance, concession arguments, and advanced cohesive devices that examiners award top marks for.',
                'published_at' => now()->subDays(10),
                'status' => true,
            ]
        );

        Video::updateOrCreate(
            ['slug' => 'mastering-listening-section-3'],
            [
                'category_id' => $vCatStrategy->id,
                'title' => 'Mastering Multiple Choice & Traps in IELTS Listening Section 3',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?q=80&w=800&auto=format&fit=crop',
                'description' => 'Learn how to detect distractors, synonym substitution, and speaker hesitation before writing your answer.',
                'published_at' => now()->subDays(15),
                'status' => true,
            ]
        );

        Video::updateOrCreate(
            ['slug' => 'sophia-band-8-5-journey'],
            [
                'category_id' => $vCatStories->id,
                'title' => 'From Band 6.5 to 8.5 in 60 Days: Sophia\'s Journey to Johns Hopkins',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'thumbnail' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=800&auto=format&fit=crop',
                'description' => 'Sophia explains how daily targeted diagnostic feedback helped overcome writing blocks and secure full scholarship funding.',
                'published_at' => now()->subDays(20),
                'status' => true,
            ]
        );

        // 12. Blog Categories, Tags, and Posts
        $bCatExam = BlogCategory::updateOrCreate(['slug' => 'exam-tactics-prep'], ['name' => 'Exam Tactics & Prep']);
        $bCatAbroad = BlogCategory::updateOrCreate(['slug' => 'study-abroad-visas'], ['name' => 'Study Abroad & Visas']);

        $tag1 = BlogTag::updateOrCreate(['slug' => 'writing-task-2'], ['name' => 'Writing Task 2']);
        $tag2 = BlogTag::updateOrCreate(['slug' => 'speaking-band-8'], ['name' => 'Speaking Band 8']);
        $tag3 = BlogTag::updateOrCreate(['slug' => 'cambridge-standards'], ['name' => 'Cambridge Standards']);
        $tag4 = BlogTag::updateOrCreate(['slug' => 'express-entry'], ['name' => 'Express Entry']);

        $post1 = Blog::updateOrCreate(
            ['slug' => '5-fatal-flaws-academic-writing-task-2'],
            [
                'author_id' => $superAdmin->id,
                'category_id' => $bCatExam->id,
                'title' => 'The 5 Most Fatal Flaws Candidates Make in Academic Writing Task 2',
                'excerpt' => 'Examiners mark thousands of essays each month. Discover the subtle structural blunders that keep capable students trapped at Band 6.5.',
                'content' => "<p>Achieving a Band 8 or higher in the Academic Writing module requires more than just rich vocabulary. In fact, overusing flowery and misplaced idioms is one of the most common reasons candidates lose points in Lexical Resource.</p><h3>1. Memorized Intro Templates</h3><p>Examiners are specifically trained to identify formulaic templates. When an essay begins with clichés like 'Since the dawn of time, human civilization has debated...', examiners immediately downgrade Task Response.</p><h3>2. Lack of a Clear Central Position</h3><p>Your thesis statement must remain consistent throughout the entirety of the response. Introduce your stance in the opening paragraph and sustain it through every body paragraph.</p><h3>3. Paragraph Overload</h3><p>Structure your essay around two deeply developed body paragraphs rather than four superficial points. Quality of elaboration always triumphs over quantity of arguments.</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1455390582262-044cdead277a?q=80&w=800&auto=format&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'seo_title' => '5 Fatal Flaws in IELTS Academic Writing Task 2',
                'seo_description' => 'Avoid these 5 common mistakes that cost candidates Band 8 in Academic Writing Task 2.',
            ]
        );
        $post1->tags()->sync([$tag1->id, $tag3->id]);

        $post2 = Blog::updateOrCreate(
            ['slug' => 'canada-express-entry-clb-9-strategy'],
            [
                'author_id' => $superAdmin->id,
                'category_id' => $bCatAbroad->id,
                'title' => 'Canada Express Entry 2026: Why CLB 9 is the Ultimate Score Gamechanger',
                'excerpt' => 'A breakdown of how achieving Band 8 in Listening and 7 in Writing, Reading, and Speaking unlocks up to 50 additional Comprehensive Ranking System points.',
                'content' => "<p>In today's highly competitive immigration landscape, language proficiency is the single most controllable variable in your Express Entry Comprehensive Ranking System (CRS) profile.</p><h3>The Magic CLB 9 Formula</h3><p>To reach Canadian Language Benchmark (CLB) level 9 in General Training IELTS, candidates must secure: <strong>Listening: 8.0, Reading: 7.0, Writing: 7.0, Speaking: 7.0</strong>.</p><p>Achieving this threshold unlocks skill transferability bonus points, often catapulting a profile by 50 to 100 points and securing an immediate Invitation to Apply (ITA).</p>",
                'featured_image' => 'https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?q=80&w=800&auto=format&fit=crop',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'seo_title' => 'How CLB 9 Unlocks Canada Express Entry Invitations',
                'seo_description' => 'Maximize your Canadian CRS score by targeting CLB 9 in IELTS General Training.',
            ]
        );
        $post2->tags()->sync([$tag4->id]);

        // 13. FAQs
        $faqs = [
            [
                'question' => 'What is the fundamental difference between Academic and General Training IELTS?',
                'answer' => 'The Academic test is designed for candidates seeking entry into undergraduate or postgraduate programs and professional bodies (medicine, nursing, engineering). The General Training test is geared toward immigration requirements (such as Canada Express Entry or Australian PR) and vocational work experience. While the Speaking and Listening modules are identical, the Reading and Writing Task 1 sections differ significantly.',
                'category' => 'general',
                'display_order' => 1,
                'status' => true,
            ],
            [
                'question' => 'How soon can I expect to achieve a Band 8.0 from an intermediate starting level?',
                'answer' => 'Typically, progressing one full band score requires approximately 80 to 100 hours of guided structured instruction and deliberate practice. In our 12-week Comprehensive Academic Program, students participate in 120 guided hours accompanied by 12 proctored mock examinations, which consistently delivers a 1.5 to 2.0 band improvement.',
                'category' => 'iets',
                'display_order' => 2,
                'status' => true,
            ],
            [
                'question' => 'How does the free appointment and evaluation process work?',
                'answer' => 'You can schedule a free 30-minute diagnostic session directly through our online calendar. You will complete a rapid 20-minute diagnostic assessment followed by an immediate 1-on-1 strategy briefing with one of our certified examiners, receiving a personalized study roadmap and target band breakdown.',
                'category' => 'appointments',
                'display_order' => 3,
                'status' => true,
            ],
            [
                'question' => 'Are your mock exams computer-delivered or paper-based?',
                'answer' => 'We offer full test environments for both! Our Smart Computer-Delivered Acoustic Lab features official test software, high-speed keyboards, and studio headsets. Alternatively, our Oxford Simulation Theatre conducts authentic paper-based mock tests every Saturday morning.',
                'category' => 'courses',
                'display_order' => 4,
                'status' => true,
            ],
            [
                'question' => 'Can I reschedule an appointment or batch timing after enrollment?',
                'answer' => 'Yes. We understand our candidates juggle university and demanding work commitments. Appointments can be rescheduled online with at least 24 hours notice, and course attendees may transfer between morning, evening, and weekend batches seamlessly.',
                'category' => 'admissions',
                'display_order' => 5,
                'status' => true,
            ],
            [
                'question' => 'How does your integrated AI Academic Assistant help me outside class hours?',
                'answer' => 'Our site features a custom Agentic AI Advisor connected directly to our academy knowledge base and booking engine. You can ask it about specific teacher qualifications, check available appointment slots in real-time, get guidance on course syllabi, and receive instant answers 24/7.',
                'category' => 'general',
                'display_order' => 6,
                'status' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(['question' => $faq['question']], $faq);
        }

        // 14. Appointment Settings
        AppointmentSetting::updateOrCreate(
            ['id' => 1],
            [
                'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'slot_duration_minutes' => 30,
                'break_start' => '13:00:00',
                'break_end' => '14:00:00',
                'max_per_slot' => 1,
            ]
        );

        // 15. System Settings
        $settings = [
            'site_name' => 'Anti Academy & IETS Institute',
            'site_tagline' => 'Premier Cambridge-Accredited IETS Coaching & Global Admissions',
            'contact_email' => 'admissions@antiacademy.edu',
            'contact_phone' => '+1 (555) 234-5678',
            'contact_whatsapp' => '+15552345678',
            'contact_address' => '450 Lexington Avenue, Suite 1800, New York, NY 10017',
            'business_hours' => 'Mon - Sat: 9:00 AM - 6:00 PM EST',
            'ai_enabled' => '1',
            'ai_bot_name' => 'Antigravity Academy AI',
            'ai_welcome_message' => 'Hello! Welcome to Anti Academy. I am your AI Academic Advisor. How can I assist your IETS preparation or appointment booking today?',
            'geo_schema_type' => 'EducationalOrganization',
            'geo_latitude' => '40.7516',
            'geo_longitude' => '-73.9755',
            'geo_area_served' => 'Worldwide, United States, Canada, United Kingdom, Australia',
            'meta_title' => 'Anti Academy — Cambridge Certified IETS & IELTS Preparation Institute',
            'meta_description' => 'Target Band 8.0+ with certified examiners, real-time proctored mock simulation labs, and personalized 1-on-1 language coaching. Book your free evaluation today.',
            'meta_keywords' => 'IELTS preparation, IETS academy, Cambridge certified examiners, Band 8 IELTS, Express Entry CLB 9, IELTS academic writing',
        ];

        foreach ($settings as $key => $val) {
            Setting::updateOrCreate(['key' => $key], ['value' => $val]);
        }
    }
}
