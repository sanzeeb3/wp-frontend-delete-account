<?php

class WP_Frontend_Delete_Account_WooCommerce_Test extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->class_instance = new \WPFrontendDeleteAccount\WooCommerce();
	}

	public function test_add_wpf_delete_account_tab() {

		$items = $this->class_instance->add_wpf_delete_account_tab(
			array(
				'dashboard'       => 'Dashboard',
				'orders'          => 'Orders',
				'downloads'       => 'Downloads',
				'edit-address'    => 'Addresses',
				'edit-account'    => 'Account Details',
				'customer-logout' => 'Logout',
			)
		);

		$expected = array(
			'dashboard'          => 'Dashboard',
			'orders'             => 'Orders',
			'downloads'          => 'Downloads',
			'edit-address'       => 'Addresses',
			'edit-account'       => 'Account Details',
			'customer-logout'    => 'Logout',
			'wpf-delete-account' => 'Delete Account',
		);

		$this->assertEquals( $expected, $items );
	}
}
