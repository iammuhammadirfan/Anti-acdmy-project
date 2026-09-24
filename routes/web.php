<?php

use Illuminate\Support\Facades\Route;

// Frontend Controllers
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\TeacherFrontendController;
use App\Http\Controllers\Frontend\AppointmentBookingController;
use App\Http\Controllers\Frontend\ContactFrontendController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Api\AiChatbotController;

// Admin Controllers
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PageSectionController;
use App\Http\Controllers\Admin\HistoryTimelineController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\CampusGalleryController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\IetsProgramController;
use App\Http\Controllers\Admin\IetsResultController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\SchedulingController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\AiController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ActivityLogController;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/history', [PageController::class, 'history'])->name('history');

Route::get('/teachers', [TeacherFrontendController::class, 'index'])->name('teachers');
Route::get('/teachers/{slug}', [TeacherFrontendController::class, 'show'])->name('teachers.show');

Route::get('/classrooms', [PageController::class, 'classrooms'])->name('classrooms');
Route::get('/gallery', [PageController::class, 'gallery'])->name('gallery');

Route::get('/iets', [PageController::class, 'iets'])->name('iets');
Route::get('/iets/results', [PageController::class, 'ietsResults'])->name('iets.results');

Route::get('/videos', [PageController::class, 'videos'])->name('videos');
Route::get('/videos/{slug}', [PageController::class, 'videoDetail'])->name('videos.show');

Route::get('/blog', [PageController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PageController::class, 'blogDetail'])->name('blog.show');

Route::get('/faq', [PageController::class, 'faq'])->name('faq');

Route::get('/appointments', [AppointmentBookingController::class, 'index'])->name('appointments');
Route::get('/appointments/slots', [AppointmentBookingController::class, 'getSlots'])->name('appointments.slots');
Route::post('/appointments/book', [AppointmentBookingController::class, 'book'])->name('appointments.book');
Route::get('/appointments/success', [AppointmentBookingController::class, 'success'])->name('appointments.success');

