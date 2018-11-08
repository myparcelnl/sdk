<?php

namespace MyParcelNL\Sdk\src\Support;

if (class_exists('\Illuminate\Support\Collection')) {
    /** @noinspection PhpUndefinedClassInspection */
    /** @noinspection PhpUndefinedNamespaceInspection */
    class CollectionProxy extends \Illuminate\Support\Collection {}
} else {
    /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
    class CollectionProxy extends \MyParcelNL\Sdk\src\Support\Collection {}
}
