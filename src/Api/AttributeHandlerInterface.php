<?php

namespace ScandiPWA\CmsGraphQl\Api;

interface AttributeHandlerInterface {
    public function resolve(string $value): string;
}