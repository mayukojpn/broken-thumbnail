<?php
/**
 * Plugin Name: Quick Broken Thumbnail Checker
 * Author: Mayo Moriyama
 * @package Broken_Thumbnail
 */
class Broken_Thumbnail {
	/**
	 * Holds the values to be used in the fields callbacks
	 */
	private $options;

	/**
	 * Start up
	 */
	public function __construct()
	{
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
	}

	/**
	 * Add options page
	 */
	public function add_plugin_page()
	{
			// This page will be under "Settings"
			add_options_page(
					'Settings Admin',
					'Broken Thumbnail Checker',
					'manage_options',
					'broken-thumbnail-admin',
					array( $this, 'create_admin_page' )
			);
	}

	/**
	 * Options page callback
	 */
	public function create_admin_page()
	{
			?>
			<div class="wrap">
					<h2>Broken Thumbnail Checker</h2>
					<?php
					// The Query
					$args = array(
						'post_type'      => 'post',
						'post_status'    => 'publish',
						'posts_per_page' => 1000
					);
					$the_query = new WP_Query( $args );

					// The Loop
					if ( $the_query->have_posts() ) {
						echo '<ul>';
						while ( $the_query->have_posts() ) {
							$the_query->the_post();
							$thumbnail = 'empty';
							if ( has_post_thumbnail( get_the_ID() ) ) {

								$thumbnail = get_post_thumbnail_id( get_the_ID() );
								if ( empty( get_the_post_thumbnail( get_the_ID() ) ) ) {

									delete_post_meta( get_the_ID(), '_thumbnail_id' );
									$thumbnail = 'Removed broken thumbnail:' . $thumbnail;

								}
							}
							echo '<li>' . get_the_ID() . ' -> ' . $thumbnail . '</li>';
						}
						echo '</ul>';

						wp_reset_postdata();
					}
					?>
			</div>
			<?php
	}

}

if( is_admin() )
	$my_settings_page = new Broken_Thumbnail();
