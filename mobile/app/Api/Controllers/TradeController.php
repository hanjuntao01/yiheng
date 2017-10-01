<?php

namespace App\Api\Controllers;

use App\Api\Foundation\Controller;
use App\Repositories\Trade\TradeRepository;
use App\Api\V2\Trade\Transformer\TradeTransformer;

/**
 * Class TradeController
 * @package App\Api\Controllers
 */
class TradeController extends Controller
{
    protected $trade;

    protected $tradeTransformer;

    /**
     * TradeController constructor.
     * @param TradeRepository $trade
     * @param TradeTransformer $tradeTransformer
     */

    public function __construct(TradeRepository $trade, TradeTransformer $tradeTransformer)
    {
        parent::__construct();
        $this->trade = $trade;
        $this->tradeTransformer = $tradeTransformer;
    }

    /**
     *
     */
    public function actionGet()
    {
    }
}
