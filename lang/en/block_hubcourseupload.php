<?php
$string['pluginname'] = 'Hub Course Upload';

$string['hubcourseupload:addinstance'] = 'Create a new instance';
$string['hubcourseupload:myaddinstance'] = 'Create a new instance in my page';
$string['hubcourseupload:upload'] = 'Upload a course to web';

$string['settings:allowcapabilitychange'] = 'Allow overwriting default capability';
$string['settings:allowcapabilitychange_description'] = 'If checked, capability <i>moodle/restore:restorecourse</i> will be granted to general authorized users.';
$string['settings:maxfilesize'] = 'Maximum course file size (MB)';
$string['settings:maxfilesize_description'] = 'Maximum course backup size per file in megabytes (MB)<br><small>*Actual maximum upload size might be limited by server settings in <i>php.ini</i> file.</small>';
$string['settings:autocreateinfoblock'] = 'Create course info block after uploaded';
$string['settings:autocreateinfoblock_decription'] = 'Automatically create a hub course info block instance to uploaded course.';

$string['error_filenotuploaded'] = 'There is no file uploaded.';
$string['error_cannotsaveuploadfile'] = 'Cannot read upload file.';
$string['error_backupisnotcourse'] = 'Backup file is not a course backup.';
$string['error_cannotextractfile'] = 'Cannot extract file.';
$string['error_cannotgetroleinfo'] = 'Cannot get role <i>block_hubcourseupload</i>, please manually create this role with given short name having permission <i>moodle:restore/restorecourse</i>.';
$string['error_cannotrestore'] = 'Cannot perform restore execution.';

$string['uploadcoursetohub'] = 'Upload Your Course to Hub';

$string['coursefilechoose'] = 'Open file browser…';
$string['draganddrop'] = 'Or you can also drag and drop your <i>.mbz</i> file here…';

$string['nocapability'] = 'You are now allowed to upload file to hub.';

$string['uploaddescription'] = 'Supported file format: .mbz';
$string['maxfilesize'] = 'Maximum file size: {$a}MB';

$string['initialversion'] = 'Initial version';