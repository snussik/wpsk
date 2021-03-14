<?php
/**
 * Review List template
 */
?><div id="jet-reviews-list-page" class="jet-reviews-admin-page jet-reviews-admin-page--reviews-list">
	<div class="jet-reviews-admin-page__header">
		<h1 class="wp-heading-inline"><?php _e( 'Review List', 'jet-reviews' ); ?></h1>
	</div>
	<hr class="wp-header-end">
	<div class="jet-reviews-admin-page__filters">
		<!-- <div class="jet-reviews-admin-page__filter">
			<label for="cx_post-type-filter"><?php _e( 'Post Type', 'jet-reviews' ); ?></label>
			<cx-vui-select
				name="post-type-filter"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				:prevent-wrap="true"
				:options-list="postTypeOptions"
				v-model="postTypeFilter"
				@input="postTypeFilterHandler"
			>
			</cx-vui-select>
		</div> -->

		<div class="jet-reviews-admin-page__filter">
			<label for="cx_search-review-input"><?php _e( 'Search reviews', 'jet-reviews' ); ?></label>
			<div class="jet-reviews-search-form">
				<cx-vui-input
					name="search-review-input"
					:wrapper-css="[ 'equalwidth' ]"
					size="fullwidth"
					:prevent-wrap="true"
					type="text"
					v-model="titleSearchText"
				>
				</cx-vui-input>
				<cx-vui-button
					button-style="accent-border"
					size="mini"
					@click="searchReviewHandle"
					:loading="reviewsGetting"
				>
					<span slot="label"><?php _e( 'Search', 'jet-reviews' ); ?></span>
				</cx-vui-button>
			</div>
		</div>
	</div>

	<div class="jet-reviews-admin-page__content">
		<cx-vui-list-table
			:is-empty="0 === itemsList.length"
			empty-message="<?php _e( 'No reviews found', 'jet-reviews' ); ?>"
			class="jet-reviews-admin-page__table jet-reviews-admin-page__table--reviews"
			:class="{ 'loading-status': reviewsGetting || actionExecution }"
		>
			<cx-vui-list-table-heading
				:slots="[ 'author', 'title', 'rating', 'post', 'date', 'actions' ]"
				slot="heading"
			>
				<div slot="author"><?php _e( 'Author', 'jet-reviews' ); ?></div>
				<div slot="title"><?php _e( 'Title', 'jet-reviews' ); ?></div>
				<div slot="rating"><?php _e( 'Rating', 'jet-reviews' ); ?></div>
				<div slot="post"><?php _e( 'Assigned to', 'jet-reviews' ); ?></div>
				<div slot="date"><?php _e( 'Date', 'jet-reviews' ); ?></div>
				<div slot="actions"><?php _e( 'Actions', 'jet-reviews' ); ?></div>
			</cx-vui-list-table-heading>
			<cx-vui-list-table-item
				:class="{ 'not-approved': ! item.approved }"
				:slots="[ 'author', 'title', 'rating', 'post', 'date', 'actions' ]"
				slot="items"
				v-for="( item, index ) in itemsList"
				:key="index"
			>
				<div slot="author">
					<div class="author-data">
						<a class="author-data__avatar" :href="item.author.url" v-html="item.author.avatar"></a>
						<div class="author-data__info">
							<b>{{ item.author.name }}</b>
							<i>{{ item.author.mail }}</i>
							<div class="author-data__roles" v-html="getRolesLabel( item.author.roles )"></div>
						</div>
					</div>
				</div>
				<div slot="title" v-html="item.title"></div>
				<div slot="rating" v-html="getRating( item.rating )"></div>
				<div slot="post">
					<i>{{ item.post_type }}: </i><a class="link" target="_blank" :href="item.post.link">{{ item.post.title }}</a>
					<div class="comments-data">
						<span class="dashicons dashicons-admin-comments"></span>
						<span>{{ item.comments_count }}</span>
					</div>
				</div>
				<div slot="date">{{ item.date }}</div>
				<div slot="actions">
					<span
						class="approve-action"
						@click='approveHandler( index, item.id )'
					>
						<span v-if="item.approved" :style="{ color: '#d98500'}"><?php _e( 'Unapprove', 'jet-reviews' ); ?></span>
						<span v-if="!item.approved" :style="{ color: '#46B450'}"><?php _e( 'Approve', 'jet-reviews' ); ?></span>
					</span>
					<span>|</span>
					<span
						class="edit-action"
						@click='openEditReviewPopup( index )'
					><?php
						_e( 'Edit', 'jet-reviews' );
					?></span>
					<span>|</span>
					<span
						class="delete-action"
						@click='openDeleteReviewPopup( index )'
					><?php
						_e( 'Delete', 'jet-reviews' );
					?></span>
				</div>
			</cx-vui-list-table-item>
		</cx-vui-list-table>
		<div
			class="jet-reviews-admin-page__pagination"
			v-if="0 !== itemsList.length"
		>
			<cx-vui-pagination
				:total="reviewsCount"
				:page-size="pageSize"
				@on-change="changePage"
			></cx-vui-pagination>
		</div>
	</div>

	<transition name="popup">
		<cx-vui-popup
			class="jet-reviews-admin-page__popup"
			v-model="editPopupVisible"
			:header="false"
			:footer="false"
			body-width="1200px"
		>
			<div class="cx-vui-subtitle" slot="title"><?php _e( 'Edit Review', 'jet-reviews' ); ?></div>
			<div
				class="edit-review"
				slot="content"
			>
				<div class="edit-review__inputs">

					<cx-vui-input
						name="review-title"
						label="<?php _e( 'Title', 'jet-reviews' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						type="text"
						v-model="editReviewData.title"
					>
					</cx-vui-input>

					<cx-vui-textarea
						name="review-content"
						label="<?php _e( 'Content', 'jet-reviews' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						v-model="editReviewData['content']"
						:rows="9"
					>
					</cx-vui-textarea>
				</div>

				<div class="edit-review__info">
					<div class="cx-vui-component cx-vui-component--vertical-fullwidth">
						<div class="cx-vui-component__meta">
							<label class="cx-vui-component__label"><?php _e( 'Fields', 'jet-reviews' ); ?></label>
						</div>
						<div class="cx-vui-component__control">
							<div class="edit-review__fields">
								<div
									class="edit-review__field"
									v-for="(fieldData, index) in editReviewData.rating_data"
								>
									<b><?php _e( 'Name:', 'jet-reviews' ); ?></b><span>{{ fieldData.field_label }}</span>
									<b><?php _e( 'Step:', 'jet-reviews' ); ?></b><span>{{ fieldData.field_step }}</span>
									<b><?php _e( 'Max:', 'jet-reviews' ); ?></b><span>{{ fieldData.field_max }}</span>
									<b><?php _e( 'Value:', 'jet-reviews' ); ?></b><span>{{ fieldData.field_value }}</span>
									<b><?php _e( 'Rating:', 'jet-reviews' ); ?></b><span>{{ Math.round( +fieldData.field_value * 100 / +fieldData.field_max ) }}%</span>
								</div>
							</div>
						</div>
					</div>

					<div class="cx-vui-component cx-vui-component--vertical-fullwidth">
						<div class="cx-vui-component__meta">
							<label class="cx-vui-component__label"><?php _e( 'Review info', 'jet-reviews' ); ?></label>
						</div>
						<div class="cx-vui-component__control">
							<div class="info-items">
								<div class="info-item">
									<div class="author">
										<div class="author-avatar" v-html="editReviewData.author.avatar"></div>
										<div class="author-info">
											<span>{{ editReviewData.author.name }}</span>
											<span>{{ editReviewData.author.mail }}</span>
										</div>
									</div>
								</div>
								<div class="info-item">
									<b><?php _e( 'Date:', 'jet-reviews' ); ?></b><span>{{ editReviewData.date }}</span>
								</div>
								<div class="info-item">
									<b><?php _e( 'Post Type:', 'jet-reviews' ); ?></b><span>{{ editReviewData.post_type }}</span>
								</div>
								<div class="info-item">
									<b><?php _e( 'Assigned to:', 'jet-reviews' ); ?></b><a class="link" target="_blank" :href="editReviewData.post.link">{{ editReviewData.post.title }}</a>
								</div>
								<div class="info-item">
									<b><?php _e( 'Review Type:', 'jet-reviews' ); ?></b><span>{{ editReviewData.type_slug }}</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="edit-review__controls">
					<cx-vui-button
						button-style="accent-border"
						size="mini"
						@click="saveReviewHandle"
						:loading="reviewSavingState"
					>
						<span slot="label"><?php _e( 'Save Review Data', 'jet-menu' ); ?></span>
					</cx-vui-button>
				</div>

			</div>
		</cx-vui-popup>
	</transition>

	<transition name="popup">
		<cx-vui-popup
			v-model="deletePopupVisible"
			body-width="350px"
			:ok-label="'<?php _e( 'Delete', 'jet-reviews' ) ?>'"
			:cancel-label="'<?php _e( 'Cancel', 'jet-reviews' ) ?>'"
			@on-ok="deleteReviewHandle"
		>
			<div class="cx-vui-subtitle" slot="title"><?php _e( 'Please confirm review deletion', 'jet-reviews' ); ?></div>
		</cx-vui-popup>
	</transition>
</div>
