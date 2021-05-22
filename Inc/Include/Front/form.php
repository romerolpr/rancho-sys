<?php 

if(isset($_SESSION["user_login"])):

	include FRONT . 'pages/add.files.php';

else: 

	// Include by params
	if (isset($get["exb"]) && !empty($get["exb"])):

		switch ($get["exb"]):
			case 'lastsheet':
				include FRONT . 'pages/lastsheet.php';
				break;

			case 'consult':
				include FRONT . 'pages/consult.php';
				break;
			
			default:
				include FRONT . 'pages/login.php';
				break;
		endswitch;

	else:

		include FRONT . 'pages/login.php';

	endif;

endif;