<?php

/* For licensing terms, see /dokeos_license.txt */

/**
==============================================================================
*   Export html to pdf
* 	@Author Juan Carlos Raña <herodoto@telefonica.net>
*
*	@package dokeos.wiki
==============================================================================
*/

// including the global dokeos file
include("../inc/global.inc.php");

// including additional library scripts
require('../inc/lib/html2pdf/html2pdf.class.php');

// action control
api_block_anonymous_users();

$contentPDF=stripslashes(api_html_entity_decode($_POST['contentPDF'], ENT_QUOTES, $charset));
$titlePDF=stripslashes(api_html_entity_decode($_POST['titlePDF'], ENT_QUOTES, $charset));

ob_start();//activate Output -Buffer
///////////////////////
?>
<page backtop="10mm" backbottom="10mm" footer="page">
      <page_header>
           <?php echo $titlePDF.'<br/><hr/>'?>
      </page_header>
      <page_footer>
            <?php echo '<hr/>'; ?>
      </page_footer>
 </page>

 <?php
/////////////////////
echo $contentPDF;
$htmlbuffer=ob_get_contents();// Store Output-Buffer in one variable
ob_end_clean();// delete Output-Buffer

/////bridge to  dokeos lang
	@ $langhtml2pdf = api_get_language_isocode($language_interface);

	// Some code translations are needed.
	$langhtml2pdf = strtolower(str_replace('_', '-', $langhtml2pdf));
	if (empty ($langhtml2pdf))
	{
		$langhtml2pdf = 'en';
	}
	switch ($langhtml2pdf)
	{
		case 'uk':
			$langhtml2pdf = 'ukr';
			break;
		case 'pt':
			$langhtml2pdf = 'pt_pt';
			break;
		case 'pt-br':
			$langhtml2pdf = 'pt_br';
			break;
		// Code here other noticed exceptions.
	}

	// Checking for availability of a corresponding language file.
	if (!file_exists(api_get_path(SYS_PATH).'main/inc/lib/html2pdf/langues/'.$langhtml2pdf.'.txt'))
	{
		// If there was no language file, use the english one.
		$langhtml2pdf = 'en';
	}

////

//$script = "
//var rep = app.response('Your name');
//app.alert('Hello '+rep);
//";

$html2pdf = new HTML2PDF('P','A4',$langhtml2pdf, array(30,10,30,10));//array (margin left, margin top, margin right, margin bottom)
//$html2pdf->pdf->IncludeJS($script);
//$html2pdf->pdf->IncludeJS("print(true);");
//$html2pdf->pdf->IncludeJS("app.alert('Generated by Dokeos to PDF');");
//$html2pdf->pdf->SetProtection(array('print'), 'guest');//add a password sample: guest
$html2pdf->pdf->SetAuthor('Wiki Dokeos');
$html2pdf->pdf->SetTitle($titlePDF);
$html2pdf->pdf->SetSubject('Exported from Dokeos Wiki');
$html2pdf->pdf->SetKeywords('Dokeos Wiki');
//$html2pdf->WriteHTML(utf8_decode($htmlbuffer));
$html2pdf->WriteHTML($htmlbuffer);
$html2pdf->Output($titlePDF.'.pdf', 'D');
?>