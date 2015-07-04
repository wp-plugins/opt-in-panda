<?php 
/**
 * License page is a place where a user can check updated and manage the license.
 */
class OPanda_LicenseManagerPage extends OnpLicensing325_LicenseManagerPage  {
 
    public $purchasePrice = '$22';
    
    public function configure() {
                $this->purchasePrice = '$22';
            

        

            $this->menuPostType = OPANDA_POST_TYPE;
        

    }
}

FactoryPages321::register($optinpanda, 'OPanda_LicenseManagerPage');
 