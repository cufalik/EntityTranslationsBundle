<?php

namespace VM5\EntityTranslationsBundle\Tests\Guesser;

use PHPUnit\Framework\TestCase;
use VM5\EntityTranslationsBundle\Guesser\GuesserLoader;
use VM5\EntityTranslationsBundle\Translator;

class GuesserLoaderTest extends TestCase
{
    public function testLocaleLoad()
    {
        $translator = new Translator('en');

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser('fi', []),
            ]
        );

        $guesserLoader->load();

        $this->assertEquals('fi', $translator->getLocale());
    }

    public function testFallbackLocalesLoad()
    {
        $translator = new Translator('en');

        $fallbacks = ['fi', 'en', 'bg'];

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser('fi', $fallbacks),
            ]
        );

        $guesserLoader->load();

        $this->assertEquals($fallbacks, $translator->getFallbackLocales());
    }

    public function testNullLocaleLoad()
    {
        $translator = new Translator('en');

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser(null, null),
            ]
        );

        $guesserLoader->load();

        $this->assertEquals('en', $translator->getLocale());
    }

    public function testNullFallbackLocalesLoad()
    {
        $translator = new Translator('en');

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser('fi'),
            ]
        );

        $guesserLoader->load();

        $this->assertCount(0, $translator->getFallbackLocales());
    }

    public function testFirstLocaleLoaded()
    {
        $translator = new Translator('en');

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser('bg', null),
                new StaticGuesser('fi', null),
            ]
        );

        $guesserLoader->load();

        $this->assertEquals('bg', $translator->getLocale());
    }

    public function testFirstFallbackLocalesLoad()
    {
        $translator = new Translator('en');

        $guesserLoader = new GuesserLoader(
            $translator, [
                new StaticGuesser('fi', ['fi', 'en']),
                new StaticGuesser('bg', ['bg', 'en']),
            ]
        );

        $guesserLoader->load();

        $this->assertEquals(['fi', 'en'], $translator->getFallbackLocales());
    }
}