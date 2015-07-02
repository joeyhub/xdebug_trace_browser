General:

This software includes ExtJS v4 with parts of the desktop example and is licensed under GPL v3.

See: https://www.sencha.com/legal/gpl/

Third party libs may also be included with this. Please read their licenses carefully.

This is a first proof of concept version of an xdebug trace browser.

It is not safe to install this on a public web server.

Only tested in PHP 5.4.

Experimental.

Purpose:

* This tool can provide inspiration on how to improve PHP debugability.
* This tool may provide an initial start for a webbased interface to xdebug in general that significantly improves accessibility to its features.

Rationale:

Using xdebug facilities with an IDE, etc can be tricky.

A webbased tool can have significant advantages in terms of access to resources.

It can directly access trace files produces by xdebug on a server with no transfere mechanism necessary.

It can also directly access the relevant source files with far less path related issues (though analysising something after an update can cause skew).

For real time debugging, if this were added, networking and firewall rules as well as IDE setup would not be needed. node-inspector is recommended as inspiration for what a PHP debugger might look like.

Possible Roadmap:

* Use simpler frontend than ExtJS. This was not chosen for any significant technical reason. It was chosen to review the library in general.
* Implement more on demand operations rather than loading entire datasets.
* Support partial traces (that start and end on depths other than 0).
* Clean up structure and remove mysterious oddities causes by copying and stripping down years old pieces of code to get th first prototype working.
* Make packagable with composer, etc and use this for fetching dependencies. Similar for JS.
* Add support for real time debugging. May need modification of the xdebug extension.
* Use computer readable trace format.
