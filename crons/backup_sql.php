
<?php

require('../connect.php');

$database = 'pim';
$backupPath= '../backup';
// MySQL dump command
$command = "mysqldump -h $host -u $user -p'$pass' $name > $backupPath/backup_" . date('Y-m-d') . ".sql";

// Execute the command
exec($command, $output, $returnCode);

// Check if backup was successful
if ($returnCode === 0) {
    echo 'Backup successful!';
} else {
    echo 'Backup failed!';
}
?>
