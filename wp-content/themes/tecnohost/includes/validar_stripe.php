<?php
/**
 * Created by PhpStorm.
 * User: DESAR01
 * Date: 24/10/2022
 * Time: 1:47 PM
 */

use AutomateWoo\RuleQuickFilters\Clauses\SetClause;

class Validar_stripe_order extends AutomateWoo\Rules\Rule {

    /** @var string - can be string|number|object|select */
    public $type = 'string';

    /** @var string - (required) choose which data item this rule will apply to */
    public $data_item = 'order';

    /**
     * Init
     */
    function init() {

        // the title for your rule
        $this->title = __( 'Order - Validar Stripe', 'automatewoo' );

        // grouping in the admin list
        $this->group = __( 'Personalizado', 'automatewoo' );

        // compare type is the middle select field in the rule list
        // you can define any options here but this is a basic true/false example
        $this->compare_types = [
            'is' => __( 'is', 'automatewoo' ),
            'is_not' => __( 'is not', 'automatewoo' )
        ];
    }


    /**
     * Validates the rule based on options set by a workflow
     * The $data_item passed will already be validated
     * @param $data_item
     * @param $compare
     * @param $expected_value
     * @return bool
     */
    function validate( $data_item, $compare, $expected_value ) {
        $args = array(
            'order_id' => $data_item->get_id(),
        );

        $metodo_de_pago = get_post_meta($data_item->get_id(),"_payment_method_title", true);
        $stripe_intent = get_post_meta($data_item->get_id(),"_stripe_intent_id", true);
        $notes = wc_get_order_notes( $args );
        $validar_stripe_id = strlen($stripe_intent);
        if($metodo_de_pago == "Tarjeta de crédito (Stripe)" || $validar_stripe_id != 0){
            return true;
        }

        return false;
    }

}

return new Validar_stripe_order();