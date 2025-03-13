<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 8/05/2020
 * Time: 12:56 PM
 */
$mysqli = new mysqli("tecnohost.net", 'tecnohos_tecno', 'io=vRniDsd1y', 'tecnohos_principal');

$pedido = [1234];

//actualizar_nombre_productos_en_pedidos_viejos($pedido, $mysqli);

//actualizar_productos_pedidos($pedido, $mysqli);

$mysqli->close();

function imprimir($datos){
    echo '<pre>';
    print_r($datos);
    echo '</pre>';
}

function actualizar_nombre_productos_en_pedidos_viejos($pedido, $mysqli){
    foreach($pedido AS $key => $value){
        $sql = "SELECT
        th_woocommerce_order_items.order_item_name,
        th_woocommerce_order_items.order_item_id,
        th_posts.post_parent
        FROM
        th_postmeta
        INNER JOIN th_woocommerce_order_items ON th_woocommerce_order_items.order_id = th_postmeta.post_id
        INNER JOIN th_posts ON th_posts.ID = th_postmeta.post_id
        WHERE
        th_postmeta.post_id = " . $value . " AND
        th_postmeta.meta_key = '_billing_interval'";
        $respuesta = $mysqli->query($sql);

        foreach($respuesta->fetch_all() AS $key_1){

            $obtener_nombre_nuevo = "SELECT th_posts.post_title FROM th_woocommerce_order_itemmeta
            INNER JOIN th_posts ON th_posts.ID = th_woocommerce_order_itemmeta.meta_value
            WHERE `order_item_id` = '".$key_1[1]."' AND `meta_key` = '_variation_id'";
            $nombre_producto = $mysqli->query($obtener_nombre_nuevo)->fetch_assoc();
            $actualizar = "UPDATE `th_woocommerce_order_items` SET `order_item_name`='".$nombre_producto['post_title']."' WHERE (`order_item_id`='".$key_1[1]."') LIMIT 1";
            $mysqli->query($actualizar);
            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el nombre del producto producto en la suscripción query:");
                imprimir($actualizar);
            } else {

            }

            $obtener_nombre_nuevo_pedido = "SELECT th_posts.post_title, th_woocommerce_order_items.order_item_id FROM th_woocommerce_order_items
            INNER JOIN th_woocommerce_order_itemmeta ON th_woocommerce_order_items.order_item_id = th_woocommerce_order_itemmeta.order_item_id
            INNER JOIN th_posts ON th_posts.ID = th_woocommerce_order_itemmeta.meta_value
            WHERE th_woocommerce_order_items.order_id = '".$key_1[2]."' AND th_woocommerce_order_itemmeta.meta_key = '_variation_id'";
            $nombre_producto_pedido = $mysqli->query($obtener_nombre_nuevo_pedido)->fetch_assoc();

            $actualizar_pedido = "UPDATE `th_woocommerce_order_items` SET `order_item_name`='".$nombre_producto_pedido['post_title']."' WHERE (`order_item_id`='".$nombre_producto_pedido['order_item_id']."') LIMIT 1";
            $mysqli->query($actualizar_pedido);
            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el nombre del producto producto en la pedido query:");
                imprimir($actualizar);
            } else {

            }
        }
    }
}

function actualizar_productos_pedidos($pedido, $mysqli){
    foreach($pedido as $key => $value ) {

        $sql = "SELECT
        th_postmeta.meta_value AS Intervalo,
        th_woocommerce_order_items.order_item_name,
        th_woocommerce_order_items.order_item_id,
        th_posts.post_parent
        FROM
        th_postmeta
        INNER JOIN th_woocommerce_order_items ON th_woocommerce_order_items.order_id = th_postmeta.post_id
        INNER JOIN th_posts ON th_posts.ID = th_postmeta.post_id
        WHERE
        th_postmeta.post_id = " . $value . " AND
        th_postmeta.meta_key = '_billing_interval'";

        $respuesta = $mysqli->query($sql)->fetch_assoc();

        $nombre_nuevo_dom = $respuesta['order_item_name'] . " - " . $respuesta['Intervalo'] . "%";

        $sql_nuevo_Dom = "SELECT th_posts.ID, th_posts.post_parent FROM `th_posts` WHERE `post_title` LIKE '%" . $nombre_nuevo_dom . "'";

        $respuesta_produc_n = $mysqli->query($sql_nuevo_Dom);

        if ($respuesta_produc_n->num_rows == 0) {
            imprimir("Falla al consultar el producto");
            imprimir($nombre_nuevo_dom);
            imprimir("Del pedido #" . $value);
        } else {

            $ordem_item_id_padre = "SELECT th_woocommerce_order_items.order_item_id FROM `th_woocommerce_order_items`
        WHERE th_woocommerce_order_items.order_id = '" . $respuesta['post_parent'] . "' AND
        th_woocommerce_order_items.order_item_name = '".$respuesta['order_item_name']."'";

            $id_pedido = $mysqli->query($ordem_item_id_padre)->fetch_assoc();

            $consulta_n = $respuesta_produc_n->fetch_assoc();

            $_product_id = $consulta_n['post_parent'];
            $_variation_id = $consulta_n['ID'];

            $actualizar_product_id_suscripcion = "UPDATE `th_woocommerce_order_itemmeta`
        SET `meta_value` = '" . $_product_id . "'
        WHERE
            `order_item_id` = '" . $respuesta['order_item_id'] . "'
        AND `meta_key` = '_product_id'
        LIMIT 1";

            $mysqli->query($actualizar_product_id_suscripcion);

            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el producto " . $_product_id);
                imprimir($actualizar_product_id_suscripcion);
                imprimir("Del pedido #" . $value);
            } else {
                imprimir("Todo bien con la suscripción: " . $value);
            }

            $actualizar_product_id_pedido = "UPDATE `th_woocommerce_order_itemmeta`
        SET `meta_value` = '" . $_product_id . "'
        WHERE
            `order_item_id` = '" . $id_pedido['order_item_id'] . "'
        AND `meta_key` = '_product_id'
        LIMIT 1";

            $mysqli->query($actualizar_product_id_pedido);

            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el producto " . $_product_id);
                imprimir($actualizar_product_id_pedido);
                imprimir("Del pedido #" . $value);
            } else {
                imprimir("Todo bien con la pedido: " . $respuesta['post_parent']);
            }

            $actualizar_variable_id_suscripcion = "UPDATE `th_woocommerce_order_itemmeta`
        SET `meta_value` = '" . $_variation_id . "'
        WHERE
            `order_item_id` = '" . $respuesta['order_item_id'] . "'
        AND `meta_key` = '_variation_id'
        LIMIT 1";

            $mysqli->query($actualizar_variable_id_suscripcion);

            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el producto " . $_product_id);
                imprimir($actualizar_variable_id_suscripcion);
                imprimir("Del pedido #" . $value);
            } else {
                imprimir("Todo bien con la suscripción: " . $value);
            }

            $actualizar_variable_id_pedido = "UPDATE `th_woocommerce_order_itemmeta`
        SET `meta_value` = '" . $_variation_id . "'
        WHERE
            `order_item_id` = '" . $id_pedido['order_item_id'] . "'
        AND `meta_key` = '_variation_id'
        LIMIT 1";

            $mysqli->query($actualizar_variable_id_pedido);

            if ($mysqli->affected_rows == 0) {
                imprimir("Falla al actualizar el producto " . $_product_id);
                imprimir($actualizar_variable_id_pedido);
                imprimir("Del pedido #" . $value);
            } else {
                imprimir("Todo bien con la pedido: " . $respuesta['post_parent']);
            }
        }

    }
}