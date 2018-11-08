<?php

namespace MyParcelNL\Sdk\src\Support;

if (class_exists('\Illuminate\Support\Collection')) {
    class CollectionProxy extends \Illuminate\Support\Collection {}
} else {
    class CollectionProxy extends \MyParcelNL\Sdk\src\Support\Collection {}
}
