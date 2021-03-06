# Handling Java exceptions

!!! note
    This page document how to deal with real Java exception and not the bridge
    exceptions (like connection failures...). Bridge exception are documented 
    [here](./bridge_connection.md#errors-and-exceptions)  

## JavaException

Exception thrown from the JVM will be converted to a generic `JavaException` class *(Soluble\Japha\Bridge\Exception\JavaException)*
that can be catched like a regular PHP one. To retrieve specific information from
the Java/JVM exception, you can use the following methods: 

- `JavaException::getClassName()` will give you the originating Java exception class name. 
  For example: java.lang.java.lang.NoSuchMethodException, ...
- `JavaException::getStackTrace()` will give you the JVM stacktrace.

!!! tip
    From version 2.4.0, JavaExceptions are logged. See how to inject
    a PSR-3 logger in the [bridge_connection section](./bridge_connection.md)

```php
<?php
use Soluble\Japha\Bridge\Exception;
//...
try {
    
    $javaObject = $ba->java('my.imaginary.JavaObject');
    $javaObject->methodThatThrowsAndException();
    
} catch (Exception\JavaException $e) {    
    echo $e->getMessage();    
    echo $e->getJavaClassName();
    echo $e->getStackTrace();
} 

```

## Extended exceptions

For convenience the following exceptions extends the base `JavaException` class
and can be useful while developping.

### ClassNotFoundException

The `Soluble\Japha\Bridge\Exception\ClassNotFoundException` is a convenient
exception class thrown whenever a Java class is not found:

```php
<?php
use Soluble\Japha\Bridge\Exception;

try {
    $string = $ba->java('java.INVALID.FQCN', "Hello world");
} catch (Exception\ClassNotFoundException $e) {    
    echo $e->getMessage();
    // -> "java.lang.ClassNotFoundException"
    echo $e->getJavaClassName();
    echo $e->getStackTrace();
} 
```

### NoSuchMethodException

The `Soluble\Japha\Bridge\Exception\NoSuchMethodException` is a convenient 
exception class thrown whenever a method does not exists on an object


```php
<?php
use Soluble\Japha\Bridge\Exception;

// Invalid method
try {
    $string = $ba->java('java.lang.String', "Hello world");
    $string->anInvalidMethod();
} catch (Exception\NoSuchMethodException $e) {
    echo $e->getJavaClassName(); 
    // -> "java.lang.NoSuchMethodException" 
    echo $e->getMessage(); 
    // -> Invoke failed: [[o:String]]->anInvalidMethod. Cause: java.lang.NoSuchMethodException: anInvalidMethod()...
    echo $e->getCause(); 
    // -> java.lang.NoSuchMethodException: anInvalidMethod()...   
    echo $e->getStackTrace();
}

```

### NoSuchFieldException

The `Soluble\Japha\Bridge\Exception\NoSuchFieldException` is a convenient 
exception class thrown whenever a property does not exists on an object


```php
<?php
use Soluble\Japha\Bridge\Exception;

// Invalid method
try {
    $string = $ba->java('java.lang.String', "Hello world");
    $string->fieldNotExists = 10;
} catch (Exception\NoSuchFieldException $e) {
    //...
}

```

## BrokenConnectionException

The `Soluble\Japha\Bridge\Exception\BrokenConnectionException` is thrown whenever there's
a communication failure with the bridge (closed unexpectedly, server down in middle of transaction...).
 
This exception might happen in very rare circumstances. Be sure to enable the logger at connection
to keep track on this.
 
!!! tip
    If the `BrokenConnectionException` happens just after the connection, chances
    are that you are not connecting to the bridge but to another service. Please check
    notes [here](./bridge_connection.md#errors_and_exceptions).

