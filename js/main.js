$(function () {

    var updateTaskNode = function(el) {
        var $widget = $(el),
            $checkbox = $(el).find('input').eq(0),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        $widget.data('state', $checkbox.is(':checked') ? "on" : "off");
        $widget.css('cursor', 'pointer');

        if ($widget.find('.state-icon').length == 0) {
            $checkbox.hide();
            $widget.prepend('<span class="state-icon ' + settings[$widget.data('state')].icon + '"></span> ');
        }

        $widget.on('click', function(e) {
            var isChecked = $checkbox.is(':checked');
            $checkbox.prop('checked', !isChecked);
            $widget.data('state', (!isChecked) ? "on" : "off");

            $widget.find('.state-icon').removeClass()
                .addClass('state-icon ' + settings[$widget.data('state')].icon);

            if (!isChecked) {
                $widget.addClass('list-group-item-primary active');
            } else {
                $widget.removeClass('list-group-item-primary active');
            }

            $.ajax({
                dataType: 'json',
                type: 'get',
                url: (isChecked ? 'uncheck/' : 'check/' )+$checkbox.attr('name'),
                error: function(data) {
                    var errorbox = $('<div class="alert alert-danger">'+data.responseJSON.text+'</div>');
                    form.before(errorbox);
                    errorbox.hide(0).slideDown();
                    timeoutAlerts(5000);
                }
            });

        });
    }

    $('.list-group.checked-list-box .list-group-item').each(function (index,el) {
        updateTaskNode(el);
    });

    var form = $('#addTask');
    form.on('submit',function(e){

        e.preventDefault();
        var textbox = $('#tasktext');

        $.ajax({
            dataType: 'json',
            type: 'post',
            url: form.attr('action'),
            data: {text: textbox.val()},
            success: function(data) {
                console.log(data);
                var successbox = $('<div class="alert alert-success">'+data.msg+'</div>');
                form.before(successbox);
                successbox.hide(0).slideDown();
                var newTask = $(data.item);
                $('#taskList').prepend(newTask);
                updateTaskNode(newTask[0]);
                newTask.hide(0).slideDown();
                textbox.val('');
                timeoutAlerts(2000);
            },
            error: function(data) {
                var errorbox = $('<div class="alert alert-danger">'+data.responseJSON.text+'</div>');
                form.before(errorbox);
                errorbox.hide(0).slideDown();
                timeoutAlerts(5000);
            }
        });

        return false;
    });

    var timeoutAlerts = function(time) {
        setTimeout(function(){
            $('.alert').slideUp();
        },time);
    }

    if($('.alert').length > 0) {
        timeoutAlerts(5000);
    }

    $('#update').hide();

});
