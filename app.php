<?php
require 'vendor/autoload.php';
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;

if ( !isset( $_GET[ 'content' ] ) ) {
    exit();
}
$qrCode = new QrCode( $_GET[ 'content' ] );
$qrCode->setSize ( 300 );
$qrCode->setWriterByName ( 'png' );
$qrCode->setMargin ( 10 );
$qrCode->setEncoding ( 'UTF-8' );
$qrCode->setErrorCorrectionLevel ( ErrorCorrectionLevel::HIGH );
$qrCode->setForegroundColor ( [
    'r' => 0 ,
    'g' => 0 ,
    'b' => 0
] );
$qrCode->setBackgroundColor ( [
    'r' => 255 ,
    'g' => 255 ,
    'b' => 255
] );
/**
 * logo
 */
if ( isset( $_GET[ 'logo' ] ) ) {
    try {
        $client = new Client();
        $tmpfname = tempnam ( sys_get_temp_dir () , "FOO" );
        $response = $client->get ( $_GET[ 'logo' ] , [
            'save_to' => $tmpfname
        ] );
        $qrCode->setLogoPath ( $tmpfname );
        if ( isset( $_GET[ 'logosize' ] ) && intval ( $_GET[ 'logosize' ] ) > 0 ) {
            $qrCode->setLogoWidth ( intval ( $_GET[ 'logosize' ] ) );
        }
    } catch ( \Exception $e ) {
    }
}
$qrCode->setValidateResult ( true );
header ( 'Content-Type: ' . $qrCode->getContentType () );
echo $qrCode->writeString ();
unlink ( $tmpfname );
$qrCode->writeFile ( __DIR__ . '/qrcode.png' );