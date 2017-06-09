<?php

namespace ShiftPHP\Classes;

/**
 * Shift API
 * @author Yondz
 * @package ShiftPHP
 */
class ShiftAPI {

    /**
     * API host
     * @var string $host
     */
    protected $host;

    /**
     * API Commands constants
     */
    const START                     = "/api";
    const ACCOUNTS                  = self::START . "/accounts";
    const ACCOUNTS_GET_BALANCE      = self::ACCOUNTS . "/getBalance";
    const ACCOUNTS_OPEN             = self::ACCOUNTS . "/open";
    const ACCOUNTS_GET_PUBLICKEY    = self::ACCOUNTS . "/getPublicKey";
    const ACCOUNTS_GEN_PUBLICKEY    = self::ACCOUNTS . "/generatePublicKey";
    const ACCOUNTS_GET_DELEGATES    = self::ACCOUNTS . "/delegates";
    const ACCOUNTS_PUT_DELEGATES    = self::ACCOUNTS_GET_DELEGATES;
    const DELEGATES                 = self::START . "/delegates";
    const DELEGATES_GET_VOTERS      = self::DELEGATES . "/voters";

    /**
     * ShiftAPI constructor. SSL enabled by default
     */
    public function __construct($host){
        $this->host =  $host;
    }

    /**
     * Returns account information of an address.
     * @param string $address
     * @return mixed
     * @throws CommandException
     */
    public function getAccount($address){
        $command = new Command($this->host, self::ACCOUNTS,"GET");
        $command->setParam("address", $address);
        return $command->execute();
    }

    /**
     * Request information about an account.
     * @param string $secret
     * @return mixed
     * @throws CommandException
     */
    public function openAccount($secret){
        $command = new Command($this->host, self::ACCOUNTS_OPEN,"POST");
        $command->setParam("secret", $secret);
        return $command->execute();
    }

    /**
     * Returns the balance of an address, if $confirmed set to false, it'll return the unconfirmedBalance
     * @param string $address
     * @param boolean $confirmed
     * @return float
     * @throws CommandException
     */
    public function getBalance($address, $confirmed = true){

        $command = new Command($this->host, self::ACCOUNTS_GET_BALANCE, "GET");
        $command->setParam("address", $address);
        $command->execute();
        $balance = $command->getData( $confirmed ? "balance" : "unconfirmedBalance");

        // Converting to float
        $right = substr($balance, -8);
        $left = str_replace($right, "",$balance);
        $balance = floatval($left.".".$right);
        return $balance;
    }

    /**
     * Get the public key of an account. If the account does not exist the API call will return an error.
     * @param string $address
     * @return mixed
     * @throws CommandException
     */
    public function getPublicKey($address){
        $command = new Command($this->host, self::ACCOUNTS_GET_PUBLICKEY,"GET");
        $command->setParam("address", $address);
        $command->execute();
        return $command->getData("publicKey");
    }

    /**
     * Returns the public key of the provided secret key.
     * @param string $secret
     * @return mixed
     * @throws CommandException
     */
    public function generatePublicKey($secret){
        $command = new Command($this->host, self::ACCOUNTS_GEN_PUBLICKEY,"POST");
        $command->setParam("secret", $secret);
        return $command->execute();
    }

    /**
     * Returns delegate accounts by address.
     * @param string $address
     * @return mixed
     * @throws CommandException
     */
    public function getDelegates($address){
        $command = new Command($this->host, self::ACCOUNTS_GET_DELEGATES,"GET");
        $command->setParam("address", $address);
        $command->execute();
        return $command->getData("delegates");
    }

    /**
     * Vote for the selected delegates. Maximum of 33 delegates at once.
     * @param string $secret first signature
     * @param array $upVote  list of public keys to upvote
     * @param array $downVote list of public keys to downvote
     * @param string $secondSecret second signature (optionnal, set to null if not needed)
     * @return mixed
     * @throws CommandException
     */
    public function putDelegates($secret, array $upVote, array $downVote, $secondSecret = null){
        $command = new Command($this->host, self::ACCOUNTS_PUT_DELEGATES,"PUT");
        $command->setParam("secret", $secret);
        if($secondSecret !== null){
            $command->setParam("secondSecret", $secondSecret);
        }

        // Building vote array
        $delegates = [];
        $count = 0;
        foreach ($upVote as $delegate){
            $delegates[] = "+".$delegate;
            $count++;
        }
        foreach ($downVote as $delegate){
            $delegates[] = "-".$delegate;
            $count++;
        }

        // Some checks
        if($count > 33){
            throw new CommandException("Max. number of delegates per voting round exceeded (".$count.") .. Aborting.");
        } else if($count == 0){
            throw new CommandException("No delegates specified .. Aborting.");
        }

        $command->execute();
        return $command->getData("transaction");
    }



    /**
     * Get voters of delegate.
     * @param string $publicKey
     * @return mixed
     * @throws CommandException
     */
    public function getVoters($publicKey){
        $command = new Command($this->host, self::DELEGATES_GET_VOTERS,"GET");
        $command->setParam("publicKey", $publicKey);
        $command->execute();
        return $command->getData("accounts");
    }



}