<?php

error_reporting(E_ALL);

ini_set('memory_limit', '512M');

require_once 'encrypt.php';
require_once 'decrypt.php';

	if(isset($_REQUEST['data']) && trim($_REQUEST['data'])) {
		 if(isset($_REQUEST['encrypt'])) {
			  $crypto = new Encrypt($_REQUEST['data'], $_REQUEST['level']);
			  $data = $crypto->getEncrypted();
		 } elseif(isset($_REQUEST['decrypt'])) {
			  $decrypto = new Decrypt($_REQUEST['data'], $_REQUEST['level']);
			  $data = $decrypto->getDecrypted();
		 }
	}

?>
<html>
    <head>
        <title>Optimus Encrypt/Decrypt</title>
    </head>
    <body>
       <div style="width:960px;margin:0 auto;">
            <form method="post" action="">
                <textarea rows="20" cols="20" style="width: 100%;height: 300px;" name="data"><?php echo $data; ?></textarea>
                <label>Lvl<input type="text" name="level" value="1" style="width:30px" /></label>
                <input type="submit" name="encrypt" value="Encrypt" />
                <input type="submit" name="decrypt" value="Decrypt" />
            </form>
       </div>
    </body>
</html>