jQuery(document).ready(function ($) {
	jQuery.noConflict();
	$('#sc_freereg').on('click', function () {
		$('.scform_cont').hide();
		$('#sc_freereg_form').show();
		$('.sc_result').val('');
		$('.sc_result').show();
		return false;
	});
	$('.membership-code-add').on('change', function () {
		var val = '[MSREGISTRATION level=' + $('.membership-code-add').val() + ']';
		$('.sc_result').val(val);
	});
	//code4
	$('#sc_loginform').on('click', function () {
		$('.scform_cont').hide();
		$('.sc_result').show();
		$('.sc_result').val('[MSLOGIN]');
		return false;
	});
	$('#sc_passreset').on('click', function () {
		$('.scform_cont').hide();
		$('.sc_result').show();
		$('.sc_result').val('[PASSWORDRESET]');
		return false;
	});
	$("#product_assoc_SA").click(function () {
		$('.ms_post_meta_checkbox').attr('checked', this.checked);
	});
	$(".ms_post_meta_checkbox").click(function () {
		if ($(".ms_post_meta_checkbox").length == $(".ms_post_meta_checkbox:checked").length) {
			$('#product_assoc_SA').attr("checked", "checked");
		} else {
			$("#product_assoc_SA").removeAttr("checked");
		}
	});
	jQuery('#mslitemetaModal').on('show.bs.modal', function (event) {
		jQuery('.msmodal-backdrop').removeClass('d-none').addClass('show');
	})
	jQuery('#mslitemetaModal').on('hide.bs.modal', function (event) {
		jQuery('.msmodal-backdrop').addClass('d-none').removeClass('show');
	});
})