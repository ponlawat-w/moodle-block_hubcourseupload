require(['jquery'], function ($) {
    $(document).ready(function () {
        var boost = (M.cfg.theme === 'boost');

        var $aside = boost ? $('aside[data-block="hubcourseupload"]') : $('div.block.block_hubcourseupload');

        var $form = $aside.find('form');
        var $filepickercol = $aside.find('*[data-fieldtype="filepicker"]');
        var $filepickerfieldname = $filepickercol.siblings('.col-md-3');
        if ($filepickerfieldname.length) {
            $filepickerfieldname.remove();
            $filepickercol.removeClass('col-md-9').addClass('container-fluid');

        }

        var $coursefileinput = $aside.find('input[name="coursefile"]');
        var $submitcol = $aside.find('*[data-fieldtype="submit"]');
        var $submitcolfieldname = $submitcol.siblings('.col-md-3');
        if ($submitcolfieldname.length) {
            $submitcolfieldname.remove();
            $submitcol.removeClass('col-md-3').addClass('container-fluid');

        }
        var $submitbtn = $aside.find('input[name="submitbutton"]');
        $submitbtn.prop('disabled', true);

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

        $coursefileinput.change(function () {
            if ($coursefileinput.val()) {
                $submitbtn.prop('disabled', false);
            } else {
                $submitbtn.prop('disabled', true);
            }
        });

        $form.submit(function () {
            $submitbtn.val(M.str.block_hubcourseupload.pleasewait);
            $submitbtn.attr('disabled', true);
        });
    });
});