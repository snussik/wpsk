( function( $, elementorFrontend, publicConfig ) {

	'use strict';

	var JetReviews = {

		eventBus: new Vue(),

		initedInstance: [],

		captchaToken: false,

		init: function() {

			var widgets = {
				'jet-reviews.default' : JetReviews.widgetJetReviewsSimple,
				'jet-reviews-advanced.default' : JetReviews.widgetJetReviewsAdvanced,
			};

			$.each( widgets, function( widget, callback ) {
				elementorFrontend.hooks.addAction( 'frontend/element_ready/' + widget, callback );
			});

			JetReviews.defineVueComponents();
		},

		widgetJetReviewsSimple: function( $scope ) {
			var $target       = $scope.find( '.jet-review' ),
				settings      = $target.data( 'settings' ),
				$form         = $( '.jet-review__form', $target ),
				$submitButton = $( '.jet-review__form-submit', $target ),
				$removeButton = $( '.jet-review__item-remove', $target ),
				$message      = $( '.jet-review__form-message', $target ),
				$rangeControl = $( '.jet-review__form-field.type-range input', $target ),
				ajaxRequest   = null;

			$rangeControl.on( 'input', function( event ) {
				var $this         = $( this ),
					$parent       = $this.closest( '.jet-review__form-field' ),
					$currentValue = $( '.current-value', $parent ),
					value         = $this.val();

					$currentValue.html( value );
			} );

			$submitButton.on( 'click.widgetJetReviews', function() {
				addReviewHandle();

				return false;
			} );

			$removeButton.on( 'click.widgetJetReviews', function() {
				var $this = $( this );

				removeReviewHandle( $this );

				return false;
			} );

			function addReviewHandle() {
				var now            = new Date(),
					reviewTime     = now.getTime(),
					reviewDate     = new Date( reviewTime ).toLocaleString(),
					sendData       = {
						'post_id': settings['post_id'],
						'review_time': reviewTime,
						'review_date': reviewDate
					},
					serializeArray = $form.serializeObject();

				sendData = jQuery.extend( sendData, serializeArray );

				ajaxRequest = jQuery.ajax( {
					type: 'POST',
					url: window.jetReviewPublicConfig.ajax_url,
					data: {
						'action': 'jet_reviews_add_meta_review',
						'data': sendData
					},
					beforeSend: function( jqXHR, ajaxSettings ) {
						if ( null !== ajaxRequest ) {
							ajaxRequest.abort();
						}

						$submitButton.addClass( 'load-state' );
					},
					error: function( jqXHR, ajaxSettings ) {

					},
					success: function( data, textStatus, jqXHR ) {

						var responseType = data['type'],
							message      = data.message || '';

						if ( 'error' === responseType ) {
							$submitButton.removeClass( 'load-state' );
							$message.addClass( 'visible-state' );

							$( 'span', $message ).html( message );
						}

						if ( 'success' === responseType ) {
							location.reload();
						}
					}
				} );
			};

			function removeReviewHandle( $removeButton ) {
				var $reviewItem  = $removeButton.closest( '.jet-review__item' ),
					reviewUserId = $reviewItem.data( 'user-id' ),
					sendData     = {
						'post_id': settings['post_id'],
						'user_id': reviewUserId
					};

				ajaxRequest = jQuery.ajax( {
					type: 'POST',
					url: window.jetReviewPublicConfig.ajax_url,
					data: {
						'action': 'jet_reviews_remove_review',
						'data': sendData
					},
					beforeSend: function( jqXHR, ajaxSettings ) {
						if ( null !== ajaxRequest ) {
							ajaxRequest.abort();
						}

						$removeButton.addClass( 'load-state' );
					},
					error: function( jqXHR, ajaxSettings ) {

					},
					success: function( data, textStatus, jqXHR ) {
						var successType   = data.type,
							message       = data.message || '';

						if ( 'error' == successType ) {

						}

						if ( 'success' == successType ) {
							location.reload();
						}
					}
				} );
			};
		},

		widgetJetReviewsAdvanced: function( $scope ) {
			let $target    = $scope.find( '.jet-reviews-advanced' ),
				instanceId = $target.attr( 'id' ),
				options    = $target.data( 'options' ) || {};

			if ( ! $target[0] ) {
				return;
			}

			JetReviews.createJetReviewAdvancedInstance( instanceId, options );
		},

		defineVueComponents: function() {

			Vue.component( 'jet-reviews-widget-pagination', {
				template: '#jet-reviews-widget-pagination-template',
				props: {
					current: {
						type: Number,
						default: 1
					},
					total: {
						type: Number,
						default: 0
					},
					pageSize: {
						type: Number,
						default: 10
					},
					prevIcon: {
						type: String,
						default: '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M5.67089 0L-5.96046e-08 6L5.67089 12L7 10.5938L2.65823 6L7 1.40625L5.67089 0Z"/></svg>'
					},
					nextIcon: {
						type: String,
						default: '<svg width="7" height="12" viewBox="0 0 7 12" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1.32911 0L7 6L1.32911 12L0 10.5938L4.34177 6L0 1.40625L1.32911 0Z"/></svg>'
					},
					customCss: {
						type: String,
						default: ''
					},
				},
				data() {
					return {
						baseClass: 'jet-reviews-widget-pagination',
						currentPage: this.current,
						currentPageSize: this.pageSize
					};
				},

				watch: {
					total ( val ) {
						let maxPage = Math.ceil( val / this.currentPageSize );

						if ( maxPage < this.currentPage ) {
							this.currentPage = ( maxPage === 0 ? 1 : maxPage );
						}
					},
					current ( val ) {
						this.currentPage = val;
					},
					pageSize ( val ) {
						this.currentPageSize = val;
					}
				},

				computed: {
					classesList() {

						let classesList = [
							this.baseClass,
						];

						if ( this.customCss ) {
							classesList.push( this.customCss );
						}

						return classesList;
					},

					prevClasses () {
						return [
							`${this.baseClass}__item`,
							`${this.baseClass}__item--prev`,
							{
								[`${this.baseClass}__item--disabled`]: this.currentPage === 1 || false
							}
						];
					},

					nextClasses () {
						return [
							`${this.baseClass}__item`,
							`${this.baseClass}__item--next`,
							{
								[`${this.baseClass}__item--disabled`]: this.currentPage === this.allPages || false
							}
						];
					},

					firstPageClasses () {
						return [
							`${this.baseClass}__item`,
							{
								[`${this.baseClass}__item--active`]: this.currentPage === 1
							}
						];
					},

					lastPageClasses () {
						return [
							`${this.baseClass}__item`,
							{
								[`${this.baseClass}__item--active`]: this.currentPage === this.allPages
							}
						];
					},

					allPages () {
						const allPage = Math.ceil( this.total / this.currentPageSize );

						return ( allPage === 0 ) ? 1 : allPage;
					},
				},
				methods: {
					changePage ( page ) {

						if ( this.currentPage !== page ) {

							this.currentPage = page;
							this.$emit( 'update:current', page );
							this.$emit( 'on-change', page );
						}
					},

					prev () {
						const current = this.currentPage;

						if ( current <= 1 ) {
							return false;
						}

						this.changePage( current - 1 );
					},

					next () {
						const current = this.currentPage;

						if ( current >= this.allPages ) {
							return false;
						}

						this.changePage(current + 1);
					},

					fastPrev () {
						const page = this.currentPage - 5;

						if ( page > 0 ) {
							this.changePage( page );
						} else {
							this.changePage( 1 );
						}
					},

					fastNext () {
						const page = this.currentPage + 5;

						if ( page > this.allPages ) {
							this.changePage( this.allPages );
						} else {
							this.changePage( page );
						}
					},
				},

			} );

			Vue.component( 'jet-advanced-reviews-form', {
				template: '#jet-advanced-reviews-form-template',

				props: {
					reviewFields: Array,
					options: Object,
				},

				data: function() {
					return ( {
						reviewSubmiting: false,
						reviewTitle: '',
						reviewContent: '',
						reviewAuthorName: '',
						reviewAuthorMail: '',
						messageText: '',
						fields: this.reviewFields
					} );
				},

				mounted: function() {
					let self = this;

					Vue.nextTick().then( function () {
						let reviewContent = self.$refs.reviewContent,
							textarea      = reviewContent.$refs.textarea;

						textarea.focus();
					} );

					if ( this.$root.isUserGuest ) {
						this.reviewAuthorName = JetReviews.getLocalStorageData( 'guestName', '' );
						this.reviewAuthorMail = JetReviews.getLocalStorageData( 'guestMail', '' );
					}
				},

				computed: {
					formControlsVisible: function() {

						if ( this.$root.isUserGuest ) {
							return this.isValidReviewContent &&
									this.isValidReviewTitle &&
									this.isValidAuthorName &&
									this.isValidAuthorEmail;
						}

						return this.isValidReviewContent && this.isValidReviewTitle;
					},

					formMessageVisible: function() {
						return '' !== this.messageText;
					},

					isValidReviewContent: function() {
						return '' !== this.reviewContent;
					},

					isValidReviewTitle: function() {
						return '' !== this.reviewTitle;
					},

					isValidAuthorName: function() {
						return '' !== this.reviewAuthorName;
					},

					isValidAuthorEmail: function() {
						return '' !== this.reviewAuthorMail && JetReviews.checkValidEmail( this.reviewAuthorMail );
					},

				},

				methods: {
					cancelSubmit: function() {
						JetReviews.eventBus.$emit( 'closeNewReviewForm', { uniqId: this.options.uniqId } );
					},

					submitReview: function() {

						let self = this,
							forSendingData = {
							post: this.options.postId,
							title: this.reviewTitle,
							content: this.reviewContent,
							author_id: this.$root.currentUserData.id,
							author_name: this.reviewAuthorName,
							author_mail: this.reviewAuthorMail,
							rating_data: this.fields
						},
						recaptchaConfig = publicConfig.recaptchaConfig;

						// Recaptcha enable check
						if ( recaptchaConfig.enable ) {

							window.grecaptcha.ready( function() {
								grecaptcha.execute(
									recaptchaConfig.site_key,
									{
										action: 'submit_review'
									}
								).then( function( token ) {

									JetReviews.captchaToken = token;

									let modifyData = Object.assign( {}, forSendingData, {
										captcha_token: token
									} );

									self.submitReviewHandle( modifyData );
								} );
							} );

							return false;
						}

						this.submitReviewHandle( forSendingData );
					},

					submitReviewHandle: function( sendData = false ) {
						let self = this;

						if ( ! sendData ) {
							console.warn( 'Empty new review data for sending' );

							return false;
						}

						this.reviewSubmiting = true;
						this.messageText = '';

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewRoute,
							data: sendData,
						} ).then( function( response ) {

							self.reviewSubmiting = false;

							self.$root.currentUserData.name = self.reviewAuthorName;
							self.$root.currentUserData.mail = self.reviewAuthorMail;
							JetReviews.setLocalStorageData( 'guestName', self.reviewAuthorName );
							JetReviews.setLocalStorageData( 'guestMail', self.reviewAuthorMail );

							if ( response.success ) {

								let responseData = response.data;

								self.messageText = response.message;

								JetReviews.eventBus.$emit( 'addReview', {
									uniqId: self.options.uniqId,
									reviewData: responseData.item,
								} );

								self.$root.reviewsAverageRating = +responseData.rating;

								self.$root.currentUserData.canReview = false;

								let guestReviewedPosts     = JetReviews.getLocalStorageData( 'guestReviewedPosts', [] ),
									alreadyReviewedMessage = self.$root.getLabelBySlug( 'alreadyReviewed' );

								if ( alreadyReviewedMessage ) {
									self.$root.currentUserData.canReviewMessage = alreadyReviewedMessage;
								}

								if ( self.$root.isUserGuest && ! guestReviewedPosts.includes( self.$root.options.postId ) ) {
									guestReviewedPosts.push( self.$root.options.postId );
									JetReviews.setLocalStorageData( 'guestReviewedPosts', guestReviewedPosts );
								}

							} else {
								self.messageText = response.message;
							}
						} );
					}
				}
			});

			Vue.component( 'slider-input', {
				template: '#jet-advanced-reviews-slider-input-template',

				props: {
					value: {
						type: [ String, Number ],
						default: ''
					},
					max: {
						type: [ Number ],
						default: 5
					},
					step: {
						type: [ Number ],
						default: 1
					},
					label: {
						type: [ String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {
						//currentValue: this.value,
					} );
				},

				computed: {
					valueLabel: function() {
						return this.value;
					}
				},

				methods: {
					handleInput ( event ) {
						let value = event.target.value;

						this.$emit( 'input', value );
						this.$emit( 'on-change', event );
					},
					handleChange ( event ) {
						this.$emit( 'on-input-change', event );
					}
				},
			});

			Vue.component( 'stars-input', {
				template: '#jet-advanced-reviews-stars-input-template',

				props: {
					value: {
						type: [ String, Number ],
						default: ''
					},
					max: {
						type: [ Number ],
						default: 5
					},
					step: {
						type: [ Number ],
						default: 1
					},
					label: {
						type: [ String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {
						currentRating: this.value,
					} );
				},

				computed: {
					valueLabel: function() {
						return `${ this.value }/${ this.max }`;
					},

					rating: function() {
						return ( this.currentRating / this.max ) * 100;
					},

					preparedRating: function() {

						if ( 10 > this.rating ) {
							return 10;
						}

						return this.rating;
					},

					emptyIcon: function() {
						return this.$root.refsHtml.emptyStarIcon || '<i class="far fa-star"></i>';
					},

					emptyIcons: function() {
						let icon = `<div class="jet-reviews-star">${ this.$root.refsHtml.emptyStarIcon }</div>` || '<div class="jet-reviews-star"><i class="far fa-star"></i></div>';

						return icon.repeat( this.max );
					},

					filledIcons: function() {
						let icon = `<div class="jet-reviews-star">${ this.$root.refsHtml.filledStarIcon }</div>` || '<div class="jet-reviews-star"><i class="fas fa-star"></i></div>';

						return icon.repeat( this.max );
					},

					ratingClass: function() {
						let ratingClass = 'very-high-rating';

						if ( this.rating >= 80 && this.rating <= 100 ) {
							ratingClass = 'very-high-rating';
						}

						if ( this.rating >= 60 && this.rating <= 79 ) {
							ratingClass = 'high-rating';
						}

						if ( this.rating >= 40 && this.rating <= 59 ) {
							ratingClass = 'medium-rating';
						}

						if ( this.rating >= 22 && this.rating <= 39 ) {
							ratingClass = 'low-rating';
						}

						if ( this.rating >= 0 && this.rating <= 21 ) {
							ratingClass = 'very-low-rating';
						}

						return ratingClass;
					}
				},

				methods: {
					ratingClick( rating ) {
						this.currentRating = rating;
						this.$emit( 'input', rating );
					},

					ratingMouseOver( rating ) {
						this.currentRating = rating;
					},

					ratingMouseOut() {
						this.currentRating = this.value;
					},
				},
			});

			Vue.component( 'jet-advanced-reviews-item', {
				template: '#jet-advanced-reviews-item-template',

				props: {
					options: Object,
					itemData: Object,
				},

				data: function() {
					return ( {
						commentFormVisible: false,
						commentText: '',
						commentAuthorName: '',
						commentAuthorMail: '',
						commentSubmiting: false,
						approvalSubmiting: false,
						parentComment: 0,
						commentsVisible: false,
						responseMessage: '',
						detailsVisibleState: false
					} );
				},

				mounted: function() {
					if ( this.$root.isUserGuest ) {
						this.commentAuthorName = JetReviews.getLocalStorageData( 'guestName', '' );
						this.commentAuthorMail = JetReviews.getLocalStorageData( 'guestMail', '' );

						let guestReviewApprovalData = JetReviews.getLocalStorageData( 'guestReviewApprovalData', {} );

						if ( guestReviewApprovalData.hasOwnProperty( this.itemData.id ) ) {
							this.$set( this.itemData, 'approval', guestReviewApprovalData[ this.itemData.id ] );
						}

					}
				},

				computed: {

					isDetailsFieldsAvaliable: function() {
						return 1 < this.itemData.rating_data.length;
					},

					detailsVisible: function() {
						return this.isDetailsFieldsAvaliable && this.detailsVisibleState;
					},

					averageRatingVisible: function() {
						return 'average' === this.$root.options.reviewRatingType;
					},

					detailsRatingVisible: function() {
						return 'details' === this.$root.options.reviewRatingType;
					},

					authorVerificationData: function() {
						return 0 !== this.itemData.verifications.length ? this.itemData.verifications : false;
					},

					isCommentsEmpty: function() {
						return 0 === this.itemData.comments.length;
					},

					isCommentsVisible: function() {
						return !this.isCommentsEmpty && this.commentsVisible;
					},

					itemCommentsCount: function() {
						let itemCommentsCount = false;

						itemCommentsCount = this.getCommentsCount( this.itemData.comments );

						return itemCommentsCount;
					},

					pinnedVisible: function() {
						return this.itemData.pinned;
					},

					commentControlsVisible: function() {

						if ( this.$root.isUserGuest ) {
							return this.isCommentValid &&
									this.isValidAuthorName &&
									this.isValidAuthorEmail;
						}

						return this.isCommentValid;
					},

					averageRatingData: function() {
						let ratingDatalength = this.itemData.rating_data.length,
							summaryValue = 0,
							avarageValue = 0,
							summaryMax = 0,
							avarageMax = 0;

						summaryValue = this.itemData.rating_data.reduce( function( accumulator, currentValue ) {
							return accumulator + +currentValue.field_value;
						}, 0 );

						summaryMax = this.itemData.rating_data.reduce( function( accumulator, currentValue ) {
							return accumulator + +currentValue.field_max;
						}, 0 );

						avarageValue = Math.round( summaryValue / ratingDatalength );
						avarageMax = Math.round( summaryMax / ratingDatalength );

						return {
							rating: Math.round( avarageValue * 100 / avarageMax, 1 ),
							max: Math.round( avarageMax, 1 ),
							value: Math.round( avarageValue, 1 )
						};
					},

					addCommentIcon: function() {
						return this.$root.refsHtml.newCommentButtonIcon || false;
					},

					showCommentsIcon: function() {
						return this.$root.refsHtml.showCommentsButtonIcon || false;
					},

					pinnedIcon: function() {
						return this.$root.refsHtml.pinnedIcon || '<i class="fas fa-thumbtack"></i>';
					},

					likeIcon: function() {
						let emptyLike  = this.$root.refsHtml.reviewEmptyLikeIcon || '<i class="far fa-thumbs-up"></i>',
							filledLike = this.$root.refsHtml.reviewFilledLikeIcon || '<i class="fas fa-thumbs-up"></i>';

						return ! this.itemData.approval.like ? emptyLike : filledLike;
					},

					dislikeIcon: function() {
						let emptyDislike  = this.$root.refsHtml.reviewEmptyDislikeIcon || '<i class="far fa-thumbs-down"></i>',
							filledDislike = this.$root.refsHtml.reviewFilledDislikeIcon || '<i class="fas fa-thumbs-down"></i>';

						return ! this.itemData.approval.dislike ? emptyDislike : filledDislike;
					},

					userCanComment: function() {
						return this.options.commentsAllowed && this.$root.currentUserData.canComment;
					},

					userCanApproval: function() {
						return this.options.approvalAllowed && this.$root.currentUserData.canApproval;
					},

					isValidAuthorName: function() {
						return '' !== this.commentAuthorName;
					},

					isValidAuthorEmail: function() {
						return JetReviews.checkValidEmail( this.commentAuthorMail );
					},

					isCommentValid: function() {
						return '' !== this.commentText;
					},

				},

				methods: {
					showCommentForm: function() {
						let self = this;

						this.commentFormVisible = !this.commentFormVisible;

						if ( this.commentFormVisible ) {
							Vue.nextTick().then( function () {
								let commentContent = self.$refs.commentContent,
									textarea       = commentContent.$refs.textarea;

								textarea.focus();
							} );
						}
					},

					cancelNewComment: function() {
						this.commentFormVisible = false;
						this.responseMessage = '';
					},

					submitNewComment: function() {

						let self = this,
							forSendingData = {
								post_id: this.options.postId,
								parent_id: this.parentComment,
								review_id: this.itemData.id,
								author_id: this.$root.currentUserData.id,
								author_name: this.commentAuthorName,
								author_mail: this.commentAuthorMail,
								content: this.commentText,
							},
							recaptchaConfig = publicConfig.recaptchaConfig;

						// Recaptcha enable check
						if ( recaptchaConfig.enable ) {

							window.grecaptcha.ready( function() {
								grecaptcha.execute(
									recaptchaConfig.site_key,
									{
										action: 'submit_review_comment'
									}
								).then( function( token ) {

									JetReviews.captchaToken = token;

									let modifyData = Object.assign( {}, forSendingData, {
										captcha_token: token
									} );

									self.submitCommentHandler( modifyData );
								} );
							} );

							return false;
						}

						this.submitCommentHandler( forSendingData );
					},

					submitCommentHandler: function( sendData = false ) {
						let self = this;

						if ( ! sendData ) {
							console.warn( 'Empty new comment data for sending' );

							return false;
						}

						this.commentSubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewCommentRoute,
							data: sendData,
						} ).then( function( response ) {

							self.commentSubmiting = false;

							self.$root.currentUserData.name = self.commentAuthorName;
							self.$root.currentUserData.mail = self.commentAuthorMail;
							JetReviews.setLocalStorageData( 'guestName', self.commentAuthorName );
							JetReviews.setLocalStorageData( 'guestMail', self.commentAuthorMail );

							if ( response.success ) {
								self.commentFormVisible = false;
								self.commentText = '';
								self.itemData.comments.unshift( response.data );
								self.commentsVisible = true;
							} else {
								self.responseMessage = response.message;
								console.log( response.message );
							}
						} );
					},

					updateApprovalHandler: function( type ) {
						let self    = this,
							altType = 'like' === type ? 'dislike' : 'like';

						this.approvalSubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.likeReviewRoute,
							data: {
								review_id: self.itemData.id,
								type: type,
								inc: ! self.itemData.approval[ type ],
								current_state: self.itemData.approval,
							},
						} ).then( function( response ) {
							self.approvalSubmiting = false;

							if ( response.success ) {
								self.$set( self.itemData, 'approval', response.data.approval );
								self.$set( self.itemData, 'like', response.data.like );
								self.$set( self.itemData, 'dislike', response.data.dislike );

								if ( self.$root.isUserGuest ) {
									self.updateGuestApprovalData( self.itemData.id, self.itemData.approval );
								}

							} else {
								console.log( response.message );
							}
						} );
					},

					updateGuestApprovalData: function( $reviewId = false, approvalData = false ) {
						let guestReviewApprovalData = JetReviews.getLocalStorageData( 'guestReviewApprovalData', {} );

						guestReviewApprovalData[ $reviewId ] = approvalData;

						JetReviews.setLocalStorageData( 'guestReviewApprovalData', guestReviewApprovalData );
					},

					toggleCommentsVisible: function() {
						this.commentsVisible = !this.commentsVisible;
					},

					getCommentsCount: function( comments, initialValue = 0 ) {

						let total = comments.reduce( ( accumulator, commentData ) => {
							if ( commentData.hasOwnProperty( 'children' ) && 0 !== commentData.children.length ) {
								let initialValue = accumulator + 1;
								return this.getCommentsCount( commentData.children, initialValue );
							} else {
								return accumulator + 1;
							}
						}, initialValue );

						return total;
					}
				}
			});

			Vue.component( 'jet-advanced-reviews-comment', {
				template: '#jet-advanced-reviews-comment-template',

				props: {
					options: Object,
					commentData: Object,
					parentId: Number,
					parentComments: Array,
					depth: Number,
				},

				data: function() {
					return ( {
						commentsList: this.commentData.children || [],
						replySubmiting: false,
						replyFormVisible: false,
						replyText: '',
						replyAuthorName: '',
						replyAuthorMail: '',
						responseMessage: ''
					} );
				},

				mounted: function() {
					if ( this.$root.isUserGuest ) {
						this.replyAuthorName = JetReviews.getLocalStorageData( 'guestName', '' );
						this.replyAuthorMail = JetReviews.getLocalStorageData( 'guestMail', '' );
					}
				},

				computed: {
					commentClass: function() {
						return '';
					},

					formControlsVisible: function() {
						return this.options.commentsAllowed;
					},

					submitVisible: function() {

						if ( this.$root.isUserGuest ) {
							return this.isReplyTextValid &&
									this.isValidAuthorName &&
									this.isValidAuthorEmail;
						}

						return this.isReplyTextValid;
					},

					replyIcon: function() {
						return this.$root.refsHtml.replyButtonIcon || false;
					},

					isValidAuthorName: function() {
						return '' !== this.replyAuthorName;
					},

					isValidAuthorEmail: function() {
						return JetReviews.checkValidEmail( this.replyAuthorMail );
					},

					isReplyTextValid: function() {
						return '' !== this.replyText;
					},

					authorVerificationData: function() {

						if ( ! this.commentData.verifications ) {
							return false;
						}

						return 0 !== this.commentData.verifications.length ? this.commentData.verifications : false;
					},
				},

				methods: {
					showReplyForm: function() {
						let self = this;

						this.replyFormVisible = !this.replyFormVisible;

						if ( this.replyFormVisible ) {
							this.replyText = '<b>' + this.commentData.author.name + '</b>,&nbsp;';

							Vue.nextTick().then( function () {
								let commentText = self.$refs.commentText,
									textarea    = commentText.$refs.textarea;

								JetReviews.placeCaretAtEnd( textarea );
							} );
						}
					},

					cancelNewReply: function() {
						this.replyFormVisible = false;
						this.responseMessage = '';
					},

					submitNewReply: function() {

						let self = this,
							forSendingData = {
								post_id: this.options.postId,
								parent_id: 0 === this.depth ? this.commentData.id : this.parentId,
								review_id: this.commentData.review_id,
								author_id: this.$root.currentUserData.id,
								author_name: this.replyAuthorName,
								author_mail: this.replyAuthorMail,
								content: this.replyText,
							},
							recaptchaConfig = publicConfig.recaptchaConfig;

						// Recaptcha enable check
						if ( recaptchaConfig.enable ) {

							window.grecaptcha.ready( function() {
								grecaptcha.execute(
									recaptchaConfig.site_key,
									{
										action: 'submit_comment_reply'
									}
								).then( function( token ) {

									JetReviews.captchaToken = token;

									let modifyData = Object.assign( {}, forSendingData, {
										captcha_token: token
									} );

									self.submitReplyHandler( modifyData );
								} );
							} );

							return false;
						}

						this.submitReplyHandler( forSendingData );
					},

					submitReplyHandler: function( sendData = false ) {
						let self = this;

						if ( ! sendData ) {
							console.warn( 'Empty review comment data for sending' );

							return false;
						}

						this.replySubmiting = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.submitReviewCommentRoute,
							data: sendData,
						} ).then( function( response ) {

							self.replySubmiting = false;

							self.$root.currentUserData.name = self.replyAuthorName;
							self.$root.currentUserData.mail = self.replyAuthorMail;
							JetReviews.setLocalStorageData( 'guestName', self.replyAuthorName );
							JetReviews.setLocalStorageData( 'guestMail', self.replyAuthorMail );

							if ( response.success ) {
								self.replyFormVisible = false;
								self.replyText = '';

								if ( 0 === self.depth ) {
									self.commentData.children.unshift( response.data );
								} else {
									self.parentComments.push( response.data );
								}
							} else {
								self.responseMessage = response.message;
							}
						} );
					}
				}
			});

			Vue.component( 'points-field', {
				template: '#jet-advanced-reviews-point-field-template',

				props: {
					before: {
						type: [ Number, String, Boolean ],
						default: false
					},
					rating: Number,
					after: {
						type: [ Number, String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {} );
				},

				computed: {
					isBeforeEmpty: function() {
						return false === this.before || '' === this.before;
					},

					isAfterEmpty: function() {
						return false === this.after || '' === this.after;
					},

					preparedRating: function() {

						if ( 10 > this.rating ) {
							return 10;
						}

						return this.rating;
					},

					ratingClass: function() {
						let ratingClass = 'very-high-rating';

						if ( this.rating >= 80 && this.rating <= 100 ) {
							ratingClass = 'very-high-rating';
						}

						if ( this.rating >= 60 && this.rating <= 79 ) {
							ratingClass = 'high-rating';
						}

						if ( this.rating >= 40 && this.rating <= 59 ) {
							ratingClass = 'medium-rating';
						}

						if ( this.rating >= 22 && this.rating <= 39 ) {
							ratingClass = 'low-rating';
						}

						if ( this.rating >= 0 && this.rating <= 21 ) {
							ratingClass = 'very-low-rating';
						}

						return ratingClass;
					}
				}
			});

			Vue.component( 'stars-field', {
				template: '#jet-advanced-reviews-star-field-template',

				props: {
					before: {
						type: [ Number, String, Boolean ],
						default: false
					},
					rating: Number,
					after: {
						type: [ Number, String, Boolean ],
						default: false
					},
				},

				data: function() {
					return ( {} );
				},

				computed: {
					isBeforeEmpty: function() {
						return ! this.before || '' === this.before;
					},

					isAfterEmpty: function() {
						return ! this.after || '' === this.after;
					},

					preparedRating: function() {

						if ( 10 > this.rating ) {
							return 10;
						}

						return this.rating;
					},

					emptyIcons: function() {
						let icon = `<div class="jet-reviews-star">${ this.$root.refsHtml.emptyStarIcon }</div>` || '<div class="jet-reviews-star"><i class="far fa-star"></i></div>';

						return icon.repeat( 5 );
					},

					filledIcons: function() {
						let icon = `<div class="jet-reviews-star">${ this.$root.refsHtml.filledStarIcon }</div>` || '<div class="jet-reviews-star"><i class="fas fa-star"></i></div>';

						return icon.repeat( 5 );
					},

					ratingClass: function() {
						let ratingClass = 'very-high-rating';

						if ( this.rating >= 80 && this.rating <= 100 ) {
							ratingClass = 'very-high-rating';
						}

						if ( this.rating >= 60 && this.rating <= 79 ) {
							ratingClass = 'high-rating';
						}

						if ( this.rating >= 40 && this.rating <= 59 ) {
							ratingClass = 'medium-rating';
						}

						if ( this.rating >= 22 && this.rating <= 39 ) {
							ratingClass = 'low-rating';
						}

						if ( this.rating >= 0 && this.rating <= 21 ) {
							ratingClass = 'very-low-rating';
						}

						return ratingClass;
					}
				},
			});

			Vue.component( 'html-textarea', {
				template:'<div :class="classes" ref="textarea" contenteditable="true" tabindex="0" :data-placeholder="placeholder" :data-not-valid-label="notValidLabel" @input="updateHTML" @focus="focusHandler" @blur="blurHandler"></div>',

				props: {
					value: String,
					isValid: {
						type: Boolean,
						default: true,
					},
					placeholder: {
						type: String,
						default: 'Input',
					},
					notValidLabel: {
						type: String,
						default: 'This field is required or not valid',
					},
				},

				data: function() {
					return ( {
						isFocus: false,
						isEmpty: true,
					} );
				},

				computed: {
					classes: function() {
						let classes = [
							'jet-reviews-content-editable',
							!this.isValid ? 'jet-reviews-content-editable--not-valid' : false,
							this.isFocus ? 'jet-reviews-content-editable--focus' : false,
							this.isPlaceholder ? 'jet-reviews-content-editable--placeholder' : false,
						];

						return classes;
					},

					isPlaceholder: function() {

						if ( this.isFocus || ! this.isEmpty ) {
							return false;
						}

						return true;
					}
				},

				mounted: function () {
					this.$el.innerHTML = this.value;
				},

				methods: {
					updateHTML: function( e ) {
						this.$emit( 'input', e.target.innerHTML );

						if ( 0 === $( this.$refs.textarea ).text().trim().length ) {
							this.isEmpty = true;
						} else {
							this.isEmpty = false;
						}
					},

					focusHandler: function( e ) {
						this.$emit( 'focus', e.target );
						this.isFocus = true;
					},

					blurHandler: function( e ) {
						this.$emit( 'blur', e.target );
						this.isFocus = false;
					}
				}
			});
		},

		createJetReviewAdvancedInstance: function( instanceId, options ) {

			if ( JetReviews.initedInstance.includes( instanceId ) ) {
				return;
			}

			JetReviews.initedInstance.push( instanceId );

			let JetReviewAdvancedInstance = new Vue( {
				el: '#' + instanceId,

				data: {
					uniqId: instanceId,
					options: options,
					reviewsLoaded: false,
					getReviewsProcessing: false,
					reviewsList: [],
					reviewsPage: 1,
					reviewsTotal: 1,
					reviewsAverageRating: 0,
					currentUserData: publicConfig.currentUserData,
					currentPostData: publicConfig.currentPostData,
					reviewTypeData: publicConfig.reviewTypeData,
					formVisible: false,
					isMounted: false,
					refsHtml: {},
				},

				mounted: function() {
					let self     = this,
						refsHtml = {};

					this.isMounted = true;

					for ( var ref in this.$refs ) {
						Object.assign( refsHtml, { [ ref ]: this.$refs[ ref ].innerHTML } );
					}

					this.refsHtml = refsHtml;

					if ( this.isUserGuest ) {
						this.$set( this.currentUserData, 'id', this.guestId );
						this.$set( this.currentUserData, 'name', this.guestName );
						this.$set( this.currentUserData, 'mail', this.guestMail );

						let guestReviewedPosts     = JetReviews.getLocalStorageData( 'guestReviewedPosts', [] ),
							alreadyReviewedMessage = this.getLabelBySlug( 'alreadyReviewed' );

						if ( guestReviewedPosts.includes( this.currentPostData.id ) ) {
							this.$set( this.currentUserData, 'canReview', false );

							if ( alreadyReviewedMessage ) {
								this.$set( this.currentUserData, 'canReviewMessage', alreadyReviewedMessage );
							}

						}
					}

					wp.apiFetch( {
						method: 'post',
						path: publicConfig.getPublicReviewsRoute,
						data: {
							post: self.options.postId,
							page: self.reviewsPage - 1,
							page_size: self.options.pageSize
						},
					} ).then( function( response ) {

						self.reviewsLoaded = true;

						if ( response.success && response.data ) {
							self.reviewsList = response.data.list;
							self.reviewsTotal = +response.data.total;
							self.reviewsAverageRating = +response.data.rating;
						} else {
							console.log( 'Error' );
						}
					} );

					JetReviews.eventBus.$on( 'addReview', function ( payLoad ) {

						if ( self.options.uniqId !== payLoad.uniqId ) {
							return;
						}

						self.formVisible = false;

						let reviewData = payLoad.reviewData;

						self.reviewsList.unshift( reviewData );

					} );

					JetReviews.eventBus.$on( 'closeNewReviewForm', function ( payLoad ) {

						if ( self.options.uniqId !== payLoad.uniqId ) {
							return;
						}

						self.formVisible = false;
					} );

				},

				computed: {
					instanceClass: function() {
						let classes = [
							'jet-reviews-advanced__instance',
						];

						if ( this.isMounted ) {
							classes.push( 'is-mounted' );
						}

						return classes;
					},

					reviewsLength: function() {
						return this.reviewsList.length;
					},

					reviewsListEmpty: function() {
						return 0 === this.reviewsList.length ? true : false;
					},

					preparedFields: function() {

						let rawFields = this.reviewTypeData.fields,
							preparedFields = [];

						for ( let fieldData of rawFields ) {
							preparedFields.push( {
								field_label: fieldData.label,
								field_value: +fieldData.max,
								field_step: +fieldData.step,
								field_max: +fieldData.max,
							} );
						}

						return preparedFields;
					},

					averageRating: function() {
						let totalRating = 0;

						if ( this.reviewsListEmpty ) {
							return 0;
						}

						totalRating = this.reviewsList.reduce( function( sum, reviewItem ) {
							return +reviewItem.rating + sum;
						}, 0 );

						return Math.round( totalRating / this.reviewsList.length, 1 );
					},

					averageValue: function() {
						let summaryValue = 0;

						if ( this.reviewsListEmpty ) {
							return 0;
						}

						for ( let reviewItem of this.reviewsList ) {
							let ratingData = reviewItem.rating_data,
								itemSummary = 0;

							for ( let ratingItem of ratingData ) {
								itemSummary += +ratingItem.field_value
							}

							summaryValue += Math.round( itemSummary / ratingData.length, 1 )
						}

						return Math.round( summaryValue / this.reviewsList.length, 1 );
					},

					averageMax: function() {
						let totalMax = 0,
							fields   = this.reviewTypeData.fields;

						totalMax = fields.reduce( function( sum, field ) {
							return +field.max + sum;
						}, 0 );

						return Math.round( totalMax / fields.length, 1 );
					},

					addReviewIcon: function() {
						return this.refsHtml.newReviewButtonIcon || false;
					},

					structuredData: function() {
						let reviewsList = [];

						reviewsList = this.reviewsList.map( function( reviewData ) {
							let fields     = reviewData.rating_data,
								totalValue = 0,
								totalMax   = 0;

							totalValue = fields.reduce( function( sum, field ) {
								return +field.field_value + sum;
							}, 0 );

							totalMax = fields.reduce( function( sum, field ) {
								return +field.field_max + sum;
							}, 0 );

							return {
								'@type': 'Review',
								'name': reviewData.title,
								'reviewBody': reviewData.content,
								'reviewRating': {
									'@type': 'Rating',
									'ratingValue': Math.round( totalValue / fields.length, 1 ).toString(),
									'bestRating': Math.round( totalMax / fields.length, 1 ).toString(),
									'worstRating': '0'
								},
								'datePublished': reviewData.date.raw,
								'author': {
									'@type': 'Person',
									'name': reviewData.author.name
								}
							};
						} );

						return {
							'@context': 'https://schema.org/',
							'@type': 'Product',
							'name': this.currentPostData.title,
							'image': this.currentPostData.image_url,
							'description': this.currentPostData.excerpt,
							'aggregateRating': {
								'@type': 'AggregateRating',
								'ratingValue': ( this.reviewsAverageRating / 100 * 5 ).toString(),
								'bestRating': '5',
								'worstRating': '0',
								'ratingCount': this.reviewsTotal.toString(),
								'reviewCount': this.reviewsTotal.toString()
							},
							'review': reviewsList
						};
					},

					isUserGuest: function() {
						return this.currentUserData.roles.includes( 'guest' );
					},

					guestId: function() {
						let guestId = JetReviews.getLocalStorageData( 'guestId', false );

						if ( ! guestId ) {
							let guestId = `guest_${ JetReviews.getUniqId() }`;

							JetReviews.setLocalStorageData( 'guestId', guestId );

							return guestId;
						}

						return guestId;
					},

					guestName: function() {
						let guestName = JetReviews.getLocalStorageData( 'guestName', false );

						if ( ! guestName ) {
							JetReviews.setLocalStorageData( 'guestName', '' );

							return '';
						}

						return guestName;
					},

					guestMail: function() {
						let guestMail = JetReviews.getLocalStorageData( 'guestMail', false );

						if ( ! guestMail ) {
							JetReviews.setLocalStorageData( 'guestMail', '' );

							return '';
						}

						return guestMail;
					},

					guestNameFieldVisible: function() {
						return this.isUserGuest && '' === this.currentUserData.name;
					},

					guestMailFieldVisible: function() {
						return this.isUserGuest && '' === this.currentUserData.mail;
					},

					paginationVisible: function() {
						return this.reviewsTotal > this.options.pageSize;
					},

				},

				methods: {
					formVisibleToggle: function() {
						this.formVisible = !this.formVisible;
					},

					getLabelBySlug: function( slug = false ) {

						if ( ! slug ) {
							return false;
						}

						if ( ! publicConfig.labels.hasOwnProperty( slug ) ) {
							return false;
						}

						return publicConfig.labels[ slug ];
					},

					changePageHanle: function( page ) {
						this.reviewsPage = page;
						this.getReviews();
					},

					getReviews: function() {
						let self = this;

						self.getReviewsProcessing = true;

						wp.apiFetch( {
							method: 'post',
							path: publicConfig.getPublicReviewsRoute,
							data: {
								post: self.options.postId,
								page: self.reviewsPage - 1,
								page_size: self.options.pageSize,
							},
						} ).then( function( response ) {
							self.getReviewsProcessing = false;

							self.scrollToInstance();

							if ( response.success && response.data ) {
								self.reviewsList = response.data.list;
								self.reviewsTotal = +response.data.total;
								self.reviewsAverageRating = +response.data.rating;
							} else {
								console.log( 'Error' );
							}
						} );
					},

					scrollToInstance: function() {
						let $instance = $( `#${ this.uniqId }` ),
							offsetTop = $instance.offset().top - 50;

						window.scrollTo( {
							top: offsetTop,
							behavior: 'smooth',
						} );
					}
				}

			} );
		},

		getLocalStorageData: function( _key = false, _default = false ) {
			try {
				let jetReviewsData = JSON.parse( window.localStorage.getItem( 'jetReviewsData' ) );

				if ( _key ) {

					if ( jetReviewsData.hasOwnProperty( _key ) ) {
						return jetReviewsData[ _key ];
					} else {
						return _default;
					}
				}

				return jetReviewsData;
			} catch ( e ) {
				return _default;
			}
		},

		setLocalStorageData: function( key, data ) {
			let jetReviewsData = this.getLocalStorageData() || {};

			jetReviewsData[ key ] = data;

			window.localStorage.setItem( 'jetReviewsData', JSON.stringify( jetReviewsData ) );
		},

		getUniqId: function() {
			return Math.random().toString( 36 ).substr( 2, 9 );
		},

		placeCaretAtEnd: function( el ) {
			el.focus();

			if ( 'undefined' !== typeof window.getSelection && 'undefined' !== typeof document.createRange ) {
				let range = document.createRange();

					range.selectNodeContents( el );
					range.collapse( false );

				let selection = window.getSelection();

				selection.removeAllRanges();
				selection.addRange( range );
			} else if ( 'undefined' !== typeof document.body.createTextRange ) {
				let textRange = document.body.createTextRange();

				textRange.moveToElementText( el );
				textRange.collapse( false );
				textRange.select();
			}
		},

		checkValidEmail: function ( email ) {
			var reg = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			return reg.test( email );
		},
	};

	$( window ).on( 'elementor/frontend/init', JetReviews.init );

	$.fn.serializeObject = function(){

		var self = this,
			json = {},
			push_counters = {},
			patterns = {
				"validate": /^[a-zA-Z][a-zA-Z0-9_-]*(?:\[(?:\d*|[a-zA-Z0-9_-]+)\])*$/,
				"key":      /[a-zA-Z0-9_-]+|(?=\[\])/g,
				"push":     /^$/,
				"fixed":    /^\d+$/,
				"named":    /^[a-zA-Z0-9_-]+$/
			};

		this.build = function(base, key, value){
			base[key] = value;
			return base;
		};

		this.push_counter = function(key){
			if(push_counters[key] === undefined){
				push_counters[key] = 0;
			}
			return push_counters[key]++;
		};

		$.each($(this).serializeArray(), function(){
			// skip invalid keys
			if(!patterns.validate.test(this.name)){
				return;
			}

			var k,
				keys = this.name.match(patterns.key),
				merge = this.value,
				reverse_key = this.name;

			while((k = keys.pop()) !== undefined){

				// adjust reverse_key
				reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

				// push
				if(k.match(patterns.push)){
					merge = self.build([], self.push_counter(reverse_key), merge);
				}

				// fixed
				else if(k.match(patterns.fixed)){
					merge = self.build([], k, merge);
				}

				// named
				else if(k.match(patterns.named)){
					merge = self.build({}, k, merge);
				}
			}

			json = $.extend(true, json, merge);
		});

		return json;
	};

}( jQuery, window.elementorFrontend, window.jetReviewPublicConfig ) );
