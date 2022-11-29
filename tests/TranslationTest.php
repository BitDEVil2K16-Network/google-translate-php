<?php

namespace Stichoza\GoogleTranslate\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TranslationTest extends TestCase
{
    public GoogleTranslate $tr;

    public function setUp(): void
    {
        $this->tr = new GoogleTranslate();
    }

    public function testTranslationEquality(): void
    {
        try {
            $resultOne = GoogleTranslate::trans('Hello', 'ka', 'en');
        } catch (Exception) {
            $resultOne = null;
        }
        $resultTwo = $this->tr->setSource('en')->setTarget('ka')->translate('Hello');

        $this->assertEqualsIgnoringCase($resultOne, $resultTwo, 'Salam');
    }

    public function testNewerLanguageTranslation(): void
    {
        try {
            $resultOne = GoogleTranslate::trans('Hello', 'tk', 'en');
        } catch (\ErrorException $e) {
            $resultOne = null;
        }
        $resultTwo = $this->tr->setSource('en')->setTarget('tk')->translate('Hello');

        $this->assertEqualsIgnoringCase($resultOne, $resultTwo, 'Salam');
    }

    public function testUTF16Translation(): void
    {
        try {
            $resultOne = GoogleTranslate::trans('yes 👍🏽', 'de', 'en');
        } catch (Exception) {
            $resultOne = null;
        }
        $resultTwo = $this->tr->setSource('en')->setTarget('de')->translate('yes 👍🏽');

        $this->assertEqualsIgnoringCase($resultOne, $resultTwo, 'ja 👍🏽');
    }

    public function testLargeTextTranslation(): void
    {
        $text = "Google Translate is a multilingual neural machine translation service developed by Google to translate text, documents and websites from one language into another. It offers a website interface, a mobile app for Android and iOS, and an API that helps developers build browser extensions and software applications. As of November 2022, Google Translate supports 133 languages at various levels, and as of April 2016, claimed over 500 million total users, with more than 100 billion words translated daily, after the company stated in May 2013 that it served over 200 million people daily.";

        $output = $this->tr->setTarget('uk')->translate($text);

        $this->assertIsString($output);
        $this->assertNotEquals($text, $output);
    }

    public function testRawResponse(): void
    {
        $rawResult = $this->tr->getResponse('cat');

        $this->assertIsArray($rawResult, 'Method getResponse() should return an array');
    }
}
