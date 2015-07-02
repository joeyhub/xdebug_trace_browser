General:

This software includes ExtJS v4 with parts of the desktop example and is licensed under GPL v3.

See: https://www.sencha.com/legal/gpl/

Third party libs may also be included with this. Please read their licenses carefully.

This is a first proof of concept version of an xdebug trace browser.

It is not safe to install this on a public web server.

Only tested in PHP 5.4 and firefox.

Experimental.

Use cases:

This tool can be useful when dealing with libraries and frameworks in PHP that are highly layered, convoluted and make use of metacoding principles or are general dynamic meaning that mean certain characteristics can only be determined in runtime.

It is great to review what an unfamiliar framework might be doing in general such a bootstrap, etc.

Where as a typical realtime debug process involves running through, adding break points, debug output, etc, this tool allows quick and casual browsing of a program's execution. If seeking out the cause of a bug that may be located anywhere, the speed in which this allows a trace to be browsed can be very useful.

This should be avoided for processes with a very high number of calls however as trace files can include a lot of data.

Purpose:

* This tool can provide inspiration on how to improve PHP debugability.
* This tool may provide an initial start for a webbased interface to xdebug in general that significantly improves accessibility to its features.

Rationale:

Using xdebug facilities with an IDE, etc can be tricky.

A web based tool can have significant advantages in terms of access to resources.

It can directly access trace files produced by xdebug on a server with no transfere mechanism necessary.

It can also directly access the relevant source files with far less path related issues (though analysising something after an update can cause skew).

For real time debugging, if this were added, networking and firewall rules as well as IDE setup would not be needed. node-inspector is recommended as inspiration for what a PHP debugger might look like.

In summary, this tool's key purpose is to make it easy to make use of xdebug.

This library's functionality crosses over somewhat with that of profiler viewers. It should avoid providing profiling functionality and stay focused on debugging.

On a side note, if you just need to see function calls without parameters and extra information, profiles opened with wincachegrind work really well.

Possible Roadmap:

* Use simpler frontend than ExtJS. This was not chosen for any significant technical reason. It was chosen to review the library in general.
* Implement more on demand operations rather than loading entire datasets.
* Support partial traces (that start and end on depths other than 0).
* Clean up structure and remove mysterious oddities caused by copying and stripping down years old pieces of code to get the first prototype working.
* Make packagable with composer, etc and use this for fetching dependencies. Similar for JS.
* Add support for real time debugging. May need modification of the xdebug extension.
* Use computer readable trace format.
* Decide on portability?
* Fixed mixed whitespace.
* Search boxes for traces.
* Replay ability for traces (amusement feature, could also link to output as a third view).
