<?php global $wpdb; ?>
<div class="qazert">
	<div class="msmodal-backdrop  d-none"></div>
	<div class="modal fade" id="mslitemetaModal" tabindex="-1" role="dialog" aria-labelledby="mslitemetaModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mslitemetaModalLabel">Membersonic Shortcode Generator</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body" id="msshortcode_popup">
					<div class="col-12">
						<div class="row">
							<div class="col-5 text-center">
								<a class="btn btn-sm btn-ms ms-scode-button  sc_buttons" id="sc_freereg" href="#">Free Registration Form</a>
							</div>
							<div class="col-3 text-center">
								<a class="btn btn-sm btn-ms ms-scode-button  sc_buttons" id="sc_loginform" href="#">Login Form</a>
							</div>
							<div class="col-4 text-center">
								<a class="btn btn-sm btn-ms ms-scode-button  sc_buttons" id="sc_passreset" href="#">Password Reset</a>
							</div>
						</div>
					</div>
					<div class="col-12 pt-3 scform_cont" id="sc_freereg_form">
						<div class="row">
							<div class="col-4 text-right pt-2">
								<div class="">
									<?php $sql = "SELECT membership_level_name, membership_level_key  FROM " . WP_MEMBERSONICLITE_MEMBERSHIP_DETAILS;
									$result = $wpdb->get_results($sql); ?>
									<strong>
										<?php echo __('Select Membership', 'membership-site'); ?>
									</strong>
								</div>
							</div>
							<div class="col-8 text-left">
								<?php
								if (!empty($result)) : ?>
									<select name="membership_code_add" class="membership-code-add form-control">
										<option value=""><?php echo __('Select One', 'membership-site'); ?></option>
										<?php
											foreach ($result as $membership_level) : ?>
											<option value="<?php echo $membership_level->membership_level_key; ?>">
												<?php echo $membership_level->membership_level_name; ?>
											</option>
										<?php
											endforeach; ?>
									</select>
								<?php
								else :
									_e('No membership level defined', 'membership-site');
								endif; ?>
							</div>
						</div>
					</div>
					<div class="col-12 row pt-3 ml-1">
						<input type="text" class="sc_result form-control" value="" />
					</div>
					<div class="alert alert-info mt-3">
						<h6 class="text-center">Copy/Paste the code inside content or sidebar</h6>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>