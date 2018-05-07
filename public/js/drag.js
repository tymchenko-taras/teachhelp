$(function(){
    $('body').on('change', '.switch [data-id][type="checkbox"]', function(){
        var checkox = $(this);
        var post = {
            '_token': $('[name="_token"]').val(),
            'data': {
                'sid': checkox.attr('data-id'),
                'gcid': 1,
                'value': +checkox.is(':checked')
            }
        };

        $.post('/set/sentence-gc', post, function(response){
            checkox.attr('data-assigned', '1');
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