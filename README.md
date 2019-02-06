# ptlis/php-serialized-data-editor

Tools for manipulating PHP serialized datastructures without deserializing and serializing the data.

This is useful in situation where you don't or can't have the classe definitions loaded (e.g. when processing arbritary data in a DB, Redis etc).