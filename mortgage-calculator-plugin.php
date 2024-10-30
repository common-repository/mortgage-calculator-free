<?php
/*
Plugin Name: Mortgage Calculator Free
Description: Mortgage calculator plugin for Wordpress. Bloat-free, can be embedded as a widget or using a shortcode.
Version: 1.1.0
Author: Overdraft Apps
Author URI: https://overdraftapps.com/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  mortgage-calculator-plugin
Domain Path:  /languages
*/

// Make sure the plugin is accessed through the appropriate channels
defined('ABSPATH') || die;

/*-----------------------------------------------------------------------------------*/
/* Register Widget */
/*-----------------------------------------------------------------------------------*/

class mortgage_calculator_plugin extends WP_Widget {
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		$widget_options = array(
			'classname' => 'mortgage_calculator_plugin',
			'description' => __( 'Display a mortgage calculator.', 'mortgage-calculator-plugin' )
		);
		
		// Pass the options to WP_Widget to create the widget.
		parent::__construct( 'mortgage_calculator_plugin', __( 'A Mortgage Calculator Widget', 'mortgage-calculator-plugin' ) );
	}
	
	/**
	 * Build the widget settings form.
	 *
	 * Responsible for creating the elements of the widget settings form.
	 */
   function form($instance) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$width = isset( $instance['width'] ) ? esc_attr( $instance['width'] ) : '';
		$submitbackground = isset( $instance['submitbackground'] ) ? esc_attr( $instance['submitbackground'] ) : '';
		$resetbackground = isset( $instance['resetbackground'] ) ? esc_attr( $instance['resetbackground'] ) : '';
		$inputradius = isset( $instance['inputradius'] ) ? esc_attr( $instance['inputradius'] ) : '';
		$resultcolor = isset( $instance['resultcolor'] ) ? esc_attr( $instance['resultcolor'] ) : '';
		$resultpadding = isset( $instance['resultpadding'] ) ? esc_attr( $instance['resultpadding'] ) : '';
		?>
		<p>
		   <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('title'); ?>"  value="<?php echo $title; ?>" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" placeholder="<?php _e('Title','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Calculator Width:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('width'); ?>"  value="<?php echo $width; ?>" class="widefat" id="<?php echo $this->get_field_id('width'); ?>" placeholder="<?php _e('Width e.g. 400px','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('submitbackground'); ?>"><?php _e('Calculate Button Background:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('submitbackground'); ?>"  value="<?php echo $submitbackground; ?>" class="widefat" id="<?php echo $this->get_field_id('submitbackground'); ?>" placeholder="<?php _e('Background e.g. #545454','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('resetbackground'); ?>"><?php _e('Reset Button Background:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('resetbackground'); ?>"  value="<?php echo $resetbackground; ?>" class="widefat" id="<?php echo $this->get_field_id('resetbackground'); ?>" placeholder="<?php _e('Background e.g. #aaaaaa','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('inputradius'); ?>"><?php _e('Input Radius:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('inputradius'); ?>"  value="<?php echo $inputradius; ?>" class="widefat" id="<?php echo $this->get_field_id('inputradius'); ?>" placeholder="<?php _e('Radius e.g. 5px','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('resultcolor'); ?>"><?php _e('Results Color:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('resultcolor'); ?>"  value="<?php echo $resultcolor; ?>" class="widefat" id="<?php echo $this->get_field_id('resultcolor'); ?>" placeholder="<?php _e('Color e.g. #f2f2f2','mortgage-calculator-plugin'); ?>" />
		   
		   <label for="<?php echo $this->get_field_id('resultpadding'); ?>"><?php _e('Results Padding:','mortgage-calculator-plugin'); ?></label>
		   <input type="text" name="<?php echo $this->get_field_name('resultpadding'); ?>"  value="<?php echo $resultpadding; ?>" class="widefat" id="<?php echo $this->get_field_id('resultpadding'); ?>" placeholder="<?php _e('Padding e.g. 10px','mortgage-calculator-plugin'); ?>" />
		</p>
		<?php
	}
	
	/**
	 * A method to save the settings.
	 */
	function update( $new_instance, $old_instance )
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['width'] = strip_tags( $new_instance['width'] );
		$instance['submitbackground'] = strip_tags( $new_instance['submitbackground'] );
		$instance['resetbackground'] = strip_tags( $new_instance['resetbackground'] );
		$instance['inputradius'] = strip_tags( $new_instance['inputradius'] );
		$instance['resultcolor'] = strip_tags( $new_instance['resultcolor'] );
		$instance['resultpadding'] = strip_tags( $new_instance['resultpadding'] );
		
		return $instance;
	}
	
	/**
	 * A method to display the widget on the front end.
	 */
	function widget($args, $instance) {  
		extract( $args );
		$widget_id = $this->id;    
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		$currency = '$';
		$width = $instance['width'];
		$submitbackground = $instance['submitbackground'];
		$resetbackground = $instance['resetbackground'];
		$inputradius = $instance['inputradius'];
		$resultcolor = $instance['resultcolor'];
		$resultpadding = $instance['resultpadding'];
		
		echo $before_widget; 
		if ($title) { echo $before_title . $title . $after_title; }
		
		global $ct_options;
		if(isset($width) || isset($submitbackground) || isset($resetbackground) || isset($inputradius) || isset($resultcolor) || isset($resultpadding)){
			echo '<style type="text/css">';
			if($width){ echo '.' . $widget_id . '{ max-width: ' . $width . ' !important}' ; }
			if($submitbackground){ echo '.' . $widget_id . ' input.calculateMort, .' . $widget_id . ' input.calculateMort:focus, .' . $widget_id . ' input.calculateMort:active { background: ' . $submitbackground . ' !important}' ; }
			if($resetbackground){ echo '.' . $widget_id . ' input.reset, .' . $widget_id . ' input.reset:focus, .' . $widget_id . ' input.reset:active{ background: ' . $resetbackground . ' !important}' ; }
			if($inputradius){ echo '.' . $widget_id . ' .mc-form-control, .' . $widget_id . ' input[type="text"], .' . $widget_id . ' input[type="number"], .' . $widget_id . ' input.btn, .' . $widget_id . ' input.btn:focus, .' . $widget_id . ' input.btn:active { border-radius: ' . $inputradius . ' !important}' ; }
			if($resultcolor){ echo '.' . $widget_id . ' p.mpayment{ color: ' . $resultcolor . ' !important}' ; }
			if($resultpadding){ echo '.' . $widget_id . ' p.mpayment{ padding: ' . $resultpadding . ' !important}' ; }
			echo '</style>';
		}
		?>	 
		<!-- Wordpres Plugin by https://overdraftapps.com -->
		<form class="cleanslate mortgage_calculator_form <?php echo $widget_id?>">
			<div class="mc-form-group">
			  <input type="text" name="mcpSPrice" maxlength="14" class="mcpSPrice text mc-form-control" placeholder="<?php _e('Sale price', 'mortgage-calculator-plugin'); ?> (<?php echo $currency; ?>)" />
			</div>
			<div class="mc-form-group">
			  <input type="number" max="100" name="mcpIRate" class="mcpIRate text mc-form-control" placeholder="<?php _e('Interest Rate (%)', 'mortgage-calculator-plugin'); ?>"/>
			</div>
			<div class="mc-form-group">
			  <input type="number" max="60" name="mcpTerm" class="mcpTerm text mc-form-control" placeholder="<?php _e('Term (years)', 'mortgage-calculator-plugin'); ?>" />
			</div>
			<div class="mc-form-group">
			  <input type="text" name="mcpDPayment" maxlength="14" class="mcpDPayment text mc-form-control" placeholder="<?php _e('Down payment', 'mortgage-calculator-plugin'); ?> (<?php echo $currency; ?>)" />
			</div>
			<div class="mc-form-group">			  
			  <input class="btn btn-default calculateMort" type="submit" value="<?php _e('Calculate', 'mortgage-calculator-plugin'); ?>" onclick="return false">
			</div>
			<div class="mc-form-group">
			  <input class="btn reset" type="button" value="Reset" onClick="this.form.reset();jQuery(this).closest('.mortgage_calculator_form').find('.mpayment').addClass('hidden');" />
			</div>
			<div class="mc-form-group">
			  <div class="mpayment hidden"><?php _e('Monthly Payment:', 'mortgage-calculator-plugin'); ?> <strong><?php echo $currency; ?> <span class="mcp_Payment"></span></strong>
				</div>
			</div>
		</form>
		<?php echo $after_widget; 
   }	
} 

