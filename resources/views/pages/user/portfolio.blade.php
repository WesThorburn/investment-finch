@extends('layouts.master')

@section('nav')
	@include('layouts.partials.nav-buttons', ['page' => 'portfolio'])
@stop

@section('title')
	Portfolio
@stop

@section('body')
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						Your Portfolios
					</div>
					@if($portfolios->first())
						<table class="table table-striped table-hover table-bordered table-condensed no-margins" id="portfolio_table">
							<tbody data-link="row" class="rowlink">
								@foreach($portfolios as $portfolio)
									<tr @if($portfolio->id == $selectedPortfolio->id) class="table-row-active" @endif>
										<td>
											<a href="{{$portfolio->id}}">{{$portfolio->portfolio_name}}</a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@else
						<div class="panel-body">
							You have no portfolios yet. 
						</div>
					@endif
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Create a portfolio
					</div>
					<div class="panel-body">
						<!-- Name -->
						<form role="form" action="{{action('PortfolioController@store')}}" method="POST">
							{{ csrf_field() }}
							<div class="col-xs-12">
								<label for="name">Portfolio Name</label>
							</div>
							<div class="col-xs-12">
								<input name="portfolioName" id="portfolioName" type="text" class="form-control{{ $errors->has('portfolioName') ? ' has-error' : ''}}" placeholder="Portfolio Name" maxlength="64" value={{ old('portfolioName') }}>
							</div>
							<div class="col-xs-12 half-margin-top half-margin-bottom">
								<button type="submit" class="btn btn-default">Create</button>
							</div>
						</form>
						@if($errors->has('portfolioName'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger three-quarter-margin-bottom">
									<ul>
							            @foreach ($errors->all() as $error)
							                <li>{{ $error }}</li>
							            @endforeach
							        </ul>
								</div>
							</div>
						@endif
						@if(Session::has('portfolioCreateSuccess'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-success three-quarter-margin-bottom">
									<ul>
							            <li>{{ Session('portfolioCreateSuccess') }}</li>
							        </ul>
								</div>
							</div>
						@elseif(Session::has('portfolioNameError'))
							<div class="col-xs-12 quarter-margin-top">
								<div class="alert alert-danger three-quarter-margin-bottom">
									<ul>
							            <li>{{ Session('portfolioNameError') }}</li>
							        </ul>
								</div>
							</div>
						@endif
					</div>
				</div>
			</div>
			@if($portfolios->first())
				<div class="col-md-9">
					@include('layouts.partials.portfolio-stocks')
					<div class="panel panel-default">
						<div class="panel-heading">
							Add a Stock to this Portfolio
						</div>
						<div class="panel-body">
							<!-- Add Stock to Portfolio -->
							<form role="form" action="{{action('PortfolioController@update', ['id' => $selectedPortfolio->id])}}" method="POST">
								<input type="hidden" name="_method" value="put"/>
								{{ csrf_field() }}
								<div class="row">
									<label class="col-xs-2 single-px-padding-right" for="stockCode">Stock Code</label>
									<label class="col-xs-2 single-px-padding-left-right" for="purchasePrice">Purchase Price</label>
									<label class="col-xs-2 single-px-padding-left-right" for="purchaseQty">Quantity</label>
									<label class="col-xs-2 single-px-padding-left-right" for="brokerage">Brokerage</label>
									<label class="col-xs-3 single-px-padding-left-right" for="date">Purchase Date</label>
								</div>
								<div class="row">
									<div class="col-xs-2 single-px-padding-right">
										<input name="stockCode" id="stockCode" type="text" class="form-control{{ $errors->has('stockCode') ? ' has-error' : ''}}" 
										placeholder="Code" maxlength="3" value={{ old('stockCode') }}>
									</div>
									<div class="col-xs-2 single-px-padding-left-right">
										<div class="input-group">
											<div class="input-group-addon">$</div>
											<input name="purchasePrice" id="purchasePrice" type="text" class="form-control{{ $errors->has('purchasePrice') ? ' has-error' : ''}}" value={{ old('purchasePrice') }}>
										</div>
									</div>
									<div class="col-xs-2 single-px-padding-left-right">
										<input name="quantity" id="quantity" type="text" class="form-control{{ $errors->has('quantity') ? ' has-error' : ''}}" value={{ old('quantity') }}>
									</div>
									<div class="col-xs-2 single-px-padding-left-right">
										<div class="input-group">
											<div class="input-group-addon">$</div>
											<input name="brokerage" id="brokerage" value="19.95" type="text" class="form-control{{ $errors->has('brokerage') ? ' has-error' : ''}}" value={{ old('brokerage') }}>
										</div>
									</div>
									<div class="col-xs-3 single-px-padding-left-right">
										<input name="date" id="date" type="date" class="form-control{{ $errors->has('date') ? ' has-error' : ''}}" value={{ old('date') }}>
									</div>
									<div class="col-xs-1 single-px-padding-left">
										<button type="submit" class="btn btn-default">Add</button>
									</div>
								</div>
							</form>
							@if($errors->has('stockCode') || $errors->has('purchasePrice') || $errors->has('quantity') || $errors->has('brokerage') || $errors->has('date'))
								<div class="col-xs-12 default-margin-top">
									<div class="alert alert-danger three-quarter-margin-bottom">
										<ul>
								            @foreach ($errors->all() as $error)
								                <li>{{ $error }}</li>
								            @endforeach
								        </ul>
									</div>
								</div>
							@endif
							@if(Session::has('addStockToPortfolioSuccess'))
								<div class="col-xs-12 default-margin-top">
									<div class="alert alert-success three-quarter-margin-bottom">
										<ul>
								            <li>{{ Session('addStockToPortfolioSuccess') }}</li>
								        </ul>
									</div>
								</div>
							@elseif(Session::has('addStockToPortfolioError'))
								<div class="col-xs-12 default-margin-top">
									<div class="alert alert-danger three-quarter-margin-bottom">
										<ul>
								            <li>{{ Session('addStockToPortfolioError') }}</li>
								        </ul>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>
@stop