<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Stock;
use App\Models\StockGains;
use App\Models\Historicals;
use App\Models\SectorIndexHistoricals;
use App\Models\StockMetrics;
use Khill\Lavacharts\Lavacharts;

class PageController extends Controller
{
    public function index()
    {
        //Line graph for market cap
        $totalMarketCapGraphData = SectorIndexHistoricals::getIndividualSectorGraphData('All', 'last_month', 'Market Cap');
        $marketCaps = \Lava::DataTable();
        $marketCaps->addStringColumn('Date')
            ->addNumberColumn('Market Cap')
            ->addRows($totalMarketCapGraphData);

        $marketCapsLava = \Lava::AreaChart('MarketCaps')
            ->dataTable($marketCaps)
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'ASX Market Cap (Billions)'
            ]);

        //PieChart for Sectors' Market Caps
        $individualSectorCapsGraphData = SectorIndexHistoricals::getAllSectorGraphData('top_5');
        $sectorCaps = \Lava::DataTable();
        $sectorCaps->addStringColumn('Sector Name') 
            ->addNumberColumn('Percent')
            ->addRows($individualSectorCapsGraphData);

        $sectorCapsLava = \Lava::PieChart('Sectors')
            ->dataTable($sectorCaps)
            ->customize([
            	'tooltip' => [
            		'text' => 'percentage'
            	]
            ])
            ->setOptions([
                'width' => 725,
                'height' => 360,
                'title' => 'Sector Caps (Billions)',
                'pieSliceText' => 'label',
            ]);

        return view('pages.home')->with([
            'marketCapsLava' => $marketCapsLava,
            'sectorDayGains' => SectorIndexHistoricals::getSectorDayChanges('top', 5, true),
            'sectorDayLosses' => SectorIndexHistoricals::getSectorDayChanges('bottom', 5, true),
            'sectorDayGainTitle' => SectorIndexHistoricals::getSectorDayChangeTitle('top'),
            'sectorDayLossTitle' => SectorIndexHistoricals::getSectorDayChangeTitle('bottom'),
            'highestVolumeStocks' => StockMetrics::with('stock')->omitOutliers()->orderBy('volume', 'desc')->take(10)->get(),
            'highestVolumeStocksTitle' => SectorIndexHistoricals::getSectorWeekDay()."'s Market Movers"
        ]);
    }

    public function topGainsLosses(){
        $allNonOmittedStocks = StockMetrics::omitOutliers()->lists('stock_code');

        return view('pages.topGainsLosses')->with([
            'topWeeklyGains' => StockGains::getTopStocksThisWeek($allNonOmittedStocks),
            'topWeeklyLosses' => StockGains::getBottomStocksThisWeek($allNonOmittedStocks),
            'topMonthlyGains' => StockGains::getTopStocksThisMonth($allNonOmittedStocks),
            'topMonthlyLosses' => StockGains::getBottomStocksThisMonth($allNonOmittedStocks),
            'topStocks12Months' => StockGains::getTopStocks12Months(29)
        ]);
    }

    public function discontinued(){
        return view('pages/dashboard/discontinued')->with([
        	'discontinuedStocks' => Stock::onlyTrashed()->get()
        ]);
    }

    public function marketCapAdjustments(){
/*        $marketCapAdjustments = StockMetrics::whereNotIn('stock_code', Stock::onlyTrashed()->lists('stock_code'))->where('market_cap_requires_adjustment', 1)->get();
        $yesterdaysHistoricalDate = Historicals::getYesterdaysHistoricalsDate();

        foreach($marketCapAdjustments as $stock){
            $stock->yesterdays_market_cap = Historicals::where(['stock_code' => $stock->stock_code, 'date' => $yesterdaysHistoricalDate])->pluck('market_cap');
        }*/

        return view('pages/dashboard/market-cap-adjustments')->with([
            'marketCapAdjustments' => []
        ]);
    }

    public function addStockForAdjustment(Request $request){
        $stockMetric = StockMetrics::where('stock_code', $request->stockCodeAdd)->first();
        $stockMetric->market_cap_requires_adjustment = $request->adjustment;
        $stockMetric->save();
        return redirect('/dashboard/marketCapAdjustments');
    }
}
