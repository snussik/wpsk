'use strict';

let jetReviewsSettinsMixin = {
	data: function() {
		return {
			pageOptions: window.JetReviewsSettingsConfig.settingsData || [],
			savingStatus: false,
			ajaxSaveHandler: null,
		};
	},

	watch: {
		pageOptions: {
			handler( options ) {
				let prepared = {};

				this.saveOptions();
			},
			deep: true
		}
	},

	computed: {
		preparedOptions: function() {
			return this.pageOptions;
		}
	},

	methods: {
		saveOptions: function() {

			let self = this;

			this.savingStatus = true;

			wp.apiFetch( {
				method: 'post',
				path: window.JetReviewsSettingsConfig.saveSettingsRoute,
				data: {
					settings: self.preparedOptions
				},
			} ).then( function( response ) {

				if ( response.success ) {
					self.$CXNotice.add( {
						message: response.message,
						type: 'success',
						duration: 5000,
					} );
				} else {
					self.$CXNotice.add( {
						message: response.message,
						type: 'error',
						duration: 5000,
					} );
				}
			} );
		},
	}
}

Vue.component( 'jet-reviews-integrations', {

	template: '#jet-dashboard-jet-reviews-integrations',

	mixins: [ jetReviewsSettinsMixin ],

	data: function() {
		return {

		};
	},

	computed: {

	},

	methods: {

	}
} );

Vue.component( 'jet-reviews-post-types', {

	template: '#jet-dashboard-jet-reviews-post-types',

	mixins: [ jetReviewsSettinsMixin ],

	data: function() {
		return {
			avaliablePostTypes: window.JetReviewsSettingsConfig.avaliablePostTypes,
			avaliableReviewTypes: window.JetReviewsSettingsConfig.avaliableReviewTypes,
			allRolesOptions: window.JetReviewsSettingsConfig.allRolesOptions,
			verificationOptions: window.JetReviewsSettingsConfig.verificationOptions,
			activeTab: window.localStorage.getItem( 'jetReviewActiveSettingsTab' ) || 'page-post-type-settings',
			syncPostmetaStatus: false
		};
	},

	computed: {
		reviewTypeOptions: function() {

			let reviewTypeOptions = [];

			for ( var prop in this.avaliableReviewTypes ) {
				let typeData = this.avaliableReviewTypes[ prop ];

				reviewTypeOptions.push( {
					label: typeData.name,
					value: typeData.slug
				} );
			}

			return reviewTypeOptions;
		},

		verificationVisible: function() {
			return this.verificationOptions.length;
		}
	},

	methods: {
		tabSwitch: function( currentTab ) {
			window.localStorage.setItem( 'jetReviewActiveSettingsTab', currentTab );
		},

		syncRatingData: function( postType = false ) {
			let self = this;

			this.syncPostmetaStatus = true;

			wp.apiFetch( {
				method: 'post',
				path: window.JetReviewsSettingsConfig.syncRatingDataRoute,
				data: {
					postType: postType,
				},
			} ).then( function( response ) {

				self.syncPostmetaStatus = false;

				if ( response.success ) {
					self.$CXNotice.add( {
						message: response.message,
						type: 'success',
						duration: 5000,
					} );
				} else {
					self.$CXNotice.add( {
						message: response.message,
						type: 'error',
						duration: 5000,
					} );
				}
			} );
		}
	}
} );
