<div class="fdCartContainer">
	<div class="fdHeading">
		<div class="title"><?php __('front_your_cart');?></div>
		<div class="items"><?php echo $tpl['cart_box']['items_in_cart'];?></div>
	</div>
	
	<?php
	if ($tpl['cart_box']['cart'] !== false && count($tpl['cart_box']['cart']) > 0)
	{
		switch ($_GET['type'])
		{
			case 'plain':
			case 'total':
				?>
				<table cellpadding="0" cellspacing="0" class="fdCartTbl">
					<thead>
						<tr>
							<th><?php __('front_product'); ?></th>
							<th style="width: 10%;"><?php __('front_qty'); ?></th>
							<th style="width: 20%;"><?php __('front_price'); ?></th>
							<th style="width: 16px;">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$price = 0;
						foreach ($tpl['cart_box']['cart'] as $hash => $item)
						{
							foreach ($tpl['cart_box']['product_arr'] as $product)
							{
								if ($product['id'] == $item['product_id'])
								{
									$product_price = 0;
									$product_price = $item['price'] * $item['cnt'];
									$price += $product_price;
									?>
									<tr class="fdProductRow<?php echo empty($item['extra_arr']) ? ' fdLineRow' : null;?>">
										<td><?php echo stripslashes($product['name']) . (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL); ?></td>
										<td class="fdQtyCol"><?php echo $item['cnt']; ?></td>
										<td class="fdPriceCol">
											<?php
											echo pjUtil::formatCurrencySign(number_format($item['price'], 2), $tpl['option_arr']['o_currency']);
											?>
										</td>
										<td><a href="#" class="fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""></a></td>
									</tr>
									<?php
									$i = 1;
									foreach ($item['extra_arr'] as $extra_id => $extra)
									{
										$price += $extra['price'] * $extra['qty'];
										?>
										<tr class="fdExtraRow<?php echo $i == count($item['extra_arr']) ? ' fdLineRow' : null;?>">
											<td class="fdItalic"><?php echo stripslashes($extra['name']); ?>
											<td class="fdQtyCol"><?php echo $extra['qty']; ?></td>
											<td class="fdPriceCol"><?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']); ?></td>
											<td><a href="#" class="fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra="<?php echo $extra_id; ?>"></a></td>
										</tr>
										<?php
										$i++;
									}
								}
							}
						}
						?>
						<tr class="fdProductRow">
							<td><span class="fdCartSubtotal"><?php __('front_price'); ?>:</span></td>
							<td>&nbsp;</td>
							<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($price, 2), $tpl['option_arr']['o_currency']); ?></span></td>
							<td>&nbsp;</td>
						</tr>
						<?php
						if ($_GET['type'] == 'total')
						{
							$delivery = $controller->_get("delivery");
							$discount = 0;
							$discount_print = NULL;
							$subtotal = 0.00;
							$tax = 0.00;
							$total = 0.00;
							
							if ($controller->_get('voucher_code') !== false)
							{
								$voucher_discount = $controller->_get('voucher_discount');
								switch ($controller->_get('voucher_type'))
								{
									case 'percent':
										$discount_print = $voucher_discount . "%";
										$discount = (($price + $delivery) * $voucher_discount) / 100;
										break;
									case 'amount':
										$discount_print = pjUtil::formatCurrencySign(number_format($voucher_discount, 2), $tpl['option_arr']['o_currency']);
										$discount = $voucher_discount;
										break;
								}
							}
							
							$subtotal = $price + $delivery - $discount;

							$controller->_set('discount', $discount);
							$controller->_set('price', $price);
							$controller->_set('subtotal', $subtotal);
							if($_GET['action'] == 'pjActionCheckout')
							{
								?>
								<tr class="fdProductRow">
									<td><span class="fdCartSubtotal fdCartSubtotalLabel"><?php __('front_promo_code'); ?>:</span></td>
									<td class="fdPriceCol fdForm" colspan="2">
										<input type="text" id="fdVoucherCode_<?php echo $_GET['index']; ?>" name="voucher_code" class="fdText fdW90 fdMb3"/>
										<a href="#" class="fdButton fdNormalButton fdButtonApply fdFloatRight"><?php __('front_button_apply');?></a>
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr style="display:none;">
									<td colspan="4" id="fdVoucherMessage_<?php echo $_GET['index'];?>" class="fdVoucherMessage"></td>
								</tr>
								<?php
							}
							if ($controller->_get('type') == 'delivery')
							{
								?>
								<tr class="fdProductRow">
									<td><span class="fdCartSubtotal fdCartSubtotalLabel"><?php __('front_delivery_fee'); ?>:</span></td>
									<td>&nbsp;</td>
									<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($delivery, 2), $tpl['option_arr']['o_currency']); ?></span></td>
									<td>&nbsp;</td>
								</tr>
								<?php
							}
							if(!is_null($discount_print))
							{
								?>
								<tr class="fdProductRow">
									<td><span class="fdCartSubtotal fdCartSubtotalLabel"><?php __('front_discount'); ?>:</span></td>
									<td>&nbsp;</td>
									<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo $discount_print; ?></span></td>
									<td>&nbsp;</td>
								</tr>
								<?php
							}
							?>
							<tr class="fdProductRow">
								<td><span class="fdCartSubtotal"><?php __('front_subtotal'); ?>:</span></td>
								<td>&nbsp;</td>
								<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($subtotal, 2), $tpl['option_arr']['o_currency']); ?></span></td>
								<td>&nbsp;</td>
							</tr>
							<?php
							if(!empty($tpl['option_arr']['o_tax_payment']))
							{ 
								$tax = ($subtotal * $tpl['option_arr']['o_tax_payment']) / 100;
								$controller->_set('tax', $tax);
								?>
								<tr class="fdProductRow">
									<td><span class="fdCartSubtotal fdCartSubtotalLabel"><?php __('front_tax'); ?>:</span></td>
									<td>&nbsp;</td>
									<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($tax, 2), $tpl['option_arr']['o_currency']); ?></span></td>
									<td>&nbsp;</td>
								</tr>
								<?php
								
							}
							$total = $subtotal + $tax;
							$controller->_set('total', $total); 
							?>
							<tr class="fdProductRow">
								<td><span class="fdCartSubtotal"><?php echo strtoupper(__('front_total', true, false)); ?>:</span></td>
								<td>&nbsp;</td>
								<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($total, 2), $tpl['option_arr']['o_currency']); ?></span></td>
								<td>&nbsp;</td>
							</tr>
							<?php
						} 
						?>
					</tbody>
				</table>
				<?php
				break;
			default:
				?>
				<table cellpadding="0" cellspacing="0" class="fdCartTbl">
					<thead>
						<tr>
							<th><?php __('front_product'); ?></th>
							<th style="width: 10%;"><?php __('front_qty'); ?></th>
							<th style="width: 20%;"><?php __('front_price'); ?></th>
							<th style="width: 16px;">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$price = 0;
						foreach ($tpl['cart_box']['cart'] as $hash => $item)
						{
							foreach ($tpl['cart_box']['product_arr'] as $product)
							{
								if ($product['id'] == $item['product_id'])
								{
									$product_price = 0;
									$product_price = $item['price'] * $item['cnt'];
									$price += $product_price;
									?>
									<tr class="fdProductRow<?php echo empty($item['extra_arr']) ? ' fdLineRow' : null;?>">
										<td><?php echo stripslashes($product['name']) . (!empty($item['size']) ? '<br/>(' .$item['size'] . ')' : NULL); ?></td>
										<td class="fdQtyCol"><?php echo $item['cnt']; ?></td>
										<td class="fdPriceCol">
											<?php
											echo pjUtil::formatCurrencySign(number_format($item['price'], 2), $tpl['option_arr']['o_currency']);
											?>
										</td>
										<td><a href="#" class="fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra=""></a></td>
									</tr>
									<?php
									$i = 1;
									foreach ($item['extra_arr'] as $extra_id => $extra)
									{
										$price += $extra['price'] * $extra['qty'];
										?>
										<tr class="fdExtraRow<?php echo $i == count($item['extra_arr']) ? ' fdLineRow' : null;?>">
											<td class="fdItalic"><?php echo stripslashes($extra['name']); ?>
											<td class="fdQtyCol"><?php echo $extra['qty']; ?></td>
											<td class="fdPriceCol"><?php echo pjUtil::formatCurrencySign(number_format($extra['price'], 2), $tpl['option_arr']['o_currency']); ?></td>
											<td><a href="#" class="fdCartItemRemove" data-hash="<?php echo $hash; ?>" data-extra="<?php echo $extra_id; ?>"></a></td>
										</tr>
										<?php
										$i++;
									}
								}
							}
						}
						$controller->_set('price', $price);
						?>
						<tr class="fdProductRow">
							<td><span class="fdCartSubtotal"><?php __('front_price'); ?>:</span></td>
							<td>&nbsp;</td>
							<td class="fdPriceCol"><span class="fdCartSubtotal"><?php echo pjUtil::formatCurrencySign(number_format($price, 2), $tpl['option_arr']['o_currency']); ?></span></td>
							<td>&nbsp;</td>
						</tr>
					</tbody>
				</table>
				<div class="fdCartButton">
					<a href="#" class="fdButton fdOrangeButton fdButtonCheckout" data-logged="<?php echo $controller->isFrontLogged() ? 'yes' : 'no';?>"><?php __('front_button_checkout');?></a>
				</div>
				<?php
				break;
		}
	} else {
		?><div class="fdEmptyCart"><?php __('front_empty_cart');?></div><?php
	}
	?>
</div>