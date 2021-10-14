<?php
class SetCharsetUtf8mb4V2 extends Rails\ActiveRecord\Migration\Base
{
    public function up()
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();

        print("Dropping foreign keys...\n");
        $this->dropForeignKey('artists',                'fk_artists__updater_id'                );
        $this->dropForeignKey('artists',                'fk_artists__alias_id'                  );
        $this->dropForeignKey('artists',                'fk_artists__group_id'                  );
        $this->dropForeignKey('artists_urls',           'fk_artists_urls__artist_id'            );
        $this->dropForeignKey('bans',                   'fk_bans__user_id'                      );
        $this->dropForeignKey('bans',                   'fk_bans__banned_by'                    );
        $this->dropForeignKey('batch_uploads',          'fk_batch_uploads__user_id'             );
        $this->dropForeignKey('comments',               'fk_comments__post_id'                  );
        $this->dropForeignKey('comments',               'fk_comments__user_id'                  );
        $this->dropForeignKey('dmails',                 'fk_dmails__from_id'                    );
        $this->dropForeignKey('dmails',                 'fk_dmails__parent_id'                  );
        $this->dropForeignKey('dmails',                 'fk_dmails__to_id'                      );
        $this->dropForeignKey('favorites',              'fk_favorites__post_id'                 );
        $this->dropForeignKey('favorites',              'fk_favorites__user_id'                 );
        $this->dropForeignKey('flagged_post_details',   'fk_flag_post_det__post_id'             );
        $this->dropForeignKey('flagged_post_details',   'fk_flag_post_details__post_id'         );
        $this->dropForeignKey('flagged_post_details',   'fk_flag_post_details__user_id'         );
        $this->dropForeignKey('flagged_post_details',   'fk_flagged_post_details__post_id'      );
        $this->dropForeignKey('flagged_post_details',   'fk_flagged_post_details__user_id'      );
        $this->dropForeignKey('forum_posts',            'fk_forum_posts__creator_id'            );
        $this->dropForeignKey('forum_posts',            'fk_forum_posts__last_updated_by'       );
        $this->dropForeignKey('forum_posts',            'fk_forum_posts__parent_id'             );
        $this->dropForeignKey('histories',              'fk_histories__user_id'                 );
        $this->dropForeignKey('history_changes',        'fk_history_changes__history_id'        );
        $this->dropForeignKey('history_changes',        'fk_history_changes__previous_id'       );
        $this->dropForeignKey('inline_images',          'fk_inline_images__inline_id'           );
        $this->dropForeignKey('inlines',                'fk_inlines__user_id'                   );
        $this->dropForeignKey('ip_bans',                'fk_ip_bans__banned_by'                 );
        $this->dropForeignKey('note_versions',          'fk_note_versions__note_id'             );
        $this->dropForeignKey('note_versions',          'fk_note_versions__post_id'             );
        $this->dropForeignKey('note_versions',          'fk_note_versions__user_id'             );
        $this->dropForeignKey('note_versions',          'user_id'                               );
        $this->dropForeignKey('notes',                  'fk_notes__post_id'                     );
        $this->dropForeignKey('notes',                  'fk_notes__user_id'                     );
        $this->dropForeignKey('pools',                  'fk_pools__user_id'                     );
        $this->dropForeignKey('pools_posts',            'fk_pools_posts__pool_id'               );
        $this->dropForeignKey('pools_posts',            'fk_pools_posts__post_id'               );
        $this->dropForeignKey('pools_posts',            'fk_pools_posts__next_post_id'          );
        $this->dropForeignKey('pools_posts',            'fk_pools_posts__prev_post_id'          );
        $this->dropForeignKey('post_tag_histories',     'fk_post_tag_histories__post_id'        );
        $this->dropForeignKey('post_tag_histories',     'fk_post_tag_histories__user_id'        );
        $this->dropForeignKey('post_votes',             'fk_post_id__posts_id'                  );
        $this->dropForeignKey('post_votes',             'fk_user_id__users_id'                  );
        $this->dropForeignKey('post_votes',             'fk_post_votes__post_id'                );
        $this->dropForeignKey('post_votes',             'fk_post_votes__user_id'                );
        $this->dropForeignKey('posts',                  'fk_posts__parent_id'                   );
        $this->dropForeignKey('posts',                  'fk_parent_id__posts_id'                );
        $this->dropForeignKey('posts',                  'fk_posts__user_id'                     );
        $this->dropForeignKey('posts',                  'posts__approver_id'                    );
        $this->dropForeignKey('posts',                  'fk_posts__approver_id'                 );
        $this->dropForeignKey('posts_tags',             'fk_posts_tags__post_id'                );
        $this->dropForeignKey('posts_tags',             'fk_posts_tags__tag_id'                 );
        $this->dropForeignKey('tag_aliases',            'fk_tag_aliases__alias_id'              );
        $this->dropForeignKey('tag_aliases',            'fk_tag_aliases__creator_id'            );
        $this->dropForeignKey('tag_implications',       'fk_tag_implications__predicate_id'     );
        $this->dropForeignKey('tag_implications',       'fk_tag_implications__consequent_id'    );
        $this->dropForeignKey('tag_implications',       'fk_tag_implications__creator_id'       );
        $this->dropForeignKey('tag_implications',       'fk_consequent_id'                      );
        $this->dropForeignKey('tag_subscriptions',      'fk_tag_subs__user_id'                  );
        $this->dropForeignKey('user_blacklisted_tags',  'fk_user_bl_tags__user_id'              );
        $this->dropForeignKey('user_blacklisted_tags',  'fk_user_blacklisted_tags__user_id'     );
        $this->dropForeignKey('user_logs',              'fk_user_logs__user_id'                 );
        $this->dropForeignKey('user_records',           'fk_user_records__reported_by'          );
        $this->dropForeignKey('user_records',           'fk_user_records__user_id'              );
        $this->dropForeignKey('users',                  'fk_users__avatar_post_id'              );
        $this->dropForeignKey('wiki_page_versions',     'fk_wiki_page_versions__wiki_page_id'   );
        $this->dropForeignKey('wiki_page_versions',     'fk_wiki_page_versions__wiki_page'      );
        $this->dropForeignKey('wiki_page_versions',     'fk_wiki_page_versions__user_id'        );
        $this->dropForeignKey('wiki_pages',             'fk_wiki_pages__user_id'                );

