<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $table) => $table->boolean('is_admin')->default(false)->index());
        foreach (['pages', 'posts', 'testimonials', 'timeline_events', 'statistics', 'media'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->string('title', 180);
                $table->string('slug', 190)->unique();
                $table->string('status', 20)->default('draft')->index();
                $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamp('published_at')->nullable()->index();
                $table->text('source')->nullable();
                $table->timestamps();
                if ($name !== 'media') {
                    $table->longText('body')->nullable();
                }
                if (in_array($name, ['pages', 'timeline_events'])) {
                    $table->boolean('is_provisional')->default(true);
                    $table->unsignedInteger('sort_order')->default(0);
                }
                if ($name === 'posts') {
                    $table->string('category', 80)->default('Noticias')->index();
                    $table->text('excerpt')->nullable();
                }
                if ($name === 'testimonials') {
                    $table->string('public_name', 100);
                    $table->date('testimony_date')->nullable();
                }
                if ($name === 'timeline_events') {
                    $table->unsignedSmallInteger('year')->index();
                    $table->text('authorization_reference')->nullable();
                }
                if ($name === 'statistics') {
                    $table->decimal('value', 14, 3);
                    $table->string('unit', 40);
                    $table->unsignedSmallInteger('year')->index();
                    $table->string('geography', 100)->index();
                    $table->string('indicator', 150);
                    $table->text('source_url');
                    $table->string('source_locator', 180)->nullable();
                    $table->timestamp('verified_at')->nullable();
                }
                if ($name === 'media') {
                    $table->boolean('restricted_to_testimonial')->default(false)->index();
                    $table->string('kind', 20);
                    $table->string('path')->nullable();
                    $table->string('mime_type', 80)->nullable();
                    $table->unsignedBigInteger('size')->nullable();
                    $table->string('alt_text', 250);
                    $table->text('external_url')->nullable();
                    $table->text('authorization_reference')->nullable();
                    $table->timestamp('authorization_expires_at')->nullable();
                }
            });
        }
        foreach (['posts', 'testimonials', 'timeline_events'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->foreignId('media_id')->nullable()->constrained('media')->nullOnDelete());
        }
        Schema::create('consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testimonial_id')->unique()->constrained()->cascadeOnDelete();
            $table->longText('details');
            $table->boolean('allow_text')->default(false);
            $table->boolean('allow_image')->default(false);
            $table->boolean('guardian_verified')->default(false);
            $table->boolean('involves_minor')->default(false);
            $table->date('granted_at');
            $table->date('expires_at')->nullable();
            $table->timestamp('withdrawn_at')->nullable();
            $table->text('withdrawal_reason')->nullable();
            $table->string('evidence_path')->nullable();
            $table->foreignId('reviewer_id')->constrained('users')->restrictOnDelete();
            $table->dateTime('reviewed_at');
            $table->timestamps();
        });
        foreach (['participation_requests', 'contact_messages'] as $name) {
            Schema::create($name, function (Blueprint $table) use ($name) {
                $table->id();
                $table->uuid('reference')->unique();
                $table->text('name');
                $table->text('email');
                $table->text('phone')->nullable();
                $table->longText('message');
                $table->string('kind', 40)->index();
                $table->string('status', 20)->default('pending')->index();
                $table->dateTime('consented_at');
                $table->string('privacy_version', 30);
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->text('internal_notes')->nullable();
                $table->timestamps();
                if ($name === 'participation_requests') {
                    $table->text('availability')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('participation_requests');
        Schema::dropIfExists('consents');
        foreach (['posts', 'testimonials', 'timeline_events'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropConstrainedForeignId('media_id'));
        }
        foreach (['media', 'statistics', 'timeline_events', 'testimonials', 'posts', 'pages'] as $name) {
            Schema::dropIfExists($name);
        }
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn('is_admin'));
    }
};
