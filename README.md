
drush sql-dump > private/backup/2024-02-28-23-30.sql -- Вигрузити базу
drush sql-drop #- Скинути базу

drush sql-cli < private/backup/2024-02-28-23-30.sql  -- Загрузити базу





else if (substr($url, 0, 15) === "/password-reset") {
$page = '../private/pass-reset.php';
$param = substr($url, 16);
}

------------------------------------------
SQL запит для експортування csv файла 'modx_site_content.csv' для міграції 'web/modules/custom/first_migration_module/migrations/content_migration.yml' -


SELECT modx_site_content.*, modx_site_tmplvar_contentvalues.value
FROM `modx_site_content` LEFT JOIN `modx_site_tmplvar_contentvalues` ON modx_site_content.id = modx_site_tmplvar_contentvalues.contentid
------------------------------------------


-----------------------------------------

Це якась фігня для показухі

SQL запит для витягування image path з content field

UPDATE modx_site_content
SET value = CASE
WHEN LOCATE('src="files/', content) > 0 AND LOCATE('"', content, LOCATE('src="files/', content) + LENGTH('src="files/')) > 0 THEN
REPLACE(
SUBSTRING(
content,
LOCATE('src="files/', content) + LENGTH('src="files/'),
LOCATE('"', content, LOCATE('src="files/', content) + LENGTH('src="files/')) - (LOCATE('src="files/', content) + LENGTH('src="files/'))
),
'"',
''
)
ELSE
value
END;

-----------------------------------------
