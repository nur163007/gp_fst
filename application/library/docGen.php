<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 10/9/2016
 * Time: 2:38 AM
 */
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=".$_POST['fileName']."");

echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' 
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
    <head><title>Time</title>";

echo "<!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:Zoom>90</w:Zoom><w:DoNotOptimizeForBrowser></w:DoNotOptimizeForBrowser>
    </w:WordDocument></xml><![endif]-->";

echo "<style>
    @page
    {
        mso-page-orientation: portrait;
        size:8.27in 11.69in;
    	margin:1.3in 1.0in 0.86in 1.2in;
        /*size:21cm 29.7cm;    margin:1in .6in .6in .75in;*/
    }
    @page Section1 {
        mso-header-margin:.5in;
        mso-footer-margin:.3in;
        mso-header: h1;
        mso-footer: f1;
        }
    div.Section1 { page:Section1; }
    table#hrdftrtbl
    {
        margin:0in 0in 0in 900in;
        width:1px;
        height:1px;
        overflow:hidden;
    }
    /* Font Definitions */
 @font-face
	{font-family:\"Cambria Math\";
	panose-1:2 4 5 3 5 4 6 3 2 4;
	mso-font-charset:0;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-536870145 1107305727 0 0 415 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:\"\";
	margin:0in;
	margin-bottom:.0001pt;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:Telenor,serif;
	mso-fareast-font-family:Telenor;
	mso-fareast-theme-font:minor-fareast;}
p
	{mso-style-noshow:yes;
	mso-style-priority:99;
	mso-margin-top-alt:auto;
	margin-right:0in;
	mso-margin-bottom-alt:auto;
	margin-left:0in;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:Telenor,serif;
	mso-fareast-font-family:Telenor;
	mso-fareast-theme-font:minor-fareast;}
p.msonormal0, li.msonormal0, div.msonormal0
	{mso-style-name:msonormal;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-unhide:no;
	mso-margin-top-alt:auto;
	margin-right:0in;
	mso-margin-bottom-alt:auto;
	margin-left:0in;
	mso-pagination:widow-orphan;
	font-size:12.0pt;
	font-family:Telenor,serif;
	mso-fareast-font-family:Telenor;
	mso-fareast-theme-font:minor-fareast;}
span.SpellE
	{mso-style-name:\"\";
	mso-spl-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-size:10.0pt;
	mso-ansi-font-size:10.0pt;
	mso-bidi-font-size:10.0pt;}
    p.MsoFooter, li.MsoFooter, div.MsoFooter
    {
        margin:0in;
        margin-bottom:.0001pt;
        mso-pagination:widow-orphan;
        tab-stops:center 3.0in right 6.0in;
        font-size:12.0pt;
    }
</style>
<xml>
<w:WordDocument>
<w:View>Print</w:View>
<w:Zoom>100</w:Zoom>
<w:DoNotOptimizeForBrowser></w:DoNotOptimizeForBrowser>
</w:WordDocument>
</xml>";

echo "</head>";

echo "<body lang=EN-US style='tab-interval:.5in'>
        <div class=Section1>";

echo "<div class=\"Section1\">";
$c = $_POST['letterContent'];
$c = str_ireplace('\"','"', $c);
echo str_ireplace("\'","'", $c);

echo "</div>";

echo "</body></html>";
?>