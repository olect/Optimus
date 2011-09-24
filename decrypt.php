<?php

require_once 'digest.php';

class Decrypt
{
	private $encrypted, $decrypted, $skey = UNIQUE_ENCRYPTION_TOKEN;

	public function __construct($encryptedData, $level = 1, $printCLI = 0) {
		$this->encrypted = $encryptedData;
		$this->decrypted = $this->_decrypt($this->decode($encryptedData));

			if($level > 1) {
				for($i = 1; $i < $level; $i++) {
					$this->decrypted = $this->_decrypt($this->decode($this->decrypted));
				}
			}

			if($printCLI) {
				echo $this->decrypted . "\n";
			}

		return;
	}

	private function _decrypt($encryptedData) {
		$decrypted = '';
		$encryptedData = substr($encryptedData,strpos($encryptedData, '-') + 1);
		$encrypted = explode('-', $encryptedData);

			foreach($encrypted as $char) {
				$decrypted .= chr(substr(base64_decode($char), 1, strlen($char)));
			}

		return $decrypted;
	}

	public function getDecrypted()
	{
		return $this->decrypted;
	}

	public function decode($value) {

			if(!$value) {
				return 0;
			}

		$crypttext = $this->safe_b64decode($value); 
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->skey, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}

	public  function safe_b64encode($string) {
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}

	public function safe_b64decode($string) {
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = (strlen($data) % 4);

			if($mod4) {
				$data .= substr('====', $mod4);
			}

		return base64_decode($data);
	}

	public function __destruct() {
		unset($encrypted, $decrypted, $skey);
	}
}

$Decrypt = new Decrypt($_SERVER['argv'][1], (int)$_SERVER['argv'][2], (int)$_SERVER['argv'][3]);

?>