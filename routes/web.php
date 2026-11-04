<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ConsentController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PublicSiteController;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
$pages = [
    'nosotros' => 'nosotros', 'nosotros/mision-vision' => 'mision-vision', 'nosotros/historia' => 'historia',
    'nuestra-labor' => 'nuestra-labor', 'nuestra-labor/programas' => 'programas', 'nuestra-labor/actividades' => 'actividades', 'nuestra-labor/impacto' => 'impacto',
    'realidad-social' => 'realidad-social', 'realidad-social/trabajo-del-hogar' => 'trabajo-hogar', 'realidad-social/derechos-laborales' => 'derechos',
    'realidad-social/trata-y-explotacion' => 'trata-explotacion', 'realidad-social/discriminacion' => 'discriminacion', 'como-ayudar' => 'como-ayudar',
];
foreach ($pages as $path => $slug) {
    Route::get($path, [PublicSiteController::class, 'page'])->defaults('slug', $slug)->name($slug);
}
Route::get('/paginas/{slug}', [PublicSiteController::class, 'page'])->name('page');
Route::get('/nosotros/linea-de-tiempo', [PublicSiteController::class, 'timeline'])->name('timeline');
Route::get('/explorar', [PublicSiteController::class, 'explore'])->name('explore');
Route::get('/noticias', [PublicSiteController::class, 'posts'])->name('news');
Route::get('/noticias/{slug}', [PublicSiteController::class, 'post'])->name('post');
Route::get('/historias', [PublicSiteController::class, 'testimonials'])->name('stories');
Route::get('/historias/{slug}', [PublicSiteController::class, 'testimonial'])->name('story');
Route::get('/historias/{slug}/imagen', [MediaController::class, 'testimonial'])->name('story.image');
Route::get('/galeria', [PublicSiteController::class, 'gallery'])->name('gallery');
Route::get('/estadisticas', [PublicSiteController::class, 'statistics'])->name('statistics');
Route::get('/participa/{kind}', [PublicSiteController::class, 'participation'])->whereIn('kind', ['voluntariado', 'actividades', 'patrocinio'])->name('participation.form');
Route::redirect('/voluntariado', 'participa/voluntariado')->name('voluntariado');
Route::redirect('/participacion', 'participa/actividades')->name('participacion');
Route::redirect('/patrocinio', 'participa/patrocinio')->name('patrocinio');
Route::post('/participa', [VisitorController::class, 'store'])->middleware('throttle:visitor-forms')->name('participation.store');
Route::get('/contacto', [PublicSiteController::class, 'contact'])->name('contact');
Route::post('/contacto', [VisitorController::class, 'store'])->middleware('throttle:visitor-forms')->name('contact.store');
Route::get('/privacidad', [PublicSiteController::class, 'privacy'])->name('privacy');
Route::get('/donaciones', [PublicSiteController::class, 'donations'])->name('donations');
Route::get('/media/{media}', [MediaController::class, 'show'])->name('media.show');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'create'])->name('login');
        Route::post('/login', [AuthController::class, 'store'])->middleware('throttle:admin-login')->name('login.store');
    });
    Route::middleware(['auth', 'can:manage-site'])->group(function () {
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
        Route::get('/contenidos/{module}', [ContentController::class, 'index'])->name('content.index');
        Route::get('/contenidos/{module}/nuevo', [ContentController::class, 'create'])->name('content.create');
        Route::post('/contenidos/{module}', [ContentController::class, 'store'])->name('content.store');
        Route::get('/contenidos/{module}/{id}/editar', [ContentController::class, 'edit'])->whereNumber('id')->name('content.edit');
        Route::put('/contenidos/{module}/{id}', [ContentController::class, 'update'])->whereNumber('id')->name('content.update');
        Route::delete('/contenidos/{module}/{id}', [ContentController::class, 'destroy'])->whereNumber('id')->name('content.destroy');
        Route::post('/testimonios/{testimonial}/consentimiento', [ConsentController::class, 'store'])->name('consent.store');
        Route::post('/testimonios/{testimonial}/retirar', [ConsentController::class, 'withdraw'])->name('consent.withdraw');
        Route::get('/testimonios/{testimonial}/evidencia', [ConsentController::class, 'evidence'])->name('consent.evidence');
        Route::get('/media/{media}', [MediaController::class, 'admin'])->name('media');
        Route::get('/bandeja/{box}', [InboxController::class, 'index'])->name('inbox.index');
        Route::get('/bandeja/{box}/{id}', [InboxController::class, 'show'])->whereNumber('id')->name('inbox.show');
        Route::put('/bandeja/{box}/{id}', [InboxController::class, 'update'])->whereNumber('id')->name('inbox.update');
        Route::delete('/bandeja/{box}/{id}', [InboxController::class, 'destroy'])->whereNumber('id')->name('inbox.destroy');
    });
});
