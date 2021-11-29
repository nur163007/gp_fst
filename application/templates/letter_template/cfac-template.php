<?php
$html = '
		<style>
			table {
				margin-bottom: 25pt;
			}
		</style>
		<table style="width: 100%; border-collapse: collapse;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle; text-align: right">
					<img src="../../assets/images/gp-logo.jpg" alt="" style="max-width: 100%">
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<h2 style="font-weight: 700; margin: 20px 0px 0px 0px; font-size: 18px; text-align: center; color: #0000FF;">GP Project</h2>
		<h2 style="font-weight: 700; margin: 0px 0px 0px 15px;   font-size: 18px; text-align: center; color: #0000FF;">' . $ci_info['cacFacText'] . '</h2>
		<table style="width: 100%; border-collapse: collapse;" cellpadding="6" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Supplier :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['suppName'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">PO Number :</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $suffLessPoNo . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Description:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['podesc'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C No:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $cfacData['lcNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $lc_info['lcvalue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C Beneficiary:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['suppName'] . '</td>
				<!-- <td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $lc_info['lcbeneficiary'] . '</td>-->
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $cfacData['ciNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">AWB/ BL Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $lc_info['blNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $cfacData['ciValue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $ci_info['cacFacText'] . ' Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' '. $cfacData['partValue'] .'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">' . nl2br($returnedLetterBody) . '</td>
				<td style="width: 10%;"></td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">' . $tac_info['currencyName'] . ' '.$cfacData['partValue'].' (' . $tac_info['currencyName'] . ' '.$valueOfDocinWord.')</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">This represents '.$ci_info['percentage'].'% of the Commercial Invoice value of the Finally Accepted Equipment.</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 50px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;"><br/><br/><br/><br/>Authorized Signature</td>
				<td style="width: 25%;">
				    <img src="../../assets/images/'.$approverSign.'.png" alt="" style="max-width: 100%">
				   : ----------------------------------
				   </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 55%;"> </td>
				<td style="width: 25%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Name</td>
				<td style="width: 25%;">: <b> '.$finalAppName.'</b></td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Designation</td>
				<td style="width: 25%; line-height: 1.4;">
					: '.$finalAppDesignation.'<br/>
					   Global Sourcing<br/>
					   Grameenphone Ltd.
				</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 40%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 25%;">Date</td>
				<td style="width: 25%;">: ' . date("F j, Y",strtotime($cfacData['cfacIssueDate'])) . '</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>';