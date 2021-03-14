<div>
	<h3 class="cx-vui-subtitle"><?php _e( 'Booking Settings', 'jet-appointments-booking' ); ?></h3>
	<br>
	<div class="cx-vui-panel">
		<cx-vui-tabs
			:in-panel="false"
			:value="initialTab"
			layout="vertical"
		>
			<cx-vui-tabs-panel
				name="general"
				label="<?php _e( 'General', 'jet-appointments-booking' ); ?>"
				key="general"
			>
				<keep-alive>
					<jet-abaf-settings-general
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-abaf-settings-general>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="labels"
				label="<?php _e( 'Labels', 'jet-appointments-booking' ); ?>"
				key="labels"
			>
				<keep-alive>
					<jet-abaf-settings-labels
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-abaf-settings-labels>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="advanced"
				label="<?php _e( 'Advanced', 'jet-appointments-booking' ); ?>"
				key="advanced"
			>
				<keep-alive>
					<jet-abaf-settings-advanced
						:settings="settings"
						@force-update="onUpdateSettings( $event, true )"
					></jet-abaf-settings-advanced>
				</keep-alive>
			</cx-vui-tabs-panel>
			<cx-vui-tabs-panel
				name="db_tables"
				label="<?php _e( 'DB Tables', 'jet-appointments-booking' ); ?>"
				key="db_tables"
			>
				<p v-if="! dbTablesExists"><?php
					_e( 'Before start you need to create required DB tables', 'jet-booking' );
				?></p>
				<cx-vui-button
					:button-style="'accent'"
					:loading="processingTables"
					@click="processTables"
				>
					<span slot="label" v-if="dbTablesExists"><?php _e( 'Update Tables', 'jet-booking' ); ?></span>
					<span slot="label" v-else><?php _e( 'Create Tables', 'jet-booking' ); ?></span>
				</cx-vui-button>
			</cx-vui-tabs-panel>
		</cx-vui-tabs>
	</div>
</div>