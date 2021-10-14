<?php
/**
 * Get products that related to learnpress course
 * @since   1.0.0
 * @since   1.0.3           Add conditional check to product post status
 * @param   integer         $check_course_id    (Optional) ID of course
 * @return  array|false     Will return false if there is no product for given course id or no related product
 */
function sejowoolp_get_products( $check_course_id = 0 ) {

    global $wpdb;

    $data    = array();
    $results = $wpdb->get_results(
                "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key LIKE '_select_course'"
               );

    foreach( (array) $results as $row ) :

        $product_id = (int) $row->post_id;
        $course_id  = (int) $row->meta_value;
        $product    = get_post( $product_id );

        if( !isset( $data[$course_id] ) && 'publish' === $product->post_status ) :
            $data[$course_id] = array();
        endif;

        $data[$course_id][] = $product_id;

    endforeach;

    // check if there is related product to giver course ID
    if( 0 < $check_course_id ) :
        return ( !isset( $data[$check_course_id] ) ) ? false : $data[$check_course_id];
    endif;

    // return false if there is no related product
    if( 0 === count( $data ) ) :
        return false;
    endif;

    return $data;

}
?>