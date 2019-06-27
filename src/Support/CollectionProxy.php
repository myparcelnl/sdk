<?php declare(strict_types=1);

namespace MyParcelNL\Sdk\src\Support;

if (class_exists('\Illuminate\Support\Collection')) {
    /** @noinspection PhpUndefinedNamespaceInspection @noinspection PhpUndefinedClassInspection */
    class CollectionProxy extends \Illuminate\Support\Collection {}
} else {
    class CollectionProxy {}
}
