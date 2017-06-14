<?php

namespace ShiftPHP\Classes;

/**
 * CoinMarketCap API
 * Official API documentation : https://coinmarketcap.com/api/
 * Please limit requests to no more than 10 per minute. (Add some caching on your side)
 * Endpoints update every 5 minutes.
 * @author Yondz
 * @package ShiftPHP
 * @link https://github.com/yondz/shift-php
 * @license https://github.com/yondz/shift-php/blob/master/LICENSE
 */
class CoinMarketCapAPI extends AbstractAPI{

    /**
     * List of currencies supported by CMC
     * @var array
     */
    protected $validCurrencies = [
        "AUD",
        "BRL",
        "CAD",
        "CHF",
        "CNY",
        "EUR",
        "GBP",
        "HKD",
        "IDR",
        "INR",
        "JPY",
        "KRW",
        "MXN",
        "RUB"
    ];

    /**
     * API Commands constants
     */

    // Root API
    const START                             = "/v1";

    // Commands
    const TICKER                            = self::START . "/ticker/";
    const GLOBAL_DATA                       = self::START . "/global/";

    /**
     * CoinMarketCapAPI constructor.
     * @param string $host
     */
    public function __construct($host = "https://api.coinmarketcap.com"){
        parent::__construct($host);
    }

    /**
     * Get ticker data for the $limit first coins
     * @param integer $limit
     * @param integer $convert
     * @return mixed
     * @throws CommandException
     */
    public function getTicker($limit = null, $convert = null){
        if($convert !== null && !in_array($convert, $this->validCurrencies)){
            throw new CommandException("This currency (".$convert.") is not supported");
        }
        $command = new Command($this->host, self::TICKER,"GET", $this->enableCache, $this->cacheLifetime, $this->cacheFolder);
        $command->setParam("limit", $limit);
        $command->setParam("convert", $convert);
        return $command->execute();
    }


    /**
     * Get ticker data for the selected $coin
     * @param integer $id
     * @param integer $convert
     * @return mixed
     * @throws CommandException
     */
    public function getTickerByCurrency($id = "bitcoin", $convert = null){
        if($convert !== null && !in_array($convert, $this->validCurrencies)){
            throw new CommandException("This currency (".$convert.") is not supported");
        }
        $command = new Command($this->host, self::TICKER,"GET", $this->enableCache, $this->cacheLifetime, $this->cacheFolder);
        $command->addRoutePath($id."/");
        $command->setParam("convert", $convert);
        return $command->execute();
    }


    /**
     * Get global data
     * @param integer $convert
     * @return mixed
     * @throws CommandException
     */
    public function getGlobalData( $convert = null){
        if($convert !== null && !in_array($convert, $this->validCurrencies)){
            throw new CommandException("This currency (".$convert.") is not supported");
        }
        $command = new Command($this->host, self::GLOBAL_DATA,"GET", $this->enableCache, $this->cacheLifetime, $this->cacheFolder);
        $command->setParam("convert", $convert);
        return $command->execute();
    }
}