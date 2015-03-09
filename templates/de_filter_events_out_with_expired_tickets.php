<?php
/**
 * The purpose of this snippet is to filter the event archive (and event taxonomy archive) pages so that they exclude events
 * that have tickets no longer on sale.
 *
 *  NOTE: The query for the ticket expiry time is correct at time of posting, but at some point in the future there will be a
 *  change to how date queries are done with Event Espresso and this snippet will be out of date then.  Watch for blog posts
 *  about datetime changes.
 *
 * To Implement this code, add it to the bottom of your themes functions.php file, or add it to a site specific plugin.
 *
 */

function de_ee_tweak_event_list_exclude_ticket_expired_events_where( $SQL, WP_Query $wp_query ) {
	if ( $wp_query->is_espresso_event_archive || $wp_query->is_espresso_event_taxonomy ) {
		$SQL .= ' AND Ticket.TKT_end_date > "' . current_time( 'mysql', true ) . '"';
	}
	return $SQL;
}
add_filter( 'posts_where', 'de_ee_tweak_event_list_exclude_ticket_expired_events_where', 15, 2 );

function de_ee_tweak_event_list_exclude_ticket_expired_events_join( $SQL, $wp_query ) {
	if ( $wp_query->is_espresso_event_archive || $wp_query->is_espresso_event_taxonomy ) {

		$SQL .= ' INNER JOIN ' . EEM_Datetime_Ticket::instance()->table() . ' AS Datetime_Ticket ON ( Datetime_Ticket.DTT_ID=' . EEM_Datetime::instance()->table() . '.' . EEM_Datetime::instance()->primary_key_name() . ' ) INNER JOIN ' . EEM_Ticket::instance()->table()  . ' AS Ticket ON ( Datetime_Ticket.TKT_ID=Ticket.' . EEM_Ticket::instance()->primary_key_name() . ' ) ';
	}
	return $SQL;
}
add_filter( 'posts_join', 'de_ee_tweak_event_list_exclude_ticket_expired_events_join', 3, 2 );
