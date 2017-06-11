<?php

namespace ShiftPHP\Classes;

/**
 * Shift API
 * Official API documentation : https://www.shiftnrg.org/api-documentation/
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

    /**
     * Converts a string amount with 8 digits on the right to a float value
     * @param $amount
     * @return mixed
     */
    public function convertAmountToFloat($amount){
        $right = substr($amount, -8);
        $left = str_replace($right, "",$amount);
        return floatval($left.".".$right);
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
        return $this->convertAmountToFloat($balance);
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
     * Returns delegate accounts voted by address.
     * @param string $address
     * @return mixed
     * @throws CommandException
     */
    public function getVotedDelegates($address){
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
        $command->setParam("secondSecret", $secondSecret);

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

    /**
     * List of transactions matched by provided parameters.
     * @param string $blockId
     * @param string $senderId
     * @param string $recipientId
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return mixed
     * @throws CommandException
     */
    public function getTransactionsList($blockId = null, $senderId = null, $recipientId = null, $limit = null, $offset = null, $orderBy = null){

        $command = new Command($this->host, self::TRANSACTIONS_LIST,"GET");
        $command->setParam("blockId", $blockId);
        $command->setParam("senderId", $senderId);
        $command->setParam("recipientId", $recipientId);
        $command->setParam("limit", $limit);
        $command->setParam("offset", $offset);
        $command->setParam("orderBy", $orderBy);
        $command->execute();
        return $command->getData("transactions");
    }

    /**
     * @param string $secret
     * @param float $amount
     * @param string $recipientId
     * @param string $publicKey
     * @param string $secondSecret
     * @return mixed
     * @throws CommandException
     */
    public function sendTransaction($secret, $amount, $recipientId, $publicKey, $secondSecret){
        $command = new Command($this->host, self::TRANSACTIONS_SEND,"POST");

        // The amount is the float value * 10^8 casted to string
        $amountString = strval($amount * 100000000);

        $command->setParam("secret", $secret);
        $command->setParam("amount", $amountString);
        $command->setParam("recipientId", $recipientId);
        $command->setParam("publicKey", $publicKey);
        $command->setParam("secondSecret", $secondSecret);
        $command->execute();
        return $command->getData("transactionId");
    }

    /**
     * Get transaction that matches the provided id.
     * @param string $id
     * @return mixed
     * @throws CommandException
     */
    public function getTransaction($id){
        $command = new Command($this->host, self::TRANSACTIONS_GET,"GET");
        $command->setParam("id", $id);
        $command->execute();
        return $command->getData("transaction");
    }

    /**
     * Get unconfirmed transaction that matches the provided id.
     * @param string $id
     * @return mixed
     * @throws CommandException
     */
    public function getUnconfirmedTransaction($id){
        $command = new Command($this->host, self::TRANSACTIONS_UNCONFIRMED_GET,"GET");
        $command->setParam("id", $id);
        $command->execute();
        return $command->getData("transaction");
    }

    /**
     * Gets a list of unconfirmed transactions.
     * @return mixed
     * @throws CommandException
     */
    public function getUnconfirmedTransactionsList(){
        $command = new Command($this->host, self::TRANSACTIONS_UNCONFIRMED_LIST,"GET");
        $command->execute();
        return $command->getData("transactions");
    }

    // =====================================================================================================
    //  PEERS
    // =====================================================================================================

    /**
     * Gets list of peers from provided filter parameters. (OR jointed)
     * @param string $state
     * @param string $os
     * @param string $version
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return mixed
     * @throws CommandException
     */
    public function getPeersList($state = null, $os = null, $version = null, $limit = null, $offset = null, $orderBy = null){

        $command = new Command($this->host, self::PEERS_LIST,"GET");
        $command->setParam("state", $state);
        $command->setParam("os", $os);
        $command->setParam("version", $version);
        $command->setParam("limit", $limit);
        $command->setParam("offset", $offset);
        $command->setParam("orderBy", $orderBy);
        $command->execute();
        return $command->getData("peers");
    }

    /**
     * Gets peer by IP address and port
     * @param string $ip
     * @param int $port
     * @return mixed
     * @throws CommandException
     */
    public function getPeer($ip, $port){

        $command = new Command($this->host, self::PEERS_GET,"GET");
        $command->setParam("ip", $ip);
        $command->setParam("port", $port);
        $command->execute();
        return $command->getData("peer");
    }

    /**
     * Gets a list peer versions and build times
     * @return mixed
     * @throws CommandException
     */
    public function getPeerVersion(){
        $command = new Command($this->host, self::PEERS_GET_VERSION,"GET");
        return $command->execute();
    }

    // =====================================================================================================
    //  BLOCKS
    // =====================================================================================================

    /**
     * Gets block by provided id.
     * @param string $id
     * @return mixed
     * @throws CommandException
     */
    public function getBlock($id){
        $command = new Command($this->host, self::BLOCKS_GET_ONE,"GET");
        $command->setParam("id", $id);
        $command->execute();
        return $command->getData("block");
    }

    /**
     * Gets all blocks by provided filter(s). (OR jointed)
     * @param int $totalFee
     * @param int $totalAmount
     * @param string $previousBlock
     * @param int $height
     * @param string $generatorPublicKey
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return mixed
     * @throws CommandException
     */
    public function getBlocks($totalFee = null, $totalAmount = null, $previousBlock = null, $height = null, $generatorPublicKey = null, $limit = null, $offset = null, $orderBy = null){

        $command = new Command($this->host, self::BLOCKS_GET_FILTER,"GET");
        $command->setParam("totalFee", $totalFee);
        $command->setParam("totalAmount", $totalAmount);
        $command->setParam("previousBlock", $previousBlock);
        $command->setParam("height", $height);
        $command->setParam("generatorPublicKey", $generatorPublicKey);
        $command->setParam("limit", $limit);
        $command->setParam("offset", $offset);
        $command->setParam("orderBy", $orderBy);
        $command->execute();
        return $command->getData("blocks");
    }

    /**
     * Get transaction fee for sending "normal" transactions.
     * @return mixed
     * @throws CommandException
     */
    public function getFee(){
        $command = new Command($this->host, self::BLOCKS_GET_FEE,"GET");
        $command->execute();
        return $this->convertAmountToFloat($command->getData("fee"));
    }

    /**
     * Get transaction fee for all types of transactions.
     * @return mixed
     * @throws CommandException
     */
    public function getFees(){
        $command = new Command($this->host, self::BLOCKS_GET_FEES,"GET");
        $command->execute();

        // Converting all fees to float
        $data = $command->getData("fees");
        foreach($data as $key => $value){
            $data[$key] = $this->convertAmountToFloat($value);
        }

        return $data;
    }

    /**
     * Gets the forging reward for blocks.
     * @return mixed
     * @throws CommandException
     */
    public function getReward(){
        $command = new Command($this->host, self::BLOCKS_GET_REWARD,"GET");
        $command->execute();
        return $this->convertAmountToFloat($command->getData("reward"));
    }

    /**
     * Gets the total amount of Shift in circulation
     * @return mixed
     * @throws CommandException
     */
    public function getSupply(){
        $command = new Command($this->host, self::BLOCKS_GET_SUPPLY,"GET");
        $command->execute();
        return $this->convertAmountToFloat($command->getData("supply"));
    }

    /**
     * Gets the blockchain height of the client.
     * @return mixed
     * @throws CommandException
     */
    public function getHeight(){
        $command = new Command($this->host, self::BLOCKS_GET_HEIGHT,"GET");
        $command->execute();
        return $command->getData("height");
    }

    /**
     * Gets status of height, fee, milestone, blockreward and supply
     * @return mixed
     * @throws CommandException
     */
    public function getStatus(){
        $command = new Command($this->host, self::BLOCKS_GET_STATUS,"GET");
        $command->execute();

        return [
            "height"    => $command->getData("height"),
            "fee"       => $this->convertAmountToFloat($command->getData("fee")),
            "milestone" => $command->getData("milestone"),
            "reward"    => $this->convertAmountToFloat($command->getData("reward")),
            "supply"    => $this->convertAmountToFloat($command->getData("supply"))
        ];
    }

    /**
     * Gets the nethash of the blockchain on a client.
     * @return mixed
     * @throws CommandException
     */
    public function getNethash(){
        $command = new Command($this->host, self::BLOCKS_GET_NETHASH,"GET");
        $command->execute();
        return $command->getData("nethash");
    }

    /**
     * Gets the milestone of the blockchain on a client.
     * @return mixed
     * @throws CommandException
     */
    public function getMilestone(){
        $command = new Command($this->host, self::BLOCKS_GET_MILESTONE,"GET");
        $command->execute();
        return $command->getData("milestone");
    }

    // =====================================================================================================
    //  SIGNATURES
    // =====================================================================================================

    /**
     * Gets the second signature status of an account.
     * @param string $id
     * @return mixed
     * @throws CommandException
     */
    public function getSignatureStatus(){
        $command = new Command($this->host, self::SIGNATURES_GET,"GET");
        $command->execute();
        return $command->getData("signature");
    }

    /**
     * Add a second signature to an account.
     * @param string $secret
     * @param string $secondSecret
     * @param string $publicKey
     * @return mixed
     * @throws CommandException
     */
    public function addSecondSignature($secret, $secondSecret, $publicKey){
        $command = new Command($this->host, self::SIGNATURES_ADD_SECOND,"PUT");
        $command->setParam("secret", $secret);
        $command->setParam("secondSecret", $secondSecret);
        $command->setParam("publicKey", $publicKey);
        return $command->execute();
    }
    // =====================================================================================================
    //  DELEGATES
    // =====================================================================================================

    /**
     * Puts request to create a delegate.
     * @param string $secret
     * @param string $secondSecret
     * @param string $username
     * @return mixed
     * @throws CommandException
     */
    public function createDelegate($secret, $secondSecret, $username){
        $command = new Command($this->host, self::DELEGATES_CREATE,"PUT");
        $command->setParam("secret", $secret);
        $command->setParam("secondSecret", $secondSecret);
        $command->setParam("username", $username);
        $command->execute();
        return $command->getData("transaction");
    }

    /**
     * Gets list of delegates by provided filter.
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @return mixed
     * @throws CommandException
     */
    public function getDelegatesList($limit = null, $offset = null, $orderBy = null){
        $command = new Command($this->host, self::DELEGATES_LIST,"GET");
        $command->setParam("limit", $limit);
        $command->setParam("offset", $offset);
        $command->setParam("orderBy", $orderBy);
        $command->execute();
        return $command->getData("delegates");
    }

    /**
     * Gets delegate by username
     * @param string $username
     * @return mixed
     * @throws CommandException
     */
    public function getDelegateByUsername($username){
        $command = new Command($this->host, self::DELEGATES_GET,"GET");
        $command->setParam("username", $username);
        $command->execute();
        return $command->getData("delegate");
    }

    /**
     * Gets delegate by public key
     * @param string $publicKey
     * @return mixed
     * @throws CommandException
     */
    public function getDelegateByPublicKey($publicKey){
        $command = new Command($this->host, self::DELEGATES_GET,"GET");
        $command->setParam("publicKey", $publicKey);
        $command->execute();
        return $command->getData("delegate");
    }

    /**
     * Get total count of registered delegates
     * @return mixed
     * @throws CommandException
     */
    public function getDelegatesCount(){
        $command = new Command($this->host, self::DELEGATES_COUNT,"GET");
        $command->execute();
        return $command->getData("count");
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

    /**
     * Enables forging for a delegate on the client node.
     * @param string $secret
     * @return mixed
     * @throws CommandException
     */
    public function enableForging($secret){
        $command = new Command($this->host, self::DELEGATES_ENABLE_FORGING,"POST");
        $command->setParam("secret", $secret);
        $command->execute();
        return $command->getData("address");
    }

    /**
     * Enables forging for a delegate on the client node.
     * @param string $secret
     * @return mixed
     * @throws CommandException
     */
    public function disableForging($secret){
        $command = new Command($this->host, self::DELEGATES_DISABLE_FORGING,"POST");
        $command->setParam("secret", $secret);
        $command->execute();
        return $command->getData("address");
    }

    /**
     * Get amount of Shift forged by an account.
     * @param string $publicKey
     * @return mixed
     * @throws CommandException
     */
    public function getForgedByAccount($publicKey){
        $command = new Command($this->host, self::DELEGATES_GET_FORGED,"GET");
        $command->setParam("generatorPublicKey", $publicKey);
        $command->execute();
        return [
            "fees"      => $this->convertAmountToFloat($command->getData("fees")),
            "rewards"   => $this->convertAmountToFloat($command->getData("rewards")),
            "forged"    => $this->convertAmountToFloat($command->getData("forged")),
        ];
    }

    // =====================================================================================================
    //  APPS
    // =====================================================================================================

    // =====================================================================================================
    //  MULTI-SIGNATURE
    // =====================================================================================================

}