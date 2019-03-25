<?php

define("MYFTP_SERVER", '37.187.161.104' );
define("MYFTP_USER", 'fguichard' );
define("MYFTP_PASS", 'Hidq46_4' );
define("MYFTP_CWD", 'vps.samlog.com' );

$sInPath = "/var/www/vhosts/samlog.com/backup.samlog.com/";
$aDir = scandir($sInPath);

foreach( $aDir as $sFile ) {
//	echo "$sFile<br>";
	if ( ($sFile!='.') && ($sFile!='..') ) {
		echo "Transfert fichier:$sFile\n";
		transfertFTP($sInPath.$sFile, $sFile);
		unlink( $sInPath.$sFile );

	}
}



function transfertFTP( $inFile, $inDestFile )
{
	global $fpLog;

	$lOK = true;
	$sErrMess = '';

	// Mise en place d'une connexion basique
	$conn_id = ftp_connect(MYFTP_SERVER);
	if ( ! $conn_id ) {
		$lOK = false;
		$sErrMess = 'connexion FTP echoue';
//		echo "ftp_connect NOK\n";
	}
//	else
//		echo "ftp_connect OK\n";

	if ( $lOK ) {
		// Identification avec un nom d'utilisateur et un mot de passe
		if ( ! ftp_login( $conn_id, MYFTP_USER, MYFTP_PASS ) ) {
			$lOK = false;
			$sErrMess = 'login FTP echoue';
//			echo "ftp_login NOK\n";
		}
//		else
//			echo "ftp_login OK\n";
	}

	//echo ftp_pwd( $conn_id ) . "\n";
	//echo "curdir:" . ftp_pwd($conn_id) . "\n";

	if ( $lOK ) {
		if ( ! ftp_chdir($conn_id, MYFTP_CWD) )  {
			$lOK = false;
			$sErrMess = 'chdir FTP echoue';
//			echo "ftp_chdir NOK\n";
		}
//		else
//			echo "ftp_chdir OK\n";
	}

//echo "PWD " . ftp_pwd( $conn_id ) . "\n";


	ftp_pasv($conn_id, true);

	if ( $lOK ) {
		if ( ! ftp_put($conn_id, $inDestFile, $inFile, FTP_BINARY) )  {
			$lOK = false;
			$sErrMess = 'put FTP echoue';
//			echo "ftp_put NOK\n";
		}
//		else
//			echo "ftp_put OK\n";
	}

	// Fermeture du flux FTP
	ftp_close($conn_id);

	if ( !$lOK ) {
		echo sprintf( "Erreur FTP: %s\n", $sErrMess );
	}
}


?>
