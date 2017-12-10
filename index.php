<?php
	/*
	Plugin Name: Woo-pdf-generator
	Description: Generation pdf for order
	Version: 0.0.1
	Author: Petrun Oleksandr
	*/
	define( 'WC_PDF_GENERATOR', plugin_dir_path( __FILE__ ) );
	define( 'WC_PDF_UPLOADS', WP_CONTENT_DIR . '/uploads' );
	require_once 'libs' . DIRECTORY_SEPARATOR . 'mpdf/mpdf.php';

	//function generate_pdf for woo email attach
	function generate_pdf( $products, $customer_order_info ) {
		ob_start();
		include 'inc' . DIRECTORY_SEPARATOR . 'email-attach.php';
		$pdf_html   = ob_get_clean();
		$stylesheet = file_get_contents( WC_PDF_GENERATOR . 'inc' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'email-css.css' );

		$mpdf = new mPDF();
		$mpdf->WriteHTML( $stylesheet, 1 );
		$mpdf->WriteHTML( $pdf_html );
		$mpdf->Output( WC_PDF_UPLOADS . DIRECTORY_SEPARATOR . "woo-pdf-generator" . DIRECTORY_SEPARATOR . "lunchzbox.pdf", 'F' );
	}

	add_filter( 'woocommerce_email_attachments', 'attach_terms_conditions_pdf_to_email', 10, 3 );
	function attach_terms_conditions_pdf_to_email( $attachments, $status, $order ) {
		$order_items         = $order->get_items();
		$customer_order_info = json_decode( $order, true );
		$products            = [];

		foreach ( $order_items as $item ) { // loop through order items
			$products[ 'product_' . $item['product_id'] ] = [
				'product_name'     => $item['name'],
				'product_quantity' => $item['quantity'],
				'product_total'    => $item['total']
			];
		}

		generate_pdf( $products, $customer_order_info );

		$allowed_statuses = [ 'customer_processing_order' ];
		if ( isset( $status ) && in_array( $status, $allowed_statuses ) ) {
			$attachments[] = WC_PDF_UPLOADS . DIRECTORY_SEPARATOR . "woo-pdf-generator" . DIRECTORY_SEPARATOR . "lunchzbox.pdf";
		}

		return $attachments;
	}

	function myplugin_activate() {
		if(!check_woo()){
			echo "Сначала установите woocommerce";
			exit();
		}

		$upload     = wp_upload_dir();
		$upload_dir = $upload['basedir'];
		$upload_dir = $upload_dir . '/woo-pdf-generator';

		if ( ! wp_mkdir_p( $upload_dir ) ) {
			echo "Не удалось создать каталог woo-pdf-generator";
		}
	}

	register_activation_hook( __FILE__, 'myplugin_activate' );

	function check_woo(){
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return true;
		}

		return false;
	}