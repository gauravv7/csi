///////////////////
// validateIt.js //
///////////////////

/*
version: 0.8 (beta)
author: gaurav arora
email: gaurav.arora0403@gmail.com
description: This plugin provides functionality to verify MULTI-STEP html forms, 
                by taking in an object of names of all
                form inputs with associated validation rule to validate with. 
                Thus, providing flexibility to validate a group of inputs or all at once 
                based on the user requirements. This plugin also provides sufficient animations to perform 
                multi-step forms with predefined ID's for related action buttons, thus minimizing the code
                to few declarative lines, with predefined html markup. 
                (upcoming version is to be more flexible)
*/

/**
 * console helper
 */
(function($) {

  if(window.console === undefined)
    window.console = { isFake: true };

  var fns = ["log","warn","info","group","groupCollapsed","groupEnd"];
  for (var i = fns.length - 1; i >= 0; i--)
    if(window.console[fns[i]] === undefined)
      window.console[fns[i]] = $.noop;

  if(!$) return;
  
  var I = function(i){ return i; };

  function log() {
    if(this.suppressLog)
      return;
    cons('log', this, arguments);
  }

  function warn() {
    cons('warn', this, arguments);
  }

  function info() {
    cons('info', this, arguments);
  }

  function cons(type, opts, args) {
    if(window.console === undefined ||
       window.isFake === true)
      return;

    var a = $.map(args,I);
    a[0] = [opts.prefix, a[0], opts.postfix].join('');
    var grp = $.type(a[a.length-1]) === 'boolean' ? a.pop() : null;

    //if(a[0]) a[0] = getName(this) + a[0];
    if(grp === true) window.group(a[0]);
    if(a[0] && grp === null)
      if(window.navigator.userAgent.indexOf("MSIE") >= 0)
        window.log(a.join(','));
      else
        window.console[type].apply(window.console, a);
    if(grp === false) window.groupEnd();
  }

  function withOptions(opts) {
    return {
      log:  function() { log.apply(opts, arguments); },
      warn: function() { warn.apply(opts, arguments); },
      info: function() { info.apply(opts, arguments); }
    };
  }

  var console = function(opts) {
    opts = $.extend({}, defaults, opts);
    return withOptions(opts);
  };

  defaults = {
    suppressLog: false,
    prefix: '',
    postfix: ''
  };

  $.extend(console, withOptions(defaults));

  if($.console === undefined)
    $.console = console;
  
  $.consoleNoConflict = console;

}(jQuery));


