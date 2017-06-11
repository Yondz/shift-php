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

    // Root API
    const START                             = "/api";

    // Accounts
    const ACCOUNTS                          = self::START . "/accounts";
    const ACCOUNTS_GET                      = self::ACCOUNTS;
    const ACCOUNTS_GET_BALANCE              = self::ACCOUNTS . "/getBalance";
    const ACCOUNTS_OPEN                     = self::ACCOUNTS . "/open";
    const ACCOUNTS_GET_PUBLICKEY            = self::ACCOUNTS . "/getPublicKey";
    const ACCOUNTS_GEN_PUBLICKEY            = self::ACCOUNTS . "/generatePublicKey";
    const ACCOUNTS_GET_DELEGATES            = self::ACCOUNTS . "/delegates";
    const ACCOUNTS_PUT_DELEGATES            = self::ACCOUNTS . "/delegates";

    // Loader
    const LOADER                            = self::START . "/loader";
    const LOADER_GET_STATUS                 = self::LOADER . "/status";
    const LOADER_GET_SYNC                   = self::LOADER . "/status/sync";

    // Transactions
    const TRANSACTIONS                      = self::START . "/transactions";
    const TRANSACTIONS_LIST                 = self::TRANSACTIONS;
    const TRANSACTIONS_SEND                 = self::TRANSACTIONS;
    const TRANSACTIONS_GET                  = self::TRANSACTIONS . "/get";
    const TRANSACTIONS_UNCONFIRMED_LIST     = self::TRANSACTIONS . "/unconfirmed";
    const TRANSACTIONS_UNCONFIRMED_GET      = self::TRANSACTIONS . "/unconfirmed/get";

    // Peers
    const PEERS                             = self::START . "/peers";
    const PEERS_LIST                        = self::PEERS;
    const PEERS_GET                         = self::PEERS . "/get";
    const PEERS_GET_VERSION                 = self::PEERS . "/get/version";

    // Blocks
    const BLOCKS                            = self::START . "/blocks";
    const BLOCKS_GET_ONE                    = self::BLOCKS . "/get";
    const BLOCKS_GET_FILTER                 = self::BLOCKS;
    const BLOCKS_GET_FEE                    = self::BLOCKS . "/getFee";
    const BLOCKS_GET_FEES                   = self::BLOCKS . "/getFees";
    const BLOCKS_GET_REWARD                 = self::BLOCKS . "/getReward";
    const BLOCKS_GET_SUPPLY                 = self::BLOCKS . "/getSupply";
    const BLOCKS_GET_HEIGHT                 = self::BLOCKS . "/getHeight";
    const BLOCKS_GET_STATUS                 = self::BLOCKS . "/getStatus";
    const BLOCKS_GET_NETHASH                = self::BLOCKS . "/getNethash";
    const BLOCKS_GET_MILESTONE              = self::BLOCKS . "/getMilestone";

    // Signatures
    const SIGNATURES                        = self::START . "/signatures";
    const SIGNATURES_GET                    = self::SIGNATURES . "/get";
    const SIGNATURES_ADD_SECOND             = self::SIGNATURES;

    // Delegates
    const DELEGATES                         = self::START . "/delegates";
    const DELEGATES_CREATE                  = self::DELEGATES;
    const DELEGATES_LIST                    = self::DELEGATES;
    const DELEGATES_GET                     = self::DELEGATES . "/get";
    const DELEGATES_COUNT                   = self::DELEGATES . "/count";
    const DELEGATES_GET_VOTES               = self::ACCOUNTS_GET_DELEGATES;
    const DELEGATES_GET_VOTERS              = self::DELEGATES . "/voters";
    const DELEGATES_ENABLE_FORGING          = self::DELEGATES . "/forging/enable";
    const DELEGATES_DISABLE_FORGING         = self::DELEGATES . "/forging/disable";
    const DELEGATES_GET_FORGED              = self::DELEGATES . "/forging/getForgedByAccount";

    // Apps
    const APPS                              = self::START . "/dapps";
    const APPS_REGISTER                     = self::APPS;
    const APPS_GET_REGISTERED               = self::APPS;
    const APPS_GET                          = self::APPS;
    const APPS_SEARCH                       = self::APPS . "/search";
    const APPS_INSTALL                      = self::APPS . "/install";
    const APPS_INSTALLED                    = self::APPS . "/installed";
    const APPS_INSTALLED_IDS                = self::APPS . "/installedIds";
    const APPS_UNINSTALL                    = self::APPS . "/uninstall";
    const APPS_LAUNCH                       = self::APPS . "/launch";
    const APPS_INSTALLING                   = self::APPS . "/installing";
    const APPS_UNINSTALLING                 = self::APPS . "/uninstalling";
    const APPS_LAUNCHED                     = self::APPS . "/launched";
    const APPS_CATEGORIES                   = self::APPS . "/categories";
    const APPS_STOP                         = self::APPS . "/stop";

    // Multi-signature
    const MULTI_SIGNATURE                   = self::START . "/multisignatures";
    const MULTI_SIGNATURE_PENDING           = self::MULTI_SIGNATURE . "/pending";
    const MULTI_SIGNATURE_CREATE_ACCOUNT    = self::MULTI_SIGNATURE;
    const MULTI_SIGNATURE_SIGN              = self::MULTI_SIGNATURE . "/sign";
    const MULTI_SIGNATURE_ACCOUNTS          = self::MULTI_SIGNATURE . "/accounts";

    /**
     * ShiftAPI constructor
     */
    public function __construct($host){
        $this->host =  $host;
    }

    // =====================================================================================================
    //  ACCOUNTS
    // =====================================================================================================

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


    // =====================================================================================================
    //  LOADER
    // =====================================================================================================

    /**
     * Returns the status of the blockchain
     * @return mixed
     * @throws CommandException
     */
    public function getLoadingStatus(){
        $command = new Command($this->host, self::LOADER_GET_STATUS,"GET");
        return $command->execute();
    }

    /**
     * Get the synchronization status of the client.
     * @return mixed
     * @throws CommandException
     */
    public function getSyncStatus(){
        $command = new Command($this->host, self::LOADER_GET_SYNC,"GET");
        return $command->execute();
    }

    // =====================================================================================================
    //  TRANSACTIONS
    // =====================================================================================================

    // =====================================================================================================
    //  PEERS
    // =====================================================================================================

    // =====================================================================================================
    //  BLOCKS
    // =====================================================================================================

    // =====================================================================================================
    //  SIGNATURES
    // =====================================================================================================

    // =====================================================================================================
    //  DELEGATES
    // =====================================================================================================

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

    // =====================================================================================================
    //  APPS
    // =====================================================================================================

    // =====================================================================================================
    //  MULTI-SIGNATURE
    // =====================================================================================================

}