        print("Cleaning up artists...\n");

        // delete artist aliases where artist was deleted
        // add a foreign key below to enforce this automatically in the future
        $sql->executeSql("DELETE `A` FROM `artists` AS `A`"
            . " LEFT JOIN `artists` AS `B` ON `A`.`alias_id` = `B`.`id`"
            . " WHERE `A`.`alias_id` IS NOT NULL AND `B`.`id` IS NULL");

        // set group_id to null where group has been deleted
        // add a foreign key below to enforce this automatically in the future
        $sql->executeSql("UPDATE `artists` AS `A`"
            . " LEFT JOIN `artists` AS `B` ON `A`.`group_id` = `B`.`id`"
            . " SET `A`.`group_id` = NULL WHERE `B`.`id` IS NULL");

        // this column should be "unsigned" as it is a reference to id
        $sql->executeSql("ALTER TABLE `history_changes` MODIFY `previous_id` bigint(20) unsigned DEFAULT NULL");


        print("Converting table character sets...\n");
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
        

        print("Creating foreign keys...\n");
        $this->createForeignKey('artists',                  'alias_id',         'artists',          'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('artists',                  'group_id',         'artists',          'id',   ['delete' => 'set null']);
        $this->createForeignKey('artists',                  'updater_id',       'users',            'id');
        $this->createForeignKey('artists_urls',             'artist_id',        'artists',          'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('bans',                     'user_id',          'users',            'id');
        $this->createForeignKey('bans',                     'banned_by',        'users',            'id');
        $this->createForeignKey('batch_uploads',            'user_id',          'users',            'id');
        $this->createForeignKey('comments',                 'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('comments',                 'user_id',          'users',            'id');
        $this->createForeignKey('dmails',                   'from_id',          'users',            'id');
        $this->createForeignKey('dmails',                   'to_id',            'users',            'id');
        $this->createForeignKey('dmails',                   'parent_id',        'dmails',           'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('favorites',                'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('favorites',                'user_id',          'users',            'id');
        $this->createForeignKey('flagged_post_details',     'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('flagged_post_details',     'user_id',          'users',            'id');
        $this->createForeignKey('forum_posts',              'creator_id',       'users',            'id');
        $this->createForeignKey('forum_posts',              'last_updated_by',  'users',            'id');
        $this->createForeignKey('forum_posts',              'parent_id',        'forum_posts',      'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('histories',                'user_id',          'users',            'id');
        $this->createForeignKey('history_changes',          'history_id',       'histories',        'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('history_changes',          'previous_id',      'history_changes',  'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('inline_images',            'inline_id',        'inlines',          'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('inlines',                  'user_id',          'users',            'id');
        $this->createForeignKey('ip_bans',                  'banned_by',        'users',            'id');
        $this->createForeignKey('note_versions',            'note_id',          'notes',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('note_versions',            'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('note_versions',            'user_id',          'users',            'id');
        $this->createForeignKey('notes',                    'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('notes',                    'user_id',          'users',            'id');
        $this->createForeignKey('pools',                    'user_id',          'users',            'id');
        $this->createForeignKey('pools_posts',              'pool_id',          'pools',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('pools_posts',              'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('pools_posts',              'next_post_id',     'posts',            'id',   ['delete' => 'set null']);
        $this->createForeignKey('pools_posts',              'prev_post_id',     'posts',            'id',   ['delete' => 'set null']);
        $this->createForeignKey('post_tag_histories',       'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('post_tag_histories',       'user_id',          'users',            'id');
        $this->createForeignKey('post_votes',               'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('post_votes',               'user_id',          'users',            'id');
        $this->createForeignKey('posts',                    'parent_id',        'posts',            'id',   ['delete' => 'set null']);
        $this->createForeignKey('posts',                    'user_id',          'users',            'id');
        $this->createForeignKey('posts',                    'approver_id',      'users',            'id');
        $this->createForeignKey('posts_tags',               'post_id',          'posts',            'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('posts_tags',               'tag_id',           'tags',             'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('tag_aliases',              'alias_id',         'tags',             'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('tag_aliases',              'creator_id',       'users',            'id');
        $this->createForeignKey('tag_implications',         'predicate_id',     'tags',             'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('tag_implications',         'consequent_id',    'tags',             'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('tag_implications',         'creator_id',       'users',            'id');
        $this->createForeignKey('tag_subscriptions',        'user_id',          'users',            'id');
        $this->createForeignKey('user_blacklisted_tags',    'user_id',          'users',            'id');
        $this->createForeignKey('user_logs',                'user_id',          'users',            'id');
        $this->createForeignKey('user_records',             'user_id',          'users',            'id');
        $this->createForeignKey('user_records',             'reported_by',      'users',            'id');
        $this->createForeignKey('users',                    'avatar_post_id',   'posts',            'id',   ['delete' => 'set null']);
        $this->createForeignKey('wiki_page_versions',       'wiki_page_id',     'wiki_pages',       'id',   ['delete' => 'cascade' ]);
        $this->createForeignKey('wiki_page_versions',       'user_id',          'users',            'id');
        $this->createForeignKey('wiki_pages',               'user_id',          'users',            'id');

        print("Creating indexes...\n");
        $this->createUniqueKey('artists', 'name');
        $this->createUniqueKey('wiki_pages', 'title');
        $this->createIndex('histories', ['group_by_id', 'group_by_table']);
        $this->createIndex('history_changes', ['remote_id', 'table_name']);
        $this->createIndex('ip_bans', ['ip_addr']);
        $this->createIndex('posts', ['source']);
        $this->createIndex('posts', ['width']);
        $this->createIndex('posts', ['height']);
        $this->createIndex('posts', ['score']);
    }



    private function checkUniqueConflicts($table, $column)
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();

        $conflicts = $sql->selectValue("SELECT COUNT(*) FROM `{$table}`"
            . " GROUP BY `{$column}` HAVING COUNT(`{$column}`) > 1");

        if ($conflicts > 0) { throw new \Exception(
            "Cannot create unique or primary key for `{$table}`.`{$column}`; duplicates exists"); }
    }



    private function checkForeignKeyConflicts($table, $column, $reftable, $refcolumn)
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();

        $conflicts = $sql->selectValue("SELECT COUNT(*) FROM `{$table}` AS `A` LEFT JOIN `{$reftable}`"
            . " AS `B` ON `A`.`{$column}` = `B`.`${refcolumn}`"
            . " WHERE `A`.`{$column}` IS NOT NULL AND `B`.`{$refcolumn}` IS NULL");

        if ($conflicts > 0) { throw new \Exception(
            "Cannot create foreign key for {$table}.{$column} => {$reftable}.{$refcolumn};"
            . " {$conflicts} rows exists in the left table that reference non-existant rows in the right table"); }
    }



    private function dropForeignKey($table, $name)
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();
        // drop foreign key
        $sql->executeSql("IF EXISTS (SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` DROP CONSTRAINT `{$name}`; END IF;");
        // drop corresponding index
        $sql->executeSql("IF EXISTS (SELECT 1 FROM `information_schema`.`STATISTICS`"
            . " WHERE `INDEX_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `INDEX_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` DROP INDEX `{$name}`; END IF;");
    }



    private function createUniqueKey($table, $column)
    {
        $this->checkUniqueConflicts($table, $column);
        $name = 'uk_' . $table . '__' . $column;
        $this->execute("IF NOT EXISTS (SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}` UNIQUE(`${column}`); END IF;");
    }


    private function createPrimaryKey($table, $column)
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();

        $pcol = $sql->selectValue("SELECT `COLUMN_NAME` FROM `information_schema`.`KEY_COLUMN_USAGE`"
            . " WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='{$table}' AND CONSTRAINT_NAME='PRIMARY'");

        if ($pcol) {
            if ($pcol == $column) { return; }
            throw new \Exception("Cannot create primary key `{$column}` on"
                . " table `{$table}`; primary key `{$pcol}` already exists.");
        }
        
        $this->checkUniqueConflicts($table, $column);
        $sql->executeSql("ALTER TABLE `{$table}` ADD PRIMARY KEY(`${column}`)");
    }


    private function createForeignKey($table, $column, $reftable, $refcolumn, $options = [])
    {
        $sql = Rails\ActiveRecord\ActiveRecord::connection();

        $name = 'fk_' . $table . '__' . $column;        
        $exists = $sql->select("SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}'");
        if (!empty($exists)) { return; }

        $this->checkForeignKeyConflicts($table, $column, $reftable, $refcolumn);

        $sql->executeSql("ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}`"
            . " FOREIGN KEY (`${column}`) REFERENCES `{$reftable}` (`{$refcolumn}`)"
            . (isset($options['delete']) ? " ON DELETE {$options['delete']}" : "")
            . (isset($options['update']) ? " ON UPDATE {$options['update']}" : ""));
    }


    private function createIndex($table, $columns)
    {
        $name = 'ix_' . $table . '__' . implode('__', $columns);
        $colspec = implode(',', array_map(function($c) { return "`{$c}`"; }, $columns));
        $this->execute("IF NOT EXISTS (SELECT 1 FROM `information_schema`.`STATISTICS`"
            . " WHERE `TABLE_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `INDEX_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` ADD INDEX `{$name}` ({$colspec}); END IF;");
    }
}
