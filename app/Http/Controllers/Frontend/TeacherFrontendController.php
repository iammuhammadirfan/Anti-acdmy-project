<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SeoMeta;
use App\Models\Teacher;
use App\Services\GeoSchemaService;

class TeacherFrontendController extends Controller
{
    protected GeoSchemaService $geoService;

    public function __construct(GeoSchemaService $geoService)
    {
        $this->geoService = $geoService;
    }

    public function index()
    {
        $teachers = Teacher::active()->paginate(12);
        $seo = SeoMeta::getForPage('teachers');
        return view('frontend.teachers.index', compact('teachers', 'seo'));
    }

    public function show(string $slug)
    {
        $teacher = Teacher::where('slug', $slug)->active()->firstOrFail();
        $otherTeachers = Teacher::active()->where('id', '!=', $teacher->id)->limit(3)->get();
        $teacherSchema = $this->geoService->getTeacherSchema($teacher);
        return view('frontend.teachers.show', compact('teacher', 'otherTeachers', 'teacherSchema'));
    }
}
