jQuery(document).ready(function ($) {
    jQuery.noConflict();
    var msloader = '<img src="'+msplugin_url + '/assets/images/ajax-loader.gif" />';
    $('#msloginform').submit(function (e) {
        e.preventDefault();
        $('#uservalidation_error').html(msloader).removeClass('alert-danger');
        
        var username = $('#msuser_login').val();
        var password = $('#msuser_pass').val();
        var levelId = $('#mslevelId').val();
        var post_data = {
            username: username,
            password: password,
            levelId: levelId,
            action: 'membersonic_login'
        };
        console.log(post_data);
        $.post(
            objectL10n.ajaxurl,
            post_data,
            function (ret) {
                console.log(ret);
                var response = JSON.parse(ret);
                console.log(response);
                if (response.status == 'success') {
                    window.location.href = response.redirecturl;
                }
                if (response.status == 'error') {
                    $('#uservalidation_error').html(response.message).addClass('alert-danger');
                }
            });
    })
    $('#newregistration').submit(function (e) {
        e.preventDefault();
        $('#result').html(msloader).removeClass('alert-danger');
        $('#password_text1_error').text('').removeClass('text-danger');
        var firstname = jQuery('#msfirst_name').val();
        var lastname = jQuery('#mslast_name').val();
        var email = jQuery('#msemail').val();
        var membershipid = jQuery('#membership_id').val();
        var password = jQuery('#mspassword1').val();
        var password2 = jQuery('#mspassword2').val();
        var sec = $('#_wpnonce').val();
        var uniqid = $('#uniqid').val();

        if (password != password2) {
            $('#password_text1_error').text('Passwords Dont Match').addClass('text-danger');
            $('#result').html('').removeClass('alert-danger');
            return;
        }
        var data = {
            'action': 'mslite_saveNewUser',
            'firstname': firstname,
            'lastname': lastname,
            'email': email,
            'password': password,
            'membershipid': membershipid,
            'security': sec,
            'uniqid': uniqid,
            'type': 'front'
        };
       // console.log(data);
        jQuery.post(objectL10n.ajaxurl, data, function (ret) {
            var response = JSON.parse(ret);
            console.log(response);
            if (response.status == 'success')
                jQuery('#result').text(response.message).addClass('alert-success').removeClass('alert-danger');
            else
                jQuery('#result').text(response.message).addClass('alert-danger').removeClass('alert-success');
        });
    })
});