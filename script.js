require(['jquery'], function($) {
    $(document).ready(function() {
        var $aside = $('aside[data-block="hubcourseupload"]');
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

        var $filetypedescription = $aside.find('.form-filetypes-descriptions');
        if ($filetypedescription.length) {
            var $siblingp = $filetypedescription.siblings('p');
            if ($siblingp.length) {
                $siblingp.append(' ');
                $siblingp.append($filetypedescription);
                $filetypedescription.css('display', 'inline-block');
                $filetypedescription.find('li').css('display', 'inline');
            }
        }

        $coursefileinput.change(function() {
            if ($coursefileinput.val()) {
                $submitbtn.prop('disabled', false);
            } else {
                $submitbtn.prop('disabled', true);
            }
        })
    });
});