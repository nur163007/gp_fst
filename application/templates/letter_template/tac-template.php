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
		<h2 style="font-weight: 700; margin: 0px 0px 0px 15px;   font-size: 18px; text-align: center; color: #0000FF;">Technical Acceptance Certificate</h2>
		
		<table style="width: 100%; border-collapse: collapse; margin-bottom: 15pt;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle">
				<h2 style="font-weight: 700; margin: 15px 0px 0px 0px; font-size: 14px; color: #0000FF;">Technical Information:</h2>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		<table style="width: 100%; border-collapse: collapse; float: right" cellpadding="6">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">TAC Issue Date:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">'.date('F j, Y', strtotime($tacData['issueDate'])).'</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Project Name:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['podesc'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%; border-collapse: collapse;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%; vertical-align: middle">
					<h2 style="font-weight: 700; margin: 15px 0px 0px 0px; font-size: 14px; color: #0000FF;">Commercial Information:</h2>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
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
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">L/C No:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tacData['lcNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Number:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tacData['ciNo'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">Commercial Invoice Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' ' . $tacData['ciValue'] . '</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $ci_info['cacFacText'] . ' Value:</td>
				<td style="width: 40%; border: 1px solid #666; vertical-align: middle">' . $tac_info['currencyName'] . ' '. $tacData['partValue'] .'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;">'.nl2br($tacData['letterBody']).'</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<table style="width: 100%;">
			<!--<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"><b>TAC effective from: </b></td>
				<td style="width: 10%;"> </td>
			</tr>-->
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;">&nbsp;</div>
		
		<!--
		* Signature area of supplier and GP related person has been removed.
		* Instructed by Tanvir Bhai(GP)
		* Edited by: Hasan Masud
		* Edit Date: 23-02-2019
		-->
		<!--<table style="width: 100%;">
			<tr>
				<td style="width: 55%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Project Manager (Supplier)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 80%;"> </td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>
		
		<div style="width: 100%; height: 25px;"> </div>
		
		<table style="width: 100%;" border="0">
			<tr>
				<td style="width: 10%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Project Owner (GP)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
				<td style="width: 35%; border-top: 1px solid #666; vertical-align: middle">
				Head of PMO (Supplier)<br/>
				Name:<br/>
				ID:<br/>
				</td>
				<td style="width: 10%;"> </td>
			</tr>
		</table>-->';