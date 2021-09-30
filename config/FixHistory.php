<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

set_time_limit(0);
$dbconfig = Yaml::parse(file_get_contents(__DIR__ . '/database.yml'));

$input = null;
$login = $dbconfig['login'];
unset($dbconfig['login']);

foreach($dbconfig as $name => $env) {
    $dbconfig[$name] = array_merge($login, $dbconfig[$name]);
}


print("This script will scan the database and generate any missing history records.\n");


do {
    print("\nDatabase Configurations\n");
    foreach($dbconfig as $name => $env) {
        print("    {$name} | H: {$env['host']} | U: {$env['username']} | D: {$env['database']}\n");
    }

    print("\n");
    $input = readline("Select Configuration: ");
} while (!isset($dbconfig[$input]));

$dbconfig = $dbconfig[$input];

print("\nConnecting to SQL server {$dbconfig['host']}...\n");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

set_error_handler(function($errno, $errstr, $errfile, $errline ){
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
});

$sql = new mysqli($dbconfig['host'], $dbconfig['username'], $dbconfig['password'], $dbconfig['database']);


$pid = 0; $ccnt = 0; $pcnt = 0;

$user = $sql->query(
    "SELECT `id`, `name` FROM `users`"
        . " WHERE `level` >= 50"
        . " ORDER BY `id` ASC"
        . " LIMIT 1")->fetch_object();

if (!$user) { die("no suitable user account found"); }

$pmax = $sql->query("SELECT MAX(`id`) FROM `posts`")->fetch_row()[0];

print("\nHistory will be attributed to user {$user->id} ({$user->name}).");
print("\nA total of {$pmax} posts will be processed.\n");
$continue = readline("Continue (y or n): ");
if (strtolower(trim($continue)) != 'y') { print("Aborting.\n"); exit(0); }


$attrs = [
    'source' => '',
    'rating' => null,
    'parent_id' => null];


$pselect = $sql->prepare(
    "SELECT `id`, `status`, `source`, `rating`, `parent_id` FROM `posts` WHERE `id` = ?");

$cselect = $sql->prepare(
    "SELECT * FROM `history_changes`"
        . " WHERE `table_name` = 'posts' AND `previous_id` IS NULL AND `remote_id` = ?");

$hinsert = $sql->prepare(
    "INSERT INTO `histories`"
        . "(`created_at`, `user_id`, `group_by_id`, `group_by_table`, `aux_as_json`)"
        . "VALUES (NOW(), ?, ?, 'posts', NULL)");

$cinsert = $sql->prepare(
    "INSERT INTO `history_changes`"
        . " (`column_name`, `remote_id`, `table_name`, `value`, `history_id`, `previous_id`, `value_index`)"
        . " VALUES (?, ?, 'posts', ?, ?, NULL, '')");


for ($pid = 0; $pid <= $pmax; ++$pid) {

    if ($pid % 10 == 0) { // report progress and yield to more important processes
        printf("\rProcessing post history... %5.1f%% (%u/%u)", 100.0*$pid/$pmax, $pid, $pmax);
        // only yield; if script causes performance issues, comment out sleep and uncomment usleep below
        sleep(0);
        //usleep(1000);
    }

    $sql->begin_transaction();

    try {
        $pselect->bind_param('i', $pid);
        $pselect->execute();
        if (!($post = $pselect->get_result()->fetch_object())) { $sql->commit(); continue; }
        if ($post->status == 'deleted') { $sql->commit(); continue; }
        
        $changes = [];
        $cselect->bind_param('i', $pid);
        $cselect->execute();
        $cresult = $cselect->get_result();
        while ($change = $cresult->fetch_object()) {
            $changes[$change->column_name] = $change;
        }

        $lcnt = 0;
        $hid = reset($changes)->history_id;
        
        if (!$hid) {
            $hinsert->bind_param('ii', $uid, $pid);
            $hinsert->execute();
            $hid = $sql->insert_id;
        }

        foreach ($attrs as $column => $default) {
            if (isset($changes[$column])) { continue; }
            if ($post->$column == $default) { continue; }
            $cinsert->bind_param('sisi', $column, $pid, $post->$column, $hid);
            $cinsert->execute();
            ++$lcnt;
        }

        $ccnt += $lcnt;
        if ($lcnt > 0) { ++$pcnt; }
        $sql->commit();
    } catch(Exception $ex) {
        $sql->rollback();
        $msg = $ex->getMessage();
        print("An error occurred proccesing post #{$pid}:\n{$msg}\n");
        exit(1);
    }
}


$pselect->close();
$cselect->close();
$cinsert->close();
print("\rProcessing post history... DONE               \n");
print("Created {$ccnt} history records for {$pcnt} posts.\n");



print "\nFinished\n";
$sql->close();
