<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'subject',
        'body',
        'description',
    ];

    /**
     * Render the template by replacing variables with provided data
     */
    public function render(array $data): array
    {
        $subject = $this->subject;
        $body = $this->body;

        foreach ($data as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $subject = str_replace($placeholder, (string) $value, $subject);
            $body = str_replace($placeholder, (string) $value, $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    /**
     * Fetch a template by slug or return a default fallback
     */
    public static function getTemplate(string $slug): self
    {
        $tpl = static::where('slug', $slug)->first();

        if ($tpl) {
            return $tpl;
        }

        $defaults = static::getDefaults();
        $defaultData = $defaults[$slug] ?? [
            'slug' => $slug,
            'name' => ucwords(str_replace('_', ' ', $slug)),
            'subject' => 'Notification from ' . config('app.name', 'Apex Academy'),
            'body' => '<p>Hello {{student_name}},</p><p>Your booking details: {{registration_number}} on {{date}} at {{time}}.</p>',
            'description' => 'Notification email',
        ];

        return static::create($defaultData);
    }

    /**
     * Pre-defined default templates with proper variable placeholders
     */
    public static function getDefaults(): array
    {
        return [
            'counseling_confirmation' => [
                'slug' => 'counseling_confirmation',
                'name' => 'Campus Counseling Confirmation',
                'subject' => 'Campus Counseling Appointment Confirmation',
                'description' => 'Dispatched automatically when a student reserves a campus counseling appointment.',
                'body' => <<<HTML
<p>Dear <strong>{{student_name}}</strong>,</p>

<p>Your campus counseling appointment has been successfully booked.</p>

<div style="background: #f8fafc; border-left: 4px solid #2563eb; padding: 15px; margin: 20px 0; border-radius: 6px;">
    <p style="margin: 4px 0;"><strong>Appointment Type:</strong> Campus Counseling</p>
    <p style="margin: 4px 0;"><strong>Purpose:</strong> {{purpose}}</p>
    <p style="margin: 4px 0;"><strong>Date:</strong> {{date}}</p>
    <p style="margin: 4px 0;"><strong>Time:</strong> {{time}}</p>
    <p style="margin: 4px 0;"><strong>Registration Number:</strong> <span style="font-family: monospace; font-weight: bold; color: #2563eb;">{{registration_number}}</span></p>
</div>

<p>Please arrive on time at the campus admissions desk.</p>

<p>Thank you,<br>
<strong>{{academy_name}}</strong></p>
HTML
            ],

            'iets_confirmation' => [
                'slug' => 'iets_confirmation',
                'name' => 'IETS Test Confirmation',
                'subject' => 'IETS Test Appointment Confirmation',
                'description' => 'Dispatched automatically when a student registers for an IETS Test slot.',
                'body' => <<<HTML
<p>Dear <strong>{{student_name}}</strong>,</p>

<p>Your IETS test has been successfully scheduled.</p>

<div style="background: #f0fdf4; border-left: 4px solid #16a34a; padding: 15px; margin: 20px 0; border-radius: 6px;">
    <p style="margin: 4px 0;"><strong>Test Type:</strong> {{test_type}}</p>
    <p style="margin: 4px 0;"><strong>Date:</strong> {{date}}</p>
    <p style="margin: 4px 0;"><strong>Time:</strong> {{time}}</p>
    <p style="margin: 4px 0;"><strong>Enrollment / Registration Number:</strong> <span style="font-family: monospace; font-weight: bold; color: #15803d; font-size: 16px;">{{registration_number}}</span></p>
</div>

<p>Please arrive at the academy on the scheduled date and time for your test.</p>
<p><strong>Important:</strong> Please keep your registration number and original CNIC / Passport with you upon entry.</p>

<p>Thank you,<br>
<strong>{{academy_name}}</strong></p>
HTML
            ],

            'cancellation' => [
                'slug' => 'cancellation',
                'name' => 'Booking Cancellation Notice',
                'subject' => 'Appointment / Test Cancellation Notice',
                'description' => 'Sent when an administrator cancels a booking, releasing the seat.',
                'body' => <<<HTML
<p>Dear <strong>{{student_name}}</strong>,</p>

<p>This is to inform you that your booking has been cancelled.</p>

<div style="background: #fef2f2; border-left: 4px solid #dc2626; padding: 15px; margin: 20px 0; border-radius: 6px;">
    <p style="margin: 4px 0;"><strong>Registration Number:</strong> <span style="font-family: monospace; font-weight: bold; color: #b91c1c;">{{registration_number}}</span></p>
    <p style="margin: 4px 0;"><strong>Appointment / Test:</strong> {{test_type}}</p>
    <p style="margin: 4px 0;"><strong>Original Date & Time:</strong> {{date}} at {{time}}</p>
    <p style="margin: 4px 0;"><strong>Status:</strong> Cancelled</p>
</div>

<p>If you believe this cancellation was made in error or wish to reschedule, please visit our booking portal or contact the academy administration desk.</p>

<p>Sincerely,<br>
<strong>{{academy_name}}</strong></p>
HTML
            ],

            'rescheduled' => [
                'slug' => 'rescheduled',
                'name' => 'Appointment Rescheduled Notice',
                'subject' => 'Your Appointment / Test Has Been Rescheduled',
                'description' => 'Sent when an administrator updates the scheduled slot for a student booking.',
                'body' => <<<HTML
<p>Dear <strong>{{student_name}}</strong>,</p>

<p>Your scheduled session with <strong>{{academy_name}}</strong> has been updated to a new time slot.</p>

<div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 15px; margin: 20px 0; border-radius: 6px;">
    <p style="margin: 4px 0;"><strong>Registration Number:</strong> <span style="font-family: monospace; font-weight: bold; color: #1d4ed8;">{{registration_number}}</span></p>
    <p style="margin: 4px 0;"><strong>Session:</strong> {{test_type}}</p>
    <p style="margin: 4px 0;"><strong>New Scheduled Date:</strong> {{date}}</p>
    <p style="margin: 4px 0;"><strong>New Scheduled Time:</strong> {{time}}</p>
</div>

<p>Please arrive at the academy 15 minutes before your new scheduled slot.</p>

<p>Best regards,<br>
<strong>{{academy_name}}</strong></p>
HTML
            ],

            'reminder' => [
                'slug' => 'reminder',
                'name' => 'Upcoming Session Reminder (24h)',
                'subject' => 'Reminder: Your Scheduled Session at Apex Academy Tomorrow',
                'description' => 'Automated reminder dispatched 24 hours prior to the scheduled test/counseling slot.',
                'body' => <<<HTML
<p>Dear <strong>{{student_name}}</strong>,</p>

<p>This is a friendly reminder that your upcoming appointment/test at <strong>{{academy_name}}</strong> is scheduled for tomorrow.</p>

<div style="background: #f8fafc; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; border-radius: 6px;">
    <p style="margin: 4px 0;"><strong>Registration Number:</strong> <span style="font-family: monospace; font-weight: bold; color: #b45309;">{{registration_number}}</span></p>
    <p style="margin: 4px 0;"><strong>Date:</strong> {{date}}</p>
    <p style="margin: 4px 0;"><strong>Time Slot:</strong> {{time}}</p>
    <p style="margin: 4px 0;"><strong>Details:</strong> {{test_type}}</p>
</div>

<p>Please remember to bring your registration number and valid photo identification.</p>

<p>See you tomorrow,<br>
<strong>{{academy_name}}</strong></p>
HTML
            ],
        ];
    }
}
