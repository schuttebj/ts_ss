<?php

add_action( 'init', array( 'TradeSafeProfile', 'init' ) );

class SwingSaveTradeSafe() {

    public static function init() {
		if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
			return;
		}

		self::init_hooks();
	}

    
	private static function init_hooks() {
        add_action( 'woocommerce_save_account_details', array( 'TradeSafeProfile', 'save_account_details' ) );
    }

    public static function save_account_details( int $user_id ) {
		// Nonce check copied from woocommerce/includes/class-wc-form-handler.php@save_account_details.
        $nonce_value = wc_get_var($_REQUEST['save-account-details-nonce'], wc_get_var($_REQUEST['_wpnonce'], '')); // @codingStandardsIgnoreLine.
		$client      = new \TradeSafe\Helpers\TradeSafeApiClient();

		if ( ! wp_verify_nonce( $nonce_value, 'save_account_details' ) ) {
			return;
		}

		if ( empty( $_POST['action'] ) || 'save_account_details' !== $_POST['action'] || is_array( $client ) ) {
			return;
		}

		$token_id = tradesafe_get_token_id( $user_id );

		$user_info = array(
			'givenName'  => sanitize_text_field( wp_unslash( $_POST['account_first_name'] ?? null ) ),
			'familyName' => sanitize_text_field( wp_unslash( $_POST['account_last_name'] ?? null ) ),
			'email'      => sanitize_email( wp_unslash( $_POST['account_email'] ?? null ) ),
			'mobile'     => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_mobile'] ?? null ) ),
			'idNumber'   => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_id_number'] ?? null ) ),
			'idType'     => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_id_type'] ?? null ) ),
			'idCountry'  => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_id_country'] ?? null ) ),
		);

		$bank_account = null;
		$organization = null;

		if ( isset( $_POST['tradesafe_token_bank_account_number'] )
			&& ! is_null( $_POST['tradesafe_token_bank_account_number'] )
			&& isset( $_POST['tradesafe_token_bank_account_type'] )
			&& ! is_null( $_POST['tradesafe_token_bank_account_type'] )
			&& isset( $_POST['tradesafe_token_bank'] )
			&& ! is_null( $_POST['tradesafe_token_bank'] ) ) {
			$bank_account = array(
				'accountNumber' => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_bank_account_number'] ?? null ) ),
				'accountType'   => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_bank_account_type'] ?? null ) ),
				'bank'          => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_bank'] ?? null ) ),
			);
		}

		if ( ! empty( $_POST['tradesafe_token_organization_name'] )
			&& ! empty( $_POST['tradesafe_token_organization_type'] )
			&& ! empty( $_POST['tradesafe_token_organization_registration_number'] ) ) {
			$organization = array(
				'name'               => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_organization_name'] ) ),
				'tradeName'          => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_organization_trading_name'] ?? null ) ),
				'type'               => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_organization_type'] ) ),
				'registrationNumber' => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_organization_registration_number'] ) ),
				'taxNumber'          => sanitize_text_field( wp_unslash( $_POST['tradesafe_token_organization_tax_number'] ?? null ) ),
			);
		}

		$payout_interval = 'IMMEDIATE';
		$settings        = get_option( 'woocommerce_tradesafe_settings', array() );

		if ( isset( $settings['payout_method'] ) ) {
			$payout_interval = $settings['payout_method'];
		}

		if ( $token_id ) {
			$token_data = $client->updateToken( $token_id, $user_info, $organization, $bank_account, $payout_interval );
		} else {
			$token_data = $client->createToken( $user_info, $organization, $bank_account, $payout_interval );

			update_user_meta( $user_id, tradesafe_token_meta_key(), sanitize_text_field( $token_data['id'] ) );
		}
	}
}