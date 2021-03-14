<div>
	<cx-vui-switcher
		label="<?php _e( 'Use custom labels', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Rewrite check-in/check-out calendar field labels', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="advancedSettings.use_custom_labels"
		@input="updateSetting( $event, 'use_custom_labels' )"
	></cx-vui-switcher>
	<cx-vui-input
		label="<?php _e( 'Excluded dates', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Tooltip text for already booked dates. Default: Sold out', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_booked"
		@on-input-change="updateSetting( $event.target.value, 'labels_booked' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Before selected dates', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Text before selected dates range. Default: Choosed', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_selected"
		@on-input-change="updateSetting( $event.target.value, 'labels_selected' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( '`Nights` text', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Text after nights number. Default: Nights', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_nights"
		@on-input-change="updateSetting( $event.target.value, 'labels_nights' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( '`Days` text', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Text after days number. Default: Days', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_days"
		@on-input-change="updateSetting( $event.target.value, 'labels_days' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Monday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Monday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_1"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_1' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Tuesday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Tuesday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_2"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_2' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Wednesday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Wednesday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_3"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_3' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Thursday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Thursday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_4"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_4' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Friday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Friday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_5"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_5' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Saturday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Saturday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_6"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_6' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Sunday', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label/translation of Sunday', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_week_7"
		@on-input-change="updateSetting( $event.target.value, 'labels_week_7' )"
	></cx-vui-input>
	<cx-vui-textarea
		label="<?php _e( 'Month names', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Comma-separated list of month names. E.g. January, February, March, ...', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_month_name"
		@on-input-change="updateSetting( $event.target.value, 'labels_month_name' )"
	></cx-vui-textarea>
	<cx-vui-input
		label="<?php _e( 'Past text', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label for past dates. Default: Past', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_past"
		@on-input-change="updateSetting( $event.target.value, 'labels_past' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Previous text', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Label for previous dates. Default: Past', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_previous"
		@on-input-change="updateSetting( $event.target.value, 'labels_previous' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Previous week', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Previous week text', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_prev_week"
		@on-input-change="updateSetting( $event.target.value, 'labels_prev_week' )"
	></cx-vui-input>
	<cx-vui-input
		label="<?php _e( 'Previous month', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Previous month text', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.labels_prev_month"
		@on-input-change="updateSetting( $event.target.value, 'labels_prev_month' )"
	></cx-vui-input>
</div>