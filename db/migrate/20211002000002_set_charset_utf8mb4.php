<?php
class SetCharsetUtf8mb4 extends Rails\ActiveRecord\Migration\Base
{
    public function up()
    {
        $this->execute("ALTER TABLE `advertisements`        CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `artists`               CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `artists_urls`          CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `bans`                  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `batch_uploads`         CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `comments`              CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `dmails`                CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `favorites`             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `flagged_post_details`  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `forum_posts`           CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `histories`             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `history_changes`       CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `inline_images`         CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `inlines`               CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `ip_bans`               CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `job_tasks`             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `note_versions`         CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `notes`                 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `pools`                 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `pools_posts`           CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `post_tag_histories`    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `post_votes`            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `posts`                 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `posts_tags`            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `schema_migrations`     CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `table_data`            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `tag_aliases`           CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `tag_implications`      CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `tag_subscriptions`     CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `tags`                  CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `user_blacklisted_tags` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `user_logs`             CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `user_records`          CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `users`                 CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `wiki_page_versions`    CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $this->execute("ALTER TABLE `wiki_pages`            CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
}
