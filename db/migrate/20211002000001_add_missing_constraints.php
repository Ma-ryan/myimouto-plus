<?php
class AddMissingConstraints extends Rails\ActiveRecord\Migration\Base
{


    public function up()
    {
        // add missing foreign key constraints
        $this->createForeignKey('Favorite', 'favorites', 'post_id', 'posts', 'id', ['delete' => 'cascade']);
        $this->createForeignKey('Favorite', 'favorites', 'user_id', 'users', 'id');
        $this->createForeignKey('History', 'histories', 'user_id', 'users', 'id');
        $this->createForeignKey('NoteVersion', 'note_versions', 'user_id', 'users', 'id');
        $this->createForeignKey('Note', 'notes', 'user_id', 'users', 'id');
        $this->createForeignKey('Pool', 'pools', 'user_id', 'users', 'id');
        $this->createForeignKey('PostTagHistory', 'post_tag_histories', 'user_id', 'users', 'id');
        $this->createForeignKey('WikiPageVersion', 'wiki_page_versions', 'user_id', 'users', 'id');

        // there is a duplicate foreign key on tag_implications.consequent_id so remove the duplicate
        $this->dropForeignKey('tag_implications', 'fk_consequent_id');

        // add missing unique key constraints
        $this->createUniqueKey('Artist', 'artists', 'name');
        $this->createUniqueKey('WikiPage', 'wiki_pages', 'title');

        // add some more indexes to help improve performance
        $this->createIndex('histories', ['group_by_id', 'group_by_table']); // improves object specific history performance
        $this->createIndex('history_changes', ['remote_id', 'table_name']); // improves object specific history performance
        $this->createIndex('ip_bans', ['ip_addr']);     // improves ip ban lookup perfomance
        $this->createIndex('posts', ['source']);        // improves "source:" metatag search performance
        $this->createIndex('posts', ['width']);         // improves "width:" metatag search performance
        $this->createIndex('posts', ['height']);        // improves "height:" metatag search performance
        $this->createIndex('posts', ['score']);         // improves "score:" metatag search performance
        
        // Additionally tag_subscriptions should have a unique key on (user_id, name)
        // but I wont worry about that now since we may scrap this later

        // Additionally some tables (user_blacklisted_tags for example) have unique keys
        // that should really be primary keys but fixing this is not very practical now.

    }


    private function checkUniqueKeyConflicts($model, $table, $attr)
    {
        $conflicts = 0;
        $sql = "SELECT * FROM `{$table}` GROUP BY `{$attr}` HAVING COUNT(`{$attr}`) > 1";
        $collection = call_user_func([$model, 'findBySql'], $sql);
        foreach ($collection as $obj) { ++$conflicts; }
        if ($conflicts > 0) { throw new \Exception(
            "Cannot create unique key for {$table}.{$attr};"
            . " {$conflicts} set" . ($conflicts>1?'s':'') . " of duplicates exists"); }
    }



    private function checkForeignKeyConflicts($model, $table, $column, $reftable, $refcolumn)
    {
        $conflicts = 0;
        $sql = "SELECT `{$table}`.* FROM `{$table}` LEFT JOIN `{$reftable}`"
            . " ON `{$table}`.`{$column}` = `{$reftable}`.`${refcolumn}`"
            . " WHERE `{$reftable}`.`{$refcolumn}` IS NULL";
        $collection = call_user_func([$model, 'findBySql'], $sql);
        foreach ($collection as $obj) { ++$conflicts; }
        if ($conflicts > 0) { throw new \Exception(
            "Cannot create foreign key for {$table}.{$column} => {$reftable}.{$refcolumn};"
            . " {$conflicts} rows exists in the left table that reference non-existant rows in the right table"); }
    }



    private function dropForeignKey($table, $name)
    {
        $this->execute("IF EXISTS (SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` DROP CONSTRAINT `{$name}`; END IF;");        
    }



    private function createUniqueKey($model, $table, $column)
    {
        $this->checkUniqueKeyConflicts($model, $table, $column);
        $name = 'uk_' . $table . '__' . $column;
        $this->execute("IF NOT EXISTS (SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}` UNIQUE(`${column}`); END IF;");
    }


    private function createForeignKey($model, $table, $column, $reftable, $refcolumn, $options = [])
    {
        $this->checkForeignKeyConflicts($model, $table, $column, $reftable, $refcolumn);
        $name = 'fk_' . $table . '__' . $column;
        $this->execute("IF NOT EXISTS (SELECT 1 FROM `information_schema`.`TABLE_CONSTRAINTS`"
            . " WHERE `CONSTRAINT_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}' AND `CONSTRAINT_NAME`='{$name}')"
            . " THEN ALTER TABLE `{$table}` ADD CONSTRAINT `{$name}`"
            . " FOREIGN KEY (`${column}`) REFERENCES `{$reftable}` (`{$refcolumn}`)"
            . (isset($options['delete']) ? " ON DELETE {$options['delete']}" : "")
            . (isset($options['update']) ? " ON UPDATE {$options['update']}" : "")
            . "; END IF;");
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
