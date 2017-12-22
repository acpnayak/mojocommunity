<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="mojo_field mojo_field_<?php echo esc_attr( $type ); ?>">

	<div class="mdl-typography--subhead-color-contrast"><?php echo esc_html( $label ); ?></div>

	<div class="mdl-grid mdl-grid--no-spacing">

		<div class="mdl-cell mdl-cell--3-col">
			<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label mdl-selectfield--full-width">
				<select class="mdl-selectfield__select" name="mojo_<?php echo $key; ?>_d" id="mojo_<?php echo $key; ?>_d">
					<option value=""></option>
					<?php for ( $i = 1; $i <= 31; $i++ ) { ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					<?php } ?>
				</select>
				<label class="mdl-selectfield__label" for="mojo_<?php echo $key; ?>_d"><?php _e( 'Day', 'mojocommunity' ); ?></label>
			</div>
		</div>

		<div class="mdl-cell mdl-cell--3-col mdl-cell--1-offset">
			<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label mdl-selectfield--full-width">
				<select class="mdl-selectfield__select" name="mojo_<?php echo $key; ?>_m" id="mojo_<?php echo $key; ?>_m">
					<option value=""></option>
					<?php foreach( mojo_get_month_names() as $month_num => $month ) { ?>
					<option value="<?php echo esc_attr( $month_num ); ?>"><?php echo esc_html( $month ); ?></option>
					<?php } ?>
				</select>
				<label class="mdl-selectfield__label" for="mojo_<?php echo $key; ?>_m"><?php _e( 'Month', 'mojocommunity' ); ?></label>
			</div>
		</div>

		<div class="mdl-cell mdl-cell--4-col mdl-cell--1-offset">
			<div class="mdl-selectfield mdl-js-selectfield mdl-selectfield--floating-label mdl-selectfield--full-width">
				<select class="mdl-selectfield__select" name="mojo_<?php echo $key; ?>_y" id="mojo_<?php echo $key; ?>_y">
					<option value=""></option>
					<?php foreach( mojo_get_years() as $key => $val ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo absint( $val ); ?></option>
					<?php } ?>
				</select>
				<label class="mdl-selectfield__label" for="mojo_<?php echo $key; ?>_y"><?php _e( 'Year', 'mojocommunity' ); ?></label>
			</div>
		</div>

	</div>

</div>