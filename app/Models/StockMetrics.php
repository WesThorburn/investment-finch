<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMetrics extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];

	protected $table = 'stock_metrics';

	protected $guarded = ['id','created_at'];

	public function stock(){
		return $this->belongsTo('App\Models\Stock', 'stock_code', 'stock_code');
	}

	public function scopeOmitOutliers($query, $omitCondition = 'omit'){
		if($omitCondition == 'omit'){
			return $query->where('last_trade', '>=', '0.05')
				->where('volume', '!=', 0)
				->where('earnings_per_share_current', '>=', 0.01)
				->where('price_to_earnings', '>=', 0.01)
				->where('price_to_book', '>=', 0.01)
				->where('current_market_cap', '!=', 'N/A');
		}
		return $query;
	}

	public function scopeLimit($query, $limit){
		if($limit == 'top_5'){
			return $query->take(5);
		}
		elseif($limit == 'top_10'){
			return $query->take(10);
		}
		elseif($limit == 'top_15'){
			return $query->take(15);
		}
		elseif($limit == 'top_20'){
			return $query->take(20);
		}
		elseif($limit == 'all'){
			return $query;
		}
	}

	public static function getMetricsByStockList($listOfStocks, $omitCondition){
		return StockMetrics::whereIn('stock_code', $listOfStocks)->omitOutliers($omitCondition)->with('stock')->get();
	}

	public static function getAverageMetric($metricName, $listOfStocks, $sectorName){
        $sectorMetrics = array();
        foreach($listOfStocks as $stock){
            $sectorMetric = StockMetrics::where('stock_code', $stock)->pluck($metricName);
            array_push($sectorMetrics, $sectorMetric);
        }
        return array_sum($sectorMetrics)/count($sectorMetrics);
    }

    public static function getMarketCapsInSectorGraphData($sectorName, $numberOfStocks){
    	$graphData = array();
    	$stocksInSector = Stock::where('sector', htmlspecialchars_decode($sectorName))->lists('stock_code');
    	$marketCaps = StockMetrics::with('stock')->select('stock_code','current_market_cap')->whereIn('stock_code', $stocksInSector)->orderBy('current_market_cap', 'DESC')->limit($numberOfStocks)->get();
    	$sumOfMarketCaps = $marketCaps->sum('current_market_cap');
    	foreach($marketCaps as $stock){
    		if($sumOfMarketCaps > 0 && $stock->current_market_cap > 0){
    			$percentageShare = 100/$sumOfMarketCaps * $stock->current_market_cap;
    		}
    		else{
    			$percentageShare = 0;
    		}
    		array_push($graphData, array($stock->stock->company_name, round($percentageShare, 2)));
    	}
    	return $graphData;
    }
}
