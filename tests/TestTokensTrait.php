<?php

namespace canisunit\tokenStorage;

trait TestTokensTrait
{
    static public function testTokens()
    {
        $tokens = [
            ["pass\0word"],
            [chr(0)],
            ['привет, я multibyte...'],
            ['Qנטשופ צרכנות'],
            ["\x21?+"],
            ["\x21\x3F"],
            [str_repeat("\0**long**\0", 1000)],
            [""],
            [null]
        ];
        $tokens[] = [sha1(uniqid(rand(), true))];
        return $tokens;
    }
}
