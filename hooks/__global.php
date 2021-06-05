
<?php /* Inserted by Audit Log for AppGini on 2021-02-16 03:42:06 */ ?>
		<?php include('audit/scripts.php');?>
<?php /* End of Audit Log for AppGini code */ ?>

<?php
	// For help on using hooks, please refer to https://bigprof.com/appgini/help/working-with-generated-web-database-application/hooks

	function login_ok($memberInfo, &$args) {

		return '';
	}

	function login_failed($attempt, &$args) {

	}

	function member_activity($memberInfo, $activity, &$args) {
		switch($activity) {
			case 'pending':
				break;

			case 'automatic':
				break;

			case 'profile':
				break;

			case 'password':
				break;

		}
	}

	function sendmail_handler(&$pm) {

	}
