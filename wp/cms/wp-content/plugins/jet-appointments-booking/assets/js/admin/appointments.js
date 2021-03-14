(function () {

	"use strict";

	Vue.component( 'jet-apb-appointments-list', {
		template: '#jet-apb-appointments-list',
		components: {
			vuejsDatepicker: window.vuejsDatepicker,
		},
		data: function() {
			return {
				itemsList: [],
				totalItems: 0,
				offset: 0,
				perPage: 30,
				deleteDialog: false,
				deleteItem: false,
				detailsDialog: false,
				currentItem: false,
				currentIndex: false,
				editDialog: false,
				statuses: window.JetAPBConfig.all_statuses,
				isLoading: false,
				servicesList: window.JetAPBConfig.services,
				providersList: window.JetAPBConfig.providers,
				filters: {},
			};
		},
		mounted: function() {
			this.getItems();
		},
		methods: {
			formatDate: function( date ) {
				return moment( date ).format( 'MMMM D, YYYY' );
			},
			updateFilters: function( value, prop ) {

				if ( value && 'date' === prop ) {
					value = moment( value ).format( 'MMMM D, YYYY' );
				}

				this.$set( this.filters, prop, value );
				this.offset = 0;
				this.getItems();
			},
			notEmpty: function( objectToCheck ){
				return Object.keys( objectToCheck ).length;
			},
			changePage: function( page ) {
				this.offset = this.perPage * ( page - 1 );
				this.getItems();
			},
			showEditDialog: function( item, index ) {
				this.editDialog = true;
				this.currentItem = JSON.parse( JSON.stringify( item ) );
				this.currentIndex = index;
			},
			showDetailsDialog: function( item ) {
				this.detailsDialog = true;
				this.currentItem = item;
			},
			showDeleteDialog: function( itemID ) {
				this.deleteItem   = itemID;
				this.deleteDialog = true;
			},
			prepareObjectForOptions: function( input ) {
				var result = [{
					'value': '',
					'label': wp.i18n.__( 'Select...', 'jet-appointments-booking' ),
				}];

				for ( var value in input ) {
					result.push( {
						'value': value,
						'label': input[ value ],
					} );
				}

				return result;
			},
			handleEdit: function() {

				var self = this;

				if ( ! self.currentItem ) {
					return;
				}

				self.itemsList.splice( self.currentIndex, 1, self.currentItem );

				wp.apiFetch( {
					method: 'post',
					path: window.JetAPBConfig.api.update_appointment + self.currentItem.ID + '/',
					data: { item: self.currentItem }
				} ).then( function( response ) {

					if ( ! response.success ) {
						self.$CXNotice.add( {
							message: response.data,
							type: 'error',
							duration: 7000,
						} );
					} else {
						self.$CXNotice.add( {
							message: 'Done!',
							type: 'success',
							duration: 7000,
						} );
					}

					self.currentItem = false;
					self.currentIndex = false;

				} ).catch( function( e ) {
					self.$CXNotice.add( {
						message: e.message,
						type: 'error',
						duration: 7000,
					} );
				} );

			},
			handleDelete: function() {

				var self = this;

				if ( ! self.deleteItem ) {
					return;
				}

				wp.apiFetch( {
					method: 'delete',
					path: window.JetAPBConfig.api.delete_appointment + self.deleteItem + '/',
				} ).then( function( response ) {
					if ( ! response.success ) {
						self.$CXNotice.add( {
							message: response.data,
							type: 'error',
							duration: 7000,
						} );
					}

					for ( var i = 0; i < self.itemsList.length; i++ ) {
						if ( self.itemsList[ i ].ID === self.deleteItem ) {
							self.itemsList.splice( i, 1 );
							break;
						}
					}

				} ).catch( function( e ) {
					self.$CXNotice.add( {
						message: e.message,
						type: 'error',
						duration: 7000,
					} );
				} );
			},
			buildQuery: function( params ) {
				return Object.keys( params ).map(function( key ) {
					return key + '=' + params[ key ];
				}).join( '&' );
			},
			getItems: function() {

				var self = this;

				self.isLoading = true;

				wp.apiFetch( {
					method: 'get',
					path: window.JetAPBConfig.api.appointments_list + '?' + this.buildQuery( {
						per_page: self.perPage,
						offset: self.offset,
						query: JSON.stringify( self.filters ),
					} ),
				} ).then( function( response ) {
					self.isLoading = false;
					if ( response.success ) {
						self.itemsList = response.data;
						if ( response.total ) {
							self.totalItems = parseInt( response.total, 10 );
						}
					}
				} ).catch( function( e ) {
					self.isLoading = false;
					self.$CXNotice.add( {
						message: e.message,
						type: 'error',
						duration: 7000,
					} );
				} );
			},
			getServiceLabel: function( id ) {
				return this.servicesList[ id ] || id;
			},
			getProviderLabel: function( id ) {
				return this.providersList[ id ] || id;
			},
			getOrderLink: function( orderID ) {
				return window.JetAPBConfig.edit_link.replace( /\%id\%/, orderID );
			},
			isFinished: function( status ) {
				return ( 0 <= window.JetAPBConfig.statuses.finished.indexOf( status ) );
			},
			isInProgress: function( status ) {
				return ( 0 <= window.JetAPBConfig.statuses.in_progress.indexOf( status ) );
			},
			isInvalid: function( status ) {
				return ( 0 <= window.JetAPBConfig.statuses.invalid.indexOf( status ) );
			}
		},
	} );

	new Vue({
		el: '#jet-apb-appointments-page',
		template: '#jet-apb-appointments',
		data: {
			isSet: window.JetAPBConfig.setup.is_set,
		}
	});

})();
