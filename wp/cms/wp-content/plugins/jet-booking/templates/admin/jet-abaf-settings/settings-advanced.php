<div>
	<cx-vui-switcher
		label="<?php _e( 'Hide DB columns manager', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Check this to hide columns manager option. This allow to prevent accidental DB changes.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="advancedSettings.hide_columns_manager"
		@input="updateSetting( $event, 'hide_columns_manager' )"
	></cx-vui-switcher>
	<cx-vui-switcher
		label="<?php _e( 'Enable iCal synchronization', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Check this to allow export your bookings into iCal format and synchronize all your data with external calendars in iCal format.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="advancedSettings.ical_synch"
		@input="updateSetting( $event, 'ical_synch' )"
	></cx-vui-switcher>
	<cx-vui-select
		label="<?php _e( 'Calendar synch interval', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Select interval between calendars synchronizing.', 'jet-appointments-booking' ); ?>"
		:options-list="cronSchedules"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="advancedSettings.synch_interval"
		@input="updateSetting( $event, 'synch_interval' )"
		v-if="advancedSettings.ical_synch"
	></cx-vui-select>
	<cx-vui-component-wrapper
		label="<?php _e( 'Calendar synch start', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Start calendar synchronizing from this time.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth', 'schedule-time' ]"
		v-if="advancedSettings.ical_synch"
	>
		<cx-vui-select
			:options-list="getInterval( 23 )"
			:value="advancedSettings.synch_interval_hours"
			@input="updateSetting( $event, 'synch_interval_hours' )"
			:prevent-wrap="true"
		></cx-vui-select>
		<span class="jet-schedule-delimiter">:</span>
		<cx-vui-select
			:options-list="getInterval( 59 )"
			:value="advancedSettings.synch_interval_mins"
			@input="updateSetting( $event, 'synch_interval_mins' )"
			:prevent-wrap="true"
		></cx-vui-select>
		<span class="jet-schedule-delimiter">HH:MM</span>
	</cx-vui-component-wrapper>
	<cx-vui-switcher
		label="<?php _e( 'Hide Set Up Wizard', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Check this to hide Set Up page to avoid unnecessary plugin resets', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="advancedSettings.hide_setup"
		@input="updateSetting( $event, 'hide_setup' )"
	></cx-vui-switcher>
</div>