<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add indexes to posts table
        Schema::table('posts', function (Blueprint $table) {
            try { $table->index('user_id'); } catch (\Exception $e) {}
            try { $table->index('created_at'); } catch (\Exception $e) {}
            try { $table->index(['user_id', 'created_at']); } catch (\Exception $e) {}
        });

        // Add indexes to comments table
        Schema::table('comments', function (Blueprint $table) {
            try { $table->index('post_id'); } catch (\Exception $e) {}
            try { $table->index('user_id'); } catch (\Exception $e) {}
            try { $table->index(['post_id', 'created_at']); } catch (\Exception $e) {}
        });

        // Add indexes to likes table
        // ensure any pre-existing unique is removed (some earlier migrations may have created it)
        try {
            DB::statement('ALTER TABLE `likes` DROP INDEX `likes_post_id_user_id_unique`');
        } catch (\Exception $e) {}
        Schema::table('likes', function (Blueprint $table) {
            try { $table->index('post_id'); } catch (\Exception $e) {}
            try { $table->index('user_id'); } catch (\Exception $e) {}
            try { $table->unique(['post_id', 'user_id']); } catch (\Exception $e) {}
        });

        // Add indexes to follows table
        // ensure any pre-existing unique is removed (some earlier migrations may have created it)
        try {
            DB::statement('ALTER TABLE `follows` DROP INDEX `follows_follower_id_following_id_unique`');
        } catch (\Exception $e) {}
        Schema::table('follows', function (Blueprint $table) {
            try { $table->index('follower_id'); } catch (\Exception $e) {}
            try { $table->index('following_id'); } catch (\Exception $e) {}
            try { $table->unique(['follower_id', 'following_id']); } catch (\Exception $e) {}
        });

        // Add indexes to notifications table
        Schema::table('notifications', function (Blueprint $table) {
            try { $table->index('user_id'); } catch (\Exception $e) {}
            try { $table->index('is_read'); } catch (\Exception $e) {}
            try { $table->index('created_at'); } catch (\Exception $e) {}
        });

        // Add indexes to messages table
        Schema::table('messages', function (Blueprint $table) {
            try { $table->index('conversation_id'); } catch (\Exception $e) {}
            try { $table->index('sender_id'); } catch (\Exception $e) {}
            try { $table->index('is_read'); } catch (\Exception $e) {}
            try { $table->index(['conversation_id', 'created_at']); } catch (\Exception $e) {}
        });

        // Add indexes to conversations table
        Schema::table('conversations', function (Blueprint $table) {
            try { $table->index('user_one_id'); } catch (\Exception $e) {}
            try { $table->index('user_two_id'); } catch (\Exception $e) {}
            try { $table->index('updated_at'); } catch (\Exception $e) {}
        });
    }

    public function down(): void
    {
        // Drop indexes
        Schema::table('posts', function (Blueprint $table) {
            try { $table->dropIndex(['user_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['created_at']); } catch (\Exception $e) {}
            try { $table->dropIndex(['user_id', 'created_at']); } catch (\Exception $e) {}
        });

        Schema::table('comments', function (Blueprint $table) {
            try { $table->dropIndex(['post_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['user_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['post_id', 'created_at']); } catch (\Exception $e) {}
        });

        Schema::table('likes', function (Blueprint $table) {
            try { $table->dropIndex(['post_id', 'user_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['post_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['user_id']); } catch (\Exception $e) {}
        });

        Schema::table('follows', function (Blueprint $table) {
            try { $table->dropIndex(['follower_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['following_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['follower_id', 'following_id']); } catch (\Exception $e) {}
        });

        Schema::table('notifications', function (Blueprint $table) {
            try { $table->dropIndex(['user_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['is_read']); } catch (\Exception $e) {}
            try { $table->dropIndex(['created_at']); } catch (\Exception $e) {}
        });

        Schema::table('messages', function (Blueprint $table) {
            try { $table->dropIndex(['conversation_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['sender_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['is_read']); } catch (\Exception $e) {}
            try { $table->dropIndex(['conversation_id', 'created_at']); } catch (\Exception $e) {}
        });

        Schema::table('conversations', function (Blueprint $table) {
            try { $table->dropIndex(['user_one_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['user_two_id']); } catch (\Exception $e) {}
            try { $table->dropIndex(['updated_at']); } catch (\Exception $e) {}
        });
    }
};
