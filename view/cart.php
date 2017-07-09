<?php include_once("header.php");?>

<section  ng-controller="cart-controller">
	
	<div class="container">

		<div class="row text-center title-default-roxo" style="margin:40px auto;">
			<h2>carrinho de compras</h2>
			<hr>	
		</div>

		<table id="cart-products" class="table table-bordered">
			<thead>
				<tr>
					<th colspan="2">Produto(s)</th>
					<th class="text-center">Quantidade</th>
					<th class="text-center">Entrega</th>
					<th class="text-center">Valor Unitário</th>
					<th class="text-center">Valor Total</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="produto in produtos">
					<td class="text-center"><img src="img/produtos/{{produto.foto_principal}}"></td>
					<td>{{produto.nome_prod_long}}</td>
					<td class="col-xs-2">
						<div class="input-group">
					      <span class="input-group-btn">
					        <button class="btn text-roxo" ng-click="removeQtd(produto)" type="button"><i class="fa fa-chevron-down"></i></button>
					      </span>
					      <input type="text" class="form-control" ng-model="produto.qtd_car">
					      <span class="input-group-btn">
					        <button class="btn text-roxo" ng-click="addQtd(produto)" type="button"><i class="fa fa-chevron-up"></i></button>
					      </span>
					    </div>
					</td>
					<td class="text-center col-xs-2">
						<p>Entrega para o<br/>CEP: {{carrinho.cep}}</p>
						<strong class="text-roxo">{{produto.prazo}}</strong>
					</td>
					<td class="text-center">R$ {{produto.preco}}</td>
					<td class="text-center">R$ {{produto.total}}</td>
					<td class="text-center"><button ng-click="removeAll(produto)" class="btn text-roxo" type="button"><i class="fa fa-close"></i></button></td>
				</tr>
			</tbody>
		</table>

		<div id="calculo-de-frete" class="row">
			<div class="col-sm-8">
				<div class="box-outline-grey">
					<p style="margin:28px auto;">Simule o prazo de entrega e o frete para seu CEP abaixo:</p>
					<div class="input-group col-xs-4" style="margin:0 auto;">
				      <input type="text" class="form-control" ng-model="cep">
				      <span class="input-group-btn">
				      	<button ng-click="calcularFrete(cep)" class="btn btn-default" type="button">Calcular Frete</button>
				      </span>
				    </div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="box-cart-totais">
					<table class="table">
						<tr>
							<td>Subtotal ({{produtos.length}} item):</td>
							<td class="text-right">R$ {{carrinho.subtotal}}</td>
						</tr>
						<tr>
							<td>Frete:</td>
							<td class="text-right">R$ {{carrinho.frete}}</td>
						</tr>
						<tr style="border-top:#999 1px solid;">
							<td class="text-roxo text-bold">Total:</td>
							<td class="text-roxo text-bold text-right">R$ {{carrinho.total}}</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<button type="button" class="btn btn-roxo pull-right" style="margin-top:10px">comprar</button>

	</div>

</section>

<?php include_once("footer.php");?>

<script>
angular.module("shop", []).controller("cart-controller", function($scope, $http){

	var carregarCarrinho = function(){

		$http({
			method:'GET',
			url:'carrinho-dados'
		}).then(function(response){

			$scope.carrinho = {
				cep: response.data.cep_car,
				subtotal: response.data.subtotal_car,
				frete: response.data.frete_car,
				total: response.data.total_car
			};

			$scope.produtos = response.data.produtos;
			console.log(response.data);


		}, function(response){

			console.error(response);

		});

	};

	/*
	$scope.carrinho = {
		cep: '01310-100',
		subtotal: '1.110,00',
		frete: '0,00',
		total: '1.110,00'
	};

	$scope.produtos = [{
		nome_prod_long:'Smartphone Motorola Moto X Play Dual',
		preco:'1.500,99',
		total:'1.500,99',
		qtd:1,
		foto_principal:'iphone.jpg',
		prazo:'11 dias úteis',
		id_prod:1
	},{
		nome_prod_long:'Smartphone Motorola Moto X Play Dual',
		preco:'1.500,99',
		total:'1.500,99',
		qtd:1,
		foto_principal:'iphone.jpg',
		prazo:'10 dias úteis',
		id_prod:2
	}];
	*/

	$scope.addQtd = function(_produto){

		$http({
			method:'POST',
			url:'carrinho-produto',
			data:JSON.stringify({
			id_prod:_produto.id_prod
			})
		}).then(function(response){

			carregarCarrinho();

		}, function(){

			//Code

		});

	};

	$scope.removeQtd = function(_produto){

		$http({
			method:'DELETE',
			url:'carrinho-produto',
			data:JSON.stringify({
			id_prod:_produto.id_prod
			})
		}).then(function(response){

			carregarCarrinho();

		}, function(){

			//Code

		});

	};

	$scope.removeAll = function(_produto){

		$http({
			method:'DELETE',
			url:'carrinhoRemoveAll-' + _produto.id_prod
		}).then(function(response){

			carregarCarrinho();

		}, function(){

			//Code

		});


	};

	$scope.calcularFrete = function(_cep){

		$http({
			method:'GET',
			url:'calcular-frete-:' + _cep
		}).then(function(response){

			console.log(response.data);
			carregarCarrinho();

		}, function(){

			//Code

		});	

	};

	carregarCarrinho();



});
</script>