<div
	class="jet-reviews-advanced__new-review-form"
	:class="{ 'jet-progress-state': reviewSubmiting }"
>
	<div class="jet-new-review-control jet-new-review-content">
		<html-textarea
			class="jet-reviews-input jet-reviews-input--textarea"
			:is-valid="isValidReviewContent"
			:placeholder="options.labels.reviewContentPlaceholder"
			:not-valid-label="$root.getLabelBySlug( 'notValidField' )"
			ref="reviewContent"
			v-model="reviewContent"
		></html-textarea>
	</div>

	<div class="jet-new-review-control jet-new-review-title">
		<html-textarea
			class="jet-reviews-input"
			:is-valid="isValidReviewTitle"
			:placeholder="options.labels.reviewTitlePlaceholder"
			:not-valid-label="$root.getLabelBySlug( 'notValidField' )"
			v-model="reviewTitle"
		></html-textarea>
	</div>

	<div
		class="jet-new-review-control jet-new-review-author-name"
		v-if="$root.guestNameFieldVisible"
	>
		<html-textarea
			class="jet-reviews-input"
			:is-valid="isValidAuthorName"
			:placeholder="options.labels.authorNamePlaceholder"
			:not-valid-label="$root.getLabelBySlug( 'notValidField' )"
			v-model="reviewAuthorName"
		></html-textarea>
	</div>

	<div
		class="jet-new-review-control jet-new-review-author-mail"
		v-if="$root.guestMailFieldVisible"
	>
		<html-textarea
			class="jet-reviews-input"
			:is-valid="isValidAuthorEmail"
			:placeholder="options.labels.authorMailPlaceholder"
			:not-valid-label="$root.getLabelBySlug( 'notValidField' )"
			v-model="reviewAuthorMail"
		></html-textarea>
	</div>

	<div class="jet-new-review-fields">
		<div
			class="jet-new-review-field"
			v-for="(field, index) in fields"
			:key="index"
		>
			<component
				:is="$root.options.ratingInputType"
				:max="field.field_max"
				:step="field.field_step"
				:label="field.field_label"
				v-model="field.field_value"
			></component>
		</div>
	</div>

	<div
		class="jet-new-review-controls"
	>
		<div
			class="jet-reviews-button jet-reviews-button--secondary"
			tabindex="0"
			@click="cancelSubmit"
			@keyup.enter="cancelSubmit"
		>
			<div class="jet-reviews-button__text">{{ options.labels.cancelButtonLabel }}</div>
		</div>
		<div
			v-if="formControlsVisible"
			class="jet-reviews-button jet-reviews-button--primary"
			tabindex="0"
			@click="submitReview"
			@keyup.enter="submitReview"
		>
			<div class="jet-reviews-button__text">{{ options.labels.submitReviewButton }}</div>
		</div>
	</div>

	<div
		class="jet-new-review-message"
		v-if="formMessageVisible"
	>
		<span>{{ messageText }}</span>
	</div>

</div>
