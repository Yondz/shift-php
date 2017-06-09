<?php

namespace ShiftWalletMonitor\Classes;



/**
 * Shift API
 */
class ShiftAPI {

    /**
     * Returns the balance of an address, if $confirmed set to false, it'll return the unconfirmedBalance
     * @param string $address
     * @param boolean $confirmed
     * @return float
     */
    public function getBalance($address, $confirmed = true){

        // Creating an API object
        $command = new Command("/api/accounts/getBalance", "GET");
        $command->setParam("address", $address);
        if($command->execute()){

            $result = $command->getData();

            if($confirmed){
                $balance = $result["balance"];
            } else {
                $balance = $result["unconfirmedBalance"];
            }

            $right = substr($balance, -8);
            $left = str_replace($right, "",$balance);
            $balance = floatval($left.".".$right);
            return $balance;
        }
        return 0;
    }

    /**
     * Request information about an account. 
     * @param $secret
     * @return mixed
     */
    public function openAccount($secret){
        $command = new Command("/api/accounts/open","POST");
        $command->setParam("secret", $secret);
        var_dump($command->getParams());
        $command->execute();
        return $command->getData();
    }

    /**
     * Get the public key of an account. If the account does not exist the API call will return an error.
     * @param $address
     */
    public function getPublicKey($address){

        $command = new Command("/api/accounts/getPublicKey","GET");
        $command->setParam("address", $address);
        if($command->execute()){
            return $command->getData("publicKey");
        }
        return 0;
        
    }


}