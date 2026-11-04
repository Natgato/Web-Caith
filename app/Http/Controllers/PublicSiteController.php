<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Statistic;
use App\Models\Testimonial;
use App\Models\TimelineEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicSiteController extends Controller
{
    public function home()
    {
        return view('Index', ['events' => TimelineEvent::institutionalPreview()->orderBy('year')->take(3)->get(),
            'posts' => Post::published()->latest('published_at')->take(3)->get(), 'statistics' => Statistic::published()->whereNotNull('verified_at')->latest('year')->take(2)->get(),
            'stories' => Testimonial::authorized()->with('consent')->latest('published_at')->take(3)->get()->filter(fn ($t) => Storage::disk('local')->exists($t->consent->evidence_path))]);
    }

    public function page(string $slug)
    {
        $page = Page::where('slug', $slug)->institutionalPreview()->first();
        $title = config('site.pages')[$slug]['title'] ?? $page?->title;
        abort_unless($title, 404);

        return view('site.page', ['page' => $page, 'title' => $title, 'links' => config('site.pages')[$slug]['links'] ?? []]);
    }

    public function timeline()
    {
        return view('site.timeline', ['events' => TimelineEvent::institutionalPreview()->with('media')->orderBy('year')->orderBy('sort_order')->get()]);
    }

    public function posts(Request $request)
    {
        $data = $request->validate(['categoria' => ['nullable', 'string', 'max:80']]);

        return view('site.posts', ['posts' => Post::published()->when($data['categoria'] ?? null, fn ($q, $c) => $q->where('category', $c))->latest('published_at')->paginate(9)->withQueryString(),
            'categories' => Post::published()->distinct()->orderBy('category')->pluck('category')]);
    }

    public function post(string $slug)
    {
        return view('site.post', ['post' => Post::published()->with('media')->where('slug', $slug)->firstOrFail()]);
    }

    public function testimonials()
    {
        $stories = Testimonial::authorized()->with('consent')->latest('published_at')->paginate(9);
        $stories->setCollection($stories->getCollection()->filter(fn ($t) => Storage::disk('local')->exists($t->consent->evidence_path)));

        return view('site.testimonials', compact('stories'));
    }

    public function testimonial(string $slug)
    {
        $story = Testimonial::authorized()->with(['consent', 'media'])->where('slug', $slug)->firstOrFail();
        abort_unless(Storage::disk('local')->exists($story->consent->evidence_path), 404);

        return view('site.testimonial', compact('story'));
    }

    public function gallery(Request $request)
    {
        $data = $request->validate(['tipo' => ['nullable', 'in:photo,video']]);
        $items = Media::published()->where('restricted_to_testimonial', false)
            ->whereNotNull('authorization_reference')->where(fn ($q) => $q->whereNull('authorization_expires_at')->orWhere('authorization_expires_at', '>', now()))
            ->when($data['tipo'] ?? null, fn ($q, $v) => $q->where('kind', $v))->latest('published_at')->paginate(9)->withQueryString();

        return view('site.gallery', compact('items'));
    }

    public function statistics(Request $request)
    {
        $data = $request->validate(['ambito' => ['nullable', 'string', 'max:100']]);
        $statistics = Statistic::published()->whereNotNull('verified_at')->when($data['ambito'] ?? null, fn ($q, $v) => $q->where('geography', $v))->orderBy('indicator')->orderBy('year')->get();
        $geographies = Statistic::published()->whereNotNull('verified_at')->distinct()->pluck('geography');

        return view('site.statistics', compact('statistics', 'geographies'));
    }

    public function participation(string $kind)
    {
        $labels = ['voluntariado' => 'Voluntariado', 'actividades' => 'Participación en actividades', 'patrocinio' => 'Patrocinio y apoyo institucional'];
        abort_unless(isset($labels[$kind]), 404);

        return view('site.participation', ['kind' => $kind, 'title' => $labels[$kind]]);
    }

    public function contact()
    {
        return view('site.contact');
    }

    public function privacy()
    {
        return view('site.privacy');
    }

    public function donations()
    {
        return view('site.donations');
    }

    public function explore()
    {
        return view('site.explore');
    }
}
