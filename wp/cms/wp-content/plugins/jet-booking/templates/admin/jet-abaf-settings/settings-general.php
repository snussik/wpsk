<div>
	<cx-vui-component-wrapper
		:wrapper-css="[ 'fullwidth-control' ]"
		v-if="! generalSettings.hide_columns_manager"
	>
		<div class="cx-vui-inner-panel">
			<div class="cx-vui-subtitle"><?php _e( 'Additional table columns' ); ?></div>
			<div class="cx-vui-component__desc"><?php _e( 'You can add any columns you want to booking table. We recommend add cloumns only before table creating. When you added new columns, you need to map these columns to apopriate form field in related booking form.' ); ?></div><br>

			<cx-vui-repeater
				button-label="<?php _e( 'New DB Column', 'jet-appointments-booking' ); ?>"
				button-style="accent"
				button-size="mini"
				v-model="generalSettings.additional_columns"
				@add-new-item="addNewColumn"
			>
				<cx-vui-repeater-item
					v-for="( column, columnIndex ) in generalSettings.additional_columns"
					:title="generalSettings.additional_columns[ columnIndex ].column"
					:collapsed="isCollapsed( column )"
					:index="columnIndex"
					@clone-item="cloneColumn( $event, columnIndex )"
					@delete-item="deleteColumn( $event, columnIndex )"
					:key="'column' + columnIndex"
				>
					<cx-vui-input
						label="<?php _e( 'Column name', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Name for additional DB column', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:value="generalSettings.additional_columns[ columnIndex ].column"
						@on-input-change="setColumnProp( columnIndex, 'column', $event.target.value )"
					></cx-vui-input>
				</cx-vui-repeater-item>
			</cx-vui-repeater>
			<div style="margin: 20px 0 -5px;"><?php
				_e( '<b>Warning:</b> If you change or remove any columns, all data stored in these columns will be lost!', 'jet-booking' );
			?></div>
		</div>
	</cx-vui-component-wrapper>
	<cx-vui-select
		label="<?php _e( 'Booking orders post type', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'You can select post type related for created booking recorods. For example this could be something like `Orders`. When you selected some post type into this option, you need to specify column in Bookings database table where stored apropiate post IDs of this post type.', 'jet-appointments-booking' ); ?>"
		:options-list="postTypes"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="generalSettings.related_post_type"
		@input="updateSetting( $event, 'related_post_type' )"
	></cx-vui-select>
	<cx-vui-input
		label="<?php _e( 'Booking orders column name', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Set Booking table column where IDs of current booking order are stored. For example you created column `order_id`. On form submission you store into this column created Order ID. So if you select post type Order in previous option, meta box with booking information will be added into this post type edit page.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:value="generalSettings.related_post_type_column"
		@input="updateSetting( $event, 'related_post_type_column' )"
	></cx-vui-input>
	<cx-vui-select
		label="<?php _e( 'Booking instance post type', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'You can select post type related for booked instances. For example it could be `Appartment` for hotel bookings. If selected, all related bookings will be shown on the post edit page.', 'jet-appointments-booking' ); ?>"
		:options-list="postTypes"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="generalSettings.apartment_post_type"
		@input="updateSetting( $event, 'apartment_post_type' )"
	></cx-vui-select>
	<cx-vui-select
		label="<?php _e( 'Booking Period', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'How to calculate booking period - per nights (last booked date not included) or per days (last booked date is included). <b>Note:</b> This option will affect on price calculation.', 'jet-appointments-booking' ); ?>"
		:options-list="[
			{
				value: 'per_nights',
				label: 'Per Nights (last booked date not included)',
			},
			{
				value: 'per_days',
				label: 'Per Days (last booked date is included)',
			}
		]"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="generalSettings.booking_period"
		@input="updateSetting( $event, 'booking_period' )"
	></cx-vui-select>
	<cx-vui-switcher
		label="<?php _e( 'Weekly bookings', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'If this option is checked only full week booking are allowed.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="generalSettings.weekly_bookings"
		@input="updateSetting( $event, 'weekly_bookings' )"
	></cx-vui-switcher>
	<cx-vui-input
		label="<?php _e( 'Days Offset', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Allow to change first booked day of the week', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		size="fullwidth"
		:value="generalSettings.week_offset"
		v-if="generalSettings.weekly_bookings"
		@on-input-change="updateSetting( $event.target.value, 'week_offset' )"
		type="number"
	></cx-vui-input>
	<cx-vui-switcher
		label="<?php _e( 'One Day bookings', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'If this option is checked only single days bookings are allowed. If Weekly bookings are enabled this option will not work.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="generalSettings.one_day_bookings"
		@input="updateSetting( $event, 'one_day_bookings' )"
	></cx-vui-switcher>
	<cx-vui-switcher
		label="<?php _e( 'WooCommerce integration', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Check this to connect booking with WooCommerce checkout', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="generalSettings.wc_integration"
		@input="updateSetting( $event, 'wc_integration' )"
	></cx-vui-switcher>
</div>
