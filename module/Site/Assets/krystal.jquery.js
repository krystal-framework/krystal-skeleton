$(function(){
    // Setup global AJAX settings
    $.ajaxSetup({
        cache : false,
        charset : "UTF-8",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Validator class constructor
     * 
     * @param $form Jquey form object
     * @return void
     */
    function Validator($form){
        this.$form = $form;
    }

    Validator.prototype = {
        // Target selectors
        inputSelector : '.form-group .form-control',
        invalidClass : 'is-invalid',
        validClass : 'is-valid',

        /**
         * Builds a selector for a target element
         * 
         * @param string name Element's name
         * @return string Built selector
         */
        buildElementSelector : function(name){
            return selector = '[name="' + name + '"]';
        },

        /**
         * Finds container element by a child's name inside it
         * 
         * @param string name Child element's name
         * @return object
         */
        getContainerElementByClosestName : function(name){
            var selector = this.buildElementSelector(name);
            return this.$form.find(selector).closest(this.inputSelector);
        },

        /**
         * Returns parent element's container
         * 
         * @param string name Child element's name
         * @return object
         */
        getParentContainer : function(name){
            var selector = this.buildElementSelector(name);
            return $(selector).parent();
        },

        /**
         * Creates message block
         * 
         * @param string text Text to be appeared within container
         * @return object
         */
        createMessageElement : function(text){
            var element = document.createElement('span');

            // Configure element for bootstrap
            $span = $(element).attr('class', 'invalid-feedback')
                              .text(text);

            return $span;
        },

        /**
         * Checks whether parent element has a helper block
         * 
         * @param object $container
         * @return boolean
         */
        hasHelpBlock : function($container){
            return $container.length > 0;
        },

        /**
         * Shows an error which belongs to a field
         * 
         * @param string name Element's name
         * @param string message To be appended
         * @return void
         */
        showErrorOn : function(name, message){
            $container = this.getContainerElementByClosestName(name);

            if ($container.hasClass(this.validClass)) {
                $container.removeClass(this.validClass);
            }

            $container.addClass(this.invalidClass);

            $span = this.createMessageElement(message);

            $parent = this.getParentContainer(name);
            $parent.append($span);
        },

        /**
         * Resets control elements to their initial state
         * 
         * @return void
         */
        resetAll : function(){
            // Classes we'd like to remove when resetting all
            var classes = [
                this.invalidClass,
                this.validClass
            ];

            this.$form.find(this.inputSelector).each(function(){
                for (var key in classes) {
                    // Value represents class name
                    var value = classes[key];

                    if ($(this).hasClass(value)) {
                        $(this).removeClass(value);
                    }
                }
            });

            // Now we'd assume that everything is okay, and later remove this class on demand
            this.$form.find(this.inputSelector).addClass(this.validClass);

            // Remove all helper spans
            this.$form.find("span.invalid-feedback").remove();
        },

        /**
         * Shows error messages
         * 
         * @param string response Server's response
         * @return void
         */
        handleAll : function(response){
            // If explicit redirect URL is provided, then redirect to it
            if (response.hasOwnProperty('backUrl')) {
                window.location = response.backUrl;
                return;
            }

            // Clear all previous messages and added classes
            this.resetAll();

            // if its not JSON, but "1" then we'd assume success
            if (response == "1") {
                // Since we might have a flash messenger, we'd simply reload current page
                window.location.reload();
            } else {
                // Otherwise, show error messages
                try {
                    var data = $.parseJSON(response);

                    for (var name in data) {
                        var message = data[name];
                        this.showErrorOn(name, message);
                    }

                } catch(e) {
                    // Otherwise we'd assume that something went wrong
                    $modal = $('#responseModal');
                    $modal.find('.modal-body').html(response);
                    $modal.modal();
                }
            }
        }
    };

    /**
     * Global factory for form validator
     * 
     * @param object $form Jquery form object
     * @return Validator
     */
    $.getValidator = function($form){
        return new Validator($form);
    };

    // CAPTCHA handler
    $("[data-captcha='button-refresh']").click(function(event){
        event.preventDefault();

        // Grab image's element
        var $image = $("[data-captcha='image']");
        var link = $image.attr('src');

        $image.attr('src', link + Math.random());
    });

    // For forms that send data
    $("button[type='submit']").click(function(event){
        event.preventDefault();

        // Find its parent form
        var $form = $(this).closest('form');

        // Attach the singular handler and cancel any previous if any
        $form.off('submit').submit(function(event){
            event.preventDefault();
            // Support both files and data
			var data = new FormData($(this)[0]);

            $.ajax({
                url: $form.attr('action') ? $form.attr('action') : '',
                type : "POST",
				processData : false,
				contentType : false,
				data : data,
                success : function(response){
                    $.getValidator($form).handleAll(response);
                }
            });
        });

        // Now submit it
        $form.submit();
    });});