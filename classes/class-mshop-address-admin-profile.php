<?php 
/*
 * [ 우커머스 버전 지원 안내 ]
 * 워드프레스 버전 : WordPress 4.0
 * 우커머스 버전 : WooCommerce 2.2.x
 * 
 * [ 코드엠 플러그인 라이센스 규정 ]
 * (주)코드엠에서 개발된 워드프레스  플러그인을 사용하시는 분들에게는 다음 사항에 대한 동의가 있는 것으로 간주합니다.
 * 1. 코드엠에서 개발한 워드프레스 우커머스용 바로구매 플러그인의 저작권은 (주)코드엠에게 있습니다.
 * 2. 플러그인은 사용권을 구매하는 것이며, 프로그램 저작권에 대한 구매가 아닙니다.
 * 3. 플러그인을 구입하여 다수의 사이트에 복사하여 사용할 수 없으며, 1개의 라이센스는 1개의 사이트에만 사용할 수 있습니다. 이를 위반 시 지적 재산권에 대한 손해 배상 의무를 갖습니다.
 * 4. 플러그인은 구입 후 1년간 업데이트를 지원합니다.
 * 5. 플러그인은 워드프레스, 테마, 플러그인과의 호환성에 대한 책임이 없습니다.
 * 6. 플러그인 설치 후 버전에 관련한 운용 및 관리의 책임은 사이트 당사자에게 있습니다.
 * 7. 다운로드한 플러그인은 환불되지 않습니다.
 */
 
add_action('current_screen', 'mshop_address_init', 999);

function mshop_address_init() {
	global $mshop_address;

	// $mshop_address->mshop_actions->remove_class_actions('show_user_profile', 'WC_Admin_Profile', 'add_customer_meta_fields');
	// $mshop_address->mshop_actions->remove_class_actions('edit_user_profile', 'WC_Admin_Profile', 'add_customer_meta_fields');
	// $mshop_address->mshop_actions->remove_class_actions('personal_options_update', 'WC_Admin_Profile', 'save_customer_meta_fields');
	// $mshop_address->mshop_actions->remove_class_actions('edit_user_profile_update', 'WC_Admin_Profile', 'save_customer_meta_fields');
    // add_action( 'show_user_profile', 'mshop_customer_meta_fields' );
    // add_action( 'edit_user_profile', 'mshop_customer_meta_fields' );
    // add_action( 'personal_options_update', 'mshop_save_customer_meta_fields' );
    // add_action( 'edit_user_profile_update', 'mshop_save_customer_meta_fields' );
}

function mshop_get_customer_meta_fields() {
    $show_fields = array(
        'billing' => array(
            'title' => __( 'Customer Billing Address', 'woocommerce' ),
            'fields' => array(
                'billing_first_name' => array(
                        'id' => 'billing_first_name',
                        'label' => __( '이름', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    ),
                'mshop_billing_address' => array(
                        'id' => 'mshop_billing_address',
                        'label' => '주소',
                        'class' => array(),
                        'type' => 'mshop_address'
                    ),
                'billing_phone' => array(
                        'id' => 'billing_phone',
                        'label' => __( 'Telephone', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    ),
                'billing_email' => array(
                        'id' => 'billing_email',
                        'label' => __( 'Email', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    )
            )
        ),
        'shipping' => array(
            'title' => __( 'Customer Shipping Address', 'woocommerce' ),
            'fields' => array(
                'shipping_first_name' => array(
                        'id' => 'shipping_first_name',
                        'label' => __( '이름', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    ),
                'mshop_shipping_address' => array(
                        'id' => 'mshop_shipping_address',
                        'label' => '주소',
                        'class' => array(),
                        'type' => 'mshop_address'
                    ),
                'shipping_phone' => array(
                        'id' => 'shipping_phone',
                        'label' => __( 'Telephone', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    ),
                'shipping_email' => array(
                        'id' => 'shipping_email',
                        'label' => __( 'Email', 'woocommerce' ),
                        'type'  => 'text',
                        'description' => ''
                    )
            )
        )
    );
    return $show_fields;
}

function mshop_customer_meta_fields( $user ) {
    if ( ! current_user_can( 'manage_woocommerce' ) )
        return;

    $show_fields = mshop_get_customer_meta_fields();

    foreach( $show_fields as $fieldkey => $fieldset ) :
        ?>
        <h3><?php echo $fieldset['title']; ?></h3>
        <table class="form-table" style="margin-bottom:50px;">
            <?php
            foreach( $fieldset['fields'] as $key => $field ) :
                switch($field['type']) :
                    case 'text' :
                        ?>
                        <tr>
                            <th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
                            <td>
                                <input type="text" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( get_user_meta( $user->ID, $key, true ) ); ?>" class="regular-text" /><br/>
                                <span class="description"><?php echo wp_kses_post( $field['description'] ); ?></span>
                            </td>
                        </tr>
                        <?php
                        break;
                    case 'mshop_address' :
                        echo '<tr><th><label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] ) . '</label></th>';
                        echo '<td>';
                        echo generate_form_field_mshop_address($user->ID, $field);
                        echo '</td></tr>';
                        break;
                endswitch;
            endforeach;
            ?>
        </table>
        <?php
    endforeach;
}

function mshop_save_customer_meta_fields( $user_id ) {
    if ( ! current_user_can( 'manage_woocommerce' ) )
        return $columns;

    $save_fields = mshop_get_customer_meta_fields();

    foreach( $save_fields as $fieldkey => $fieldset )
        foreach( $fieldset['fields'] as $key => $field )
            switch ($field['type']) :
                case 'text' :
                    if ( isset( $_POST[ $key ] ) )
                        update_user_meta( $user_id, $key, woocommerce_clean( $_POST[ $key ] ) );
                    break;
                case 'mshop_address' :
                    update_customer_address($user_id, $fieldkey);
                    break;
            endswitch;
}
