<?php
$g_hostname               = 'localhost';
$g_db_type                = 'mysqli';
$g_database_name          = 'support';
$g_db_username            = 'support2018';
$g_db_password            = 'SiYLx68d6PdHsSh3';

$g_path = 'http://support.developerstohire.com/';

$g_default_timezone       = 'Asia/Kolkata';

$g_crypto_master_salt     = 'jHWmXXloA4Lu7FD4I9350qGFUnWkO8IjlCPQzsj4tw8=';

$g_allow_signup    = ON;  //allows the users to sign up for a new account
$g_form_security_validation =OFF; //Form security validation.
$g_enable_email_notification = ON; //enables the email messages
$g_phpMailer_method = PHPMAILER_METHOD_SMTP;
$g_smtp_host = 'smtp.gmail.com';
$g_smtp_connection_mode = 'ssl';
$g_smtp_port = 465;
$g_smtp_username = 'support@matrixnmedia.com'; //replace it with your gmail address
$g_smtp_password = 'ebybbwufkkmgyxed'; //replace it with your gmail password
$g_administrator_email = 'support@matrixnmedia.com'; //this will be your administrator email address


$g_logo_image = 'images/cmpny_logo.png';
$g_favicon_image = 'images/favicon.png';
$g_application_logo = 'images/icon.png';
$g_show_avatar = ON;
$g_show_avatar_threshold = REPORTER;
$g_window_title = 'Matrix Bug Tracker';
$g_company_homepage = 'http://www.matrixnmedia.com/';
$g_powered_by_logo = 'images/icon.gif';
$g_application_homepage = 'http://www.matrixnmedia.com/';
$g_from_name	= 'Matrix Bug Tracker';



$g_log_level = LOG_EMAIL | LOG_EMAIL_RECIPIENT;
$g_log_destination = 'file:C:/xampp/htdocs/mantis/logs/mantisbt.log';

$g_reauthentication_expiry = 60 * 60;

$g_max_file_size = 25000000;

$g_allow_delete_own_attachments = ON;

$g_antispam_max_event_count = 0;  //enables the no restriction of actions as per new users

$g_preview_attachments_inline_max_size = 1920 * 1920;