Route::get('/contact', [ContactFrontendController::class, 'index'])->name('contact');
Route::post('/contact/submit', [ContactFrontendController::class, 'submit'])->name('contact.submit');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms-and-conditions', [PageController::class, 'terms'])->name('terms');

Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// AI Chatbot Public Endpoint
Route::post('/api/ai/chat', [AiChatbotController::class, 'chat'])->name('api.ai.chat');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (with Dynamic Section RBAC)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/profile', [AuthController::class, 'profile'])->name('admin.profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('admin.profile.update');

    // User Management (Super Admin & Users module)
    Route::middleware(['module.permission:users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Roles & Permissions (Super Admin only)
    Route::middleware(['super.admin'])->group(function () {
        Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });

    // Sliders
    Route::middleware(['module.permission:sliders'])->group(function () {
        Route::get('/sliders', [SliderController::class, 'index'])->name('admin.sliders.index');
        Route::get('/sliders/create', [SliderController::class, 'create'])->name('admin.sliders.create');
        Route::post('/sliders', [SliderController::class, 'store'])->name('admin.sliders.store');
        Route::get('/sliders/{slider}/edit', [SliderController::class, 'edit'])->name('admin.sliders.edit');
        Route::put('/sliders/{slider}', [SliderController::class, 'update'])->name('admin.sliders.update');
        Route::delete('/sliders/{slider}', [SliderController::class, 'destroy'])->name('admin.sliders.destroy');
    });

    // Page Sections (Homepage Builder)
    Route::middleware(['module.permission:homepage'])->group(function () {
        Route::get('/sections', [PageSectionController::class, 'index'])->name('admin.sections.index');
        Route::get('/sections/{section}/edit', [PageSectionController::class, 'edit'])->name('admin.sections.edit');
        Route::put('/sections/{section}', [PageSectionController::class, 'update'])->name('admin.sections.update');
        Route::post('/sections/{section}/toggle', [PageSectionController::class, 'toggle'])->name('admin.sections.toggle');
    });

    // History Timeline
    Route::middleware(['module.permission:about'])->group(function () {
        Route::get('/timelines', [HistoryTimelineController::class, 'index'])->name('admin.timelines.index');
        Route::get('/timelines/create', [HistoryTimelineController::class, 'create'])->name('admin.timelines.create');
        Route::post('/timelines', [HistoryTimelineController::class, 'store'])->name('admin.timelines.store');
        Route::get('/timelines/{timeline}/edit', [HistoryTimelineController::class, 'edit'])->name('admin.timelines.edit');
        Route::put('/timelines/{timeline}', [HistoryTimelineController::class, 'update'])->name('admin.timelines.update');
        Route::post('/timelines/{timeline}/toggle', [HistoryTimelineController::class, 'toggle'])->name('admin.timelines.toggle');
        Route::delete('/timelines/{timeline}', [HistoryTimelineController::class, 'destroy'])->name('admin.timelines.destroy');
    });

    // Statistics & Students
    Route::middleware(['module.permission:students'])->group(function () {
        Route::get('/statistics', [StatisticController::class, 'index'])->name('admin.statistics.index');
        Route::get('/statistics/create', [StatisticController::class, 'create'])->name('admin.statistics.create');
        Route::post('/statistics', [StatisticController::class, 'store'])->name('admin.statistics.store');
        Route::get('/statistics/{statistic}/edit', [StatisticController::class, 'edit'])->name('admin.statistics.edit');
        Route::put('/statistics/{statistic}', [StatisticController::class, 'update'])->name('admin.statistics.update');
        Route::post('/statistics/{statistic}/toggle', [StatisticController::class, 'toggle'])->name('admin.statistics.toggle');
        Route::delete('/statistics/{statistic}', [StatisticController::class, 'destroy'])->name('admin.statistics.destroy');
    });

    // Teachers
    Route::middleware(['module.permission:teachers'])->group(function () {
        Route::get('/teachers', [TeacherController::class, 'index'])->name('admin.teachers.index');
        Route::get('/teachers/create', [TeacherController::class, 'create'])->name('admin.teachers.create');
        Route::post('/teachers', [TeacherController::class, 'store'])->name('admin.teachers.store');
        Route::get('/teachers/{teacher}/edit', [TeacherController::class, 'edit'])->name('admin.teachers.edit');
        Route::put('/teachers/{teacher}', [TeacherController::class, 'update'])->name('admin.teachers.update');
        Route::delete('/teachers/{teacher}', [TeacherController::class, 'destroy'])->name('admin.teachers.destroy');
    });

    // Classrooms
    Route::middleware(['module.permission:classrooms'])->group(function () {
        Route::get('/classrooms', [ClassroomController::class, 'index'])->name('admin.classrooms.index');
        Route::get('/classrooms/create', [ClassroomController::class, 'create'])->name('admin.classrooms.create');
        Route::post('/classrooms', [ClassroomController::class, 'store'])->name('admin.classrooms.store');
        Route::get('/classrooms/{classroom}/edit', [ClassroomController::class, 'edit'])->name('admin.classrooms.edit');
        Route::put('/classrooms/{classroom}', [ClassroomController::class, 'update'])->name('admin.classrooms.update');
        Route::post('/classrooms/{classroom}/toggle', [ClassroomController::class, 'toggle'])->name('admin.classrooms.toggle');
        Route::delete('/classrooms/{classroom}', [ClassroomController::class, 'destroy'])->name('admin.classrooms.destroy');
    });

    // Campus Gallery
    Route::middleware(['module.permission:gallery'])->group(function () {
        Route::get('/gallery', [CampusGalleryController::class, 'index'])->name('admin.gallery.index');
        Route::get('/gallery/create', [CampusGalleryController::class, 'create'])->name('admin.gallery.create');
        Route::post('/gallery', [CampusGalleryController::class, 'store'])->name('admin.gallery.store');
        Route::get('/gallery/{gallery}/edit', [CampusGalleryController::class, 'edit'])->name('admin.gallery.edit');
        Route::put('/gallery/{gallery}', [CampusGalleryController::class, 'update'])->name('admin.gallery.update');
        Route::post('/gallery/{gallery}/toggle', [CampusGalleryController::class, 'toggle'])->name('admin.gallery.toggle');
        Route::delete('/gallery/{gallery}', [CampusGalleryController::class, 'destroy'])->name('admin.gallery.destroy');
    });

    // IETS Programs
    Route::middleware(['module.permission:iets'])->group(function () {
        Route::get('/iets/programs', [IetsProgramController::class, 'index'])->name('admin.iets.programs.index');
        Route::get('/iets/programs/create', [IetsProgramController::class, 'create'])->name('admin.iets.programs.create');
        Route::post('/iets/programs', [IetsProgramController::class, 'store'])->name('admin.iets.programs.store');
        Route::get('/iets/programs/{program}/edit', [IetsProgramController::class, 'edit'])->name('admin.iets.programs.edit');
        Route::put('/iets/programs/{program}', [IetsProgramController::class, 'update'])->name('admin.iets.programs.update');
        Route::post('/iets/programs/{program}/toggle', [IetsProgramController::class, 'toggle'])->name('admin.iets.programs.toggle');
        Route::delete('/iets/programs/{program}', [IetsProgramController::class, 'destroy'])->name('admin.iets.programs.destroy');
    });

    // IETS Results
    Route::middleware(['module.permission:iets_results'])->group(function () {
        Route::get('/iets/results', [IetsResultController::class, 'index'])->name('admin.iets.results.index');
        Route::get('/iets/results/create', [IetsResultController::class, 'create'])->name('admin.iets.results.create');
        Route::post('/iets/results', [IetsResultController::class, 'store'])->name('admin.iets.results.store');
        Route::get('/iets/results/{result}/edit', [IetsResultController::class, 'edit'])->name('admin.iets.results.edit');
        Route::put('/iets/results/{result}', [IetsResultController::class, 'update'])->name('admin.iets.results.update');
        Route::post('/iets/results/{result}/toggle', [IetsResultController::class, 'toggle'])->name('admin.iets.results.toggle');
        Route::delete('/iets/results/{result}', [IetsResultController::class, 'destroy'])->name('admin.iets.results.destroy');
    });

    // Videos & Vlogs
    Route::middleware(['module.permission:videos'])->group(function () {
        Route::get('/videos', [VideoController::class, 'index'])->name('admin.videos.index');
        Route::get('/videos/create', [VideoController::class, 'create'])->name('admin.videos.create');
        Route::post('/videos', [VideoController::class, 'store'])->name('admin.videos.store');
        Route::get('/videos/{video}/edit', [VideoController::class, 'edit'])->name('admin.videos.edit');
        Route::put('/videos/{video}', [VideoController::class, 'update'])->name('admin.videos.update');
        Route::post('/videos/{video}/toggle', [VideoController::class, 'toggle'])->name('admin.videos.toggle');
        Route::delete('/videos/{video}', [VideoController::class, 'destroy'])->name('admin.videos.destroy');
    });

    // Blog & News
    Route::middleware(['module.permission:blog'])->group(function () {
        Route::get('/blogs', [BlogController::class, 'index'])->name('admin.blog.index');
        Route::get('/blogs/create', [BlogController::class, 'create'])->name('admin.blog.create');
        Route::post('/blogs', [BlogController::class, 'store'])->name('admin.blog.store');
        Route::get('/blogs/{blog}/edit', [BlogController::class, 'edit'])->name('admin.blog.edit');
        Route::put('/blogs/{blog}', [BlogController::class, 'update'])->name('admin.blog.update');
        Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])->name('admin.blog.destroy');
    });

    // FAQs
    Route::middleware(['module.permission:faq'])->group(function () {
        Route::get('/faqs', [FaqController::class, 'index'])->name('admin.faqs.index');
        Route::get('/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');
        Route::post('/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
        Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit'])->name('admin.faqs.edit');
        Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
        Route::post('/faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('admin.faqs.toggle');
        Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');
    });

    // Scheduling & Appointment Management (Dynamic Section RBAC)
    Route::middleware(['module.permission:appointments'])->group(function () {
        // Dedicated Scheduling Section
        Route::get('/scheduling', [SchedulingController::class, 'dashboard'])->name('admin.scheduling.dashboard');
        Route::get('/scheduling/calendar', [SchedulingController::class, 'calendar'])->name('admin.scheduling.calendar');
        Route::get('/scheduling/slots', [SchedulingController::class, 'slots'])->name('admin.scheduling.slots');
        Route::post('/scheduling/slots', [SchedulingController::class, 'storeSlot'])->name('admin.scheduling.slot.store');
        Route::post('/scheduling/slots/batch', [SchedulingController::class, 'batchGenerateSlots'])->name('admin.scheduling.slots.batch');
        Route::put('/scheduling/slots/{slot}', [SchedulingController::class, 'updateSlot'])->name('admin.scheduling.slot.update');
        Route::post('/scheduling/slots/{slot}/toggle', [SchedulingController::class, 'toggleSlot'])->name('admin.scheduling.slot.toggle');
        Route::delete('/scheduling/slots/{slot}', [SchedulingController::class, 'destroySlot'])->name('admin.scheduling.slot.destroy');

        Route::get('/scheduling/bookings', [SchedulingController::class, 'bookings'])->name('admin.scheduling.bookings');
        Route::get('/scheduling/counseling', [SchedulingController::class, 'counselingSchedule'])->name('admin.scheduling.counseling');
        Route::get('/scheduling/iets', [SchedulingController::class, 'ietsSchedule'])->name('admin.scheduling.iets');
        Route::get('/scheduling/bookings/{booking}', [SchedulingController::class, 'showBooking'])->name('admin.scheduling.booking.show');
        Route::post('/scheduling/bookings/{booking}/status', [SchedulingController::class, 'updateBookingStatus'])->name('admin.scheduling.booking.status');
        Route::post('/scheduling/bookings/{booking}/reschedule', [SchedulingController::class, 'rescheduleBooking'])->name('admin.scheduling.booking.reschedule');
        Route::get('/scheduling/export', [SchedulingController::class, 'exportBookings'])->name('admin.scheduling.export');

        Route::get('/scheduling/students', [SchedulingController::class, 'students'])->name('admin.scheduling.students');

        Route::get('/scheduling/emails', [SchedulingController::class, 'emails'])->name('admin.scheduling.emails');
        Route::get('/scheduling/emails/{template}/edit', [SchedulingController::class, 'editEmail'])->name('admin.scheduling.email.edit');
        Route::put('/scheduling/emails/{template}', [SchedulingController::class, 'updateEmail'])->name('admin.scheduling.email.update');
        Route::post('/scheduling/emails/{template}/reset', [SchedulingController::class, 'resetEmail'])->name('admin.scheduling.email.reset');

        Route::get('/scheduling/settings', [SchedulingController::class, 'settings'])->name('admin.scheduling.settings');
        Route::post('/scheduling/settings', [SchedulingController::class, 'updateSettings'])->name('admin.scheduling.settings.update');

        // Backward compatibility for legacy appointment endpoints
        Route::get('/appointments', [SchedulingController::class, 'bookings'])->name('admin.appointments.index');
        Route::get('/appointments/{appointment}', [SchedulingController::class, 'showBooking'])->name('admin.appointments.show');
        Route::post('/appointments/{appointment}/status', [SchedulingController::class, 'updateBookingStatus'])->name('admin.appointments.status');
        Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('admin.appointments.destroy');
    });

    // Calendar & Blocking
    Route::middleware(['module.permission:calendar'])->group(function () {
        Route::get('/calendar', [SchedulingController::class, 'calendar'])->name('admin.calendar.index');
        Route::post('/calendar/settings', [SchedulingController::class, 'updateSettings'])->name('admin.calendar.settings');
        Route::post('/calendar/block-date', [CalendarController::class, 'blockDate'])->name('admin.calendar.block');
        Route::delete('/calendar/unblock/{blockedDate}', [CalendarController::class, 'unblockDate'])->name('admin.calendar.unblock');
    });

    // Contact Messages
    Route::middleware(['module.permission:contact'])->group(function () {
        Route::get('/messages', [ContactMessageController::class, 'index'])->name('admin.messages.index');
        Route::get('/messages/{message}', [ContactMessageController::class, 'show'])->name('admin.messages.show');
        Route::post('/messages/{message}/reply', [ContactMessageController::class, 'reply'])->name('admin.messages.reply');
        Route::delete('/messages/{message}', [ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');
    });

    // Media Library
    Route::middleware(['module.permission:media'])->group(function () {
        Route::get('/media', [MediaController::class, 'index'])->name('admin.media.index');
        Route::post('/media/upload', [MediaController::class, 'upload'])->name('admin.media.upload');
        Route::put('/media/{media}', [MediaController::class, 'update'])->name('admin.media.update');
        Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('admin.media.destroy');
    });

    // SEO & GEO
    Route::middleware(['module.permission:seo'])->group(function () {
        Route::get('/seo', [SeoController::class, 'index'])->name('admin.seo.index');
        Route::get('/seo/{pageKey}/edit', [SeoController::class, 'edit'])->name('admin.seo.edit');
        Route::put('/seo/{pageKey}', [SeoController::class, 'update'])->name('admin.seo.update');
        Route::post('/seo/redirects', [SeoController::class, 'storeRedirect'])->name('admin.seo.redirects.store');
        Route::delete('/seo/redirects/{redirect}', [SeoController::class, 'destroyRedirect'])->name('admin.seo.redirects.destroy');
    });

    // Agentic AI Chatbot
    Route::middleware(['module.permission:ai'])->group(function () {
        Route::get('/ai', [AiController::class, 'index'])->name('admin.ai.index');
        Route::post('/ai/settings', [AiController::class, 'updateSettings'])->name('admin.ai.settings');
        Route::get('/ai/conversations/{conversation}', [AiController::class, 'showConversation'])->name('admin.ai.conversation');
        Route::delete('/ai/conversations/{conversation}', [AiController::class, 'destroyConversation'])->name('admin.ai.conversation.destroy');
    });

    // System Settings
    Route::middleware(['module.permission:settings'])->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/settings', [SettingController::class, 'update'])->name('admin.settings.update');
        Route::post('/settings/test-smtp', [SettingController::class, 'testSmtp'])->name('admin.settings.test_smtp');
    });

    // Audit Activity Logs
    Route::middleware(['super.admin'])->group(function () {
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('admin.activity.index');
    });
});
