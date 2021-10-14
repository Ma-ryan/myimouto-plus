<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

set_time_limit(0);
$dbconfig = Yaml::parse(file_get_contents(__DIR__ . '/../config/database.yml'));

$input = null;
$login = $dbconfig['login'];
unset($dbconfig['login']);

foreach($dbconfig as $name => $env) {
    $dbconfig[$name] = array_merge($login, $dbconfig[$name]);
}


if (!isset($dbconfig['production'])) { die('error: no production database configuration'); }
$dbconfig = $dbconfig['production'];

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

$sql = new mysqli($dbconfig['host'], $dbconfig['username'], $dbconfig['password'], $dbconfig['database']);

$concharset = isset($dbconfig['charset']) ? $dbconfig['charset'] : '';

if ($concharset != '') { $sql->set_charset($concharset); }


$ctest = "\u{1F49A}";
$ctest2 = "\u{1F49A}\u{1F3E0}";
$test_query1 = '';
$test_query2 = '';
$test_append = $ctest . "\u{1F3E0}";



$sql->begin_transaction();
try
{
    $test_query1 = $sql->query("SELECT CONCAT(_utf8mb4 x'F09F929A', _utf8mb4 x'F09F8FA0') as `test`")->fetch_array()[0];
    $estr = $sql->real_escape_string($ctest2);
    $sql->query("INSERT INTO `artists` (`name`) VALUES('{$estr}')");
    $tid = $sql->insert_id;
    $test_query2 = $sql->query("SELECT `name` FROM `artists` WHERE `id`={$tid}")->fetch_array()[0];
}
finally
{
    $sql->rollback();
}


$phpcfg = [
    'default_charset'               => 'UTF-8',
    'mbstring.http_input'           => '',
    'mbstring.http_output'          => '',
    'mbstring.internal_encoding'    => '',
];


$sqlcfg = [
    'character_set_server'          => 'utf8mb4',
    'character_set_client'          => 'utf8mb4',
    'character_set_connection'      => 'utf8mb4',
    'character_set_results'         => 'utf8mb4',
    'character_set_database'        => 'utf8mb4',
    'collation_server'              => 'utf8mb4_unicode_ci',
    'collation_database'            => 'utf8mb4_unicode_ci',
];


$tables = [
    'advertisements',
    'artists',
    'artists_urls',
    'bans',
    'batch_uploads',
    'comments',
    'dmails',
    'favorites',
    'flagged_post_details',
    'forum_posts',
    'histories',
    'history_changes',
    'inline_images',
    'inlines',
    'ip_bans',
    'job_tasks',
    'note_versions',
    'notes',
    'pools',
    'pools_posts',
    'post_tag_histories',
    'post_votes',
    'posts',
    'posts_tags',
    'schema_migrations',
    'table_data',
    'tag_aliases',
    'tag_implications',
    'tag_subscriptions',
    'tags',
    'user_blacklisted_tags',
    'user_logs',
    'user_records',
    'users',
    'wiki_page_versions',
    'wiki_pages',
];

$columns = [
    ['artists',                 'name'              ],
    ['artists_urls',            'url'               ],
    ['artists_urls',            'normalized_url'    ],
    ['bans',                    'reason'            ],
    ['batch_uploads',           'tags'              ],
    ['batch_uploads',           'data_as_json'      ],
    ['comments',                'body'              ],
    ['dmails',                  'title'             ],
    ['dmails',                  'body'              ],
    ['flagged_post_details',    'reason'            ],
    ['forum_posts',             'title'             ],
    ['forum_posts',             'body'              ],
    ['history_changes',         'value'             ],
    ['inline_images',           'description'       ],
    ['inlines',                 'description'       ],
    ['ip_bans',                 'reason'            ],
    ['note_versions',           'body'              ],
    ['notes',                   'body'              ],
    ['pools',                   'name'              ],
    ['pools',                   'description'       ],
    ['pools_posts',             'sequence'          ],
    ['post_tag_histories',      'tags'              ],
    ['posts',                   'source'            ],
    ['posts',                   'cached_tags'       ],
    ['schema_migrations',       'version'           ],
    ['tag_aliases',             'name'              ],
    ['tag_aliases',             'reason'            ],
    ['tag_implications',        'reason'            ],
    ['tag_subscriptions',       'tag_query'         ],
    ['tag_subscriptions',       'name'              ],
    ['tags',                    'name'              ],
    ['tags',                    'cached_related'    ],
    ['user_blacklisted_tags',   'tags'              ],
    ['user_records',            'body'              ],
    ['users',                   'name'              ],
    ['users',                   'my_tags'           ],
    ['wiki_page_versions',      'title'             ],
    ['wiki_page_versions',      'body'              ],
    ['wiki_page_versions',      'text_search_index' ],
    ['wiki_pages',              'title'             ],
    ['wiki_pages',              'body'              ],
    ['wiki_pages',              'text_search_index' ],
];


