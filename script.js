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
 * Script file for adjusting style of block
 *
 * @package block_hubcourseupload
 * @copyright 2018 Moodle Association of Japan
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(['jquery'], function ($) {
    $(document).ready(function () {
        var boost = (M.cfg.theme === 'boost');

        var $aside = boost ? $('[data-block="hubcourseupload"]') : $('div.block.block_hubcourseupload');

        var $form = $aside.find('form');
        var $filepickercol = $aside.find('*[data-fieldtype="filepicker"]');
        var $filepickerfieldname = $filepickercol.siblings('.col-md-3');
        if ($filepickerfieldname.length) {
            $filepickerfieldname.remove();
            $filepickercol.removeClass('col-md-9').addClass('container-fluid');
        }

        var $formgroup = $form.find('.form-group');
        if ($formgroup.length) {
            $formgroup.css('margin-left', '0');
            $formgroup.css('margin-right', '0');
        }

        if (!boost) {
            var $fsubmit = $aside.find('.fsubmit');
            if ($fsubmit.length) {
                $fsubmit.css('margin-left', '0');
            }
        }

        var $coursefileinput = $aside.find('input[name="coursefile"]');
        var $submitcol = $aside.find('*[data-fieldtype="submit"]');
        var $submitcolfieldname = $submitcol.siblings('.col-md-3');
        if ($submitcolfieldname.length) {
            $submitcolfieldname.remove();
            $submitcol.removeClass('col-md-3').addClass('container-fluid');

        }
        var $submitbtn = $aside.find('input[name="submitbutton"]');
        $submitbtn.hide();

        $filepickercol.find('input[name="coursefilechoose"]').val(M.str.block_hubcourseupload.coursefilechoose);
        $filepickercol.find('.dndupload-message').html(
            M.str.block_hubcourseupload.draganddrop + '<br><div class="dndupload-arrow"></div>'
        );

        var $container = $aside.find('.filepicker-container');
        if ($container.length) {
            $container.css('padding-top', '55px');
        }

        var $arrow = $aside.find('.dndupload-arrow');
        if ($arrow.length) {
            $arrow.css('height', '50px');
        }

        var $filetypedescription = $aside.find('.form-filetypes-descriptions');
        if ($filetypedescription.length) {
            var $siblingp = $filetypedescription.siblings('p');
            if ($siblingp.length) {
                $siblingp.append(' ');
                $siblingp.append($filetypedescription);
                $filetypedescription.css('display', 'inline-block');
                var $filetypelis = $filetypedescription.find('li');
                for (var i = 0; i < $filetypelis.length; i++) {
                    var $filetypeli = $($filetypelis[i]);
                    $filetypeli.css('display', 'inline');

                    var $filetypesmall = $filetypeli.find('small');
                    $filetypeli.append('(' + $filetypesmall.html() + ')');
                    $filetypesmall.remove();
                }
            }

            var $parentp = $filetypedescription.parent('p:not(div)');
            $parentp.css('font-weight', 'bold');
            $parentp.find('div').css('font-weight', 'normal');
        }

        if (!boost) {
            $aside.find('div[data-fieldtype="filepicker"]').css('margin-left', '0');
        }

        $coursefileinput.change(function () {
            if ($coursefileinput.val()) {
                $submitbtn.show();
            } else {
                $submitbtn.hide();
            }
        });

        $form.submit(function () {
            $submitbtn.val(M.str.block_hubcourseupload.pleasewait);
            $submitbtn.attr('disabled', true);
        });
    });
});