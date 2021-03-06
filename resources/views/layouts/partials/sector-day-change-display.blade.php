<div class="panel panel-default three-quarter-margin-bottom">
	<div class="panel-heading"><h4 class="no-margin-top no-margin-bottom text-center">{{ $title }}</h4></div>
	<table class="table table-striped table-hover table-bordered table-condensed table-bordered-only-top-bottom no-margins">
		<tbody data-link="row" class="rowlink">
		    @foreach($sectorChanges as $sectorChange)
				<tr>
					<td>
						{{ $sectorChange->sector }}<a href="/sectors/{{$sectorChange->sector}}"></a>
					</td>
					<td @if($sectorChange->day_change < 0) class="color-red" 
						@elseif($sectorChange->day_change > 0) class="color-green"
						@endif>
						{{ $sectorChange->day_change }}%
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>