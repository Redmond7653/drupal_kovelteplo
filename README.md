
drush sql-dump > private/backup/2024-02-28-23-30.sql -- Вигрузити базу



drush sql-cli < private/backup/2024-02-28-23-30.sql  -- Загрузити базу




else if (substr($url, 0, 15) === "/password-reset") {
$page = '../private/pass-reset.php';
$param = substr($url, 16);
}
