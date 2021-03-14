<?php
namespace JET_APB\DB;

/**
 * Database manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Base DB class
 */
abstract class Base {

	/**
	 * Check if booking DB table already exists
	 *
	 * @var bool
	 */
	public $table_exists = null;

	/**
	 * Return format flag;
	 *
	 * @var null
	 */
	public $format_flag = null;

	/**
	 * Stores latest queried result to use it
	 *
	 * @var null
	 */
	public $latest_result = null;

	/**
	 *
	 */
	public $defaults = array();

	/**
	 *
	 */
	public $queried_booking = false;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		if ( ! empty( $_GET['jet_apb_install_table'] ) ) {
			add_action( 'init', array( $this, 'install_table' ) );
		}

	}

	/**
	 * Returns table name
	 * @return [type] [description]
	 */
	abstract public function table();

	/**
	 * Insert booking
	 *
	 * @param  array  $booking [description]
	 * @return [type]          [description]
	 */
	public function insert( $data = array() ) {

		if ( ! empty( $this->defaults ) ) {
			foreach ( $this->defaults as $default_key => $default_value ) {
				if ( ! isset( $data[ $default_key ] ) ) {
					$data[ $default_key ] = $default_value;
				}
			}
		}

		$inserted = $this->wpdb()->insert( $this->table(), $data );

		if ( $inserted ) {
			return $this->wpdb()->insert_id;
		} else {
			return false;
		}
	}

	/**
	 * Update appointment info
	 *
	 * @param  array  $new_data [description]
	 * @param  array  $where    [description]
	 * @return [type]           [description]
	 */
	public function update( $new_data = array(), $where = array() ) {

		if ( ! empty( $this->defaults ) ) {
			foreach ( $this->defaults as $default_key => $default_value ) {
				if ( ! isset( $data[ $default_key ] ) ) {
					$data[ $default_key ] = $default_value;
				}
			}
		}

		$this->wpdb()->update( $this->table(), $new_data, $where );

	}

	/**
	 * Delete column
	 * @return [type] [description]
	 */
	public function delete( $where = array() ) {
		$this->wpdb()->delete( $this->table(), $where );
	}

	/**
	 * Check if booking table alredy exists
	 *
	 * @return boolean [description]
	 */
	public function is_table_exists() {

		if ( null !== $this->table_exists ) {
			return $this->table_exists;
		}

		$table = $this->table();

		if ( $table === $this->wpdb()->get_var( "SHOW TABLES LIKE '$table'" ) ) {
			$this->table_exists = true;
		} else {
			$this->table_exists = false;
		}

		return $this->table_exists;
	}

	/**
	 * Try to recreate DB table by request
	 *
	 * @return void
	 */
	public function install_table() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->create_table();

	}

	/**
	 * Returns WPDB instance
	 * @return [type] [description]
	 */
	public function wpdb() {
		global $wpdb;
		return $wpdb;
	}

	/**
	 * Create DB table for apartment units
	 *
	 * @return [type] [description]
	 */
	public function create_table( $delete_if_exists = false ) {

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		}

		if ( $delete_if_exists && $this->is_table_exists() ) {
			$table = $this->table();
			$this->wpdb()->query( "DROP TABLE $table;" );
		}

		$sql = $this->get_table_schema();

		dbDelta( $sql );

	}

	/**
	 * Insert new columns into existing bookings table
	 *
	 * @param  [type] $columns [description]
	 * @return [type]          [description]
	 */
	public function insert_table_columns( $columns = array() ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$table          = $this->table();
		$columns_schema = '';

		foreach ( $columns as $column ) {
			$columns_schema .= $column . ' text,';
		}

		$columns_schema = rtrim( $columns_schema, ',' );

		$sql = "ALTER TABLE $table
			ADD $columns_schema;";

		$this->wpdb()->query( $sql );

	}

	/**
	 * Check if booking DB column is exists
	 *
	 * @return [type] [description]
	 */
	public function column_exists( $column ) {

		$table = $this->table();
		return $this->wpdb()->query( "SHOW COLUMNS FROM `$table` LIKE '$column'" );

	}

	/**
	 * Delete columns into existing bookings table
	 *
	 * @param  [type] $columns [description]
	 * @return [type]          [description]
	 */
	public function delete_table_columns( $columns ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$table          = $this->table();
		$columns_schema = '';

		foreach ( $columns as $column ) {
			$columns_schema .= $column . ',';
		}

		$columns_schema = rtrim( $columns_schema, ',' );

		$sql = "ALTER TABLE $table
			DROP COLUMN $columns_schema;";

		$this->wpdb()->query( $sql );

	}

	/**
	 * Add nested query arguments
	 *
	 * @param  [type]  $key    [description]
	 * @param  [type]  $value  [description]
	 * @param  boolean $format [description]
	 * @return [type]          [description]
	 */
	public function get_sub_query( $key, $value, $format = false ) {

		$query = '';
		$glue  = '';

		if ( ! $format ) {

			if ( false !== strpos( $key, '!' ) ) {
				$format = '`%1$s` != \'%2$s\'';
				$key    = ltrim( $key, '!' );
			} else {
				$format = '`%1$s` = \'%2$s\'';
			}

		}

		foreach ( $value as $child ) {
			$query .= $glue;
			$query .= sprintf( $format, esc_sql( $key ), esc_sql( $child ) );
			$glue   = ' OR ';
		}

		return $query;

	}

	/**
	 * Return count of queried items
	 *
	 * @return [type] [description]
	 */
	public function count( $args = array(), $rel = 'AND' ) {

		$table = $this->table();

		$query = "SELECT count(*) FROM $table";

		if ( ! $rel ) {
			$rel = 'AND';
		}

		if ( isset( $args['after'] ) ) {
			$after = $args['after'];
			unset( $args['after'] );
			$args['ID>'] = $after;
		}

		if ( isset( $args['before'] ) ) {
			$before = $args['before'];
			unset( $args['before'] );
			$args['ID<'] = $before;
		}

		$query .= $this->add_where_args( $args, $rel );

		return $this->wpdb()->get_var( $query );

	}

	/**
	 * Add where arguments to query
	 *
	 * @param array  $args [description]
	 * @param string $rel  [description]
	 */
	public function add_where_args( $args = array(), $rel = 'AND' ) {

		$query      = '';
		$multi_args = false;

		if ( ! empty( $args ) ) {

			$query  .= ' WHERE ';
			$glue    = '';
			$search  = array();
			$props   = array();

			if ( count( $args ) > 1 ) {
				$multi_args = true;
			}

			foreach ( $args as $key => $value ) {

				$format = '`%1$s` = \'%2$s\'';

				$query .= $glue;

				if ( false !== strpos( $key, '!' ) ) {
					$key    = ltrim( $key, '!' );
					$format = '`%1$s` != \'%2$s\'';
				} elseif ( false !== strpos( $key, '>=' ) ) {
					$key    = rtrim( $key, '>=' );
					$format = '`%1$s` >= %2$d';
				} elseif ( false !== strpos( $key, '>' ) ) {
					$key    = rtrim( $key, '>' );
					$format = '`%1$s` > %2$d';
				} elseif ( false !== strpos( $key, '<=' ) ) {
					$key    = rtrim( $key, '<=' );
					$format = '`%1$s` <= %2$d';
				} elseif ( false !== strpos( $key, '<' ) ) {
					$key    = rtrim( $key, '<' );
					$format = '`%1$s` < %2$d';
				}

				if ( is_array( $value ) ) {
					$query .= sprintf( '( %s )', $this->get_sub_query( $key, $value, $format ) );
				} else {
					$query .= sprintf( $format, esc_sql( $key ), esc_sql( $value ) );
				}

				$glue = ' ' . $rel . ' ';

			}

		}

		return $query;

	}

	/**
	 * Add order arguments to query
	 *
	 * @param array $args [description]
	 */
	public function add_order_args( $order = array() ) {

		$query = '';

		if ( ! empty( $order['orderby'] ) ) {

			$orderby = $order['orderby'];
			$order   = ! empty( $order['order'] ) ? $order['order'] : 'desc';
			$order   = strtoupper( $order );
			$query  .= " ORDER BY $orderby $order";

		}

		return $query;

	}

	/**
	 * Clear table data
	 * @return [type] [description]
	 */
	public function clear() {
		$table = $this->table();
		$this->wpdb()->query( "TRUNCATE `$table`;" );
	}

	/**
	 * Set current format flag
	 *
	 * @param [type] $flag [description]
	 */
	public function set_format_flag( $flag = null ) {
		$this->format_flag = $flag;
	}

	/**
	 * Get current format flag
	 *
	 */
	public function get_format_flag() {
		if ( $this->format_flag ) {
			return $this->format_flag;
		} else {
			return ARRAY_A;
		}
	}

	/**
	 * Query data from db table
	 *
	 * @return [type] [description]
	 */
	public function query( $args = array(), $limit = 0, $offset = 0, $order = array(), $rel = 'AND' ) {

		$table = $this->table();

		$query = "SELECT * FROM $table";

		if ( ! $rel ) {
			$rel = 'AND';
		}

		if ( isset( $args['after'] ) ) {
			$after = $args['after'];
			unset( $args['after'] );
			$args['ID>'] = $after;
		}

		if ( isset( $args['before'] ) ) {
			$before = $args['before'];
			unset( $args['before'] );
			$args['ID<'] = $before;
		}

		$by_user = ! empty( $args['by_user'] ) ? $args['by_user'] : false;

		if ( isset( $args['by_user'] ) ) {
			unset( $args['by_user'] );
		}

		$query .= $this->add_where_args( $args, $rel );

		if ( $by_user ) {
			$uid  = $by_user;
			$user = get_user_by( 'ID', $uid );

			if ( $user ) {

				if ( ! empty( $args ) ) {
					$st = $rel;
				} else {
					$st = 'WHERE';
				}

				$query .= ' ' . $st . ' `user_id` = ' . $uid;
			}

		}

		$query .= $this->add_order_args( $order );

		if ( intval( $limit ) > 0 ) {
			$limit  = absint( $limit );
			$offset = absint( $offset );
			$query .= " LIMIT $offset, $limit";
		}

		$raw = $this->wpdb()->get_results( $query, $this->get_format_flag() );

		return $raw;

	}

}
