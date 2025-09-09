<?php
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertEquals;

it("should work", function() {
	assertTrue(true);
});

it("should also work", function() {
    assertFalse(false);
});

