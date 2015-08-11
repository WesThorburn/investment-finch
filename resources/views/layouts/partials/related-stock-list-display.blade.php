<div class="panel panel-default">
	<div class="panel-heading"><b>Related Stocks</b></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margin-top" id="stock_table">
	    <thead>
	        <tr>
	            <th>Code</th>
	            <th>Name</th>
	            <th>Sector</th>
	            <th>Share Price</th>
	            <th>Day Change</th>
	            <th>Mkt Cap (M)</th>
	        </tr>
	    </thead>
	    <tbody data-link="row" class="rowlink">
		    @foreach($relatedStocks as $stock)
				<tr>
					<td>
						{{ $stock->stock_code }}<a href="/stocks/{{$stock->stock_code}}"></a>
					</td>
					<td>{{ ucwords(strtolower($stock->stock->company_name)) }}</td>
					<td>{{ $stock->stock->sector }}</td>
					<td>${{ $stock->last_trade }}</td>
					<td @if($stock->day_change < 0) class="color-red" 
						@elseif($stock->day_change > 0) class="color-green"
						@endif>
						{{ $stock->day_change }}%
					</td>
					<td>{{ $stock->market_cap }}</td>
				</tr>
			@endforeach
	    </tbody>
	</table>
</div>

<script>
	$(document).ready(function(){
		$('#stock_table').DataTable({
			"dom": 'tp',
			"pageLength": 5,
			"lengthMenu": [5,10,20,50,100],
			"stateSave": true
		});
	});
</script>