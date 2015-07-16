<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SectorHistoricals extends Model
{
    protected $table = 'sector_historicals';

    protected $fillable = [
    	'sector',
    	'date',
    	'day_change',
    	'created_at',
    	'updated_at'
    ];

    public static function getSectorDayChanges($section){
        if($section == 'sectorDayGain'){
            $order = "desc";
        }
        elseif($section == 'sectorDayLoss'){
            $order = "asc";
        }
    	return SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate()[0])
            ->where('sector', '!=', 'Class Pend')
            ->where('sector', '!=', 'All')
            ->orderBy('day_change', $order)
            ->take(5)
            ->get();
    }

    public static function getSectorDayChangeTitle($section){
        $mostRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate()[0];
        if($mostRecentSectorHistoricalsDate == date("Y-m-d")){
            //Most Recent Date is today
            $dayForTitle = "Today";
        }
        else{
            $dayForTitle = date("l", strtotime($mostRecentSectorHistoricalsDate));
        }

        if($section == 'sectorDayGain'){
            return $dayForTitle."'s Best Performing Sectors";
        }
        elseif($section == 'sectorDayLoss'){
            return $dayForTitle."'s Worst Performing Sectors";
        }
    }

    public static function getMarketChange(){
        return SectorHistoricals::where('date', SectorHistoricals::getMostRecentSectorHistoricalsDate()[0])
            ->where('sector', 'All')
            ->pluck('day_change');
    }

    public static function getMarketChangeMessage(){
        $mostRecentSectorHistoricalsDate = SectorHistoricals::getMostRecentSectorHistoricalsDate()[0];
        $marketChange = SectorHistoricals::getMarketChange();
        if($mostRecentSectorHistoricalsDate == date("Y-m-d")){
            if($marketChange < 0){
                return "The ASX is down ".$marketChange."% today.";
            }
            elseif($marketChange >= 0){
                return "The ASX is up ".$marketChange."% today.";
            }
        }
        else{
            $dayForTitle = date("l", strtotime($mostRecentSectorHistoricalsDate));
            if($marketChange < 0){
                return "The ASX was down ".$marketChange."% on ".$dayForTitle.".";
            }
            elseif($marketChange >= 0){
                return "The ASX was up ".$marketChange."% on ".$dayForTitle.".";
            }
        }
    }

    private static function getMostRecentSectorHistoricalsDate(){
        return SectorHistoricals::orderBy('date', 'desc')->take(1)->lists('date');
    }
}
