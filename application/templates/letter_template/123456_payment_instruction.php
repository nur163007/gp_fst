<div class="page bg-blue-100 animsition">
    <div class="page-content container-fluid">    
        <div class="panel">
        
<div class="panel-body container-fluid" style="border: solid 1px #000; height: 842px; width: 595px; font-size: 15px;" id="paymentInstructionLetter" class="">
<p><?php echo date("F").' '.date("d").', ',date("Y"); ?></p>
<p>Ref: <?php echo $_GET['po']; ?>/##LCNO##/<?php echo date("y").''.date("m").'',date("d"); ?>/LCO</p>
Manager
<p>##BANKNAME##<br />##BANKADDRESS##</p>
<p>&nbsp;</p>
<p>Subject: Settlement of LC Payment of ##CUR## ##PAIDAMOUNT##</p>
<p>&nbsp;</p>
<p>Dear Sir,</p>
<p>We request you to settle ##CUR## ##PAIDAMOUNT## (##CUR## ##INWORD## Only) against LC payments as mentioned below:</p>
<p>You are requested to endorse the attached shipping documents, so that we can release the same from the customs and airport cargo authority. The equipment is urgently required by us.</p>
<table border="0">
    <thead>
        <tr>
            <th>L/C No</th>
            <th>Supplier</th>
            <th>Amount (##CUR##)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>##LCNO##</td>
            <td>##CONAME##</td>
            <td>##PAIDAMOUNT##</td>
        </tr>
        <tr>
            <td></td>
            <td>Total:</td>
            <td><b>##PAIDAMOUNT##</b></td>
        </tr>
    </tbody>
</table>
<p>We are also authorizing you to debit BDT ##PAIDAMOUNTBDT## (##CUR## ##PAIDAMOUNT## @ ##XRATE##) and applicable charges from our account # ##ACCNO## maintained with you.</p>
<p>&nbsp;</p>
<p style="margin-top: 50px; margin-bottom: 80px;">Yours faithfully,</p>
<p style="margin: 0;">________________________<span style="color: #fff;">-----------------------------------------------------</span>________________________</p>
<p style="margin: 0;">Authorized Signature<span style="color: #fff;">---------------------------------------------------------------</span>Authorized Signature</p>
<p style="margin: 0;">Grameenphone Ltd.<span style="color: #fff;">-----------------------------------------------------------------</span>Grameenphone Ltd.</p>

<p style="margin-top: 100px;">___________________________________________________________________________________</p>
<p style="margin: 0; font-size: 10px;">Grameenphone Ltd.<span style="color: #fff;">-------------------------------</span>Telephone +(8802) 988 2990<span style="color: #fff;">-------------------------</span>Postal address:<span style="color: #fff;">------------------------------------</span>Office:</p>
<p style="margin: 0; font-size: 10px;">Finance Division<span style="color: #fff;">----------------------------------</span>Telefax<span style="color: #fff;">-----</span>+(8802) 881 9271<span style="color: #fff;">-------------------------</span>GPHOUSE<span style="color: #fff;">---------------------------------------</span>GPHOUSE</p>
<p style="margin: 0; font-size: 10px;"><span style="color: #fff;">-------------------------------------------------------------------------------------------------------------------</span> Bashudhara, Baridhara,<span style="color: #fff;">-------------------------</span>Bashudhara, Baridhara,</p>
<p style="margin: 0; font-size: 10px;"><span style="color: #fff;">-------------------------------------------------------------------------------------------------------------------</span> Dhaka-1229.<span style="color: #fff;">-------------------------------------</span> Dhaka-1229.</p>
</div>

        </div>
    </div>
</div>
<!-- End Page -->
