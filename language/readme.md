## `me\fru1t\common\language`
Extends the language of PHP by providing features like auto-importing,
standardized naming, and safe parameter methods.

### Elements
#### Autoload
Because PHP is a runtime-parsed language, files containing classes that are used in other files need
to be included via "require" or "include" or either of their "*_once" variations. Introduce
auto-loading, which removes this requirement. By simply structuring your PHP files and folders in
a Java-like manor (ie. a folder for each namespace, with the file name equal to the declared class),
one can tell PHP to automatically "include" a given class.  
  
Use `Autoload::setup` to setup.

#### Param
Interface with PHP's GET and POST global variables in an exception-safe way.

#### Preconditions
High frequency checks and standardized naming.

#### Session
Interfaces with the php session manager to store/retrieve data within a session.  
  
Use `Session::setup` to setup.

#### StandardTime
Easily manipulate timestamps into a standardized format for storage, retrieval, etc.
