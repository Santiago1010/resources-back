<?php

namespace SRLTests;

use SRL\Language\Helpers\Literally;
use SRL\Language\Helpers\ParenthesesParser;

class ParenthesesParserTest extends TestCase
{
    public function testDefault()
    {
        $this->assertEquals(['foo', ['bar'], 'baz'], (new ParenthesesParser('foo (bar) baz'))->parse());

        $this->assertEquals(['foo', ['bar'], 'baz'], (new ParenthesesParser('(foo (bar) baz)'))->parse());

        $this->assertEquals(['foo', ['bar']], (new ParenthesesParser('foo (bar)'))->parse());

        $this->assertEquals([['foo'], 'bar'], (new ParenthesesParser('(foo)bar'))->parse());

        $this->assertEquals(['foo', ['0']], (new ParenthesesParser('foo (0)'))->parse());

        $this->assertEquals([
            'foo', ['bar', ['nested']], 'baz'
        ], (new ParenthesesParser('foo (bar (nested)) baz'))->parse());

        $this->assertEquals([
            'foo boo', ['bar', ['nested'], 'something'], 'baz', ['bar', ['foo foo']]
        ], (new ParenthesesParser('foo boo (bar (nested) something) baz (bar (foo foo))'))->parse());
    }

    public function testEscaping()
    {
        $parser = new ParenthesesParser('foo (bar "(bla)") baz');

        $this->assertEquals(['foo', ['bar', new Literally('(bla)')], 'baz'], $parser->parse());

        $this->assertEquals(['sample', new Literally('foo'), 'bar'], $parser->setString('sample "foo" bar')->parse());

        $this->assertEquals(['sample', new Literally('foo')], $parser->setString('sample "foo"')->parse());

        $this->assertEquals(['bar', new Literally('(b\"la)'), 'baz'], $parser->setString('bar "(b\"la)" baz')->parse());

        $this->assertEquals(['foo', new Literally("ba'r"), 'baz'], $parser->setString('foo "ba\'r" baz')->parse());

        $this->assertEquals([
            'foo', ['bar', new Literally("(b\\'la)")], 'baz'
        ], $parser->setString("foo (bar '(b\\'la)') baz")->parse());

        $this->assertEquals([
            'bar', new Literally('b\\\\'), ['la'], 'baz'
        ], $parser->setString('bar "b\\\" (la) baz')->parse());

        $this->assertEquals([
            new Literally('fizz'), 'and', new Literally('buzz'), ['with'], new Literally('bar')
        ], $parser->setString('"fizz" and "buzz" (with) "bar"')->parse());

        $this->assertEquals([
            'foo \"boo', ['bar', ['nes', new Literally('ted) s"om"')], 'ething'], 'baz', ['bar', ['foo foo']]
        ], (new ParenthesesParser('foo \"boo (bar (nes"ted) s\"om\"")ething) baz (bar (foo foo))'))->parse());
    }

    public function testEmptyStrings()
    {
        $this->assertEquals([], (new ParenthesesParser(''))->parse());
    }
}
