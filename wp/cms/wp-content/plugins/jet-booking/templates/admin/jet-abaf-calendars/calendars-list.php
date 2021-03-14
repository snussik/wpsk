<div
	:class="{ 'jet-abaf-loading': isLoading }"
>
	<cx-vui-list-table
		:is-empty="! itemsList.length"
		empty-message="<?php _e( 'No calendars found', 'jet-engine' ); ?>"
	>
		<cx-vui-list-table-heading
			:slots="[ 'post_title', 'unit_title', 'export_url', 'import_url' ]"
			slot="heading"
		>
			<span slot="post_title"><?php _e( 'Post Title', 'jet-engine' ); ?></span>
			<span slot="unit_title"><?php _e( 'Unit Title', 'jet-engine' ); ?></span>
			<span slot="export_url"><?php _e( 'Export URL', 'jet-engine' ); ?></span>
			<span slot="import_url"><?php _e( 'External Calendars', 'jet-engine' ); ?></span>
		</cx-vui-list-table-heading>
		<cx-vui-list-table-item
			:slots="[ 'post_title', 'unit_title', 'export_url', 'import_url' ]"
			slot="items"
			v-for="( item, index ) in itemsList"
			:key="item.post_id + item.unit_id"
		>
			<span slot="post_title">{{ item.title }}</span>
			<span slot="unit_title">{{ item.unit_title }}</span>
			<code slot="export_url">{{ item.export_url }}</code>
			<div slot="import_url">
				<ul v-if="item.import_url">
					<li v-for="url in item.import_url" :key="url"><a :href="url">{{ url }}</a></li>
				</ul>
				<div v-else>--</div>
			</div>
			<div
				class="jet-abaf-actions"
				slot="import_url"
			>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					v-if="item.import_url && item.import_url.length"
					@click="showSynchDialog( item )"
				>
					<span slot="label"><?php
						_e( 'Synch', 'jet-appoinments-booking' );
					?></span>
				</cx-vui-button>
				<cx-vui-button
					button-style="accent"
					size="mini"
					@click="showEditDialog( item, index )"
				>
					<span slot="label"><?php
						_e( 'Edit Calendars', 'jet-appoinments-booking' );
					?></span>
				</cx-vui-button>
			</div>
		</cx-vui-list-table-item>
	</cx-vui-list-table>
	<cx-vui-popup
		v-model="editDialog"
		body-width="400px"
		ok-label="<?php _e( 'Save', 'jet-engine' ) ?>"
		@on-cancel="editDialog = false"
		@on-ok="handleEdit"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Edit Calendars:', 'jet-engine' );
		?></div>
		<div class="jet-abaf-calendars" slot="content">
			<br>
			<p v-for="( url, index ) in currentItem.import_url">
				<input
					type="url"
					placeholder="https://calendar-link.com"
					v-model="currentItem.import_url[ index ]"
					:style="{width: '100%'}"
				>
			</p>
			<a href="#" @click.prevent="addURL" :style="{ textDecoration: 'none' }"><b>
				+ <?php _e( 'New URL', 'jet-booking' ); ?>
			</b></a>
		</div>
	</cx-vui-popup>
	<cx-vui-popup
		v-model="synchDialog"
		body-width="600px"
		cancel-label="<?php _e( 'Close', 'jet-engine' ) ?>"
		@on-cancel="synchDialog = false"
		:show-ok="false"
	>
		<div class="cx-vui-subtitle" slot="title"><?php
			_e( 'Synchronizing Calendars:', 'jet-engine' );
		?></div>
		<div class="jet-abaf-calendars" slot="content">
			<div v-if="!synchLog"><?php _e( 'Processing...', 'jet-engine' ); ?></div>
			<div v-else v-html="synchLog" class="jet-abaf-synch-log"></div>
		</div>
	</cx-vui-popup>
</div>