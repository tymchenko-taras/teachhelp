$(function(){
    $('body').on('click', '.table.table-hover tr', function(){
        var post = {};
        var row = $(this);
        row.toggleClass('highlight');
        $.post('/chose', post, function(response){

        })
    });


    $('.klsjdfb').on('submit', function(){
        var form = $(this);
        var post = {
            'ajax': 'on',
        };

        form.find('input').each(function() {
            var self = $(this);
            if ((self.attr('type') == 'checkbox') && !self.is(':checked')) {
                return true;
            }
            if(!self.attr('name')){
                return true;
            }

            post[self.attr('name')] = self.val();
        });

        $.post(form.attr('action'), post, function(response){
            $('.result').html(response);
        })

        return false;
    });
});