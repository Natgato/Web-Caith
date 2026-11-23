<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('community_privacy_at')->nullable();
            $table->string('community_privacy_version', 30)->nullable();
        });

        Schema::create('site_layouts', function (Blueprint $table) {
            $table->id();
            $table->string('area', 24)->unique();
            $table->json('draft')->nullable();
            $table->json('published')->nullable();
            $table->timestamps();
        });

        Schema::create('site_layout_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_layout_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('snapshot');
            $table->timestamps();
        });

        Schema::create('opinions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 190)->unique();
            $table->string('title', 180);
            $table->string('public_name', 100);
            $table->longText('body')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->text('source')->nullable();
            $table->foreignId('contributor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->text('review_feedback')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->foreignId('contributor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('image_requested_at')->nullable();
            $table->timestamp('contributor_withdrawn_at')->nullable();
            $table->text('review_feedback')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contributor_id');
            $table->dropColumn(['submitted_at', 'image_requested_at', 'contributor_withdrawn_at', 'review_feedback']);
        });

        Schema::dropIfExists('opinions');
        Schema::dropIfExists('site_layout_revisions');
        Schema::dropIfExists('site_layouts');
        Schema::table('users', fn (Blueprint $table) => $table->dropColumn(['community_privacy_at', 'community_privacy_version']));
    }
};
