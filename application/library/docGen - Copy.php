<?php
/**
 * Created by PhpStorm.
 * User: aaqa
 * Date: 10/9/2016
 * Time: 2:38 AM
 */

header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=document_name.doc");

echo "<html xmlns:o='urn:schemas-microsoft-com:office:office' 
    xmlns:w='urn:schemas-microsoft-com:office:word'
    xmlns='http://www.w3.org/TR/REC-html40'>
    <head><title>Time</title>";

echo "<!--[if gte mso 9]><xml><w:WordDocument><w:View>Print</w:View><w:Zoom>90</w:Zoom><w:DoNotOptimizeForBrowser/>
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
        mso-footer-margin:.5in;
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
<w:DoNotOptimizeForBrowser/>
</w:WordDocument>
</xml>";

echo "</head>";

echo "<body lang=EN-US style='tab-interval:.5in'>
        <div class=Section1>";

//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
//echo "<body>";

echo "<div class=\"Section1\">
    <table class=MsoNormalTable border=0 cellspacing=3 cellpadding=0 width=\"100%\" style='width:100.0%;mso-cellspacing:1.5pt;mso-yfti-tbllook:1184;mso-padding-alt: 0in 5.4pt 0in 5.4pt'>
     <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes'>
      <td style='padding:.75pt .75pt .75pt .75pt'>
      <p class=MsoNormal><span style='font-size:10.0pt;mso-fareast-font-family:Telenor'>Date: ##LETTERDATE##<o:p></o:p><br>
      <span style=\"font-size:8.0pt;mso-fareast-font-family:Telenor;\">Ref: MAH\CF & T\FPO\2016\67\21\08\022</span></span>
      </p>
      </td>
     </tr>
     <tr style='mso-yfti-irow:1'>
      <td style='padding:.75pt .75pt .75pt .75pt'>
      <p class=MsoNormal><span style='mso-fareast-font-family:Telenor'>&nbsp;<o:p></o:p></span></p>
      </td>
     </tr>
     <tr style='mso-yfti-irow:2;mso-yfti-lastrow:yes'>  
      <td style='padding:.75pt .75pt .75pt .75pt'>
      <p class=MsoNormal><span class=SpellE><span style='font-size:10.0pt;
      mso-fareast-font-family:Telenor'>The Manager</span></span><span style='font-size:10.0pt;mso-fareast-font-family:Telenor'><br>
      Southeast Bank Ltd.<o:p></o:p></span></p>
      <p class=MsoNormal><span class=SpellE><span style='font-size:10.0pt;mso-fareast-font-family:Telenor'>Bashundhara Branch</span></span><span
      style='font-size:10.0pt;mso-fareast-font-family:Telenor'><br>
      Dhaka-1229<o:p></o:p></span></p>
      </td>
     </tr>
    </table>
    <p><o:p>&nbsp;</o:p></p>

    <p style='font-size:10.0pt;mso-fareast-font-family:Telenor;line-height:120%;'><b>Re: Issue of a pay-order in favour of \"##BENEFECIARY## A/C: Grameenphone Ltd\" from your Bashundhara Branch.<o:p></o:p></b></p>
    
    <p><o:p>&nbsp;</o:p></p>
    
    <p style='font-size:10.0pt;mso-fareast-font-family:Telenor;line-height:120%;'>Dear Sir,</p>
    
    <p style='font-size:10.0pt;mso-fareast-font-family:Telenor;text-align:justify;line-height:120%;'>We are requesting you, please arrange to issue a Pay Order from your Bashundhara Branch, Dhaka in favour of <b>\"##BENEFECIARY## A/C: Grameenphone Ltd\"</b> for an amount of Tk. <b>##CDAMOUNT##</b> (Taka ##AMOUNTINWORD## Only). This pay-order is required for payment of taxes and duties for clearing of our telecommunication equipment from ##PORT##.</p>
    
    <p style='font-size:10.0pt;mso-fareast-font-family:Telenor;text-align:justify;line-height:120%;'>In this regard, we hereby authorise you to debit our SND account no. 13100000020 with your bank for the transaction. ##CONTACT##, from ##CNFAGENT## are authorised to collect the Pay Order from your ##PORTBRANCH## Branch.</p>
    
    <p style='font-size:10.0pt;mso-fareast-font-family:Telenor;text-align:justify;line-height:120%;'>Please arrange the transaction with an advice to us on urgent basis.</p>
    
    <p style='margin-bottom:12.0pt;font-size:10.0pt;mso-fareast-font-family:Telenor;line-height:120%;'>Thanking you.<br style='mso-special-character:line-break'>
    
    <p style='margin-bottom:12.0pt;font-size:10.0pt;mso-fareast-font-family:Telenor;line-height:120%;'>Yours faithfully</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <table class=MsoNormalTable border=0 cellspacing=3 cellpadding=0 width=\"100%\"
     style='width:100.0%;mso-cellspacing:1.5pt;mso-yfti-tbllook:1184;mso-padding-alt:
     0in 5.4pt 0in 5.4pt'>
     <tr style='mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes'>
      <td width=\"35%\" valign=top style='width:35%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
      <p class=MsoNormal><span style='font-size:10.0pt;mso-fareast-font-family:Telenor'><b>Authorized Signature</b><br>
      Grameenphone Ltd.<o:p></o:p></span></p>
      </td>
      <td width=\"30%\" valign=top style='width:35%;padding:.75pt .75pt .75pt .75pt'>
      <p class=MsoNormal><span style='mso-fareast-font-family:Telenor'>&nbsp;<o:p></o:p></span></p>
      </td>
      <td width=\"35%\" valign=top style='width:35%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
      <p class=MsoNormal><span style='font-size:10.0pt;mso-fareast-font-family:Telenor'><b>Authorized Signature</b><br>
      Grameenphone Ltd.<o:p></o:p></span></p>
      </td>
     </tr>
    </table>
    
    <table id='hrdftrtbl' border='0' cellspacing='0' cellpadding='0'>
        <tr>
            <td>
                <div style='mso-element:footer' id=f1>
                    <span style='position:relative;z-index:-1'> 
                    <!-- FOOTER-tags -->
                    
                    <table class=MsoNormalTable border=0 cellspacing=3 cellpadding=0 width=\"100%\"
                        style='width:100.0%;mso-cellspacing:1.5pt;mso-yfti-tbllook:1184;mso-padding-alt:0in 5.4pt 0in 5.4pt'>
                        <tr>
                            <td width=\"20%\" valign=top style='width:20%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
                                <span style='font-size:6.0pt;mso-fareast-font-family:Telenor'>
                                    Grameenphone Ltd.<br>
                                    Finance Division
                                </span>
                            </td>
                            <td width=\"30%\" valign=top style='width:30%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
                                <span style='font-size:6.0pt;mso-fareast-font-family:Telenor'>
                                    Telephone: +(8802) 988 2990<br>
                                    Telefax: +(8802) 9882970
                                </span>
                            </td>
                            <td width=\"30%\" valign=top style='width:30%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
                                <span style='font-size:6.0pt;mso-fareast-font-family:Telenor'>
                                    Postal address:<br>
                                    GP house<br>
                                    Bashundhara<br>
                                    Baridhara, Dhaka-1229
                                </span>
                            </td>
                            <td width=\"20%\" valign=top style='width:20%;padding:.75pt .75pt .75pt .75pt;border-top: solid 1px #000;'>
                                <span style='font-size:6.0pt;mso-fareast-font-family:Telenor'>
                                    Office:<br>
                                    GP House<br>
                                    Bashundhara<br>
                                    Baridhara, Dhaka-1229<br><br>
                                </span>
                            </td>
                        </tr>
                    </table>
                    
                    </span>
                </div>
            </td>
        </tr>
    </table>

</div>";

echo "</body></html>";
?>