/*
    The semi-colon before the function invocation is a safety net against
    concatenated scripts and/or other plugins which may not be closed properly.

    "undefined" is used because the undefined global variable in ECMAScript 3
    is mutable (ie. it can be changed by someone else). Because we don't pass a
    value to undefined when the anonymyous function is invoked, we ensure that
    undefined is truly undefined. Note, in ECMAScript 5 undefined can no
    longer be modified.

    "window" and "document" are passed as local variables rather than global.
    This (slightly) quickens the resolution process.
*/
;(function ( $, window, document, undefined ) {
    

    /* ===================================== *
     * Rules Manager (Plugin Wide)
     * ===================================== */

    var ruleManager = null;
    (function() {


      //privates
      var rawRules = {};

      var addRule = function(obj) {
        //check format, insert type
        for(var name in obj){
          if(rawRules[name])
            warn("validator '%s' already exists", name);
          //functions get auto-objectified
          if($.isFunction(obj[name]))
            obj[name] = { fn: obj[name] };
        }
        //deep extend rules by obj
        $.extend(true, rawRules, obj);
      };

      var updateRule = function(obj) {

        var data = {};
        //check format, insert type
        for(var name in obj) {
          if(rawRules[name])
            data[name] = obj[name];
          else
            warn("cannot update validator '%s' doesn't exist yet", name);
        }

        $.extend(true, rawRules, data);
      };

      var getRule = function(name) {
        var obj = rawRules[name];
        if(!obj) {
            var msg = "Missing rule: " + name;
            warn(msg);
            throw new Error('Stopped: no rule found with name: ' + name);
        }
        return obj;
      };

      var getRawRules = function(){
        return rawRules;
      }

      //public interface
      ruleManager = {
        addRule: addRule,
        getRule: getRule,
        updateRule: updateRule,
        getRawRules: getRawRules
      };
    }());


    var Utils = {

      //append to arguments[i]
      appendArg: function(args, expr, i) {
          if(!i) i = 0;
          var a = [].slice.call(args, i);
          a[i] = expr + a[i];
          return a;
      },
      validateDate: function(date){
        // date is supposed to be a date object, further check if date param is string, then use parseDate
        if ( Object.prototype.toString.call(date) === "[object Date]" ) {
          // it is a date
          if ( !isNaN( date.getTime() ) )  // d.valueOf() could also work
            return true;
          else
            return false;
        } else{
          // not a date
          return false;
        }
      },
      dateToString: function(date) {
        return date.getFullYear()+"-"+(date.getMonth()+1)+"-"+date.getDate();
      },
      parseDate: function(dateStr) {
        //format check
        var m = dateStr.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
        if(!m) return null;

        var date;
        date = new Date(parseInt(m[3], 10),parseInt(m[2], 10)-1,parseInt(m[1], 10));

        return date;
      },
      giveAlertMessage: function(msg){
        var $heading = $('<h5/>', {
            text: 'Alert!',
            style: 'color: #fff;'
        }).append($('<span/>',{
            style: 'font-size: 14px; float:right; display: inline;padding: 0px 4px;border-radius: 50%;background-color: #fff;color: #1F3C58;cursor: pointer;',
            text: 'x'
        }).click(function(e){
           $(this).parent().parent().remove().fadeOut(); 
        }));
        var $pmsg = $('<p/>', {
            text: msg
        });
        var $notify = $('<div/>', {
            'style': 'position: fixed;bottom: 0px;left: 25px;display: block;width: 300px;height: auto;background-color: #1F3C58;color: #fff;padding: 5px 10px;margin: 10px 0px;box-sizing: content-box;border-radius: 4px;word-wrap: break-word;font-weight: bold;',
        }).append($heading).append($pmsg).hide().appendTo('body').fadeIn();
      }
    };

    var VERSION = "0.0.1",
        cons = $.consoleNoConflict({ prefix: 'validateIt.js: ' }),
        log  = cons.log,
        warn = cons.warn,
        info = cons.info;

    /*
        Store the name of the plugin in the "pluginName" variable. This
        variable is used in the "Plugin" constructor below, as well as the
        plugin wrapper to construct the key for the "$.data" method.

        More: http://api.jquery.com/jquery.data/
    */
    var pluginName = 'validateIt';

    /*
        The "Plugin" constructor, builds a new instance of the plugin for the
        DOM node(s) that the plugin is called on. For example,
        "$('h1').pluginName();" creates a new instance of pluginName for
        all h1's.
    */
    // Create the plugin constructor
    function Plugin ( element, options, isSubmit ) {
        /*
            Provide local access to the DOM node(s) that called the plugin,
            as well local access to the Validate It and default options.
        */
        this.element = element;
        this._name = pluginName;
        this._defaults = $.fn.validateIt.defaults;
        /*
            The "$.extend" method merges the contents of two or more objects,
            and stores the result in the first object. The first object is
            empty so that we don't alter the default options for future
            instances of the plugin.

            More: http://api.jquery.com/jquery.extend/
        */
        this.options = options;
        this.isSubmit = isSubmit;
        this.success = false;
        this.formHeight = $(this.element).parent().innerHeight();
        /*
            The "init" method is the starting point for all plugin logic.
            Calling the init method here in the "Plugin" constructor function
            allows us to store all methods (including the init method) in the
            plugin's prototype. Storing methods required by the plugin in its
            prototype lowers the memory footprint, as each instance of the
            plugin does not need to duplicate all of the same methods. Rather,
            each instance can inherit the methods from the constructor
            function's prototype.
        */
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {

        // Initialization logic
        init: function () {
            /*
                Create additional methods below and call them via
                "this.myFunction(arg1, arg2)", ie: "this.buildCache();".

                Note, you can cccess the DOM node(s), Validate It, default
                plugin options and custom plugin options for a each instance
                of the plugin by using the variables "this.element",
                "this._name", "this._defaults" and "this.options" created in
                the "Plugin" constructor function (as shown in the buildCache
                method below).
            */
            
            this.buildCache();
            this.bindEvents();
        },

        // Remove plugin instance completely
        destroy: function() {
            /*
                The destroy method unbinds all events for the specific instance
                of the plugin, then removes all plugin data that was stored in
                the plugin instance using jQuery's .removeData method.

                Since we store data for each instance of the plugin in its
                instantiating element using the $.data method (as explained
                in the plugin wrapper below), we can call methods directly on
                the instance outside of the plugin initalization, ie:
                $('selector').data('plugin_validateIt').validator();

                Consequently, the destroy method can be called using:
                $('selector').data('plugin_validateIt').destroy();
            */
            this.unbindEvents();
            this.$element.removeData();
        },

        // Cache DOM nodes for performance
        buildCache: function () {
            /*
                Create variable(s) that can be accessed by other plugin
                functions. For example, "this.$element = $(this.element);"
                will cache a jQuery reference to the elementthat initialized
                the plugin. Cached variables can then be used in other methods. 
            */
            this.$element = $(this.element);
        },

        // Bind events that trigger methods
        bindEvents: function() {
            var plugin = this;
            
            /*
                Bind event(s) to handlers that trigger other functions, ie:
                "plugin.$element.on('click', function() {});". Note the use of
                the cached variable we created in the buildCache method.

                All events are namespaced, ie:
                ".on('click'+'.'+this._name', function() {});".
                This allows us to unbind plugin-specific events using the
                unbindEvents method below.
            */
           
            //checking to see if click handler should be on button or a-link or input type button
            plugin.$element.on('click'+'.'+plugin._name, function(e) {
                /*
                    Use the "call" method so that inside of the method being
                    called, ie: "validator", the "this" keyword refers
                    to the plugin instance, not the event handler.

                    More: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/call
                */
                if(!plugin.isSubmit){
                    e.preventDefault();
                }
                log("configuring form height");
                info("configuring form height: "+plugin.$element.closest($.fn.validateIt.globalOptions.stepsContainerClass).outerHeight());
                var currentStepValidationStatus =  plugin.validator.call(plugin);
                log('exiting validation for current step: status: '+currentStepValidationStatus);
                try{
                    if(!currentStepValidationStatus){
                        plugin.$element.closest('form').css({'height': plugin.$element.closest($.fn.validateIt.globalOptions.stepsContainerClass).outerHeight()});
                        plugin.success = false;
                        if(plugin.isSubmit){
                            $.validateIt.utils.giveAlertMessage('There are errors in the form, Please go through again');
                        }
                        return false;
                    } else{
                        var currentForm = plugin.$element.closest('form');
                        currentForm.css({'height': plugin.$element.closest($.fn.validateIt.globalOptions.stepsContainerClass).next().outerHeight()});
                        plugin.success = true;
                        if(plugin.isSubmit)
                            currentForm.submit();
                        else
                            return true;
                    }
                } catch(e){
                    warn('Error occurred at binding events: '+e);
                }
            });
        },

        // Unbind events that trigger methods
        unbindEvents: function() {
            /*
                Unbind all events in our plugin's namespace that are attached
                to "this.$element".
            */
            this.$element.off('.'+this._name);
        },

        /*
            "validator" is an example of a custom method in your
            plugin. Each method should perform a specific task. For example,
            the buildCache method exists only to create variables for other
            methods to access. The bindEvents method exists only to bind events
            to event handlers that trigger other methods. Creating custom
            plugin methods this way is less confusing (separation of concerns)
            and makes your code easier to test.
        */
        // Create custom methods
        validator: function() {
            var _tagName = this.$element.prop('tagName').toLowerCase().trim();
            var _isAValidTag = false;
            var message = "";
            
            if( (_tagName=="a") || (_tagName=="button") || (_tagName=="input")  ){
                if(_tagName=="input"){
                    var _tagType = this.$element.attr('type');
                    log(_tagType);  
                    if(_tagType===undefined){
                        message = 'not a valid operator to work upon: input tag doesnt have type attribute';
                        _isAValidTag = false;
                    } else if( _tagType.toLowerCase().trim()!='submit' && _tagType.toLowerCase().trim()!='button'){
                        message = 'not a valid operator to work upon: input/button tag is not of type submit';
                        _isAValidTag = false;
                    } else{
                        _isAValidTag = true;
                    }
                } else{
                    _isAValidTag = true;
                }
            }

            // button clicked is an <a>, <button>, input with type: submit/button 
            if(_isAValidTag){
                var fh = new FormHandler();
                var retVal = fh.validateFormElements(this.options);
                
                if(retVal){
                    if(!this.isSubmit){
                        log('calling next step');
                        // change here to send JQuery obj as return val
                        //this.onComplete(this.element); // calling the method to perform, i.e. animating next step
                        $.validateIt.multiStepFormExecutor.moveNext(null);
                    }
                    return true;
                } else {
                    return false;
                }
            } else{
                log(" : Invalid tag: "+message);
                return false;
            }
            return false;
        },

        callback: function() {
            // Cache onComplete option
            var onComplete = this.options.onComplete;

            if ( typeof onComplete === 'function' ) {
                /*
                    Use the "call" method so that inside of the onComplete
                    callback function the "this" keyword refers to the
                    specific DOM node that called the plugin.

                    More: https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Function/call
                */
                onComplete.call(this.element);
            }
        }

    });

    /*
        Create a lightweight plugin wrapper around the "Plugin" constructor,
        preventing against multiple instantiations.

        More: http://learn.jquery.com/plugins/basic-plugin-creation/
    */
    $.fn.validateIt = function ( options  ) {
        var p = null;                       // object to hold data for each 'this' element
        var counter = 0;                    // counter to process multiple steps
        var self = this;                    
        log('Initialization...');
        // initializing MultiStepFormExecutor to set text of progressbar and follow ups 
        $.validateIt.multiStepFormExecutor = new MultiStepFormExecutor();

        this.each(function(a) {

            if ( ! ( p = $.data( this, "plugin_" + pluginName ) ) ) {
                /*
                    Use "$.data" to save each instance of the plugin in case
                    the user wants to modify it. Using "$.data" in this way
                    ensures the data is removed when the DOM element(s) are
                    removed via jQuery methods, as well as when the userleaves
                    the page. It's a smart way to prevent memory leaks.

                    More: http://api.jquery.com/jquery.data/
                */
               // setting all 'this'(next) elements, options.length-1 formValidation objects will be scanned since the
               // plugin works in $('.next') selector which will always be 1 less than the form validation objects               // array
                if($.isArray(options) && (options.length > 1) ){
                    log('setting up next('+counter+') button');
                    $.data( this, "plugin_" + pluginName, new Plugin( this, options[counter++] , false) );
                    
                    log('setting up corresponding previous('+counter+') button');
                    // prepare all previous elements pairing to next
                    $(this).prev($.validateIt.globalOptions.btnPreviousClass).click(function(e){
                        $.validateIt.multiStepFormExecutor.movePrev(e, null);
                    });
                } else if($.isArray(options)){
                    log('setting up submit button with css ID: '+$.validateIt.globalOptions.btnSubmitID );
                    // setting the form as single step submit form but if validation obj is provided as array of a single object
                    $.data( this, "plugin_" + pluginName, new Plugin( this, options[0] , true) );
                } else {
                    log('setting up submit button');
                    // setting the form as single step submit form with validation object provided as an object only
                    $.data( this, "plugin_" + pluginName, new Plugin( this, options , true) );
                }
                p = $.data( this, "plugin_" + pluginName );
            }
        });

        // setting up the submit button with btnSubmitID
        log('setting up submit button');
        $.data( this, "plugin_" + pluginName, new Plugin( $($.validateIt.globalOptions.btnSubmitID), options[options.length-1] , true) );

        if($.isArray(options) && (options.length > 1) ){
            log('setting up corresponding previous button with submit button of the form');
            // prepare previous element pairing to submit always with btnSubmitID 
            $($.validateIt.globalOptions.btnSubmitID).prev($.validateIt.globalOptions.btnPreviousClass).click(function(e){
                $.validateIt.multiStepFormExecutor.movePrev(e, null);
            });
        }
        $(this).closest('form').css({'height': $('steps').eq(0).outerHeight()});
        info('setting form height: ' + $(this).parentsUntil($.fn.validateIt.globalOptions.stepsContainerClass).parent().outerHeight()+'px');
        
        // setting up the click event for progressBar items
        $($.fn.validateIt.globalOptions.progressBarID+' li').click(function(e){
            var currIndex = $(this).index();
            log('progressbar button #'+(currIndex+1)+' click');
            if(currIndex > $.validateIt.multiStepFormExecutor.currentStepIndex){
                $.validateIt.multiStepFormExecutor.moveNext(currIndex);
            } else if(currIndex < $.validateIt.multiStepFormExecutor.currentStepIndex){
                $.validateIt.multiStepFormExecutor.movePrev(e, currIndex);
            }
        });

        log('Initialized successfully');
        /*
            "return this;" returns the original jQuery object. This allows
            additional jQuery methods to be chained.
        */
        return this;
    };

    /*
        Attach the default plugin options directly to the plugin object. This
        allows users to override default plugin options globally, instead of
        passing the same option(s) every time the plugin is initialized.

        For example, the user could set the "property" value once for all
        instances of the plugin with
        "$.fn.validateIt.defaults.property = 'myValue';". Then, every time
        plugin is initialized, "property" will be set to "myValue".

        More: http://learn.jquery.com/plugins/advanced-plugin-concepts/
    */
   
    var globalOptions = {
        debug: false,
        btnNextClass: '.next',
        btnPreviousClass: '.previous',
        btnSubmitID: '#submit',
        stepsContainerClass: '.steps',
        progressBarID: '#progressbar',
        parent: 'form-group',
        errorBlock: null,
        errorContainer: 'form-group',
        property: 'value',
    };


    var settings = {
        formElements: null,        
        onComplete: null
    };

    $.fn.validateIt.defaults = settings; 
    $.fn.validateIt.globalOptions = globalOptions; 

    $.validateIt = function(options){
        $.extend($.fn.validateIt.globalOptions, options);
    };
    ////////////////////////////////////////////////////////////////////////////////////////////
    // Below are some helper functions to this library, you only wana look up to set defaults //
    ////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * defined below is the legacy ECMAScript 5 API for creating classes and subclasses
     */
    

    /**
     * inherit() returns a newly created object that inherits properties from the
     * prototype object p. It uses the ECMAScript 5 function Object.create() if
     * it is defined, and otherwise falls back to an older technique
     * */
     
    function inherit(p){
        if(p == null) throw TypeError();    // p must be a non-null object
        if(Object.create)                   // if Object.create() is defined
            return Object.create(p);        //      then just use it
        var t = typeof p;                   // otherwise do some more type checking
        if(t !== "object" && t !== "function")  throw TypeError();
        function f(){};
        f.prototype = p;
        return new f();
    }

    
    function defineSubclass(superclass,       // Constructor of the superclass
                            constructor,    // The constructor for the new Subclass
                            methods,        // Instance method: copied to prototype
                            statics)        // Class properties: copied to constructor
    {

        // Set up the prototype object of the subclass
        constructor.prototype = inherit(superclass.prototype);
        constructor.prototype.constructor = constructor;
        // Copy the methods and statistics as we would for a regular class
        if(methods) $.extend(constructor.prototype, methods);
        if(statics) $.extend(constructor, statics);
        // Return the class
        return constructor; 
    }

    
    Function.prototype.extend = function(constructor, methods, statics){
        return defineSubclass(this, constructor, methods, statics);
    };

    // A conveniet function that can be used for any abstract method
    function abstractMethod(){ throw new Error('abstract method'); }

    /**
     * The Abstract class from which all the classes would be derived
     */
    
    function AbstractClass() { throw new Error("Can't instantiate abstract classes"); }
    AbstractClass.prototype.contains = abstractMethod;

    /**
     * Base class will be a abstract sublass of AbstractClass, having some basic functions
     */
    
    var BaseClass = AbstractClass.extend(
        function() {throw new Error("Can't instantiate abstract classes"); },
        {
              toString: function() {
                return (this.type ? this.type + ": ":'') +
                       (this.name ? this.name + ": ":'');
              },
              log: function() {
                if(!$.validateIt.globalOptions.debug) return;
                log.apply(this, Utils.appendArg(arguments, this.toString()));
              },
              warn: function() {
                warn.apply(this, Utils.appendArg(arguments, this.toString()));
              },
              info: function() {
                info.apply(this, Utils.appendArg(arguments, this.toString()));
              }
        }

    );

    var ValidationElement = BaseClass.extend(
        function ValidationElement(formElementID, rule, errorBlock){

            this.type = "ValidationElement";
            this.name = formElementID;  
            this.parent = null;
            this.formElement = null;
            this.processedSuccessfully = false;
            this.elementType = null;
            this.rules = new Object();
            var obj =  null;
            var self = this;

            if(!formElementID.length || $.type(formElementID)==='undefined') {
                this.warn("Can't instantiate without a formElement reference");
                throw new Error("Stopped at processing formElement");
            }
            if(!rule.length || $.type(rule)==='undefined') {
                this.warn("Can't instantiate without a formElement reference");
                throw new Error("Can't instantiate without a rule");
            }
            
            this.formElement = $("input[name='"+formElementID+"']");
            if( $.type(this.formElement.prop('tagName')) === 'undefined'){
                this.formElement = $("select[name='"+formElementID+"']");
            } 
            if( $.type(this.formElement.prop('tagName')) === 'undefined') {
                this.formElement = $("textarea[name='"+formElementID+"']");
            }
            if(!this.formElement.length ){
                this.warn("No element found: "+formElementID);
                throw new Error("Can't instantiate without a reference");
            }
            //type check here to get successful <select> and throw error if not

            this.parent = this.formElement.parent();
            while(!this.parent.hasClass(globalOptions.errorContainer)){
                this.parent = this.parent.parent();
            }

            //splitting the rules by ':' and then adding the properties
            $.each(rule, function(index, rule){
                var s = rule.split(':');
                obj = {};
                obj.name = $.trim(s[0]);
                obj.length = (1 in s)? parseInt(s[1]) : 0;
                
                // setting rule specific properties 
                if(obj.name == "min"){
                    obj.minLength = s[1];
                } else if(obj.name == "max"){
                    obj.maxLength = s[1];
                } else if(obj.name == "dateRange"){
                    if(s.length == 2){
                        info("Initializing rule: "+obj.name+" : element: "+this.name+" : 2 parameter given, setting the start date");
                        obj.startDate = s[1];                        
                    } else if(s.length == 3){
                        info("Initializing rule: "+obj.name+" : element: "+this.name+" : 3 parameter given, setting the start and end date");
                        obj.startDate = ( s[1]=="null" )? null: s[1];
                        obj.endDate = s[2];                        
                    }
                }
                obj.status = false;
                obj.message = '';
                self.rules[ obj.name ] = obj;
            });
            
            
            log("error Block specified for "+this.getName()+", using specfied block: " + errorBlock);
            var errorBlockType = $.type(errorBlock);
            if(errorBlockType==='undefined' || errorBlockType==='null' || !errorBlock.length) {
                info("No error Block specified for "+this.getName()+", using default block: " + $.validateIt.errorBlock);
                this.errorBlock = $.validateIt.errorBlock;
            } else {
                log("error Block specified for "+this.getName()+", using specfied block: " + errorBlock);
                this.errorBlock = errorBlock;
            }
        },
        {
            getName: function() { return this.name; },
            getFormElement: function() { return this.formElement; },
            getParent: function(){ return this.parent; },
            getRules: function(){ return this.rules; },
            getRuleStatus: function(ruleName){ return this.rules[ruleName].status; },
            setRuleStatus: function(ruleName, status){ this.rules[ruleName].status = status; },
            getRuleLength: function(ruleName){ return this.rules[ruleName].length; },
            getRule: function(ruleName){ return this.rules[ruleName]; },
            getRuleMessage: function(ruleName){ return this.rules[ruleName].message; },
            setRuleMessage: function(ruleName, msg){ return this.rules[ruleName].message = msg; },
            getMinLength: function(){ return this.rules['min'].minLength; },
            getMaxLength: function(){ return this.rules['max'].maxLength; },
            getDateRangeStart: function(){ return this.rules['dateRange'].startDate; },
            getDateRangeEnd: function(){ return this.rules['dateRange'].endDate; },
            getErrorBlock: function(){ return this.errorBlock; },
            setStatus: function(status){ if($.type( new Boolean() ) === "boolean" && status) this.processedSuccessfully = true; },
            isOkay: function(){ return this.processedSuccessfully; },
            getElementType: function(){ 
                var type = null, tag;
                type = this.getFormElement().attr('type');
                
                if($.type(type) !== "undefined" && this.getFormElement().prop('tagName').toLowerCase()=="input");
                else if($.type(type)==="undefined")  type = this.getFormElement().prop('tagName').toLowerCase();
                else return null;
                
                return type;
            }
        }
    );

    var Rule = BaseClass.extend(
        function Rule(name, userObj){
                this.type = "Rule";
                this.name = name; 

                if(!$.isPlainObject(userObj)) {
                    this.warn("rule definition must be a function or an object");
                    throw new Error("Stopped at processing Rule");
                }

                //handle object.fn
                if(userObj.hasOwnProperty('fn') && $.isFunction(userObj.fn)) {

                  //create ref on the rule, 
                  //function should return true ya false
                  this.fn = userObj.fn;
                  this.message = null;
                //handle object.regexp
                } else if($.type(userObj.regex) === "regexp") {
                  
                  this.regex = userObj.regex;
                  this.message = userObj.message;
                  //build regex function
                  this.fn = (function(regex) {
                    return function(r) {
                      var re = new RegExp(regex);
                      if(!r.getFormElement().val().match(re))
                        return this.message || "Invalid Format";
                      return true;
                    };

                  })(userObj.regex);

                }
        }, 
        {
            getRuleName: function() { return this.name; },
            getRulefn: function() { return this.fn; }
        }
    );

    var ErrorHandler = BaseClass.extend(
        function ErrorHandler(){
            this.type = "ErrorHandler";
        },
        {
            addErrorMessage: function(formElement, msg){
                var span = $('<span />', {'id':'validationErrorRequired', 'class': 'bg-danger help-block' }).text(msg);
                var elementParent = formElement.getParent();
                var block = formElement.getErrorBlock();
                var errorBlockType = $.type(block);
                
                this.info('type of errorBlock: '+errorBlockType+" : "+block);
                if(errorBlockType==='undefined' || errorBlockType==='null' || ( errorBlockType==='string' && !block.length ) ) {
                    var errorBlockLength = elementParent.find('#validationErrorRequired').length;    
                    this.info('#validationErrorRequired length: '+errorBlockLength);
                    if(errorBlockLength==0) {
                        elementParent.append(span);
                    } else if(errorBlockLength==1){
                        elementParent.find('#validationErrorRequired').text(msg);
                    } 
                } else{
                    
                    this.info('error block specified' + block);
                    var errorBlockContainer = elementParent.find('#'+block);
                    var errorBlockLength = errorBlockContainer.find('#validationErrorRequired').length;
                    this.info('error block specified: length: ' + errorBlockLength);
                    if(errorBlockLength == 0) {
                        this.info('setting errorBlock');
                        errorBlockContainer.append(span);
                    } else if (errorBlockLength == 1 ){
                        errorBlockContainer.find('#validationErrorRequired').text(msg);
                    }
                }
            },
            removeErrorMessage: function(formElement){
                var span = '#validationErrorRequired';
                var elementParent = formElement.getParent();
                var block = formElement.getErrorBlock();
                var errorBlockType = $.type(block);
                if(errorBlockType!=='undefined' || errorBlockType!=='null' || ( errorBlockType==='string' && !block.length ) ) {
                    if(elementParent.find('#validationErrorRequired').length>0) elementParent.find(span).remove();
                } else{
                    if(elementParent.find('#'+block).find('#validationErrorRequired').length>0) elementParent.find('#'+block).find(span).remove();
                }
            },
            addErrorIndicator: function(formElement){
                var parent = formElement.getParent();
                if(parent.find('.glyphicon-ok').length > 0){
                    parent.find('.glyphicon-ok').remove();
                }
                if(parent.find('.glyphicon-remove').length == 0){
                    parent.append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                }
                this.informError(formElement);
            },
            informError: function (formElement){
                var parent = formElement.getParent();
        
                if(parent.hasClass('has-success')){
                    parent.removeClass('has-success');
                }
                parent.addClass('has-error').addClass('has-feedback');
            },
            addSuccessIndicator: function (formElement){
                var parent = formElement.getParent();
                if(parent.find('.glyphicon-remove').length > 0){
                    parent.find('.glyphicon-remove').remove();
                }
                if(parent.find('.glyphicon-ok').length == 0){
                    parent.append('<span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');
                }
                this.informSuccess(formElement);
            }, 
            informSuccess: function (formElement){
                var parent = formElement.getParent();
                if(parent.hasClass('has-error')){
                    parent.removeClass('has-error');
                }
                parent.addClass('has-success').addClass('has-feedback');
            }
        }
    );


    var MultiStepFormExecutor = BaseClass.extend(
        function MultiStepFormExecutor(){
            this.type = 'MultiStepFormExecutor';

            this.totalSteps = $($.fn.validateIt.globalOptions.stepsContainerClass).size();
            this.currentStepIndex = 0;
            this.isAnimating = false;
            this.init();
        },
        {
            init: function(){
                this.setProgressText($($.fn.validateIt.globalOptions.progressBarID+' li').eq(this.currentStepIndex).text());
            },
            moveNext: function(stepIndex){
                var self = this;
                if(self.isAnimating){
                    log("button action on 'next' is returned.. previous still executing");
                    return false;
                }
                self.isAnimating = true;

                
                // taking the current containting div       
                var currentStep = $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex);
                log('current step index: '+self.currentStepIndex);
                var progressItems = $($.fn.validateIt.globalOptions.progressBarID+' li');
                if(!stepIndex && stepIndex!=0 ){
                    log(self.type + ':moveNext: null step');
                    progressItems.eq(++self.currentStepIndex).addClass('active');
                } else if( (self.currentStepIndex) == stepIndex ) {
                    log(self.type + ':moveNext: eq to step: '+stepIndex );
                    progressItems.eq(++self.currentStepIndex).addClass('active');
                } else {
                    log(self.type + ':moveNext: > step');
                    do{
                        progressItems.eq(++self.currentStepIndex).addClass('active'); 
                        $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex).hide();
                    }while(self.currentStepIndex < stepIndex);
                }

                // taking the next sibling div
                var nextStep = $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex);
                log('next step: ');
                log(nextStep);
                stepText = $($.fn.validateIt.globalOptions.progressBarID+' li').eq(self.currentStepIndex).text();

                if($('#stepText').hasClass('slideDown')){
                    $('#stepText').removeClass('slideDown').show();
                }
                log("Setting up progressBar header text: " + stepText);
                // Incrementing the currentStepIndex
                // Now setting the text in header, after incrementing the stepIndex so that the form number gets updated.
                self.setProgressText(stepText);
                
                nextStep.css({'transform': 'translateX(0%)'});
                $($.fn.validateIt.globalOptions.stepsContainerClass).hide();
                nextStep.show();
                currentStep.animate({opacity: 0}, {
                    step: function(now, mx){
                        var left;
                        transx = (50*(1-now));
                        left = (50*now);
                        opacity = 1-now;
                        
                        currentStep.css({'transform': 'translateX('+transx+'%)'  });
                        nextStep.css({'left': left+'%', 'opacity': opacity});
                    },
                    duration: 800,
                    easing: 'easeInOutCubic',
                    complete: function(){
                        if(stepIndex!=null){    // null check for moveNext fn call when null param applied, for next btn click state
                            var newHeight = nextStep.outerHeight();
                            nextStep.closest('form').css({'height': newHeight});
                        }
                        log('configuring form height for prev btn click: '+ newHeight+' current step: '+self.currentStepIndex);
                        
                        currentStep.hide();
                        self.isAnimating = false;
                    }
                });
            },
            movePrev: function(e, stepIndex){
                var self = this;
                if(self.isAnimating){
                    log("button action on 'previous' is returned.. previous still executing");
                    return false;
                }
                
                self.isAnimating = true;
                // preventing the button click to reload page, we don't want this
                e.preventDefault();
                
                 // taking the current containting div       
                var currentStep = $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex);
                log('current step index: '+self.currentStepIndex);
                var progressItems = $($.fn.validateIt.globalOptions.progressBarID+' li');
    
                if(!stepIndex && stepIndex != 0){
                    log(self.type + ':movePrev: null step');
                    progressItems.eq(self.currentStepIndex--).removeClass('active');
                } else if( (self.currentStepIndex) == stepIndex){
                    log(self.type + ':movePrev: eq step');
                    progressItems.eq(self.currentStepIndex--).removeClass('active');
                } else {
                    log(self.type + ':movePrev: > step');
                    while(self.currentStepIndex > stepIndex){
                        progressItems.eq(self.currentStepIndex--).removeClass('active');
                        $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex).hide();
                    }                        
                }

                // taking the next sibling div
                var previousStep = $($.fn.validateIt.globalOptions.stepsContainerClass).eq(self.currentStepIndex);
                
                stepText = $($.fn.validateIt.globalOptions.progressBarID+' li').eq(self.currentStepIndex).text();
                if($('#stepText').hasClass('slideDown')){
                    $('#stepText').removeClass('slideDown').show();
                }
                log("Setting up progressBar header text: " + stepText);
                
                // Now setting the text in header, after incrementing the stepIndex so that the form number gets updated.
                self.setProgressText(stepText);

                previousStep.css({'transform': 'translateX(0%)'});
                $($.fn.validateIt.globalOptions.stepsContainerClass).hide();
                previousStep.show();
                
                currentStep.animate({opacity: 0}, {
                    step: function(now, mx){
                        transx = (50*(1-now));
                        left = (50*now);
                        opacity = 1-now;
                        
                        currentStep.css({'transform': 'translateX('+transx+'%)'  });
                        previousStep.css({'left': left+'%', 'opacity': opacity});
                    },
                    duration: 800,
                    easing: 'easeInOutCubic',
                    complete: function(){
                        var newHeight = previousStep.outerHeight();
                        previousStep.closest('form').css({'height': newHeight});
                        log('configuring form height for prev btn click: '+ newHeight+' current step: '+self.currentStepIndex);
                        currentStep.hide();
                        self.isAnimating = false;
                    }
                });
            },
            setProgressText: function(stepText){
                //take true for all the data keys and check in the end to see if all the keys are true 
                //so to verify inputs of a section and more forward
                $('#stepText').text(stepText);
                var subText = $('<small />', {'class': 'ph-small'}).text( (this.currentStepIndex+1) + "/" + this.totalSteps);
                $('#stepText').append(subText).addClass('slideDown');
            }
        }
    );

    var FormHandler = BaseClass.extend(
        function FormHandler(){
            this.type = 'FormHandler';

            this.allInputsTrue = [];
            this.formElements = new Object();
            this.isSuccess = true;
            this.errorHandler = new ErrorHandler();
        },
        {
            validateFormElements: function(data){
                
                //take true for all the data keys and check in the end to see if all the keys are true 
                //so to verify inputs of a section and more forward
                log("Validating Form Element: starting");
                var self = this;
                try{
                    $.each(data, function(index, objKey){
                        log("scanning element: " + index);
                        var _formElementID = String(index); //safely typecasting it to a string
                        var _errorBlock = undefined;
                        if(objKey.hasOwnProperty('errorBlock')){
                                _errorBlock = objKey.errorBlock;
                                log('has property block: '+_errorBlock);
                        }
                        var _rule = objKey.rule;
                        try{
                            var formElement = new ValidationElement(_formElementID, _rule, _errorBlock );
                            self.formElements[formElement.getName()] = formElement;
                            self.formElementsHandler.call(self, self.formElements[formElement.getName()])
                        } catch(e){
                            log("Validating Form Element: error: "+e);
                        }
                    });
                } catch(e){
                    log("validation form: error: "+e);
                }
                $.each(this.formElements, function(index, data){
                    if(!data.isOkay()){
                        self.isSuccess = false; 
                        return false;
                    }
                });
                log("validation form: terminating: " + this.isSuccess);
                return self.isSuccess;
            },
            formElementsHandler: function (formElement){

                var _retVal = true;
                var _jqElement = formElement.getFormElement();
                var _msg = "";
                var self = this;
                var elementTagName = formElement.getFormElement().prop('tagName').toLowerCase();
                
                $.each(formElement.getRules(), function(ruleName, ruleObj){
                    info('element Name: '+formElement.getName()+' :rule: '+ruleName);
                }); 
                
                $.each(formElement.getRules(), function(ruleName, ruleObj){
                    info('element Name: '+formElement.getName()+' : applying rule: '+ruleName);
                    if(!self.validationHandler.call(self, formElement, ruleName)){
                        info('element Name: '+formElement.getName()+' :rule: '+ruleName + " : returned false");
                        return false;
                    }
                    info('element Name: '+formElement.getName()+' :rule: '+ruleName + " : returned true");
                });

                try{
                    info("Accessing Element's error indicator setter: starting");
                    $.each(formElement.getRules(), function(index, rule){
                        info("Element("+formElement.getName()+") validation status: " + formElement.getRuleStatus(index));
                        info("Element("+formElement.getName()+") value captured: " + formElement.getFormElement().val());
                        var ruleRequiredType = $.type(formElement.getRule('required'));
                        if( (ruleRequiredType==='undefined') && (formElement.getFormElement().val().length > 0 ) ) {
                            if(!formElement.getRuleStatus(index)){
                                
                                var check = formElement.getRuleMessage(index);
                                info("Element("+formElement.getName()+") Rule Error Message: "+check);
                            
                                if($.type(check)=='string'){
                                    try{
                                        self.errorHandler.addErrorMessage(formElement, check);
                                    } catch(e){
                                        log("Accessing Element's error indicator setter: error: "+e);
                                    }
                                    if( ( elementTagName ==='textarea' || elementTagName ==='input') && formElement.getElementType().toLowerCase()!=='radio' ){
                                        self.errorHandler.addErrorIndicator(formElement);
                                    } else if( formElement.getElementType()==='select' ) {
                                        self.errorHandler.informError(formElement);
                                    }
                                }

                                _retVal = false;
                                return false;
                            } 
                            
                        } else {

                            if( (ruleRequiredType!=='undefined') ) { 
                                if(!formElement.getRuleStatus(index)){
                                    
                                    var check = formElement.getRuleMessage(index);
                                    info("Element("+formElement.getName()+") Rule Error Message: "+check);
                                
                                    if($.type(check)=='string'){
                                        try{
                                            self.errorHandler.addErrorMessage(formElement, check);
                                        } catch(e){
                                            self.log(e);
                                        }
                                        
                                        if( ( elementTagName ==='textarea' || elementTagName ==='input') && formElement.getElementType().toLowerCase()!=='radio' ){
                                            self.errorHandler.addErrorIndicator(formElement);
                                        } else if( formElement.getElementType()==='select' ) {
                                            self.errorHandler.informError(formElement);
                                        }
                                    }

                                    _retVal = false;
                                    return false;
                                } 
                          }   
                        }
                        
                    });
                } catch(e){
                    log("Accessing Element's error indicator setter: error: "+e);
                }

                log("Accessing Element's error indicator setter: status: " + _retVal);
                if(_retVal){
                    self.errorHandler.removeErrorMessage(formElement);
                    if(( elementTagName ==='textarea' || elementTagName ==='input') && formElement.getElementType().toLowerCase()!=='radio' ) {
                        self.errorHandler.addSuccessIndicator(formElement);
                    } else if( formElement.getElementType()==='select' ) {
                        self.errorHandler.informSuccess(formElement);
                    }
                    formElement.setStatus(true);
                }
            }, 
            validationHandler: function(r, ruleName){
                var retVal = false;
                try{
                    var rr = $.validateIt.getRawRules();
                    var rule = new Rule(ruleName, $.validateIt.getRule(ruleName));
                    var check = rule.fn(r);
                    
                    info('status for rule('+ruleName+') applied on element('+r.getName()+'): '+check);

                    if($.type(check)==='boolean' && check){
                        r.setRuleStatus(ruleName, true);
                        r.setRuleMessage(ruleName, '');
                        retVal = true;
                    } else if($.type(check)=='string'){
                        r.setRuleStatus(ruleName, false);
                        r.setRuleMessage(ruleName, check);
                        retVal = false;
                    }

                } catch(e){
                    warn("processing element: Error: "+e);
                }
                log("processing element status: " + retVal);
                return retVal;
            }
        }
    );
    
    /**
    * Form Default settings
    * Right now includes only one var obj as the obj of class MultiStepFormExecutor
    */

    
    $.extend($.validateIt, {
      version: VERSION,
      addRule: ruleManager.addRule,
      updateRule: ruleManager.updateRule,
      getRule: ruleManager.getRule,
      getRawRules: ruleManager.getRawRules,
      globalOptions: $.fn.validateIt.globalOptions,
      defaults: settings,
      multiStepFormExecutor: null,
      utils: Utils
    });

    $.validateIt.addRule({
        required: {
            fn: function(r){
                var element = r.getFormElement();
                var eleType = r.getElementType();
                if($.type(eleType)==="null") return 'There is no type for this element to check for this field';

                if(eleType=='radio'){
                    if(!element.is(':checked')) {
                        return 'Please select one radio option.';
                    }
                } else {
                    var value = element.val().toLowerCase();
                    if(eleType=="select"){
                        if(value=="invalid") {
                            return 'Please select one option';
                        }
                    } else {
                        if(!value.length) {
                            return 'This field is required';
                        }
                    }

                }
                return true;
            }
        },
        email: {
          regex: /^(([^<>()\[\]\\.,;:\s@\"]+(\.[^<>()\[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
          message: "Invalid email address"
        },
        number: {
          regex: /^[+-]?\d+(\.\d+)?$/,
          message: "Use digits only"
        },
        alphaNumeric: {
          regex: /^[0-9A-Za-z ]+$/,
          message: "Use digits & letters only"
        },
        alphaNumericStrict: {
            regex: /^(?![0-9 ]*$)[a-zA-Z0-9 ]+$/,
            message: "Cannot start with numbers, use only alpha numeric characters" 
        },
        alphaDash: {
          regex: /^(?![0-9 ]*$)[0-9A-Za-z ().\-]+$/,
          message: "Use digits, letters & '-' only "
        },
        name: {
          regex: /^[A-Za-z ',.\-]+$/,
          message: "Use digits, letters & special character which are -, ', . only "
        },
        designation: {
          regex: /^[0-9A-Za-z ',."()\-]+$/,
          message: "Use digits, letters & special character which are -, ', . only "
        },
        address: {
          regex: /^[ 0-9A-Za-z,.():#/\-]+$/,
          message: "Use digits, letters & '-' only "
        },
        text: {
          regex: /^(?![0-9 ]*$)[0-9A-Za-z :"@#&',.\\()'\-]+$/,
          message: "Use digits, letters & special characters which are :, ', \",@ , #, \,. , (), - "
        },
        min: {
            fn: function(r) {
                var element = r.getFormElement();
                var eleType = r.getElementType();

                if($.type(eleType)==="null") return 'There is no type for this element to check for this field';

                var v = element.val(), min = parseInt(r.getMinLength(), 10);
                if(v.length < min)
                    return "Must be at least " + min + " characters";
                return true;
            }
        },
        max: function(r) {
            var element = r.getFormElement();
            var eleType = r.getElementType();

            if($.type(eleType)==="null") return 'There is no type for this element to check for this field';

            var v = element.val(), max = parseInt(r.getMaxLength(), 10);
            if(v.length > max)
               return "Must be at most " + max + " characters";
            return true;
        },
        date: {
            fn: function(r) {
                var element = r.getFormElement();
                var eleType = r.getElementType();

                if($.type(eleType)==="null") return 'There is no type for this element to check for this field';
                
                var d = $.validateIt.utils.parseDate(element.val());
                if(!$.validateIt.utils.validateDate(d))
                        return "Invalid Date";
                return true;
                
              }
        },
        dateRange: {
            fn: function(r) {
                var element = r.getFormElement();
                var eleType = r.getElementType();

                if($.type(eleType)==="null") return 'There is no type for this element to check for this field';

                var start = r.getDateRangeStart(),
                  end = r.getDateRangeEnd(),
                  inputDate = element.val(),
                  endDate = null,
                  startDate = null;

                
                if(start==null)
                    r.warn("Missing start date, skipping that...");
                else {
                    startDate = $.validateIt.utils.parseDate(start);
                    if(!$.validateIt.utils.validateDate(startDate))
                        return "Invalid Start Date " + startDate;
                }
                if(end==null)
                    r.warn("Missing end date, skipping that...");
                else {
                    endDate = $.validateIt.utils.parseDate(end);
                    if(!$.validateIt.utils.validateDate(endDate))
                        return "Invalid End Date";
                }
                var inputGiven = $.validateIt.utils.parseDate(inputDate);
                if(!$.validateIt.utils.validateDate(inputGiven))
                        return "Invalid Entered Date";

                if(startDate >= endDate && startDate!=null && endDate!=null)
                   return "Start Date must come before End Date in the rule specified";
                else if(start!=null && end==null){ 
                    if( (inputGiven >= startDate) )
                        return true;
                    return "Invalid Date, entered date should be after "+startDate;
                }
                else if(start==null && end!=null){ 
                    if( (inputGiven <= endDate))
                        return true;
                    return "Invalid Date, entered date should be before "+endDate;
                }
                else if(start!=null && end!=null){ 
                    if( (inputGiven >= startDate) && (inputGiven <= endDate))
                        return true;
                    return "Invalid Date, entered date should be between "+startDate+" and "+endDate;
                } else {
                    return "Invalid parameters given in the rule";
                }
            }
        }
    });

})( jQuery, window, document );