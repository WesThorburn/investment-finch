<?php
Route::get('/', 'PageController@index');

Route::resource('stocks', 'StockController');
Route::resource('search', 'SearchController');
Route::resource('sectors', 'SectorController');

Route::get('graph/{stockCode}/{timeFrame}/{dataType}', 'StockController@graph');

Route::group(['prefix' => 'ajax'], function(){
	Route::get('sectors/{sectorName}/daychanges', 'SectorController@sectorDayChanges');
	Route::get('sectors/{sectorName}/otherstocksinsector', 'SectorController@otherStocksInSector');
	Route::get('relatedstocks/{stockCode}', 'StockController@relatedStocks');
	Route::get('/marketstatus','SearchController@marketStatus');
	Route::get('/marketchange', 'SearchController@marketChange');
});

/*route::get('/test', function(){

});

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);*/