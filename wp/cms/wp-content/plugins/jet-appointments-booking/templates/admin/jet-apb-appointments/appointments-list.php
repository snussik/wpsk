<div
	:class="{ 'jet-apb-loading': isLoading }"
>
	<div class="cx-vui-panel jet-apb-filters">
		<cx-vui-select
			v-if="notEmpty( servicesList )"
			label="<?php _e( 'Service', 'jet-appointments-booking' ); ?>"
			:wrapper-css="[ 'jet-apb-filter' ]"
			:options-list="prepareObjectForOptions( servicesList )"
			:value="filters.service"
			@input="updateFilters( $event, 'service' )"
		></cx-vui-select>
		<cx-vui-select
			v-if="notEmpty( providersList )"
			label="<?php _e( 'Provider', 'jet-appointments-booking' ); ?>"
			:wrapper-css="[ 'jet-apb-filter' ]"
			:options-list="prepareObjectForOptions( providersList )"
			:value="filters.provider"
			@input="updateFilters( $event, 'provider' )"
		></cx-vui-select>
		<cx-vui-select
			label="<?php _e( 'Status', 'jet-appointments-booking' ); ?>"
			:wrapper-css="[ 'jet-apb-filter' ]"
			:options-list="prepareObjectForOptions( statuses )"
			:value="filters.status"
			@input="updateFilters( $event, 'status' )"
		></cx-vui-select>
		<cx-vui-component-wrapper
			:wrapper-css="[ 'jet-apb-filter' ]"
			label="<?php _e( 'Select Date', 'jet-appointments-booking' ); ?>"
		>
			<vuejs-datepicker
				input-class="cx-vui-input size-fullwidth"
				:format="formatDate"
				:value="filters.date"
				@input="updateFilters( $event, 'date' )"
			></vuejs-datepicker>
			<span
				v-if="filters.date"
				class="jet-apb-date-clear"
				@click="updateFilters( '', 'date' )"
			>&times; <?php _e( 'Clear', 'jet-appointments-booking' ); ?></span>
		</cx-vui-component-wrapper>
	</div>
	<cx-vui-list-table
		:is-empty="! itemsList.length"
		empty-message="<?php _e( 'No appointments found', 'jet-appointments-booking' ); ?>"
	>
		<cx-vui-list-table-heading
			:slots="[ 'id', 'user_email', 'service', 'provider', 'date', 'slot', 'order_id', 'status' ]"
			slot="heading"
		>
			<span slot="id"><?php _e( 'ID', 'jet-appointments-booking' ); ?></span>
			<span slot="user_email"><?php _e( 'User e-mail', 'jet-appointments-booking' ); ?></span>
			<span slot="service"><?php _e( 'Service', 'jet-appointments-booking' ); ?></span>
			<span slot="provider"><?php _e( 'Provider', 'jet-appointments-booking' ); ?></span>
			<span slot="date"><?php _e( 'Date', 'jet-appointments-booking' ); ?></span>
			<span slot="slot"><?php _e( 'Time', 'jet-appointments-booking' ); ?></span>
			<span slot="order_id"><?php _e( 'Related Order', 'jet-appointments-booking' ); ?></span>
			<span slot="status"><?php _e( 'Status', 'jet-appointments-booking' ); ?></span>
		</cx-vui-list-table-heading>
		<cx-vui-list-table-item
			:slots="[ 'id', 'user_email', 'service', 'provider', 'date', 'slot', 'order_id', 'status' ]"
			slot="items"
			v-for="( item, index ) in itemsList"
			:key="item.ID + item.service"
		>
			<span slot="id">{{ item.ID }}</span>
			<span slot="user_email">{{ item.user_email }}</span>
			<span slot="service">{{ getServiceLabel( item.service ) }}</span>
			<span slot="provider">{{ getProviderLabel( item.provider ) }}</span>
			<span slot="date">{{ item.date }}</span>
			<span slot="slot">{{ item.slot }} - {{ item.slot_end }}</span>
			<span slot="order_id">
				<a v-if="item.order_id" :href="getOrderLink( item.order_id )" target="_blank">#{{ item.order_id }}</a>
			</span>
			<span
				slot="status"
				:class="{
					'notice': true,
					'notice-alt': true,
					'notice-success': isFinished( item.status ),
					'notice-warning': isInProgress( item.status ),
					'notice-error': isInvalid( item.status ),
				}"
			>{{ item.status }}</span>
			<div
				class="jet-apb-actions"
				slot="status"
			>
				<cx-vui-button
					button-style="accent"
					size="mini"
					@click="showEditDialog( item, index )"
				><span slot="label"><?php _e( 'Edit', 'jet-appoinments-booking' ); ?></span></cx-vui-button>
				<cx-vui-button
					button-style="link-accent"
					size="link"
					@click="showDetailsDialog( item )"
				><span slot="label"><?php _e( 'Details', 'jet-appoinments-booking' ); ?></span></cx-vui-button>
				<cx-vui-button
					button-style="link-error"
					size="link"
					@click="showDeleteDialog( item.ID )"
				><span slot="label"><?php _e( 'Delete', 'jet-appoinments-booking' ); ?></span></cx-vui-button>
			</div>
		</cx-vui-list-table-item>
	</cx-vui-list-table>
	<cx-vui-pagination
		v-if="perPage < totalItems"
		:total="totalItems"
		:page-size="perPage"
		@on-change="changePage"
	></cx-vui-pagination>
	<cx-vui-popup
		v-model="deleteDialog"
		body-width="460px"
		ok-label="<?php _e( 'Delete', 'jet-appointments-booking' ) ?>"
		cancel-label="<?php _e( 'Cancel', 'jet-appointments-booking' ) ?>"
		@on-cancel="deleteDialog = false"
		@on-ok="handleDelete"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Are you sure? Deleted appointment can\'t be restored.', 'jet-appointments-booking' );
		?></div>
	</cx-vui-popup>
	<cx-vui-popup
		v-model="detailsDialog"
		body-width="400px"
		:show-cancel="false"
		ok-label="<?php _e( 'Close', 'jet-appointments-booking' ) ?>"
		@on-ok="detailsDialog = false"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Appointment Details:', 'jet-appointments-booking' );
		?></div>
		<div class="jet-apb-details" slot="content">
			<br>
			<p v-for="( itemValue, itemKey ) in currentItem" :key="itemKey">
				<b>{{ itemKey }}:</b>
				<a
					v-if="'order_id' === itemKey && itemValue"
					:href="getOrderLink( itemValue )"
					target="_blank"
				>
					#{{ itemValue }}
				</a>
				<span
					v-else-if="'status' === itemKey && itemValue"
					:class="{
						'notice': true,
						'notice-alt': true,
						'notice-success': isFinished( itemValue ),
						'notice-warning': isInProgress( itemValue ),
						'notice-error': isInvalid( itemValue ),
					}"
				>{{ itemValue }}</span>
				<span v-else-if="'service' === itemKey && itemValue">{{ getServiceLabel( itemValue ) }}</span>
				<span v-else-if="'provider' === itemKey && itemValue">{{ getProviderLabel( itemValue ) }}</span>
				<span v-else>{{ itemValue }}</span>
			</p>
		</div>
	</cx-vui-popup>
	<cx-vui-popup
		v-model="editDialog"
		body-width="400px"
		ok-label="<?php _e( 'Save', 'jet-appointments-booking' ) ?>"
		@on-cancel="editDialog = false"
		@on-ok="handleEdit"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Edit Appointment:', 'jet-appointments-booking' );
		?></div>
		<div class="jet-apb-details" slot="content">
			<br>
			<p v-for="( itemValue, itemKey ) in currentItem" :key="itemKey">
				<b>{{ itemKey }}:</b>
				<a
					v-if="'order_id' === itemKey && itemValue"
					:href="getOrderLink( itemValue )"
					target="_blank"
				>
					#{{ itemValue }}
				</a>
				<select
					v-else-if="'status' === itemKey && itemValue"
					v-model="currentItem.status"
				>
					<option
						v-for="( statusLabel, statusId ) in statuses"
						:value="statusId"
					>{{ statusLabel }}</option>
				</select>
				<span v-else-if="'service' === itemKey && itemValue">{{ getServiceLabel( itemValue ) }}</span>
				<span v-else-if="'provider' === itemKey && itemValue">{{ getProviderLabel( itemValue ) }}</span>
				<span v-else-if="'ID' === itemKey || 'date' === itemKey || 'slot' === itemKey || 'slot_end' === itemKey">{{ itemValue }}</span>
				<input type="text" v-else v-model="currentItem[ itemKey ]">
			</p>
		</div>
	</cx-vui-popup>
</div>