// Call the hook to register the widget.
add_action( 'widgets_init', 'mortgage_calculator_plugin_register_widget' );

/**
 * Callback function to register the widget.
 */
function mortgage_calculator_plugin_register_widget() {
	register_widget( 'mortgage_calculator_plugin' );
}

/*-----------------------------------------------------------------------------------*/
/* Include CSS */
/*-----------------------------------------------------------------------------------*/
 
function mortgage_calculator_plugin_css() {		
	wp_enqueue_style( 'mortgage_calculator_plugin_cleanslate', plugins_url( 'assets/css/cleanslate.css', __FILE__ ), false, '1.0' );
	wp_enqueue_style( 'mortgage_calculator_plugin_style', plugins_url( 'assets/css/style_new.css', __FILE__ ), false, '1.0' );
}
add_action( 'wp_print_styles', 'mortgage_calculator_plugin_css' );

/*-----------------------------------------------------------------------------------*/
/* Include JS */
/*-----------------------------------------------------------------------------------*/

function mortgage_calculator_plugin_scripts() {
	wp_enqueue_script( 'mortgage_calculator_plugin_js', plugins_url( 'assets/js/calculator.js', __FILE__ ), array('jquery'), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'mortgage_calculator_plugin_scripts' );


/*-----------------------------------------------------------------------------------*/
/* Register Shortcode */
/*-----------------------------------------------------------------------------------*/

function mortgage_calculator_plugin_shortcode($atts) {
	STATIC $i = 1;
	
	$args = shortcode_atts( array(
        'title' => 'Mortgage Calculator',
		'width' => '400px',
		'submitbuttonbackground' => '',
		'resetbuttonbackground' => '',
		'inputradius' => '',
		'resultcolor' => '',
		'resultpadding' => ''
    ), $atts );
	
	$currency = '$';
	$width = $args['width'];
	$submitbackground = $args['submitbuttonbackground'];
	$resetbackground = $args['resetbuttonbackground'];
	$inputradius = $args['inputradius'];
	$resultcolor = $args['resultcolor'];
	$resultpadding = $args['resultpadding'];
	
	if(isset($width) || isset($submitbackground) || isset($resetbackground) || isset($inputradius) || isset($resultcolor) || isset($resultpadding)){
		echo '<style type="text/css">';
		if($width){ echo '.mortgage_calculator_form_' . $i . '{ max-width: ' . $width . ' !important}' ; }
		if($submitbackground){ echo '.mortgage_calculator_form_' . $i . ' input.calculateMort, .mortgage_calculator_form_' . $i . ' input.calculateMort:focus, .mortgage_calculator_form_' . $i . ' input.calculateMort:active{ background: ' . $submitbackground . ' !important}' ; }
		if($resetbackground){ echo '.mortgage_calculator_form_' . $i . ' input.reset, .mortgage_calculator_form_' . $i . ' input.reset:focus, .mortgage_calculator_form_' . $i . ' input.reset:active{ background: ' . $resetbackground . ' !important}' ; }
		if($inputradius){ echo '.mortgage_calculator_form_' . $i . ' .mc-form-control, .mortgage_calculator_form_' . $i . ' input[type="text"], .mortgage_calculator_form_' . $i . ' input[type="number"], .mortgage_calculator_form_' . $i . ' input.btn,   .mortgage_calculator_form_' . $i . ' input.btn:focus,  .mortgage_calculator_form_' . $i . ' input.btn:active{ border-radius: ' . $inputradius . ' !important}' ; }
		if($resultcolor){ echo '.mortgage_calculator_form_' . $i . ' p.mpayment{ color: ' . $resultcolor . ' !important}' ; }
		if($resultpadding){ echo '.mortgage_calculator_form_' . $i . ' p.mpayment{ padding: ' . $resultpadding . ' !important}' ; }
		echo '</style>';
	}
	ob_start();
	?>

	<!-- Wordpres Plugin by https://overdraftapps.com -->
	<form class="cleanslate mortgage_calculator_form mortgage_calculator_form_<?php echo $i?>">
		<div class="mc-form-group">
		  <input type="text" name="mcpSPrice" maxlength="14" class="mcpSPrice text mc-form-control" placeholder="<?php _e('Sale price', 'mortgage-calculator-plugin'); ?> (<?php echo $currency; ?>)" />
		</div>
		<div class="mc-form-group">
		  <input type="number" max="100" name="mcpIRate" class="mcpIRate mc-form-control" placeholder="<?php _e('Interest Rate (%)', 'mortgage-calculator-plugin'); ?>"/>
		</div>
		<div class="mc-form-group">
		  <input type="number" max="60" name="mcpTerm" class="mcpTerm mc-form-control" placeholder="<?php _e('Term (years)', 'mortgage-calculator-plugin'); ?>" />
		</div>
		<div class="mc-form-group">
		  <input type="text" name="mcpDPayment" maxlength="14" class="mcpDPayment text mc-form-control" placeholder="<?php _e('Down payment', 'mortgage-calculator-plugin'); ?> (<?php echo $currency; ?>)" />
		</div>
		<div class="mc-form-group">			  
		  <input class="btn btn-default calculateMort" type="submit" value="<?php _e('Calculate', 'mortgage-calculator-plugin'); ?>" onclick="return false">
		</div>
		<div class="mc-form-group">
		  <input class="btn reset" type="button" value="Reset" onClick="this.form.reset(); jQuery(this).closest('.mortgage_calculator_form').find('.mpayment').addClass('hidden');" />
		</div>
		<div class="mc-form-group">
		  <div class="mpayment hidden"><?php _e('Monthly Payment:', 'mortgage-calculator-plugin'); ?> <strong><?php echo $currency; ?> <span class="mcp_Payment"></span></strong>
			</div>
		</div>
	</form>
    <div class="clear"></div>
<?php $i++; return ob_get_clean(); }
add_shortcode('mortgagecalculator', 'mortgage_calculator_plugin_shortcode');
?>
