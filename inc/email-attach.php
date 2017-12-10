<?php
$customer             = $customer_order_info['billing']['first_name'] . ' ' . $customer_order_info['billing']['last_name'];
$order_id             = $customer_order_info['id'];
$order_date           = date_i18n( 'j F Y', strtotime( $customer_order_info['date_created']['date'] ) );
$total_money          = $customer_order_info['total'];
$payment_method_title = $customer_order_info['payment_method_title'];
$address_2            = $customer_order_info['billing']['address_2'];
$city                 = $customer_order_info['billing']['city'];
$phone                = $customer_order_info['billing']['phone'];
$email                = $customer_order_info['billing']['email'];
?>
<div class="pdf-header">
	<div class="pdf-header-txt">
		Нове замовлення!
	</div>
</div>
<div class="pdf-body">
	<p style="margin:0 0 16px">Ви отримали замовлення від <?= $customer; ?>. Деталі замовлення:</p>
	<div class="order-info-header">
		<div class="order-number">
			Замовлення № <?= $order_id; ?>
			<span>(<?= $order_date; ?>)</span>
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" class="pdf-table">
		<thead>
		<tr>
			<th>Товар</th>
			<th>Кількість</th>
			<th>Ціна</th>
		</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $products as $product ) { ?>
			<tr>
				<td><?= $product['product_name']?></td>
				<td><?= $product['product_quantity']?></td>
				<td><?= $product['product_total']?></td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
		<tr>
			<th colspan="2">Разом:</th>
			<td><?= $total_money; ?></td>
		</tr>
		<tr>
			<th colspan="2">Спосіб оплати:</th>
			<td><?= $payment_method_title; ?></td>
		</tr>
		<tr>
			<th colspan="2">Всього:</th>
			<td><?= $total_money; ?></td>
		</tr>
		</tfoot>
	</table>

	<div class="order-address-title">
		Платіжна адреса
	</div>
	<div class="order-address-block">
		<address>
			<?= $customer; ?><br>
			<?= $address_2; ?><br>
			<?= $city; ?><br>
			<?= $phone; ?>
			<p style="margin:0 0 16px">
				<a href="mailto:<?= $email; ?>" target="_blank"><?= $email; ?></a>
			</p>
		</address>
	</div>

	<div class="pdf-body-footer">
		<p>LunchZBox</p>
	</div>
</div>

