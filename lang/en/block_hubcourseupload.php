<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * English language string
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Hub Course Upload';

$string['hubcourseupload:addinstance'] = 'Create a new instance';
$string['hubcourseupload:myaddinstance'] = 'Create a new instance in my page';
$string['hubcourseupload:upload'] = 'Upload a course to web';

$string['settings:allowcapabilitychange'] = 'Allow overwriting default capability';
$string['settings:allowcapabilitychange_description'] = 'If checked, capability <i>moodle/restore:restorecourse</i> will be granted to general authorized users.';
$string['settings:autoenableguestenrol'] = 'Auto enable guest enrolment';
$string['settings:autoenableguestenrol_description'] = 'Enable guest enrolment method automatically after course is uploaded';
$string['settings:maxfilesize'] = 'Maximum course file size (MB)';
$string['settings:maxfilesize_description'] = 'Maximum course backup size per file in megabytes (MB)<br><small>*Actual maximum upload size might be limited by server settings in <i>php.ini</i> file.</small>';
$string['settings:defaultcategory'] = 'Default category';
$string['settings:defaultcategory_description'] = 'Default category of newly uploaded course';
$string['settings:autocreateinfoblock'] = 'Create course info block after uploaded';
$string['settings:autocreateinfoblock_decription'] = 'Automatically create a hub course info block instance to uploaded course.';

$string['error_filenotuploaded'] = 'There is no file uploaded.';
$string['error_cannotsaveuploadfile'] = 'Cannot read upload file.';
$string['error_backupisnotcourse'] = 'Backup file is not a course backup.';
$string['error_cannotextractfile'] = 'Cannot extract file.';
$string['error_cannotgetroleinfo'] = 'Cannot get role <i>block_hubcourseupload</i>, please manually create this role with given short name having permission <i>moodle:restore/restorecourse</i>.';
$string['error_cannotrestore'] = 'Cannot perform restore execution.';
$string['error_categorynotfound'] = 'Category not found';

$string['uploadcoursetohub'] = 'Upload Your Course to Hub';

$string['coursefilechoose'] = 'Open file browser…';
$string['draganddrop'] = 'Or you can also drag and drop your <i>.mbz</i> file here…';
$string['nocapability'] = 'You are not allowed to upload file to hub.';
$string['nosignin'] = 'Please sign in to upload your course to hub.';
$string['uploaddescription'] = 'Supported file format: .mbz';
$string['maxfilesize'] = 'Maximum file size: {$a}MB';
$string['pleasewait'] = 'Please wait…';

$string['continueupload'] = 'Continue Upload';

$string['proceedanyway'] = 'Proceed Anyway';

$string['warning_moodleversion'] = '<p><strong>Warning!</strong> Course from your file is originally from newer Moodle version, the demo course on this site might not function correctly.
<br>Do you want to continue?</p>
<p><strong>Your Course Moodle Version:</strong> <span class="text-success">{$a->original}</span><br>
<strong>Moodle Version on this Site:</strong> <span class="text-danger">{$a->current}</span></p>';

$string['warning_pluginversion'] = '<strong>Warning!</strong> Some plugins in your course do not match with the ones in this site. This might causes your course to function improperly in current site.
<br>Please check list below.';
$string['requiredplugin_name'] = 'Plugin Name';
$string['requiredplugin_courseversion'] = 'Version from your course';
$string['requiredplugin_siteversion'] = 'Version in this site';
$string['requiredplugin_status'] = 'Status';
$string['requiredplugin_notinstalled'] = 'Not installed in this site';
$string['requiredplugin_identical'] = 'Identical';
$string['requiredplugin_siteolder'] = 'This site has an older version';
$string['requiredplugin_sitenewer'] = 'This site has a newer version';

$string['initialversion'] = 'Initial version';