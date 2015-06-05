$(document).ready(function() {
    $('#logout').on('click', function(ev) {
            $.ajax({
                method: 'POST',
                url: '/account/logout',
                success: function(data) {
                    location.reload(); 
                },
                error: function() {
                    console.log('Cannot load AJAX data.');
                }
            });
        });
});