?>


<!DOCTYPE html>
<html>
    <head>
        <title>Debug UTF-8</title>
        <style>
            td { border: 1px solid #000; padding: 0.25em 0.5em; }
            td.pass { color: #090; }
            td.fail { color: #900; }
            thead td { font-weight: bold; }
        </style>
    </head>
    <body>

        <h2>PHP Settings</h2>
        <table>
            <thead>
                <tr><td>Setting</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
            <tbody>
            <?php foreach ($phpcfg as $setting => $expected) { $actual = ini_get($setting); $class = $actual == $expected ? 'pass' : 'fail'; ?>
                <tr><td><?= $setting ?></td><td><?= $expected ?></td><td class="<?= $class ?>"><?= $actual ?></td></tr>
            <?php } ?>
            </tbody>
        </table>

        <h2>MyImouto Settings</h2>
        <table>
            <thead>
                <tr><td>Setting</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
            <tbody>
            <?php $actual = $concharset; $expected = 'utf8mb4'; $class = $actual == $expected ? 'pass' : 'fail'; ?>
                <tr><td>charset</td><td><?= $expected ?></td><td class="<?= $class ?>"><?= $actual ?></td></tr>
            </tbody>
        </table>

        <h2>SQL Settings</h2>
        <table>
            <thead>
                <tr><td>Setting</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
            <tbody>
            <?php foreach ($sqlcfg as $setting => $expected) {
                $actual = $sql->query('SELECT @@' . $setting)->fetch_array()[0];
                $class = $actual == $expected ? 'pass' : 'fail'; ?>
                <tr><td><?= $setting ?></td><td><?= $expected ?></td><td class="<?= $class ?>"><?= $actual ?></td></tr>
            <?php } ?>                
            </tbody>
        </table>
        
        <h2>Table Collation</h2>
        <table>
            <thead>
                <tr><td>Table</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
            <tbody>
            <?php foreach ($tables as $table) {
                $actual = $sql->query("SELECT `TABLE_COLLATION` FROM `information_schema`.`TABLES`"
                    . " WHERE `TABLE_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$table}'")->fetch_array()[0];
                $class = $actual == 'utf8mb4_unicode_ci' ? 'pass' : 'fail'; ?>
                <tr><td><?= $table ?></td><td>utf8mb4_unicode_ci</td><td class="<?= $class ?>"><?= $actual ?></td></tr>
            <?php } ?>                
            </tbody>        
        </table>

        <h2>Column Collation</h2>
        <table>
            <thead>
                <tr><td>Table</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
            <tbody>
            <?php foreach ($columns as $column) {
                $actual = $sql->query("SELECT `COLLATION_NAME` FROM `information_schema`.`COLUMNS`"
                    . " WHERE `TABLE_SCHEMA`=DATABASE() AND `TABLE_NAME`='{$column[0]}' AND `COLUMN_NAME`='{$column[1]}'")->fetch_array()[0];
                $class = $actual == 'utf8mb4_unicode_ci' ? 'pass' : 'fail'; ?>
                <tr><td><?= implode('.', $column) ?></td><td>utf8mb4_unicode_ci</td><td class="<?= $class ?>"><?= $actual ?></td></tr>
            <?php } ?>                
            </tbody>        
        </table>

        <h2>Unicode Tests</h2>
        <table>
            <thead>
                <tr><td>Test</td><td>Expected Value</td><td>Actual Value</td></tr>
            </thead>
                <tr><td>Unicode Escape</td><td>&#128154;&#127968;</td><td><?= $ctest2 ?></td></tr>
                <tr><td>Unicode Append</td><td>&#128154;&#127968;</td><td><?= $test_append ?></td></tr>
                <tr><td>htmlspecialchars()</td><td>&#128154;&#127968;</td><td><?= htmlspecialchars($ctest2) ?></td></tr>
                <tr><td>Simple SQL Query</td><td>&#128154;&#127968;</td><td><?= $test_query1 ?></td></tr>
                <tr><td>Round Trip SQL Query</td><td>&#128154;&#127968;</td><td><?= $test_query2 ?></td></tr>
            <tbody>
        </table>        
    </body>
</html>


