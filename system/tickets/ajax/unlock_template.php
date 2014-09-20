<?php
REQUIRE("../../../frame/engine.php");ENGINE::START();
$USER = USER::VERIFY(0,TRUE);
ECHO <<<TEMPLATE
<div style='width:100%;font-family: Calibri, Candara, Segoe, "Segoe UI", Optima, Arial, sans-serif;font-size:18px;'>
    <p style='font-weight:bold;width:100%;text-align:center;text-decoration:underline;'>AUTHORIZATION TO UNLOCK MOBILE PHONE</p>

    <p>CPR OF ATLANTA (hereinafter, &#34;we&#34; or &#34;us&#34;)</p>
    <p>CUSTOMER NAME (printed): _____________________ (hereinafter, &#34;you&#34;)</p>

    <p>Effective October 28, 2012, regulations enacted under the Digital Millennium Copyright Act prohibit unlocking mobile phones for use on other wireless networks unless certain criteria are satisfied.</p>
    <p>You have requested that we unlock your phone on your behalf. To &#34;unlock&#34; means to alter a wireless telephone&#39;s computer software, including its firmware, in order to connect to a different wireless telecommunications network.</p>
    <p>You represent and warrant that the operator of the wireless telecommunications network (e.g., AT&amp;T, Verizon, T-Mobile, etc.) from which you acquired your phone has authorized you to unlock the phone for the purpose of using it on a different wireless telecommunications network.</p>
    <p>You agree to indemnify, defend, and hold harmless us, our franchisor, CPR-Cell Phone Repair Franchise Systems, Inc., our respective affiliates, and our and their respective owners, directors, officers, employees, agents, successors, and assignees (the &#34;Indemnified Parties&#34;) against, and to reimburse any one or more of the Indemnified Parties for, all claims, obligations, and damages directly or indirectly arising out of a breach of your representations and warranties above. For purposes of this indemnification, &#34;claims&#34; include all obligations, damages (actual, consequential, or otherwise), and costs that any Indemnified Party reasonably incurs in defending any claim against it, including, without limitation, reasonable attorneys&#39; fees. Each Indemnified Party may defend any claim against it at your expense and agree to settlement or take any other remedial, corrective, or other actions. </p>

	<p>Customer Address:_________________________________________</p>
    <p>Phone Number:____________________________________________</p>
    <p>E-mail Address:____________________________________________</p>
    <p>Customer Signature:________________________________________</p>

    <p style='font-weight:bold;width:100%;text-align:center;text-decoration:underline;'>For store use only</p>
    <p>&#9744; ID verified (ID/Driver&#39;s License No. ___________________)</p>
    <p>Phone Model: </p>
    <p>Serial Number: </p>
</div>
TEMPLATE;
?>