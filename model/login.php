<?php

class login {

    private $recaptcha_sitekey = "6LezDForAAAAAGO3uUT5K_CiAFXSGgMh0L5_hcfZ";
    private $recaptcha_secret = '6LezDForAAAAAHC8I1vcR4GzEi_GUjJee-dbZtDQ';

    public function get_recaptcha_secret() {
        return $this->recaptcha_secret;
    }

    public function get_recaptcha_sitekey() {
        return $this->recaptcha_sitekey;
    }

}
?>